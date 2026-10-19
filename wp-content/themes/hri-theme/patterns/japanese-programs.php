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

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head( __( 'หลักสูตรและบริการ', 'hri-theme' ), 'Our Programs' );
$hri_out .= hri_bm_cards(
	array(
		array(
			'num'    => '01',
			'kicker' => 'Executive Leadership Journey',
			'title'  => 'DO·WA·KEI',
			'text'   => __( 'เส้นทางพัฒนาผู้บริหาร 3 ขั้น จากการเข้าใจตนเอง สู่การนำคนอย่างกลมกลืน และการวางรากฐานองค์กรให้ยืนได้ข้ามรุ่น', 'hri-theme' ),
			'link'   => array( __( 'ดูรายละเอียด DO·WA·KEI', 'hri-theme' ), '#do-wa-kei' ),
		),
		array(
			'num'   => '02',
			'title' => 'Japanese Working Philosophy',
			'text'  => __( 'หลักคิดและวิธีทำงานแบบญี่ปุ่นที่สร้างองค์กรยั่งยืน นำมาประยุกต์ใช้กับการทำงานประจำวันในบริบทไทย', 'hri-theme' ),
			'link'  => array( __( 'สอบถามหลักสูตรนี้', 'hri-theme' ), hri_bm_contact() ),
		),
		array(
			'num'   => '03',
			'title' => 'Cross-cultural Communication & Collaboration',
			'text'  => __( 'เสริมทักษะการสื่อสารและการทำงานร่วมกันระหว่างทีมไทยและญี่ปุ่น ลดความเข้าใจผิด และสร้างความไว้วางใจข้ามวัฒนธรรม', 'hri-theme' ),
			'link'  => array( __( 'สอบถามหลักสูตรนี้', 'hri-theme' ), hri_bm_contact() ),
		),
	),
	3
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
