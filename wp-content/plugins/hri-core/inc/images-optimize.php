<?php
/**
 * Convert images that are already in the media library (JPG/PNG -> WebP/AVIF, see images.php).
 *
 * - WP-CLI:  wp hri images optimize [--dry-run] [--limit=<n>] [--ids=<1,2,3>]
 * - wp-admin: Tools -> ปรับรูปให้เล็ก (batches of 10, the page reloads itself)
 *
 * For each image: the file is converted and the JPG/PNG deleted, old sub-sizes and the pre-scaled original are
 * deleted, the sub-sizes are regenerated, and every URL of the old files is replaced in post content/excerpts.
 * Run it on staging after importing images (blog importer "download" mode, sideloaded media).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * IDs of JPG/PNG attachments after $after_id, ascending.
 *
 * @return int[]
 */
function hri_image_optimize_ids( int $after_id = 0, int $limit = 0 ): array {
	global $wpdb;
	// JPG/PNG files, plus images WordPress already scaled itself but whose big original it still keeps.
	$sql = $wpdb->prepare(
		"SELECT p.ID FROM {$wpdb->posts} p
		LEFT JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_wp_attachment_metadata'
		WHERE p.post_type = 'attachment' AND p.ID > %d
		AND ( p.post_mime_type IN ('image/jpeg','image/png') OR m.meta_value LIKE %s )
		ORDER BY p.ID ASC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$after_id,
		'%' . $wpdb->esc_like( '"original_image"' ) . '%'
	);
	if ( $limit > 0 ) {
		$sql .= $wpdb->prepare( ' LIMIT %d', $limit );
	}
	return array_map( 'intval', $wpdb->get_col( $sql ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
}

/**
 * Convert one attachment.
 *
 * @return array{status:string,before:int,after:int,reason:string,replaced:int} status: converted|would-convert|skipped|failed
 */
function hri_image_optimize_attachment( int $id, bool $dry = false ): array {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$out  = array( 'status' => 'skipped', 'before' => 0, 'after' => 0, 'reason' => '', 'replaced' => 0 );
	$file = get_attached_file( $id );
	if ( ! $file || ! is_file( $file ) ) {
		$out['status'] = 'failed';
		$out['reason'] = 'file missing';
		return $out;
	}
	$meta = wp_get_attachment_metadata( $id );
	$meta = is_array( $meta ) ? $meta : array();
	$dir  = dirname( $file );
	$url  = (string) wp_get_attachment_url( $id );
	$base = trailingslashit( dirname( $url ) );

	// Everything the old image was reachable at, so content can be re-pointed afterwards.
	$old_files = array( 'full' => basename( $file ) );
	foreach ( (array) ( $meta['sizes'] ?? array() ) as $key => $size ) {
		if ( ! empty( $size['file'] ) ) {
			$old_files[ $key ] = $size['file'];
		}
	}
	$original = ! empty( $meta['original_image'] ) ? (string) $meta['original_image'] : '';
	$before   = (int) filesize( $file );

	// Already WebP/AVIF (WordPress scaled it itself) but the big JPG/PNG original is still kept: just drop it.
	$file_type = (string) wp_check_filetype( $file )['type']; // The real file: WordPress keeps the uploaded type on the attachment even when its scaled copy is WebP.
	if ( ! in_array( $file_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		if ( '' === $original || ! is_file( "$dir/$original" ) ) {
			$out['reason'] = 'nothing to do';
			return $out;
		}
		$out['before'] = (int) filesize( "$dir/$original" );
		$out['reason'] = 'removed pre-scaled original';
		if ( $dry ) {
			$out['status'] = 'would-convert';
			return $out;
		}
		wp_delete_file( "$dir/$original" );
		unset( $meta['original_image'] );
		wp_update_attachment_metadata( $id, $meta );
		if ( '' !== $file_type && get_post_mime_type( $id ) !== $file_type ) {
			wp_update_post( array( 'ID' => $id, 'post_mime_type' => $file_type ) );
		}
		$out['replaced'] = hri_image_replace_urls( array( $base . $original => $url ), pathinfo( $original, PATHINFO_FILENAME ) );
		$out['status']   = 'converted';
		return $out;
	}

	$converted = hri_image_convert_file( $file, 0, $dry );
	if ( ! $converted ) {
		$out['before'] = $before;
		$out['reason'] = '' === hri_image_target_mime() ? 'server cannot write WebP/AVIF' : 'not smaller or unreadable';
		return $out;
	}
	$out['before'] = $converted['before'];
	$out['after']  = $converted['after'];
	if ( $dry ) {
		$out['status'] = 'would-convert';
		return $out;
	}

	// Old derivatives: sub-sizes, backups and the pre-scaled original.
	foreach ( $old_files as $key => $name ) {
		if ( 'full' !== $key && is_file( "$dir/$name" ) ) {
			wp_delete_file( "$dir/$name" );
		}
	}
	foreach ( (array) get_post_meta( $id, '_wp_attachment_backup_sizes', true ) as $backup ) {
		if ( ! empty( $backup['file'] ) && is_file( "$dir/{$backup['file']}" ) ) {
			wp_delete_file( "$dir/{$backup['file']}" );
		}
	}
	delete_post_meta( $id, '_wp_attachment_backup_sizes' );
	if ( '' !== $original && is_file( "$dir/$original" ) ) {
		wp_delete_file( "$dir/$original" );
	}

	update_attached_file( $id, $converted['file'] );
	wp_update_post( array( 'ID' => $id, 'post_mime_type' => $converted['type'] ) );
	$new_meta = wp_generate_attachment_metadata( $id, $converted['file'] );
	if ( is_array( $new_meta ) ) {
		wp_update_attachment_metadata( $id, $new_meta );
	}
	$new_meta  = is_array( $new_meta ) ? $new_meta : array();
	$new_full  = $base . basename( $converted['file'] );
	$new_sizes = (array) ( $new_meta['sizes'] ?? array() );

	// old URL => new URL (same size name when it still exists, otherwise the full image).
	$map = array();
	foreach ( $old_files as $key => $name ) {
		$map[ $base . $name ] = ( 'full' !== $key && ! empty( $new_sizes[ $key ]['file'] ) ) ? $base . $new_sizes[ $key ]['file'] : $new_full;
	}
	if ( '' !== $original ) {
		$map[ $base . $original ] = $new_full;
	}
	$out['replaced'] = hri_image_replace_urls( $map, pathinfo( $old_files['full'], PATHINFO_FILENAME ) );
	$out['status']   = 'converted';
	return $out;
}

/**
 * Replace URLs in post content and excerpts. Returns the number of posts changed.
 *
 * @param array<string,string> $map  Old URL => new URL.
 * @param string               $stem File name without extension, to narrow the search.
 */
function hri_image_replace_urls( array $map, string $stem ): int {
	global $wpdb;
	// Longest first, so "name-300x200.jpg" is never shortened by "name.jpg".
	uksort( $map, static fn( $a, $b ) => strlen( $b ) <=> strlen( $a ) );
	$search  = array();
	$replace = array();
	foreach ( $map as $from => $to ) {
		$search[]  = $from;
		$replace[] = $to;
		$search[]  = str_replace( '/', '\\/', $from ); // JSON-escaped, as inside block comments.
		$replace[] = str_replace( '/', '\\/', $to );
	}
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ID, post_content, post_excerpt FROM {$wpdb->posts} WHERE post_content LIKE %s OR post_excerpt LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			'%' . $wpdb->esc_like( $stem ) . '%',
			'%' . $wpdb->esc_like( $stem ) . '%'
		)
	);
	$changed = 0;
	foreach ( $rows as $row ) {
		$content = str_replace( $search, $replace, $row->post_content );
		$excerpt = str_replace( $search, $replace, $row->post_excerpt );
		if ( $content !== $row->post_content || $excerpt !== $row->post_excerpt ) {
			$wpdb->update( $wpdb->posts, array( 'post_content' => $content, 'post_excerpt' => $excerpt ), array( 'ID' => (int) $row->ID ) );
			clean_post_cache( (int) $row->ID );
			++$changed;
		}
	}
	return $changed;
}

/* ------------------------------------------------------------------------- WP-CLI */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'hri images optimize',
		/**
		 * Convert JPG/PNG in the media library to WebP/AVIF and re-point content.
		 *
		 * ## OPTIONS
		 *
		 * [--dry-run]
		 * : Only report what would change.
		 *
		 * [--limit=<n>]
		 * : Stop after n images.
		 *
		 * [--ids=<ids>]
		 * : Comma-separated attachment IDs instead of the whole library.
		 */
		static function ( $args, $assoc ) {
			if ( '' === hri_image_target_mime() ) {
				WP_CLI::error( 'This server cannot write WebP or AVIF (needs GD with WebP, or Imagick).' );
			}
			$dry   = ! empty( $assoc['dry-run'] );
			$limit = isset( $assoc['limit'] ) ? absint( $assoc['limit'] ) : 0;
			$ids   = isset( $assoc['ids'] ) ? array_map( 'absint', explode( ',', (string) $assoc['ids'] ) ) : hri_image_optimize_ids();
			if ( $limit > 0 ) {
				$ids = array_slice( $ids, 0, $limit );
			}
			$totals = array( 'converted' => 0, 'would-convert' => 0, 'skipped' => 0, 'failed' => 0, 'before' => 0, 'after' => 0, 'replaced' => 0 );
			foreach ( $ids as $n => $id ) {
				$r = hri_image_optimize_attachment( (int) $id, $dry );
				++$totals[ $r['status'] ];
				if ( 'converted' === $r['status'] || 'would-convert' === $r['status'] ) {
					$totals['before']  += $r['before'];
					$totals['after']   += $r['after'];
					$totals['replaced'] += $r['replaced'];
				}
				WP_CLI::log( sprintf( '[%d/%d] #%d %s %s', $n + 1, count( $ids ), $id, $r['status'], ( $r['after'] ? size_format( $r['before'] ) . ' -> ' . size_format( $r['after'] ) : size_format( $r['before'] ) ) . ( $r['reason'] ? ' (' . $r['reason'] . ')' : '' ) ) );
			}
			WP_CLI::success(
				sprintf(
					'%s: %d, skipped: %d, failed: %d | %s -> %s | posts updated: %d',
					$dry ? 'would convert' : 'converted',
					$dry ? $totals['would-convert'] : $totals['converted'],
					$totals['skipped'],
					$totals['failed'],
					size_format( $totals['before'] ),
					size_format( $totals['after'] ),
					$totals['replaced']
				)
			);
		}
	);
}

