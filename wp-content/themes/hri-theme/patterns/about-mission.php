<?php
/**
 * Title: About — Mission and vision
 * Slug: hri/about-mission
 * Categories: hri
 * Keywords: about, mission, vision, พันธกิจ, วิสัยทัศน์
 * Description: Mission and Vision, each with an illustration (texts of the old About page).
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-mission-section', '', 'mission', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_about_row( __( "Mission | พันธกิจ", 'hri-theme' ), "Develop Leaders.\nTransform the Future.", __( "พัฒนาผู้นำ ขับเคลื่อนสู่อนาคต", 'hri-theme' ), __( "HRI DeOne มุ่งพัฒนาผู้นำที่ไม่เพียงเติบโตในความสามารถของตนเอง แต่พร้อมสร้างการเปลี่ยนแปลงเชิงบวกให้กับผู้คน องค์กร และสังคม เพื่อร่วมขับเคลื่อนอนาคตที่ดีกว่า", 'hri-theme' ), false )
	. hri_about_row( __( "Vision | วิสัยทัศน์", 'hri-theme' ), "A trusted partner\nin shaping organizations\nthat thrive across generations.", __( "พันธมิตรที่องค์กรไว้วางใจ\nเพื่อร่วมสร้างองค์กรที่เติบโตอย่างยั่งยืนจากรุ่นสู่รุ่น", 'hri-theme' ), __( "HRI DeOne มุ่งเป็นมากกว่าผู้ให้บริการฝึกอบรม เราพร้อมเดินเคียงข้างองค์กรในฐานะพันธมิตรระยะยาว ร่วมพัฒนาคน ผู้นำ และองค์กรให้แข็งแกร่ง ปรับตัวได้อย่างยั่งยืนในโลกธุรกิจ และส่งต่อคุณค่าอย่างต่อเนื่องจากรุ่นสู่รุ่น", 'hri-theme' ), true )
	. hri_bm_section_end();
