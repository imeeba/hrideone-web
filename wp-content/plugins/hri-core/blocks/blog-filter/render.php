<?php
/**
 * Blog Category Filter block (server-rendered): "All" + one button per category with posts.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$terms = get_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_category' ) ),
	)
);
if ( is_wp_error( $terms ) || ! $terms ) {
	return;
}

$posts_page = (int) get_option( 'page_for_posts' );
if ( $posts_page && function_exists( 'pll_get_post' ) ) {
	$posts_page = (int) pll_get_post( $posts_page ) ? (int) pll_get_post( $posts_page ) : $posts_page;
}
$all_url = $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
$current = is_category() ? (int) get_queried_object_id() : 0;

$items = sprintf(
	'<li><a href="%1$s"%2$s>%3$s</a></li>',
	esc_url( $all_url ),
	$current ? '' : ' aria-current="page"',
	esc_html__( 'ทั้งหมด', 'hri-core' )
);
foreach ( $terms as $term ) {
	$items .= sprintf(
		'<li><a href="%1$s"%2$s>%3$s</a></li>',
		esc_url( get_term_link( $term ) ),
		$current === (int) $term->term_id ? ' aria-current="page"' : '',
		esc_html( $term->name )
	);
}

printf(
	'<nav %1$s aria-label="%2$s"><ul>%3$s</ul></nav>',
	get_block_wrapper_attributes( array( 'class' => 'hri-blogfilter' ) ), // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
	esc_attr__( 'หมวดหมู่บทความ', 'hri-core' ),
	$items // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
);
