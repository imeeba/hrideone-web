<?php
/**
 * Title: Home — Hero
 * Slug: hri/home-hero
 * Categories: hri
 * Keywords: hero, banner, video, หน้าแรก
 * Description: Full-width hero. Background: an image or a short muted video (Media Library, MP4/WebM); a video longer than 10 seconds loops its first 10 seconds.
 *
 * @package hri-theme
 */

$hri_hero = get_theme_file_uri( 'assets/images/home/hero.svg' );
?>
<!-- wp:cover {"url":"<?php echo esc_url( $hri_hero ); ?>","dimRatio":70,"overlayColor":"primary-deep","isUserOverlayColor":true,"align":"full","className":"is-style-hero","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover alignfull is-style-hero"><span aria-hidden="true" class="wp-block-cover__background has-primary-deep-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( $hri_hero ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"base"} -->
<p class="is-style-eyebrow has-base-color has-text-color">Develop Leaders, Transform the Future</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"textColor":"base"} -->
<h1 class="wp-block-heading has-base-color has-text-color"><?php esc_html_e( 'พัฒนาผู้นำ ขับเคลื่อนองค์กรสู่อนาคต', 'hri-theme' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"base","fontSize":"large"} -->
<p class="has-base-color has-text-color has-large-font-size"><?php esc_html_e( 'หลักสูตรพัฒนาผู้นำและองค์กร ผสานแนวคิดการทำงานแบบญี่ปุ่น เพื่อสร้างองค์กรที่เติบโตอย่างยั่งยืนข้ามรุ่น', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-light"} -->
<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="/public-training/"><?php esc_html_e( 'หลักสูตรที่เปิดรับสมัคร', 'hri-theme' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-light"} -->
<div class="wp-block-button is-style-outline-light"><a class="wp-block-button__link wp-element-button" href="/in-house/"><?php esc_html_e( 'In-house Training', 'hri-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
