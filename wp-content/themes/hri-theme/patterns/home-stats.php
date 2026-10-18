<?php
/**
 * Title: Home — Statistics
 * Slug: hri/home-stats
 * Categories: hri
 * Keywords: stats, numbers, ตัวเลข
 * Description: Four key numbers on a dark band. Edit the numbers and labels directly.
 *
 * @package hri-theme
 */

$hri_stats = array(
	array( '20+', __( 'ปีแห่งประสบการณ์ ให้บริการองค์กรชั้นนำในประเทศไทย', 'hri-theme' ) ),
	array( '500+', __( 'องค์กรที่ให้ความไว้วางใจใช้บริการ', 'hri-theme' ) ),
	array( '2,500+', __( 'คลาสที่จัดอบรมมาแล้ว', 'hri-theme' ) ),
	array( '150+', __( 'วิทยากรและผู้บริหารที่เป็น Partner', 'hri-theme' ) ),
);
?>
<!-- wp:group {"align":"full","className":"hri-section hri-stats","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"primary-deep","textColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section hri-stats has-base-color has-primary-deep-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"className":"hri-stats__grid","layout":{"type":"grid","minimumColumnWidth":"14rem"}} -->
<div class="wp-block-group hri-stats__grid">
<?php foreach ( $hri_stats as $hri_stat ) : ?>
<!-- wp:group {"className":"hri-stat","layout":{"type":"default"}} -->
<div class="wp-block-group hri-stat"><!-- wp:paragraph {"className":"hri-stat__number"} -->
<p class="hri-stat__number"><?php echo esc_html( $hri_stat[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"hri-stat__label"} -->
<p class="hri-stat__label"><?php echo esc_html( $hri_stat[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
