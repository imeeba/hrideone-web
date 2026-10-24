<?php
/**
 * Lead form: the handler behind the `hri/lead-form` block.
 *
 * A submission is
 *   1. validated (required fields, e-mail, PDPA consent; honeypot, rate limit, optional Cloudflare Turnstile),
 *   2. saved as a private "ผู้สนใจ" (hri_lead) entry in wp-admin, so no lead is lost even if mail or Google fails,
 *   3. e-mailed to the company address (Settings → ข้อมูลบริษัท), and
 *   4. sent to a Google Sheet through an Apps Script web app, when HRI_LEAD_WEBHOOK_URL is defined.
 *
 * Secrets never live in the repository: define these in wp-config.php (see docs/LEAD-SETUP.md).
 *   HRI_LEAD_WEBHOOK_URL     Apps Script web app URL (…/exec)
 *   HRI_LEAD_WEBHOOK_TOKEN   shared secret the script checks (optional but recommended)
 *   HRI_TURNSTILE_SITE_KEY / HRI_TURNSTILE_SECRET   Cloudflare Turnstile (optional; honeypot + rate limit work without it)
 *   HRI_LEAD_NOTIFY          e-mail that receives new leads (default: company e-mail)
 *   HRI_IP_HEADER            (optional) server variable that holds the visitor's real IP behind a proxy / CDN, e.g. HTTP_CF_CONNECTING_IP
 *
 * Works without JavaScript (plain POST to admin-post.php + redirect); view.js sends it with fetch instead.
 * There is no nonce, so pages with the form can be cached.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fixed (English) labels stored with a lead when a service, not a course, was chosen.
 *
 * @return array<string,string>
 */
function hri_lead_service_labels(): array {
	return array(
		'inhouse'        => 'In-house Training',
		'public'         => 'Public Training',
		'transformation' => 'Transformation Solutions',
		'japanese'       => 'Japanese Concept',
		'other'          => 'Other',
	);
}

/**
 * Service names shown in the form (translatable).
 *
 * @return array<string,string>
 */
function hri_lead_service_options(): array {
	return array(
		'inhouse'        => __( 'หลักสูตร In-house (จัดอบรมในองค์กร)', 'hri-core' ),
		'public'         => __( 'Public Training (เปิดรับสมัครทั่วไป)', 'hri-core' ),
		'transformation' => __( 'Transformation Solutions', 'hri-core' ),
		'japanese'       => __( 'Japanese Concept', 'hri-core' ),
		'other'          => __( 'เรื่องอื่น ๆ', 'hri-core' ),
	);
}

/**
 * Published courses of one program in the visitor's language: id => title.
 *
 * @return array<int,string>
 */
