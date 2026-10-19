<?php
/**
 * Course Register Button block (server-rendered).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
if ( ! $post_id || 'course' !== get_post_type( $post_id ) ) {
	return;
}

echo hri_course_buttons( $post_id, (string) ( $attributes['variant'] ?? 'default' ), ! isset( $attributes['secondary'] ) || $attributes['secondary'], ! empty( $attributes['center'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
