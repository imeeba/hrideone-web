<?php
/**
 * Title: Transformation — Culture Strategy
 * Slug: hri/transformation-culture
 * Categories: hri
 * Keywords: transformation, culture, framework
 * Description: The five-stage Cultural Transformation Framework (Define → Sustain). Anchor #culture-strategy.
 *
 * @package hri-theme
 */

$hri_inner  = hri_bm_heading( 'Cultural Transformation Framework', array( 'level' => 3, 'textAlign' => 'center', 'className' => 'hri-subtitle' ) );
$hri_inner .= hri_bm_steps(
	array(
		array( 'Define: Leadership & Vision', __( 'ผู้นำร่วมกันกำหนด DNA และค่านิยมขององค์กร แล้วแปลงเป็นพฤติกรรมที่มองเห็นและวัดผลได้', 'hri-theme' ) ),
		array( 'Align: Mindset & Meaning', __( 'พนักงานเข้าใจว่าวัฒนธรรมใหม่ดีต่อตัวเองอย่างไร และเชื่อมจุดแข็งของตนเข้ากับเป้าหมายองค์กร', 'hri-theme' ) ),
		array( 'Empower: Tools & Autonomy', __( 'ระบบ Peer Feedback และเครื่องมือที่ใช้ได้จริง ทำให้ทุกระดับมั่นใจที่จะลงมือทำตามค่านิยมใหม่', 'hri-theme' ) ),
		array( 'Hardwire: Systemic Integration', __( 'ฝังวัฒนธรรมไว้ในการสรรหา การประเมินผล และระบบรางวัล โดยผูกกับ Cultural KPIs', 'hri-theme' ) ),
		array( 'Sustain: Measurement & Champions', __( 'แต่งตั้ง Culture Ambassador ทุกหน่วยงาน และวัดผล 3 มิติ: ผลทางธุรกิจ ความเข้าใจของพนักงาน และพฤติกรรมที่เปลี่ยนไป', 'hri-theme' ) ),
	)
);

echo hri_bm_service( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	'culture-strategy',
	'',
	'Built Into Every System. Felt By Every Person.',
	'Culture Strategy',
	__( 'วัฒนธรรมองค์กรที่ดีไม่ได้เกิดจากคำคมสวยหรู แต่ต้องถูกออกแบบและฝังรากในทุกระบบขององค์กร เพราะวัฒนธรรมคือสิ่งที่ทุกระบบในองค์กรให้รางวัลและสนับสนุนในทุกวัน', 'hri-theme' ),
	$hri_inner
);