/* ------------------------------------------------------------------------- wp-admin: Tools */

add_action(
	'admin_menu',
	static function () {
		add_management_page(
			__( 'ปรับรูปในคลังสื่อให้เล็ก', 'hri-core' ),
			__( 'ปรับรูปให้เล็ก', 'hri-core' ),
			'manage_options',
			'hri-images-optimize',
			'hri_images_optimize_page'
		);
	}
);

function hri_images_optimize_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$message = '';
	$next    = null;
	$target  = hri_image_target_mime();

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- checked by check_admin_referer() below.
	if ( '' !== $target && isset( $_POST['hri_images_action'] ) ) {
		check_admin_referer( 'hri_images_optimize' );
		$cursor = isset( $_POST['cursor'] ) ? absint( $_POST['cursor'] ) : 0;
		$total  = isset( $_POST['total'] ) ? absint( $_POST['total'] ) : 0;
		$done   = isset( $_POST['done'] ) ? absint( $_POST['done'] ) : 0;
		$saved  = isset( $_POST['saved'] ) ? absint( $_POST['saved'] ) : 0;
		$ids    = hri_image_optimize_ids( $cursor, 10 );
		foreach ( $ids as $id ) {
			$r = hri_image_optimize_attachment( $id );
			if ( 'converted' === $r['status'] ) {
				$saved += max( 0, $r['before'] - $r['after'] );
			}
			$cursor = $id;
			++$done;
		}
		if ( $ids ) {
			$next = array( 'cursor' => $cursor, 'total' => $total, 'done' => $done, 'saved' => $saved );
			/* translators: 1: images done, 2: total, 3: size saved */
			$message = sprintf( __( 'ตรวจแล้ว %1$d จาก %2$d รูป ประหยัดไปแล้ว %3$s กำลังทำต่อ…', 'hri-core' ), $done, $total, size_format( $saved ) );
		} else {
			/* translators: 1: images checked, 2: size saved */
			$message = sprintf( __( 'เสร็จแล้ว ตรวจ %1$d รูป ประหยัดพื้นที่ %2$s', 'hri-core' ), $done, size_format( $saved ) );
		}
	}
	// phpcs:enable

	echo '<div class="wrap"><h1>' . esc_html__( 'ปรับรูปในคลังสื่อให้เล็ก', 'hri-core' ) . '</h1>';
	if ( '' === $target ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'เซิร์ฟเวอร์นี้เขียนไฟล์ WebP/AVIF ไม่ได้ (ต้องมี GD ที่รองรับ WebP หรือ Imagick) ติดต่อโฮสต์', 'hri-core' ) . '</p></div></div>';
		return;
	}
	if ( $message ) {
		echo '<div class="notice notice-info"><p>' . esc_html( $message ) . '</p></div>';
	}
	if ( $next ) {
		echo '<form method="post" id="hri-images-next">';
		wp_nonce_field( 'hri_images_optimize' );
		echo '<input type="hidden" name="hri_images_action" value="batch">';
		foreach ( $next as $name => $value ) {
			echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $value ) . '">';
		}
		echo '<p>' . esc_html__( 'อย่าปิดหน้านี้จนกว่าจะเสร็จ', 'hri-core' ) . '</p>';
		// Button not named "submit": that name would hide form.submit().
		submit_button( __( 'ทำต่อ', 'hri-core' ), 'primary', 'hri_continue', false );
		echo '</form><script>document.getElementById("hri-images-next").submit();</script></div>';
		return;
	}
	$count = count( hri_image_optimize_ids() );
	/* translators: 1: number of images, 2: WEBP or AVIF */
	echo '<p>' . esc_html( sprintf( __( 'ในคลังสื่อมีรูป JPG/PNG %1$d รูป ปุ่มด้านล่างจะแปลงเป็น %2$s ลบไฟล์เดิมและขนาดย่อยเก่า สร้างขนาดย่อยใหม่ และแก้ลิงก์รูปในบทความ/หน้าให้เอง', 'hri-core' ), $count, 'image/avif' === $target ? 'AVIF' : 'WebP' ) ) . '</p>';
	echo '<p><strong>' . esc_html__( 'ลบไฟล์ต้นฉบับถาวร กู้คืนไม่ได้ — สำรองเว็บก่อนกด', 'hri-core' ) . '</strong></p>';
	echo '<form method="post">';
	wp_nonce_field( 'hri_images_optimize' );
	echo '<input type="hidden" name="hri_images_action" value="batch"><input type="hidden" name="cursor" value="0"><input type="hidden" name="total" value="' . esc_attr( (string) $count ) . '"><input type="hidden" name="done" value="0"><input type="hidden" name="saved" value="0">';
	submit_button( __( 'เริ่มปรับรูป', 'hri-core' ), 'primary', 'hri_start', true, $count ? array() : array( 'disabled' => 'disabled' ) );
	echo '</form></div>';
}
