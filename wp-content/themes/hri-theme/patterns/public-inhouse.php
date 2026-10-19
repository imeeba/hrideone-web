<?php
/**
 * Title: Public Training — In-house offer
 * Slug: hri/public-inhouse
 * Categories: hri
 * Keywords: public, in-house, cta
 * Description: Dark band: run a course for your own team (links to In-house and the quote form).
 *
 * @package hri-theme
 */

echo hri_bm_ctaband( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	__( 'อยากจัดหลักสูตรให้ทีมของคุณโดยเฉพาะ?', 'hri-theme' ),
	__( 'ให้เราออกแบบหลักสูตร In-house ที่ปรับเนื้อหา กรณีศึกษา และตารางเวลาให้ตรงกับองค์กรของคุณ', 'hri-theme' ),
	array(
		array( __( 'ดูบริการ In-house', 'hri-theme' ), '/in-house/' ),
		array( __( 'ขอใบเสนอราคา', 'hri-theme' ), hri_bm_contact() ),
	)
);
