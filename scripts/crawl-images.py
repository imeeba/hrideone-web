#!/usr/bin/env python3
"""Crawl the old hrideone.com pages and list every image the page shows (not the media library).

For each page (Thai site, desktop 1280px) it records every <img> (largest srcset / data-src candidate),
CSS background images (inline and computed), <video poster> and <source>, with the on-page position,
alt text, the nearest heading/section and the natural size. It also saves a full-page screenshot with
numbered boxes around each image, cut into chunks, so the matching to the new site's image slots can be
done by looking at the picture instead of guessing from file names.

Output (committed by .github/workflows/crawl-images.yml):
  playground/data/crawl/pages.json            one entry per page with its images
  playground/data/crawl/shots/<page>-<n>.jpg  annotated screenshot chunks (JPEG q55, small)

The Claude workspace cannot reach hrideone.com, so this runs on GitHub's runners.
Needs: playwright (+ chromium), pillow

  python3 scripts/crawl-images.py [--base https://hrideone.com] [--max-pages 70]
"""

import argparse
import io
import json
import os
import re
import sys
import time
import urllib.parse

from PIL import Image, ImageDraw, ImageFont
from playwright.sync_api import sync_playwright

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, 'playground', 'data', 'crawl')
SHOTS = os.path.join(OUT, 'shots')

SEEDS = ['/', '/in-house/', '/public-training/', '/transformation-solutions/', '/japanese-concept/', '/about/', '/contact-us/']
FOLLOW = re.compile(r'^/(courses|in-house|public-training|transformation-solutions|japanese-concept)/[^?#]+', re.I)
IMG_EXT = re.compile(r'\.(jpe?g|png|gif|webp|svg|avif)(\?|$)', re.I)
THUMB = re.compile(r'-\d{2,4}x\d{2,4}(?=\.[a-z]+$)', re.I)  # WordPress size suffix: -1024x576.webp

COLLECT_JS = r"""
() => {
  const abs = (u) => { if (!u) return ''; try { return new URL(u, location.href).href; } catch (e) { return ''; } };
  const biggest = (srcset) => {
    if (!srcset) return '';
    let best = '', w = -1;
    srcset.split(',').forEach((part) => {
      const [u, d] = part.trim().split(/\s+/);
      const n = parseInt(d || '0', 10) || 0;
      if (u && n >= w) { best = u; w = n; }
    });
    return abs(best);
  };
  const label = (el) => {
    let n = el;
    while (n && n !== document.body) {
      const sec = n.closest('section, [data-elementor-type], .elementor-section, .e-con');
      if (!sec) break;
      const h = sec.querySelector('h1, h2, h3');
      if (h && h.textContent.trim()) return h.textContent.trim().replace(/\s+/g, ' ').slice(0, 80);
      n = sec.parentElement;
    }
    return '';
  };
  const inView = (el) => {
    const r = el.getBoundingClientRect();
    return { x: Math.round(r.left + scrollX), y: Math.round(r.top + scrollY), w: Math.round(r.width), h: Math.round(r.height) };
  };
  const out = [];
  document.querySelectorAll('img').forEach((el) => {
    const cands = [biggest(el.getAttribute('srcset')), biggest(el.getAttribute('data-srcset')), biggest(el.getAttribute('data-lazy-srcset')),
      abs(el.getAttribute('data-src') || ''), abs(el.getAttribute('data-lazy-src') || ''), el.currentSrc, el.src].filter(Boolean);
    const src = cands.find((u) => !u.startsWith('data:')) || '';
    if (!src) return;
    out.push({ kind: 'img', src, alt: el.getAttribute('alt') || '', natural: [el.naturalWidth, el.naturalHeight], box: inView(el),
      section: label(el), cls: (el.className || '').toString().slice(0, 60), inHeader: !!el.closest('header, footer, nav') });
  });
  document.querySelectorAll('*').forEach((el) => {
    const bg = getComputedStyle(el).backgroundImage;
    if (!bg || bg === 'none') return;
    const m = [...bg.matchAll(/url\(["']?([^"')]+)["']?\)/g)];
    m.forEach((x) => {
      const src = abs(x[1]);
      if (!src || src.startsWith('data:')) return;
      const b = inView(el);
      if (b.w < 40 || b.h < 40) return;
      out.push({ kind: 'bg', src, alt: '', natural: [0, 0], box: b, section: label(el), cls: (el.className || '').toString().slice(0, 60), inHeader: !!el.closest('header, footer, nav') });
    });
  });
  document.querySelectorAll('video').forEach((el) => {
    const src = abs(el.getAttribute('poster') || '');
    const vid = abs(el.currentSrc || el.src || (el.querySelector('source') || {}).src || '');
    out.push({ kind: 'video', src, video: vid, alt: '', natural: [0, 0], box: inView(el), section: label(el), cls: '', inHeader: false });
  });
  const links = [...document.querySelectorAll('a[href]')].map((a) => a.href);
  return { images: out, links, height: document.documentElement.scrollHeight, title: document.title };
}
"""


