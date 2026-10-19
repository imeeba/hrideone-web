<?php
/**
 * Course Trainers block (server-rendered): a section with one card per trainer.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$ids     = $post_id ? hri_course_trainer_ids( $post_id ) : array();
if ( ! $ids ) {
	return;
}

$cards = '';
foreach ( $ids as $id ) {
	$trainer  = get_post( $id );
	$position = has_excerpt( $id ) ? get_the_excerpt( $id ) : '';
	$photo    = has_post_thumbnail( $id )
		? get_the_post_thumbnail( $id, 'medium', array( 'alt' => '', 'loading' => 'lazy' ) )
		: '<img src="' . esc_url( get_theme_file_uri( 'assets/images/placeholder/portrait.svg' ) ) . '" alt="" loading="lazy" width="400" height="400">';
	$cards   .= sprintf(
		'<article class="hri-trainer"><div class="hri-trainer__photo">%1$s</div><div class="hri-trainer__body"><h3 class="hri-trainer__name">%2$s</h3>%3$s<div class="hri-trainer__bio">%4$s</div></div></article>',
		$photo, // phpcs:ignore WordPress.Security.EscapeOutput -- core markup / fixed.
		esc_html( get_the_title( $trainer ) ),
		$position ? '<p class="hri-trainer__position">' . esc_html( $position ) . '</p>' : '',
		do_blocks( (string) $trainer->post_content ) // phpcs:ignore WordPress.Security.EscapeOutput -- rendered blocks.
	);
}

$eyebrow = '' !== (string) ( $attributes['eyebrow'] ?? '' ) ? (string) $attributes['eyebrow'] : __( 'วิทยากร', 'hri-core' );
$title   = '' !== (string) ( $attributes['title'] ?? '' ) ? (string) $attributes['title'] : 'Keynote Speaker';

printf(
	'<section %1$s><p class="is-style-eyebrow has-text-align-center">%2$s</p><h2 class="wp-block-heading has-text-align-center is-style-section-title">%3$s</h2><div class="hri-trainers__list hri-trainers__list--%4$d">%5$s</div></section>',
	get_block_wrapper_attributes( array( 'class' => 'hri-trainers hri-section' ) ), // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
	esc_html( $eyebrow ),
	esc_html( $title ),
	min( 4, count( $ids ) ),
	$cards // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
);
