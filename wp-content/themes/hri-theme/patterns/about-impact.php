<?php
/**
 * Title: About — Social impact
 * Slug: hri/about-impact
 * Categories: hri
 * Keywords: about, social impact
 * Description: Social impact statement (text of the old About page).
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-impact-section', '', 'social-impact', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( __( "มุ่งพัฒนาสังคมให้น่าอยู่", 'hri-theme' ), 'SOCIAL IMPACT', '', '', true )
	. hri_bm_group( array( 'className' => 'hri-prose', 'layout' => array( 'type' => 'constrained', 'contentSize' => '760px' ) ) )
	. hri_bm_p( '<strong>' . esc_html( __( "ส่งต่อโอกาส สร้างการเติบโตให้กับผู้คนและสังคม", 'hri-theme' ) ) . '</strong>', array( 'align' => 'center', 'fontSize' => 'x-large' ) )
	. hri_bm_p( esc_html( __( "เราเชื่อในศักยภาพของผู้คน และเชื่อว่าการแบ่งปันโอกาส ความห่วงใย และทรัพยากรที่เรามี สามารถสร้างการเปลี่ยนแปลงที่ดีให้เกิดขึ้นได้ นอกเหนือจากการพัฒนาคนและองค์กรผ่านงานของเรา HRI DeOne และบริษัทใน HRI Group ยังมุ่งพัฒนา มีส่วนร่วมกับชุมชนและสังคม ผ่านกิจกรรมเพื่อสังคมในหลากหลายรูปแบบ เพื่อร่วมสร้างโอกาส ส่งต่อกำลังใจ และสนับสนุนการเติบโตของผู้คนอย่างต่อเนื่องไปด้วยกัน", 'hri-theme' ) ), array( 'align' => 'center', 'fontSize' => 'large' ) )
	. hri_bm_group_end()
	. hri_bm_section_end();
