<?php
/**
 * Title: Transformation — Hero
 * Slug: hri/transformation-hero
 * Categories: hri
 * Keywords: transformation, consulting, hero
 * Description: Page introduction: headline, why transformation, consultation / scope buttons, key facts and a photo.
 *
 * @package hri-theme
 */

echo hri_bm_pagehero( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow'   => 'Transformation Solutions',
		'title'     => __( 'บริการ Consulting เพื่อการ Transform องค์กรอย่างยั่งยืน', 'hri-theme' ),
		'lead'      => __( 'องค์กรที่จะประสบความสำเร็จในทศวรรษหน้า ไม่ใช่องค์กรที่ตามการเปลี่ยนแปลงทัน แต่คือองค์กรที่ถูกออกแบบมาเพื่อการเปลี่ยนแปลงตั้งแต่ต้น', 'hri-theme' ),
		'buttons'   => array(
			array( __( 'นัดคุยกับที่ปรึกษา', 'hri-theme' ), hri_bm_contact() ),
			array( __( 'ดู 4 Scope of Transformation', 'hri-theme' ), '#scope', 'outline' ),
		),
		'chips'     => array(
			__( 'ครบ 4 มิติ: กลยุทธ์ เทคโนโลยี คน วัฒนธรรม', 'hri-theme' ),
			__( 'เครือข่ายผู้เชี่ยวชาญที่คัดสรร', 'hri-theme' ),
			__( 'ทีมภายในขับเคลื่อนการเปลี่ยนแปลงได้เอง', 'hri-theme' ),
		),
		'photo_alt' => __( 'ทีมที่ปรึกษาวางแผนการเปลี่ยนแปลงองค์กร', 'hri-theme' ),
	)
);
