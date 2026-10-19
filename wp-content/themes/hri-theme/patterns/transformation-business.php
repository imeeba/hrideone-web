<?php
/**
 * Title: Transformation — Business Strategy
 * Slug: hri/transformation-business
 * Categories: hri
 * Keywords: transformation, business strategy
 * Description: Pain points and the three Business Strategy services. Anchor #business-strategy.
 *
 * @package hri-theme
 */

$hri_inner  = hri_bm_problems(
	__( 'สัญญาณที่องค์กรควรทบทวนกลยุทธ์', 'hri-theme' ),
	array(
		__( 'ทิศทางองค์กรไม่ชัดเจน', 'hri-theme' ),
		__( 'ทีมทำงานแบบแยกส่วน (Silo)', 'hri-theme' ),
		__( 'เป้าหมายของผู้บริหารและพนักงานไม่ตรงกัน', 'hri-theme' ),
		__( 'วิสัยทัศน์ไม่ถูกแปลงเป็นแผนที่ลงมือทำได้', 'hri-theme' ),
	)
);
$hri_inner .= hri_bm_cards(
	array(
		array(
			'kicker' => __( 'วางรากฐานกลยุทธ์องค์กร', 'hri-theme' ),
			'title'  => 'Strategic Foundation',
			'text'   => __( 'กำหนดวิสัยทัศน์ พันธกิจ และเป้าหมายให้ชัด ออกแบบ Strategic Framework 3 ปี และ House Strategy ที่ทุกทีมเข้าใจตรงกัน พร้อมเตรียม People, Process และ Culture ให้รองรับการเปลี่ยนแปลง', 'hri-theme' ),
		),
		array(
			'kicker' => __( 'แปลงกลยุทธ์สู่การปฏิบัติจริง', 'hri-theme' ),
			'title'  => 'Strategic Execution',
			'text'   => __( 'มีแผนดีแต่ยังไม่ได้ผล? ปัญหามักอยู่ที่การลงมือทำ กระบวนการ 7 ขั้นตอน ตั้งแต่ทบทวน Company Protocol วิเคราะห์ช่องว่าง ปรับกรอบกลยุทธ์ วางแผนปฏิบัติการ จนถึงโค้ชติดตามผลนานสูงสุด 6 เดือน', 'hri-theme' ),
		),
		array(
			'kicker' => __( 'เปลี่ยนองค์กรอย่างรวดเร็วและยั่งยืน', 'hri-theme' ),
			'title'  => '90-Day Rapid Transformation',
			'text'   => __( 'สำหรับสถานการณ์เร่งด่วน เช่น วิกฤตหรือการพลิกฟื้นธุรกิจ ด้วย Framework ที่มีผลงานต่อเนื่องกว่า 11 ปี ให้ทีมภายในเป็นผู้ขับเคลื่อนการเปลี่ยนแปลงเอง เพื่อผลลัพธ์ที่จับต้องได้ใน 90 วัน', 'hri-theme' ),
		),
	),
	3
);

echo hri_bm_service( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	'business-strategy',
	'surface-brand',
	'Think Sharp. Plan Right. Execute Well.',
	'Business Strategy',
	__( 'นำพาธุรกิจสู่เป้าหมาย ด้วยโครงสร้างที่แข็งแกร่งและยั่งยืน เราช่วยเปลี่ยนเป้าหมายที่ซับซ้อนให้เป็นกลยุทธ์ที่ทำได้จริง และพากลยุทธ์ออกจากกระดาษสู่การปฏิบัติ', 'hri-theme' ),
	$hri_inner
);
