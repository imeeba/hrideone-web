<?php
/**
 * Title: Course archive header
 * Slug: hri/archive-course-header
 * Categories: hri
 * Description: Heading of /courses/. Text comes from the theme translations (TH / EN / JA).
 * Inserter: no
 *
 * @package hri-theme
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"}}},"backgroundColor":"surface-brand","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-surface-brand-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">HRI DeOne</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'หลักสูตรทั้งหมด', 'hri-theme' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"large","textColor":"contrast-muted"} -->
<p class="has-contrast-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'ค้นหาและกรองหลักสูตร In-house และ Public Training ตามหมวด หัวข้อ ระดับผู้เรียน และรูปแบบ', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
