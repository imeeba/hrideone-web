<?php
/**
 * Title: Page not found
 * Slug: hri/not-found
 * Categories: hri
 * Description: 404 message and search.
 * Inserter: no
 *
 * @package hri-theme
 */

?>
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">404</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"xxx-large"} -->
<h1 class="wp-block-heading has-xxx-large-font-size"><?php esc_html_e( 'ไม่พบหน้าที่คุณต้องการ', 'hri-theme' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:search <?php echo wp_json_encode( array( 'label' => __( 'ค้นหา', 'hri-theme' ), 'showLabel' => false, 'buttonText' => __( 'ค้นหา', 'hri-theme' ) ), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ); ?> /-->
