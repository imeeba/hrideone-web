<?php
/**
 * Title: Course page — In-house (content)
 * Slug: hri/course-inhouse
 * Categories: hri
 * Keywords: course, in-house, หลักสูตร, page
 * Block Types: core/post-content
 * Post Types: course
 * Description: Sections for an In-house course page: overview, problems, outcomes, why HRI DeOne and FAQ. The hero, key facts and form come from the course details. Replace the sample texts.
 *
 * @package hri-theme
 */

echo hri_course_page_markup( hri_course_page_sample( 'inhouse' ), 'inhouse' ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
