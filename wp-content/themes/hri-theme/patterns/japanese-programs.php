<?php
/**
 * Title: Japanese Concept — Programs
 * Slug: hri/japanese-programs
 * Categories: hri
 * Keywords: japanese, programs, do wa kei
 * Description: The three Japanese Concept programs as cards.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( '', '', 'programs' );
$hri_out .= hri_bm_head( __( 'หลักสูตรและบริการ', 'hri-theme' ), 'Our Programs' );
$hri_out .= hri_bm_cards(
	array(
		array(
			'num'    => '01',
			'kicker' => 'Japan Leadership Journey',
			'title'  => 'DO·WA·KEI',
			'text'   => __( 'โปรแกรมผู้บริหารในไทยและญี่ปุ่น พัฒนาภาวะผู้นำจากภายในและเยี่ยมชมองค์กรอายุกว่าร้อยปี', 'hri-theme' ),
			'link'   => array( __( 'ดูรายละเอียด DO·WA·KEI', 'hri-theme' ), '#do-wa-kei' ),
		),
		array(
			'num'   => '02',
			'title' => 'Japanese Working Philosophy & Core Practice',
			'text'  => __( 'ปลูกฝังแนวปฏิบัติที่หล่อเลี้ยงองค์กรญี่ปุ่นมาหลายศตวรรษ เช่น Ho-Ren-So, Kaizen, PDCA และ 5S ให้ทีมไทยนำไปใช้ได้ทุกวัน', 'hri-theme' ),
			'link'  => array( __( 'สอบถามหลักสูตรนี้', 'hri-theme' ), hri_bm_contact() ),
		),
		array(
			'num'   => '03',
			'title' => 'Cross-cultural Communication & Collaboration',
			'text'  => __( 'เชื่อมช่องว่างวัฒนธรรมการทำงานไทย–ญี่ปุ่น ลดความเข้าใจผิด และสร้างความไว้วางใจกับบริษัทแม่ พันธมิตร และลูกค้าชาวญี่ปุ่น', 'hri-theme' ),
			'link'  => array( __( 'สอบถามหลักสูตรนี้', 'hri-theme' ), hri_bm_contact() ),
		),
	),
	3
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
