<?php
/**
 * Related Posts block (server-rendered): three cards, same category first.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
if ( ! $post_id ) {
	return;
}

$args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'post__not_in'   => array( $post_id ),
	'no_found_rows'  => true,
);
$cats = wp_get_post_categories( $post_id );
$cats = array_values( array_diff( $cats, array( (int) get_option( 'default_category' ) ) ) );
$related = $cats ? get_posts( array_merge( $args, array( 'category__in' => $cats ) ) ) : array();
if ( count( $related ) < 3 ) {
	$have    = wp_list_pluck( $related, 'ID' );
	$related = array_merge( $related, get_posts( array_merge( $args, array( 'post__not_in' => array_merge( array( $post_id ), $have ), 'posts_per_page' => 3 - count( $related ) ) ) ) );
}
if ( ! $related ) {
	return;
}

$cards = '';
foreach ( $related as $item ) {
	$link  = get_permalink( $item );
	$image = has_post_thumbnail( $item ) ? get_the_post_thumbnail( $item, 'hri-card', array( 'loading' => 'lazy', 'alt' => '', 'sizes' => '(min-width: 782px) 33vw, (min-width: 600px) 50vw, 100vw' ) ) : '';
	$cards .= sprintf(
		'<article class="wp-block-group is-style-card hri-post">%1$s<div class="hri-post__body"><time class="wp-block-post-date has-small-font-size has-contrast-muted-color has-text-color" datetime="%2$s">%3$s</time><h3 class="wp-block-post-title has-large-font-size"><a href="%4$s">%5$s</a></h3></div></article>',
		$image ? '<a class="hri-post__media" href="' . esc_url( $link ) . '" tabindex="-1" aria-hidden="true">' . $image . '</a>' : '', // phpcs:ignore WordPress.Security.EscapeOutput -- core markup.
		esc_attr( get_the_date( 'c', $item ) ),
		esc_html( get_the_date( '', $item ) ),
		esc_url( $link ),
		esc_html( get_the_title( $item ) )
	);
}

$title = '' !== (string) ( $attributes['title'] ?? '' ) ? (string) $attributes['title'] : __( 'บทความที่เกี่ยวข้อง', 'hri-core' );
printf(
	'<section %1$s><h2 class="wp-block-heading has-text-align-center is-style-section-title">%2$s</h2><div class="hri-related__grid">%3$s</div></section>',
	get_block_wrapper_attributes( array( 'class' => 'hri-related' ) ), // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
	esc_html( $title ),
	$cards // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
);
