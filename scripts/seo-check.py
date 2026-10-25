#!/usr/bin/env python3
"""SEO / AI-SEO checks for the HRI DeOne site (run against a local or staging site, never production).

Starts from the sitemaps (every language) and, for every URL in them, checks:
  - HTTP 200, <title>, meta description, canonical = the page itself, no noindex, <html lang> = the URL's language
  - hreflang: x-default present, every alternate is itself in the sitemap (so never a noindex page) and
    links back (A -> B means B -> A), the page lists itself
  - JSON-LD: exactly one script, valid JSON, one @graph; no duplicate @id, every {"@id": ...} reference
    resolves; required fields per type (EducationalOrganization, WebSite, WebPage, BreadcrumbList,
    BlogPosting, Course/CourseInstance/Offer, FAQPage)
Then robots.txt (AI search bots allowed, sitemap lines, search results blocked) and llms.txt (every link
answers 200 and is in the sitemap).

Usage:
  python3 scripts/seo-check.py [--base http://127.0.0.1:8899/wp] [--robots-url URL]
                               [--min-posts 345] [--limit N] [--workers 4] [--verbose]

Exit code is 1 when any check fails. Standard library only.
"""
import argparse
import concurrent.futures as cf
import json
import re
import sys
import urllib.error
import urllib.request
import xml.etree.ElementTree as ET
from collections import Counter, defaultdict
from html.parser import HTMLParser
from urllib.parse import unquote, urlparse

LANGS = ("th", "en", "ja")
SM = "{http://www.sitemaps.org/schemas/sitemap/0.9}"
AI_SEARCH_BOTS = ("OAI-SearchBot", "ChatGPT-User", "Claude-SearchBot", "PerplexityBot", "Bingbot")


def get(url, timeout=60):
    req = urllib.request.Request(url, headers={"User-Agent": "hri-seo-check"})
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            return r.status, r.read().decode("utf-8", "replace"), r.geturl()
    except urllib.error.HTTPError as e:
        return e.code, "", url
    except Exception as e:  # noqa: BLE001 - report, don't crash
        return 0, str(e), url


class Head(HTMLParser):
    """Collects what the checks need from a page."""

    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.title, self._in_title = "", False
        self.meta, self.links, self.ld, self._in_ld, self.lang, self.h1 = {}, [], [], False, "", 0

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag == "html":
            self.lang = a.get("lang", "")
        elif tag == "title":
            self._in_title = True
        elif tag == "meta" and (a.get("name") or a.get("property")):
            self.meta[(a.get("name") or a.get("property")).lower()] = a.get("content", "")
        elif tag == "link":
            self.links.append(a)
        elif tag == "script" and a.get("type") == "application/ld+json":
            self._in_ld = True
            self.ld.append("")
        elif tag == "h1":
            self.h1 += 1

    def handle_endtag(self, tag):
        if tag == "title":
            self._in_title = False
        elif tag == "script":
            self._in_ld = False

    def handle_data(self, data):
        if self._in_title:
            self.title += data
        elif self._in_ld:
            self.ld[-1] += data


def norm(url):
    u = urlparse(url)
    return u._replace(fragment="", query="" if not u.query else u.query).geturl()


def sitemap_urls(base):
    """All page URLs from the sitemaps of every language (index files are followed)."""
    seen, urls, queue = set(), [], [f"{base}/sitemap.xml"] + [f"{base}/{l}/sitemap.xml" for l in LANGS if l != "th"]
    while queue:
        sm = queue.pop(0)
        if sm in seen:
            continue
        seen.add(sm)
        status, body, _ = get(sm)
        if status != 200:
            continue
        try:
            root = ET.fromstring(body.encode("utf-8"))
        except ET.ParseError:
            continue
        for loc in root.iter(f"{SM}loc"):
            (queue if loc.text.endswith(".xml") else urls).append(loc.text.strip())
    return list(dict.fromkeys(urls))


def url_lang(url, base):
    path = unquote(urlparse(url).path)[len(urlparse(base).path):]
    for l in ("en", "ja"):
        if path == f"/{l}" or path.startswith(f"/{l}/"):
            return l
    return "th"


