<?php
/**
 * Site images → media library.
 *
 * The seeded pages show the old site's images from small WebP copies hosted in the repository
 * (playground/data/site-media.json, via jsDelivr), and courses / trainers keep their picture as a URL
 * (meta _hri_thumbnail_url, see blog.php). This module downloads the full-size originals into the
 * media library (the upload hook in images.php turns them into WebP), then points the pages at the
 * library copies and gives courses / trainers a real featured image, so editors can replace any
 * picture in wp-admin and nothing depends on GitHub or jsDelivr.
 *
 * Safe to run again: images are found by their source URL, and URLs that were already replaced are
 * not touched. Text editors changed in the page is kept (only image URLs are replaced).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * The images of site-media.json: copy URL (what the pages use now) => array( original URL, fallback URL, alt ).
 *
 * @return array<string,array{0:string,1:string,2:string}>
 */
function hri_site_media_list(): array {
	$dir  = function_exists( 'hri_setup_dir' ) ? hri_setup_dir() : '';
	$file = $dir ? $dir . 'data/site-media.json' : '';
	if ( ! $file || ! is_readable( $file ) ) {
		return array();
	}
	$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! is_array( $data ) || empty( $data['assets'] ) ) {
		return array();
	}
	$base = defined( 'HRI_SITE_MEDIA_BASE' ) ? (string) HRI_SITE_MEDIA_BASE : (string) ( $data['base'] ?? '' );
	$list = array();
	foreach ( $data['assets'] as $asset ) {
		if ( empty( $asset['file'] ) || '' === $base ) {
			continue;
		}
		$copy          = rtrim( $base, '/' ) . '/' . $asset['file'];
		$list[ $copy ] = array( (string) ( $asset['src'] ?? '' ), $copy, (string) ( $asset['alt'] ?? '' ) );
	}
	return $list;
}

/**
 * Download a slice of the list into the media library. The original is tried first (full quality), the
 * repository copy when the old site does not answer.
 *
 * @return array{done:int,failed:string[]} Images handled in this slice and the copy URLs that failed.
 */
function hri_site_media_import( int $offset, int $limit ): array {
	$list   = hri_site_media_list();
	$slice  = array_slice( $list, $offset, $limit, true );
	$failed = array();
	foreach ( $slice as $copy => $info ) {
		$id = $info[0] ? hri_blog_import_media( $info[0], 0, $info[2], 40 ) : 0;
		if ( ! $id ) {
			$id = hri_blog_import_media( $info[1], 0, $info[2], 40 );
		}
		if ( ! $id ) {
			$failed[] = $copy;
		}
	}
	return array(
		'done'   => count( $slice ),
		'failed' => $failed,
	);
}

/**
 * Attachment ID of an imported image (by the URL it was downloaded from), 0 when it is not there.
 */
function hri_site_media_attachment( string $copy, array $info ): int {
	foreach ( array_filter( array( $info[0], $info[1] ) ) as $url ) {
		$found = get_posts(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'numberposts' => 1,
				'fields'      => 'ids',
				'meta_key'    => '_hri_source_url', // phpcs:ignore WordPress.DB.SlowDBQuery -- one-off import.
				'meta_value'  => $url, // phpcs:ignore WordPress.DB.SlowDBQuery
				'lang'        => '',
			)
		);
		if ( $found ) {
			return (int) $found[0];
		}
	}
	return 0;
}

/**
 * Replace the copy URLs in page content with the library images, and turn the remote cover meta of
 * courses / trainers into featured images. Returns what changed.
 *
 * @return array{posts:int,covers:int,missing:int}
 */
function hri_site_media_apply(): array {
	global $wpdb;
	$map     = array(); // copy URL => attachment ID.
	$missing = 0;
	foreach ( hri_site_media_list() as $copy => $info ) {
		$id = hri_site_media_attachment( $copy, $info );
		if ( $id && wp_get_attachment_url( $id ) ) {
			$map[ $copy ] = $id;
		} else {
			++$missing;
		}
	}
	$out = array(
		'posts'   => 0,
		'covers'  => 0,
		'missing' => $missing,
	);
	if ( ! $map ) {
		return $out;
	}

	// Featured images of courses, trainers and their translations.
	$posts = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_hri_thumbnail_url'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	foreach ( $posts as $row ) {
		if ( ! isset( $map[ $row->meta_value ] ) || get_post_thumbnail_id( (int) $row->post_id ) ) {
			continue;
		}
		set_post_thumbnail( (int) $row->post_id, $map[ $row->meta_value ] );
		foreach ( array( '_hri_thumbnail_url', '_hri_thumbnail_alt', '_hri_thumbnail_w', '_hri_thumbnail_h' ) as $key ) {
			delete_post_meta( (int) $row->post_id, $key );
		}
		++$out['covers'];
	}

	// Images inside page content (pages, patterns, templates in any language).
	$first = (string) array_key_first( $map );
	$like  = '%' . $wpdb->esc_like( substr( $first, 0, (int) strrpos( $first, '/' ) + 1 ) ) . '%';
	$ids   = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_status <> 'trash' AND post_type <> 'revision' AND post_content LIKE %s", $like ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	foreach ( $ids as $post_id ) {
		$content = (string) get_post_field( 'post_content', (int) $post_id, 'raw' );
		$new     = hri_site_media_replace( $content, $map );
		if ( $new !== $content ) {
			$modified = get_post_field( 'post_modified', (int) $post_id, 'raw' );
			$gmt      = get_post_field( 'post_modified_gmt', (int) $post_id, 'raw' );
			wp_update_post( wp_slash( array( 'ID' => (int) $post_id, 'post_content' => $new, 'edit_date' => true, 'post_modified' => $modified, 'post_modified_gmt' => $gmt ) ) );
			++$out['posts'];
		}
	}
	return $out;
}

/**
 * Swap the copy URLs of one content string. Core image blocks also get the attachment ID (editor
 * "Replace" works, and WordPress adds srcset); every other place gets the URL only.
 *
 * @param array<string,int> $map Copy URL => attachment ID.
 */
function hri_site_media_replace( string $content, array $map ): string {
	$content = (string) preg_replace_callback(
		'#<!-- wp:image (\{[^}]*\}) -->(\s*)<figure class="wp-block-image([^"]*)"><img src="([^"]+)" alt="([^"]*)"/>#',
		static function ( $m ) use ( $map ) {
			$url = html_entity_decode( $m[4] );
			if ( ! isset( $map[ $url ] ) || false !== strpos( $m[1], '"id"' ) ) {
				return $m[0];
			}
			$id    = $map[ $url ];
			$attrs = json_decode( $m[1], true );
			$attrs = is_array( $attrs ) ? array_merge( array( 'id' => $id ), $attrs ) : array( 'id' => $id );
			$size  = isset( $attrs['sizeSlug'] ) ? (string) $attrs['sizeSlug'] : 'full';
			$src   = wp_get_attachment_image_url( $id, $size );
			return '<!-- wp:image ' . wp_json_encode( $attrs ) . ' -->' . $m[2] . '<figure class="wp-block-image' . $m[3] . '"><img src="' . esc_url( $src ? $src : (string) wp_get_attachment_url( $id ) ) . '" alt="' . $m[5] . '" class="wp-image-' . $id . '"/>';
		},
		$content
	);
	foreach ( $map as $copy => $id ) {
		if ( false !== strpos( $content, $copy ) ) {
			$content = str_replace( $copy, (string) wp_get_attachment_url( $id ), $content );
		}
	}
	return $content;
}
