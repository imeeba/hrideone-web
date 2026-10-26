<?php
/**
 * Back-end of the automatic translation: the "การแปล" box in the editor, status column, the
 * "งานแปล" page, Settings → การแปล (policy, glossary, tone, usage) and the daily summary e-mail.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Status label and badge colour class.
 *
 * @return array{0:string,1:string}
 */
function hri_tr_status_label( string $status ): array {
	$map = array(
		'machine'  => array( __( '🤖 แปลด้วย AI (ยังไม่ตรวจ)', 'hri-core' ), 'machine' ),
		'reviewed' => array( __( '✅ ตรวจแล้ว', 'hri-core' ), 'reviewed' ),
		'outdated' => array( __( '⚠️ ต้องตัดสิน (ต้นฉบับเปลี่ยน)', 'hri-core' ), 'outdated' ),
		'queued'   => array( __( '⏳ รอคิว', 'hri-core' ), 'queued' ),
		'running'  => array( __( '⏳ กำลังแปล', 'hri-core' ), 'queued' ),
		'error'    => array( __( '❌ แปลไม่สำเร็จ', 'hri-core' ), 'error' ),
		'manual'   => array( __( '✍️ แปลด้วยมือ (ไม่ใช้ระบบอัตโนมัติ)', 'hri-core' ), 'manual' ),
		'none'     => array( __( '— ยังไม่มีหน้าแปล', 'hri-core' ), 'manual' ),
	);
	return $map[ $status ] ?? array( $status, 'manual' );
}

/**
 * The status shown for one language of a source post: job state first (queued, error), then the
 * translation's own status.
 *
 * @return array{status:string,target:int,job:array<string,mixed>,state:?array<string,mixed>}
 */
function hri_tr_display_status( int $source_id, string $lang ): array {
	$target = (int) pll_get_post( $source_id, $lang );
	$state  = $target ? hri_tr_state( $target ) : null;
	$job    = hri_tr_job( $source_id, $lang );
	if ( ! empty( $job['state'] ) && in_array( $job['state'], array( 'queued', 'running', 'error' ), true ) ) {
		$status = $job['state'];
	} elseif ( $state ) {
		$status = (string) $state['status'];
	} else {
		$status = $target ? 'manual' : 'none';
	}
	return array( 'status' => $status, 'target' => $target, 'job' => $job, 'state' => $state );
}

/**
 * Badge HTML.
 */
function hri_tr_badge( string $status ): string {
	$label = hri_tr_status_label( $status );
	return sprintf( '<span class="hri-tr-badge hri-tr-badge--%s">%s</span>', esc_attr( $label[1] ), esc_html( $label[0] ) );
}

add_action(
	'admin_head',
	static function () {
		$screen = get_current_screen();
		if ( ! $screen || ( ! in_array( $screen->post_type, hri_tr_post_types(), true ) && false === strpos( (string) $screen->id, 'hri-tr' ) ) ) {
			return;
		}
		echo '<style>.hri-tr-badge{display:inline-block;padding:1px 8px;border-radius:10px;font-size:12px;line-height:20px;background:#f0f0f1;white-space:nowrap}.hri-tr-badge--reviewed{background:#d7f0e8}.hri-tr-badge--outdated{background:#fcebc2}.hri-tr-badge--error{background:#f8d4d4}.hri-tr-badge--machine{background:#e0eef9}.hri-tr-box table{border-collapse:collapse;width:100%}.hri-tr-box td,.hri-tr-box th{padding:6px 8px;border-bottom:1px solid #dcdcde;text-align:left;vertical-align:top}.hri-tr-seg{background:#fafafa;border:1px solid #dcdcde;padding:8px;margin:6px 0}.hri-tr-seg pre{white-space:pre-wrap;margin:2px 0 8px;font:inherit}</style>';
	}
);

/* ----------------------------------------------------------------------
 * Meta box
 * ---------------------------------------------------------------------- */

add_action(
	'add_meta_boxes',
	static function ( $post_type ) {
		if ( in_array( $post_type, hri_tr_post_types(), true ) && hri_has_polylang() ) {
			add_meta_box( 'hri_tr_box', __( 'การแปล', 'hri-core' ), 'hri_tr_metabox', $post_type, 'side', 'default' );
		}
	}
);

/**
 * Link that runs an action on a post (admin-post, with nonce).
 *
 * @param array<string,string> $extra Extra query args.
 */
function hri_tr_action_url( string $action, int $post_id, string $lang = '', array $extra = array() ): string {
	$args = array_merge( array( 'action' => "hri_tr_{$action}", 'post' => $post_id, 'lang' => $lang ), $extra );
	return wp_nonce_url( add_query_arg( $args, admin_url( 'admin-post.php' ) ), "hri_tr_{$action}_{$post_id}" );
}

