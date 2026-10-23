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

		return hri_seed_swap_photos( $content, $page );
	}
}

/**
 * Real images of the old site (playground/data/site-media.json, made by scripts/site-media.py from the crawl)
 * or an empty array when the file is not there (then the sample images are used).
 */
if ( ! function_exists( 'hri_seed_media' ) ) {
	function hri_seed_media(): array {
		static $media = null;
		if ( null !== $media ) {
			return $media;
		}
		$media = array();
		foreach ( array( __DIR__ . '/data/site-media.json', __DIR__ . '/site-media.json', __DIR__ . '/hri-site-media.json' ) as $file ) {
			if ( is_readable( $file ) ) {
				$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				if ( is_array( $data ) && ! empty( $data['assets'] ) ) {
					$media = $data;
					break;
				}
			}
		}
		return $media;
	}
}

/**
 * One real image: array( url, width, height, alt ), or array() when the asset or its file is missing.
 * HRI_SITE_MEDIA_BASE (wp-config / test script) replaces the base URL of the files.
 */
if ( ! function_exists( 'hri_seed_media_asset' ) ) {
	function hri_seed_media_asset( ?string $id ): array {
		$media = hri_seed_media();
		$asset = $id ? ( $media['assets'][ $id ] ?? null ) : null;
		if ( ! $asset || empty( $asset['file'] ) ) {
			return array();
		}
		$base = defined( 'HRI_SITE_MEDIA_BASE' ) ? (string) HRI_SITE_MEDIA_BASE : (string) ( $media['base'] ?? '' );
		return array(
			'url'    => rtrim( $base, '/' ) . '/' . $asset['file'],
			'width'  => (int) ( $asset['width'] ?? 0 ),
			'height' => (int) ( $asset['height'] ?? 0 ),
			'alt'    => (string) ( $asset['alt'] ?? '' ),
		);
	}
}

/**
 * Cover / portrait of a course or trainer without uploading a file: the image stays a URL (meta read by
 * the filters in hri-core/inc/blog.php), so the demo boots fast and the browser gets a small WebP.
 */
if ( ! function_exists( 'hri_seed_set_remote_thumb' ) ) {
	function hri_seed_set_remote_thumb( int $post_id, ?string $asset_id, string $alt = '' ): bool {
		$a = hri_seed_media_asset( $asset_id );
		if ( ! $a ) {
			return false;
		}
		update_post_meta( $post_id, '_hri_thumbnail_url', esc_url_raw( $a['url'] ) );
		update_post_meta( $post_id, '_hri_thumbnail_alt', $alt );
		update_post_meta( $post_id, '_hri_thumbnail_w', $a['width'] );
		update_post_meta( $post_id, '_hri_thumbnail_h', $a['height'] );
		return true;
	}
}

/**
 * The translations of a post show the same image: copy the remote-image meta.
 */
if ( ! function_exists( 'hri_seed_copy_remote_thumb' ) ) {
	function hri_seed_copy_remote_thumb( int $from, int $to ): void {
		foreach ( array( '_hri_thumbnail_url', '_hri_thumbnail_alt', '_hri_thumbnail_w', '_hri_thumbnail_h' ) as $key ) {
			$value = get_post_meta( $from, $key, true );
			if ( '' !== $value ) {
				update_post_meta( $to, $key, $value );
			}
		}
	}
}

/**
 * Placeholder images in $content become the real images of the old site (site-media.json): hero background,
 * client / partner logos, executive portraits and the photos of the page ($context = slug of the Thai page) or
 * of a course gallery ($course = course slug). A slot without a real image (null in the list) keeps its
 * placeholder. Without site-media.json the sample images are used instead.
 */
