#!/usr/bin/env python3
"""Layout checks for the HRI DeOne site (run against a local or staging site, never production).

Checks, per page x viewport:
  - horizontal overflow
  - tap targets smaller than 44x44 CSS px (inline links in running text and the honeypot are skipped;
    a checkbox/radio inside a <label> is measured as the label)
  - text contrast below WCAG AA
  - PHP notices/warnings/fatals in the HTML
  - JS console errors / page errors

Usage:
  python3 scripts/measure.py [--base http://127.0.0.1:8899/wp] [--widths 1280,768,375]
                             [--langs th,en,ja] [--pages /,/courses/] [--json out.json] [--verbose]

Exit code is 1 when any check fails, so it can gate a commit.
Requires: pip install playwright (Chromium is expected at /opt/pw-browsers, no download needed).
"""
import argparse
import json
import os
import re
import sys
from urllib.parse import urljoin

from playwright.sync_api import sync_playwright

TAP_MIN = 44
DEFAULT_WIDTHS = [1280, 768, 375]
# Page paths in Thai (default language). EN/JA get the language prefix and, for pages and single
# courses, the "-en" / "-ja" slug suffix that the sample data uses (Polylang free needs unique slugs).
DEFAULT_PAGES = [
    "/",
    "/courses/",
    "/in-house/",
    "/public-training/",
    "/courses/coaching-mastery/",
    "/courses/negotiation-mastery/",
    "/contact-us/",
]
LANG_PREFIX = {"th": "", "en": "/en", "ja": "/ja"}
NO_SUFFIX = {"/", "/courses/"}  # home and the course archive keep the same slug in every language
# Pages whose EN / JA path does not follow the "-en" / "-ja" rule (old-site slugs kept).
# Any --pages entry can also be written "th-path|en-path|ja-path" (paths without the /en /ja prefix).
PATH_OVERRIDE = {"/blog/": {"ja": "/%E3%83%96%E3%83%AD%E3%82%B0/"}}

PHP_RE = re.compile(r"(Notice|Warning|Deprecated|Fatal error|Parse error)</b>:|<b>(Notice|Warning|Deprecated|Fatal error)</b>:|PHP (Notice|Warning|Deprecated|Fatal)")

