<?php
/**
 * Title: Blog — Post list
 * Slug: hri/blog-loop
 * Categories: hri
 * Keywords: blog, posts, list, บทความ
 * Description: Category buttons, a grid of the posts of this page (image, category, date, title, excerpt) and pagination. Used by the blog and category templates.
 *
 * @package hri-theme
 */

?>
<!-- wp:group {"align":"full","className":"hri-section hri-bloglist","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section hri-bloglist" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:hri/blog-filter /-->

<!-- wp:query {"queryId":21,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"hri-posts hri-posts--list"} -->
<div class="wp-block-query hri-posts hri-posts--list"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<!-- wp:group {"className":"is-style-card hri-post","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card hri-post"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","className":"hri-post__media"} /-->

<!-- wp:group {"className":"hri-post__body","layout":{"type":"default"}} -->
<div class="wp-block-group hri-post__body"><!-- wp:post-terms {"term":"category","fontSize":"small","className":"hri-post__cat"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->

<!-- wp:post-excerpt {"excerptLength":28,"fontSize":"small","textColor":"contrast-muted"} /-->

<!-- wp:post-date {"fontSize":"small","textColor":"contrast-muted"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"},"className":"hri-pagination"} -->
<!-- wp:query-pagination-previous {"label":"<?php echo esc_attr( __( 'ก่อนหน้า', 'hri-theme' ) ); ?>"} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":"<?php echo esc_attr( __( 'ถัดไป', 'hri-theme' ) ); ?>"} /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><?php echo esc_html( __( 'ยังไม่มีบทความ', 'hri-theme' ) ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
