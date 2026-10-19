<?php
/**
 * Title: In-house — Enquiry form
 * Slug: hri/inhouse-enquiry
 * Categories: hri
 * Keywords: in-house, form, enquiry, ฟอร์ม, ปรึกษา
 * Description: Short enquiry form (Lead Form block, "In-house" pre-selected) with phone and LINE. Anchor #enquiry.
 *
 * @package hri-theme
 */

echo hri_bm_enquiry( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	'enquiry',
	__( 'ปรึกษาเรื่องหลักสูตร In-house', 'hri-theme' ),
	__( 'เล่าโจทย์ให้เราฟัง แล้วเราจะออกแบบหลักสูตรที่เหมาะกับทีมของคุณ', 'hri-theme' ),
	__( 'บอกเราว่าอยากพัฒนาทีมด้านไหน จำนวนผู้เรียน และช่วงเวลาที่สะดวก ทีมงานจะติดต่อกลับพร้อมแนวทางและใบเสนอราคา', 'hri-theme' ),
	'inhouse'
);
