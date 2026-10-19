<?php
/**
 * Title: In-house — Leadership + Business
 * Slug: hri/inhouse-pillars
 * Categories: hri
 * Keywords: in-house, leadership, business, pillars
 * Description: The two course pillars, each with its topics and a link to its filtered course list.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section();
$hri_out .= hri_bm_head(
	'Leadership + Business',
	__( 'ผู้นำยุคใหม่ ต้องเก่งทั้งคนและธุรกิจ', 'hri-theme' ),
	__( 'การพัฒนาผู้นำแห่งอนาคตต้องสร้างสมดุลระหว่างสองเสาหลัก คือการนำคน (Leadership) และความเข้าใจธุรกิจ (Business) หลักสูตรของเราจึงครอบคลุมทั้งสองด้าน', 'hri-theme' ),
	'',
	true
);
$hri_out .= hri_bm_cards(
	array(
		array(
			'kicker' => 'Lead Self · Lead Team',
			'title'  => 'Leadership',
			'text'   => __( 'พัฒนาผู้นำตั้งแต่การรู้จักและบริหารตัวเอง วิธีคิด การสื่อสาร ไปจนถึงการโค้ชและการนำทีม', 'hri-theme' ),
			'list'   => array( 'Self Improvement', 'Thinking', 'Personal Communication', 'Coaching', 'Teamwork & Team Communication' ),
			'link'   => array( __( 'ดูหลักสูตร Leadership', 'hri-theme' ), '/in-house/?pillar%5B%5D=leadership#courses' ),
		),
		array(
			'kicker' => 'Strategy · Sales · Customer · Operation',
			'title'  => 'Business',
			'text'   => __( 'ผู้นำที่ไม่เข้าใจธุรกิจ ไม่สามารถพาองค์กรไปในทิศทางที่ถูกต้องได้ หลักสูตรด้านธุรกิจจึงครอบคลุมทุกฟังก์ชันสำคัญขององค์กร', 'hri-theme' ),
			'list'   => array( 'Strategy', 'Sales', 'Customer Service', 'Marketing', 'Finance', 'HR', 'Operation' ),
			'link'   => array( __( 'ดูหลักสูตร Business', 'hri-theme' ), '/in-house/?pillar%5B%5D=business#courses' ),
		),
	),
	2,
	'hri-pillars'
);
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
