<?php
/**
 * Title: Home — Our services
 * Slug: hri/home-services
 * Categories: hri
 * Keywords: services, บริการ
 * Description: Light-bulb infographic (01–03) with the three services around it, then three service cards (Future Leader, Transformation Solutions, Japanese Concept) with SVG illustrations.
 *
 * @package hri-theme
 */

$hri_map = array(
	array( '01', 'Future Leader', __( 'สร้างผู้นำยุคใหม่ที่แข็งแกร่งทั้งด้าน Leadership และ Business เพราะผู้นำที่แท้จริงต้องนำคนเป็นและเข้าใจธุรกิจอย่างลึกซึ้ง', 'hri-theme' ) ),
	array( '02', 'Transformation Solutions', __( 'วางกลยุทธ์และขับเคลื่อนการเปลี่ยนแปลงองค์กรแบบองค์รวม ครอบคลุม 4 มิติ ได้แก่ Business Strategy, Technology Strategy, People Strategy และ Culture Strategy', 'hri-theme' ) ),
	array( '03', 'Japanese Concept', __( 'นำปรัชญาและแนวปฏิบัติการทำงานแบบญี่ปุ่นที่พิสูจน์แล้วว่าสร้างองค์กรยั่งยืน มาประยุกต์ใช้ในบริบทไทยอย่างเป็นระบบ', 'hri-theme' ) ),
);
$hri_services = array(
	array(
		'image' => 'service-leader.svg',
		'title' => 'Future Leader',
		'tag'   => 'In-house Training',
		'text'  => __( 'พัฒนาผู้นำและทีมงานให้เติบโตได้จริง ตั้งแต่การรู้จักตัวเอง การนำทีม สู่การบริหารธุรกิจในทุกมิติ', 'hri-theme' ),
		'url'   => '/in-house/',
	),
	array(
		'image' => 'service-transformation.svg',
		'title' => 'Transformation Solutions',
		'tag'   => 'Organization Transformation',
		'text'  => __( 'โซลูชันสำหรับองค์กรที่ต้องการเปลี่ยนแปลงอย่างเป็นระบบ ครอบคลุมกลยุทธ์ธุรกิจ เทคโนโลยี คน และวัฒนธรรมองค์กร', 'hri-theme' ),
		'url'   => '/transformation-solutions/',
	),
	array(
		'image' => 'service-japanese.svg',
		'title' => 'Japanese Concept',
		'tag'   => 'Cross-cultural Collaboration',
		'text'  => __( 'แนวคิดการทำงานแบบญี่ปุ่นที่เสริมทักษะการสื่อสารและการทำงานร่วมกันข้ามวัฒนธรรม', 'hri-theme' ),
		'url'   => '/japanese-concept/',
	),
);
?>
<!-- wp:group {"align":"full","className":"hri-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"surface-brand","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull hri-section has-surface-brand-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow"><?php esc_html_e( 'บริการของเรา', 'hri-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Our Services</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"hri-svcmap","layout":{"type":"default"}} -->
<div class="wp-block-group hri-svcmap"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"hri-svcmap__bulb"} -->
<figure class="wp-block-image size-full hri-svcmap__bulb"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/home/services-bulb.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->
<?php foreach ( $hri_map as $hri_item ) : ?>
<!-- wp:group {"className":"hri-svcmap__item hri-svcmap__item--<?php echo esc_attr( $hri_item[0] ); ?>","layout":{"type":"default"}} -->
<div class="wp-block-group hri-svcmap__item hri-svcmap__item--<?php echo esc_attr( $hri_item[0] ); ?>"><!-- wp:paragraph {"className":"hri-svcmap__num"} -->
<p class="hri-svcmap__num"><?php echo esc_html( $hri_item[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"hri-svcmap__title"} -->
<h3 class="wp-block-heading hri-svcmap__title"><?php echo esc_html( $hri_item[1] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"hri-svcmap__text"} -->
<p class="hri-svcmap__text"><?php echo esc_html( $hri_item[2] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"hri-services","layout":{"type":"grid","minimumColumnWidth":"18rem"}} -->
<div class="wp-block-group hri-services">
<?php foreach ( $hri_services as $hri_service ) : ?>
<!-- wp:group {"className":"is-style-card hri-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card hri-service"><!-- wp:image {"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"hri-service__media"} -->
<figure class="wp-block-image size-full hri-service__media"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/home/' . $hri_service['image'] ) ); ?>" alt="" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"hri-service__body","layout":{"type":"default"}} -->
<div class="wp-block-group hri-service__body"><!-- wp:paragraph {"className":"hri-service__tag"} -->
<p class="hri-service__tag"><?php echo esc_html( $hri_service['tag'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"hri-service__title"} -->
<h3 class="wp-block-heading hri-service__title"><?php echo esc_html( $hri_service['title'] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"contrast-muted"} -->
<p class="has-contrast-muted-color has-text-color"><?php echo esc_html( $hri_service['text'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_attr( $hri_service['url'] ); ?>"><?php esc_html_e( 'ดูรายละเอียด', 'hri-theme' ); ?><span class="screen-reader-text"> <?php echo esc_html( $hri_service['title'] ); ?></span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
