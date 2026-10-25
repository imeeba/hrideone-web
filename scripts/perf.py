#!/usr/bin/env python3
"""Lighthouse over 6 page types x mobile/desktop -> JSON + summary table.

Usage: python3 -I scripts/perf.py [--base http://127.0.0.1:8899/wp] [--out DIR]
         [--runs 1] [--only home,courses] [--form-factors mobile,desktop]
Needs: npm i lighthouse (LIGHTHOUSE_BIN or ./node_modules/.bin/lighthouse or `lighthouse` on PATH)
       Chromium (CHROME_PATH or /opt/pw-browsers/chromium-*/chrome-linux*/chrome)
Goes through scripts/perf-proxy.mjs (Brotli + long cache, like the host). Still NOT host speed
(no HTTP/2, no CDN, PHP -S is single threaded): compare before/after, trust LCP/CLS/TBT/bytes more than the score.
"""
import argparse, glob, json, os, shutil, statistics, subprocess, sys, time, urllib.request

PAGES = {
    "home": "/",
    "courses": "/courses/",
    "course": "/courses/the-new-manager/",
    "blog": "/blog/",
    "post": "/why-leadership-remains-human-in-the-age-of-ai/",
    "contact": "/contact-us/",
}


def find_bin():
    for c in (os.environ.get("LIGHTHOUSE_BIN"), "/tmp/claude-0/lh/node_modules/.bin/lighthouse", "./node_modules/.bin/lighthouse", shutil.which("lighthouse")):
        if c and os.path.exists(c):
            return c
    sys.exit("lighthouse not found (npm i lighthouse)")


def find_chrome():
    if os.environ.get("CHROME_PATH"):
        return os.environ["CHROME_PATH"]
    hits = sorted(glob.glob("/opt/pw-browsers/chromium-*/chrome-linux*/chrome"))
    if not hits:
        sys.exit("chromium not found (set CHROME_PATH)")
    return hits[-1]


def run(lh, chrome, url, ff, out):
    cmd = [lh, url, "--output=json", f"--output-path={out}", "--quiet",
           "--only-categories=performance", f"--form-factor={ff}",
           "--chrome-flags=--headless=new --no-sandbox --disable-gpu"]
    if ff == "desktop":
        cmd.append("--preset=desktop")
    subprocess.run(cmd, check=True, env={**os.environ, "CHROME_PATH": chrome}, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
    return json.load(open(out))


def pick(r):
    a = r["audits"]
    return {
        "score": round(r["categories"]["performance"]["score"] * 100),
        "fcp": a["first-contentful-paint"]["numericValue"],
        "lcp": a["largest-contentful-paint"]["numericValue"],
        "tbt": a["total-blocking-time"]["numericValue"],
        "cls": a["cumulative-layout-shift"]["numericValue"],
        "kb": a["total-byte-weight"]["numericValue"] / 1024,
        "reqs": len(a["network-requests"]["details"]["items"]),
        "lcp_el": (a.get("largest-contentful-paint-element", {}).get("details", {}).get("items") or [{}])[0].get("items", [{}])[0].get("node", {}).get("snippet", "")[:70],
    }


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--base", default="http://127.0.0.1:8899/wp", help="site to measure (php -S)")
    ap.add_argument("--no-proxy", action="store_true", help="measure the raw php -S server (no Brotli / cache headers)")
    ap.add_argument("--out", default="/tmp/claude-0/perf")
    ap.add_argument("--runs", type=int, default=1)
    ap.add_argument("--only", default="")
    ap.add_argument("--form-factors", default="mobile,desktop")
    a = ap.parse_args()
    os.makedirs(a.out, exist_ok=True)
    lh, chrome = find_bin(), find_chrome()
    proxy = None
    if not a.no_proxy:  # Brotli + long cache like the real host; php -S sends raw bytes
        here = os.path.dirname(os.path.abspath(__file__))
        up = a.base.split("/", 3)
        a.base = "http://127.0.0.1:8898/" + (up[3] if len(up) > 3 else "")
        proxy = subprocess.Popen(["node", f"{here}/perf-proxy.mjs", "8898", "/".join(up[:3])], stdout=subprocess.DEVNULL)
        for _ in range(20):
            try:
                urllib.request.urlopen("http://127.0.0.1:8898/", timeout=1)
                break
            except Exception:
                time.sleep(0.25)
    names = [n for n in PAGES if not a.only or n in a.only.split(",")]
    rows, bad = [], 0
    for ff in a.form_factors.split(","):
        for n in names:
            res = [pick(run(lh, chrome, a.base.rstrip("/") + PAGES[n], ff, f"{a.out}/{n}-{ff}-{i}.json")) for i in range(a.runs)]
            m = {k: (statistics.median(r[k] for r in res) if k != "lcp_el" else res[0][k]) for k in res[0]}
            rows.append((ff, n, m))
            if ff == "mobile" and (m["score"] < 90 or m["cls"] >= 0.1):
                bad += 1
    print(f"{'form':8}{'page':9}{'score':>6}{'FCP':>7}{'LCP':>7}{'TBT':>6}{'CLS':>7}{'KB':>7}{'req':>5}  LCP element")
    for ff, n, m in rows:
        print(f"{ff:8}{n:9}{m['score']:>6}{m['fcp']/1000:>6.1f}s{m['lcp']/1000:>6.1f}s{m['tbt']:>5.0f}m{m['cls']:>7.3f}{m['kb']:>7.0f}{m['reqs']:>5.0f}  {m['lcp_el']}")
    if proxy:
        proxy.terminate()
    json.dump([{"form": f, "page": n, **m} for f, n, m in rows], open(f"{a.out}/summary.json", "w"), indent=1)
    sys.exit(1 if bad else 0)


if __name__ == "__main__":
    main()
