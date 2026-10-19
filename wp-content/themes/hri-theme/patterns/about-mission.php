<?php
/**
 * Title: About — Mission and vision
 * Slug: hri/about-mission
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Mission and vision as two large cards. Edit the texts directly.
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-mission-section', '', 'mission', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( 'Mission & Vision', __( 'เราเชื่อในอะไร', 'hri-theme' ), '', '', true )
	. hri_bm_cards(
		array(
			array( 'kicker' => __( 'พันธกิจ', 'hri-theme' ), 'title' => 'Develop Leaders. Transform the Future.', 'text' => __( 'พัฒนาผู้นำและองค์กรด้วยความรู้ เครื่องมือ และการลงมือทำจริง เพื่อสร้างผลลัพธ์ที่ยั่งยืน', 'hri-theme' ) ),
			array( 'kicker' => __( 'วิสัยทัศน์', 'hri-theme' ), 'title' => 'A trusted partner in shaping organizations that thrive across generations.', 'text' => __( 'เป็นพันธมิตรที่องค์กรไว้วางใจ ในการสร้างองค์กรที่เติบโตได้ข้ามรุ่นข้ามยุค', 'hri-theme' ) ),
		),
		2,
		'hri-mission'
	)
	. hri_bm_section_end();
