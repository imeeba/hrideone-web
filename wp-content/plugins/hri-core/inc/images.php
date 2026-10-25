<?php
/**
 * Image handling: keep uploads small without a plugin.
 *
 * - JPG/PNG files are converted to WebP (or AVIF, see HRI_IMAGE_FORMAT) the moment they are received
 *   (upload in wp-admin, sideload from code, the blog importer's download mode). The JPG/PNG is deleted,
 *   so the media library keeps one small file per image. Orientation (EXIF) is applied first.
 * - The long edge is capped at HRI_IMAGE_MAX_EDGE, quality is HRI_IMAGE_QUALITY.
 * - Sub-sizes the theme never uses are not generated; "hri-card" (3:2) is the size of every card.
 * - If the server cannot write the format, or the result is not smaller, the original is kept
 *   (still scaled down by WordPress at the same edge) and the upload never fails.
 *
 * wp-config.php (all optional):
 *   define( 'HRI_IMAGE_FORMAT', 'webp' );   // 'webp' (default) | 'avif' (used only if the server supports it)
 *   define( 'HRI_IMAGE_MAX_EDGE', 1600 );
 *   define( 'HRI_IMAGE_QUALITY', 78 );
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'HRI_IMAGE_MAX_EDGE' ) ) {
	define( 'HRI_IMAGE_MAX_EDGE', 1600 );
}

if ( ! defined( 'HRI_IMAGE_QUALITY' ) ) {
	define( 'HRI_IMAGE_QUALITY', 78 );
}

if ( ! defined( 'HRI_IMAGE_FORMAT' ) ) {
	define( 'HRI_IMAGE_FORMAT', 'webp' );
}

/** Card image (courses, articles, related articles, home): 3:2, 2x of a ~400px card. */
add_image_size( 'hri-card', 800, 533, true );

/**
 * MIME type new images are saved as, or '' when the server can write neither WebP nor AVIF.
 */
function hri_image_target_mime(): string {
	static $mime = null;
	if ( null !== $mime ) {
		return $mime;
	}
	$mime = '';
	if ( 'avif' === strtolower( (string) HRI_IMAGE_FORMAT ) && wp_image_editor_supports( array( 'mime_type' => 'image/avif' ) ) ) {
		$mime = 'image/avif';
	} elseif ( wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
		$mime = 'image/webp';
	}
	return $mime;
}

/**
 * Convert one JPG/PNG file to the target format, in place of the original.
 *
 * @param string $file    Absolute path of the JPG/PNG.
 * @param int    $quality Quality override (default HRI_IMAGE_QUALITY).
 * @param bool   $dry     Only measure: nothing is written or deleted and 'file' is returned empty.
 * @return array{file:string,type:string,before:int,after:int}|false New file info, or false when the file
 *                                                                  was left untouched (unsupported, failed, or not smaller).
 */
