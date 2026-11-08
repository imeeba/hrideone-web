<?php
/**
 * Site health and security report for admins.
 *
 * - Tools → สุขภาพเว็บ: the full report, the recipients and a "send a test" button.
 * - Dashboard widget with the same checks (Administrators only).
 * - Weekly email (Monday morning, Bangkok time) to the recipients.
 * - Urgent email at once (checked daily) when the SSL certificate has 14 days or less, or an
 *   installed plugin / theme / WordPress version has a known vulnerability. Same issue is sent
 *   again after 3 days at most.
 *
 * Vulnerabilities come from WPVulnerability.net (free, no API key). Uptime is watched from outside
 * the server (Better Stack, see docs/MONITORING.md): a site that is down cannot email about itself.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_HEALTH_OPTION = 'hri_health';

/**
 * Saved settings.
 *
 * @return array{emails:string,status_url:string}
 */
function hri_health_options(): array {
	$saved = get_option( HRI_HEALTH_OPTION );
	$saved = is_array( $saved ) ? $saved : array();
	return array(
		'emails'     => (string) ( $saved['emails'] ?? '' ),
		'status_url' => (string) ( $saved['status_url'] ?? '' ),
	);
}

/**
 * Who gets the emails: the saved list, or the site admin email when none is saved.
 *
 * @return string[]
 */
function hri_health_recipients(): array {
	$list = array_filter( array_map( 'sanitize_email', preg_split( '/[\s,;]+/', hri_health_options()['emails'] ) ) );
	return $list ? array_values( array_unique( $list ) ) : array( (string) get_option( 'admin_email' ) );
}

/* ----------------------------------------------------------------------
 * Checks. Each returns: status (ok | warn | bad), label, text, and optional items.
 * ---------------------------------------------------------------------- */

/**
 * One check result.
 *
 * @param string   $status ok | warn | bad.
 * @param string   $label  What was checked.
 * @param string   $text   The result in one line.
 * @param string[] $items  Details.
 * @return array{status:string,label:string,text:string,items:string[]}
 */
function hri_health_result( string $status, string $label, string $text, array $items = array() ): array {
	return compact( 'status', 'label', 'text', 'items' );
}

/**
 * Days until the site's HTTPS certificate expires (null = could not read it).
 */
