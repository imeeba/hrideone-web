<?php
/**
 * Title: Transformation — People Strategy
 * Slug: hri/transformation-people
 * Categories: hri
 * Keywords: transformation, people, talent, predictive index
 * Description: Team problems next to the Talent Optimization & Team Synergy approach. Anchor #people-strategy.
 *
 * @package hri-theme
 */

$hri_inner  = hri_bm_group( array( 'className' => 'hri-duo' ) );
$hri_inner .= hri_bm_problems(
	__( 'คุณกำลังเจอปัญหาเหล่านี้อยู่ไหม?', 'hri-theme' ),
	array(
		__( 'ทีมงานไม่เป็นหนึ่งเดียว', 'hri-theme' ),
		__( 'พนักงานขาดแรงจูงใจ', 'hri-theme' ),
		__( 'สื่อสารกันไม่เข้าใจ', 'hri-theme' ),
		__( 'ผลงานไม่บรรลุเป้าหมาย', 'hri-theme' ),
	)
);
$hri_inner .= hri_bm_cards(
	array(
		array(
			'kicker' => 'Key Focus',
			'title'  => 'Talent Optimization & Team Synergy',
			'text'   => __( 'ปัญหาเหล่านี้ไม่ได้เกิดจากการขาดทักษะ แต่เกิดจากทีมที่ไม่ได้ถูกออกแบบให้ทำงานร่วมกันได้เต็มศักยภาพ กระบวนการที่ปรึกษาของเราใช้ The Predictive Index (PI) แพลตฟอร์ม Talent Optimization จากสหรัฐอเมริกา ที่มีงานวิจัยด้านวิทยาศาสตร์และจิตวิทยารองรับกว่า 70 ปี', 'hri-theme' ),
			'list'   => array(
				__( 'เข้าใจแรงขับของแต่ละคน', 'hri-theme' ),
				__( 'ออกแบบทีมให้สมาชิกเสริมจุดแข็งกัน', 'hri-theme' ),
				__( 'เชื่อมกลยุทธ์ด้านคนเข้ากับเป้าหมายธุรกิจ', 'hri-theme' ),
			),
		),
	),
	1
);
$hri_inner .= hri_bm_group_end();

echo hri_bm_service( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	'people-strategy',
	'surface-brand',
	'Know Your People. Unlock Your Team.',
	'People Strategy',
	__( 'คนคือหัวใจของทุกการเปลี่ยนแปลง แต่ต้องถูกออกแบบให้ทำงานร่วมกันได้อย่างลงตัว แม้คนเก่งก็อาจทำผลงานได้ไม่เต็มที่ หากทีมไม่ได้ถูกออกแบบมาให้ทำงานร่วมกัน', 'hri-theme' ),
	$hri_inner
);
