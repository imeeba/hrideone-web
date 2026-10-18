<?php
/**
 * Title: Course call to action
 * Slug: hri/single-course-cta
 * Categories: hri
 * Description: Enquiry button beside the course facts, to the contact page in the visitor's language.
 * Inserter: no
 *
 * @package hri-theme
 */

$hri_contact_url = function_exists( 'hri_localize_url' ) ? hri_localize_url( '/contact-us/' ) : home_url( '/contact-us/' );
?>
<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--50)">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( str_starts_with( $hri_contact_url, '/' ) ? home_url( $hri_contact_url ) : $hri_contact_url ); ?>"><?php esc_html_e( 'สนใจหลักสูตรนี้ / ขอใบเสนอราคา', 'hri-theme' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
