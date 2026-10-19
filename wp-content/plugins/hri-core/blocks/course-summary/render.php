<?php
/**
 * Course Summary block (server-rendered): fact chips and the course buttons.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
if ( ! $post_id || 'course' !== get_post_type( $post_id ) ) {
	return;
}

$chips = '';
foreach ( hri_course_hero_chips( $post_id ) as $chip ) {
	$chips .= '<li>' . esc_html( $chip ) . '</li>';
}

printf(
	'<div %1$s>%2$s%3$s</div>',
	get_block_wrapper_attributes( array( 'class' => 'hri-csum' ) ), // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
	hri_course_buttons( $post_id ), // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
	$chips ? '<ul class="hri-chips">' . $chips . '</ul>' : '' // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
);
