<?php
/**
 * Plugin Name: HRI Core
 * Description: Site features for HRI DeOne (content model, search, lead forms, images, SEO). Theme-independent.
 * Version: 0.11.0
 * Requires at least: 6.6
 * Requires PHP: 8.1
 * Author: HRI DeOne
 * License: GPL-2.0-or-later
 * Text Domain: hri-core
 * Domain Path: /languages
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

define( 'HRI_CORE_VERSION', '0.11.0' );
define( 'HRI_CORE_DIR', __DIR__ . '/' );

foreach ( array( 'cleanup', 'icons', 'images', 'images-optimize', 'content-model', 'trainers', 'course-card', 'course-filter', 'languages', 'polylang', 'company', 'leads', 'blog', 'blog-import', 'search', 'search-admin', 'blocks' ) as $module ) {
	require_once HRI_CORE_DIR . "inc/{$module}.php";
}

/**
 * Interface translations (languages/hri-core-{locale}). Thai is the source language.
 */
add_action(
	'init',
	static function () {
		load_plugin_textdomain( 'hri-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	},
	0
);
