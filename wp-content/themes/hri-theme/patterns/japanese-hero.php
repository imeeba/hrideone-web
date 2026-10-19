<?php
/**
 * Title: Japanese Concept — Hero
 * Slug: hri/japanese-hero
 * Categories: hri
 * Keywords: japanese, concept, hero
 * Description: Page introduction: headline, the HR Institute (Japan) network, DO·WA·KEI / contact buttons, key facts and a photo.
 *
 * @package hri-theme
 */

echo hri_bm_pagehero( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow'   => 'Japanese Concept',
		'title'     => __( 'ยกระดับการทำงานอย่างมืออาชีพแบบ “ญี่ปุ่น” ตัวจริง', 'hri-theme' ),
		'lead'      => __( 'แนวคิดการทำงานแบบญี่ปุ่นที่เสริมทักษะการสื่อสารและการทำงานร่วมกันข้ามวัฒนธรรม ถ่ายทอดผ่านเครือข่าย HR Institute ประเทศญี่ปุ่นที่สั่งสมมากว่า 30 ปี และประยุกต์ใช้ในบริบทไทยอย่างเป็นระบบ', 'hri-theme' ),
		'buttons'   => array(
			array( __( 'ดูหลักสูตร DO·WA·KEI', 'hri-theme' ), '#do-wa-kei' ),
			array( __( 'สอบถามรายละเอียด', 'hri-theme' ), hri_bm_contact(), 'outline' ),
		),
		'chips'     => array(
			__( 'เครือข่าย HR Institute ประเทศญี่ปุ่นกว่า 30 ปี', 'hri-theme' ),
			__( 'เรียนรู้จากองค์กรร้อยปีในญี่ปุ่น', 'hri-theme' ),
			__( 'ประยุกต์ใช้ในบริบทไทย', 'hri-theme' ),
		),
		'photo_alt' => __( 'ผู้บริหารเรียนรู้แนวคิดการทำงานแบบญี่ปุ่น', 'hri-theme' ),
	)
);
