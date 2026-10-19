<?php
/**
 * Title: About — Our journey
 * Slug: hri/about-timeline
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Four milestones as numbered cards. Years and events are placeholders to confirm with the company.
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-timeline-section', '', 'journey', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( 'Our Journey', __( 'เส้นทางของเรา', 'hri-theme' ), '', '', true )
	. hri_bm_cards(
		array(
			array( 'kicker' => '1993', 'title' => 'HR Institute', 'text' => __( 'เริ่มงานพัฒนาบุคลากรร่วมกับองค์กรญี่ปุ่นในประเทศไทย', 'hri-theme' ) ),
			array( 'kicker' => 'DeOne Academy', 'title' => 'Leadership & Transformation', 'text' => __( 'สร้างหลักสูตรภาวะผู้นำและการเปลี่ยนแปลงองค์กรสำหรับองค์กรไทยและข้ามชาติ', 'hri-theme' ) ),
			array( 'kicker' => 'HRI DeOne', 'title' => __( 'รวมสองจุดแข็งเป็นหนึ่งเดียว', 'hri-theme' ), 'text' => __( 'ควบรวมเป็น HRI DeOne เพื่อให้บริการครบทั้งการเรียนรู้ ที่ปรึกษา และการเปลี่ยนแปลง', 'hri-theme' ) ),
			array( 'kicker' => 'Launch', 'title' => 'Develop Leaders, Transform the Future', 'text' => __( 'เปิดตัวอย่างเป็นทางการด้วยงานเปิดตัวร่วมกับลูกค้าและพันธมิตร', 'hri-theme' ) ),
		),
		4,
		'hri-program'
	)
	. hri_bm_section_end();
