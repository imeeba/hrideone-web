<?php
/**
 * Title: In-house — Why HRI DeOne
 * Slug: hri/inhouse-why
 * Categories: hri
 * Keywords: in-house, why, benefits, จุดเด่น
 * Description: Four reasons to train with HRI DeOne (numbered cards).
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( '', 'surface-brand' );
$hri_out .= hri_bm_head( __( 'ทำไมต้อง HRI DeOne', 'hri-theme' ), 'Why HRI DeOne' );
$hri_out .= hri_bm_cards(
	array(
		array(
			'num'   => '01',
			'title' => 'Customization',
			'text'  => __( 'วิเคราะห์บริบทและวัฒนธรรมองค์กรก่อน แล้วออกแบบเนื้อหาและเวิร์กชอปให้พนักงานรู้สึกว่า “นี่คือเรื่องของเรา”', 'hri-theme' ),
		),
		array(
			'num'   => '02',
			'title' => 'Facilitation & Engagement',
			'text'  => __( 'เรียนรู้ผ่านประสบการณ์และกิจกรรม ไม่ใช่การบรรยายทางเดียว ผู้เรียนกล้ามีส่วนร่วมและแลกเปลี่ยนในบรรยากาศที่ปลอดภัย', 'hri-theme' ),
		),
		array(
			'num'   => '03',
			'title' => 'Evidence-Based & Actionable',
			'text'  => __( 'เลือกเครื่องมือที่ใช้ง่าย ใช้ได้จริง และวัดผลได้ ทีมนำไปใช้กับงานได้ตั้งแต่วันแรกหลังอบรม', 'hri-theme' ),
		),
		array(
			'num'   => '04',
			'title' => 'Measurable Impact & Follow-up',
			'text'  => __( 'มีเครื่องมือประเมินผลหลังอบรมและที่ปรึกษาติดตามต่อเนื่อง ช่วยให้ฝ่าย HR แสดง ROI ของการพัฒนาคนได้ชัดเจน', 'hri-theme' ),
		),
	),
	4
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
