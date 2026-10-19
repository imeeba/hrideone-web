<?php
/**
 * Title: Public Training — Hero
 * Slug: hri/public-hero
 * Categories: hri
 * Keywords: public, hero, intro, แนะนำ
 * Description: Page introduction: headline, why public training, open courses / contact buttons, key facts and a photo.
 *
 * @package hri-theme
 */

echo hri_bm_pagehero( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow'   => 'Public Training',
		'title'     => __( 'หลักสูตรอบรม Public Training สำหรับพนักงานในองค์กรทุกระดับ', 'hri-theme' ),
		'lead'      => __( 'เรียนรู้จากผู้เชี่ยวชาญตัวจริง พร้อมแลกเปลี่ยนกับผู้บริหารและผู้เข้าอบรมจากหลากหลายอุตสาหกรรม เปิดโลกทัศน์ ขยายมุมมอง และเพิ่มศักยภาพในการทำงาน', 'hri-theme' ),
		'buttons'   => array(
			array( __( 'ดูหลักสูตรที่เปิดรับสมัคร', 'hri-theme' ), '#courses' ),
			array( __( 'สอบถาม / สมัครอบรม', 'hri-theme' ), hri_bm_contact(), 'outline' ),
		),
		'chips'     => array(
			__( 'เปิดรับสมัครรุ่นใหม่ต่อเนื่อง', 'hri-theme' ),
			__( 'ผู้เข้าอบรมจากหลากหลายอุตสาหกรรม', 'hri-theme' ),
			__( 'ตั้งแต่พนักงานถึงผู้บริหารระดับสูง', 'hri-theme' ),
		),
		'photo_alt' => __( 'บรรยากาศการอบรม Public Training', 'hri-theme' ),
	)
);
