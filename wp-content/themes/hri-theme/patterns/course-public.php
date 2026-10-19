<?php
/**
 * Title: Course page — Public Training (content)
 * Slug: hri/course-public
 * Categories: hri
 * Keywords: course, public, หลักสูตร, page
 * Block Types: core/post-content
 * Post Types: course
 * Description: Sections for a Public Training course page: overview, problems, outcomes, quote, daily program, trainers, highlights, FAQ and the closing register button. The hero, key facts, price and form come from the course details. Replace the sample texts.
 *
 * @package hri-theme
 */

echo hri_course_page_markup( hri_course_page_sample( 'public' ), 'public' ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
