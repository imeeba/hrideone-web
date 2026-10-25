<?php
/**
 * Front-end speed: minified CSS, small stylesheets inlined, speculative loading.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register a stylesheet of this plugin, using the committed `.min.css` when there is one
 * (`node scripts/minify-css.mjs` writes it). SCRIPT_DEBUG loads the readable original.
 *
 * Registers the file path too, so WordPress can print small stylesheets inline instead of
 * as extra render-blocking requests (see the `styles_inline_size_limit` filter below).
 *
 * @param string   $handle Style handle.
 * @param string   $file   File under assets/, e.g. `courses.css`.
 * @param string[] $deps   Dependencies.
 */
function hri_register_plugin_style( string $handle, string $file, array $deps = array() ): void {
	$min  = preg_replace( '/\.css$/', '.min.css', $file );
	$file = ( ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) && file_exists( HRI_CORE_DIR . 'assets/' . $min ) ) ? $min : $file;

	wp_register_style( $handle, plugins_url( 'assets/' . $file, HRI_CORE_DIR . 'hri-core.php' ), $deps, HRI_CORE_VERSION );
	wp_style_add_data( $handle, 'path', HRI_CORE_DIR . 'assets/' . $file );
}

/**
 * WordPress prints stylesheets under this many bytes (all together, smallest first) inline in the
 * <head>. Default is 20 KB. Raised so the many small hri-core and core block files stop costing a
 * request each; the big theme stylesheet (base.css) stays a cacheable file. Filter: hri_inline_css_limit.
 */
add_filter(
	'styles_inline_size_limit',
	static function ( $limit ) {
		return max( (int) $limit, (int) apply_filters( 'hri_inline_css_limit', 40000 ) );
	}
);

/* -------------------------------------------------------------------------
 * Image sizes
 *
 * An <img> without width and height lets the page jump when the file arrives, and WordPress does not
 * lazy-load it either. Images the editor inserts from the media library get both from core. Images
 * that arrive as plain URLs (theme files, seeded demo content) get them here, when the page is built,
 * so the stored markup stays as the editor wrote it (adding them there fails block validation).
 * ---------------------------------------------------------------------- */

/**
 * Width and height of an image URL, or array() when unknown.
 *
 * Order: filter `hri_image_dimensions` → table in option `hri_image_dims` (demo images, key = path after
 * "/playground/") → a file under uploads or the theme/plugin (read once, cached) → SVG viewBox.
 *
 * @param string $src Image URL.
 * @return int[] array( width, height ) or empty.
 */
function hri_image_dimensions( string $src ): array {
	static $memo = array();
	if ( isset( $memo[ $src ] ) ) {
		return $memo[ $src ];
	}
	$dims = apply_filters( 'hri_image_dimensions', null, $src );

	if ( null === $dims ) {
		$dims  = array();
		$table = get_option( 'hri_image_dims' );
		if ( is_array( $table ) && preg_match( '#/playground/([^?\#]+)#', $src, $m ) && isset( $table[ $m[1] ] ) ) {
			$dims = array_map( 'intval', (array) $table[ $m[1] ] );
		}
	}

	if ( null === $dims || ! $dims ) {
		$path = '';
		foreach ( array( wp_get_upload_dir()['baseurl'] => wp_get_upload_dir()['basedir'], get_theme_file_uri() => get_theme_file_path(), plugins_url() => WP_PLUGIN_DIR ) as $url => $dir ) {
			if ( $url && 0 === strpos( $src, $url . '/' ) ) {
				$path = $dir . '/' . strtok( substr( $src, strlen( $url ) + 1 ), '?#' );
				break;
			}
		}
		if ( $path && is_readable( $path ) ) {
			$key  = 'hri_dim_' . md5( $path . filemtime( $path ) );
			$dims = wp_cache_get( $key, 'hri' );
			if ( ! is_array( $dims ) ) {
				$dims = array();
				if ( preg_match( '/\.svg$/i', $path ) ) {
					$head = (string) file_get_contents( $path, false, null, 0, 2048 ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- local file.
					if ( preg_match( '/viewBox="[\d.\-]+[ ,]+[\d.\-]+[ ,]+([\d.]+)[ ,]+([\d.]+)"/', $head, $v ) && $v[1] > 0 && $v[2] > 0 ) {
						$dims = array( (int) round( (float) $v[1] ), (int) round( (float) $v[2] ) );
					}
				} else {
					$size = wp_getimagesize( $path );
					if ( $size && $size[0] > 0 && $size[1] > 0 ) {
						$dims = array( (int) $size[0], (int) $size[1] );
					}
				}
				wp_cache_set( $key, $dims, 'hri' );
			}
		}
	}

	$memo[ $src ] = ( is_array( $dims ) && 2 === count( $dims ) && $dims[0] > 0 && $dims[1] > 0 ) ? array_values( $dims ) : array();
	return $memo[ $src ];
}

/**
 * Add width and height to <img> tags in post content that lack them. Runs before core's
 * wp_filter_content_tags (priority 12), which then adds loading="lazy" / fetchpriority.
 */
add_filter(
	'the_content',
	static function ( $content ) {
		if ( ! is_string( $content ) || false === strpos( $content, '<img' ) ) {
			return $content;
		}
		$tags = new WP_HTML_Tag_Processor( $content );
		while ( $tags->next_tag( 'img' ) ) {
			$src = $tags->get_attribute( 'src' );
			if ( ! is_string( $src ) || '' === $src || ( $tags->get_attribute( 'width' ) && $tags->get_attribute( 'height' ) ) ) {
				continue;
			}
			$dims = hri_image_dimensions( $src );
			if ( $dims ) {
				$tags->set_attribute( 'width', (string) $dims[0] );
				$tags->set_attribute( 'height', (string) $dims[1] );
			}
		}
		return $tags->get_updated_html();
	},
	11
);

/* -------------------------------------------------------------------------
 * Speculative loading (core: Speculation Rules)
 *
 * Core defaults to prefetching on click ("conservative"). "moderate" prerenders a page when the pointer
 * rests on its link for ~200 ms (desktop) or on touch-down areas in view (mobile, per browser), so the
 * click opens it almost instantly. Cost: one extra page render per hover on the host. Chromium only;
 * other browsers ignore it. Turn off or change from code: add_filter( 'hri_speculation', ... ).
 * ---------------------------------------------------------------------- */
add_filter(
	'wp_speculation_rules_configuration',
	static function ( $config ) {
		$config = apply_filters(
			'hri_speculation',
			array(
				'mode'      => 'prerender',
				'eagerness' => 'moderate',
			)
		);
		return is_array( $config ) ? $config : null; // null = off.
	}
);

/** Not content. (Core already skips every URL with a query string, so filters, search and form results are never speculated.) */
add_filter(
	'wp_speculation_rules_href_exclude_paths',
	static function ( $paths ) {
		return array_merge( (array) $paths, array( '/wp-json/*', '/feed/*' ) );
	}
);
