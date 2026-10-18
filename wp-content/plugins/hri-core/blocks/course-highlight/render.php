<?php
/**
 * Course Highlight block: course cards for the home page.
 *
 * Public Training: only courses open for registration, soonest start date first (no date last).
 * In-house: courses in menu order. Always the visitor's language (Polylang filters the query).
 * Nothing is printed when there is no course to show.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$ids = hri_course_highlight_ids(
	(string) ( $attributes['program'] ?? 'public' ),
	(int) ( $attributes['count'] ?? 4 )
);
if ( ! $ids ) {
	return;
}

echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'hri-highlight' ) ) . '><div class="hri-grid">'; // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
foreach ( $ids as $id ) {
	echo hri_course_card( $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
}
echo '</div></div>';
