<?php
/**
 * After the plugin files change (FTP deploy from GitHub, or a zip uploaded over the old one),
 * bring the database up to the new version on the first request — no click on Tools →
 * ตั้งค่าเว็บเริ่มต้น needed.
 *
 * Only steps that never touch content people may have edited run here: saved image sizes,
 * SEO settings, rewrite rules, and the one-off migrations listed in hri_core_migrations().
 * Pages, courses, translations and articles are not re-seeded (that would overwrite edits made
 * in the editor); for that, run the Tools page by hand.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * One-off data changes, keyed by the plugin version that needs them. Each runs once, on the
 * first request after the site moves from an older version to that one or later. Write them so a
 * second run does no harm, and never overwrite text an editor may have changed.
 *
 * @return array<string,callable>
 */
function hri_core_migrations(): array {
	return array(
		// 'x.y.z' => static function () { … },
	);
}

/**
 * Run what a version change needs. Returns one line per thing done.
 *
 * @param string $from Version the database was at ('' = never recorded).
 * @return string[]
 */
function hri_core_upgrade( string $from ): array {
	$done = array();

	foreach ( hri_core_migrations() as $version => $migration ) {
		// A first install ('') gets its data from the Tools page instead.
		if ( '' !== $from && version_compare( $from, $version, '<' ) && version_compare( $version, HRI_CORE_VERSION, '<=' ) ) {
			$migration();
			$done[] = "migration {$version}";
		}
	}

	// Image sizes come from the build (setup/data/image-dims.json), not from editors.
	$dir  = function_exists( 'hri_setup_dir' ) ? hri_setup_dir() : '';
	$file = $dir ? $dir . 'data/image-dims.json' : '';
	if ( $file && is_readable( $file ) ) {
		$dims = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $dims ) ) {
			update_option( 'hri_image_dims', $dims, false );
			$done[] = 'image sizes';
		}
	}

	// Only the keys hri-core owns; does nothing without The SEO Framework.
	if ( function_exists( 'hri_seo_setup' ) && function_exists( 'hri_has_tsf' ) && hri_has_tsf() ) {
		hri_seo_setup();
		$done[] = 'SEO settings';
	}

	flush_rewrite_rules( false );
	$done[] = 'permalinks';

	return $done;
}

add_action(
	'init',
	static function () {
		$from = (string) get_option( 'hri_core_version', '' );
		if ( HRI_CORE_VERSION === $from ) {
			return;
		}
		// Only the request that adds the lock runs it; it expires in case that request dies.
		$lock = 'hri_core_upgrading';
		if ( ! add_option( $lock, time(), '', false ) ) {
			if ( time() - (int) get_option( $lock ) < 120 ) {
				return;
			}
			update_option( $lock, time(), false );
		}
		$done = hri_core_upgrade( $from );
		update_option( 'hri_core_version', HRI_CORE_VERSION );
		update_option(
			'hri_core_upgrade_log',
			array(
				'from' => $from,
				'to'   => HRI_CORE_VERSION,
				'time' => time(),
				'done' => $done,
			),
			false
		);
		delete_option( $lock );
	},
	99 // After post types, taxonomies and Polylang are registered.
);

// Tell admins once what changed, and when re-seeding content is still a manual step.
add_action(
	'admin_notices',
	static function () {
		$log = get_option( 'hri_core_upgrade_log' );
		if ( ! is_array( $log ) || ! current_user_can( 'manage_options' ) || get_user_meta( get_current_user_id(), 'hri_core_upgrade_seen', true ) === $log['to'] ) {
			return;
		}
		update_user_meta( get_current_user_id(), 'hri_core_upgrade_seen', $log['to'] );
		printf(
			'<div class="notice notice-info is-dismissible"><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: 1: old version, 2: new version, 3: list of steps */
					__( 'HRI Core อัปเดตจาก %1$s เป็น %2$s แล้ว (%3$s) · เนื้อหาหน้าและคอร์สไม่ถูกแตะ ถ้าต้องการข้อความตั้งต้นชุดใหม่ ให้รัน Tools → ตั้งค่าเว็บเริ่มต้น เอง', 'hri-core' ),
					$log['from'] ? $log['from'] : '—',
					$log['to'],
					implode( ', ', (array) $log['done'] )
				)
			)
		);
	}
);
