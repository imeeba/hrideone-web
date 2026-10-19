<?php
/**
 * Title: Japanese Concept — Business outcomes
 * Slug: hri/japanese-outcomes
 * Categories: hri
 * Keywords: japanese, outcomes, legacy leader
 * Description: The four dimensions of a Legacy Leader (numbered cards).
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head( 'Business Outcomes', __( 'จากผู้บริหารที่สร้างผลงาน สู่ Legacy Leader', 'hri-theme' ), '', '', true );
$hri_out .= hri_bm_cards(
	array(
		array(
			'num'   => '01',
			'title' => 'The Purpose-Driven Leader',
			'text'  => __( 'ผู้นำที่รู้ว่านำไปเพื่ออะไร ได้ Ikigai Statement ที่เชื่อมสิ่งที่รัก ความถนัด พันธกิจ และคุณค่าทางธุรกิจเข้าด้วยกัน', 'hri-theme' ),
		),
		array(
			'num'   => '02',
			'title' => 'The Golden Resilience',
			'text'  => __( 'ผู้นำที่เปลี่ยนความล้มเหลวให้เป็นความแข็งแกร่ง ด้วยแนวคิด Kintsugi และ Shoshin', 'hri-theme' ),
		),
		array(
			'num'   => '03',
			'title' => 'The Harmony Builder',
			'text'  => __( 'ผู้นำที่ขับเคลื่อนคนด้วยความกลมกลืน ใช้ Sanpo-Yoshi สร้างประโยชน์ทั้งผู้ขาย ผู้ซื้อ และสังคม และใช้ Nemawashi สร้างความเห็นพ้อง', 'hri-theme' ),
		),
		array(
			'num'   => '04',
			'title' => 'The Legacy Architect',
			'text'  => __( 'ผู้นำที่สร้างสิ่งที่อยู่เกินตัวเอง ได้ Legacy Blueprint ประกอบด้วยวิสัยทัศน์ 100 ปี สินทรัพย์ Noren และเครือข่าย Kizuna', 'hri-theme' ),
		),
	),
	4
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
