<?php
/**
 * Company Text block: one per-language text from Settings → ข้อมูลบริษัท.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$field = (string) ( $attributes['field'] ?? 'tagline' );
$text  = in_array( $field, array( 'tagline', 'address', 'cta_title', 'cta_text' ), true ) ? hri_company( $field ) : '';

if ( '' !== $text ) {
	echo '<p ' . get_block_wrapper_attributes( array( 'class' => 'hri-company-text' ) ) . '>' . nl2br( esc_html( $text ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped here.
}
