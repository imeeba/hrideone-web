<?php
/**
 * Title: About — Social impact
 * Slug: hri/about-impact
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Community activities of HRI Group (photo slider, placeholders).
 *
 * @package hri-theme
 */

echo hri_bm_section( 'hri-impact-section', 'base-alt', 'social-impact', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( 'Social Impact', __( 'มุ่งพัฒนาสังคมให้น่าอยู่', 'hri-theme' ), __( 'HRI Group ร่วมทำกิจกรรมเพื่อชุมชนและสังคมอย่างต่อเนื่อง เพราะองค์กรที่ยั่งยืนเติบโตไปพร้อมกับสังคมรอบตัว', 'hri-theme' ) )
	. hri_bm_gallery( 4, __( 'กิจกรรมเพื่อสังคมของ HRI Group', 'hri-theme' ) )
	. hri_bm_section_end();
