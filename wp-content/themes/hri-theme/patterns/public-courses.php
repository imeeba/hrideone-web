<?php
/**
 * Title: Public Training — Course list
 * Slug: hri/public-courses
 * Categories: hri
 * Keywords: public, courses, filter, หลักสูตร
 * Description: Search and filters, locked to Public Training (latest open batch of each course). Anchor #courses.
 *
 * @package hri-theme
 */

$hri_out  = hri_bm_section( 'hri-courses-section', '', 'courses', '', '70' );
$hri_out .= hri_bm_head( __( 'หลักสูตรที่เปิดรับสมัคร', 'hri-theme' ), 'Open Courses', __( 'แต่ละหลักสูตรแสดงรุ่นล่าสุดที่เปิดอยู่ พร้อมวันที่และสถานะการรับสมัคร', 'hri-theme' ) );
$hri_out .= "<!-- wp:hri/course-filter {\"program\":\"public\"} /-->\n";
$hri_out .= hri_bm_section_end();
echo $hri_out; // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
