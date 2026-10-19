<?php
/**
 * Title: Public Training — Why public training
 * Slug: hri/public-why
 * Categories: hri
 * Keywords: public, why, benefits
 * Description: Photo next to the reasons to join a public course (checklist).
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( '', 'surface-brand' );
$hri_out .= hri_bm_group( array( 'className' => 'hri-split' ) );
$hri_out .= hri_bm_photo( __( 'ผู้เข้าอบรมแลกเปลี่ยนความคิดเห็นกัน', 'hri-theme' ) );
$hri_out .= hri_bm_group( array( 'className' => 'hri-split__text' ) );
$hri_out .= hri_bm_eyebrow( __( 'ทำไมต้องอบรม Public', 'hri-theme' ), false );
$hri_out .= hri_bm_title( __( 'ได้ทั้งความรู้จากผู้เชี่ยวชาญ และมุมมองจากเพื่อนร่วมรุ่น', 'hri-theme' ), false );
$hri_out .= hri_bm_p( esc_html__( 'การอบรมแบบ Public ให้มากกว่าเนื้อหาในห้องเรียน ผู้เข้าอบรมได้เรียนรู้วิธีคิดและประสบการณ์จริงจากผู้บริหารต่างองค์กร ต่างอุตสาหกรรม ซึ่งเป็นการเปิดโลกทัศน์ ขยายมุมมอง และเพิ่มศักยภาพให้ทั้งตนเองและองค์กร', 'hri-theme' ) );
$hri_out .= hri_bm_list(
	array(
		esc_html__( 'เรียนกับวิทยากรผู้เชี่ยวชาญเฉพาะด้าน', 'hri-theme' ),
		esc_html__( 'แลกเปลี่ยนกับผู้บริหารจากหลากหลายอุตสาหกรรม', 'hri-theme' ),
		esc_html__( 'ขยายเครือข่ายทางธุรกิจ', 'hri-theme' ),
		esc_html__( 'เหมาะกับการส่งพนักงานบางส่วนเข้าอบรม โดยไม่ต้องจัดคลาสเอง', 'hri-theme' ),
	),
	'hri-checklist'
);
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
