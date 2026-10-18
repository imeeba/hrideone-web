<?php
/**
 * Shared by seed.php and seed-languages.php.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Home page content in one language: the home patterns of the theme, rendered with that
 * language's theme translations ('' = Thai, the source language). Placeholder photos in the
 * Gallery become the sample images so the slider has something to show.
 */
if ( ! function_exists( 'hri_seed_home_content' ) ) {
	function hri_seed_home_content( string $locale = '' ): string {
		if ( ! function_exists( 'hri_home_sections' ) ) {
			return '';
		}
		unload_textdomain( 'hri-theme' );
		if ( $locale ) {
			load_textdomain( 'hri-theme', get_template_directory() . "/languages/{$locale}.mo", $locale );
		} else {
			$GLOBALS['l10n']['hri-theme'] = new NOOP_Translations(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
		ob_start();
		foreach ( hri_home_sections() as $section ) {
			include get_template_directory() . "/patterns/home-{$section}.php";
		}
		$content = (string) ob_get_clean();
		unload_textdomain( 'hri-theme', true );

		$photos = get_posts( array( 'post_type' => 'attachment', 'post_mime_type' => 'image/jpeg', 'numberposts' => 4, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC' ) );
		$i      = 0;
		return (string) preg_replace_callback(
			'#<!-- wp:image \{"sizeSlug":"large","linkDestination":"none"\} -->\s*<figure class="wp-block-image size-large"><img src="[^"]*placeholder/photo\.svg" alt=""/></figure>#',
			static function ( $m ) use ( $photos, &$i ) {
				$id = $photos[ $i++ % max( 1, count( $photos ) ) ] ?? 0;
				$src = $id ? wp_get_attachment_image_url( $id, 'large' ) : '';
				if ( ! $src ) {
					return $m[0];
				}
				return sprintf( '<!-- wp:image {"id":%1$d,"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="%2$s" alt="" class="wp-image-%1$d"/></figure>', $id, esc_url( $src ) );
			},
			$content
		);
	}
}
