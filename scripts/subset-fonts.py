#!/usr/bin/env python3
"""Re-subset the self-hosted Latin web fonts without hinting (about half the size, same characters).

Why: Kanit/Montserrat latin files from Fontsource carry TrueType hinting, which is ~50% of the bytes
and is ignored by macOS/iOS/Android/Linux and by Windows DirectWrite for the vertical-only case.
Run once after replacing a font file; it is idempotent. Needs: pip install fonttools brotli
Usage: python3 scripts/subset-fonts.py
"""
import glob, os, subprocess, sys

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "wp-content/themes/hri-theme/assets/fonts")
# Must match RANGES.latin in tokens/build.mjs (the @font-face unicode-range)
LATIN = "U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD"
FEATURES = "kern,liga,ccmp,locl,mark,mkmk,calt,clig"

files = sorted([x for x in glob.glob(f"{ROOT}/kanit/kanit-latin-*-normal.woff2") if "-ext-" not in x]) + [f"{ROOT}/montserrat/montserrat-latin-wght-normal.woff2"]
before = after = 0
for f in files:
    tmp = f + ".tmp"
    size = os.path.getsize(f)
    subprocess.run(["pyftsubset", f, f"--unicodes={LATIN}", "--flavor=woff2", f"--output-file={tmp}",
                    f"--layout-features={FEATURES}", "--no-hinting", "--desubroutinize"], check=True)
    new = os.path.getsize(tmp)
    if new < size:
        os.replace(tmp, f)
    else:
        os.remove(tmp)
        new = size
    before += size
    after += new
    print(f"{os.path.basename(f):42} {size:>7} -> {new:>7}")
print(f"total {before} -> {after} ({100 - after * 100 // before}% smaller)")
