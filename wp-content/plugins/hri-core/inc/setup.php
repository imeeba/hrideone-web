<?php
/**
 * First-time site setup from wp-admin (for hosting without WP-CLI or SSH).
 *
 * Tools → ตั้งค่าเว็บเริ่มต้น runs the same steps as the Playground blueprint (languages, sample
 * pages and courses, translations, blog articles, search index, SEO) one step per request, so
 * no step runs into the host's time limit. Safe to run again: every step updates what exists.
 *
 * The data comes from `hri-core/setup/` (a copy of `playground/` made by
 * `scripts/package-staging.sh`; not part of the repository). Without that folder the screen
 * only explains how to get it.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Folder with the seed files, or '' when this build does not carry them.
 */
function hri_setup_dir(): string {
	$dir = HRI_CORE_DIR . 'setup/';
	return is_readable( $dir . 'seed.php' ) ? $dir : '';
}

/**
 * The steps: key => label.
 *
 * @return array<string,string>
 */
function hri_setup_steps(): array {
	return array(
		'languages' => __( 'ตั้งค่าภาษา TH / EN / JA', 'hri-core' ),
		'content'   => __( 'สร้างหน้า คอร์ส และข้อมูลตัวอย่าง', 'hri-core' ),
		'translate' => __( 'สร้างหน้าแปล EN / JA', 'hri-core' ),
		'images'    => __( 'บันทึกขนาดรูป', 'hri-core' ),
		'blog'      => __( 'นำเข้าบทความ 115 เรื่อง × 3 ภาษา', 'hri-core' ),
		'search'    => __( 'สร้างดัชนีค้นหา', 'hri-core' ),
		'seo'       => __( 'ตั้งค่า SEO และล้าง permalink', 'hri-core' ),
	);
}

add_action(
	'admin_menu',
	static function () {
		add_management_page(
			__( 'ตั้งค่าเว็บเริ่มต้น', 'hri-core' ),
			__( 'ตั้งค่าเว็บเริ่มต้น', 'hri-core' ),
			'manage_options',
			'hri-setup',
			'hri_setup_page'
		);
	}
);

/**
 * Run one step. Returns a message and, for batched steps, the next offset.
 *
 * @param string $step   Step key.
 * @param int    $offset Where a batched step continues.
 * @return array{message:string,next?:int}|WP_Error
 */
function hri_setup_run( string $step, int $offset = 0 ) {
	$dir = hri_setup_dir();
	if ( ! $dir ) {
		return new WP_Error( 'hri_setup_missing', __( 'ไม่พบโฟลเดอร์ setup ในปลั๊กอิน (ต้องใช้ hri-core.zip ที่สร้างจาก scripts/package-staging.sh)', 'hri-core' ) );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- some hosts forbid it.
	}
	wp_raise_memory_limit( 'admin' );

	switch ( $step ) {
		case 'languages':
			if ( ! function_exists( 'PLL' ) ) {
				return new WP_Error( 'hri_setup_pll', __( 'ยังไม่ได้เปิดปลั๊กอิน Polylang', 'hri-core' ) );
			}
			$log = hri_polylang_setup();
			return is_wp_error( $log ) ? $log : array( 'message' => implode( ' · ', $log ) );

		case 'content':
			if ( ! hri_polylang_is_set_up() ) {
				return new WP_Error( 'hri_setup_order', __( 'ทำขั้นตอน "ตั้งค่าภาษา" ก่อน', 'hri-core' ) );
			}
			require $dir . 'seed.php';
			foreach ( get_posts(
				array(
					'post_type'     => array( 'post', 'page' ),
					'post_status'   => 'any',
					'numberposts'   => -1,
					'post_name__in' => array( 'hello-world', 'sample-page' ),
					'lang'          => '',
				)
			) as $post ) {
				wp_delete_post( $post->ID, true );
			}
			flush_rewrite_rules();
			return array( 'message' => __( 'สร้างหน้าและคอร์สแล้ว', 'hri-core' ) );

		case 'translate':
			require $dir . 'seed-languages.php';
			return array( 'message' => __( 'สร้างหน้าแปลแล้ว', 'hri-core' ) );

		case 'images':
			$file = $dir . 'data/image-dims.json';
			$dims = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( ! is_array( $dims ) ) {
				return new WP_Error( 'hri_setup_dims', __( 'อ่านไฟล์ขนาดรูปไม่ได้', 'hri-core' ) );
			}
			update_option( 'hri_image_dims', $dims, false );
			return array( 'message' => __( 'บันทึกขนาดรูปแล้ว', 'hri-core' ) );

		case 'blog':
			$posts = hri_blog_import_read( $dir . 'data/blog-posts.json.gz' );
			if ( is_wp_error( $posts ) ) {
				return $posts;
			}
			hri_blog_import(
				$posts,
				array(
					'images' => 'remote',
					'offset' => $offset,
					'limit'  => 5,
				)
			);
			$offset += 5;
			if ( $offset < count( $posts ) ) {
				return array(
					/* translators: 1: articles done, 2: total */
					'message' => sprintf( __( 'นำเข้าแล้ว %1$d จาก %2$d เรื่อง', 'hri-core' ), $offset, count( $posts ) ),
					'next'    => $offset,
				);
			}
			/* translators: %d: number of articles */
			return array( 'message' => sprintf( __( 'นำเข้าครบ %d เรื่อง', 'hri-core' ), count( $posts ) ) );

		case 'search':
			hri_search_rebuild( 0, 0 );
			return array( 'message' => __( 'สร้างดัชนีค้นหาแล้ว', 'hri-core' ) );

		case 'seo':
			$lines = function_exists( 'hri_seo_setup' ) ? hri_seo_setup() : array();
			flush_rewrite_rules( false );
			return array( 'message' => $lines ? implode( ' · ', $lines ) : __( 'ล้าง permalink แล้ว', 'hri-core' ) );
	}
	return new WP_Error( 'hri_setup_step', __( 'ไม่รู้จักขั้นตอนนี้', 'hri-core' ) );
}