def slug_of(path: str) -> str:
    s = re.sub(r'[^a-z0-9]+', '-', urllib.parse.unquote(path).strip('/').lower()).strip('-')
    return s or 'home'


def original_url(url: str) -> str:
    """Drop the WordPress size suffix so the full-size original is fetched (falls back later if 404)."""
    p = urllib.parse.urlparse(url)
    return urllib.parse.urlunparse(p._replace(path=THUMB.sub('', p.path), query=''))


def scroll_all(page):
    height = page.evaluate('document.documentElement.scrollHeight')
    y = 0
    while y < height:
        page.evaluate(f'window.scrollTo(0,{y})')
        page.wait_for_timeout(250)
        y += 600
        height = page.evaluate('document.documentElement.scrollHeight')
    page.wait_for_timeout(800)
    page.evaluate('window.scrollTo(0,0)')
    page.wait_for_timeout(300)


def annotate(png: bytes, images: list, slug: str):
    im = Image.open(io.BytesIO(png)).convert('RGB')
    d = ImageDraw.Draw(im)
    try:
        font = ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf', 20)
    except OSError:
        font = ImageFont.load_default()
    for i, it in enumerate(images):
        b = it['box']
        if b['w'] < 20 or b['h'] < 20:
            continue
        d.rectangle([b['x'], b['y'], b['x'] + b['w'], b['y'] + b['h']], outline=(255, 0, 90), width=3)
        d.rectangle([b['x'], b['y'], b['x'] + 44, b['y'] + 26], fill=(255, 0, 90))
        d.text((b['x'] + 4, b['y'] + 2), str(i), fill=(255, 255, 255), font=font)
    chunk = 1500
    n = 0
    for top in range(0, im.height, chunk):
        part = im.crop((0, top, im.width, min(im.height, top + chunk)))
        part.save(os.path.join(SHOTS, f'{slug}-{n:02d}.jpg'), 'JPEG', quality=55, optimize=True)
        n += 1
    return n


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--base', default='https://hrideone.com')
    ap.add_argument('--max-pages', type=int, default=70)
    args = ap.parse_args()
    os.makedirs(SHOTS, exist_ok=True)
    for f in os.listdir(SHOTS):
        os.remove(os.path.join(SHOTS, f))

    host = urllib.parse.urlparse(args.base).netloc
    queue, seen, pages = list(SEEDS), set(), {}
    with sync_playwright() as pw:
        browser = pw.chromium.launch()
        ctx = browser.new_context(viewport={'width': 1280, 'height': 900}, user_agent='Mozilla/5.0 (compatible; hrideone-web crawl-images)')
        page = ctx.new_page()
        while queue and len(pages) < args.max_pages:
            path = queue.pop(0)
            if path in seen:
                continue
            seen.add(path)
            url = args.base + path
            try:
                resp = page.goto(url, wait_until='networkidle', timeout=60000)
                if not resp or resp.status >= 400:
                    print(f'SKIP {path}: HTTP {resp.status if resp else "none"}', flush=True)
                    continue
                scroll_all(page)
                data = page.evaluate(COLLECT_JS)
                png = page.screenshot(full_page=True, type='png')
            except Exception as e:  # noqa: BLE001 - keep crawling
                print(f'FAIL {path}: {e}', flush=True)
                continue
            imgs = [i for i in data['images'] if IMG_EXT.search(i['src'] or '') or i['kind'] == 'video']
            imgs.sort(key=lambda i: (i['box']['y'], i['box']['x']))
            for i in imgs:
                i['original'] = original_url(i['src']) if i['src'] else ''
            slug = slug_of(path)
            chunks = annotate(png, imgs, slug)
            pages[path] = {'slug': slug, 'title': data['title'], 'height': data['height'], 'chunks': chunks, 'images': imgs}
            print(f'OK {path}: {len(imgs)} images, {chunks} screenshot chunks', flush=True)
            for href in data['links']:
                u = urllib.parse.urlparse(href)
                if u.netloc == host and FOLLOW.match(u.path) and not IMG_EXT.search(u.path):
                    p = u.path if u.path.endswith('/') else u.path + '/'
                    if p not in seen and p not in queue and not re.search(r'/(page|feed)/', p):
                        queue.append(p)
            time.sleep(0.5)
        browser.close()

    with open(os.path.join(OUT, 'pages.json'), 'w', encoding='utf-8') as f:
        json.dump({'base': args.base, 'pages': pages}, f, ensure_ascii=False, indent=1)
    total = sum(len(p['images']) for p in pages.values())
    print(f'{len(pages)} pages, {total} images', flush=True)
    if not pages:
        sys.exit(1)


if __name__ == '__main__':
    main()
