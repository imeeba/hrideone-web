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
 * Theme supports.
 */
add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/base.css' );
		remove_theme_support( 'core-block-patterns' );
	}
);

/**
 * Whether the Japanese font set is needed on this request.
 * Its @font-face list is ~100 KB, so it only loads on Japanese pages and the style guide.
 */
function hri_needs_japanese_fonts(): bool {
	$needs = str_starts_with( determine_locale(), 'ja' ) || is_page( 'style-guide' );
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

		if ( is_page( 'style-guide' ) ) {
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

		register_block_pattern_category( 'hri', array( 'label' => __( 'HRI DeOne', 'hri-theme' ) ) );
	}
);
