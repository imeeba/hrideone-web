#!/usr/bin/env python3
"""Save the text of the old hrideone.com /about/ page (Thai, English, Japanese) in reading order.

Output: playground/data/old-about.json   { "th": [ {tag, text, img?}, ... ], "en": [...], "ja": [...] }
        playground/data/crawl/about-<lang>-<n>.jpg   full-page screenshot chunks (to see the layout)

The Claude workspace cannot reach hrideone.com, so this runs on GitHub (.github/workflows/fetch-old-about.yml).
Needs: playwright (+ chromium), pillow
"""

import io
import json
import os

from PIL import Image
from playwright.sync_api import sync_playwright

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, 'playground', 'data')
PAGES = {'th': 'https://hrideone.com/about/', 'en': 'https://hrideone.com/en/about/', 'ja': 'https://hrideone.com/ja/about/'}

JS = r"""
() => {
  const root = document.querySelector('main, #content, .elementor') || document.body;
  const out = [];
  root.querySelectorAll('h1,h2,h3,h4,h5,h6,p,li,img,blockquote').forEach((el) => {
    if (el.closest('header, footer, nav')) return;
    if (el.tagName === 'IMG') {
      const src = el.currentSrc || el.src;
      if (src && !/flags|logo/i.test(src)) out.push({ tag: 'img', src, alt: el.alt || '', w: el.naturalWidth, h: el.naturalHeight });
      return;
    }
    if (el.tagName === 'P' && el.closest('li, blockquote')) return;
    const text = el.innerText.replace(/\s+\n/g, '\n').trim();
    if (text) out.push({ tag: el.tagName.toLowerCase(), text });
  });
  return out;
}
"""


def main():
    result = {}
    os.makedirs(os.path.join(OUT, 'crawl'), exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page(viewport={'width': 1280, 'height': 900})
        for lang, url in PAGES.items():
            try:
                resp = page.goto(url, wait_until='networkidle', timeout=60000)
                print(lang, url, resp.status if resp else None, page.url)
                page.evaluate("() => window.scrollTo(0, document.body.scrollHeight)")
                page.wait_for_timeout(1500)
                result[lang] = {'url': page.url, 'status': resp.status if resp else None, 'blocks': page.evaluate(JS)}
                shot = Image.open(io.BytesIO(page.screenshot(full_page=True))).convert('RGB')
                for n, y in enumerate(range(0, shot.height, 2400), 1):
                    shot.crop((0, y, shot.width, min(y + 2400, shot.height))).resize((640, round(min(2400, shot.height - y) / 2))).save(
                        os.path.join(OUT, 'crawl', f'about-{lang}-{n}.jpg'), 'JPEG', quality=55)
            except Exception as e:  # noqa: BLE001 - report and go on with the next language
                result[lang] = {'url': url, 'error': str(e)}
                print('FAILED', lang, e)
        browser.close()
    with open(os.path.join(OUT, 'old-about.json'), 'w', encoding='utf-8') as f:
        json.dump(result, f, ensure_ascii=False, indent=1)


if __name__ == '__main__':
    main()
