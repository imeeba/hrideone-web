<?php
/**
 * Title: Public Training — FAQ
 * Slug: hri/public-faq
 * Categories: hri
 * Keywords: public, faq, questions, คำถาม
 * Description: Frequently asked questions (Details blocks: click to open).
 *
 * @package hri-theme
 */

$hri_faq = array(
	array( __( 'Public Training ต่างจาก In-house อย่างไร?', 'hri-theme' ), esc_html__( 'Public Training เปิดรับสมัครทั่วไป ผู้เข้าอบรมมาจากหลายองค์กรตามวันและสถานที่ที่กำหนด ส่วน In-house จัดเฉพาะพนักงานขององค์กรคุณ ปรับเนื้อหาและตารางได้ตามโจทย์', 'hri-theme' ) ),
	array( __( 'สมัครอบรมอย่างไร?', 'hri-theme' ), hri_bm_contact_answer() ),
	array( __( 'ส่งพนักงานหลายคนพร้อมกันได้ไหม?', 'hri-theme' ), esc_html__( 'ได้ แจ้งจำนวนผู้เข้าอบรมตอนสมัคร ทีมงานจะแจ้งเงื่อนไขและรายละเอียดให้', 'hri-theme' ) ),
	array( __( 'ถ้ารุ่นที่สนใจเต็มแล้ว ทำอย่างไร?', 'hri-theme' ), esc_html__( 'แจ้งความสนใจกับทีมงานไว้ได้ เราจะติดต่อกลับเมื่อเปิดรับสมัครรุ่นถัดไป', 'hri-theme' ) ),
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
