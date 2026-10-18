<?php
/**
 * Contact CTA band: title and text from Settings → ข้อมูลบริษัท, a quote button to the Contact page
 * and a LINE button. Not shown on the Contact page itself (any language).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$contact = hri_contact_url();
if ( hri_is_contact_page() ) {
	return;
}

$title = hri_company( 'cta_title' );
$text  = hri_company( 'cta_text' );
$line  = hri_company( 'line_url' );

$buttons = sprintf( '<a class="hri-cta__button hri-cta__button--primary" href="%1$s">%2$s</a>', esc_url( $contact ), esc_html__( 'ขอใบเสนอราคา', 'hri-core' ) );
if ( $line ) {
	$buttons .= sprintf( '<a class="hri-cta__button hri-cta__button--line" href="%1$s" rel="noopener" target="_blank">%2$s<span class="screen-reader-text"> %3$s</span></a>', esc_url( $line ), esc_html__( 'แอด LINE', 'hri-core' ), esc_html__( '(เปิดในแท็บใหม่)', 'hri-core' ) );
}

printf(
	'<section %1$s aria-labelledby="hri-cta-title"><div class="hri-cta__inner"><div class="hri-cta__copy"><h2 id="hri-cta-title" class="hri-cta__title">%2$s</h2>%3$s</div><div class="hri-cta__actions">%4$s</div></div></section>',
	get_block_wrapper_attributes( array( 'class' => 'hri-cta' ) ), // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
	esc_html( $title ),
	$text ? '<p class="hri-cta__text">' . esc_html( $text ) . '</p>' : '', // phpcs:ignore WordPress.Security.EscapeOutput -- escaped here.
	$buttons // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
);