if ( ! function_exists( 'hri_seed_swap_photos' ) ) {
	function hri_seed_swap_photos( string $content, string $context = '', string $course = '' ): string {
		$media = hri_seed_media();
		if ( $media ) {
			return hri_seed_swap_real( $content, $media, $context, $course );
		}

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
 * hri_seed_swap_photos() with the real images. No width/height attributes in the markup: core/image saves
 * them as styles, which would not match what the editor expects (the page CSS sizes the slots).
 */
if ( ! function_exists( 'hri_seed_swap_real' ) ) {
	function hri_seed_swap_real( string $content, array $media, string $context, string $course ): string {
		$use = $media['use'] ?? array();

		// Hero background of the home page (Cover block: the URL is in the block comment and in the <img>).
		$hero = hri_seed_media_asset( $use['hero'] ?? null );
		if ( $hero ) {
			$content = (string) preg_replace_callback( '#https?://[^"\s]*assets/images/home/hero\.svg#', static fn() => esc_url( $hero['url'] ), $content );
		}

		// Client logos (partner logos on the Transformation Solutions page).
		$logos = $use['transformation-solutions' === $context ? 'partners' : 'logos'] ?? array();
		$i     = 0;
		$content = (string) preg_replace_callback(
			'#<img src="[^"]*placeholder/logo\.svg" alt=""/>#',
			static function ( $m ) use ( $logos, &$i ) {
				$a = hri_seed_media_asset( $logos[ $i++ ] ?? null );
				return $a ? '<img src="' . esc_url( $a['url'] ) . '" alt="' . esc_attr( $a['alt'] ) . '"/>' : $m[0];
			},
			$content
		);

		// Executive portraits (CEO, Chairman).
		$portraits = $use['portraits'] ?? array();
		$i         = 0;
		$content   = (string) preg_replace_callback(
			'#<img src="[^"]*placeholder/portrait\.svg"#',
			static function ( $m ) use ( $portraits, &$i ) {
				$a = hri_seed_media_asset( $portraits[ $i++ ] ?? null );
				return $a ? '<img src="' . esc_url( $a['url'] ) . '"' : $m[0];
			},
			$content
		);

		// Photos: the page's own list, or the gallery of a course (its own, else the shared pool).
		$list = $use['pages'][ $context ] ?? null;
		if ( '' !== $course ) {
			$pool = $use['gallery_pool'] ?? array();
			$list = $use['course_gallery'][ $course ] ?? null;
			if ( null === $list && $pool ) {
				$offset = abs( crc32( $course ) ) % count( $pool );
				$list   = array();
				for ( $n = 0; $n < 12; $n++ ) {
					$list[] = $pool[ ( $offset + $n ) % count( $pool ) ];
				}
			}
		}
		if ( ! is_array( $list ) ) {
			return $content;
		}
		$i = 0;
		return (string) preg_replace_callback(
			'#<!-- wp:image \{"sizeSlug":"large","linkDestination":"none"\} -->\s*<figure class="wp-block-image size-large"><img src="[^"]*placeholder/photo\.svg" alt="([^"]*)"/></figure>#',
			static function ( $m ) use ( $list, &$i ) {
				$a = hri_seed_media_asset( $list[ $i++ ] ?? null );
				if ( ! $a ) {
					return $m[0];
				}
				return sprintf( '<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->' . "\n" . '<figure class="wp-block-image size-large"><img src="%1$s" alt="%2$s"/></figure>', esc_url( $a['url'] ), $m[1] );
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
	function hri_seed_course_body( $data, string $program, string $locale = '', string $slug = '' ): string {
		unload_textdomain( 'hri-theme' );
		if ( $locale ) {
			load_textdomain( 'hri-theme', get_template_directory() . "/languages/{$locale}.mo", $locale );
		} else {
			$GLOBALS['l10n']['hri-theme'] = new NOOP_Translations(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
		if ( 'sample' === $data ) {
			$data = hri_course_page_sample( $program ); // Placeholder texts, translated by the theme.
		}
		$content = hri_course_page_markup( $data, $program );
		unload_textdomain( 'hri-theme', true );
		return hri_seed_swap_photos( $content, '', $slug );
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
