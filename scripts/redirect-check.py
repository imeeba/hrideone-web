#!/usr/bin/env python3
"""Check the 301 table of the old hrideone.com URLs (hri-core/data/redirects.json) on a running site.

Usage: python3 -I scripts/redirect-check.py [--base http://127.0.0.1:8899/wp]

Every old path x TH/EN/JA must answer exactly one 301 to a page that answers 200 in the same language
(<html lang>), keep its #anchor (the id exists on the page) and the ?utm_ query. 'external:' targets must leave
the site in one hop, '#' entries must stay a 404.
"""
import argparse, json, re, sys, urllib.error, urllib.parse, urllib.request
from pathlib import Path

LANG = {'': 'th', 'en/': 'en', 'ja/': 'ja'}
ROOT = Path(__file__).resolve().parent.parent
DATA = ROOT / 'wp-content/plugins/hri-core/data/redirects.json'


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


opener = urllib.request.build_opener(NoRedirect)


def get(url):
    try:
        r = opener.open(urllib.request.Request(url, headers={'User-Agent': 'redirect-check'}), timeout=60)
        return r.status, r.headers.get('Location', ''), r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get('Location', ''), e.read().decode('utf-8', 'replace')


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--base', default='http://127.0.0.1:8899/wp')
    args = ap.parse_args()
    base = args.base.rstrip('/')
    host = urllib.parse.urlparse(base).netloc
    doc = json.loads(DATA.read_text(encoding='utf-8'))
    bad, n = [], 0
    for old, target in doc['map'].items():
        for prefix, lang in LANG.items():
            for query in ('', '?utm_source=x'):
                n += 1
                label = f'/{prefix}{old}/{query}'
                code, loc, _ = get(f'{base}/{prefix}{old}/{query}')
                if target == '#':
                    if code != 404:
                        bad.append(f'{label}: placeholder must stay 404, got {code} {loc}')
                    continue
                if code != 301:
                    bad.append(f'{label}: expected 301, got {code} {loc}')
                    continue
                if target.startswith('external:'):
                    if urllib.parse.urlparse(loc).netloc in ('', host):
                        bad.append(f'{label}: expected an external URL, got {loc}')
                    continue
                if query and 'utm_source=x' not in loc:
                    bad.append(f'{label}: query lost -> {loc}')
                u = urllib.parse.urlparse(loc)
                if u.netloc != host:
                    bad.append(f'{label}: leaves the site -> {loc}')
                    continue
                code2, loc2, html = get(loc.split('#')[0])
                if code2 != 200:
                    bad.append(f'{label}: {loc} answers {code2} {loc2}')
                    continue
                m = re.search(r'<html[^>]*\blang="([^"]+)"', html)
                got = (m.group(1) if m else '')[:2].lower()
                if got != lang:
                    bad.append(f'{label}: {loc} is in "{got}", expected "{lang}"')
                frag = target.split('#', 1)[1] if '#' in target else ''
                if frag:
                    if not u.fragment.startswith(frag):
                        bad.append(f'{label}: anchor lost -> {loc}')
                    elif not re.search(r'\bid="%s"' % re.escape(frag), html):
                        bad.append(f'{label}: id="{frag}" not found in {loc}')
    print(f'{n} cases, {len(bad)} problems')
    for b in bad:
        print(' -', b)
    return 1 if bad else 0


sys.exit(main())