/**
 * The screen. Each request runs one step, then the page submits itself for the next one.
 */
function hri_setup_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$steps = hri_setup_steps();
	$keys  = array_keys( $steps );
	$log   = get_option( 'hri_setup_log', array() );
	$log   = is_array( $log ) ? $log : array();
	$error = '';
	$next  = null; // array( step, offset ) to run on the next request.

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- checked by check_admin_referer() below.
	if ( isset( $_POST['hri_setup_step'] ) ) {
		check_admin_referer( 'hri_setup' );
		$step   = sanitize_key( wp_unslash( $_POST['hri_setup_step'] ) );
		$offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
		if ( 'all' === $step ) {
			$log  = array();
			$step = $keys[0];
		}
		$result = hri_setup_run( $step, $offset );
		if ( is_wp_error( $result ) ) {
			$error = $result->get_error_message();
		} else {
			$log[ $step ] = $result['message'];
			if ( isset( $result['next'] ) ) {
				$next = array( $step, (int) $result['next'] );
			} elseif ( isset( $_POST['hri_setup_all'] ) ) {
				$pos  = array_search( $step, $keys, true );
				$next = isset( $keys[ $pos + 1 ] ) ? array( $keys[ $pos + 1 ], 0 ) : null;
			}
		}
		update_option( 'hri_setup_log', $log, false );
	}
	// phpcs:enable

	echo '<div class="wrap"><h1>' . esc_html__( 'ตั้งค่าเว็บเริ่มต้น', 'hri-core' ) . '</h1>';

	if ( ! hri_setup_dir() ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'ปลั๊กอินชุดนี้ไม่มีข้อมูลตั้งต้น (โฟลเดอร์ setup) ให้อัปโหลด hri-core.zip ที่สร้างจาก scripts/package-staging.sh ทับของเดิม', 'hri-core' ) . '</p></div></div>';
		return;
	}
	if ( $error ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $error ) . '</p></div>';
	}

	echo '<p>' . esc_html__( 'สร้างหน้า คอร์ส หน้าแปล และบทความตัวอย่างทั้งหมด ใช้เวลาหลายนาที อย่าปิดหน้านี้ ทำซ้ำได้ ข้อมูลเดิมจะถูกอัปเดต ไม่สร้างซ้ำ แต่หน้าที่ทีมแก้ไว้ใน editor จะถูกเขียนทับด้วยข้อความตั้งต้น จึงไม่ควรรันซ้ำหลังเริ่มแก้เนื้อหาจริง', 'hri-core' ) . '</p>';

	echo '<ol>';
	foreach ( $steps as $key => $label ) {
		$mark = isset( $log[ $key ] ) ? '✓ ' : '';
		$note = isset( $log[ $key ] ) ? ' — ' . $log[ $key ] : '';
		echo '<li>' . esc_html( $mark . $label . $note ) . '</li>';
	}
	echo '</ol>';

	if ( $next ) {
		echo '<form method="post" id="hri-setup-next">';
		wp_nonce_field( 'hri_setup' );
		echo '<input type="hidden" name="hri_setup_step" value="' . esc_attr( $next[0] ) . '"><input type="hidden" name="offset" value="' . esc_attr( (string) $next[1] ) . '"><input type="hidden" name="hri_setup_all" value="1">';
		/* translators: %s: step name */
		echo '<p>' . esc_html( sprintf( __( 'กำลังทำ: %s', 'hri-core' ), $steps[ $next[0] ] ?? $next[0] ) ) . '</p>';
		// Button not named "submit": that name would hide form.submit().
		submit_button( __( 'ทำต่อ', 'hri-core' ), 'primary', 'hri_continue', false );
		echo '</form><script>document.getElementById("hri-setup-next").submit();</script></div>';
		return;
	}

	echo '<form method="post">';
	wp_nonce_field( 'hri_setup' );
	echo '<input type="hidden" name="hri_setup_step" value="all"><input type="hidden" name="hri_setup_all" value="1">';
	submit_button( __( 'เริ่มตั้งค่าทั้งหมด', 'hri-core' ) );
	echo '</form></div>';
}