def check_ld(page, data, problems):
    """Structured data of one page."""
    if len(page["ld"]) != 1:
        problems.append(f"{len(page['ld'])} JSON-LD scripts (want exactly 1)")
        if not page["ld"]:
            return
    try:
        doc = json.loads(page["ld"][0])
    except ValueError as e:
        problems.append(f"JSON-LD does not parse: {e}")
        return
    if doc.get("@context") != "https://schema.org" or not isinstance(doc.get("@graph"), list):
        problems.append("JSON-LD is not one @graph with the schema.org context")
        return
    nodes = doc["@graph"]
    ids = [n.get("@id") for n in nodes if isinstance(n, dict) and n.get("@id")]
    for dup, n in Counter(ids).items():
        if n > 1:
            problems.append(f"duplicate @id {dup}")
    by_type = defaultdict(list)
    for n in nodes:
        for t in (n.get("@type") if isinstance(n.get("@type"), list) else [n.get("@type")]):
            by_type[t].append(n)

    def refs(x):
        if isinstance(x, dict):
            if set(x) == {"@id"}:
                yield x["@id"]
            for v in x.values():
                yield from refs(v)
        elif isinstance(x, list):
            for v in x:
                yield from refs(v)

    known = set(ids)
    for r in set(refs(nodes)):
        if r not in known:
            problems.append(f"@id reference without a node: {r}")

    def need(node, fields, label):
        for f in fields:
            if not node.get(f):
                problems.append(f"{label}: missing {f}")

    for label, fields in (("EducationalOrganization", ("name", "url", "logo", "address")), ("WebSite", ("name", "url", "inLanguage"))):
        if len(by_type[label]) != 1:
            problems.append(f"{len(by_type[label])} x {label} (want 1)")
        for n in by_type[label][:1]:
            need(n, fields, label)
    pages_ = [n for t in ("WebPage", "AboutPage", "ContactPage", "CollectionPage", "SearchResultsPage") for n in by_type[t]]
    if len(pages_) != 1:
        problems.append(f"{len(pages_)} page nodes (WebPage/AboutPage/ContactPage/CollectionPage, want 1)")
    for n in pages_[:1]:
        need(n, ("url", "name", "inLanguage"), "WebPage")
    crumbs = by_type["BreadcrumbList"]
    if page["home"]:
        pass  # A one-item trail is not useful, so home pages print none.
    elif len(crumbs) != 1 or not crumbs[0].get("itemListElement"):
        problems.append("BreadcrumbList missing or empty")
    elif [i.get("position") for i in crumbs[0]["itemListElement"]] != list(range(1, len(crumbs[0]["itemListElement"]) + 1)):
        problems.append("BreadcrumbList positions are not 1..n")
    for n in by_type["BlogPosting"]:
        need(n, ("headline", "datePublished", "dateModified", "inLanguage", "publisher", "mainEntityOfPage"), "BlogPosting")
        if n.get("dateModified") and n.get("datePublished") and n["dateModified"] < n["datePublished"]:
            problems.append("BlogPosting: dateModified is before datePublished")
    for n in by_type["Course"]:
        need(n, ("name", "description", "provider", "inLanguage"), "Course")
        for inst in n.get("hasCourseInstance") or []:
            # In-house courses have no fixed dates; a public batch has both.
            need(inst, ("courseMode", "endDate") if inst.get("startDate") else ("courseMode",), "CourseInstance")
            for off in (inst.get("offers") if isinstance(inst.get("offers"), list) else [inst.get("offers")] if inst.get("offers") else []):
                need(off, ("price", "priceCurrency"), "Offer")
    for n in by_type["FAQPage"]:
        qs = n.get("mainEntity") or []
        if not qs or any(not (q.get("name") and (q.get("acceptedAnswer") or {}).get("text")) for q in qs):
            problems.append("FAQPage: empty question or answer")
    if page["kind"] == "post" and not by_type["BlogPosting"]:
        problems.append("article page without BlogPosting")
    if page["kind"] == "course" and not by_type["Course"]:
        problems.append("course page without Course")


