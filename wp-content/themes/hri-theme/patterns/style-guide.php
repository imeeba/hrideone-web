<?php
/**
 * Title: Style Guide
 * Slug: hri/style-guide
 * Categories: hri
 * Description: Living style guide. Colors, type, spacing and components are read from theme.json, so this page always matches the tokens.
 * Inserter: no
 *
 * @package hri-theme
 */

$settings = wp_get_global_settings();
$custom   = $settings['custom'] ?? array();
$palette  = $settings['color']['palette']['theme'] ?? array();
$grads    = $settings['color']['gradients']['theme'] ?? array();
$sizes    = $settings['typography']['fontSizes']['theme'] ?? array();
$families = $settings['typography']['fontFamilies']['theme'] ?? array();
$spacing  = $settings['spacing']['spacingSizes']['theme'] ?? array();
$shadows  = $settings['shadow']['presets']['theme'] ?? array();
$radius   = $custom['radius'] ?? array();
$ramps    = $custom['color'] ?? array();

$luminance = static function ( string $hex ): float {
	$hex = ltrim( $hex, '#' );
	$rgb = array_map( static fn( $c ) => hexdec( $c ) / 255, str_split( $hex, 2 ) );
	$lin = array_map( static fn( $c ) => $c <= 0.03928 ? $c / 12.92 : ( ( $c + 0.055 ) / 1.055 ) ** 2.4, $rgb );
	return 0.2126 * $lin[0] + 0.7152 * $lin[1] + 0.0722 * $lin[2];
};
$contrast = static function ( string $a, string $b ) use ( $luminance ): float {
	$l1 = $luminance( $a );
	$l2 = $luminance( $b );
	return ( max( $l1, $l2 ) + 0.05 ) / ( min( $l1, $l2 ) + 0.05 );
};
$badge = static function ( float $ratio ): string {
	if ( $ratio >= 4.5 ) {
		return '<span class="sg-badge is-pass">AA</span>';
	}
	if ( $ratio >= 3 ) {
		return '<span class="sg-badge is-large">AA Large</span>';
	}
	return '<span class="sg-badge is-fail">พื้น/กราฟิกเท่านั้น</span>';
};
$white = '#FFFFFF';
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|70"}}},"backgroundColor":"surface-brand","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-brand-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--70)">
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Design System · v0.1</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">HRI DeOne Style Guide</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">ทุกค่าบนหน้านี้อ่านจาก <code>theme.json</code> ซึ่ง build มาจาก <code>tokens/tokens.json</code> แก้ token ที่เดียว ทั้งเว็บและหน้านี้เปลี่ยนตาม สีและฟอนต์อ้างอิงจาก hrideone.com ปัจจุบัน</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--40)">
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">01 · Color</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"className":"is-style-section-title"} -->
<h2 class="wp-block-heading is-style-section-title">Semantic colors</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>สีที่ทีมการตลาดเลือกได้ใน editor มีแค่ชุดนี้ (ปิดการเลือกสีอิสระไว้) ตัวเลขคือค่า contrast เทียบกับพื้นขาวตามมาตรฐาน WCAG</p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<div class="sg-swatches">
<?php foreach ( $palette as $c ) : $r = $contrast( $c['color'], $white ); ?>
	<figure class="sg-swatch">
		<div class="sg-swatch__chip" style="background:<?php echo esc_attr( $c['color'] ); ?>"></div>
		<figcaption>
			<strong><?php echo esc_html( $c['name'] ); ?></strong>
			<code>--wp--preset--color--<?php echo esc_html( $c['slug'] ); ?></code>
			<span class="sg-meta"><?php echo esc_html( strtoupper( $c['color'] ) ); ?> · <?php echo esc_html( number_format( $r, 2 ) ); ?>:1 <?php echo $badge( $r ); // phpcs:ignore ?></span>
		</figcaption>
	</figure>
<?php endforeach; ?>
</div>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Primitive ramps</h3>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="sg-ramps">
<?php foreach ( $ramps as $name => $steps ) : ?>
	<div class="sg-ramp">
		<span class="sg-ramp__name"><?php echo esc_html( $name ); ?></span>
		<div class="sg-ramp__steps">
		<?php foreach ( $steps as $step => $hex ) : $on = $contrast( $hex, $white ) >= 4.5 ? '#FFFFFF' : '#111111'; ?>
			<div class="sg-ramp__step" style="background:<?php echo esc_attr( $hex ); ?>;color:<?php echo esc_attr( $on ); ?>">
				<span><?php echo esc_html( $step ); ?></span>
				<small><?php echo esc_html( strtoupper( $hex ) ); ?></small>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
<?php endforeach; ?>
</div>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Gradients</h3>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="sg-grads">
<?php foreach ( $grads as $g ) : ?>
	<div class="sg-grad" style="background:<?php echo esc_attr( $g['gradient'] ); ?>"><span><?php echo esc_html( $g['name'] ); ?></span></div>
