<?php
/**
 * Title: Japanese Concept — DO·WA·KEI Journey
 * Slug: hri/japanese-journey
 * Categories: hri
 * Keywords: japanese, journey, modules, do wa kei
 * Description: The three parts of DO·WA·KEI with their modules. Anchor #journey.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( '', 'surface-brand', 'journey' );
$hri_out .= hri_bm_head( 'DO·WA·KEI Journey', __( 'เส้นทาง 3 ขั้น สู่ Legacy Leader', 'hri-theme' ), __( 'หลักสูตรที่เปลี่ยนปรัชญาญี่ปุ่นให้เป็นวิธีการนำที่ใช้ได้จริง', 'hri-theme' ), '', true );
$hri_out .= hri_bm_cards(
	array(
		array(
			'num'      => '<span lang="ja">道</span>',
			'num_html' => true,
			'kicker'   => __( 'Part 1 · DO · 1 วัน · ประเทศไทย', 'hri-theme' ),
			'title'    => 'The Leader Within',
			'text'     => __( 'เปลี่ยนระบบภายในของผู้นำ ก่อนนำผู้อื่น', 'hri-theme' ),
			'list'     => array(
				__( 'IKIGAI Spark — ค้นหาเหตุผลในการนำ และกลั่นเป็น Ikigai Statement', 'hri-theme' ),
				__( 'Kintsugi Mindset — เปลี่ยนความผิดพลาดให้เป็นความแข็งแกร่ง', 'hri-theme' ),
				__( 'Shoshin Reset — ละทิ้งสมมติฐานเดิม มองโจทย์ด้วยสายตาใหม่', 'hri-theme' ),
			),
		),
		array(
			'num'      => '<span lang="ja">和</span>',
			'num_html' => true,
			'kicker'   => 'Part 2 · WA',
			'title'    => 'The Space Between People',
			'text'     => __( 'เปลี่ยนคนเก่งให้เป็นผู้นำที่คนไว้วางใจ', 'hri-theme' ),
			'list'     => array(
				__( 'Sanpo-Yoshi Harmony — ใช้หลักการค้าของพ่อค้าโอมิกับการตัดสินใจจริง', 'hri-theme' ),
				__( 'Omotenashi Design — อ่านความต้องการของทีมก่อนที่จะถูกพูดออกมา', 'hri-theme' ),
				__( 'Nemawashi Magic — สร้างความเห็นพ้องก่อนประชุม พร้อมวาด Stakeholder Map', 'hri-theme' ),
			),
		),
		array(
			'num'      => '<span lang="ja">敬</span>',
			'num_html' => true,
			'kicker'   => __( 'Part 3 · KEI · 3 วัน · ประเทศญี่ปุ่น', 'hri-theme' ),
			'title'    => 'Beyond a Single Lifetime',
			'text'     => __( 'จุดสูงสุดของเส้นทาง เรียนรู้จากองค์กรที่ยืนหยัดข้ามศตวรรษในญี่ปุ่น', 'hri-theme' ),
			'list'     => array(
				__( 'Day 1 · 100-Year Vision — เยี่ยมชมบริษัทอายุกว่า 100 ปี และร่างเข็มทิศ 100 ปีขององค์กรตนเอง', 'hri-theme' ),
				__( 'Day 2 · Noren Value — เยี่ยมชมโรงงานช่างฝีมือ ค้นหาคุณค่าที่มองไม่เห็นขององค์กร', 'hri-theme' ),
				__( 'Day 3 · Kizuna Ecosystem — ศึกษาระบบนิเวศระหว่างบริษัทกับชุมชน ปิดท้ายด้วย Legacy Commitment ของผู้นำแต่ละคน', 'hri-theme' ),
			),
		),
	),
	3,
	'hri-journey'
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