# Runs in the page. Returns overflow, small tap targets and low-contrast text.
MEASURE_JS = r"""
({ tapMin }) => {
  const out = { overflow: null, taps: [], contrast: [] };
  const de = document.documentElement;
  const vw = de.clientWidth;
  if (de.scrollWidth > vw + 1) {
    // Find the widest offenders (skip the honeypot that is parked off-screen on purpose).
    const bad = [];
    for (const el of document.body.querySelectorAll('*')) {
      if (el.closest('.hri-lead__hp')) continue;
      const r = el.getBoundingClientRect();
      if (r.width === 0 || r.height === 0) continue;
      const cs = getComputedStyle(el);
      if (cs.position === 'fixed') continue;
      if (r.right > vw + 1 || r.left < -1) {
        // Ignore elements that are clipped by a scroll container ancestor.
        let p = el.parentElement, clipped = false;
        while (p && p !== document.body) {
          const o = getComputedStyle(p);
          if (/(auto|scroll|hidden|clip)/.test(o.overflowX)) { clipped = true; break; }
          p = p.parentElement;
        }
        if (!clipped) bad.push(el);
      }
    }
    out.overflow = { scrollWidth: de.scrollWidth, viewport: vw, examples: bad.slice(0, 5).map(describe) };
  }

  function describe(el) {
    let s = el.tagName.toLowerCase();
    if (el.id) s += '#' + el.id;
    if (el.className && typeof el.className === 'string') s += '.' + el.className.trim().split(/\s+/).slice(0, 3).join('.');
    const t = (el.innerText || el.getAttribute('aria-label') || el.value || '').trim().replace(/\s+/g, ' ').slice(0, 30);
    return t ? `${s} "${t}"` : s;
  }
  function visible(el) {
    const cs = getComputedStyle(el);
    if (cs.visibility === 'hidden' || cs.display === 'none' || parseFloat(cs.opacity) === 0) return false;
    const r = el.getBoundingClientRect();
    return r.width > 0 && r.height > 0;
  }
  function inView(el) {
    // Hidden by a closed <details> or a closed menu overlay counts as not visible.
    for (let p = el.parentElement; p; p = p.parentElement) {
      if (p.tagName === 'DETAILS' && !p.open) {
        const sum = [...p.children].find(c => c.tagName === 'SUMMARY');
        if (!(sum && sum.contains(el))) return false;
      }
    }
    return true;
  }
  function isInlineInText(el) {
    // Link sitting inside a sentence (WCAG 2.5.8 inline exception).
    if (el.tagName !== 'A') return false;
    const p = el.closest('p, li, dd, td, figcaption, label');
    if (!p) return false;
    // "Inline" = the paragraph holds more text than the link itself.
    const own = (el.textContent || '').trim().length;
    const all = (p.textContent || '').trim().length;
    return all - own > 8 && getComputedStyle(el).display === 'inline';
  }

  const sel = 'a[href], button, summary, input:not([type=hidden]), select, textarea, [role=button], [role=menuitem], [tabindex]:not([tabindex="-1"])';
  const seen = new Set();
  for (const el0 of document.querySelectorAll(sel)) {
    let el = el0;
    if (el.closest('.hri-lead__hp')) continue;
    if (el.classList.contains('skip-link') || el.closest('.screen-reader-text')) continue;
    // A checkbox/radio is activated through its label: measure the label instead.
    if (el.tagName === 'INPUT' && /^(checkbox|radio)$/.test(el.type)) {
      const lab = el.closest('label') || (el.id && document.querySelector(`label[for="${CSS.escape(el.id)}"]`));
      if (lab) el = lab;
    }
    if (seen.has(el)) continue;
    seen.add(el);
    if (!visible(el) || !inView(el)) continue;
    if (isInlineInText(el)) continue;
    let r = el.getBoundingClientRect();
    // "Stretched link": a ::after that covers its container makes the whole container the target.
    if (el.tagName === 'A') {
      const af = getComputedStyle(el, '::after');
      if (af.content !== 'none' && af.position === 'absolute') {
        const host = el.offsetParent;
        if (host && host !== document.body) {
          const hr = host.getBoundingClientRect();
          const w = parseFloat(af.width), h = parseFloat(af.height);
          // inset:0 resolves to the host's padding box.
          if (Math.abs(w - host.clientWidth) < 2 && Math.abs(h - host.clientHeight) < 2) r = hr;
        }
      }
    }
    if (r.width < tapMin - 0.5 || r.height < tapMin - 0.5) {
      out.taps.push({ el: describe(el), w: Math.round(r.width * 10) / 10, h: Math.round(r.height * 10) / 10 });
    }
  }

  // ---- contrast ----
  function parse(c) {
    const m = c.match(/rgba?\(([^)]+)\)/);
    if (!m) return null;
    const p = m[1].split(/[ ,/]+/).filter(Boolean).map(Number);
    return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
  }
  function lum({ r, g, b }) {
    const f = v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
    return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b);
  }
  function blend(fg, bg) {
    const a = fg.a;
    return { r: fg.r * a + bg.r * (1 - a), g: fg.g * a + bg.g * (1 - a), b: fg.b * a + bg.b * (1 - a), a: 1 };
  }
  function bgOf(el) {
    // Walk up compositing solid/translucent backgrounds. Images/gradients -> unknown (skip).
    const stack = [];
    for (let p = el; p; p = p.parentElement) {
      const cs = getComputedStyle(p);
      if (cs.backgroundImage && cs.backgroundImage !== 'none') return null;
      const c = parse(cs.backgroundColor);
      if (c && c.a > 0) { stack.push(c); if (c.a >= 1) break; }
    }
    let base = { r: 255, g: 255, b: 255, a: 1 };
    for (let i = stack.length - 1; i >= 0; i--) base = blend(stack[i], base);
    return base;
  }
  const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
  const checked = new Set();
  let n;
  while ((n = walker.nextNode())) {
    if (!n.nodeValue.trim()) continue;
    const el = n.parentElement;
    if (!el || checked.has(el)) continue;
    checked.add(el);
    if (el.closest('script, style, noscript, .hri-lead__hp, [aria-hidden="true"]')) continue;
    // Text over a background image/video (Cover block): contrast depends on the overlay, checked by hand.
    if (el.closest('.wp-block-cover')) continue;
    if (!visible(el) || !inView(el)) continue;
    const cs = getComputedStyle(el);
    const fg = parse(cs.color);
    const bg = bgOf(el);
    if (!fg || !bg) continue;
    const f = blend(fg, bg);
    const L1 = lum(f), L2 = lum(bg);
    const ratio = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
    const px = parseFloat(cs.fontSize);
    const bold = parseInt(cs.fontWeight, 10) >= 700;
    const large = px >= 24 || (px >= 18.66 && bold);
    const need = large ? 3 : 4.5;
    if (ratio < need) out.contrast.push({ el: describe(el), ratio: Math.round(ratio * 100) / 100, need });
  }
  return out;
}
"""