function hri_health_ssl_days(): ?int {
	$cached = get_transient( 'hri_health_ssl' );
	if ( false !== $cached ) {
		return '' === $cached ? null : (int) $cached;
	}
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	$days = null;
	if ( $host && function_exists( 'stream_socket_client' ) && function_exists( 'openssl_x509_parse' ) ) {
		$context = stream_context_create( array( 'ssl' => array( 'capture_peer_cert' => true, 'SNI_enabled' => true, 'peer_name' => $host, 'verify_peer' => false, 'verify_peer_name' => false ) ) );
		$socket  = @stream_socket_client( "ssl://{$host}:443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- a failure is reported as "could not read".
		if ( $socket ) {
			$params = stream_context_get_params( $socket );
			$cert   = $params['options']['ssl']['peer_certificate'] ?? null;
			$info   = $cert ? openssl_x509_parse( $cert ) : false;
			if ( is_array( $info ) && ! empty( $info['validTo_time_t'] ) ) {
				$days = (int) floor( ( (int) $info['validTo_time_t'] - time() ) / DAY_IN_SECONDS );
			}
			fclose( $socket ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		}
	}
	set_transient( 'hri_health_ssl', null === $days ? '' : $days, 6 * HOUR_IN_SECONDS );
	return $days;
}

/**
 * SSL certificate.
 */
function hri_health_check_ssl(): array {
	$label = __( 'ใบรับรอง SSL', 'hri-core' );
	$days  = hri_health_ssl_days();
	if ( null === $days ) {
		return hri_health_result( 'warn', $label, __( 'อ่านวันหมดอายุไม่ได้ (เซิร์ฟเวอร์ไม่อนุญาตให้เชื่อมต่อตัวเอง) ให้ดูที่ Better Stack แทน', 'hri-core' ) );
	}
	/* translators: %d: days */
	$text = sprintf( _n( 'หมดอายุในอีก %d วัน', 'หมดอายุในอีก %d วัน', $days, 'hri-core' ), $days );
	if ( $days <= 14 ) {
		return hri_health_result( 'bad', $label, $text . ' — ' . __( 'ติดต่อโฮสต์ให้ต่ออายุทันที', 'hri-core' ) );
	}
	return hri_health_result( $days <= 30 ? 'warn' : 'ok', $label, $text );
}

/**
 * Pending updates of WordPress, plugins and themes (from WordPress's own update check).
 */
function hri_health_check_updates(): array {
	require_once ABSPATH . 'wp-admin/includes/update.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$items = array();
	foreach ( (array) get_core_updates() as $update ) {
		if ( is_object( $update ) && 'upgrade' === ( $update->response ?? '' ) ) {
			/* translators: %s: version */
			$items[] = sprintf( __( 'WordPress %s', 'hri-core' ), $update->current );
			break;
		}
	}
	foreach ( get_plugin_updates() as $plugin ) {
		$items[] = $plugin->Name . ' ' . $plugin->Version . ' → ' . ( $plugin->update->new_version ?? '?' );
	}
	foreach ( get_theme_updates() as $theme ) {
		$items[] = $theme->get( 'Name' ) . ' → ' . ( $theme->update['new_version'] ?? '?' );
	}
	$label = __( 'อัปเดตที่ค้าง', 'hri-core' );
	if ( ! $items ) {
		return hri_health_result( 'ok', $label, __( 'ทุกอย่างเป็นเวอร์ชันล่าสุด', 'hri-core' ) );
	}
	/* translators: %d: number of updates */
	return hri_health_result( 'warn', $label, sprintf( __( 'มี %d รายการรออัปเดต', 'hri-core' ), count( $items ) ), $items );
}

/**
 * Known vulnerabilities of one component (WPVulnerability.net), cached 12 hours.
 *
 * @param string $type    plugin | theme | core.
 * @param string $slug    Plugin/theme slug, or the WordPress version for core.
 * @param string $version Installed version.
 * @return string[]|null  Titles of the vulnerabilities that affect $version; null = no answer.
 */
function hri_health_vulns( string $type, string $slug, string $version ): ?array {
	$key    = 'hri_vuln_' . md5( "{$type}/{$slug}/{$version}" );
	$cached = get_transient( $key );
	if ( is_array( $cached ) ) {
		return $cached;
	}
	$response = wp_remote_get( "https://www.wpvulnerability.net/{$type}/" . rawurlencode( $slug ) . '/', array( 'timeout' => 8 ) );
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return null;
	}
	$body  = json_decode( wp_remote_retrieve_body( $response ), true );
	$found = array();
	foreach ( (array) ( $body['data']['vulnerability'] ?? array() ) as $vuln ) {
		$op       = (array) ( $vuln['operator'] ?? array() );
		$affected = 'core' === $type; // Core answers are already for this exact version.
		if ( ! $affected ) {
			$affected = true;
			if ( ! empty( $op['max_version'] ) ) {
				$affected = version_compare( $version, (string) $op['max_version'], (string) ( $op['max_operator'] ?? 'le' ) );
			}
			if ( $affected && ! empty( $op['min_version'] ) ) {
				$affected = version_compare( $version, (string) $op['min_version'], (string) ( $op['min_operator'] ?? 'ge' ) );
			}
		}
		if ( $affected ) {
			$found[] = wp_strip_all_tags( (string) ( $vuln['name'] ?? __( 'ช่องโหว่ที่ไม่มีชื่อ', 'hri-core' ) ) );
		}
	}
	set_transient( $key, $found, 12 * HOUR_IN_SECONDS );
	return $found;
}

/**
 * Vulnerabilities of WordPress, every installed plugin and the active theme.
 */
function hri_health_check_vulns(): array {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$label   = __( 'ช่องโหว่ที่รู้จัก', 'hri-core' );
	$items   = array();
	$no_data = 0;
	$checks  = array( array( 'core', get_bloginfo( 'version' ), get_bloginfo( 'version' ), 'WordPress' ) );
	foreach ( get_plugins() as $file => $data ) {
		$slug = dirname( $file );
		if ( '.' === $slug || str_starts_with( $slug, 'hri-' ) ) {
			continue; // Single-file plugins have no public record; ours is reviewed in the repo.
		}
		$checks[] = array( 'plugin', $slug, (string) $data['Version'], (string) $data['Name'] );
	}
	foreach ( array( get_template(), get_stylesheet() ) as $theme ) {
		if ( ! str_starts_with( $theme, 'hri-' ) ) {
			$checks[] = array( 'theme', $theme, (string) wp_get_theme( $theme )->get( 'Version' ), $theme );
		}
	}
	foreach ( array_unique( $checks, SORT_REGULAR ) as $check ) {
		$found = hri_health_vulns( $check[0], $check[1], $check[2] );
		if ( null === $found ) {
			++$no_data;
			continue;
		}
		foreach ( $found as $title ) {
			$items[] = $check[3] . ' ' . $check[2] . ': ' . $title;
		}
	}
	if ( $items ) {
		return hri_health_result( 'bad', $label, __( 'พบช่องโหว่ — อัปเดตหรือปิดปลั๊กอินนั้นทันที', 'hri-core' ), $items );
	}
	if ( $no_data === count( $checks ) ) {
		return hri_health_result( 'warn', $label, __( 'เชื่อมต่อฐานข้อมูลช่องโหว่ไม่ได้ จะลองใหม่รอบหน้า', 'hri-core' ) );
	}
	/* translators: %d: number of components */
	return hri_health_result( 'ok', $label, sprintf( __( 'ไม่พบช่องโหว่ใน %d รายการที่ติดตั้ง', 'hri-core' ), count( $checks ) - $no_data ) );
}

/**
 * Failed logins in the last 7 days (Loginizer's log), and admins without two-factor login.
 */
function hri_health_check_logins(): array {
	global $wpdb;
	$label = __( 'การเข้าสู่ระบบ', 'hri-core' );
	$items = array();

	$table = $wpdb->prefix . 'loginizer_logs';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$failed = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COALESCE(SUM(`count`),0) FROM `{$table}` WHERE `time` > %d", time() - WEEK_IN_SECONDS ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name from $wpdb->prefix.
		/* translators: %d: number of failed logins */
		$items[] = sprintf( __( 'ล็อกอินผิด %d ครั้งใน 7 วัน (Loginizer)', 'hri-core' ), $failed );
	}

	$admins  = get_users( array( 'role' => 'administrator', 'fields' => array( 'ID', 'user_login' ) ) );
	$without = array();
	if ( class_exists( 'Two_Factor_Core' ) ) {
		foreach ( $admins as $admin ) {
			if ( ! Two_Factor_Core::is_user_using_two_factor( (int) $admin->ID ) ) {
				$without[] = $admin->user_login;
			}
		}
	} else {
		$without = wp_list_pluck( $admins, 'user_login' );
	}
	/* translators: %d: number of administrators */
	$items[] = sprintf( __( 'ผู้ดูแลระบบ %d คน', 'hri-core' ), count( $admins ) );
	if ( $without ) {
		/* translators: %s: user names */
		$items[] = sprintf( __( 'ยังไม่เปิด 2FA: %s', 'hri-core' ), implode( ', ', $without ) );
		return hri_health_result( 'warn', $label, __( 'มีผู้ดูแลระบบที่ยังไม่เปิดล็อกอิน 2 ขั้นตอน (ปลั๊กอิน Two Factor)', 'hri-core' ), $items );
	}
	return hri_health_result( 'ok', $label, __( 'ผู้ดูแลระบบทุกคนเปิด 2FA แล้ว', 'hri-core' ), $items );
}

