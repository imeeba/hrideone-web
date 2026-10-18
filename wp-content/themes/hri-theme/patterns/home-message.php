<?php
/**
 * Title: Home — Message from the executives
 * Slug: hri/home-message
 * Categories: hri
 * Keywords: ceo, message, executives, สาส์น, ผู้บริหาร
 * Description: Two executive quotes with photos (placeholders).
 *
 * @package hri-theme
 */

$hri_people = array(
	array(
		'quote' => __( 'การก่อตั้ง HRI DeOne ไม่ใช่เพียงการรวมกันของสองบริษัท แต่เป็นการรวมกันของสองจุดแข็ง สองวัฒนธรรม และวิสัยทัศน์เดียวกัน', 'hri-theme' ),
		'name'  => 'Dr. Chaipat Lertlucktaweekul',
		'role'  => 'Board of Director, CEO',
	),
	array(
		'quote' => __( 'HRI DeOne มุ่งหวังที่จะเป็นสะพานเชื่อมระหว่างญี่ปุ่น ไทย และภูมิภาคเอเชียตะวันออกเฉียงใต้ พร้อมเป็นพันธมิตรที่ร่วมสร้างการเติบโตในอนาคตไปพร้อมกับทุกท่าน', 'hri-theme' ),
		'name'  => 'Mr. Ken Misaka',
		'role'  => 'Board of Director, Chairman',
	),
);
$hri_portrait = get_theme_file_uri( 'assets/images/placeholder/portrait.svg' );
?>
<!-- wp:group {"align":"full","className":"hri-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"surface-brand","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section has-surface-brand-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow"><?php esc_html_e( 'สาส์นจากผู้บริหาร', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Message from CEO</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"hri-messages","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":"20rem"}} -->
<div class="wp-block-group hri-messages">
<?php foreach ( $hri_people as $hri_person ) : ?>
<!-- wp:group {"className":"is-style-card hri-message","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card hri-message"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"hri-message__photo"} -->
<figure class="wp-block-image size-full hri-message__photo"><img src="<?php echo esc_url( $hri_portrait ); ?>" alt="<?php echo esc_attr( $hri_person['name'] ); ?>" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:quote {"className":"hri-message__quote"} -->
<blockquote class="wp-block-quote hri-message__quote"><!-- wp:paragraph -->
<p><?php echo esc_html( $hri_person['quote'] ); ?></p>
<!-- /wp:paragraph --><cite><strong><?php echo esc_html( $hri_person['name'] ); ?></strong><br><?php echo esc_html( $hri_person['role'] ); ?></cite></blockquote>
<!-- /wp:quote --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
