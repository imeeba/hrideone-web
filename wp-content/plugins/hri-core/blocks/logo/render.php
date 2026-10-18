<?php
/**
 * HRI Logo block. The SVG files live in the theme (hri-theme/assets/images/logo/); without them
 * the site title is shown instead.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$variants = array(
	'color'      => array( 'logo.svg', 1871.3, 425.7 ),
	'white'      => array( 'logo-white.svg', 1871.3, 425.7 ),
	'black'      => array( 'logo-black.svg', 1871.3, 425.7 ),
	'mark'       => array( 'mark.svg', 459, 425.7 ),
	'mark-white' => array( 'mark-white.svg', 459, 425.7 ),
);
$variant = $variants[ $attributes['variant'] ?? 'color' ] ?? $variants['color'];
$width   = max( 24, min( 600, (int) ( $attributes['width'] ?? 200 ) ) );
$height  = (int) round( $width * $variant[2] / $variant[1] );
$file    = 'assets/images/logo/' . $variant[0];
$name    = get_bloginfo( 'name' );

$art = file_exists( get_theme_file_path( $file ) )
	? sprintf( '<img src="%1$s" width="%2$d" height="%3$d" alt="%4$s" decoding="async">', esc_url( get_theme_file_uri( $file ) ), $width, $height, esc_attr( $name ) )
	: '<span class="hri-logo__text">' . esc_html( $name ) . '</span>';

if ( ! empty( $attributes['link'] ) ) {
	$home = function_exists( 'pll_home_url' ) && hri_has_polylang() ? pll_home_url() : home_url( '/' );
	$art  = sprintf( '<a href="%1$s" rel="home">%2$s</a>', esc_url( $home ), $art );
}

echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'hri-logo' ) ) . '>' . $art . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