function hri_image_convert_file( string $file, int $quality = 0, bool $dry = false ) {
	$target = hri_image_target_mime();
	if ( '' === $target || ! is_file( $file ) ) {
		return false;
	}
	$type = wp_check_filetype( $file )['type'];
	if ( ! in_array( $type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return false;
	}
	$before = (int) filesize( $file );
	$info   = wp_getimagesize( $file );
	if ( ! $info ) {
		return false;
	}

	$editor = wp_get_image_editor( $file );
	if ( is_wp_error( $editor ) ) {
		return false;
	}
	// WordPress re-reads the quality from wp_editor_set_quality when it saves under a new file type, so an
	// override has to be a filter (later than ours at priority 10) that stays on until save() returns.
	$override = static fn() => $quality;
	if ( $quality > 0 ) {
		add_filter( 'wp_editor_set_quality', $override, 99 );
	}
	$editor->set_quality( $quality > 0 ? $quality : HRI_IMAGE_QUALITY );
	if ( method_exists( $editor, 'maybe_exif_rotate' ) ) {
		$editor->maybe_exif_rotate();
	}
	$size   = $editor->get_size();
	$edge   = max( (int) $size['width'], (int) $size['height'] );
	$scaled = false;
	if ( $edge > HRI_IMAGE_MAX_EDGE ) {
		$ratio  = HRI_IMAGE_MAX_EDGE / $edge;
		$result = $editor->resize( max( 1, (int) round( $size['width'] * $ratio ) ), max( 1, (int) round( $size['height'] * $ratio ) ), false );
		if ( is_wp_error( $result ) ) {
			remove_filter( 'wp_editor_set_quality', $override, 99 );
			return false;
		}
		$scaled = true;
	}

	$dir  = dirname( $file );
	$ext  = 'image/avif' === $target ? 'avif' : 'webp';
	$temp = trailingslashit( $dir ) . '.hri-' . wp_generate_password( 8, false ) . '.' . $ext;
	$saved = $editor->save( $temp, $target );
	if ( $quality > 0 ) {
		remove_filter( 'wp_editor_set_quality', $override, 99 );
	}
	if ( is_wp_error( $saved ) || empty( $saved['path'] ) || ! is_file( $saved['path'] ) ) {
		return false;
	}
	$after = (int) filesize( $saved['path'] );
	// Already-optimised small files can grow when re-encoded: keep them as they are.
	if ( ! $scaled && $after >= $before ) {
		wp_delete_file( $saved['path'] );
		return false;
	}
	if ( $dry ) {
		wp_delete_file( $saved['path'] );
		return array(
			'file'   => '',
			'type'   => $target,
			'before' => $before,
			'after'  => $after,
		);
	}
	// Delete the original first, otherwise WordPress treats "name.jpg" as a clash for "name.webp".
	wp_delete_file( $file );
	$final = trailingslashit( $dir ) . wp_unique_filename( $dir, pathinfo( $file, PATHINFO_FILENAME ) . '.' . $ext );
	if ( ! rename( $saved['path'], $final ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
		$final = $saved['path'];
	}

	return array(
		'file'   => $final,
		'type'   => $target,
		'before' => $before,
		'after'  => $after,
	);
}

/*
 * Upload, sideload and download_url()+media_handle_sideload() all end in wp_handle_upload(), whose result
 * is filtered here before WordPress creates the attachment.
 */
add_filter(
	'wp_handle_upload',
	static function ( $upload ) {
		if ( ! is_array( $upload ) || ! empty( $upload['error'] ) || empty( $upload['file'] ) ) {
			return $upload;
		}
		if ( ! apply_filters( 'hri_image_convert_upload', true, $upload ) ) {
			return $upload;
		}
		$converted = hri_image_convert_file( (string) $upload['file'] );
		if ( ! $converted ) {
			return $upload;
		}
		$upload['url']  = trailingslashit( dirname( (string) $upload['url'] ) ) . basename( $converted['file'] );
		$upload['file'] = $converted['file'];
		$upload['type'] = $converted['type'];
		return $upload;
	}
);

// Anything that still arrives bigger than the cap (conversion failed) is scaled by WordPress at the same edge.
add_filter( 'big_image_size_threshold', static fn() => HRI_IMAGE_MAX_EDGE );

add_filter( 'wp_editor_set_quality', static fn() => HRI_IMAGE_QUALITY );

add_filter(
	'image_editor_output_format',
	static function ( array $formats ): array {
		$target = hri_image_target_mime();
		if ( '' === $target ) {
			return $formats;
		}
		foreach ( array( 'image/jpeg', 'image/png', 'image/webp' ) as $from ) {
			$formats[ $from ] = $target;
		}
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

/**
 * Make "hri-card" pickable in the editor's image size menu.
 */
add_filter(
	'image_size_names_choose',
	static function ( array $sizes ): array {
		$sizes['hri-card'] = __( 'การ์ด (3:2)', 'hri-core' );
		return $sizes;
	}
);

/**
 * Cards sit in a 3 / 2 / 1 column grid, so WordPress' generic "(max-width: 800px) 100vw, 800px" would make the
 * browser pick a file twice as big as needed. Replace only that generated value; explicit `sizes` stay.
 */
add_filter(
	'wp_get_attachment_image_attributes',
	static function ( array $attr ): array {
		$is_card = isset( $attr['class'] ) && false !== strpos( (string) $attr['class'], 'size-hri-card' );
		if ( $is_card && ( empty( $attr['sizes'] ) || preg_match( '/^\(max-width: \d+px\) 100vw, \d+px$/', (string) $attr['sizes'] ) ) ) {
			$attr['sizes'] = '(min-width: 1100px) 33vw, (min-width: 600px) 50vw, 100vw';
		}
		return $attr;
	}
);
