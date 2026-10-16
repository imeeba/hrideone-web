<?php
/**
 * Course Facts block (server-rendered).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$facts   = $post_id ? hri_course_facts( $post_id ) : '';

if ( $facts ) {
	echo '<div ' . get_block_wrapper_attributes() . '>' . $facts . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
}
