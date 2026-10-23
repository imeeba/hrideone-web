<?php
/**
 * Title: Search — Header
 * Slug: hri/search-hero
 * Categories: hri
 * Description: Title and search field at the top of the search results page.
 * Inserter: no
 *
 * @package hri-theme
 */

?>
<!-- wp:group {"align":"full","className":"hri-section hri-banner hri-banner--archive","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"}}},"backgroundColor":"surface-brand","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section hri-banner hri-banner--archive has-surface-brand-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"level":1,"className":"hri-pagehero__title"} -->
<h1 class="wp-block-heading hri-pagehero__title"><?php esc_html_e( 'ค้นหา', 'hri-theme' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:hri/search-form {"variant":"page"} /--></div>
<!-- /wp:group -->
