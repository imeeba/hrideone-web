<?php
/**
 * Title: Contact — Form and details
 * Slug: hri/contact-main
 * Categories: hri
 * Keywords: contact, form, lead, ติดต่อ, ฟอร์ม
 * Description: The enquiry form (Lead Form block) next to phone, LINE, e-mail, address and company registration. Details come from Settings → ข้อมูลบริษัท.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( 'hri-contact-section', '', 'contact-form', '', '70', '70' );
$hri_out .= hri_bm_group( array( 'className' => 'hri-enquiry__grid' ) );
$hri_out .= hri_bm_group( array( 'className' => 'is-style-card hri-enquiry__form' ) );
$hri_out .= hri_bm_heading( __( 'ส่งข้อความถึงเรา', 'hri-theme' ), array( 'level' => 2, 'className' => 'hri-contact-section__title' ) );
$hri_out .= hri_bm_leadform();
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_group( array( 'className' => 'is-style-card hri-contactcard' ) );
$hri_out .= hri_bm_heading( __( 'ช่องทางติดต่อ', 'hri-theme' ), array( 'level' => 2, 'className' => 'hri-contact-section__title' ) );
$hri_out .= hri_bm_p( esc_html__( 'ต้องการคุยทันที โทรหรือทักทาง LINE ได้เลย', 'hri-theme' ), array( 'className' => 'hri-contactcard__lead' ) );
$hri_out .= hri_bm_contactinfo( array( 'phone', 'line', 'email', 'address', 'legal' ) );
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
