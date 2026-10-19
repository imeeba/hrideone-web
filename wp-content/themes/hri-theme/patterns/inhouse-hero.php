<?php
/**
 * Title: In-house — Hero
 * Slug: hri/inhouse-hero
 * Categories: hri
 * Keywords: in-house, hero, intro, แนะนำ
 * Description: Page introduction: headline, value statement, quote / courses buttons, key facts and a photo.
 *
 * @package hri-theme
 */

echo hri_bm_pagehero( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow'   => 'In-house Training',
		'title'     => __( 'เปลี่ยน “ทักษะพนักงาน” ให้เป็น “ขีดความสามารถในการแข่งขัน” อย่างยั่งยืน', 'hri-theme' ),
		'lead'      => __( 'หลักสูตรอบรมภายในองค์กรที่ออกแบบจากโจทย์จริงและเป้าหมายของแต่ละองค์กร ผสานประสบการณ์กว่า 20 ปีเข้ากับเครื่องมือการเรียนรู้สมัยใหม่ เพื่อให้ทีมนำไปใช้ได้ตั้งแต่วันแรก', 'hri-theme' ),
		'buttons'   => array(
			array( __( 'ขอใบเสนอราคา', 'hri-theme' ), hri_bm_contact() ),
			array( __( 'ดูหลักสูตรทั้งหมด', 'hri-theme' ), '#courses', 'outline' ),
		),
		'chips'     => array(
			__( 'ประสบการณ์กว่า 20 ปี', 'hri-theme' ),
			__( 'หลักสูตรด้านผู้นำกว่า 100 หลักสูตร', 'hri-theme' ),
			'Onsite · Online · Hybrid',
			__( 'ตอบกลับภายใน 24 ชั่วโมง', 'hri-theme' ),
		),
		'photo_alt' => __( 'บรรยากาศการอบรม In-house', 'hri-theme' ),
	)
);
