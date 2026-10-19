<?php
/**
 * Small helpers that print core block markup for the theme's patterns.
 *
 * The output is ordinary, saved block markup (the same HTML the editor writes), so pages built
 * from a pattern stay fully editable in the block editor. Texts are escaped here; translate them
 * with __() before passing them in.
 *
 * @package hri-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block comment attributes as JSON (unicode and slashes kept readable, like the editor).
 */
function hri_bm_attrs( array $attrs ): string {
	return $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '';
}

/**
 * Class attribute value from a list (empty parts dropped).
 */
function hri_bm_class( array $classes ): string {
	return implode( ' ', array_filter( $classes ) );
}

/**
 * core/paragraph.
 *
 * @param string $html  Paragraph inner HTML (already escaped / allowed tags only).
 * @param array  $attrs className, align, fontSize, textColor.
 */
function hri_bm_p( string $html, array $attrs = array() ): string {
	$classes = array(
		! empty( $attrs['align'] ) ? 'has-text-align-' . $attrs['align'] : '',
		$attrs['className'] ?? '',
		! empty( $attrs['textColor'] ) ? 'has-' . $attrs['textColor'] . '-color has-text-color' : '',
		! empty( $attrs['fontSize'] ) ? 'has-' . $attrs['fontSize'] . '-font-size' : '',
	);
	$class = hri_bm_class( $classes );
	return '<!-- wp:paragraph' . hri_bm_attrs( $attrs ) . " -->\n<p" . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . $html . "</p>\n<!-- /wp:paragraph -->\n";
}

/**
 * Eyebrow paragraph (small label above a title).
 */
function hri_bm_eyebrow( string $text, bool $center = true, string $color = '' ): string {
	$attrs = $center ? array( 'align' => 'center' ) : array();
	$attrs['className'] = 'is-style-eyebrow';
	if ( $color ) {
		$attrs['textColor'] = $color;
	}
	return hri_bm_p( esc_html( $text ), $attrs );
}

/**
 * core/heading.
 *
 * @param array $attrs level, textAlign, className, anchor, textColor.
 */
function hri_bm_heading( string $text, array $attrs = array() ): string {
	$level   = (int) ( $attrs['level'] ?? 2 );
	$classes = array(
		'wp-block-heading',
		! empty( $attrs['textAlign'] ) ? 'has-text-align-' . $attrs['textAlign'] : '',
		$attrs['className'] ?? '',
		! empty( $attrs['textColor'] ) ? 'has-' . $attrs['textColor'] . '-color has-text-color' : '',
	);
	if ( 2 === $level ) {
		unset( $attrs['level'] );
	}
	$id = ! empty( $attrs['anchor'] ) ? ' id="' . esc_attr( $attrs['anchor'] ) . '"' : '';
	return '<!-- wp:heading' . hri_bm_attrs( $attrs ) . " -->\n<h{$level}{$id} class=\"" . esc_attr( hri_bm_class( $classes ) ) . '">' . esc_html( $text ) . "</h{$level}>\n<!-- /wp:heading -->\n";
}

/**
 * Section title (H2, style "Section title"), centred by default.
 */
function hri_bm_title( string $text, bool $center = true, string $color = '', string $class = '' ): string {
	$attrs = $center ? array( 'textAlign' => 'center' ) : array();
	$attrs['className'] = hri_bm_class( array( 'is-style-section-title', $class ) );
	if ( $color ) {
		$attrs['textColor'] = $color;
	}
	return hri_bm_heading( $text, $attrs );
}

/**
 * core/buttons with core/button children.
 *
 * @param array  $buttons List of array( label, href, style ) — style: '' (filled), 'outline', 'light', 'outline-light'.
 * @param string $justify '' (left), 'center'.
 */