/**
 * WordPress Site Health (Tools → Site Health), as WordPress saved it at its last run.
 */
function hri_health_check_site_health(): array {
	$label  = __( 'Site Health ของ WordPress', 'hri-core' );
	$result = json_decode( (string) get_transient( 'health-check-site-status-result' ), true );
	if ( ! is_array( $result ) ) {
		return hri_health_result( 'warn', $label, __( 'ยังไม่มีผล เปิดหน้า Tools → Site Health หนึ่งครั้ง', 'hri-core' ) );
	}
	$critical = (int) ( $result['critical'] ?? 0 );
	/* translators: 1: good, 2: recommended, 3: critical */
	$text = sprintf( __( 'ดี %1$d · ควรปรับ %2$d · ร้ายแรง %3$d', 'hri-core' ), (int) ( $result['good'] ?? 0 ), (int) ( $result['recommended'] ?? 0 ), $critical );
	return hri_health_result( $critical ? 'bad' : 'ok', $label, $text );
}

/**
 * Age of the newest backup (UpdraftPlus or All-in-One WP Migration).
 */
function hri_health_check_backup(): array {
	$label = __( 'Backup ล่าสุด', 'hri-core' );
	$times = array();

	$updraft = get_option( 'updraft_last_backup' );
	if ( is_array( $updraft ) && ! empty( $updraft['backup_time'] ) ) {
		$times['UpdraftPlus'] = (int) $updraft['backup_time'];
	}
	$dir = WP_CONTENT_DIR . '/ai1wm-backups';
	if ( is_dir( $dir ) ) {
		foreach ( (array) glob( $dir . '/*.wpress' ) as $file ) {
			$times['All-in-One WP Migration'] = max( $times['All-in-One WP Migration'] ?? 0, (int) filemtime( $file ) );
		}
	}
	if ( ! $times ) {
		return hri_health_result( 'bad', $label, __( 'ไม่พบ backup จาก UpdraftPlus หรือ All-in-One WP Migration', 'hri-core' ) );
	}
	arsort( $times );
	$tool = (string) array_key_first( $times );
	$days = (int) floor( ( time() - $times[ $tool ] ) / DAY_IN_SECONDS );
	/* translators: 1: days ago, 2: plugin name */
	$text = sprintf( __( '%1$d วันที่แล้ว (%2$s)', 'hri-core' ), $days, $tool );
	$item = 'All-in-One WP Migration' === $tool ? array( __( 'ไฟล์ backup อยู่บนเซิร์ฟเวอร์เดียวกับเว็บ ควรมีสำเนาไว้นอกเซิร์ฟเวอร์ด้วย', 'hri-core' ) ) : array();
	return hri_health_result( $days > 7 ? 'bad' : ( $days > 1 || $item ? 'warn' : 'ok' ), $label, $text, $item );
}

