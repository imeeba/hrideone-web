<?php
/**
 * Title: Blog — Closing call to action
 * Slug: hri/blog-cta
 * Categories: hri
 * Keywords: blog, cta
 * Description: Dark band at the end of the blog pages and posts: talk to the team or see the services.
 *
 * @package hri-theme
 */

echo hri_bm_ctaband( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	__( 'อยากนำแนวคิดเหล่านี้ไปใช้ในองค์กรของคุณ?', 'hri-theme' ),
	__( 'ปรึกษาทีมงานเพื่อเลือกหลักสูตรหรือโครงการที่เหมาะกับโจทย์ของคุณ', 'hri-theme' ),
	array(
		array( __( 'ปรึกษาทีมงาน', 'hri-theme' ), hri_bm_contact() ),
		array( __( 'ดูหลักสูตรทั้งหมด', 'hri-theme' ), '/courses/' ),
	)
);
