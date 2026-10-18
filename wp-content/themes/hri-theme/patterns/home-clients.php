<?php
/**
 * Title: Home — Our customers
 * Slug: hri/home-clients
 * Categories: hri
 * Keywords: clients, customers, logos, ลูกค้า
 * Description: Logo wall (Gallery, style "Logos"). Replace the placeholders with client logos and give each one the company name as alt text.
 *
 * @package hri-theme
 */

$hri_logo = get_theme_file_uri( 'assets/images/placeholder/logo.svg' );
?>
<!-- wp:group {"align":"full","className":"hri-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow"><?php esc_html_e( 'ลูกค้าที่ให้ความไว้วางใจ', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Our Customers</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":6,"imageCrop":false,"linkTo":"none","className":"is-style-logos"} -->
<figure class="wp-block-gallery has-nested-images columns-6 is-style-logos">
<?php for ( $hri_i = 0; $hri_i < 12; $hri_i++ ) : ?>
<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $hri_logo ); ?>" alt=""/></figure>
<!-- /wp:image -->
<?php endfor; ?>
</figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->
