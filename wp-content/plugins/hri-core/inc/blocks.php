<?php
/**
 * Block registration. Blocks are server-rendered, so there is no build step.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		hri_register_plugin_style( 'hri-courses', 'courses.css' );

		hri_register_plugin_style( 'hri-language', 'language.css' );

		hri_register_plugin_style( 'hri-search', 'search.css' );

		hri_register_plugin_style( 'hri-search-results', 'search-results.css', array( 'hri-courses' ) );

		hri_register_plugin_style( 'hri-company', 'company.css' );

		hri_register_plugin_style( 'hri-lead', 'lead.css' );

		foreach ( array( 'course-filter', 'course-facts', 'language-switcher', 'logo', 'company-text', 'contact-info', 'social-links', 'copyright', 'cta-band', 'course-highlight', 'lead-form', 'course-register', 'course-summary', 'course-trainers', 'blog-filter', 'related-posts', 'search-form', 'search-results' ) as $block ) {
			register_block_type( HRI_CORE_DIR . 'blocks/' . $block );
		}
	},
	20
);
