<?php
/**
 * Trainers (วิทยากร): one entry per person, reused by any number of courses.
 *
 * Title = name, excerpt = position, content = short bio and certifications, featured image = photo.
 * A course lists its trainers with checkboxes in the course details box (meta `hri_trainers`);
 * the `hri/course-trainers` block prints them. There are no public trainer pages.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_post_type(
			'trainer',
			array(
				'labels'              => array(
					'name'          => __( 'วิทยากร', 'hri-core' ),
					'singular_name' => __( 'วิทยากร', 'hri-core' ),
					'menu_name'     => __( 'วิทยากร', 'hri-core' ),
					'all_items'     => __( 'วิทยากรทั้งหมด', 'hri-core' ),
					'add_new_item'  => __( 'เพิ่มวิทยากร', 'hri-core' ),
					'edit_item'     => __( 'แก้ไขวิทยากร', 'hri-core' ),
					'search_items'  => __( 'ค้นหาวิทยากร', 'hri-core' ),
					'not_found'     => __( 'ยังไม่มีวิทยากร', 'hri-core' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-businessperson',
				'menu_position'       => 6,
				'show_in_rest'        => true,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
				'rewrite'             => false,
			)
		);
	}
);

/**
 * Trainers can be translated (name, position, bio per language).
 */
add_filter(
	'pll_get_post_types',
	static function ( $post_types, $is_settings ) {
		if ( $is_settings ) {
			unset( $post_types['trainer'] );
		} else {
			$post_types['trainer'] = 'trainer';
		}
		return $post_types;
	},
	10,
	2
);

/**
 * Trainers offered in the course details box: source-language entries only (id => name).
 *
 * @return array<int,string>
 */
function hri_trainer_choices(): array {
	$args = array(
		'post_type'        => 'trainer',
		'post_status'      => 'publish',
		'numberposts'      => 100,
		'orderby'          => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'suppress_filters' => false,
	);
	if ( function_exists( 'pll_default_language' ) && pll_default_language() ) {
		$args['lang'] = pll_default_language();
	}
	$out = array();
	foreach ( get_posts( $args ) as $post ) {
		$out[ $post->ID ] = get_the_title( $post );
	}
	return $out;
}

/**
 * Trainer IDs of a course, in the visitor's language (the translation when there is one).
 *
 * @return int[]
 */
function hri_course_trainer_ids( int $course_id ): array {
	$ids  = array_map( 'intval', array_filter( explode( ',', (string) get_post_meta( $course_id, 'hri_trainers', true ) ) ) );
	$lang = function_exists( 'pll_current_language' ) ? (string) pll_current_language() : '';
	$out  = array();
	foreach ( $ids as $id ) {
		if ( $lang && function_exists( 'pll_get_post' ) ) {
			$id = (int) ( pll_get_post( $id, $lang ) ?: $id );
		}
		if ( 'publish' === get_post_status( $id ) ) {
			$out[] = $id;
		}
	}
	return $out;
}
