<?php
/**
 * Title: Japanese Concept — DO·WA·KEI introduction
 * Slug: hri/japanese-dowakei
 * Categories: hri
 * Keywords: japanese, do wa kei, leadership
 * Description: Dark band introducing DO·WA·KEI with its three parts (道 和 敬) and an apply button. Anchor #do-wa-kei.
 *
 * @package hri-theme
 */

$hri_parts = array(
	array( '道', 'DO', 'The Leader Within' ),
	array( '和', 'WA', 'The Space Between People' ),
	array( '敬', 'KEI', 'Beyond a Single Lifetime' ),
);

$hri_out  = hri_bm_section( 'hri-dowakei', 'primary-deep', 'do-wa-kei', 'base' );
$hri_out .= hri_bm_eyebrow( 'DO · WA · KEI', true, 'accent' );
$hri_out .= hri_bm_heading( __( 'เรียนรู้การนำจากองค์กรร้อยปีของญี่ปุ่น สู่การสร้างองค์กรที่ยืนหยัดข้ามรุ่น', 'hri-theme' ), array( 'textAlign' => 'center', 'className' => 'hri-dowakei__title', 'textColor' => 'base' ) );
$hri_out .= hri_bm_group( array( 'className' => 'hri-kanji' ) );
foreach ( $hri_parts as $hri_part ) {
	$hri_out .= hri_bm_group( array( 'className' => 'hri-kanji__item' ) )
		. hri_bm_p( '<span lang="ja">' . esc_html( $hri_part[0] ) . '</span>', array( 'className' => 'hri-kanji__char' ) )
		. hri_bm_p( '<strong>' . esc_html( $hri_part[1] ) . '</strong> ' . esc_html( $hri_part[2] ), array( 'className' => 'hri-kanji__label' ) )
		. hri_bm_group_end();
}
$hri_out .= hri_bm_group_end();
$hri_out .= hri_bm_p( esc_html__( 'ผู้นำที่เก่งสร้างผลงาน แต่ผู้นำที่ยิ่งใหญ่สร้างสิ่งที่อยู่ยาวนานกว่าตัวเอง', 'hri-theme' ), array( 'align' => 'center', 'className' => 'hri-dowakei__quote', 'fontSize' => 'x-large' ) );
$hri_out .= hri_bm_p( esc_html__( 'องค์กรไทยกำลังขยายสู่ภูมิภาค ความได้เปรียบในอนาคตจึงไม่ใช่ความเร็วหรือขนาด แต่คือผู้นำที่คนไว้วางใจ DO·WA·KEI ถ่ายทอดผ่านปรัชญาญี่ปุ่นและการเรียนรู้จากประเทศญี่ปุ่นจริง', 'hri-theme' ), array( 'align' => 'center', 'className' => 'hri-dowakei__text' ) );
$hri_out .= hri_bm_buttons(
	array(
		array( __( 'สมัครเลย', 'hri-theme' ), hri_bm_contact(), 'light' ),
		array( __( 'ดูเส้นทาง 3 ขั้น', 'hri-theme' ), '#journey', 'outline-light' ),
	),
	'center'
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
