<?php
/**
 * Search form block (server-rendered).
 *
 * variant "dropdown": a <details> menu opened from a search icon (desktop header, >= 1100px).
 * variant "inline":   the field itself, inside the mobile / tablet menu (< 1100px).
 * variant "page":     the field on the results page (and the 404 page), filled with the current query.
 *
 * Plain GET form to the home page of the current language: works without JavaScript.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$variant = in_array( $attributes['variant'] ?? '', array( 'inline', 'page' ), true ) ? $attributes['variant'] : 'dropdown';
$id      = wp_unique_id( 'hri-search-' );
$value   = 'page' === $variant && function_exists( 'hri_search_query_string' ) ? hri_search_query_string() : '';

$form = sprintf(
	'<form class="hri-sf__form" role="search" method="get" action="%1$s"><label class="screen-reader-text" for="%2$s">%3$s</label><input class="hri-sf__input" id="%2$s" type="search" name="s" value="%4$s" placeholder="%5$s" maxlength="%6$d" required autocomplete="off" enterkeyhint="search" /><button class="hri-sf__submit" type="submit">%7$s<span class="hri-sf__submit-label">%8$s</span></button></form>',
	esc_url( function_exists( 'hri_search_action_url' ) ? hri_search_action_url() : home_url( '/' ) ),
	esc_attr( $id ),
	esc_html__( 'ค้นหาในเว็บไซต์', 'hri-core' ),
	esc_attr( $value ),
	esc_attr__( 'ค้นหาหลักสูตร บทความ…', 'hri-core' ),
	defined( 'HRI_SEARCH_MAX_CHARS' ) ? HRI_SEARCH_MAX_CHARS : 100,
	hri_icon( 'magnifying-glass', 'hri-sf__icon' ), // phpcs:ignore WordPress.Security.EscapeOutput -- local SVG.
	esc_html__( 'ค้นหา', 'hri-core' )
);

$wrapper = get_block_wrapper_attributes( array( 'class' => 'hri-sf hri-sf--' . $variant ) );

if ( 'dropdown' === $variant ) {
	printf(
		'<div %1$s><details class="hri-sf__details"><summary aria-label="%2$s">%3$s</summary><div class="hri-sf__panel">%4$s</div></details></div>',
		$wrapper, // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
		esc_attr__( 'ค้นหา', 'hri-core' ),
		hri_icon( 'magnifying-glass', 'hri-sf__icon' ), // phpcs:ignore WordPress.Security.EscapeOutput -- local SVG.
		$form // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
	);
} else {
	printf( '<div %1$s>%2$s</div>', $wrapper, $form ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
}
