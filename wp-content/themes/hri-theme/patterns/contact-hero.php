<?php
/**
 * Title: Contact — Banner
 * Slug: hri/contact-hero
 * Categories: hri
 * Keywords: contact, banner, ติดต่อ
 * Description: Page title with a short promise (reply within 24 hours).
 *
 * @package hri-theme
 */

echo hri_bm_banner( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow' => 'Contact Us',
		'title'   => __( 'ติดต่อเรา', 'hri-theme' ),
		'lead'    => __( 'เล่าโจทย์ขององค์กร หรือสอบถามหลักสูตรที่สนใจ ทีมงานจะติดต่อกลับพร้อมแนวทางที่เหมาะกับคุณ', 'hri-theme' ),
		'chips'   => array(
			__( 'ตอบกลับภายใน 24 ชั่วโมง', 'hri-theme' ),
			__( 'โทร · LINE · อีเมล', 'hri-theme' ),
			'Onsite · Online · Hybrid',
		),
	)
);