function hri_lead_course_options( string $program ): array {
	$posts = get_posts(
		array(
			'post_type'        => 'course',
			'post_status'      => 'publish',
			'numberposts'      => 60,
			'orderby'          => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'no_found_rows'    => true,
			'suppress_filters' => false, // Polylang: current language only.
			'tax_query'        => array( array( 'taxonomy' => 'hri_program', 'field' => 'slug', 'terms' => $program ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	$out   = array();
	foreach ( $posts as $post ) {
		$out[ $post->ID ] = get_the_title( $post );
	}
	return $out;
}

/**
 * Select value for a course slug (or ID) in the visitor's language; '' when it is not a course.
 */
function hri_lead_course_value( string $slug_or_id ): string {
	$post = ctype_digit( $slug_or_id )
		? get_post( (int) $slug_or_id )
		: ( get_posts( array( 'post_type' => 'course', 'name' => sanitize_title( $slug_or_id ), 'post_status' => 'publish', 'numberposts' => 1, 'lang' => '' ) )[0] ?? null );
	if ( ! $post || 'course' !== $post->post_type ) {
		return '';
	}
	$id = $post->ID;
	if ( function_exists( 'pll_get_post' ) && function_exists( 'pll_current_language' ) && pll_current_language() ) {
		$id = (int) ( pll_get_post( $id, pll_current_language() ) ?: $id );
	}
	return 'course:' . $id;
}

/* -------------------------------------------------------------------------
 * Storage: private "ผู้สนใจ" entries in wp-admin.
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	static function () {
		register_post_type(
			'hri_lead',
			array(
				'labels'              => array(
					'name'          => __( 'ผู้สนใจ (Lead)', 'hri-core' ),
					'singular_name' => __( 'ผู้สนใจ', 'hri-core' ),
					'menu_name'     => __( 'ผู้สนใจ (Lead)', 'hri-core' ),
					'all_items'     => __( 'ผู้สนใจทั้งหมด', 'hri-core' ),
					'edit_item'     => __( 'ข้อมูลผู้สนใจ', 'hri-core' ),
					'search_items'  => __( 'ค้นหาผู้สนใจ', 'hri-core' ),
					'not_found'     => __( 'ยังไม่มีผู้สนใจ', 'hri-core' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-email-alt',
				'menu_position'       => 25,
				'capability_type'     => 'post',
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
				'supports'            => array( 'title' ),
				'rewrite'             => false,
				'show_in_rest'        => false,
			)
		);
	}
);

/**
 * Lead fields kept as post meta (labels for the admin).
 *
 * @return array<string,string>
 */
function hri_lead_meta_labels(): array {
	return array(
		'name'      => __( 'ชื่อ-นามสกุล', 'hri-core' ),
		'company'   => __( 'บริษัท / องค์กร', 'hri-core' ),
		'position'  => __( 'ตำแหน่ง', 'hri-core' ),
		'phone'     => __( 'เบอร์โทร', 'hri-core' ),
		'email'     => __( 'อีเมล', 'hri-core' ),
		'interest'  => __( 'สนใจเรื่อง', 'hri-core' ),
		'lang'      => __( 'ภาษา', 'hri-core' ),
		'source'    => __( 'หน้าที่ส่งฟอร์ม', 'hri-core' ),
		'consent'   => __( 'ยินยอม PDPA เมื่อ', 'hri-core' ),
		'webhook'   => __( 'ส่งเข้า Google Sheet', 'hri-core' ),
	);
}

add_action(
	'add_meta_boxes_hri_lead',
	static function () {
		add_meta_box(
			'hri_lead_details',
			__( 'ข้อมูลที่ผู้สนใจกรอก', 'hri-core' ),
			static function ( WP_Post $post ) {
				echo '<table class="form-table" role="presentation"><tbody>';
				foreach ( hri_lead_meta_labels() as $key => $label ) {
					$value = (string) get_post_meta( $post->ID, "hri_lead_{$key}", true );
					printf( '<tr><th scope="row">%1$s</th><td>%2$s</td></tr>', esc_html( $label ), '' !== $value ? esc_html( $value ) : '—' );
				}
				printf( '<tr><th scope="row">%1$s</th><td>%2$s</td></tr>', esc_html__( 'ข้อความ', 'hri-core' ), nl2br( esc_html( $post->post_content ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped.
				echo '</tbody></table>';
			},
			'hri_lead',
			'normal',
			'high'
		);
		remove_meta_box( 'submitdiv', 'hri_lead', 'side' );
		add_meta_box( 'submitdiv', __( 'จัดการ', 'hri-core' ), 'post_submit_meta_box', 'hri_lead', 'side', 'high' );
	}
);

add_filter(
	'manage_hri_lead_posts_columns',
	static function ( $columns ) {
		return array(
			'cb'       => $columns['cb'] ?? '',
			'title'    => __( 'ผู้สนใจ', 'hri-core' ),
			'phone'    => __( 'เบอร์โทร', 'hri-core' ),
			'email'    => __( 'อีเมล', 'hri-core' ),
			'interest' => __( 'สนใจเรื่อง', 'hri-core' ),
			'date'     => __( 'วันที่', 'hri-core' ),
		);
	}
);

add_action(
	'manage_hri_lead_posts_custom_column',
	static function ( $column, $post_id ) {
		if ( in_array( $column, array( 'phone', 'email', 'interest' ), true ) ) {
			echo esc_html( (string) get_post_meta( $post_id, "hri_lead_{$column}", true ) );
		}
	},
	10,
	2
);

/* -------------------------------------------------------------------------
 * Processing.
 * ---------------------------------------------------------------------- */

/**
 * Whether Cloudflare Turnstile is configured (both keys defined in wp-config.php).
 */
function hri_turnstile_enabled(): bool {
	return defined( 'HRI_TURNSTILE_SITE_KEY' ) && defined( 'HRI_TURNSTILE_SECRET' ) && HRI_TURNSTILE_SITE_KEY && HRI_TURNSTILE_SECRET;
}

/**
 * Visitor IP (only used to rate-limit and to call Turnstile; it is hashed and never stored).
 */
function hri_lead_ip(): string {
	$remote = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ) : '';
	$remote = false !== filter_var( $remote, FILTER_VALIDATE_IP ) ? $remote : '';

	// Behind a proxy / CDN every visitor would share the proxy's address (and the 5-per-10-minutes limit).
	// A forwarding header can be forged, so it is only believed when
	//   - the site owner says which header to trust: define( 'HRI_IP_HEADER', 'HTTP_CF_CONNECTING_IP' ) in wp-config.php
	//     (Cloudflare: HTTP_CF_CONNECTING_IP · many hosts: HTTP_X_FORWARDED_FOR or HTTP_X_REAL_IP), or
	//   - the connection comes from a private / loopback address (a reverse proxy in front of PHP on the host's own network).
	$headers = array();
	if ( defined( 'HRI_IP_HEADER' ) && '' !== (string) HRI_IP_HEADER ) {
		$headers = array( (string) HRI_IP_HEADER );
	} elseif ( '' !== $remote && false === filter_var( $remote, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
		$headers = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR' );
	}
	foreach ( $headers as $header ) {
		if ( empty( $_SERVER[ $header ] ) || ! is_string( $_SERVER[ $header ] ) ) {
			continue;
		}
		foreach ( explode( ',', wp_unslash( $_SERVER[ $header ] ) ) as $candidate ) { // X-Forwarded-For: the first address is the visitor.
			$candidate = trim( $candidate );
			if ( false !== filter_var( $candidate, FILTER_VALIDATE_IP ) ) {
				return $candidate;
			}
		}
	}
	return $remote;
}

/**
 * Validate, store and forward one submission.
 *
 * @param array<string,mixed> $input Raw request parameters.
 * @return array{id:int}|WP_Error The error carries the list of invalid field names in `data['fields']`.
 */
function hri_lead_process( array $input ) {
	$text = static fn( string $key ): string => isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ? trim( sanitize_text_field( wp_unslash( (string) $input[ $key ] ) ) ) : '';

	// Honeypot: bots fill the hidden field. Answer "success" so they do not retry.
	if ( '' !== $text( 'hri_website' ) ) {
		return array( 'id' => 0 );
	}

	$data = array(
		'name'     => mb_substr( $text( 'name' ), 0, 120 ),
		'company'  => mb_substr( $text( 'company' ), 0, 160 ),
		'position' => mb_substr( $text( 'position' ), 0, 120 ),
		'phone'    => mb_substr( $text( 'phone' ), 0, 40 ),
		'email'    => sanitize_email( $text( 'email' ) ),
		'interest' => $text( 'interest' ),
		'lang'     => in_array( $text( 'lang' ), array_keys( hri_languages() ), true ) ? $text( 'lang' ) : 'th',
		'source'   => esc_url_raw( $text( 'source' ) ),
	);
	$message = isset( $input['message'] ) && is_scalar( $input['message'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( (string) $input['message'] ) ), 0, 3000 ) : '';

	$invalid = array();
	if ( '' === $data['name'] ) {
		$invalid[] = 'name';
	}
	if ( '' === $data['company'] ) {
		$invalid[] = 'company';
	}
	if ( strlen( preg_replace( '/\D/', '', $data['phone'] ) ) < 8 ) {
		$invalid[] = 'phone';
	}
	if ( ! is_email( $data['email'] ) ) {
		$invalid[] = 'email';
	}
	if ( empty( $input['pdpa'] ) ) {
		$invalid[] = 'pdpa';
	}
	if ( $invalid ) {
		return new WP_Error( 'hri_lead_invalid', __( 'กรุณากรอกข้อมูลที่จำเป็นให้ครบ', 'hri-core' ), array( 'status' => 400, 'fields' => $invalid ) );
	}

	// Rate limit: 5 submissions per 10 minutes per visitor.
	$key   = 'hri_lead_' . md5( hri_lead_ip() . wp_salt() );
	$count = (int) get_transient( $key );
	if ( $count >= 5 ) {
		return new WP_Error( 'hri_lead_rate', __( 'ส่งข้อมูลบ่อยเกินไป กรุณารอสักครู่แล้วลองใหม่ หรือโทรหาเราได้ทันที', 'hri-core' ), array( 'status' => 429, 'fields' => array() ) );
	}
	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	if ( hri_turnstile_enabled() ) {
		$token    = $text( 'cf-turnstile-response' );
		$response = '' === $token ? null : wp_remote_post(
			'https://challenges.cloudflare.com/turnstile/v0/siteverify',
			array( 'timeout' => 8, 'body' => array( 'secret' => HRI_TURNSTILE_SECRET, 'response' => $token, 'remoteip' => hri_lead_ip() ) )
		);
		$ok       = $response && ! is_wp_error( $response ) && ! empty( json_decode( wp_remote_retrieve_body( $response ), true )['success'] );
		if ( ! $ok ) {
			return new WP_Error( 'hri_lead_captcha', __( 'ตรวจสอบไม่ผ่าน กรุณาลองใหม่อีกครั้ง', 'hri-core' ), array( 'status' => 400, 'fields' => array() ) );
		}
	}

	// What the visitor chose: a service or one course.
	$interest_label = '';
	if ( str_starts_with( $data['interest'], 'course:' ) ) {
		$course = get_post( (int) substr( $data['interest'], 7 ) );
		if ( $course && 'course' === $course->post_type ) {
			$interest_label = get_the_title( $course ) . ' (' . strtoupper( $data['lang'] ) . ')';
		}
	} elseif ( str_starts_with( $data['interest'], 'service:' ) ) {
		$interest_label = hri_lead_service_labels()[ substr( $data['interest'], 8 ) ] ?? '';
	}
	$data['interest'] = $interest_label;

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'hri_lead',
			'post_status'  => 'publish',
			'post_title'   => $data['name'] . ' — ' . $data['company'],
			'post_content' => $message,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'hri_lead_save', __( 'ระบบขัดข้อง กรุณาโทรหาเราหรือลองใหม่อีกครั้ง', 'hri-core' ), array( 'status' => 500, 'fields' => array() ) );
	}
	foreach ( $data as $field => $value ) {
		update_post_meta( $post_id, "hri_lead_{$field}", $value );
	}
	update_post_meta( $post_id, 'hri_lead_consent', current_time( 'mysql' ) );

	hri_lead_notify( $post_id, $data, $message );
	update_post_meta( $post_id, 'hri_lead_webhook', hri_lead_webhook( $post_id, $data, $message ) ? 'sent' : '' );

	return array( 'id' => (int) $post_id );
}

/**
 * E-mail the team (HRI_LEAD_NOTIFY or the company e-mail).
 *
 * @param array<string,string> $data
 */
function hri_lead_notify( int $post_id, array $data, string $message ): void {
	$to = defined( 'HRI_LEAD_NOTIFY' ) ? (string) HRI_LEAD_NOTIFY : hri_company( 'email' );
	if ( ! is_email( $to ) ) {
		return;
	}
	$lines = array();
	foreach ( hri_lead_meta_labels() as $key => $label ) {
		if ( isset( $data[ $key ] ) && '' !== $data[ $key ] ) {
			$lines[] = $label . ': ' . $data[ $key ];
		}
	}
	$lines[] = '';
	$lines[] = $message;
	$lines[] = '';
	$lines[] = admin_url( 'post.php?post=' . $post_id . '&action=edit' );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $data['email'] ) ) {
		$headers[] = 'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>';
	}
	wp_mail( $to, '[HRI DeOne] ' . ( $data['interest'] ?: 'Lead' ) . ' — ' . $data['name'], implode( "\n", $lines ), $headers );
}

/**
 * Send the lead to the Google Sheet (Apps Script web app). Does not wait for the answer.
 *
 * @param array<string,string> $data
 */
function hri_lead_webhook( int $post_id, array $data, string $message ): bool {
	if ( ! defined( 'HRI_LEAD_WEBHOOK_URL' ) || ! HRI_LEAD_WEBHOOK_URL ) {
		return false;
	}
	$payload = array_merge(
		$data,
		array(
			'token'     => defined( 'HRI_LEAD_WEBHOOK_TOKEN' ) ? HRI_LEAD_WEBHOOK_TOKEN : '',
			'timestamp' => current_time( 'c' ),
			'message'   => $message,
			'lead_id'   => $post_id,
			'site'      => home_url( '/' ),
		)
	);
	$result = wp_remote_post(
		HRI_LEAD_WEBHOOK_URL,
		array(
			'timeout'  => 5,
			'blocking' => false,
			'headers'  => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'     => wp_json_encode( $payload, JSON_UNESCAPED_UNICODE ),
		)
	);
	return ! is_wp_error( $result );
}

/* -------------------------------------------------------------------------
 * Entry point: admin-post.php. view.js posts to it with hri_ajax=1 and gets JSON; a plain form post
 * (no JavaScript) is redirected back with ?hri_lead=ok|error. (admin-post works on hosts that block /wp-json.)
 * ---------------------------------------------------------------------- */

function hri_lead_admin_post(): void {
	if ( ! empty( $_POST['hri_ajax'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- public form, see hri_lead_process().
		$result = hri_lead_process( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( is_wp_error( $result ) ) {
			wp_send_json(
				array( 'ok' => false, 'message' => $result->get_error_message(), 'fields' => $result->get_error_data()['fields'] ?? array() ),
				(int) ( $result->get_error_data()['status'] ?? 400 )
			);
		}
		wp_send_json( array( 'ok' => true ) );
	}

	$back = wp_get_referer();
	$back = $back ? remove_query_arg( array( 'hri_lead', 'hri_lead_fields' ), $back ) : hri_contact_url();
	$back = wp_validate_redirect( $back, hri_contact_url() );

	$result = hri_lead_process( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification -- public form (cache-friendly), protected by honeypot / rate limit.
	if ( is_wp_error( $result ) ) {
		$fields = (array) ( $result->get_error_data()['fields'] ?? array() );
		$url    = add_query_arg( array( 'hri_lead' => 'error', 'hri_lead_fields' => implode( ',', $fields ) ), $back );
	} else {
		$url = add_query_arg( 'hri_lead', 'ok', $back );
	}
	wp_safe_redirect( $url . '#hri-lead' );
	exit;
}
add_action( 'admin_post_nopriv_hri_lead', 'hri_lead_admin_post' );
add_action( 'admin_post_hri_lead', 'hri_lead_admin_post' );
