<?php
/**
 * Header menu: drop-down lists that fill themselves.
 *
 * The header (hri-theme/parts/header.html) is built from core "Submenu" blocks. Two of them are
 * lists that change on their own, so the marketing team never edits them by hand:
 *
 * - class `hri-nav-public`: every published Public Training course (title + batch number),
 *   soonest start date first. A course with its own website (TEN X, meta
 *   `hri_external_url`) links out and opens in a new tab.
 * - class `hri-nav-blog`: the blog categories that have articles, most articles first.
 *
 * Whatever is inside those two submenus in the template (a single placeholder link, so the menu
 * is not empty in the Site Editor) is replaced when the page is rendered. Both lists follow the
 * current language (Polylang filters the queries). Filter `hri_nav_items` can change them.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Items of the "Public Training" submenu.
 *
 * @return array<int, array{label:string,url:string,external:bool}>
 */
function hri_nav_public_items(): array {
	$items = array();
	$ids   = function_exists( 'hri_program_course_ids' ) ? hri_program_course_ids( 'public' ) : array();

	// Soonest start first; a course without a start date goes last (then menu order, then title).
	$sort = array();
	foreach ( $ids as $id ) {
		$start = (string) get_post_meta( $id, 'hri_start_date', true );
		$sort[ $id ] = array( '' !== $start ? $start : '9999-12-31', (int) get_post_field( 'menu_order', $id ), (string) get_the_title( $id ) );
	}
	uasort( $sort, static fn( $a, $b ) => $a <=> $b );
	$ids = array_keys( $sort );

	foreach ( $ids as $id ) {
		$meta     = function_exists( 'hri_course_meta' ) ? hri_course_meta( (int) $id ) : array();
		$external = ! empty( $meta['hri_external_url'] );
		$batch    = function_exists( 'hri_batch_number_label' ) ? hri_batch_number_label( $meta ) : '';
		$title    = html_entity_decode( get_the_title( $id ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		$items[]  = array(
			'label'    => '' !== $batch ? $title . ' · ' . $batch : $title,
			'url'      => $external ? (string) $meta['hri_external_url'] : (string) get_permalink( $id ),
			'external' => $external,
		);
	}

	/**
	 * Items of the Public Training submenu in the header.
	 *
	 * @param array $items Each: label, url, external (bool).
	 */
	return (array) apply_filters( 'hri_nav_items', $items, 'public' );
}

/**
 * Items of the "Blog" submenu: categories with articles in the current language.
 *
 * @return array<int, array{label:string,url:string,external:bool}>
 */
function hri_nav_blog_items(): array {
	$items = array();
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 8,
		)
	);
	if ( is_array( $terms ) ) {
		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			$items[] = array(
				'label'    => html_entity_decode( $term->name, ENT_QUOTES, get_bloginfo( 'charset' ) ),
				'url'      => (string) $link,
				'external' => false,
			);
		}
	}
	return (array) apply_filters( 'hri_nav_items', $items, 'blog' );
}

/**
 * Swap the placeholder children of those submenus for the generated links before they render.
 */
add_filter(
	'render_block_data',
	static function ( $block ) {
		if ( 'core/navigation-submenu' !== ( $block['blockName'] ?? '' ) || is_admin() ) {
			return $block;
		}
		$class = (string) ( $block['attrs']['className'] ?? '' );
		if ( str_contains( $class, 'hri-nav-public' ) ) {
			$items = hri_nav_public_items();
		} elseif ( str_contains( $class, 'hri-nav-blog' ) ) {
			$items = hri_nav_blog_items();
		} else {
			return $block;
		}

		$inner = array();
		foreach ( $items as $item ) {
			$attrs = array(
				'label' => $item['label'],
				'url'   => $item['url'],
				'kind'  => 'custom',
				'type'  => 'custom',
			);
			if ( ! empty( $item['external'] ) ) {
				$attrs['opensInNewTab'] = true;
				$attrs['rel']           = 'noopener';
			}
			$inner[] = array(
				'blockName'    => 'core/navigation-link',
				'attrs'        => $attrs,
				'innerBlocks'  => array(),
				'innerHTML'    => '',
				'innerContent' => array(),
			);
		}
		$block['innerBlocks']  = $inner;
		$block['innerContent'] = array_fill( 0, count( $inner ), null );
		return $block;
	},
	5 // Before the language-aware link filter in polylang.php, which then sees the new links.
);
