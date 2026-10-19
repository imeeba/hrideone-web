<?php
/**
 * Shared by seed.php and seed-languages.php.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Content of a page built from patterns (hri_page_sections(): home, in-house, public-training,
 * transformation-solutions, japanese-concept) in one language: its section patterns rendered with
 * that language's theme translations ('' = Thai, the source language). Placeholder photos become
 * the sample images so every photo slot has something to show.
 */
if ( ! function_exists( 'hri_seed_page_content' ) ) {
	function hri_seed_page_content( string $page, string $locale = '' ): string {
		if ( ! function_exists( 'hri_page_sections' ) ) {
			return '';
		}
		unload_textdomain( 'hri-theme' );
		if ( $locale ) {
			load_textdomain( 'hri-theme', get_template_directory() . "/languages/{$locale}.mo", $locale );
		} else {
			$GLOBALS['l10n']['hri-theme'] = new NOOP_Translations(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
		ob_start();
		hri_include_page_sections( $page );
		$content = (string) ob_get_clean();
		unload_textdomain( 'hri-theme', true );

		return hri_seed_swap_photos( $content );
	}
}

/**
 * Placeholder photos in $content become the sample images.
 */
if ( ! function_exists( 'hri_seed_swap_photos' ) ) {
	function hri_seed_swap_photos( string $content ): string {
		$photos = get_posts( array( 'post_type' => 'attachment', 'post_mime_type' => 'image/jpeg', 'numberposts' => 4, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC' ) );
		$i      = 0;
		return (string) preg_replace_callback(
			'#<!-- wp:image \{"sizeSlug":"large","linkDestination":"none"\} -->\s*<figure class="wp-block-image size-large"><img src="[^"]*placeholder/photo\.svg" alt="([^"]*)"/></figure>#',
			static function ( $m ) use ( $photos, &$i ) {
				$id  = $photos[ $i++ % max( 1, count( $photos ) ) ] ?? 0;
				$src = $id ? wp_get_attachment_image_url( $id, 'large' ) : '';
				if ( ! $src ) {
					return $m[0];
				}
				return sprintf( '<!-- wp:image {"id":%1$d,"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="%2$s" alt="%3$s" class="wp-image-%1$d"/></figure>', $id, esc_url( $src ), $m[1] );
			},
			$content
		);
	}
}

/**
 * Body of a course page (hri_course_page_markup()) written in one language: $data holds the texts
 * already in that language, the fixed headings come from the theme translations.
 */
if ( ! function_exists( 'hri_seed_course_body' ) ) {
	function hri_seed_course_body( array $data, string $program, string $locale = '' ): string {
		unload_textdomain( 'hri-theme' );
		if ( $locale ) {
			load_textdomain( 'hri-theme', get_template_directory() . "/languages/{$locale}.mo", $locale );
		} else {
			$GLOBALS['l10n']['hri-theme'] = new NOOP_Translations(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
		$content = hri_course_page_markup( $data, $program );
		unload_textdomain( 'hri-theme', true );
		return hri_seed_swap_photos( $content );
	}
}

/**
 * Home page content in one language (kept for older callers).
 */
if ( ! function_exists( 'hri_seed_home_content' ) ) {
	function hri_seed_home_content( string $locale = '' ): string {
		return hri_seed_page_content( 'home', $locale );
	}
}