function hri_bm_buttons( array $buttons, string $justify = '', string $class = '' ): string {
	$attrs = array();
	if ( $class ) {
		$attrs['className'] = $class;
	}
	if ( $justify ) {
		$attrs['layout'] = array( 'type' => 'flex', 'justifyContent' => $justify );
	}
	$out = '<!-- wp:buttons' . hri_bm_attrs( $attrs ) . " -->\n<div class=\"" . esc_attr( hri_bm_class( array( 'wp-block-buttons', $class ) ) ) . '">';
	foreach ( $buttons as $button ) {
		list( $label, $href ) = $button;
		$style    = $button[2] ?? '';
		$b_attrs  = $style ? array( 'className' => 'is-style-' . $style ) : array();
		$out     .= '<!-- wp:button' . hri_bm_attrs( $b_attrs ) . " -->\n"
			. '<div class="' . esc_attr( hri_bm_class( array( 'wp-block-button', $style ? 'is-style-' . $style : '' ) ) ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_attr( $href ) . '">' . esc_html( $label ) . "</a></div>\n<!-- /wp:button -->\n\n";
	}
	return rtrim( $out ) . "</div>\n<!-- /wp:buttons -->\n";
}

/**
 * core/list (unordered) with core/list-item children.
 *
 * @param string[] $items Item inner HTML (escape before passing).
 */
function hri_bm_list( array $items, string $class = '' ): string {
	$attrs = $class ? array( 'className' => $class ) : array();
	$out   = '<!-- wp:list' . hri_bm_attrs( $attrs ) . " -->\n<ul class=\"" . esc_attr( hri_bm_class( array( 'wp-block-list', $class ) ) ) . '">';
	foreach ( $items as $item ) {
		$out .= '<!-- wp:list-item --><li>' . $item . '</li><!-- /wp:list-item -->';
	}
	return $out . "</ul>\n<!-- /wp:list -->\n";
}

/**
 * Opening of a core/group (close with hri_bm_group_end()).
 *
 * @param array $attrs Group attributes (className, align, anchor, backgroundColor, textColor, layout, style.spacing.padding).
 */
function hri_bm_group( array $attrs = array(), string $tag = 'div' ): string {
	if ( 'div' !== $tag ) {
		$attrs = array( 'tagName' => $tag ) + $attrs;
	}
	if ( empty( $attrs['layout'] ) ) {
		$attrs['layout'] = array( 'type' => 'default' );
	}
	$classes = array(
		'wp-block-group',
		! empty( $attrs['align'] ) ? 'align' . $attrs['align'] : '',
		$attrs['className'] ?? '',
		! empty( $attrs['textColor'] ) ? 'has-' . $attrs['textColor'] . '-color' : '',
		! empty( $attrs['backgroundColor'] ) ? 'has-' . $attrs['backgroundColor'] . '-background-color' : '',
		! empty( $attrs['textColor'] ) ? 'has-text-color' : '',
		! empty( $attrs['backgroundColor'] ) ? 'has-background' : '',
	);
	$style   = '';
	$padding = $attrs['style']['spacing']['padding'] ?? array();
	foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
		if ( ! empty( $padding[ $side ] ) ) {
			$style .= "padding-{$side}:" . preg_replace( '#^var:preset\|spacing\|(\w+)$#', 'var(--wp--preset--spacing--$1)', $padding[ $side ] ) . ';';
		}
	}
	$id = ! empty( $attrs['anchor'] ) ? ' id="' . esc_attr( $attrs['anchor'] ) . '"' : '';
	return '<!-- wp:group' . hri_bm_attrs( $attrs ) . " -->\n<{$tag}{$id} class=\"" . esc_attr( hri_bm_class( $classes ) ) . '"' . ( $style ? ' style="' . esc_attr( rtrim( $style, ';' ) ) . '"' : '' ) . '>';
}

/**
 * Closing of a core/group.
 */
function hri_bm_group_end( string $tag = 'div' ): string {
	return "</{$tag}>\n<!-- /wp:group -->\n";
}

