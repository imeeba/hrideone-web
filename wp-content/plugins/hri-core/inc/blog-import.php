<?php
/**
 * Blog import: the articles of the old hrideone.com (TH / EN / JA) from a JSON file made by
 * scripts/wxr-to-blog.py (playground/data/blog-posts.json).
 *
 * - one post per language, linked as Polylang translations, same slug as the old site
 *   (Polylang free does not allow the same slug in two languages: a clash gets "-en" / "-ja" and
 *   the old slug is kept in _wp_old_slug, so WordPress redirects the old URL)
 * - categories Business / Sales / Coaching in every language (slugs business, business-en, business-ja ...)
 * - idempotent: run it again to update (posts are found by their old-site ID, _hri_source_id)
 * - images: "remote" keeps the hrideone.com URLs (preview sites; cover via _hri_thumbnail_url, see blog.php)
 *   or "download" copies cover and content images into the media library (staging / real site,
 *   run it while the old site is still online)
 *
 * Run with WP-CLI (`wp hri import-blog <file> [--images=download]`) or Tools → นำเข้าบทความ
 * (batches of a few articles per request, for hosts without WP-CLI).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read and check an import file.
 *
 * @return array|WP_Error The "posts" list.
 */
function hri_blog_import_read( string $file ) {
	if ( ! is_readable( $file ) ) {
		return new WP_Error( 'hri_import_file', __( 'อ่านไฟล์นำเข้าไม่ได้', 'hri-core' ) );
	}
	$raw = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- local file.
	if ( str_starts_with( $raw, "\x1f\x8b" ) && function_exists( 'gzdecode' ) ) {
		$raw = (string) gzdecode( $raw ); // blog-posts.json.gz (small enough for the upload limit).
	}
	$data = json_decode( $raw, true );
	if ( ! is_array( $data ) || empty( $data['posts'] ) || ! is_array( $data['posts'] ) ) {
		return new WP_Error( 'hri_import_format', __( 'ไฟล์นำเข้าไม่ใช่รูปแบบที่ถูกต้อง (ต้องสร้างจาก scripts/wxr-to-blog.py)', 'hri-core' ) );
	}
	return array_values( $data['posts'] );
}

/**
 * Languages the import can write: Thai always, EN / JA when Polylang has them.
 *
 * @return string[]
 */
function hri_blog_import_languages(): array {
	if ( ! function_exists( 'pll_languages_list' ) ) {
		return array( 'th' );
	}
	return array_values( array_intersect( array( 'th', 'en', 'ja' ), (array) pll_languages_list() ) );
}

/**
 * Blog categories in every language, created when missing and linked as translations.
 *
 * @param string[] $slugs Base slugs (business, sales, coaching).
 * @return array<string, array<string, int>> [ lang ][ base slug ] => term ID.
 */
function hri_blog_import_terms( array $slugs ): array {
	$names = array(
		'business' => 'Business',
		'sales'    => 'Sales',
		'coaching' => 'Coaching',
	);
	$langs = hri_blog_import_languages();
	$terms = array();
	foreach ( $slugs as $base ) {
		$group = array();
		foreach ( $langs as $lang ) {
			$slug = 'th' === $lang ? $base : "{$base}-{$lang}";
			$term = get_term_by( 'slug', $slug, 'category' );
			if ( ! $term ) {
				$made = wp_insert_term( $names[ $base ] ?? ucfirst( $base ), 'category', array( 'slug' => $slug ) );
				if ( is_wp_error( $made ) ) {
					continue;
				}
				$term = get_term( (int) $made['term_id'], 'category' );
			}
			if ( function_exists( 'pll_set_term_language' ) ) {
				pll_set_term_language( (int) $term->term_id, $lang );
			}
			$terms[ $lang ][ $base ] = (int) $term->term_id;
			$group[ $lang ]          = (int) $term->term_id;
		}
		if ( count( $group ) > 1 && function_exists( 'pll_save_term_translations' ) ) {
			pll_save_term_translations( $group );
		}
	}
	return $terms;
}

