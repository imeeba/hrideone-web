#!/usr/bin/env python3
"""Copy the images the blog uses from hrideone.com into playground/media/ (resized, WebP) and point
playground/data/blog-posts.json at those copies, so the demo (Playground) does not depend on the old site.

Only images that the new site uses are copied: covers + images inside the imported articles
(not the old media library). Run by .github/workflows/demo-media.yml (GitHub's runners can reach
hrideone.com; the Claude workspace cannot). Safe to run again: existing files are kept.

  python3 scripts/demo-media.py [--max-edge 1600] [--quality 78] [--base <public URL of playground/media/>]

After re-running scripts/wxr-to-blog.py (which writes the hrideone.com URLs again), run this again.
Needs: pillow (pip install pillow)
"""

import argparse
import gzip
import hashlib
import io
import json
import os
import re
import sys
import time
import urllib.parse
import urllib.request

from PIL import Image, ImageOps

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA = os.path.join(ROOT, 'playground', 'data', 'blog-posts.json')
MEDIA = os.path.join(ROOT, 'playground', 'media')
MANIFEST = os.path.join(MEDIA, 'manifest.json')
DEFAULT_BASE = 'https://cdn.jsdelivr.net/gh/imeeba/hrideone-web@main/playground/media/'
OLD = re.compile(r'https?://(?:www\.)?hrideone\.com/wp-content/uploads/[^"\s)]+')


def local_name(url: str) -> str:
    """uploads/2021/10/Name ไทย.jpg -> 2021/10/name-<hash>.webp (ASCII file names, stable)."""
    path = urllib.parse.unquote(urllib.parse.urlparse(url).path).split('/wp-content/uploads/', 1)[1]
    folder, name = os.path.split(path)
    stem = os.path.splitext(name)[0]
    ascii_stem = re.sub(r'[^a-z0-9]+', '-', stem.lower()).strip('-')
    if ascii_stem != stem.lower() or not ascii_stem:
        ascii_stem = (ascii_stem[:60].strip('-') + '-' if ascii_stem else '') + hashlib.sha1(url.encode()).hexdigest()[:8]
    return f'{folder}/{ascii_stem}.webp'


def fetch(url: str) -> bytes:
    req = urllib.request.Request(url, headers={'User-Agent': 'hrideone-web demo-media (GitHub Actions)'})
    for attempt in range(3):
        try:
            with urllib.request.urlopen(req, timeout=60) as r:
                return r.read()
        except Exception:  # noqa: BLE001 - retried, then reported
            if attempt == 2:
                raise
            time.sleep(2 * (attempt + 1))
    return b''


def convert(raw: bytes, max_edge: int, quality: int) -> tuple[bytes, int, int]:
    im = Image.open(io.BytesIO(raw))
    animated = getattr(im, 'is_animated', False)
    if not animated:
        im = ImageOps.exif_transpose(im)
        if im.mode not in ('RGB', 'RGBA'):
            im = im.convert('RGBA' if 'transparency' in im.info or im.mode in ('LA', 'P') else 'RGB')
        im.thumbnail((max_edge, max_edge), Image.LANCZOS)
    out = io.BytesIO()
    im.save(out, 'WEBP', quality=quality, method=6, save_all=animated)
    return out.getvalue(), im.width, im.height


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--max-edge', type=int, default=1600)
    ap.add_argument('--quality', type=int, default=78)
    ap.add_argument('--base', default=DEFAULT_BASE)
    args = ap.parse_args()

    with open(DATA, encoding='utf-8') as f:
        text = f.read()
    manifest = {}
    if os.path.exists(MANIFEST):
        with open(MANIFEST, encoding='utf-8') as f:
            manifest = json.load(f)

    urls = sorted(set(OLD.findall(text)))
    print(f'{len(urls)} image URLs from the old site in blog-posts.json')
    failed, done, before, after = [], 0, 0, 0
    for url in urls:
        rel = manifest.get(url, {}).get('file') or local_name(url)
        dest = os.path.join(MEDIA, rel)
        if not os.path.exists(dest):
            try:
                raw = fetch(url)
                data, w, h = convert(raw, args.max_edge, args.quality)
            except Exception as e:  # noqa: BLE001 - keep going, report at the end
                failed.append(f'{url}: {e}')
                continue
            os.makedirs(os.path.dirname(dest), exist_ok=True)
            with open(dest, 'wb') as f:
                f.write(data)
            manifest[url] = {'file': rel, 'width': w, 'height': h, 'bytes': len(data), 'source_bytes': len(raw)}
            done += 1
        before += manifest[url].get('source_bytes', 0)
        after += manifest[url].get('bytes', 0)

    with open(MANIFEST, 'w', encoding='utf-8') as f:
        json.dump(dict(sorted(manifest.items())), f, ensure_ascii=False, indent=1)

    # Point the demo data at the copies (URLs that failed keep pointing at the old site).
    def swap(m):
        item = manifest.get(m.group(0))
        return args.base + item['file'] if item else m.group(0)

    text = OLD.sub(swap, text)
    with open(DATA, 'w', encoding='utf-8') as f:
        f.write(text)
    with open(DATA + '.gz', 'wb') as f:
        f.write(gzip.compress(text.encode('utf-8'), mtime=0))

    print(f'new files: {done} · total {len(manifest)} · {before / 1e6:.1f} MB on the old site -> {after / 1e6:.1f} MB WebP')
    if failed:
        print(f'{len(failed)} failed (still pointing at hrideone.com):')
        print('\n'.join(failed[:50]))
        sys.exit(1 if len(failed) > len(urls) // 10 else 0)


if __name__ == '__main__':
    main()