/**
 * Settings that should be on/off on a live site.
 */
function hri_health_check_config(): array {
	$items = array();
	if ( ! defined( 'DISALLOW_FILE_EDIT' ) || ! DISALLOW_FILE_EDIT ) {
		$items[] = __( 'แก้ไฟล์ธีม/ปลั๊กอินจากหน้า admin ได้ (DISALLOW_FILE_EDIT ปิดอยู่)', 'hri-core' );
	}
	if ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$items[] = __( 'แสดง error ของ PHP บนหน้าเว็บ (WP_DEBUG_DISPLAY)', 'hri-core' );
	}
	if ( ! is_ssl() && str_starts_with( home_url(), 'http://' ) ) {
		$items[] = __( 'เว็บยังไม่ใช้ https', 'hri-core' );
	}
	if ( '0' === (string) get_option( 'blog_public' ) ) {
		$items[] = __( 'ซ่อนจาก search engine อยู่ (ปกติสำหรับ staging ต้องเปิดก่อน go-live)', 'hri-core' );
	}
	$label = __( 'การตั้งค่าความปลอดภัย', 'hri-core' );
	return $items ? hri_health_result( 'warn', $label, __( 'มีเรื่องที่ควรตรวจ', 'hri-core' ), $items ) : hri_health_result( 'ok', $label, __( 'ตั้งค่าถูกต้อง', 'hri-core' ) );
}

