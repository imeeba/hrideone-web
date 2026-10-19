<?php
/**
 * Title: Transformation — 4 scopes
 * Slug: hri/transformation-scope
 * Categories: hri
 * Keywords: transformation, scope, pillars
 * Description: The four transformation pillars as cards, each linking to its section further down the page. Anchor #scope.
 *
 * @package hri-theme
 */

$hri_scopes = array(
	array( 'Business Strategy', 'business-strategy', __( 'นำพาธุรกิจสู่เป้าหมาย ด้วยโครงสร้างที่แข็งแกร่งและยั่งยืน', 'hri-theme' ) ),
	array( 'Technology Strategy', 'technology-strategy', __( 'เทคโนโลยีที่ใช่ ในเวลาที่ถูกต้อง เพื่อผลลัพธ์ที่วัดได้จริง', 'hri-theme' ) ),
	array( 'People Strategy', 'people-strategy', __( 'คนคือหัวใจของทุกการเปลี่ยนแปลง แต่ต้องถูกออกแบบให้ทำงานร่วมกันได้อย่างลงตัว', 'hri-theme' ) ),
	array( 'Culture Strategy', 'culture-strategy', __( 'วัฒนธรรมองค์กรที่ดีไม่ได้เกิดจากคำคมสวยหรู แต่ต้องถูกออกแบบและฝังรากในทุกระบบขององค์กร', 'hri-theme' ) ),
);
$hri_cards = array();
foreach ( $hri_scopes as $hri_i => $hri_scope ) {
	$hri_cards[] = array(
		'num'   => sprintf( '%02d', $hri_i + 1 ),
		'title' => $hri_scope[0],
		'text'  => $hri_scope[2],
		/* translators: %s: pillar name, e.g. Business Strategy */
		'link'  => array( sprintf( __( 'รายละเอียด %s', 'hri-theme' ), $hri_scope[0] ), '#' . $hri_scope[1] ),
	);
}

$hri_out  = hri_bm_section( '', '', 'scope' );
$hri_out .= hri_bm_head( __( 'ขอบเขตการทำงาน', 'hri-theme' ), '4 Scope of Transformation', __( 'การ Transform ที่ได้ผลจริง ต้องให้กลยุทธ์ เทคโนโลยี คน และวัฒนธรรม ขับเคลื่อนไปในทิศทางเดียวกัน เราให้บริการผ่านเครือข่ายผู้เชี่ยวชาญที่คัดสรรมาใน 4 เสาหลัก', 'hri-theme' ) );
$hri_out .= hri_bm_cards( $hri_cards, 4, 'hri-scope' );
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
