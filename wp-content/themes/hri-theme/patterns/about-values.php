<?php
/**
 * Title: About — Core value
 * Slug: hri/about-values
 * Categories: hri
 * Keywords: about, values, คุณค่าหลัก
 * Description: Core value: one statement, centred (text of the old About page).
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-values-section', 'surface-brand', 'values', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( __( "Core Value | คุณค่าหลัก", 'hri-theme' ), __( "Where Differences Become Strength.", 'hri-theme' ), '', '', true )
	. hri_bm_p( '<strong>' . esc_html( __( "เมื่อความแตกต่างกลายเป็นพลัง", 'hri-theme' ) ) . '</strong>', array( 'align' => 'center', 'fontSize' => 'x-large' ) )
	. hri_bm_group( array( 'className' => 'hri-prose', 'layout' => array( 'type' => 'constrained', 'contentSize' => '760px' ) ) )
	. hri_bm_p( esc_html( __( "HRI DeOne เกิดจากการผสานจุดแข็งของสององค์กร เราเชื่อว่า\nความแตกต่างของผู้คน ประสบการณ์ และมุมมอง จะกลายเป็นพลังที่ช่วยให้เราเรียนรู้ เติบโต และสร้างคุณค่าที่ดีกว่าร่วมกัน", 'hri-theme' ) ), array( 'align' => 'center', 'fontSize' => 'large' ) )
	. hri_bm_group_end()
	. hri_bm_section_end();
