<?php
/**
 * Title: In-house — FAQ
 * Slug: hri/inhouse-faq
 * Categories: hri
 * Keywords: in-house, faq, questions, คำถาม
 * Description: Frequently asked questions (Details blocks: click to open).
 *
 * @package hri-theme
 */

$hri_faq = array(
	array( __( 'หลักสูตร In-house ของ HRI DeOne ต่างจากการอบรมทั่วไปอย่างไร?', 'hri-theme' ), esc_html__( 'เราเน้น Tailor-made Solution ไม่ใช่การสอนตามสไลด์สำเร็จรูป ทีมงานจะทำความเข้าใจบริบทขององค์กรคุณก่อนเริ่มออกแบบทุกครั้ง', 'hri-theme' ) ),
	array( __( 'ปรับเนื้อหาให้เข้ากับวัฒนธรรมองค์กรได้ไหม?', 'hri-theme' ), esc_html__( 'ได้ เราปรับกรณีศึกษาและแบบฝึกหัดร่วมกับ HRD หรือหัวหน้าหน่วยงาน ให้สะท้อนสถานการณ์จริงที่ทีมเจอ', 'hri-theme' ) ),
	array( __( 'ใช้เวลาอบรมนานเท่าไร และจัดรูปแบบไหนได้บ้าง?', 'hri-theme' ), esc_html__( 'โดยทั่วไป 1–2 วัน ปรับตารางได้ยืดหยุ่น จัดได้ทั้ง Onsite, Online หรือ Hybrid และออกแบบเป็นโปรแกรมระยะยาวแบบ Journey ได้', 'hri-theme' ) ),
	array( __( 'มีการประเมินผลหลังอบรมไหม?', 'hri-theme' ), esc_html__( 'มี เราสรุปผลและข้อเสนอแนะหลังอบรม เพื่อให้ HRD ประเมินและติดตามพัฒนาการของผู้เรียนได้', 'hri-theme' ) ),
	array( __( 'เริ่มต้นอย่างไร?', 'hri-theme' ), hri_bm_contact_answer() ),
);

$hri_out  = hri_bm_section( 'hri-faq-section' );
$hri_out .= hri_bm_head( __( 'คำถามที่พบบ่อย', 'hri-theme' ), 'FAQ' );
$hri_out .= hri_bm_group( array( 'className' => 'hri-faq' ) );
foreach ( $hri_faq as $hri_item ) {
	$hri_out .= hri_bm_details( $hri_item[0], array( $hri_item[1] ), 'hri-faq__item' );
}
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
