<?php
/**
 * Title: Contact — Map
 * Slug: hri/contact-map
 * Categories: hri
 * Keywords: map, location, แผนที่
 * Description: Google Map of the office (Custom HTML block with an iframe; change the address in its q= parameter) and a link to open it.
 *
 * @package hri-theme
 */

$hri_query = rawurlencode( 'Soi Chulalongkorn 5, Wang Mai, Pathum Wan, Bangkok 10330' );
$hri_embed = 'https://www.google.com/maps?q=' . $hri_query . '&output=embed';
$hri_open  = 'https://www.google.com/maps/search/?api=1&query=' . $hri_query;

$hri_out  = hri_bm_section( 'hri-map-section', 'base-alt', 'map', '', '70', '70' );
$hri_out .= hri_bm_head( __( 'สำนักงาน', 'hri-theme' ), __( 'แผนที่และการเดินทาง', 'hri-theme' ), __( 'ซอยจุฬาลงกรณ์ 5 เขตปทุมวัน กรุงเทพมหานคร', 'hri-theme' ), '', true );
$hri_out .= '<!-- wp:html -->' . "\n"
	. '<div class="hri-map"><iframe title="' . esc_attr__( 'แผนที่สำนักงาน HRI DeOne', 'hri-theme' ) . '" src="' . esc_url( $hri_embed ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>' . "\n"
	. '<!-- /wp:html -->' . "\n";
$hri_out .= hri_bm_buttons( array( array( __( 'เปิดใน Google Maps', 'hri-theme' ), $hri_open, 'outline' ) ), 'center' );
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
