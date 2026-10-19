<?php
/**
 * Title: About — Core values
 * Slug: hri/about-values
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Where Differences Become Strength: the two organizations behind HRI DeOne and what they bring.
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-values-section', 'base-alt', 'values', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( __( 'คุณค่าหลัก', 'hri-theme' ), 'Where Differences Become Strength.', __( 'HRI DeOne ไม่ใช่เพียงการรวมกันของสองบริษัท แต่เป็นการรวมสองจุดแข็ง สองวัฒนธรรม และวิสัยทัศน์เดียวกัน', 'hri-theme' ) )
	. hri_bm_cards(
		array(
			array( 'num' => '01', 'title' => 'HR Institute', 'text' => __( 'ได้รับความไว้วางใจจากองค์กรญี่ปุ่นตั้งแต่ปี 1993 ด้วยมาตรฐานและความละเอียดแบบญี่ปุ่น', 'hri-theme' ) ),
			array( 'num' => '02', 'title' => 'DeOne Academy', 'text' => __( 'เชี่ยวชาญด้าน Leadership และ Transformation พร้อมเครือข่ายวิทยากรและผู้บริหารพาร์ทเนอร์', 'hri-theme' ) ),
			array( 'num' => '03', 'title' => 'HRI DeOne', 'text' => __( 'ยกระดับสู่มาตรฐานสากล เป็นสะพานเชื่อมญี่ปุ่น ไทย และอาเซียน เพื่อสร้างผลลัพธ์จริงในยุค AI', 'hri-theme' ) ),
		),
		3,
		'hri-values'
	)
	. hri_bm_section_end();