/**
 * Full-width page section (constrained to 1200px) with the standard vertical padding.
 *
 * @param string $class      Extra class names.
 * @param string $background Background color slug ('' = none).
 * @param string $anchor     HTML id (for #links).
 * @param string $text_color Text color slug.
 */
function hri_bm_section( string $class = '', string $background = '', string $anchor = '', string $text_color = '', string $top = '80', string $bottom = '80' ): string {
	$attrs = array( 'align' => 'full', 'className' => hri_bm_class( array( 'hri-section', $class ) ) );
	if ( $anchor ) {
		$attrs['anchor'] = $anchor;
	}
	$attrs['style'] = array( 'spacing' => array( 'padding' => array( 'top' => "var:preset|spacing|{$top}", 'bottom' => "var:preset|spacing|{$bottom}" ) ) );
	if ( $background ) {
		$attrs['backgroundColor'] = $background;
	}
	if ( $text_color ) {
		$attrs['textColor'] = $text_color;
	}
	$attrs['layout'] = array( 'type' => 'constrained', 'contentSize' => '1200px' );
	return hri_bm_group( $attrs, 'section' );
}

/**
 * Closing of hri_bm_section().
 */
function hri_bm_section_end(): string {
	return hri_bm_group_end( 'section' );
}

/**
 * Photo placeholder (core/image). The sample-content scripts swap it for a real photo; the
 * marketing team replaces it in the editor.
 */
function hri_bm_photo( string $alt = '' ): string {
	return "<!-- wp:image {\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( get_theme_file_uri( 'assets/images/placeholder/photo.svg' ) ) . '" alt="' . esc_attr( $alt ) . "\"/></figure>\n<!-- /wp:image -->\n";
}

/**
 * Image from the theme folder (core/image, full size).
 */
function hri_bm_image( string $path, string $alt = '', string $class = '' ): string {
	$attrs = array( 'sizeSlug' => 'full', 'linkDestination' => 'none' );
	if ( $class ) {
		$attrs['className'] = $class;
	}
	return '<!-- wp:image' . hri_bm_attrs( $attrs ) . " -->\n<figure class=\"" . esc_attr( hri_bm_class( array( 'wp-block-image', 'size-full', $class ) ) ) . '"><img src="' . esc_url( get_theme_file_uri( $path ) ) . '" alt="' . esc_attr( $alt ) . "\"/></figure>\n<!-- /wp:image -->\n";
}

/**
 * core/details (FAQ item: question in the summary, answer paragraphs inside).
 *
 * @param string[] $answer Paragraph inner HTML (escape before passing).
 */
function hri_bm_details( string $question, array $answer, string $class = '' ): string {
	$attrs = $class ? array( 'className' => $class ) : array();
	$out   = '<!-- wp:details' . hri_bm_attrs( $attrs ) . " -->\n<details class=\"" . esc_attr( hri_bm_class( array( 'wp-block-details', $class ) ) ) . '"><summary>' . esc_html( $question ) . "</summary>";
	foreach ( $answer as $paragraph ) {
		$out .= hri_bm_p( $paragraph );
	}
	return $out . "</details>\n<!-- /wp:details -->\n";
}

/**
 * Contact page path (internal link, localized per language at render time).
 */
function hri_bm_contact(): string {
	return '/contact-us/';
}

/**
 * A grid of cards: number / label, title, text, optional list and link.
 *
 * @param array $cards   Each: array( 'num' => '', 'kicker' => '', 'title' => '', 'text' => '', 'list' => string[], 'link' => array( label, href ), 'anchor' => '' ).
 * @param int   $columns Desktop column count.
 */