/**
 * All checks.
 *
 * @return array<string,array{status:string,label:string,text:string,items:string[]}>
 */
function hri_health_report(): array {
	return array(
		'vulns'   => hri_health_check_vulns(),
		'ssl'     => hri_health_check_ssl(),
		'updates' => hri_health_check_updates(),
		'backup'  => hri_health_check_backup(),
		'logins'  => hri_health_check_logins(),
		'health'  => hri_health_check_site_health(),
		'config'  => hri_health_check_config(),
	);
}

/**
 * The report, kept for an hour so the dashboard does not wait for the vulnerability lookups.
 *
 * @param bool $fresh Run every check now.
 */
function hri_health_report_cached( bool $fresh = false ): array {
	$report = $fresh ? false : get_transient( 'hri_health_report' );
	if ( ! is_array( $report ) ) {
		$report = hri_health_report();
		set_transient( 'hri_health_report', $report, HOUR_IN_SECONDS );
	}
	return $report;
}

/* ----------------------------------------------------------------------
 * Output: HTML table (admin page, widget, email)
 * ---------------------------------------------------------------------- */

/**
 * The report as an HTML table with inline styles (works in email clients too).
 *
 * @param array $report From hri_health_report().
 */
function hri_health_html( array $report ): string {
	$icons  = array( 'ok' => '✅', 'warn' => '⚠️', 'bad' => '🔴' );
	$words  = array( 'ok' => __( 'ปกติ', 'hri-core' ), 'warn' => __( 'ควรดู', 'hri-core' ), 'bad' => __( 'ต้องแก้', 'hri-core' ) );
	$out    = '<table role="presentation" style="border-collapse:collapse;width:100%;font-size:14px;line-height:1.5">';
	foreach ( $report as $row ) {
		$out .= '<tr style="border-top:1px solid #dcdcde"><td style="padding:8px 8px 8px 0;vertical-align:top;white-space:nowrap">'
			. '<span aria-hidden="true">' . $icons[ $row['status'] ] . '</span> <span class="screen-reader-text">' . esc_html( $words[ $row['status'] ] ) . '</span></td>'
			. '<td style="padding:8px 0;vertical-align:top"><strong>' . esc_html( $row['label'] ) . '</strong><br>' . esc_html( $row['text'] );
		if ( $row['items'] ) {
			$out .= '<ul style="margin:4px 0 0 1.2em;padding:0">';
			foreach ( $row['items'] as $item ) {
				$out .= '<li>' . esc_html( $item ) . '</li>';
			}
			$out .= '</ul>';
		}
		$out .= '</td></tr>';
	}
	$status_url = hri_health_options()['status_url'];
	if ( $status_url ) {
		$out .= '<tr style="border-top:1px solid #dcdcde"><td style="padding:8px 8px 8px 0">🌐</td><td style="padding:8px 0"><strong>' . esc_html__( 'Uptime', 'hri-core' ) . '</strong><br><a href="' . esc_url( $status_url ) . '">' . esc_html__( 'ดูสถานะเว็บล่ม/ไม่ล่ม (Better Stack)', 'hri-core' ) . '</a></td></tr>';
	}
	return $out . '</table>';
}

/**
 * Worst status in a report: bad > warn > ok.
 *
 * @param array $report From hri_health_report().
 */
function hri_health_worst( array $report ): string {
	$statuses = wp_list_pluck( $report, 'status' );
	return in_array( 'bad', $statuses, true ) ? 'bad' : ( in_array( 'warn', $statuses, true ) ? 'warn' : 'ok' );
}

/**
 * Sends an HTML email to the recipients.
 *
 * @param string $subject Subject (the site name is added).
 * @param string $html    Body.
 */
