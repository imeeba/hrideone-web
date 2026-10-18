<?php
/**
 * Language switcher block (server-rendered).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$languages = hri_language_list();
if ( count( $languages ) < 2 ) {
	return;
}

echo '<nav ' . get_block_wrapper_attributes( array( 'class' => 'hri-lang', 'aria-label' => __( 'ภาษา / Language', 'hri-core' ) ) ) . '><ul>';
foreach ( $languages as $language ) {
	echo '<li>';
	if ( $language['url'] && ! $language['current'] ) {
		printf(
			'<a href="%1$s" lang="%2$s" hreflang="%2$s" title="%3$s">%4$s</a>',
			esc_url( $language['url'] ),
			esc_attr( $language['lang'] ),
			esc_attr( $language['name'] ),
			esc_html( $language['label'] )
		);
	} else {
		printf(
			'<span lang="%1$s" title="%2$s"%3$s%4$s>%5$s</span>',
			esc_attr( $language['lang'] ),
			esc_attr( $language['name'] ),
			$language['current'] ? ' aria-current="true" class="is-current"' : '',
			$language['url'] ? '' : ' class="is-soon"',
			esc_html( $language['label'] )
		);
	}
	echo '</li>';
}
echo '</ul></nav>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