/**
 * The post made from one old-site post: by old ID, else the existing translation of the Thai post
 * (sample posts made by the seed), else by slug in that language.
 */
function hri_blog_import_find( array $item, string $lang, int $th_id ): int {
	$found = get_posts(
		array(
			'post_type'        => 'post',
			'post_status'      => 'any',
			'numberposts'      => 1,
			'fields'           => 'ids',
			'meta_key'         => '_hri_source_id', // phpcs:ignore WordPress.DB.SlowDBQuery -- one-off import.
			'meta_value'       => (string) $item['old_id'], // phpcs:ignore WordPress.DB.SlowDBQuery
			'lang'             => '',
			'suppress_filters' => false,
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}
	if ( $th_id && 'th' !== $lang && function_exists( 'pll_get_post' ) ) {
		$tr = (int) pll_get_post( $th_id, $lang );
		if ( $tr && ! get_post_meta( $tr, '_hri_source_id', true ) ) {
			return $tr;
		}
	}
	// Polylang ignores "lang" in a query by slug: check the language here.
	$found = get_posts(
		array(
			'post_type'   => 'post',
			'post_status' => 'any',
			'numberposts' => -1,
			'fields'      => 'ids',
			'name'        => $item['slug'],
			'lang'        => '',
		)
	);
	foreach ( $found as $id ) {
		$id_lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $id ) : 'th';
		if ( ( $id_lang === $lang || ! $id_lang ) && ! get_post_meta( $id, '_hri_source_id', true ) ) {
			return (int) $id;
		}
	}
	return 0;
}

/**
 * An image of the old site in the media library (downloaded once, found again by its URL).
 *
 * @return int Attachment ID, 0 on failure.
 */
function hri_blog_import_media( string $url, int $post_id, string $alt = '' ): int {
	if ( ! $url ) {
		return 0;
	}
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
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = download_url( $url, 60 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}
	$name = rawurldecode( wp_basename( (string) wp_parse_url( $url, PHP_URL_PATH ) ) );
	$id   = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), $post_id );
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}
	update_post_meta( $id, '_hri_source_url', $url );
	if ( '' !== $alt ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	}
	return (int) $id;
}

/**
 * Content images from the old site → media library images (block attributes, class and URL updated).
 */
function hri_blog_import_content_images( string $content, int $post_id ): string {
	return (string) preg_replace_callback(
		'#<!-- wp:image -->\n<figure class="wp-block-image"><img src="([^"]+)" alt="([^"]*)"/>(.*?)</figure>\n<!-- /wp:image -->#s',
		static function ( $m ) use ( $post_id ) {
			$url = html_entity_decode( $m[1] );
			if ( ! str_contains( $url, 'hrideone.com/' ) ) {
				return $m[0];
			}
			$id  = hri_blog_import_media( $url, $post_id, html_entity_decode( $m[2] ) );
			$src = $id ? wp_get_attachment_image_url( $id, 'large' ) : '';
			if ( ! $src ) {
				return $m[0];
			}
			return '<!-- wp:image {"id":' . $id . ',"sizeSlug":"large","linkDestination":"none"} -->' . "\n"
				. '<figure class="wp-block-image size-large"><img src="' . esc_url( $src ) . '" alt="' . $m[2] . '" class="wp-image-' . $id . '"/>' . $m[3] . "</figure>\n<!-- /wp:image -->";
		},
		$content
	);
}

/**
 * Import articles.
 *
 * @param array $posts  The "posts" list of the import file.
 * @param array $args   images: remote|download · offset, limit: a slice of the list (batches).
 * @return array Counts: created, updated, skipped, images (downloaded / reused), total.
 */
