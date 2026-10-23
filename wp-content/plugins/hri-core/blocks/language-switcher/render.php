<?php
/**
 * Language switcher block (server-rendered).
 *
 * variant "dropdown": a <details> menu (desktop header). variant "list": the languages in a
 * row (inside the mobile / tablet menu).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$languages = hri_language_list();
if ( count( $languages ) < 2 ) {
	return;
}

$variant = 'list' === ( $attributes['variant'] ?? '' ) ? 'list' : 'dropdown';
$current = $languages[0];
foreach ( $languages as $language ) {
	if ( $language['current'] ) {
		$current = $language;
		break;
	}
}

$items = '';
foreach ( $languages as $language ) {
	if ( $language['current'] ) {
		$items .= sprintf( '<li><span class="is-current" lang="%1$s" aria-current="true">%2$s</span></li>', esc_attr( $language['lang'] ), esc_html( $language['name'] ) );
	} elseif ( $language['url'] ) {
		$items .= sprintf( '<li><a href="%1$s" lang="%2$s" hreflang="%2$s">%3$s</a></li>', esc_url( $language['url'] ), esc_attr( $language['lang'] ), esc_html( $language['name'] ) );
	} else {
		$items .= sprintf( '<li><span class="is-soon" lang="%1$s">%2$s</span></li>', esc_attr( $language['lang'] ), esc_html( $language['name'] ) );
	}
}

$wrapper = get_block_wrapper_attributes( array( 'class' => 'hri-lang hri-lang--' . $variant ) );

if ( 'dropdown' === $variant ) {
	printf(
		'<div %1$s><details class="hri-lang__details"><summary aria-label="%2$s">%5$s<span>%3$s</span>%6$s</summary><ul>%4$s</ul></details></div>',
		$wrapper, // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
		esc_attr__( 'เลือกภาษา / Language', 'hri-core' ),
		esc_html( $current['label'] ),
		$items, // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
		hri_icon( 'globe', 'hri-lang__globe' ), // phpcs:ignore WordPress.Security.EscapeOutput -- local SVG.
		hri_icon( 'caret-down', 'hri-lang__chevron' ) // phpcs:ignore WordPress.Security.EscapeOutput -- local SVG.
	);
} else {
	printf(
		'<nav %1$s aria-label="%2$s"><ul>%3$s</ul></nav>',
		$wrapper, // phpcs:ignore WordPress.Security.EscapeOutput
		esc_attr__( 'ภาษา / Language', 'hri-core' ),
		$items // phpcs:ignore WordPress.Security.EscapeOutput
	);
}
