<?php
/**
 * Title: Home — Message from the executives
 * Slug: hri/home-message
 * Categories: hri
 * Keywords: ceo, message, executives, สาส์น, ผู้บริหาร
 * Description: The CEO and the Chairman: large portrait, headline and the first paragraphs of their message, with a link to the full text on the About page.
 *
 * @package hri-theme
 */

$hri_leaders = hri_leaders();
echo hri_bm_section( 'hri-message-section', 'surface-brand', 'message', '', '80', '80' ) // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	. hri_bm_head( __( 'สาส์นจากผู้บริหาร', 'hri-theme' ), 'Message from CEO & Chairman', '', '', true )
	. hri_bm_leader( $hri_leaders[0], false, 2, '/about/#message' )
	. hri_bm_leader( $hri_leaders[1], true, 2, '/about/#message' )
	. hri_bm_section_end();