function hri_blog_import( array $posts, array $args = array() ): array {
	$args   = wp_parse_args(
		$args,
		array(
			'images' => 'remote',
			'offset' => 0,
			'limit'  => 0,
		)
	);
	$langs  = hri_blog_import_languages();
	$stats  = array(
		'created' => 0,
		'updated' => 0,
		'images'  => 0,
		'total'   => count( $posts ),
		'renamed' => array(),
	);
	$slice  = $args['limit'] ? array_slice( $posts, (int) $args['offset'], (int) $args['limit'] ) : $posts;
	$bases  = array_values( array_unique( array_merge( array( 'business', 'sales', 'coaching' ), wp_list_pluck( $posts, 'category' ) ) ) );
	$terms  = hri_blog_import_terms( $bases );
	$author = get_current_user_id() ? get_current_user_id() : 1;

	wp_defer_term_counting( true );
	foreach ( $slice as $group ) {
		$ids = array();
		foreach ( $langs as $lang ) {
			$item = $group['translations'][ $lang ] ?? null;
			if ( ! $item ) {
				continue;
			}
			$existing = hri_blog_import_find( $item, $lang, $ids['th'] ?? 0 );
			// Slug used by a post of another language (Polylang free): "<slug>-<lang>".
			$taken    = get_posts(
				array(
					'post_type'    => 'post',
					'post_status'  => 'any',
					'numberposts'  => 1,
					'fields'       => 'ids',
					'name'         => $item['slug'],
					'post__not_in' => $existing ? array( $existing ) : array(),
					'lang'         => '',
				)
			);
			$postarr  = array(
				'ID'           => $existing,
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_author'  => $existing ? (int) get_post_field( 'post_author', $existing ) : $author,
				'post_name'    => $taken && 'th' !== $lang ? $item['slug'] . '-' . $lang : $item['slug'],
				'post_title'   => $item['title'],
				'post_excerpt' => $item['excerpt'],
				'post_content' => $item['content'],
				'post_date'    => $group['date'],
				'meta_input'   => array( '_hri_source_id' => (string) $item['old_id'] ),
			);
			$post_id  = wp_insert_post( wp_slash( $postarr ), true );
			if ( is_wp_error( $post_id ) ) {
				continue;
			}
			++$stats[ $existing ? 'updated' : 'created' ];
			if ( function_exists( 'pll_set_post_language' ) ) {
				pll_set_post_language( $post_id, $lang );
			}
			$ids[ $lang ] = $post_id;

			// The same slug is already used by another language: keep the old one as a redirect.
			$slug = get_post_field( 'post_name', $post_id );
			$old  = array_values( array_diff( (array) ( $item['old_slugs'] ?? array() ), array( $slug ) ) );
			if ( $slug !== $item['slug'] ) {
				$old[]                       = $item['slug'];
				$stats['renamed'][ $item['slug'] ] = $slug;
			}
			delete_post_meta( $post_id, '_wp_old_slug' );
			foreach ( array_unique( $old ) as $old_slug ) {
				add_post_meta( $post_id, '_wp_old_slug', $old_slug );
			}

			$term = $terms[ $lang ][ $group['category'] ] ?? 0;
			if ( $term ) {
				wp_set_object_terms( $post_id, array( $term ), 'category' );
			}

			if ( 'download' === $args['images'] ) {
				$thumb = hri_blog_import_media( (string) $item['thumbnail'], $post_id, (string) $item['thumbnail_alt'] );
				if ( $thumb ) {
					set_post_thumbnail( $post_id, $thumb );
					delete_post_meta( $post_id, '_hri_thumbnail_url' );
				}
				$content = hri_blog_import_content_images( $item['content'], $post_id );
				if ( $content !== $item['content'] ) {
					wp_update_post( wp_slash( array( 'ID' => $post_id, 'post_content' => $content ) ) );
				}
			} else {
				delete_post_thumbnail( $post_id );
				if ( $item['thumbnail'] ) {
					update_post_meta( $post_id, '_hri_thumbnail_url', esc_url_raw( $item['thumbnail'] ) );
					update_post_meta( $post_id, '_hri_thumbnail_alt', $item['thumbnail_alt'] );
				} else {
					delete_post_meta( $post_id, '_hri_thumbnail_url' );
				}
			}
		}
		if ( count( $ids ) > 1 && function_exists( 'pll_save_post_translations' ) ) {
			pll_save_post_translations( $ids );
		}
	}
	wp_defer_term_counting( false );
	$stats['images'] = 'download' === $args['images'] ? count( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => '_hri_source_url', 'lang' => '' ) ) ) : 0; // phpcs:ignore WordPress.DB.SlowDBQuery
	return $stats;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Import the old site's articles (TH / EN / JA) from blog-posts.json.
	 *
	 * ## OPTIONS
	 *
	 * <file>
	 * : JSON made by scripts/wxr-to-blog.py
	 *
	 * [--images=<mode>]
	 * : remote (keep hrideone.com image URLs) or download (copy into the media library)
	 * ---
	 * default: remote
	 * ---
	 *
	 * ## EXAMPLES
	 *     wp hri import-blog playground/data/blog-posts.json --images=download
	 */
	WP_CLI::add_command(
		'hri import-blog',
		static function ( $positional, $assoc ) {
			$posts = hri_blog_import_read( (string) $positional[0] );
			if ( is_wp_error( $posts ) ) {
				WP_CLI::error( $posts->get_error_message() );
			}
			$mode = ( $assoc['images'] ?? 'remote' ) === 'download' ? 'download' : 'remote';
			$done = 0;
			foreach ( array_chunk( $posts, 10 ) as $i => $chunk ) {
				$stats = hri_blog_import( $posts, array( 'images' => $mode, 'offset' => $i * 10, 'limit' => 10 ) );
				$done += count( $chunk );
				WP_CLI::log( sprintf( '%d / %d articles (created %d, updated %d)', $done, count( $posts ), $stats['created'], $stats['updated'] ) );
				foreach ( $stats['renamed'] as $from => $to ) {
					WP_CLI::log( "  slug {$from} is used by another language: saved as {$to} (old URL redirects)" );
				}
			}
			WP_CLI::success( 'Blog imported.' );
		}
	);
}