/**
 * The box.
 */
function hri_tr_metabox( WP_Post $post ): void {
	if ( ! hri_tr_configured() ) {
		echo '<p>' . esc_html__( 'ยังไม่ได้เชื่อมการแปลอัตโนมัติ (ตั้งค่าใน wp-config.php — ดู ตั้งค่า → การแปล)', 'hri-core' ) . '</p>';
		return;
	}
	echo '<div class="hri-tr-box">';
	if ( hri_tr_is_source( $post ) ) {
		hri_tr_metabox_source( $post );
	} elseif ( hri_tr_state( $post->ID ) ) {
		hri_tr_metabox_target( $post );
	} else {
		$lang = hri_tr_post_lang( $post->ID );
		echo '<p>' . hri_tr_badge( 'manual' ) . '</p><p class="description">' . esc_html__( 'หน้านี้แปลด้วยมือ ระบบจะไม่แก้ให้ ถ้าอยากให้อัปเดตตามหน้าไทยอัตโนมัติ ไปที่หน้าไทยแล้วกด "ใช้การแปลอัตโนมัติ" ที่ภาษานี้', 'hri-core' ) . '</p>';
		unset( $lang );
	}
	echo '</div>';
}

/**
 * Box on a Thai post: one row per language.
 */
function hri_tr_metabox_source( WP_Post $post ): void {
	wp_nonce_field( 'hri_tr_langs', 'hri_tr_langs_nonce' );
	$chosen = hri_tr_target_langs( $post );
	$names  = array( 'en' => 'English', 'ja' => '日本語' );
	echo '<p class="description">' . esc_html__( 'เขียนและแก้เฉพาะภาษาไทย เมื่อกดอัปเดต ระบบจะแปลให้ภายในไม่กี่นาที', 'hri-core' ) . '</p>';
	foreach ( $names as $lang => $name ) {
		$info = hri_tr_display_status( $post->ID, $lang );
		echo '<div style="margin:10px 0;padding-top:8px;border-top:1px solid #dcdcde">';
		printf( '<label><input type="checkbox" name="hri_tr_langs[]" value="%s"%s /> <strong>%s</strong></label> ', esc_attr( $lang ), checked( in_array( $lang, $chosen, true ), true, false ), esc_html( $name ) );
		echo hri_tr_badge( $info['status'] ) . '<br />'; // phpcs:ignore WordPress.Security.EscapeOutput -- built with esc_*.
		if ( 'error' === $info['status'] && ! empty( $info['job']['message'] ) ) {
			echo '<span style="color:#b32d2e">' . esc_html( (string) $info['job']['message'] ) . '</span><br />';
		}
		if ( $info['target'] ) {
			printf( '<a href="%s">%s</a> · ', esc_url( (string) get_edit_post_link( $info['target'] ) ), esc_html__( 'เปิดหน้าแปล', 'hri-core' ) );
		}
		if ( 'publish' === $post->post_status && in_array( $lang, $chosen, true ) ) {
			if ( 'manual' === $info['status'] ) {
				printf( '<a href="%s">%s</a>', esc_url( hri_tr_action_url( 'adopt', $post->ID, $lang ) ), esc_html__( 'ใช้การแปลอัตโนมัติ', 'hri-core' ) );
			} else {
				printf( '<a href="%s">%s</a>', esc_url( hri_tr_action_url( 'now', $post->ID, $lang ) ), esc_html( 'error' === $info['status'] ? __( 'ลองใหม่', 'hri-core' ) : __( 'แปลใหม่ตอนนี้', 'hri-core' ) ) );
			}
		}
		echo '</div>';
	}
	if ( 'publish' !== $post->post_status ) {
		echo '<p class="description">' . esc_html__( 'ระบบแปลเมื่อเผยแพร่แล้วเท่านั้น', 'hri-core' ) . '</p>';
	}
}

/**
 * Box on a translated post: status, decisions.
 */
