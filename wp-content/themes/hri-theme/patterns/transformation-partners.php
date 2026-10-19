<?php
/**
 * Title: Transformation — Consulting partners
 * Slug: hri/transformation-partners
 * Categories: hri
 * Keywords: transformation, partners, logos
 * Description: Partner logo wall (Gallery, style "Logos", 4 per row). Replace the placeholders and give each logo the company name as alt text.
 *
 * @package hri-theme
 */

$hri_logo = get_theme_file_uri( 'assets/images/placeholder/logo.svg' );
$hri_out  = hri_bm_section( 'hri-partners-section' );
$hri_out .= hri_bm_head( __( 'พาร์ทเนอร์ที่ปรึกษาของเรา', 'hri-theme' ), 'Our Partners', 'A trusted partner in shaping organizations that thrive across generations.' );
$hri_out .= "<!-- wp:gallery {\"columns\":4,\"imageCrop\":false,\"linkTo\":\"none\",\"className\":\"is-style-logos hri-logos--4\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-4 is-style-logos hri-logos--4\">";
for ( $hri_i = 0; $hri_i < 8; $hri_i++ ) {
	$hri_out .= "<!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"" . esc_url( $hri_logo ) . "\" alt=\"\"/></figure>\n<!-- /wp:image -->\n";
}
$hri_out .= "</figure>\n<!-- /wp:gallery -->\n";
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