/* -------------------------------------------------------------------------
 * Tools → นำเข้าบทความ (hosts without WP-CLI): upload the JSON, then batches of 5 articles.
 * ---------------------------------------------------------------------- */

/**
 * Where the uploaded import file is kept between batches.
 */
function hri_blog_import_file(): string {
	$dir = wp_upload_dir( null, false );
	return trailingslashit( $dir['basedir'] ) . 'hri-import/blog-posts.json';
}

add_action(
	'admin_menu',
	static function () {
		add_management_page(
			__( 'นำเข้าบทความจากเว็บเดิม', 'hri-core' ),
			__( 'นำเข้าบทความ', 'hri-core' ),
			'manage_options',
			'hri-blog-import',
			'hri_blog_import_page'
		);
	}
);

/**
 * The import screen: upload form, then one batch per request (the page reloads itself).
 */
function hri_blog_import_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$file    = hri_blog_import_file();
	$message = '';
	$next    = null;

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- checked by check_admin_referer() below.
	if ( isset( $_POST['hri_import_action'] ) ) {
		check_admin_referer( 'hri_blog_import' );
		$action = sanitize_key( wp_unslash( $_POST['hri_import_action'] ) );
		$mode   = ( isset( $_POST['images'] ) && 'download' === $_POST['images'] ) ? 'download' : 'remote';
		if ( 'upload' === $action ) {
			$error = isset( $_FILES['hri_file']['error'] ) ? (int) $_FILES['hri_file']['error'] : UPLOAD_ERR_NO_FILE;
			wp_mkdir_p( dirname( $file ) );
			if ( UPLOAD_ERR_OK === $error && move_uploaded_file( $_FILES['hri_file']['tmp_name'], $file ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- moved, then validated as JSON.
				$posts = hri_blog_import_read( $file );
				if ( is_wp_error( $posts ) ) {
					wp_delete_file( $file );
					$message = $posts->get_error_message();
				} else {
					$next = array( 'offset' => 0, 'images' => $mode );
				}
			} elseif ( in_array( $error, array( UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE ), true ) ) {
				/* translators: %s: upload size limit, e.g. 2 MB */
				$message = sprintf( __( 'ไฟล์ใหญ่เกินขนาดอัปโหลดของโฮสต์ (%s) ให้ใช้ไฟล์ blog-posts.json.gz แทน', 'hri-core' ), size_format( wp_max_upload_size() ) );
			} else {
				$message = __( 'อัปโหลดไฟล์ไม่สำเร็จ', 'hri-core' );
			}
		} elseif ( 'batch' === $action ) {
			$offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
			$posts  = hri_blog_import_read( $file );
			if ( is_wp_error( $posts ) ) {
				$message = $posts->get_error_message();
			} else {
				hri_blog_import( $posts, array( 'images' => $mode, 'offset' => $offset, 'limit' => 5 ) );
				$offset += 5;
				if ( $offset < count( $posts ) ) {
					$next    = array( 'offset' => $offset, 'images' => $mode );
					/* translators: 1: articles done, 2: total */
					$message = sprintf( __( 'นำเข้าแล้ว %1$d จาก %2$d เรื่อง กำลังทำต่อ…', 'hri-core' ), $offset, count( $posts ) );
				} else {
					wp_delete_file( $file );
					/* translators: %d: number of articles (each in TH / EN / JA) */
					$message = sprintf( __( 'นำเข้าครบ %d เรื่องแล้ว (แต่ละเรื่องมี TH / EN / JA)', 'hri-core' ), count( $posts ) );
				}
			}
		}
	}
	// phpcs:enable

	echo '<div class="wrap"><h1>' . esc_html__( 'นำเข้าบทความจากเว็บเดิม', 'hri-core' ) . '</h1>';
	if ( $message ) {
		echo '<div class="notice notice-info"><p>' . esc_html( $message ) . '</p></div>';
	}
	if ( $next ) {
		echo '<form method="post" id="hri-import-next">';
		wp_nonce_field( 'hri_blog_import' );
		echo '<input type="hidden" name="hri_import_action" value="batch"><input type="hidden" name="offset" value="' . esc_attr( (string) $next['offset'] ) . '"><input type="hidden" name="images" value="' . esc_attr( $next['images'] ) . '">';
		echo '<p>' . esc_html__( 'อย่าปิดหน้านี้จนกว่าจะเสร็จ', 'hri-core' ) . '</p>';
		// Button not named "submit": that name would hide form.submit().
		submit_button( __( 'ทำต่อ', 'hri-core' ), 'primary', 'hri_continue', false );
		echo '</form><script>document.getElementById("hri-import-next").submit();</script></div>';
		return;
	}
	echo '<p>' . esc_html__( 'ใช้ไฟล์ blog-posts.json.gz (หรือ .json) ที่สร้างจากไฟล์ Export ของเว็บเดิม (scripts/wxr-to-blog.py) นำเข้าซ้ำได้ บทความเดิมจะถูกอัปเดต ไม่สร้างซ้ำ', 'hri-core' ) . '</p>';
	echo '<form method="post" enctype="multipart/form-data">';
	wp_nonce_field( 'hri_blog_import' );
	echo '<input type="hidden" name="hri_import_action" value="upload">';
	echo '<p><input type="file" name="hri_file" accept=".json,.gz,application/json,application/gzip" required></p>';
	echo '<fieldset><p><label><input type="radio" name="images" value="download" checked> ' . esc_html__( 'คัดลอกรูปเข้าคลังสื่อ (ต้องทำตอนเว็บเดิมยังเปิดอยู่)', 'hri-core' ) . '</label></p>';
	echo '<p><label><input type="radio" name="images" value="remote"> ' . esc_html__( 'ใช้รูปจากเว็บเดิม (สำหรับเว็บทดสอบ)', 'hri-core' ) . '</label></p></fieldset>';
	submit_button( __( 'เริ่มนำเข้า', 'hri-core' ) );
	echo '</form></div>';
}
