<?php
/**
 * Image handling: keep uploads small without a plugin.
 *
 * - Large originals are scaled down to HRI_IMAGE_MAX_EDGE on upload.
 * - JPEG/PNG sub-sizes are written as WebP when the server's image editor supports it.
 * - Sub-sizes the theme never uses are not generated.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'HRI_IMAGE_MAX_EDGE' ) ) {
	define( 'HRI_IMAGE_MAX_EDGE', 1920 );
}

if ( ! defined( 'HRI_IMAGE_QUALITY' ) ) {
	define( 'HRI_IMAGE_QUALITY', 78 );
}

add_filter( 'big_image_size_threshold', static fn() => HRI_IMAGE_MAX_EDGE );

add_filter( 'wp_editor_set_quality', static fn() => HRI_IMAGE_QUALITY );

add_filter(
	'image_editor_output_format',
	static function ( array $formats ): array {
		static $webp = null;
		$webp ??= wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) );
		if ( ! $webp ) {
			return $formats;
		}
		$formats['image/jpeg'] = 'image/webp';
		$formats['image/png']  = 'image/webp';
		return $formats;
	}
);

add_filter(
	'intermediate_image_sizes_advanced',
	static function ( array $sizes ): array {
		// medium_large (768) and the 2x sizes duplicate what srcset already gets from large/medium.
		unset( $sizes['medium_large'], $sizes['1536x1536'], $sizes['2048x2048'] );
		return $sizes;
	}
);
