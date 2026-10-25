<?php
/**
 * Router for the PHP built-in server used by scripts/local-setup.sh.
 *
 * Without a router, `php -S` answers 404 itself for any missing path that has an extension, so
 * WordPress never sees /wp/sitemap.xml or /wp/llms.txt. Real files are served as
 * usual; everything else under /wp/ goes to WordPress, like the rewrite rules of a real host.
 *
 * Usage: php -S 127.0.0.1:8899 -t <site-dir> scripts/local-router.php
 */

$path = (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
$file = $_SERVER['DOCUMENT_ROOT'] . $path;

if ( '/' !== $path && is_file( $file ) ) {
	return false; // Let the built-in server send the file.
}

// The site lives at /wp/. WordPress answers robots.txt only when installed at the domain root,
// so locally read it at /wp/?robots=1 (the real host serves /robots.txt).
if ( str_starts_with( $path, '/wp/' ) || '/wp' === $path ) {
	if ( is_dir( $file ) && is_file( rtrim( $file, '/' ) . '/index.php' ) ) {
		return false;
	}
	$_SERVER['SCRIPT_NAME']     = '/wp/index.php';
	$_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . '/wp/index.php';
	$_SERVER['PHP_SELF']        = '/wp/index.php';
	chdir( $_SERVER['DOCUMENT_ROOT'] . '/wp' );
	require $_SERVER['DOCUMENT_ROOT'] . '/wp/index.php';
	return true;
}

return false;