function hri_tr_metabox_target( WP_Post $post ): void {
	$state  = (array) hri_tr_state( $post->ID );
	$source = get_post( (int) $state['source'] );
	echo '<p>' . hri_tr_badge( (string) $state['status'] ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput
	if ( $source ) {
		printf( '<p><a href="%s">%s</a></p>', esc_url( (string) get_edit_post_link( $source->ID ) ), esc_html__( '← แก้ที่หน้าไทย (ต้นฉบับ)', 'hri-core' ) );
	}
	$open = false;
	foreach ( (array) $state['segs'] as $key => $rec ) {
		if ( 'outdated' !== $rec['st'] ) {
			continue;
		}
		$open    = true;
		$current = hri_tr_post_segments( $post )[ $key ] ?? '';
		echo '<div class="hri-tr-seg"><strong>' . esc_html__( 'ข้อความไทยเปลี่ยน แต่คุณเคยแก้ข้อความนี้', 'hri-core' ) . '</strong>';
		echo '<small>' . esc_html__( 'ไทย (ใหม่)', 'hri-core' ) . '</small><pre>' . esc_html( wp_strip_all_tags( (string) ( $rec['new'] ?? '' ) ) ) . '</pre>';
		echo '<small>' . esc_html__( 'ที่คุณแก้ไว้ (ใช้อยู่)', 'hri-core' ) . '</small><pre>' . esc_html( wp_strip_all_tags( $current ) ) . '</pre>';
		echo '<small>' . esc_html__( 'ข้อเสนอจากเครื่อง', 'hri-core' ) . '</small><pre>' . esc_html( wp_strip_all_tags( (string) ( $rec['prop'] ?? '' ) ) ) . '</pre>';
		printf( '<a class="button button-small" href="%s">%s</a> ', esc_url( hri_tr_action_url( 'accept', $post->ID, '', array( 'key' => $key ) ) ), esc_html__( 'ใช้ข้อเสนอจากเครื่อง', 'hri-core' ) );
		printf( '<a class="button button-small" href="%s">%s</a>', esc_url( hri_tr_action_url( 'keep', $post->ID, '', array( 'key' => $key ) ) ), esc_html__( 'เก็บข้อความที่แก้ไว้', 'hri-core' ) );
		echo '</div>';
	}
	foreach ( (array) ( $state['orphans'] ?? array() ) as $key => $text ) {
		$open = true;
		echo '<div class="hri-tr-seg"><strong>' . esc_html__( 'ข้อความที่คุณเขียนไว้ในส่วนที่หน้าไทยลบหรือย้ายไปแล้ว', 'hri-core' ) . '</strong><pre>' . esc_html( wp_strip_all_tags( (string) $text ) ) . '</pre>';
		printf( '<a class="button button-small" href="%s">%s</a>', esc_url( hri_tr_action_url( 'dismiss', $post->ID, '', array( 'key' => $key ) ) ), esc_html__( 'ไม่ต้องใช้แล้ว (ลบรายการนี้)', 'hri-core' ) );
		echo '</div>';
	}
	if ( ! $open && 'reviewed' !== $state['status'] ) {
		printf( '<p><a class="button button-primary" href="%s">%s</a></p>', esc_url( hri_tr_action_url( 'review', $post->ID ) ), esc_html__( '✅ ตรวจแล้ว', 'hri-core' ) );
		if ( 'publish_noindex' === ( hri_tr_settings()['policy'][ hri_tr_post_lang( $post->ID ) ] ?? '' ) ) {
			echo '<p class="description">' . esc_html__( 'ภาษานี้ยังไม่ให้ Google เก็บ (noindex) จนกว่าจะกด "ตรวจแล้ว"', 'hri-core' ) . '</p>';
		}
	}
	if ( $source ) {
		printf(
			'<p><a href="%s" onclick="return confirm(\'%s\')">%s</a></p>',
			esc_url( hri_tr_action_url( 'redo', (int) $source->ID, hri_tr_post_lang( $post->ID ) ) ),
			esc_js( __( 'แปลทั้งหน้าใหม่จากภาษาไทย? ข้อความที่แก้ไว้ด้วยมือในหน้านี้จะถูกทับ', 'hri-core' ) ),
			esc_html__( 'แปลทั้งหน้าใหม่ (ทับที่แก้ไว้)', 'hri-core' )
		);
	}
}

// Which languages a Thai post is translated into (the checkboxes).
add_action(
	'save_post',
	static function ( $post_id, $post ) {
		if ( ! isset( $_POST['hri_tr_langs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hri_tr_langs_nonce'] ) ), 'hri_tr_langs' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}
		$langs = isset( $_POST['hri_tr_langs'] ) ? array_values( array_intersect( array_map( 'sanitize_key', (array) wp_unslash( $_POST['hri_tr_langs'] ) ), array( 'en', 'ja' ) ) ) : array();
		update_post_meta( $post_id, '_hri_tr_langs', $langs );
	},
	30,
	2
);

/* ----------------------------------------------------------------------
 * Actions (admin-post)
 * ---------------------------------------------------------------------- */

