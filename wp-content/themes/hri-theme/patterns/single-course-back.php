<?php
/**
 * Title: Course back link
 * Slug: hri/single-course-back
 * Categories: hri
 * Description: "All courses" link above a course title, to the course list in the visitor's language.
 * Inserter: no
 *
 * @package hri-theme
 */

$hri_courses_url = get_post_type_archive_link( 'course' );
?>
<!-- wp:paragraph {"className":"is-style-eyebrow hri-backlink"} -->
<p class="is-style-eyebrow hri-backlink"><a href="<?php echo esc_url( $hri_courses_url ? $hri_courses_url : home_url( '/courses/' ) ); ?>"><?php echo function_exists( 'hri_icon' ) ? hri_icon( 'arrow-left' ) : '← '; // phpcs:ignore WordPress.Security.EscapeOutput -- local SVG. ?><?php esc_html_e( 'หลักสูตรทั้งหมด', 'hri-theme' ); ?></a></p>
<!-- /wp:paragraph -->