function hri_bm_cards( array $cards, int $columns = 3, string $class = '' ): string {
	$out = hri_bm_group(
		array(
			'className' => hri_bm_class( array( 'hri-cards', "hri-cards--{$columns}", $class ) ),
			'layout'    => array( 'type' => 'grid', 'columnCount' => $columns, 'minimumColumnWidth' => '16rem' ),
		)
	);
	foreach ( $cards as $card ) {
		$out .= hri_bm_group( array( 'className' => 'is-style-card hri-card' ) );
		if ( ! empty( $card['num'] ) ) {
			// 'num_html' = the number is already HTML (e.g. a kanji in <span lang="ja">).
			$out .= hri_bm_p( empty( $card['num_html'] ) ? esc_html( $card['num'] ) : $card['num'], array( 'className' => 'hri-card__num' ) );
		}
		if ( ! empty( $card['kicker'] ) ) {
			$out .= hri_bm_p( esc_html( $card['kicker'] ), array( 'className' => 'hri-card__kicker' ) );
		}
		$out .= hri_bm_heading( $card['title'], array( 'level' => 3, 'className' => 'hri-card__title' ) );
		if ( ! empty( $card['text'] ) ) {
			$out .= hri_bm_p( esc_html( $card['text'] ), array( 'className' => 'hri-card__text' ) );
		}
		if ( ! empty( $card['list'] ) ) {
			$out .= hri_bm_list( array_map( 'esc_html', $card['list'] ), 'hri-checklist' );
		}
		if ( ! empty( $card['link'] ) ) {
			$out .= hri_bm_p( '<a href="' . esc_attr( $card['link'][1] ) . '">' . esc_html( $card['link'][0] ) . '</a>', array( 'className' => 'hri-card__link' ) );
		}
		$out .= hri_bm_group_end();
	}
	return $out . hri_bm_group_end();
}

/**
 * Numbered steps (01 → 0n), connected by a line on wide screens.
 *
 * @param array $steps Each: array( title, text ).
 */
function hri_bm_steps( array $steps ): string {
	$out = hri_bm_group( array( 'className' => 'hri-steps hri-steps--' . count( $steps ) ) );
	foreach ( $steps as $i => $step ) {
		$out .= hri_bm_group( array( 'className' => 'hri-step' ) )
			. hri_bm_p( sprintf( '%02d', $i + 1 ), array( 'className' => 'hri-step__num' ) )
			. hri_bm_heading( $step[0], array( 'level' => 3, 'className' => 'hri-step__title' ) )
			. ( ! empty( $step[1] ) ? hri_bm_p( esc_html( $step[1] ), array( 'className' => 'hri-step__text' ) ) : '' )
			. hri_bm_group_end();
	}
	return $out . hri_bm_group_end();
}

/**
 * Section heading block: eyebrow, title and an optional lead paragraph, centred.
 * $sentence: the title is a sentence (not a short label), so it keeps its letter case.
 */
function hri_bm_head( string $eyebrow, string $title, string $lead = '', string $color = '', bool $sentence = false ): string {
	$out = $eyebrow ? hri_bm_eyebrow( $eyebrow, true, 'base' === $color ? 'accent' : '' ) : '';
	$out .= hri_bm_title( $title, true, $color, $sentence ? 'hri-title-sentence' : '' );
	if ( $lead ) {
		$out .= hri_bm_p( esc_html( $lead ), array( 'align' => 'center', 'className' => 'hri-lead', 'fontSize' => 'large' ) );
	}
	return $out;
}

/**
 * Page hero: text (eyebrow, H1, lead, buttons, key facts) next to a photo, on the light brand surface.
 *
 * @param array $hero eyebrow, title, lead, buttons (see hri_bm_buttons), chips (string[]), photo_alt.
 */