function hri_health_mail( string $subject, string $html ): bool {
	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$body = '<div style="font-family:sans-serif;max-width:640px">' . $html
		. '<p style="color:#50575e;font-size:12px;margin-top:24px">' . esc_html( home_url( '/' ) ) . ' · '
		. '<a href="' . esc_url( admin_url( 'tools.php?page=hri-health' ) ) . '">' . esc_html__( 'ดูรายงานเต็มใน wp-admin', 'hri-core' ) . '</a></p></div>';
	return wp_mail( hri_health_recipients(), "[{$site}] {$subject}", $body, array( 'Content-Type: text/html; charset=UTF-8' ) );
}

/* ----------------------------------------------------------------------
 * Schedules: weekly report, daily urgent check
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	static function () {
		if ( ! wp_next_scheduled( 'hri_health_weekly' ) ) {
			// Next Monday 09:00 Bangkok (02:00 UTC).
			wp_schedule_event( (int) strtotime( 'next monday 02:00 UTC' ), 'weekly', 'hri_health_weekly' );
		}
		if ( ! wp_next_scheduled( 'hri_health_daily' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'hri_health_daily' );
		}
	}
);

add_action(
	'hri_health_weekly',
	static function () {
		$report = hri_health_report_cached( true );
		$titles = array( 'ok' => __( 'รายงานประจำสัปดาห์: ทุกอย่างปกติ', 'hri-core' ), 'warn' => __( 'รายงานประจำสัปดาห์: มีเรื่องควรดู', 'hri-core' ), 'bad' => __( 'รายงานประจำสัปดาห์: มีเรื่องต้องแก้', 'hri-core' ) );
		$worst  = hri_health_worst( $report );
		hri_health_mail( $titles[ $worst ], '<h2 style="font-size:18px">' . esc_html( $titles[ $worst ] ) . '</h2>' . hri_health_html( $report ) );
	}
);

add_action(
	'hri_health_daily',
	static function () {
		$urgent = array_filter(
			array( 'ssl' => hri_health_check_ssl(), 'vulns' => hri_health_check_vulns() ),
			static fn( $row ) => 'bad' === $row['status']
		);
		if ( ! $urgent ) {
			return;
		}
		$fingerprint = md5( (string) wp_json_encode( $urgent ) );
		if ( get_transient( 'hri_health_alerted' ) === $fingerprint ) {
			return; // Same problem already sent within 3 days.
		}
		set_transient( 'hri_health_alerted', $fingerprint, 3 * DAY_IN_SECONDS );
		$title = __( 'แจ้งเตือนด่วน: ต้องแก้ทันที', 'hri-core' );
		hri_health_mail( $title, '<h2 style="font-size:18px;color:#b32d2e">' . esc_html( $title ) . '</h2>' . hri_health_html( $urgent ) );
	}
);

/* ----------------------------------------------------------------------
 * Admin: Tools → สุขภาพเว็บ, dashboard widget
 * ---------------------------------------------------------------------- */

add_action(
	'admin_init',
	static function () {
		register_setting(
			'hri_health',
			HRI_HEALTH_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => static function ( $value ): array {
					$value  = is_array( $value ) ? $value : array();
					$emails = array_filter( array_map( 'sanitize_email', preg_split( '/[\s,;]+/', (string) ( $value['emails'] ?? '' ) ) ) );
					return array(
						'emails'     => implode( ', ', array_unique( $emails ) ),
						'status_url' => esc_url_raw( (string) ( $value['status_url'] ?? '' ) ),
					);
				},
			)
		);
	}
);

add_action(
	'admin_menu',
	static function () {
		add_management_page( __( 'สุขภาพเว็บ', 'hri-core' ), __( 'สุขภาพเว็บ', 'hri-core' ), 'manage_options', 'hri-health', 'hri_health_page' );
	}
);