def page_url(base, lang, path):
    if "|" in path:
        parts = dict(zip(("th", "en", "ja"), path.split("|")))
        return base.rstrip("/") + LANG_PREFIX[lang] + parts.get(lang, parts["th"])
    if lang in PATH_OVERRIDE.get(path, {}):
        return base.rstrip("/") + LANG_PREFIX[lang] + PATH_OVERRIDE[path][lang]
    if lang == "th" or path in NO_SUFFIX:
        p = LANG_PREFIX[lang] + path
    else:
        p = LANG_PREFIX[lang] + path.rstrip("/") + "-" + lang + "/"
    return base.rstrip("/") + p


def main():
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("--base", default=os.environ.get("HRI_BASE", "http://127.0.0.1:8899/wp"))
    ap.add_argument("--widths", default=",".join(map(str, DEFAULT_WIDTHS)))
    ap.add_argument("--langs", default="th,en,ja")
    ap.add_argument("--pages", default=",".join(DEFAULT_PAGES))
    ap.add_argument("--json", help="write full results to this file")
    ap.add_argument("--verbose", action="store_true", help="list every finding, not just the first few")
    ap.add_argument("--only", choices=["overflow", "tap", "contrast", "php", "js"], help="fail only on this check")
    args = ap.parse_args()

    widths = [int(w) for w in args.widths.split(",") if w]
    langs = [l for l in args.langs.split(",") if l]
    pages = [p for p in args.pages.split(",") if p]

    exe = None
    for cand in ("/opt/pw-browsers/chromium", ):
        if os.path.exists(cand):
            exe = cand
    launch_kw = {"executable_path": exe} if exe and os.path.isfile(exe) else {}

    results = []
    with sync_playwright() as pw:
        try:
            browser = pw.chromium.launch(**launch_kw)
        except Exception:
            browser = pw.chromium.launch()
        for lang in langs:
            for path in pages:
                url = page_url(args.base, lang, path)
                for w in widths:
                    ctx = browser.new_context(viewport={"width": w, "height": 900}, device_scale_factor=1)
                    page = ctx.new_page()
                    console_errors = []
                    page.on("pageerror", lambda e, ce=console_errors: ce.append(str(e)[:160]))
                    page.on("console", lambda m, ce=console_errors: ce.append(m.text[:160]) if m.type == "error" and "favicon" not in m.text and "Failed to load resource" not in m.text else None)
                    row = {"lang": lang, "path": path, "url": url, "width": w}
                    try:
                        resp = page.goto(url, wait_until="domcontentloaded", timeout=60000)
                        row["status"] = resp.status if resp else 0
                        page.wait_for_timeout(500)
                        html = page.content()
                        row["php"] = [m.group(0) for m in PHP_RE.finditer(html)][:3]
                        # Force lazy images in so layout is final.
                        page.evaluate("document.querySelectorAll('img[loading=lazy]').forEach(i=>i.loading='eager')")
                        m = page.evaluate(MEASURE_JS, {"tapMin": TAP_MIN})
                        row.update(m)
                        known = {(t["el"], t["w"], t["h"]) for t in row.get("taps", [])}
                        # Extra states: panels that start closed (mobile filter, language dropdown) and the
                        # full-screen menu. Only tap targets are re-measured there.
                        for state, action in (
                            ("details open", "document.querySelectorAll('details').forEach(d => d.open = true)"),
                            ("menu open", None),
                        ):
                            if action:
                                page.evaluate(action)
                            else:
                                btn = page.query_selector("button.wp-block-navigation__responsive-container-open")
                                if not btn or not btn.is_visible():
                                    continue
                                btn.click()
                                page.wait_for_timeout(300)
                            extra = page.evaluate(MEASURE_JS, {"tapMin": TAP_MIN})
                            for t in extra["taps"]:
                                if (t["el"], t["w"], t["h"]) not in known:
                                    known.add((t["el"], t["w"], t["h"]))
                                    t["el"] = f"{t['el']} [{state}]"
                                    row["taps"].append(t)
                        row["js"] = console_errors[:5]
                    except Exception as e:  # noqa: BLE001
                        row["error"] = str(e)[:200]
                    ctx.close()
                    results.append(row)
        browser.close()

    failures = 0
    print(f"{'lang':4} {'w':>4} {'page':32} {'HTTP':>4} {'ovf':>3} {'tap':>3} {'con':>3} {'php':>3} {'js':>3}")
    for r in results:
        if "error" in r:
            print(f"{r['lang']:4} {r['width']:>4} {r['path']:32} ERROR {r['error']}")
            failures += 1
            continue
        flags = {
            "overflow": 1 if r.get("overflow") else 0,
            "tap": len(r.get("taps", [])),
            "contrast": len(r.get("contrast", [])),
            "php": len(r.get("php", [])),
            "js": len(r.get("js", [])),
        }
        bad_status = r.get("status", 200) >= 400
        key = {"overflow": "overflow", "tap": "tap", "contrast": "contrast", "php": "php", "js": "js"}
        failing = [k for k, v in flags.items() if v and (args.only in (None, k))]
        if bad_status or failing:
            failures += 1
        print(f"{r['lang']:4} {r['width']:>4} {r['path']:32} {r.get('status', '-'):>4} {flags['overflow']:>3} {flags['tap']:>3} {flags['contrast']:>3} {flags['php']:>3} {flags['js']:>3}")

    # Aggregated tap-target findings: element -> worst size, grouped so the list stays short.
    agg = {}
    for r in results:
        for t in r.get("taps", []):
            k = re.sub(r'\s*"[^"]*"', "", t["el"])
            a = agg.setdefault(k, {"count": 0, "min_h": 999, "min_w": 999, "where": set()})
            a["count"] += 1
            a["min_h"] = min(a["min_h"], t["h"])
            a["min_w"] = min(a["min_w"], t["w"])
            a["where"].add(f"{r['lang']}{r['path']}@{r['width']}")
    if agg:
        print("\nTap targets < %dpx (grouped by element):" % TAP_MIN)
        for k, a in sorted(agg.items(), key=lambda kv: -kv[1]["count"]):
            ex = sorted(a["where"])[: (50 if args.verbose else 2)]
            print(f"  {k:60} x{a['count']:<3} min {a['min_w']}x{a['min_h']}  e.g. {', '.join(ex)}")

    def dump(title, key, fmt):
        items = [(r, x) for r in results for x in r.get(key, [])]
        if not items:
            return
        print(f"\n{title}:")
        seen = set()
        shown = 0
        for r, x in items:
            sig = fmt(x)
            if sig in seen:
                continue
            seen.add(sig)
            shown += 1
            if shown > (200 if args.verbose else 8):
                print("  ...")
                break
            print(f"  {r['lang']}{r['path']}@{r['width']}: {sig}")

    dump("Contrast below AA", "contrast", lambda x: f"{x['el']} {x['ratio']}:1 (need {x['need']})")
    dump("PHP notices", "php", lambda x: x)
    dump("JS errors", "js", lambda x: x)
    ovf = [r for r in results if r.get("overflow")]
    if ovf:
        print("\nHorizontal overflow:")
        for r in ovf[: (50 if args.verbose else 8)]:
            o = r["overflow"]
            print(f"  {r['lang']}{r['path']}@{r['width']}: scrollWidth {o['scrollWidth']} > {o['viewport']}  {', '.join(o['examples'][:3])}")

    if args.json:
        with open(args.json, "w", encoding="utf-8") as f:
            json.dump(results, f, ensure_ascii=False, indent=1)

    print(f"\n{len(results)} page-viewport checks, {failures} with problems")
    sys.exit(1 if failures else 0)


if __name__ == "__main__":
    main()
