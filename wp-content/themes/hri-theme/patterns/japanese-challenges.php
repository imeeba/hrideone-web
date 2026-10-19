<?php
/**
 * Title: Japanese Concept — Leadership challenges
 * Slug: hri/japanese-challenges
 * Categories: hri
 * Keywords: japanese, challenges, ikigai, nemawashi
 * Description: Four executive challenges, each with the Japanese concept that answers it.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head( __( 'ความท้าทายของผู้บริหาร', 'hri-theme' ), __( 'คุณกำลังนำองค์กร “ไปได้ไกล” หรือแค่ “ไปได้เร็ว”?', 'hri-theme' ), '', '', true );
$hri_out .= hri_bm_cards(
	array(
		array(
			'kicker' => 'Ikigai',
			'title'  => __( 'เก่งขึ้น แต่หมดไฟเร็วขึ้นทุกปี', 'hri-theme' ),
			'text'   => __( 'เมื่อผู้นำขาดเป้าหมายที่มีความหมาย พลังในการนำจะค่อย ๆ หมดลง และทีมก็หมดไฟตาม ทางออกคือการค้นหา Ikigai ของการเป็นผู้นำ', 'hri-theme' ),
		),
		array(
			'kicker' => 'Omotenashi',
			'title'  => __( 'สั่งงานได้ แต่ซื้อใจคนไม่ได้', 'hri-theme' ),
			'text'   => __( 'ทีมทำตามเพราะจำเป็น ไม่ใช่เพราะเชื่อ ทางออกคือการนำด้วยหัวใจแบบ Omotenashi', 'hri-theme' ),
		),
		array(
			'kicker' => 'Nemawashi',
			'title'  => __( 'เข้าห้องประชุมเมื่อไหร่ ก็เจอแรงต้านเมื่อนั้น', 'hri-theme' ),
			'text'   => __( 'ข้อเสนอถูกปฏิเสธเพราะผู้เกี่ยวข้องไม่ได้ร่วมคิดตั้งแต่ต้น ทางออกคือการสร้างความเห็นพ้องล่วงหน้าแบบ Nemawashi', 'hri-theme' ),
		),
		array(
			'kicker' => '100-Year Company',
			'title'  => __( 'โตเร็ว แต่ไม่รู้ว่าจะยืนได้นานแค่ไหน', 'hri-theme' ),
			'text'   => __( 'องค์กรเติบโตได้ในยุคผู้ก่อตั้ง แต่สั่นคลอนเมื่อส่งต่อให้รุ่นถัดไป ทางออกคือเรียนรู้จากบริษัทร้อยปีในญี่ปุ่น เพื่อวาง “เข็มทิศ 100 ปี” ขององค์กร', 'hri-theme' ),
		),
	),
	2,
	'hri-challenges'
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
