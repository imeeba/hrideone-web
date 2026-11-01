<?php
/**
 * Title: About — Message from the executives (full)
 * Slug: hri/about-message
 * Categories: hri
 * Keywords: about, เกี่ยวกับ, สาส์น
 * Description: Full message from the CEO and the Chairman with large portraits (texts of the old About page).
 *
 * @package hri-theme
 */

$hri_leaders = hri_leaders();
echo hri_bm_section( 'hri-message-section', '', 'message', '', '70', '70' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( __( "สาสน์จากผู้บริหาร", 'hri-theme' ), 'LEADER MESSAGE' )
	. hri_bm_leader( $hri_leaders[0] )
	. hri_bm_leader( $hri_leaders[1], true )
	. hri_bm_section_end();
