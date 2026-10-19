<?php
/**
 * Title: In-house — How it works
 * Slug: hri/inhouse-process
 * Categories: hri
 * Keywords: in-house, process, steps, ขั้นตอน
 * Description: Four steps from the first talk to the follow-up, with a CTA button.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head( __( 'ขั้นตอนการทำงาน', 'hri-theme' ), 'How It Works', __( 'จากโจทย์ขององค์กรคุณ สู่หลักสูตรที่ใช่ใน 4 ขั้นตอน', 'hri-theme' ) );
$hri_out .= hri_bm_steps(
	array(
		array( __( 'คุยโจทย์และเป้าหมาย', 'hri-theme' ), __( 'ทำความเข้าใจปัญหา เป้าหมาย และกลุ่มผู้เรียน ร่วมกับ HRD หรือหัวหน้าหน่วยงาน', 'hri-theme' ) ),
		array( __( 'ออกแบบหลักสูตรและเสนอราคา', 'hri-theme' ), __( 'ปรับกรณีศึกษาและแบบฝึกหัดให้ตรงกับสถานการณ์จริงขององค์กร พร้อมใบเสนอราคา', 'hri-theme' ) ),
		array( __( 'จัดอบรม', 'hri-theme' ), __( 'โดยทั่วไป 1–2 วัน ปรับตารางได้ ทั้ง Onsite, Online หรือ Hybrid และจัดเป็น Journey ระยะยาวได้', 'hri-theme' ) ),
		array( __( 'ประเมินผลและติดตาม', 'hri-theme' ), __( 'สรุปผลการอบรมพร้อมข้อเสนอแนะ ให้ HRD ติดตามพัฒนาการของผู้เรียนต่อได้', 'hri-theme' ) ),
	)
);
$hri_out .= hri_bm_buttons( array( array( __( 'เริ่มคุยโจทย์กับเรา', 'hri-theme' ), hri_bm_contact() ) ), 'center' );
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
