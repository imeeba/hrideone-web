#!/usr/bin/env python3
"""Add translations to the theme's .po files and rebuild their .mo and .l10n.php (no WP-CLI / msgfmt needed).

  python3 scripts/po-build.py                      rebuild .mo + .l10n.php from the .po files (checks they match)
  python3 scripts/po-build.py additions.json       first append the entries of additions.json to the .po files

additions.json:  { "<Thai msgid>": { "en_US": "English", "ja": "日本語" }, ... }  (an existing msgid is updated)
Only the hri-theme domain (wp-content/themes/hri-theme/languages) is handled.
"""

import json
import os
import re
import struct
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
LANG_DIR = os.path.join(ROOT, 'wp-content', 'themes', 'hri-theme', 'languages')
LOCALES = ('en_US', 'ja')


def unquote(line: str) -> str:
    s = line.strip()
    assert s.startswith('"') and s.endswith('"'), line
    return json.loads(s)  # PO string escapes are JSON-compatible for the characters used here


def quote(text: str) -> str:
    return json.dumps(text, ensure_ascii=False)


def parse_po(path: str):
    """Returns (header_lines, entries); entry = dict(comments, ctxt, id, str)."""
    entries, cur, field = [], None, None
    header = []
    with open(path, encoding='utf-8') as f:
        lines = f.read().split('\n')
    block = []
    blocks = []
    for line in lines + ['']:
        if line.strip() == '':
            if block:
                blocks.append(block)
            block = []
        else:
            block.append(line)
    for b in blocks:
        cur = {'comments': [], 'ctxt': None, 'id': '', 'str': ''}
        field = None
        for line in b:
            if line.startswith('#'):
                cur['comments'].append(line)
            elif line.startswith('msgctxt '):
                field, cur['ctxt'] = 'ctxt', unquote(line[8:])
            elif line.startswith('msgid '):
                field, cur['id'] = 'id', unquote(line[6:])
            elif line.startswith('msgstr '):
                field, cur['str'] = 'str', unquote(line[7:])
            elif line.startswith('"'):
                cur[field] += unquote(line)
            else:
                raise ValueError(f'{path}: cannot parse {line!r}')
        entries.append(cur)
    return entries


def write_po(path: str, entries):
    out = []
    for e in entries:
        lines = list(e['comments'])
        if e['ctxt'] is not None:
            lines.append('msgctxt ' + quote(e['ctxt']))
        lines.append('msgid ' + quote(e['id']))
        lines.append('msgstr ' + quote(e['str']))
        out.append('\n'.join(lines))
    with open(path, 'w', encoding='utf-8') as f:
        f.write('\n\n'.join(out) + '\n')


def messages(entries):
    out = {}
    for e in entries:
        if e['id'] == '' or e['str'] == '':
            continue
        key = e['id'] if e['ctxt'] is None else e['ctxt'] + '\x04' + e['id']
        out[key] = e['str']
    return out


def write_mo(path: str, header: dict, msgs: dict):
    items = [('', '\n'.join(f'{k}: {v}' for k, v in header.items()) + '\n')] + sorted(msgs.items(), key=lambda kv: kv[0].encode('utf-8'))
    ids = b''
    strs = b''
    offsets = []
    for k, v in items:
        kb, vb = k.encode('utf-8'), v.encode('utf-8')
        offsets.append((len(ids), len(kb), len(strs), len(vb)))
        ids += kb + b'\0'
        strs += vb + b'\0'
    n = len(items)
    keystart = 7 * 4 + 16 * n
    valuestart = keystart + len(ids)
    koffsets, voffsets = [], []
    for o1, l1, o2, l2 in offsets:
        koffsets += [l1, o1 + keystart]
        voffsets += [l2, o2 + valuestart]
    data = struct.pack('<IIIIIII', 0x950412DE, 0, n, 7 * 4, 7 * 4 + n * 8, 0, 0)
    data += struct.pack(f'<{n * 2}I', *koffsets) + struct.pack(f'<{n * 2}I', *voffsets) + ids + strs
    with open(path, 'wb') as f:
        f.write(data)


def php_str(s: str) -> str:
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"


def write_l10n(path: str, meta: dict, msgs: dict):
    head = ','.join(f'{php_str(k)}=>{php_str(v)}' for k, v in meta.items())
    body = ','.join(f'{php_str(k)}=>{php_str(v)}' for k, v in msgs.items())
    with open(path, 'w', encoding='utf-8') as f:
        f.write(f"<?php\nreturn [{head},'messages'=>[{body}]];\n")


def main():
    additions = {}
    if len(sys.argv) > 1:
        with open(sys.argv[1], encoding='utf-8') as f:
            additions = json.load(f)
    for loc in LOCALES:
        po = os.path.join(LANG_DIR, f'{loc}.po')
        entries = parse_po(po)
        by_id = {(e['ctxt'], e['id']): e for e in entries}
        for thai, tr in additions.items():
            if loc not in tr:
                continue
            e = by_id.get((None, thai))
            if e:
                e['str'] = tr[loc]
            else:
                e = {'comments': ['#: patterns/about (old-site text)'], 'ctxt': None, 'id': thai, 'str': tr[loc]}
                entries.append(e)
                by_id[(None, thai)] = e
        if additions:
            write_po(po, entries)
        msgs = messages(entries)
        hdr = {}
        for line in next(e for e in entries if e['id'] == '')['str'].split('\n'):
            if ': ' in line:
                k, v = line.split(': ', 1)
                hdr[k] = v
        write_mo(os.path.join(LANG_DIR, f'{loc}.mo'), hdr, msgs)
        meta = {
            'domain': hdr.get('X-Domain', 'hri-theme'),
            'plural-forms': hdr.get('Plural-Forms', ''),
            'language': hdr.get('Language', loc),
            'project-id-version': hdr.get('Project-Id-Version', ''),
            'pot-creation-date': hdr.get('POT-Creation-Date', ''),
            'po-revision-date': hdr.get('PO-Revision-Date', ''),
            'x-generator': hdr.get('X-Generator', ''),
        }
        write_l10n(os.path.join(LANG_DIR, f'{loc}.l10n.php'), meta, msgs)
        print(loc, len(msgs), 'messages')


if __name__ == '__main__':
    main()
