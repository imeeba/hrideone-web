<?php
/**
 * Title: In-house — Course list
 * Slug: hri/inhouse-courses
 * Categories: hri
 * Keywords: in-house, courses, filter, หลักสูตร
 * Description: Search and filters, locked to In-house courses. Anchor #courses (the hero button links here).
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( 'hri-courses-section', 'surface-brand', 'courses' );
$hri_out .= hri_bm_head( __( 'หลักสูตร In-house', 'hri-theme' ), 'Our Courses', __( 'เลือกดูตามหมวด หัวข้อ ระดับผู้เรียน หรือรูปแบบการอบรม ทุกหลักสูตรปรับเนื้อหาให้ตรงกับองค์กรของคุณได้', 'hri-theme' ) );
$hri_out .= "<!-- wp:hri/course-filter {\"program\":\"inhouse\"} /-->\n";
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
