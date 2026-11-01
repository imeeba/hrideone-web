<?php
/**
 * Title: About — Hero
 * Slug: hri/about-hero
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Page introduction: brand name and the closing line of the old About page.
 *
 * @package hri-theme
 */

echo hri_bm_banner( // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
	array(
		'eyebrow' => 'About Us',
		'title'   => 'HRI DeOne',
		'lead'    => 'A trusted partner in shaping organizations that thrive across generations.',
	)
);
