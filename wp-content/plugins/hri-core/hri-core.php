<?php
/**
 * Plugin Name: HRI Core
 * Description: Site features for HRI DeOne (content model, search, lead forms, images, SEO). Theme-independent.
 * Version: 0.1.0
 * Requires at least: 6.6
 * Requires PHP: 8.1
 * Author: HRI DeOne
 * License: GPL-2.0-or-later
 * Text Domain: hri-core
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

define( 'HRI_CORE_VERSION', '0.1.0' );
define( 'HRI_CORE_DIR', __DIR__ . '/' );

foreach ( array( 'cleanup', 'images', 'content-model', 'course-card', 'course-filter', 'blocks' ) as $module ) {
	require_once HRI_CORE_DIR . "inc/{$module}.php";
}
