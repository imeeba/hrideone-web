<?php
/**
 * Title: Home — Knowledge (latest posts)
 * Slug: hri/home-knowledge
 * Categories: hri
 * Keywords: blog, posts, articles, บทความ, คลังความรู้
 * Description: The three latest posts in the visitor's language, with a link to the blog.
 *
 * @package hri-theme
 */

?>
<!-- wp:group {"align":"full","className":"hri-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"base-alt","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section has-base-alt-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow"><?php esc_html_e( 'คลังความรู้', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Knowledge Tanks</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"hri-posts"} -->
<div class="wp-block-query hri-posts"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<!-- wp:group {"className":"is-style-card hri-post","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card hri-post"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","sizeSlug":"hri-card","className":"hri-post__media"} /-->

<!-- wp:group {"className":"hri-post__body","layout":{"type":"default"}} -->
<div class="wp-block-group hri-post__body"><!-- wp:post-date {"fontSize":"small","textColor":"contrast-muted"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'ยังไม่มีบทความ', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/blog/"><?php esc_html_e( 'ดูบทความทั้งหมด', 'hri-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
