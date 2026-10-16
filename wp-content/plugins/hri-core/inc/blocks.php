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
		wp_register_style( 'hri-courses', plugins_url( 'assets/courses.css', HRI_CORE_DIR . 'hri-core.php' ), array(), HRI_CORE_VERSION );

		foreach ( array( 'course-filter', 'course-facts' ) as $block ) {
			register_block_type( HRI_CORE_DIR . 'blocks/' . $block );
		}
	},
	20
);
