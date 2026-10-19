<?php
/**
 * Title: Japanese Concept — Closing call to action
 * Slug: hri/japanese-cta
 * Categories: hri
 * Keywords: japanese, cta, apply
 * Description: Dark band with the closing line and apply / LINE buttons.
 *
 * @package hri-theme
 */

$hri_buttons = array( array( __( 'สมัครเลย', 'hri-theme' ), hri_bm_contact() ) );
$hri_line    = function_exists( 'hri_company' ) ? (string) hri_company( 'line_url' ) : '';
if ( $hri_line ) {
	$hri_buttons[] = array( __( 'สอบถามทาง LINE', 'hri-theme' ), $hri_line );
}

echo hri_bm_ctaband( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	'Build something that outlasts you.',
	__( 'สร้างสิ่งที่ยังคงยืนหยัดอยู่ได้ แม้ในวันที่คุณไม่ได้อยู่ตรงนั้นแล้ว', 'hri-theme' ),
	$hri_buttons
);