def analyse(url, base):
    status, body, final = get(url)
    res = {"url": url, "status": status, "problems": []}
    if status != 200:
        res["problems"].append(f"HTTP {status}")
        return res
    p = Head()
    p.feed(body)
    pr = res["problems"]
    lang = url_lang(url, base)
    if not p.title.strip():
        pr.append("no <title>")
    if not p.meta.get("description", "").strip():
        pr.append("no meta description")
    canon = [l.get("href") for l in p.links if l.get("rel") == "canonical"]
    if len(canon) != 1:
        pr.append(f"{len(canon)} canonical links")
    elif unquote(norm(canon[0])).rstrip("/") != unquote(norm(url)).rstrip("/"):
        pr.append(f"canonical differs: {canon[0]}")
    if "noindex" in p.meta.get("robots", "").lower():
        pr.append("noindex page is in the sitemap")
    if not p.lang.lower().startswith({"th": "th", "en": "en", "ja": "ja"}[lang]):
        pr.append(f"<html lang={p.lang!r}> for a {lang} URL")
    alts = {l.get("hreflang"): l.get("href") for l in p.links if l.get("rel") == "alternate" and l.get("hreflang")}
    res["alts"] = alts
    path = unquote(urlparse(url).path)
    res["kind"] = "course" if "/courses/" in path and path.rstrip("/").split("/")[-1] != "courses" else "post" if p.meta.get("og:type") == "article" else "page"
    check_ld({"ld": p.ld, "kind": res["kind"], "home": unquote(norm(url)).rstrip("/") in {base.rstrip("/"), f"{base}/en", f"{base}/ja"}}, body, pr)
    return res


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--base", default="http://127.0.0.1:8899/wp")
    ap.add_argument("--robots-url", default="")
    ap.add_argument("--min-posts", type=int, default=345)
    ap.add_argument("--limit", type=int, default=0, help="only the first N URLs (quick run)")
    ap.add_argument("--workers", type=int, default=4)
    ap.add_argument("--verbose", action="store_true")
    a = ap.parse_args()
    base = a.base.rstrip("/")
    failures = []

    urls = sitemap_urls(base)
    per_lang = Counter(url_lang(u, base) for u in urls)
    print(f"sitemap: {len(urls)} URLs  " + "  ".join(f"{l}={per_lang[l]}" for l in LANGS))
    if not urls:
        print("FAIL no URLs in the sitemaps")
        return 1
    scan = urls[: a.limit] if a.limit else urls

    pages = {}
    with cf.ThreadPoolExecutor(a.workers) as ex:
        for i, res in enumerate(ex.map(lambda u: analyse(u, base), scan), 1):
            pages[res["url"]] = res
            if i % 100 == 0:
                print(f"  … {i}/{len(scan)}")

    sm_set = {unquote(norm(u)).rstrip("/") for u in urls}
    for url, res in pages.items():
        pr = res["problems"]
        alts = res.get("alts", {})
        if alts:
            if "x-default" not in alts and len(alts) > 1:
                pr.append("hreflang without x-default")
            me = unquote(norm(url)).rstrip("/")
            if me not in {unquote(norm(h)).rstrip("/") for h in alts.values()}:
                pr.append("hreflang does not list the page itself")
            for code, href in alts.items():
                h = unquote(norm(href)).rstrip("/")
                if h not in sm_set:
                    pr.append(f"hreflang {code} -> not in sitemap: {href}")
                    continue
                other = pages.get(next((u for u in pages if unquote(norm(u)).rstrip("/") == h), ""))
                if other and other.get("alts") and me not in {unquote(norm(x)).rstrip("/") for x in other["alts"].values()}:
                    pr.append(f"hreflang {code} does not link back: {href}")
        if pr:
            failures.append((url, pr))

    kinds = Counter(r.get("kind") for r in pages.values() if r["status"] == 200)
    print(f"checked {len(pages)} pages: " + ", ".join(f"{k}={v}" for k, v in sorted(kinds.items())))
    if not a.limit and kinds["post"] < a.min_posts:
        failures.append(("sitemap", [f"{kinds['post']} articles in the sitemaps, expected at least {a.min_posts}"]))

    # robots.txt
    parts = urlparse(base)
    robots_url = a.robots_url or (f"{base}/?robots=1" if parts.path not in ("", "/") else f"{base}/robots.txt")
    status, robots, _ = get(robots_url)
    rp = []
    if status != 200:
        rp.append(f"robots.txt HTTP {status}")
    else:
        for bot in AI_SEARCH_BOTS:
            m = re.search(rf"User-agent: {re.escape(bot)}\n((?:(?!User-agent).*\n?)*)", robots, re.I)
            if not m or "Disallow: /\n" in m.group(1) + "\n" and "Allow: /\n" not in m.group(1):
                rp.append(f"{bot} is not allowed")
        if "Sitemap:" not in robots:
            rp.append("no Sitemap line")
        if "Disallow: /*?s=" not in robots:
            rp.append("search results are not blocked")
        if re.search(r"^User-agent: \*\s*\nDisallow: /\s*$", robots, re.M):
            rp.append("the whole site is blocked")
    if rp:
        failures.append((robots_url, rp))

    # llms.txt
    status, llms, _ = get(f"{base}/llms.txt")
    lp = []
    if status != 200:
        lp.append(f"llms.txt HTTP {status}")
    else:
        if not llms.startswith("# "):
            lp.append("does not start with a # title")
        links = re.findall(r"\]\((https?://[^)\s]+)\)", llms)
        if len(links) < 20:
            lp.append(f"only {len(links)} links")
        with cf.ThreadPoolExecutor(a.workers) as ex:
            for link, (st, _, _) in zip(links, ex.map(get, links)):
                if st != 200:
                    lp.append(f"link {link} -> HTTP {st}")
                elif unquote(norm(link)).rstrip("/") not in sm_set and not link.endswith("sitemap.xml") and "/courses/" != urlparse(link).path[-9:]:
                    lp.append(f"link not in sitemap: {link}")
        print(f"llms.txt: {len(links)} links")
    if lp:
        failures.append((f"{base}/llms.txt", lp))

    for url, pr in failures:
        print(f"FAIL {url}")
        for x in pr[: (None if a.verbose else 5)]:
            print(f"     - {x}")
    print(f"\n{'OK: no problems' if not failures else f'{len(failures)} URLs with problems'}")
    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
