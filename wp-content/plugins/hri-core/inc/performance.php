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
