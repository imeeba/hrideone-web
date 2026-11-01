#!/usr/bin/env bash
# Builds the zips for a host without WP-CLI/SSH (wp-admin upload or FTP):
#   hri-theme.zip  theme
#   hri-core.zip   plugin + setup/ (a copy of playground/ seed files, sample images, data), used by
#                  Tools → ตั้งค่าเว็บเริ่มต้น
# Usage: scripts/package-staging.sh [output-dir]   (default: dist/, ignored by git)
set -euo pipefail

REPO="$(cd "$(dirname "$0")/.." && pwd)"
OUT="${1:-$REPO/dist}"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
mkdir -p "$OUT" && OUT="$(cd "$OUT" && pwd)"

cp -r "$REPO/wp-content/themes/hri-theme" "$TMP/hri-theme"
cp -r "$REPO/wp-content/plugins/hri-core" "$TMP/hri-core"

S="$TMP/hri-core/setup"
mkdir -p "$S/data"
cp "$REPO"/playground/seed*.php "$S/"
cp -r "$REPO/playground/sample" "$S/sample"
cp "$REPO/playground/data/site-media.json" "$REPO/playground/data/image-dims.json" "$REPO/playground/data/blog-posts.json.gz" "$S/data/"
# Nothing in setup/ may be served as a page: block direct requests on Apache; NGINX hosts only run the PHP via wp-admin.
printf 'Require all denied\n' > "$S/.htaccess"
printf '<?php // Silence is golden.\n' > "$S/index.php"

find "$TMP" -name '.DS_Store' -delete
rm -f "$OUT/hri-theme.zip" "$OUT/hri-core.zip"
(cd "$TMP" && zip -qr "$OUT/hri-theme.zip" hri-theme && zip -qr "$OUT/hri-core.zip" hri-core)
ls -lh "$OUT"/hri-theme.zip "$OUT"/hri-core.zip
