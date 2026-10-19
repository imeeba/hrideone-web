<?php
/**
 * Title: Course — Details and enquiry
 * Slug: hri/single-course-enrol
 * Categories: hri
 * Description: Bottom of every course page: the course facts (dates, venue, price), the register button and the enquiry form with this course pre-selected.
 * Inserter: no
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( 'hri-enquiry hri-enrol', 'base-alt', 'enrol', '', '70', '70' );
$hri_out .= hri_bm_group( array( 'className' => 'hri-enquiry__grid' ) );
$hri_out .= hri_bm_group( array( 'className' => 'hri-enquiry__text' ) );
$hri_out .= hri_bm_eyebrow( __( 'รายละเอียดและการสมัคร', 'hri-theme' ), false );
$hri_out .= hri_bm_title( __( 'ข้อมูลหลักสูตร', 'hri-theme' ), false, '', 'hri-title-sentence' );
$hri_out .= '<!-- wp:hri/course-facts /-->' . "\n";
$hri_out .= '<!-- wp:hri/course-register {"secondary":false} /-->' . "\n";
$hri_out .= hri_bm_contactinfo( array( 'phone', 'line' ) );
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_group( array( 'className' => 'is-style-card hri-enquiry__form' ) );
$hri_out .= hri_bm_heading( __( 'ฝากข้อมูลให้ทีมงานติดต่อกลับ', 'hri-theme' ), array( 'level' => 3, 'className' => 'hri-contact-section__title' ) );
$hri_out .= hri_bm_leadform();
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