foreach ( array( 'now', 'adopt', 'redo', 'review', 'accept', 'keep', 'dismiss' ) as $hri_tr_action ) {
	add_action(
		"admin_post_hri_tr_{$hri_tr_action}",
		static function () use ( $hri_tr_action ) {
			$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
			check_admin_referer( "hri_tr_{$hri_tr_action}_{$post_id}" );
			if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
				wp_die( esc_html__( 'ไม่มีสิทธิ์', 'hri-core' ), '', array( 'response' => 403 ) );
			}
			$lang = isset( $_GET['lang'] ) ? sanitize_key( $_GET['lang'] ) : '';
			$key  = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
			$back = (string) get_edit_post_link( $post_id, 'raw' );
			switch ( $hri_tr_action ) {
				case 'now':
					hri_tr_job_save( $post_id, $lang, array() );
					hri_tr_enqueue( $post_id, $lang, 0 );
					spawn_cron();
					break;
				case 'adopt':
					try {
						hri_tr_run( $post_id, $lang, array( 'adopt' => true, 'budget' => 20 ) );
						hri_tr_job_save( $post_id, $lang, array() );
					} catch ( HRI_Translate_Error $e ) {
						hri_tr_job_save( $post_id, $lang, array( 'state' => 'error', 'kind' => $e->kind, 'message' => $e->getMessage(), 'attempt' => 3, 'at' => time() ) );
					}
					break;
				case 'redo':
					$target = (int) pll_get_post( $post_id, $lang );
					if ( $target && hri_tr_state( $target ) ) {
						$state                 = hri_tr_state( $target );
						$state['segs']         = array();
						$state['orphans']      = array();
						$state['status']       = 'machine';
						update_post_meta( $target, HRI_TR_META, $state );
					}
					hri_tr_job_save( $post_id, $lang, array() );
					hri_tr_enqueue( $post_id, $lang, 0 );
					spawn_cron();
					break;
				case 'review':
					hri_tr_mark_reviewed( $post_id );
					break;
				case 'accept':
					hri_tr_accept_proposal( $post_id, $key );
					break;
				case 'keep':
					hri_tr_keep_human( $post_id, $key );
					break;
				case 'dismiss':
					hri_tr_dismiss_orphan( $post_id, $key );
					break;
			}
			wp_safe_redirect( $back );
			exit;
		}
	);
}

/* ----------------------------------------------------------------------
 * Status column in the lists
 * ---------------------------------------------------------------------- */

