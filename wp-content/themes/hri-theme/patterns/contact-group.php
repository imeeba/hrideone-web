<?php
/**
 * Title: Contact — Group companies
 * Slug: hri/contact-group
 * Categories: hri
 * Keywords: group, japan, vietnam, บริษัทในกลุ่ม
 * Description: HRI Group companies in Japan and Vietnam (trust signal).
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( 'hri-group-section', '', 'group', '', '70', '80' );
$hri_out .= hri_bm_head( __( 'บริษัทในกลุ่ม', 'hri-theme' ), 'Our Group Companies', __( 'เครือข่ายพันธมิตรที่เชื่อมประเทศญี่ปุ่น ไทย และอาเซียนเข้าด้วยกัน', 'hri-theme' ) );
$hri_out .= hri_bm_cards(
	array(
		array(
			'kicker' => __( 'ญี่ปุ่น · โตเกียว', 'hri-theme' ),
			'title'  => 'HR Institute Co., Ltd.',
			'text'   => '1-13-23 Jingumae, Shibuya-ku, Tokyo 150-0001, Japan',
		),
		array(
			'kicker' => __( 'เวียดนาม · โฮจิมินห์', 'hri-theme' ),
			'title'  => 'HRI Vietnam',
			'text'   => 'Sunwah Innovation Center, Ho Chi Minh City, Vietnam',
		),
	),
	2
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
