<?php
/**
 * Title: Public Training — How to register
 * Slug: hri/public-steps
 * Categories: hri
 * Keywords: public, register, steps, สมัคร
 * Description: Three registration steps with a CTA button.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head( __( 'ขั้นตอนการสมัคร', 'hri-theme' ), 'How to Register' );
$hri_out .= hri_bm_steps(
	array(
		array( __( 'เลือกหลักสูตรและรุ่น', 'hri-theme' ), __( 'ดูรายละเอียด วันที่ และสถานะการรับสมัครของแต่ละหลักสูตร', 'hri-theme' ) ),
		array( __( 'ติดต่อสมัคร', 'hri-theme' ), __( 'กรอกแบบฟอร์ม โทร หรือทักทาง LINE แจ้งชื่อหลักสูตรและจำนวนผู้เข้าอบรม', 'hri-theme' ) ),
		array( __( 'ยืนยันที่นั่ง', 'hri-theme' ), __( 'ทีมงานติดต่อกลับเพื่อยืนยันการสมัครและแจ้งรายละเอียดก่อนวันอบรม', 'hri-theme' ) ),
	)
);
$hri_out .= hri_bm_buttons( array( array( __( 'สมัครหรือสอบถามรายละเอียด', 'hri-theme' ), hri_bm_contact() ) ), 'center' );
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