<?php endforeach; ?>
</div>
<!-- /wp:html -->
<!-- wp:group {"className":"sg-note","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"8px","left":{"color":"var:preset|color|highlight","width":"4px"}}},"backgroundColor":"base-alt","layout":{"type":"default"}} -->
<div class="wp-block-group sg-note has-base-alt-background-color has-background" style="border-radius:8px;border-left-color:var(--wp--preset--color--highlight);border-left-width:4px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--50)">
<!-- wp:paragraph -->
<p><strong>ข้อสังเกตด้าน Accessibility:</strong> สี Primary (#00B0BD) ที่เว็บเดิมใช้เป็นตัวหนังสือและพื้นปุ่ม มี contrast กับพื้นขาวแค่ประมาณ 2.6:1 ซึ่งอ่านยากและไม่ผ่านมาตรฐาน ในระบบใหม่ Primary ยังคงเป็นสีแบรนด์สำหรับพื้น ไอคอน และกราฟิก แต่ตัวหนังสือ ลิงก์ และปุ่มจะใช้ <strong>Primary Strong (#0F7070)</strong> ซึ่งเป็นเฉดเดียวกับโลโก้และผ่าน AA</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">02 · Typography</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"className":"is-style-section-title"} -->
<h2 class="wp-block-heading is-style-section-title">Font families</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="sg-fonts">
	<div class="sg-font" style="font-family:var(--wp--preset--font-family--body)">
		<span class="sg-meta">Body · Kanit — ไทย / English</span>
		<p class="sg-font__sample">พัฒนาผู้นำ เปลี่ยนองค์กร</p>
		<p>Develop Leaders, Transform the Future. 0123456789</p>
		<p class="sg-weights"><span style="font-weight:300">Light 300</span><span style="font-weight:400">Regular 400</span><span style="font-weight:500">Medium 500</span><span style="font-weight:600">SemiBold 600</span><span style="font-weight:700">Bold 700</span></p>
	</div>
	<div class="sg-font" style="font-family:var(--wp--preset--font-family--display)">
		<span class="sg-meta">Display · Montserrat — หัวข้อภาษาอังกฤษบน Hero</span>
		<p class="sg-font__sample" style="font-weight:700">Negotiation Mastery</p>
		<p>หัวข้อที่ผสมไทยจะ fallback เป็น Kanit โดยอัตโนมัติ</p>
	</div>
	<div class="sg-font" lang="ja">
		<span class="sg-meta">Japanese · M PLUS 1 — ใช้อัตโนมัติเมื่อเนื้อหาเป็นภาษาญี่ปุ่น</span>
		<p class="sg-font__sample" style="font-weight:600">リーダーを育て、未来を変える</p>
		<p>日本式の働き方とグローバルな知見で、タイの組織と人材の成長を支援します。</p>
	</div>
</div>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Type scale (fluid)</h3>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="sg-scale">
<?php foreach ( array_reverse( $sizes ) as $s ) : $fluid = $s['fluid'] ?? false; ?>
	<div class="sg-scale__row">
		<span class="sg-meta"><?php echo esc_html( $s['name'] ); ?> · <?php echo esc_html( is_array( $fluid ) ? $fluid['min'] . ' → ' . $fluid['max'] : $s['size'] ); ?></span>
		<span style="font-size:var(--wp--preset--font-size--<?php echo esc_attr( $s['slug'] ); ?>);line-height:1.3">หลักสูตรพัฒนาผู้นำ Leadership</span>
	</div>
<?php endforeach; ?>
</div>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Headings &amp; body (core blocks)</h3>
<!-- /wp:heading -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">H1 · The Next Director รุ่นที่ 3</h1>
<!-- /wp:heading -->
<!-- wp:heading -->
<h2 class="wp-block-heading">H2 · ทำไมต้องจัดอบรมกับ HRI DeOne</h2>
<!-- /wp:heading -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">H3 · Customization ที่ตอบโจทย์ Pain Point จริง</h3>
<!-- /wp:heading -->
<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">H4 · Facilitation &amp; Engagement Focus</h4>
<!-- /wp:heading -->
<!-- wp:heading {"level":5} -->
<h5 class="wp-block-heading">H5 · Evidence-Based Outcomes</h5>
<!-- /wp:heading -->
<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">H6 · Label</h6>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>เนื้อหาปกติใช้ Kanit น้ำหนัก 300 ขนาด 16px และ line-height 1.7 ซึ่งสูงกว่าภาษาอังกฤษทั่วไป เพื่อไม่ให้สระบนและวรรณยุกต์ภาษาไทยชนกันระหว่างบรรทัด <a href="#">ลิงก์ในเนื้อหา</a> ใช้สี Primary Strong พร้อมขีดเส้นใต้</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">03 · Space, radius &amp; elevation</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"className":"is-style-section-title"} -->
<h2 class="wp-block-heading is-style-section-title">Spacing scale</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="sg-space">
<?php foreach ( $spacing as $sp ) : ?>
	<div class="sg-space__row">
		<span class="sg-meta"><?php echo esc_html( $sp['slug'] ); ?> · <?php echo esc_html( $sp['name'] ); ?></span>
		<span class="sg-space__bar" style="width:var(--wp--preset--spacing--<?php echo esc_attr( $sp['slug'] ); ?>)"></span>
	</div>
<?php endforeach; ?>
</div>
<div class="sg-tiles">
<?php foreach ( $radius as $name => $value ) : ?>
	<div class="sg-tile" style="border-radius:<?php echo esc_attr( $value ); ?>"><span>radius / <?php echo esc_html( $name ); ?></span><small><?php echo esc_html( $value ); ?></small></div>
<?php endforeach; ?>
<?php foreach ( $shadows as $sh ) : ?>
	<div class="sg-tile" style="box-shadow:var(--wp--preset--shadow--<?php echo esc_attr( $sh['slug'] ); ?>);border-color:transparent"><span>shadow / <?php echo esc_html( $sh['slug'] ); ?></span><small><?php echo esc_html( $sh['name'] ); ?></small></div>
<?php endforeach; ?>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">04 · Components</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"className":"is-style-section-title"} -->
<h2 class="wp-block-heading is-style-section-title">Buttons</h2>
<!-- /wp:heading -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">ขอใบเสนอราคา</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">ดูรายละเอียดหลักสูตร</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}},"border":{"radius":"8px"}},"gradient":"brand","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-brand-gradient-background has-text-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)">
<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontWeight":"700"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontFamily":"display","fontSize":"xxx-large"} -->
<h3 class="wp-block-heading has-text-align-center has-base-color has-text-color has-link-color has-display-font-family has-xxx-large-font-size" style="font-weight:700">Negotiation Mastery รุ่นที่ 3</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">พลิกเกมธุรกิจด้วยสุดยอดหลักสูตรการเจรจาต่อรอง</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-light"} -->
<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="#">EXPLORE</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var:preset|spacing|60"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow">หลักสูตรยอดฮิต</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-section-title">Highlight Program</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"contrast-muted"} -->
<p class="has-text-align-center has-contrast-muted-color has-text-color">ตัวอย่าง Course Card ซึ่งจะกลายเป็น block จริงที่ดึงข้อมูลจากหลักสูตรในขั้นต่อไป</p>
<!-- /wp:paragraph -->
<?php
$cards = array(
	array( 'Leadership', 'High-Performance Leadership Mastery', 'Manager · 2 วัน · On-site / Online', 'brand' ),
	array( 'Sales', 'Negotiation Masterclass', 'Staff–Manager · 1 วัน · On-site', 'growth' ),
	array( 'Japanese Concept', 'DO · WA · KEI Executive Program', 'Executive · 3 วัน · Hybrid', 'deep' ),
);
?>
<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns">
<?php foreach ( $cards as $card ) : ?>
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card">
<!-- wp:group {"style":{"dimensions":{"minHeight":"160px"}},"gradient":"<?php echo esc_attr( $card[3] ); ?>","layout":{"type":"default"}} -->
<div class="wp-block-group has-<?php echo esc_attr( $card[3] ); ?>-gradient-background has-background" style="min-height:160px"></div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
<!-- wp:paragraph {"textColor":"secondary","fontSize":"x-small","style":{"typography":{"fontWeight":"500","textTransform":"uppercase"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size" style="font-weight:500;text-transform:uppercase"><?php echo esc_html( $card[0] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-large-font-size"><?php echo esc_html( $card[1] ); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"contrast-muted","fontSize":"small"} -->
<p class="has-contrast-muted-color has-text-color has-small-font-size"><?php echo esc_html( $card[2] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var:preset|spacing|60"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"className":"sg-stats"} -->
<div class="wp-block-columns sg-stats">
<?php foreach ( array( array( '20+', 'ปีแห่งประสบการณ์' ), array( '500+', 'องค์กรที่ไว้วางใจ' ), array( '2,500+', 'คลาสที่จัดอบรม' ), array( '150+', 'วิทยากรและ Partner' ) ) as $stat ) : ?>
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"align":"center","textColor":"primary-strong","fontSize":"xxx-large","style":{"typography":{"fontWeight":"500","lineHeight":"1.1"}}} -->
<p class="has-text-align-center has-primary-strong-color has-text-color has-xxx-large-font-size" style="font-weight:500;line-height:1.1"><?php echo esc_html( $stat[0] ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","textColor":"contrast-muted","fontSize":"small"} -->
<p class="has-text-align-center has-contrast-muted-color has-text-color has-small-font-size"><?php echo esc_html( $stat[1] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
