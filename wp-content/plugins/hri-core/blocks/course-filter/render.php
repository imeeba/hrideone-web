<?php
/**
 * Course Filter block (server-rendered).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

echo '<div ' . get_block_wrapper_attributes() . '>' . hri_render_course_filter() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
