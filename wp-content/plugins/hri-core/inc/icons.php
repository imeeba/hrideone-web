<?php
/**
 * Icons: Phosphor Icons, weight "light" (MIT) — files built by scripts/build-icons.py.
 *
 * - PHP:  hri_icon( 'globe' ) prints an inline SVG (colour = text colour, size = 1.25em).
 * - CSS:  mask: var(--hri-icon-check) center / contain no-repeat; background: currentColor;
 *         (the variables are printed inline on every page, ~1.7 KB).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG of an icon from assets/icons/. Decorative (aria-hidden) unless $label is given.
 *
 * @param string $name  File name without .svg (see ICONS in scripts/build-icons.py).
 * @param string $class Extra class names.
 * @param string $label Accessible name; '' = hidden from screen readers.
 */
function hri_icon( string $name, string $class = '', string $label = '' ): string {
	static $cache = array();
	$name = sanitize_key( $name );
	if ( ! isset( $cache[ $name ] ) ) {
		$file           = HRI_CORE_DIR . "assets/icons/{$name}.svg";
		$cache[ $name ] = is_readable( $file ) ? trim( (string) file_get_contents( $file ) ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions -- local file.
	}
	if ( '' === $cache[ $name ] ) {
		return '';
	}
	$attrs = sprintf( ' class="hri-icon hri-icon--%1$s%2$s"', esc_attr( $name ), $class ? ' ' . esc_attr( $class ) : '' );
	$attrs .= $label ? ' role="img" aria-label="' . esc_attr( $label ) . '"' : ' aria-hidden="true" focusable="false"';
	return preg_replace( '/^<svg /', '<svg' . $attrs . ' ', $cache[ $name ] );
}

/** CSS variables of the icons, inline, on the front end and inside the editor canvas. */
add_action(
	'enqueue_block_assets',
	static function () {
		$css = (string) file_get_contents( HRI_CORE_DIR . 'assets/icons.css' ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- local file.
		wp_register_style( 'hri-icons', false, array(), HRI_CORE_VERSION );
		wp_enqueue_style( 'hri-icons' );
		wp_add_inline_style( 'hri-icons', $css );
	}
);
