<?php
/**
 * Title: Japanese Concept — Why Japan
 * Slug: hri/japanese-intro
 * Categories: hri
 * Keywords: japanese, 100 year, ho-ren-so, kaizen
 * Description: The 100-year-company fact and three everyday Japanese practices (Ho-Ren-So, Kaizen, working with Japan).
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head(
	__( 'ทำไมต้องแนวคิดแบบญี่ปุ่น', 'hri-theme' ),
	__( 'ญี่ปุ่นมีบริษัทอายุเกิน 100 ปีมากที่สุดในโลก', 'hri-theme' ),
	__( 'ความยั่งยืนนี้ไม่ได้มาจากโชคหรือเทคโนโลยี แต่มาจากวินัยในการทำงานประจำวัน การสื่อสารที่เปิดกว้าง และการปรับปรุงเล็ก ๆ อย่างต่อเนื่อง HRI DeOne ถ่ายทอดแนวคิดนี้ผ่าน HR Institute ประเทศญี่ปุ่น ซึ่งพัฒนาบุคลากรให้องค์กรชั้นนำของญี่ปุ่นมาตั้งแต่ปี 1993 และปรับให้เข้ากับบริบทธุรกิจไทย', 'hri-theme' ),
	'',
	true
);
$hri_out .= hri_bm_cards(
	array(
		array(
			'kicker' => 'Ho-Ren-So',
			'title'  => __( 'ปัญหาเล็กไม่เคยถูกปล่อย จนกลายเป็นเรื่องใหญ่', 'hri-theme' ),
			'text'   => __( 'วัฒนธรรม Ho-Ren-So (รายงาน แจ้ง ปรึกษา) ทำให้ปัญหาถูกพูดถึงตั้งแต่ยังเล็ก ก่อนจะบานปลาย', 'hri-theme' ),
		),
		array(
			'kicker' => 'Kaizen',
			'title'  => __( 'เก่งขึ้นทุกวัน โดยไม่ต้องรอการเปลี่ยนแปลงครั้งใหญ่', 'hri-theme' ),
			'text'   => __( 'Kaizen คือการปรับปรุงทีละ 1% ในงานประจำวัน โดยไม่ต้องใช้งบประมาณก้อนใหญ่', 'hri-theme' ),
		),
		array(
			'kicker' => 'Cross-cultural',
			'title'  => __( 'ทำงานกับญี่ปุ่นได้อย่างเข้าใจ ไม่ใช่แค่ทำตาม', 'hri-theme' ),
			'text'   => __( 'เมื่อเข้าใจเหตุผลเบื้องหลังวิธีสื่อสารแบบญี่ปุ่น ความขัดแย้งจะกลายเป็นความร่วมมือที่แน่นแฟ้นกับบริษัทแม่ พันธมิตร และลูกค้า', 'hri-theme' ),
		),
	),
	3
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
