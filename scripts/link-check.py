#!/usr/bin/env python3
"""Check the internal links of every article (and the main pages) on a running site.

Usage: python3 -I scripts/link-check.py [--base http://127.0.0.1:8899/wp] [--urls urls.txt]

urls.txt = one page URL per line (default: sitemap.xml of the site). For every <a href> to this site
(not the admin, not files) the script asks the URL WITHOUT following redirects:
  200 = ok · 301/302 = "redirect" (a link that should point straight to the new page) · 404 etc. = broken.
Exit code 1 when there is a broken link. Redirects are listed but only counted as a problem with --strict.
"""
import argparse, concurrent.futures as cf, re, sys, urllib.error, urllib.parse, urllib.request
from html.parser import HTMLParser


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


opener = urllib.request.build_opener(NoRedirect)


def fetch(url):
    try:
        r = opener.open(urllib.request.Request(url, headers={'User-Agent': 'link-check'}), timeout=60)
        return r.status, r.headers.get('Location', ''), r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get('Location', ''), ''
    except Exception as e:  # timeout etc.
        return 0, str(e), ''


class Links(HTMLParser):
    def __init__(self):
        super().__init__()
        self.hrefs, self.ids = [], set()

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag == 'a' and a.get('href'):
            self.hrefs.append(a['href'])
        if a.get('id'):
            self.ids.add(a['id'])


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--base', default='http://127.0.0.1:8899/wp')
    ap.add_argument('--urls')
    ap.add_argument('--strict', action='store_true')
    args = ap.parse_args()
    base = args.base.rstrip('/')
    host = urllib.parse.urlparse(base).netloc
    if args.urls:
        pages = [l.strip() for l in open(args.urls, encoding='utf-8') if l.strip()]
    else:
        _, _, xml = fetch(base + '/sitemap.xml')
        pages = re.findall(r'<loc>([^<]+)</loc>', xml)
    found = {}  # link -> set of pages
    def crawl(page):
        code, loc, html = fetch(page)
        if code != 200:
            return page, None, code
        p = Links()
        p.feed(html)
        return page, p.hrefs, 200
    with cf.ThreadPoolExecutor(8) as ex:
        for page, hrefs, code in ex.map(crawl, pages):
            if hrefs is None:
                print(f'page {page} answers {code}')
                continue
            for h in hrefs:
                u = urllib.parse.urlparse(urllib.parse.urljoin(page, h))
                if u.netloc != host or not u.path.startswith(urllib.parse.urlparse(base).path):
                    continue
                if re.search(r'/(wp-admin|wp-login|wp-content|wp-json|feed)|\.(jpg|jpeg|png|webp|svg|pdf|xml|js|css)$', u.path):
                    continue
                found.setdefault(urllib.parse.urlunparse(u._replace(fragment='')), set()).add(page)
    print(f'{len(pages)} pages, {len(found)} distinct internal links')
    def check(link):
        return link, fetch(link)[:2]
    broken, redirects = [], []
    with cf.ThreadPoolExecutor(8) as ex:
        for link, (code, loc) in ex.map(check, sorted(found)):
            if code in (301, 302, 307, 308):
                redirects.append((link, code, loc))
            elif code != 200:
                broken.append((link, code))
    for link, code in broken:
        print(f'BROKEN {code} {link}  <- {sorted(found[link])[0]} (+{len(found[link]) - 1})')
    for link, code, loc in redirects:
        print(f'redirect {code} {link} -> {loc}  <- {sorted(found[link])[0]} (+{len(found[link]) - 1})')
    print(f'{len(broken)} broken, {len(redirects)} redirects')
    return 1 if broken or (args.strict and redirects) else 0


sys.exit(main())
