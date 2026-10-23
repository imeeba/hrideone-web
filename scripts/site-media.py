#!/usr/bin/env python3
"""Copy the real images of the old hrideone.com pages that the new site's image slots use.

Reads playground/data/site-media.json (built from the crawl: scripts/crawl-images.py), downloads each asset
once, converts it for its job and writes playground/site-media/<id>.webp. The sizes are chosen for fast
display: no file is larger than the slot needs at 2x.

  kind    what it is                         result
  logo    client / partner logo              fit in 240x240, WebP q85 (alpha kept)
  cover   course cover (card + course hero)  center-cropped 3:2, 960x640, q76
  square  portrait (executives, trainers)    square crop around "focus" [x, y] (0-1), 640x640, q80
  photo   photo in a page / gallery          longest edge 1000, q76
  hero    full-width cover background        longest edge 1600, q70

Width, height and file size are written back into site-media.json ("file", "width", "height", "bytes"), plus
contact sheets playground/data/site-media-sheet-N.jpg so the matching can be checked by eye.

The Claude workspace cannot reach hrideone.com; this runs on GitHub (.github/workflows/site-media.yml).
Needs: pillow
"""

import argparse
import concurrent.futures
import io
import json
import os
import urllib.error
import urllib.parse
import urllib.request

from PIL import Image, ImageDraw, ImageFont, ImageOps

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA = os.path.join(ROOT, 'playground', 'data', 'site-media.json')
OUT = os.path.join(ROOT, 'playground', 'site-media')


def fetch(url: str) -> bytes:
    url = urllib.parse.quote(url, safe=':/%?=&')
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (compatible; hrideone-web site-media)'})
    with urllib.request.urlopen(req, timeout=30) as r:
        return r.read()


def load(raw: bytes) -> Image.Image:
    im = ImageOps.exif_transpose(Image.open(io.BytesIO(raw)))
    if im.mode not in ('RGB', 'RGBA'):
        im = im.convert('RGBA' if 'transparency' in im.info or im.mode in ('LA', 'P') else 'RGB')
    return im


def crop_to(im: Image.Image, ratio: float, focus) -> Image.Image:
    """Largest crop with width/height = ratio, centred on focus (fractions of the free space)."""
    fx, fy = focus
    w, h = im.size
    if w / h > ratio:
        cw, ch = round(h * ratio), h
    else:
        cw, ch = w, round(w / ratio)
    x = round((w - cw) * fx)
    y = round((h - ch) * fy)
    return im.crop((x, y, x + cw, y + ch))


def convert(raw: bytes, asset: dict) -> bytes:
    im = load(raw)
    kind = asset['kind']
    if kind == 'logo':
        im.thumbnail((240, 240), Image.LANCZOS)
        q = 85
    elif kind == 'cover':
        im = crop_to(im, 3 / 2, (0.5, 0.5)).resize((960, 640), Image.LANCZOS) if im.width >= 960 else crop_to(im, 3 / 2, (0.5, 0.5))
        q = 76
    elif kind == 'square':
        im = crop_to(im, 1, asset.get('focus', (0.5, 0.3)))
        if im.width > 640:
            im = im.resize((640, 640), Image.LANCZOS)
        q = 80
    elif kind == 'hero':
        im.thumbnail((1600, 1600), Image.LANCZOS)
        q = 70
    else:  # photo
        im.thumbnail((1000, 1000), Image.LANCZOS)
        q = 76
    out = io.BytesIO()
    im.save(out, 'WEBP', quality=q, method=6)
    asset['width'], asset['height'] = im.size
    return out.getvalue()


def work(item):
    key, asset = item
    dest = os.path.join(OUT, f'{key}.webp')
    if os.path.exists(dest) and asset.get('file'):
        return key, asset, None
    last = None
    for url in (asset['src'], asset.get('fallback')):
        if not url:
            continue
        try:
            data = convert(fetch(url), asset)
            with open(dest, 'wb') as f:
                f.write(data)
            asset['file'], asset['bytes'] = f'{key}.webp', len(data)
            asset.pop('missing', None)
            return key, asset, None
        except Exception as e:  # noqa: BLE001 - try the fallback URL, then report
            last = f'{url}: {e}'
    asset['missing'] = True
    return key, asset, last


def contact_sheets(assets: dict):
    items = [(k, a) for k, a in assets.items() if a.get('file')]
    try:
        font = ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf', 15)
    except OSError:
        font = ImageFont.load_default()
    cell, cols, per = 230, 6, 30
    for n in range(0, len(items), per):
        part = items[n:n + per]
        rows = (len(part) + cols - 1) // cols
        sheet = Image.new('RGB', (cols * cell, rows * (cell + 22)), (235, 235, 235))
        d = ImageDraw.Draw(sheet)
        for i, (k, a) in enumerate(part):
            im = Image.open(os.path.join(OUT, a['file'])).convert('RGBA')
            im.thumbnail((cell - 8, cell - 8))
            bg = Image.new('RGBA', im.size, (255, 255, 255, 255))
            bg.alpha_composite(im)
            x, y = (i % cols) * cell + 4, (i // cols) * (cell + 22) + 2
            sheet.paste(bg.convert('RGB'), (x, y))
            d.text((x, y + cell - 4), f'{k} {a["width"]}x{a["height"]}', fill=(200, 0, 60), font=font)
        sheet.save(os.path.join(ROOT, 'playground', 'data', f'site-media-sheet-{n // per + 1}.jpg'), 'JPEG', quality=60)


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--workers', type=int, default=6)
    args = ap.parse_args()
    os.makedirs(OUT, exist_ok=True)
    with open(DATA, encoding='utf-8') as f:
        data = json.load(f)
    assets = data['assets']
    failed = []
    with concurrent.futures.ThreadPoolExecutor(max_workers=args.workers) as pool:
        for key, asset, err in pool.map(work, list(assets.items())):
            assets[key] = asset
            if err:
                failed.append(f'{key}: {err}')
    for old in os.listdir(OUT):  # files of assets that are no longer in the list
        if old.endswith('.webp') and old[:-5] not in assets:
            os.remove(os.path.join(OUT, old))
    with open(DATA, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=1)
    contact_sheets(assets)
    ok = [a for a in assets.values() if a.get('file')]
    total = sum(a['bytes'] for a in ok)
    print(f'{len(ok)}/{len(assets)} assets, {total / 1024:.0f} KB total, median {sorted(a["bytes"] for a in ok)[len(ok) // 2] / 1024:.0f} KB')
    for line in failed:
        print('FAILED', line)


if __name__ == '__main__':
    main()