add_action(
	'admin_post_hri_health_test',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'ไม่มีสิทธิ์', 'hri-core' ), 403 );
		}
		check_admin_referer( 'hri_health_test' );
		delete_transient( 'hri_health_ssl' );
		$report = hri_health_report_cached( true );
		$sent   = hri_health_mail( __( 'ทดสอบรายงานสุขภาพเว็บ', 'hri-core' ), hri_health_html( $report ) );
		wp_safe_redirect( add_query_arg( 'hri_sent', $sent ? '1' : '0', admin_url( 'tools.php?page=hri-health' ) ) );
		exit;
	}
);

/**
 * Tools → สุขภาพเว็บ.
 */
function hri_health_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = hri_health_options();
	echo '<div class="wrap"><h1>' . esc_html__( 'สุขภาพเว็บ', 'hri-core' ) . '</h1>';
	if ( isset( $_GET['hri_sent'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
		$ok = '1' === $_GET['hri_sent']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-' . ( $ok ? 'success' : 'error' ) . '"><p>' . ( $ok ? esc_html__( 'ส่งอีเมลทดสอบแล้ว', 'hri-core' ) : esc_html__( 'ส่งอีเมลไม่สำเร็จ ตรวจการตั้งค่าอีเมลของโฮสต์', 'hri-core' ) ) . '</p></div>';
	}
	echo '<div style="max-width:760px;background:#fff;border:1px solid #c3c4c7;padding:4px 16px 12px;margin-top:16px">' . hri_health_html( hri_health_report_cached() ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in hri_health_html().

	echo '<h2>' . esc_html__( 'การแจ้งเตือน', 'hri-core' ) . '</h2>';
	echo '<p>' . esc_html__( 'รายงานส่งทุกวันจันทร์ 9 โมงเช้า และส่งทันทีเมื่อ SSL เหลือไม่เกิน 14 วันหรือพบช่องโหว่ (ตรวจวันละครั้ง)', 'hri-core' ) . '</p>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'hri_health' );
	echo '<table class="form-table" role="presentation">';
	echo '<tr><th scope="row"><label for="hri-health-emails">' . esc_html__( 'อีเมลผู้รับ', 'hri-core' ) . '</label></th><td><input type="text" class="regular-text" id="hri-health-emails" name="' . esc_attr( HRI_HEALTH_OPTION ) . '[emails]" value="' . esc_attr( $opts['emails'] ) . '" aria-describedby="hri-health-emails-help" /><p class="description" id="hri-health-emails-help">'
		/* translators: %s: admin email */
		. esc_html( sprintf( __( 'คั่นด้วยจุลภาค ว่างไว้ = ส่งที่ %s', 'hri-core' ), get_option( 'admin_email' ) ) ) . '</p></td></tr>';
	echo '<tr><th scope="row"><label for="hri-health-status">' . esc_html__( 'ลิงก์หน้า status (Better Stack)', 'hri-core' ) . '</label></th><td><input type="url" class="regular-text" id="hri-health-status" name="' . esc_attr( HRI_HEALTH_OPTION ) . '[status_url]" value="' . esc_attr( $opts['status_url'] ) . '" /></td></tr>';
	echo '</table>';
	submit_button();
	echo '</form>';

	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	wp_nonce_field( 'hri_health_test' );
	echo '<input type="hidden" name="action" value="hri_health_test" />';
	submit_button( __( 'ส่งรายงานทดสอบตอนนี้', 'hri-core' ), 'secondary' );
	echo '</form></div>';
}

add_action(
	'wp_dashboard_setup',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		wp_add_dashboard_widget(
			'hri_health',
			__( 'สุขภาพเว็บ', 'hri-core' ),
			static function () {
				echo hri_health_html( hri_health_report_cached() ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in hri_health_html().
				echo '<p><a href="' . esc_url( admin_url( 'tools.php?page=hri-health' ) ) . '">' . esc_html__( 'ตั้งค่าการแจ้งเตือน', 'hri-core' ) . '</a></p>';
			}
		);
	}
);
