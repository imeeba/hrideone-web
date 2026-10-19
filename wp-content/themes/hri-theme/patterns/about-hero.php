<?php
/**
 * Title: About — Hero
 * Slug: hri/about-hero
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Page introduction: headline, mission line, buttons to services and contact, and a photo.
 *
 * @package hri-theme
 */

echo hri_bm_pagehero( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow'   => 'About HRI DeOne',
		'title'     => 'Develop Leaders. Transform the Future.',
		'lead'      => __( 'HRI DeOne คือพันธมิตรด้านการพัฒนาภาวะผู้นำและการเปลี่ยนแปลงองค์กร เกิดจากการรวมกันของ HR Institute และ DeOne Academy เพื่อเชื่อมญี่ปุ่น ไทย และอาเซียนเข้าด้วยกัน', 'hri-theme' ),
		'buttons'   => array(
			array( __( 'ดูบริการของเรา', 'hri-theme' ), '/in-house/' ),
			array( __( 'ติดต่อเรา', 'hri-theme' ), hri_bm_contact(), 'outline' ),
		),
		'chips'     => array( __( 'ก่อตั้งงานกับองค์กรญี่ปุ่นตั้งแต่ปี 1993', 'hri-theme' ), __( 'มากกว่า 30 ปีร่วมกับบริษัทญี่ปุ่น', 'hri-theme' ), 'Japan · Thailand · ASEAN' ),
		'photo_alt' => __( 'ทีมงาน HRI DeOne', 'hri-theme' ),
	)
);