add_action(
	'admin_init',
	static function () {
		foreach ( hri_tr_post_types() as $type ) {
			add_filter(
				"manage_{$type}_posts_columns",
				static function ( $cols ) {
					$cols['hri_tr'] = __( 'การแปล', 'hri-core' );
					return $cols;
				}
			);
			add_action(
				"manage_{$type}_posts_custom_column",
				static function ( $col, $post_id ) {
					if ( 'hri_tr' !== $col || ! hri_has_polylang() ) {
						return;
					}
					$post = get_post( $post_id );
					if ( $post && hri_tr_is_source( $post ) ) {
						foreach ( array( 'en', 'ja' ) as $lang ) {
							$info = hri_tr_display_status( $post_id, $lang );
							echo '<div>' . esc_html( strtoupper( $lang ) ) . ' ' . hri_tr_badge( $info['status'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
						}
					} elseif ( $post && hri_tr_state( $post_id ) ) {
						echo hri_tr_badge( (string) hri_tr_state( $post_id )['status'] ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
				},
				10,
				2
			);
		}
	}
);

/* ----------------------------------------------------------------------
 * "งานแปล" page
 * ---------------------------------------------------------------------- */

add_action(
	'admin_menu',
	static function () {
		add_menu_page( __( 'งานแปล', 'hri-core' ), __( 'งานแปล', 'hri-core' ), 'edit_pages', 'hri-tr-jobs', 'hri_tr_jobs_page', 'dashicons-translation', 58 );
		add_options_page( __( 'การแปล', 'hri-core' ), __( 'การแปล', 'hri-core' ), 'manage_options', 'hri-tr-settings', 'hri_tr_settings_page' );
	}
);

/**
 * The list of translations.
 */
function hri_tr_jobs_page(): void {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	$f_lang   = isset( $_GET['lang'] ) ? sanitize_key( $_GET['lang'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$f_status = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	echo '<div class="wrap hri-tr-box"><h1>' . esc_html__( 'งานแปล', 'hri-core' ) . '</h1>';
	if ( ! hri_tr_configured() ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'ยังไม่ได้เชื่อมการแปลอัตโนมัติ — ดู ตั้งค่า → การแปล', 'hri-core' ) . '</p></div></div>';
		return;
	}
	if ( isset( $_GET['terms_done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( /* translators: %d: count */ __( 'แปลชื่อหมวด/หัวข้อแล้ว %d รายการ', 'hri-core' ), absint( $_GET['terms_done'] ) ) ) . '</p></div>'; // phpcs:ignore WordPress.Security.NonceVerification
	}
	if ( isset( $_GET['terms_error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="notice notice-error"><p>' . esc_html( sanitize_text_field( wp_unslash( $_GET['terms_error'] ) ) ) . '</p></div>'; // phpcs:ignore WordPress.Security.NonceVerification
	}

	$rows = array();
	$targets = get_posts(
		array(
			'post_type'        => hri_tr_post_types(),
			'post_status'      => array( 'publish', 'draft', 'pending', 'private' ),
			'numberposts'      => 500,
			'meta_key'         => HRI_TR_META, // phpcs:ignore WordPress.DB.SlowDBQuery
			'lang'             => '',
			'orderby'          => 'modified',
			'order'            => 'DESC',
			'suppress_filters' => false,
		)
	);
	foreach ( $targets as $t ) {
		$st = hri_tr_state( $t->ID );
		if ( $st ) {
			$rows[] = array( 'post' => $t, 'lang' => (string) $st['lang'], 'status' => (string) $st['status'], 'source' => (int) $st['source'], 'at' => (int) $st['at'], 'error' => '' );
		}
	}
	// Jobs that failed or wait before a translation exists.
	foreach ( get_posts( array( 'post_type' => hri_tr_post_types(), 'post_status' => 'publish', 'numberposts' => 200, 'meta_key' => '_hri_tr_job_en', 'lang' => '', 'suppress_filters' => false ) ) as $s ) { // phpcs:ignore WordPress.DB.SlowDBQuery
		foreach ( array( 'en', 'ja' ) as $lang ) {
			$job = hri_tr_job( $s->ID, $lang );
			if ( $job && ! pll_get_post( $s->ID, $lang ) ) {
				$rows[] = array( 'post' => $s, 'lang' => $lang, 'status' => (string) $job['state'], 'source' => $s->ID, 'at' => (int) ( $job['at'] ?? 0 ), 'error' => (string) ( $job['message'] ?? '' ) );
			}
		}
	}

	$counts = array();
	foreach ( $rows as $r ) {
		$counts[ $r['status'] ] = ( $counts[ $r['status'] ] ?? 0 ) + 1;
	}
	$base = admin_url( 'admin.php?page=hri-tr-jobs' );
	echo '<p>';
	printf( '<a href="%s">%s</a>', esc_url( $base ), esc_html__( 'ทั้งหมด', 'hri-core' ) );
	foreach ( array( 'outdated', 'machine', 'reviewed', 'error', 'queued' ) as $s ) {
		if ( ! empty( $counts[ $s ] ) ) {
			printf( ' | <a href="%s">%s (%d)</a>', esc_url( add_query_arg( 'status', $s, $base ) ), esc_html( wp_strip_all_tags( hri_tr_status_label( $s )[0] ) ), (int) $counts[ $s ] );
		}
	}
	foreach ( array( 'en' => 'EN', 'ja' => 'JA' ) as $l => $name ) {
		printf( ' | <a href="%s">%s</a>', esc_url( add_query_arg( 'lang', $l, $base ) ), esc_html( $name ) );
	}
	echo '</p>';

	echo '<table class="widefat striped"><thead><tr><th>' . esc_html__( 'หน้า', 'hri-core' ) . '</th><th>' . esc_html__( 'ภาษา', 'hri-core' ) . '</th><th>' . esc_html__( 'สถานะ', 'hri-core' ) . '</th><th>' . esc_html__( 'ต้นฉบับแก้ล่าสุด', 'hri-core' ) . '</th><th>' . esc_html__( 'แปลล่าสุด', 'hri-core' ) . '</th></tr></thead><tbody>';
	$shown = 0;
	foreach ( $rows as $r ) {
		if ( ( $f_lang && $f_lang !== $r['lang'] ) || ( $f_status && $f_status !== $r['status'] ) ) {
			continue;
		}
		++$shown;
		$src = get_post( $r['source'] );
		printf(
			'<tr><td><a href="%s">%s</a><br /><small>%s</small>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
			esc_url( (string) get_edit_post_link( $r['post']->ID ) ),
			esc_html( wp_strip_all_tags( get_the_title( $r['post'] ) ) ),
			esc_html( $r['post']->post_type ),
			'' !== $r['error'] ? '<br /><span style="color:#b32d2e">' . esc_html( $r['error'] ) . '</span>' : '',
			esc_html( strtoupper( $r['lang'] ) ),
			hri_tr_badge( $r['status'] ), // phpcs:ignore WordPress.Security.EscapeOutput
			$src ? esc_html( mysql2date( 'j M Y H:i', $src->post_modified ) ) : '—',
			$r['at'] ? esc_html( wp_date( 'j M Y H:i', $r['at'] ) ) : '—'
		);
	}
	if ( ! $shown ) {
		echo '<tr><td colspan="5">' . esc_html__( 'ยังไม่มีรายการ', 'hri-core' ) . '</td></tr>';
	}
	echo '</tbody></table>';

	// Names of course categories / topics (shown on the filter), translated once.
	$missing = hri_tr_terms_missing();
	echo '<h2>' . esc_html__( 'ชื่อหมวดและหัวข้อของหลักสูตร', 'hri-core' ) . '</h2>';
	if ( $missing ) {
		echo '<p>' . esc_html( sprintf( /* translators: %d: count */ __( 'ยังไม่มีชื่อภาษาอังกฤษหรือญี่ปุ่น %d รายการ (แสดงในตัวกรองคอร์ส)', 'hri-core' ), count( $missing ) ) ) . '</p>';
		printf( '<p><a class="button" href="%s">%s</a></p>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=hri_tr_terms' ), 'hri_tr_terms' ) ), esc_html__( 'แปลชื่อที่ยังว่าง', 'hri-core' ) );
	} else {
		echo '<p>' . esc_html__( 'ครบทุกรายการแล้ว', 'hri-core' ) . '</p>';
	}
	echo '</div>';
}

/**
 * Course terms (all taxonomies) with an empty English or Japanese name: term id => missing languages.
 *
 * @return array<int,array{name:string,langs:string[]}>
 */
function hri_tr_terms_missing(): array {
	$out = array();
	if ( ! function_exists( 'hri_course_taxonomy_names' ) ) {
		return $out;
	}
	foreach ( hri_course_taxonomy_names() as $taxonomy ) {
		foreach ( (array) get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) ) as $term ) {
			if ( ! $term instanceof WP_Term ) {
				continue;
			}
			$langs = array();
			foreach ( array( 'en', 'ja' ) as $lang ) {
				if ( '' === trim( (string) get_term_meta( $term->term_id, "hri_name_{$lang}", true ) ) && preg_match( '/\p{L}/u', $term->name ) ) {
					$langs[] = $lang;
				}
			}
			if ( $langs ) {
				$out[ $term->term_id ] = array( 'name' => $term->name, 'langs' => $langs );
			}
		}
	}
	return $out;
}

add_action(
	'admin_post_hri_tr_terms',
	static function () {
		check_admin_referer( 'hri_tr_terms' );
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( '', '', array( 'response' => 403 ) );
		}
		$done = 0;
		$args = array();
		try {
			$missing = hri_tr_terms_missing();
			foreach ( array( 'en', 'ja' ) as $lang ) {
				$items = array();
				foreach ( $missing as $id => $row ) {
					if ( in_array( $lang, $row['langs'], true ) ) {
						$items[ 't:' . $id ] = $row['name'];
					}
				}
				if ( $items ) {
					$res = hri_tr_translate_items( $items, $lang, array( 'tone' => hri_tr_tone( $lang ), 'glossary' => hri_tr_glossary_for( $items, $lang ) ) );
					hri_tr_usage_add( $res['usage'] );
					foreach ( $res['translations'] as $key => $text ) {
						update_term_meta( (int) substr( $key, 2 ), "hri_name_{$lang}", sanitize_text_field( $text ) );
						++$done;
					}
				}
			}
			$args['terms_done'] = $done;
		} catch ( HRI_Translate_Error $e ) {
			$args['terms_error'] = $e->getMessage();
		}
		wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php?page=hri-tr-jobs' ) ) );
		exit;
	}
);

/* ----------------------------------------------------------------------
 * Settings → การแปล
 * ---------------------------------------------------------------------- */

add_action(
	'admin_init',
	static function () {
		register_setting(
			'hri_tr',
			'hri_tr_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => static function ( $v ): array {
					$v      = is_array( $v ) ? wp_unslash( $v ) : array();
					$out    = array( 'types' => array(), 'policy' => array(), 'reviewers' => array(), 'tone' => array() );
					foreach ( hri_tr_post_types() as $type ) {
						$out['types'][ $type ] = empty( $v['types'][ $type ] ) ? 0 : 1;
					}
					foreach ( array( 'en', 'ja' ) as $lang ) {
						$p                       = (string) ( $v['policy'][ $lang ] ?? 'publish' );
						$out['policy'][ $lang ]  = in_array( $p, array( 'publish', 'publish_noindex', 'draft' ), true ) ? $p : 'publish';
						$emails                  = array_filter( array_map( 'sanitize_email', array_map( 'trim', explode( ',', (string) ( $v['reviewers'][ $lang ] ?? '' ) ) ) ) );
						$out['reviewers'][ $lang ] = implode( ', ', $emails );
						$out['tone'][ $lang ]    = sanitize_textarea_field( (string) ( $v['tone'][ $lang ] ?? '' ) );
					}
					return $out;
				},
			)
		);
		register_setting(
			'hri_tr',
			'hri_tr_glossary',
			array(
				'type'              => 'string',
				'sanitize_callback' => static function ( $v ): string {
					$lines = array();
					foreach ( preg_split( '/\R/u', is_string( $v ) ? wp_unslash( $v ) : '' ) ?: array() as $line ) {
						$cols = array_map( static fn( $c ) => trim( sanitize_text_field( $c ) ), explode( '|', (string) $line ) );
						if ( '' !== ( $cols[0] ?? '' ) ) {
							$lines[] = implode( ' | ', array_slice( array_pad( $cols, 3, '' ), 0, 3 ) );
						}
					}
					return implode( "\n", array_slice( $lines, 0, 500 ) );
				},
			)
		);
	}
);

/**
 * The page.
 */
function hri_tr_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s     = hri_tr_settings();
	$types = array( 'page' => __( 'หน้า', 'hri-core' ), 'course' => __( 'หลักสูตร', 'hri-core' ), 'trainer' => __( 'วิทยากร', 'hri-core' ), 'post' => __( 'บทความ (ปกติไม่แปลเอง ติ๊กรายเรื่อง)', 'hri-core' ) );
	echo '<div class="wrap hri-tr-box"><h1>' . esc_html__( 'การแปลอัตโนมัติ (ไทย → English / 日本語)', 'hri-core' ) . '</h1>';

	echo '<h2>' . esc_html__( 'การเชื่อมต่อ', 'hri-core' ) . '</h2><table class="form-table"><tbody>';
	$provider = hri_tr_provider_name();
	echo '<tr><th>' . esc_html__( 'ผู้ให้บริการ', 'hri-core' ) . '</th><td>' . ( $provider ? esc_html( 'fake' === $provider ? 'fake (ทดสอบ ไม่เรียก API)' : 'Claude (Anthropic) — ' . hri_tr_model() ) : esc_html__( 'ยังไม่ได้ตั้งค่า', 'hri-core' ) ) . '</td></tr>';
	echo '<tr><th>API key</th><td>' . ( hri_tr_configured() ? esc_html__( 'ตั้งแล้ว (เก็บใน wp-config.php เท่านั้น)', 'hri-core' ) : esc_html__( 'ยังไม่ได้ตั้งค่า', 'hri-core' ) ) . '</td></tr>';
	if ( ! hri_tr_configured() ) {
		echo '<tr><th></th><td><p>' . esc_html__( 'เพิ่มบรรทัดเหล่านี้ใน wp-config.php (ใช้ API key ของบัญชี Anthropic ของเจ้าของเว็บเอง ค่าใช้จ่ายและโควต้าเป็นของบัญชีนั้น):', 'hri-core' ) . '</p><pre>define( \'HRI_TRANSLATE_PROVIDER\', \'anthropic\' );' . "\n" . 'define( \'HRI_TRANSLATE_API_KEY\', \'sk-ant-…\' );</pre></td></tr>';
	}
	echo '</tbody></table>';

	echo '<form method="post" action="options.php">';
	settings_fields( 'hri_tr' );
	echo '<h2>' . esc_html__( 'แปลอัตโนมัติ', 'hri-core' ) . '</h2><p>';
	foreach ( $types as $type => $label ) {
		printf( '<label style="display:block"><input type="checkbox" name="hri_tr_settings[types][%s]" value="1"%s /> %s</label>', esc_attr( $type ), checked( ! empty( $s['types'][ $type ] ), true, false ), esc_html( $label ) );
	}
	echo '</p><h2>' . esc_html__( 'การเผยแพร่ และผู้ตรวจ', 'hri-core' ) . '</h2><table class="form-table"><tbody>';
	$policies = array(
		'publish'         => __( 'เผยแพร่ทันที', 'hri-core' ),
		'publish_noindex' => __( 'เผยแพร่ แต่ไม่ให้ Google เก็บ (noindex) จนกว่าจะกด "ตรวจแล้ว"', 'hri-core' ),
		'draft'           => __( 'เก็บเป็นฉบับร่าง รอคนตรวจ', 'hri-core' ),
	);
	foreach ( array( 'en' => 'English', 'ja' => '日本語' ) as $lang => $name ) {
		echo '<tr><th>' . esc_html( $name ) . '</th><td><select name="hri_tr_settings[policy][' . esc_attr( $lang ) . ']">';
		foreach ( $policies as $value => $label ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $s['policy'][ $lang ], $value, false ), esc_html( $label ) );
		}
		echo '</select><br /><label>' . esc_html__( 'อีเมลผู้ตรวจ (คั่นด้วย , ว่าง = ไม่ส่ง)', 'hri-core' ) . ' <input type="text" class="regular-text" name="hri_tr_settings[reviewers][' . esc_attr( $lang ) . ']" value="' . esc_attr( $s['reviewers'][ $lang ] ) . '" /></label>';
		echo '<br /><label>' . esc_html__( 'น้ำเสียง (ว่าง = ค่าเริ่มต้น)', 'hri-core' ) . '<br /><textarea class="large-text" rows="2" name="hri_tr_settings[tone][' . esc_attr( $lang ) . ']" placeholder="' . esc_attr( hri_tr_default_tone()[ $lang ] ) . '">' . esc_textarea( $s['tone'][ $lang ] ) . '</textarea></label></td></tr>';
	}
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'อภิธานศัพท์', 'hri-core' ) . '</h2><p class="description">' . esc_html__( 'หนึ่งบรรทัดหนึ่งคำ: ไทย | English | 日本語 (เว้นช่องว่าง = ไม่แปล คงคำเดิม) ชื่อหลักสูตรและวิทยากรที่แปลแล้วใช้เป็นอภิธานให้อัตโนมัติ', 'hri-core' ) . '</p>';
	echo '<textarea class="large-text code" rows="8" name="hri_tr_glossary">' . esc_textarea( (string) get_option( 'hri_tr_glossary', '' ) ) . '</textarea>';
	submit_button();
	echo '</form>';

	$usage = get_option( 'hri_tr_usage' );
	echo '<h2>' . esc_html__( 'ยอดใช้งาน', 'hri-core' ) . '</h2><p class="description">' . esc_html__( 'ข้อมูลอย่างเดียว ไม่มีเพดานในเว็บนี้ — ใช้ตามโควต้าของบัญชี API ที่เชื่อมไว้', 'hri-core' ) . '</p>';
	echo '<table class="widefat striped" style="max-width:560px"><thead><tr><th>' . esc_html__( 'เดือน', 'hri-core' ) . '</th><th>' . esc_html__( 'ครั้งที่เรียก', 'hri-core' ) . '</th><th>Input tokens</th><th>Output tokens</th></tr></thead><tbody>';
	if ( is_array( $usage ) && $usage ) {
		foreach ( array_reverse( $usage, true ) as $ym => $row ) {
			printf( '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', esc_html( (string) $ym ), esc_html( number_format_i18n( (int) $row['calls'] ) ), esc_html( number_format_i18n( (int) $row['in'] ) ), esc_html( number_format_i18n( (int) $row['out'] ) ) );
		}
	} else {
		echo '<tr><td colspan="4">—</td></tr>';
	}
	echo '</tbody></table></div>';
}

