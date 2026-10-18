<?php
/**
 * HRI DeOne theme bootstrap.
 *
 * Presentation only. Features (post types, search, forms, SEO) live in the hri-core plugin.
 *
 * @package hri-theme
 */

defined( 'ABSPATH' ) || exit;

define( 'HRI_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Theme supports and translations (languages/hri-theme-{locale}).
 */
add_action(
	'after_setup_theme',
	static function () {
		load_theme_textdomain( 'hri-theme', get_template_directory() . '/languages' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/base.css' );
		remove_theme_support( 'core-block-patterns' );
	}
);

/**
 * Whether this is the Style Guide page (or its EN / JA translation).
 */
function hri_is_style_guide(): bool {
	if ( is_page( 'style-guide' ) ) {
		return true;
	}
	if ( ! is_page() || ! function_exists( 'pll_get_post' ) || ! function_exists( 'pll_default_language' ) ) {
		return false;
	}
	$source = pll_get_post( get_queried_object_id(), pll_default_language() );
	return $source && 'style-guide' === get_post_field( 'post_name', $source );
}

/**
 * Whether the Japanese font set is needed on this request.
 * Its @font-face list is ~100 KB, so it only loads on Japanese pages and the style guide.
 */
function hri_needs_japanese_fonts(): bool {
	$needs = str_starts_with( determine_locale(), 'ja' ) || hri_is_style_guide();
	return (bool) apply_filters( 'hri_needs_japanese_fonts', $needs );
}

/**
 * Front-end assets.
 */
add_action(
	'wp_enqueue_scripts',
	static function () {
		$uri = get_theme_file_uri( 'assets/css/' );
		wp_enqueue_style( 'hri-base', $uri . 'base.css', array(), HRI_THEME_VERSION );

		if ( hri_needs_japanese_fonts() ) {
			wp_enqueue_style( 'hri-fonts-ja', $uri . 'fonts-ja.css', array(), HRI_THEME_VERSION );
		}

		if ( hri_is_style_guide() ) {
			wp_enqueue_style( 'hri-style-guide', $uri . 'style-guide.css', array( 'hri-base' ), HRI_THEME_VERSION );
		}
	}
);

/**
 * Editor gets the Japanese fonts too, so translators see the real glyphs.
 */
add_action(
	'enqueue_block_editor_assets',
	static function () {
		wp_enqueue_style( 'hri-fonts-ja', get_theme_file_uri( 'assets/css/fonts-ja.css' ), array(), HRI_THEME_VERSION );
	}
);

/**
 * Preload the two Kanit files every Thai page needs above the fold.
 */
add_action(
	'wp_head',
	static function () {
		foreach ( array( 'kanit-thai-300-normal', 'kanit-latin-300-normal' ) as $file ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( get_theme_file_uri( "assets/fonts/kanit/{$file}.woff2" ) )
			);
		}
	},
	1
);

/**
 * Block style variations (styling lives in theme.json / base.css).
 */
add_action(
	'init',
	static function () {
		register_block_style( 'core/button', array( 'name' => 'light', 'label' => __( 'Light (on dark)', 'hri-theme' ) ) );
		register_block_style( 'core/paragraph', array( 'name' => 'eyebrow', 'label' => __( 'Eyebrow', 'hri-theme' ) ) );
		register_block_style( 'core/group', array( 'name' => 'card', 'label' => __( 'Card', 'hri-theme' ) ) );
		register_block_style( 'core/heading', array( 'name' => 'section-title', 'label' => __( 'Section title', 'hri-theme' ) ) );
		register_block_style( 'core/button', array( 'name' => 'outline-light', 'label' => __( 'Outline light (on dark)', 'hri-theme' ) ) );
		register_block_style( 'core/cover', array( 'name' => 'hero', 'label' => __( 'Hero (video ≤ 10 s)', 'hri-theme' ) ) );
		register_block_style( 'core/gallery', array( 'name' => 'slider', 'label' => __( 'Slider', 'hri-theme' ) ) );
		register_block_style( 'core/gallery', array( 'name' => 'logos', 'label' => __( 'Logos', 'hri-theme' ) ) );

		register_block_pattern_category( 'hri', array( 'label' => __( 'HRI DeOne', 'hri-theme' ) ) );
	}
);

/**
 * Navigation blocks without a menu name get an empty or numbered aria-label (" 2") from core.
 * Give them a real one: main menu in the header, footer menu below.
 */
add_filter(
	'render_block_core/navigation',
	static function ( $content, $block ) {
		$tags = new WP_HTML_Tag_Processor( $content );
		if ( ! $tags->next_tag( 'nav' ) ) {
			return $content;
		}
		$label = trim( (string) $tags->get_attribute( 'aria-label' ) );
		if ( '' !== $label && ! preg_match( '/^\d+$/', $label ) ) {
			return $content;
		}
		$is_footer = str_contains( (string) ( $block['attrs']['className'] ?? '' ), 'hri-footer__nav' );
		$tags->set_attribute( 'aria-label', $is_footer ? __( 'เมนูท้ายเว็บ', 'hri-theme' ) : __( 'เมนูหลัก', 'hri-theme' ) );
		return $tags->get_updated_html();
	},
	10,
	2
);

/**
 * Home page sections, in order (patterns/home-{slug}.php). Used by the "Home page" pattern.
 *
 * @return string[]
 */
function hri_home_sections(): array {
	return array( 'hero', 'intro', 'highlight', 'services', 'stats', 'clients', 'message', 'gallery', 'knowledge' );
}

/**
 * Small scripts, loaded only on pages that use them:
 * - Hero cover with a video: plays at most the first 10 seconds in a loop, pause button,
 *   no autoplay for visitors who prefer reduced motion.
 * - Gallery "Slider": previous / next buttons and dots on a scroll-snap strip (works without JS).
 */
add_filter(
	'render_block',
	static function ( $content, $block ) {
		$name  = $block['blockName'] ?? '';
		$class = (string) ( $block['attrs']['className'] ?? '' );
		if ( 'core/cover' === $name && str_contains( $class, 'is-style-hero' ) && 'video' === ( $block['attrs']['backgroundType'] ?? '' ) ) {
			hri_enqueue_script( 'hero' );
		} elseif ( 'core/gallery' === $name && str_contains( $class, 'is-style-slider' ) ) {
			hri_enqueue_script( 'slider' );
		}
		return $content;
	},
	10,
	2
);

/**
 * Enqueue assets/js/{name}.js with its translated UI labels.
 */
function hri_enqueue_script( string $name ): void {
	$handle = "hri-{$name}";
	if ( wp_script_is( $handle ) ) {
		return;
	}
	wp_enqueue_script( $handle, get_theme_file_uri( "assets/js/{$name}.js" ), array(), HRI_THEME_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$labels = 'hero' === $name
		? array(
			'pause' => __( 'หยุดวิดีโอ', 'hri-theme' ),
			'play'  => __( 'เล่นวิดีโอ', 'hri-theme' ),
		)
		: array(
			'prev'  => __( 'รูปก่อนหน้า', 'hri-theme' ),
			'next'  => __( 'รูปถัดไป', 'hri-theme' ),
			/* translators: 1: slide number, 2: number of slides */
			'slide' => __( 'รูปที่ %1$s จาก %2$s', 'hri-theme' ),
		);
	wp_add_inline_script( $handle, 'window.hriL10n=Object.assign(window.hriL10n||{},' . wp_json_encode( array( $name => $labels ) ) . ');', 'before' );
}

/**
 * Browser icon from the logo mark until a Site Icon is set (Settings → General).
 */
add_action(
	'wp_head',
	static function () {
		if ( ! has_site_icon() ) {
			printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( get_theme_file_uri( 'assets/images/logo/mark.svg' ) ) );
		}
	},
	2
);
