<?php
/**
 * Title: Blog — Banner
 * Slug: hri/blog-hero
 * Categories: hri
 * Keywords: blog, articles, บทความ
 * Description: Blog page title with a short line.
 *
 * @package hri-theme
 */

echo hri_bm_banner( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow' => 'Knowledge Tanks',
		'title'   => __( 'บทความและองค์ความรู้', 'hri-theme' ),
		'lead'    => __( 'มุมมองและเครื่องมือด้านภาวะผู้นำ การขาย การโค้ช และการเปลี่ยนแปลงองค์กร จากทีมที่ทำงานกับองค์กรจริง', 'hri-theme' ),
	)
);