function hri_bm_pagehero( array $hero ): string {
	$out  = hri_bm_group(
		array(
			'align'           => 'full',
			'className'       => 'hri-section hri-pagehero',
			'style'           => array( 'spacing' => array( 'padding' => array( 'top' => 'var:preset|spacing|70', 'bottom' => 'var:preset|spacing|70' ) ) ),
			'backgroundColor' => 'surface-brand',
			'layout'          => array( 'type' => 'constrained', 'contentSize' => '1200px' ),
		)
	);
	$out .= hri_bm_group( array( 'className' => 'hri-pagehero__grid' ) );
	$out .= hri_bm_group( array( 'className' => 'hri-pagehero__text' ) );
	$out .= hri_bm_eyebrow( $hero['eyebrow'], false );
	$out .= hri_bm_heading( $hero['title'], array( 'level' => 1, 'className' => 'hri-pagehero__title' ) );
	$out .= hri_bm_p( esc_html( $hero['lead'] ), array( 'className' => 'hri-pagehero__lead', 'fontSize' => 'large' ) );
	$out .= hri_bm_buttons( $hero['buttons'] );
	if ( ! empty( $hero['chips'] ) ) {
		$out .= hri_bm_list( array_map( 'esc_html', $hero['chips'] ), 'hri-chips' );
	}
	$out .= hri_bm_group_end();
	$out .= hri_bm_photo( $hero['photo_alt'] ?? '' );
	$out .= hri_bm_group_end();
	return $out . hri_bm_group_end();
}

/**
 * Call-to-action band inside a page (dark), centred.
 */
function hri_bm_ctaband( string $title, string $text, array $buttons, string $anchor = '' ): string {
	$buttons = array_map(
		static function ( $b, $i ) {
			$b[2] = $b[2] ?? ( 0 === $i ? 'light' : 'outline-light' );
			return $b;
		},
		$buttons,
		array_keys( $buttons )
	);
	return hri_bm_section( 'hri-pagecta', 'primary-deep', $anchor, 'base', '70', '70' )
		. hri_bm_heading( $title, array( 'textAlign' => 'center', 'className' => 'hri-pagecta__title', 'textColor' => 'base' ) )
		. ( $text ? hri_bm_p( esc_html( $text ), array( 'align' => 'center', 'className' => 'hri-pagecta__text', 'fontSize' => 'large' ) ) : '' )
		. hri_bm_buttons( $buttons, 'center' )
		. hri_bm_section_end();
}

/**
 * Phone number from Settings → ข้อมูลบริษัท as a tel: link.
 */
function hri_bm_phone_link(): string {
	$phone = function_exists( 'hri_company' ) ? (string) hri_company( 'phone' ) : '';
	$phone = '' !== $phone ? $phone : '065-097-9444';
	return '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
}

/**
 * "How do I get started / sign up" FAQ answer: contact page link, phone, LINE.
 */
function hri_bm_contact_answer(): string {
	return sprintf(
		/* translators: 1: link to the contact page, 2: phone number link */
		esc_html__( 'กรอกแบบฟอร์มใน%1$s โทร %2$s หรือทักทาง LINE ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง', 'hri-theme' ),
		'<a href="' . esc_attr( hri_bm_contact() ) . '">' . esc_html__( 'หน้าติดต่อเรา', 'hri-theme' ) . '</a>',
		hri_bm_phone_link()
	);
}

/**
 * "Do you have these problems?" box: a heading and a list of pain points.
 *
 * @param string[] $items Plain-text items.
 */
function hri_bm_problems( string $title, array $items ): string {
	return hri_bm_group( array( 'className' => 'hri-problems' ) )
		. hri_bm_heading( $title, array( 'level' => 3, 'className' => 'hri-problems__title' ) )
		. hri_bm_list( array_map( 'esc_html', $items ), 'hri-problems__list' )
		. hri_bm_group_end();
}

/**
 * Service detail section: tagline (eyebrow), service name, lead, then the given inner markup
 * and a "talk to us about …" button.
 */
function hri_bm_service( string $anchor, string $background, string $tagline, string $name, string $lead, string $inner ): string {
	return hri_bm_section( 'hri-service', $background, $anchor )
		. hri_bm_head( $tagline, $name, $lead )
		. $inner
		/* translators: %s: service name, e.g. Business Strategy */
		. hri_bm_buttons( array( array( sprintf( __( 'ปรึกษาเรื่อง %s', 'hri-theme' ), $name ), hri_bm_contact() ) ), 'center' )
		. hri_bm_section_end();
}