/* ----------------------------------------------------------------------
 * Daily summary e-mail to the reviewers (only when an address is set and there is something to review)
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	static function () {
		if ( hri_tr_configured() && ! wp_next_scheduled( 'hri_tr_digest' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'hri_tr_digest' );
		}
	}
);

add_action(
	'hri_tr_digest',
	static function () {
		$since = (int) get_option( 'hri_tr_digest_at', time() - DAY_IN_SECONDS );
		update_option( 'hri_tr_digest_at', time(), false );
		foreach ( array( 'en', 'ja' ) as $lang ) {
			$to = array_filter( array_map( 'trim', explode( ',', (string) hri_tr_settings()['reviewers'][ $lang ] ) ) );
			if ( ! $to ) {
				continue;
			}
			$lines = array();
			foreach ( get_posts( array( 'post_type' => hri_tr_post_types(), 'post_status' => 'any', 'numberposts' => 200, 'meta_key' => HRI_TR_META, 'lang' => $lang, 'suppress_filters' => false ) ) as $p ) { // phpcs:ignore WordPress.DB.SlowDBQuery
				$st = hri_tr_state( $p->ID );
				if ( $st && 'reviewed' !== $st['status'] && (int) $st['at'] >= $since ) {
					$lines[] = '- ' . wp_strip_all_tags( get_the_title( $p ) ) . ' [' . $st['status'] . '] ' . get_edit_post_link( $p->ID, 'raw' );
				}
			}
			if ( $lines ) {
				wp_mail(
					$to,
					sprintf( /* translators: %s: language */ __( '[HRI DeOne] มีงานแปล %s รอตรวจ', 'hri-core' ), strtoupper( $lang ) ),
					implode( "\n", $lines ) . "\n\n" . admin_url( 'admin.php?page=hri-tr-jobs&lang=' . $lang )
				);
			}
		}
	}
);
