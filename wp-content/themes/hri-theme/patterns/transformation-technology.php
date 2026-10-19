<?php
/**
 * Title: Transformation — Technology Strategy
 * Slug: hri/transformation-technology
 * Categories: hri
 * Keywords: transformation, technology, lean, ai
 * Description: Lean & Process Excellence and AI Solutions. Anchor #technology-strategy.
 *
 * @package hri-theme
 */

$hri_inner = hri_bm_cards(
	array(
		array(
			'kicker' => __( 'ลดความสูญเสีย เพิ่มประสิทธิภาพอย่างยั่งยืน', 'hri-theme' ),
			'title'  => 'Lean & Process Excellence',
			'text'   => __( 'ต้นทุนสูงขึ้นแต่กำไรไม่ขยับ อาจมีความสูญเสียซ่อนอยู่ในธุรกิจ เราใช้แนวทาง Lean Manufacturing ของ Toyota ปรับปรุงอย่างเป็นระบบ ตั้งแต่การวางมาตรฐานงาน ลดความสูญเสีย จนถึงเพิ่มประสิทธิภาพสายการผลิต', 'hri-theme' ),
			'link'   => array( __( 'หลักสูตร Lean Transformation in 7 weeks', 'hri-theme' ), '/courses/lean-transformation-in-7-weeks/' ),
		),
		array(
			'kicker' => __( 'เปลี่ยน AI ให้เป็นผลลัพธ์ทางธุรกิจที่จับต้องได้', 'hri-theme' ),
			'title'  => 'AI Solutions',
			'text'   => __( 'หลายองค์กรมีทั้งข้อมูลและเครื่องมือ แต่ยังไม่เห็นผล เพราะ AI ยังไม่ถูกนำไปใช้กับเป้าหมายที่ถูกต้อง ONE7AI เชื่อม AI เข้ากับการเปลี่ยนแปลงขององค์กร และใช้ AI เป็นเครื่องมือเชิงกลยุทธ์', 'hri-theme' ),
			'list'   => array(
				__( 'Operation: บริหารงานหลังบ้านอย่างเป็นเลิศ', 'hri-theme' ),
				__( 'Customer Mastery: ชนะใจลูกค้าด้วยบริการที่เหนือกว่า', 'hri-theme' ),
				__( 'Business Growth: เร่งการเติบโตและขยายฐานลูกค้า', 'hri-theme' ),
			),
		),
	),
	2
);

echo hri_bm_service( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	'technology-strategy',
	'',
	'The Right Technology, Driving the Right Results.',
	'Technology Strategy',
	__( 'เทคโนโลยีที่ใช่ ในเวลาที่ถูกต้อง เพื่อผลลัพธ์ที่วัดได้จริง เราผสานความเชี่ยวชาญด้าน Lean เข้ากับ AI ให้องค์กรทำงานได้ฉลาดขึ้น เร็วขึ้น และแข่งขันได้อย่างยั่งยืน', 'hri-theme' ),
	$hri_inner
);
