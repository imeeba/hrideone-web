<?php
/**
 * Title: About — Closing call to action
 * Slug: hri/about-cta
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Dark band with buttons to the three services and the contact page.
 *
 * @package hri-theme
 */

echo hri_bm_ctaband( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	__( 'มาสร้างผู้นำและองค์กรที่เติบโตไปด้วยกัน', 'hri-theme' ),
	__( 'เล่าโจทย์ให้เราฟัง แล้วเราจะช่วยเลือกบริการที่เหมาะกับองค์กรของคุณ', 'hri-theme' ),
	array(
		array( __( 'ดูบริการ', 'hri-theme' ), '/in-house/' ),
		array( __( 'ติดต่อเรา', 'hri-theme' ), hri_bm_contact() ),
	)
);
