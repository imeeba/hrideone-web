#!/usr/bin/env python3
"""Width/height of the demo images → playground/data/image-dims.json.

hri-core (inc/performance.php) puts width/height on <img> tags that only have a URL, so the browser
reserves the space and WordPress can lazy-load them. For the demo, the sizes of the images that live in
this repo are listed here: key = path after "/playground/", value = [width, height].
Sources: playground/data/site-media.json (pages) and playground/media/manifest.json (blog).
Usage: python3 scripts/image-dims.py
"""
import json, os

HERE = os.path.dirname(os.path.abspath(__file__))
PG = os.path.join(HERE, "..", "playground")
dims = {}
for a in json.load(open(f"{PG}/data/site-media.json"))["assets"].values():
    if a.get("file") and a.get("width") and a.get("height"):
        dims[f"site-media/{a['file']}"] = [a["width"], a["height"]]
for m in json.load(open(f"{PG}/media/manifest.json")).values():
    if m.get("file") and m.get("width") and m.get("height"):
        dims[f"media/{m['file']}"] = [m["width"], m["height"]]
out = f"{PG}/data/image-dims.json"
new = json.dumps(dims, sort_keys=True, separators=(",", ":"))
if not os.path.exists(out) or open(out).read() != new:
    open(out, "w").write(new)
print(len(dims), "images,", len(new), "bytes")
