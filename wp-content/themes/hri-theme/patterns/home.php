<?php
/**
 * Title: Home page (all sections)
 * Slug: hri/home
 * Categories: hri
 * Keywords: home, front page, หน้าแรก
 * Block Types: core/post-content
 * Post Types: page
 * Description: Every home page section in order. Insert into an empty page, then edit the texts and images.
 *
 * @package hri-theme
 */

foreach ( hri_home_sections() as $hri_section ) {
	include __DIR__ . "/home-{$hri_section}.php";
}
