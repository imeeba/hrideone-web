#!/usr/bin/env bash
# Local test site: WordPress (from GitHub) + SQLite + WP-CLI + PHP built-in server.
# Theme and plugin are symlinked from this repo, so edits show up immediately.
#
# Usage: scripts/local-setup.sh [target-dir] [port]
#   then open http://127.0.0.1:<port>/wp/  (admin / admin)
set -euo pipefail

REPO="$(cd "$(dirname "$0")/.." && pwd)"
DIR="${1:-$REPO/../hri-local}"
PORT="${2:-8899}"
WP_VERSION="${WP_VERSION:-7.1.3}"
URL="http://127.0.0.1:$PORT/wp"

mkdir -p "$DIR" && DIR="$(cd "$DIR" && pwd)"
cd "$DIR"

[ -d wp ] || git clone -q --depth 1 --branch "$WP_VERSION" https://github.com/WordPress/WordPress wp
[ -d sqlite ] || git clone -q --depth 1 https://github.com/WordPress/sqlite-database-integration sqlite
[ -f wp-cli.phar ] || curl -sSL -o wp-cli.phar https://github.com/wp-cli/wp-cli/releases/download/v2.12.0/wp-cli-2.12.0.phar

PLUG=wp/wp-content/plugins/sqlite-database-integration
[ -d "$PLUG" ] || cp -rL sqlite/packages/plugin-sqlite-database-integration "$PLUG"
[ -f wp/wp-content/db.php ] || cp "$PLUG/db.copy" wp/wp-content/db.php
ln -sfn "$REPO/wp-content/themes/hri-theme" wp/wp-content/themes/hri-theme
ln -sfn "$REPO/wp-content/plugins/hri-core" wp/wp-content/plugins/hri-core
mkdir -p wp/wp-content/database

WP="php $DIR/wp-cli.phar --allow-root --path=$DIR/wp"
if [ ! -f wp/wp-config.php ]; then
	$WP config create --dbname=wp --dbuser=x --dbpass=x --skip-check --quiet --extra-php <<'PHP'
define( 'DB_DIR', __DIR__ . '/wp-content/database/' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
PHP
fi

if ! $WP core is-installed 2>/dev/null; then
	$WP core install --url="$URL" --title="HRI DeOne" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --skip-email --quiet
	$WP option update blogdescription "สถาบันฝึกอบรม Executive Training ชั้นนำ" --quiet
	$WP rewrite structure '/%postname%/' --quiet
	$WP theme activate hri-theme --quiet
	$WP plugin activate hri-core --quiet
	$WP eval-file "$REPO/playground/seed.php"
	$WP eval '$e = get_page_by_path( "style-guide" ); $id = $e ? $e->ID : wp_insert_post( array( "post_type" => "page", "post_status" => "publish", "post_title" => "Style Guide", "post_name" => "style-guide", "post_content" => "<!-- wp:pattern {\"slug\":\"hri/style-guide\"} /-->" ) ); update_option( "show_on_front", "page" ); update_option( "page_on_front", $id ); flush_rewrite_rules();'
fi

# Serve $DIR so the site lives at /wp/ (same as the original test setup).
if ! curl -s -o /dev/null "http://127.0.0.1:$PORT/"; then
	(cd "$DIR" && nohup php -S "127.0.0.1:$PORT" -t "$DIR" >"$DIR/server.log" 2>&1 &)
	sleep 1
fi
echo "Ready: $URL/  (admin / admin)   WP-CLI: $WP"
