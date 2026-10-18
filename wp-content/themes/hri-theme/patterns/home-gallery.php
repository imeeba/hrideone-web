<?php
/**
 * Title: Home — Gallery
 * Slug: hri/home-gallery
 * Categories: hri
 * Keywords: gallery, slider, photos, บรรยากาศ
 * Description: Photo slider (Gallery, style "Slider"): add or remove images in the Gallery, one slide per image.
 *
 * @package hri-theme
 */

$hri_photo = get_theme_file_uri( 'assets/images/placeholder/photo.svg' );
?>
<!-- wp:group {"align":"full","className":"hri-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow"><?php esc_html_e( 'บรรยากาศการจัดอบรมที่ผ่านมา', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Gallery</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":1,"linkTo":"none","align":"wide","className":"is-style-slider"} -->
<figure class="wp-block-gallery alignwide has-nested-images columns-1 is-cropped is-style-slider">
<?php for ( $hri_i = 0; $hri_i < 4; $hri_i++ ) : ?>
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $hri_photo ); ?>" alt=""/></figure>
<!-- /wp:image -->
<?php endfor; ?>
</figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->
