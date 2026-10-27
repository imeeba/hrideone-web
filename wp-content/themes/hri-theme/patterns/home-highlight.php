<?php
/**
 * Title: Home — Highlight programs
 * Slug: hri/home-highlight
 * Categories: hri
 * Keywords: highlight, public, courses, หลักสูตรยอดฮิต
 * Description: Public Training courses open for registration (automatic), with a link to all Public courses.
 *
 * @package hri-theme
 */

?>
<!-- wp:group {"align":"full","className":"hri-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow"><?php esc_html_e( 'หลักสูตรยอดฮิต', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Highlight Program</h2>
<!-- /wp:heading -->

<!-- wp:hri/course-highlight {"count":6} /-->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/public-training/"><?php esc_html_e( 'ดูหลักสูตร Public Training ทั้งหมด', 'hri-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
