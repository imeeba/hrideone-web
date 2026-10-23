<?php
/**
 * Settings → การค้นหา: synonym list (editable by marketing), index status, rebuild, test box.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	static function () {
		add_options_page( __( 'การค้นหา', 'hri-core' ), __( 'การค้นหา', 'hri-core' ), 'manage_options', 'hri-search', 'hri_search_admin_page' );
	}
);

add_action(
	'admin_init',
	static function () {
		register_setting(
			'hri_search',
			HRI_SEARCH_OPTION_SYN,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'hri_search_sanitize_synonyms',
				'default'           => '',
			)
		);
	}
);

/**
 * One group per line, words separated by "=": plain text only, at most 200 groups of 20 words.
 *
 * @param mixed $value Textarea content.
 */
function hri_search_sanitize_synonyms( $value ): string {
	$lines = preg_split( '/\R/u', is_string( $value ) ? wp_unslash( $value ) : '' ) ?: array();
	$out   = array();
	foreach ( array_slice( $lines, 0, 400 ) as $line ) {
		$words = array();
		foreach ( explode( '=', (string) $line ) as $word ) {
			$word = trim( preg_replace( '/\s+/u', ' ', sanitize_text_field( $word ) ) ?? '' );
			if ( '' !== $word ) {
				$words[] = mb_substr( $word, 0, 60, 'UTF-8' );
			}
		}
		if ( count( $words ) >= 2 ) {
			$out[] = implode( ' = ', array_slice( array_unique( $words ), 0, 20 ) );
		}
	}
	return implode( "\n", array_slice( $out, 0, 200 ) );
}

/**
 * The page.
 */
function hri_search_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$url = admin_url( 'options-general.php?page=hri-search' );

	// Rebuild, a batch per page load (a meta refresh moves on to the next one: no JavaScript).
	if ( isset( $_GET['rebuild'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- checked below.
		check_admin_referer( 'hri_search_rebuild' );
		$offset = absint( $_GET['rebuild'] ); // phpcs:ignore WordPress.Security.NonceVerification
		$step   = hri_search_rebuild( $offset, 40 );
		echo '<div class="wrap"><h1>' . esc_html__( 'การค้นหา', 'hri-core' ) . '</h1>';
		if ( null !== $step['next'] ) {
			$next = wp_nonce_url( add_query_arg( 'rebuild', $step['next'], $url ), 'hri_search_rebuild' );
			printf( '<meta http-equiv="refresh" content="0;url=%s" />', esc_url( html_entity_decode( $next ) ) );
			echo '<p>' . esc_html( sprintf( /* translators: 1: done, 2: total */ __( 'กำลังสร้างดัชนี… %1$s / %2$s', 'hri-core' ), number_format_i18n( $step['next'] ), number_format_i18n( $step['total'] ) ) ) . '</p>';
			echo '<noscript><p><a href="' . esc_url( $next ) . '">' . esc_html__( 'ทำต่อ', 'hri-core' ) . '</a></p></noscript>';
		} else {
			echo '<div class="notice notice-success"><p>' . esc_html__( 'สร้างดัชนีเสร็จแล้ว', 'hri-core' ) . '</p></div>';
			echo '<p><a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'กลับ', 'hri-core' ) . '</a></p>';
		}
		echo '</div>';
		return;
	}

	// Reset the synonyms to the defaults.
	if ( isset( $_POST['hri_search_reset'] ) ) {
		check_admin_referer( 'hri_search_reset' );
		delete_option( HRI_SEARCH_OPTION_SYN );
		echo '<div class="notice notice-success"><p>' . esc_html__( 'คืนค่าเริ่มต้นแล้ว', 'hri-core' ) . '</p></div>';
	}

	$meta  = get_option( HRI_SEARCH_OPTION_INDEX );
	$meta  = is_array( $meta ) ? $meta : array();
	$test  = isset( $_GET['q'] ) ? hri_search_clean_query( $_GET['q'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
	$lang  = isset( $_GET['l'] ) && is_string( $_GET['l'] ) ? sanitize_key( $_GET['l'] ) : hri_search_current_lang(); // phpcs:ignore WordPress.Security.NonceVerification
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'การค้นหา', 'hri-core' ); ?></h1>

		<h2><?php esc_html_e( 'คำที่มีความหมายเดียวกัน', 'hri-core' ); ?></h2>
		<p><?php esc_html_e( 'หนึ่งบรรทัดต่อหนึ่งกลุ่ม คั่นคำด้วยเครื่องหมาย = เช่น เจรจา = negotiation = 交渉 เมื่อผู้เข้าชมค้นหาคำใดคำหนึ่ง จะเจอเนื้อหาที่มีคำอื่นในกลุ่มด้วย (มีผลทันที ไม่ต้องสร้างดัชนีใหม่)', 'hri-core' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'hri_search' ); ?>
			<textarea name="<?php echo esc_attr( HRI_SEARCH_OPTION_SYN ); ?>" rows="18" class="large-text code" spellcheck="false"><?php echo esc_textarea( hri_search_synonym_text() ); ?></textarea>
			<?php submit_button( __( 'บันทึก', 'hri-core' ), 'primary', 'submit', false ); ?>
		</form>
		<form method="post" style="margin-top:8px">
			<?php wp_nonce_field( 'hri_search_reset' ); ?>
			<button class="button" name="hri_search_reset" value="1"><?php esc_html_e( 'คืนค่าเริ่มต้น', 'hri-core' ); ?></button>
		</form>

		<h2><?php esc_html_e( 'ดัชนีการค้นหา', 'hri-core' ); ?></h2>
		<table class="widefat striped" style="max-width:32rem"><tbody>
			<tr><th><?php esc_html_e( 'วิธีตัดคำ', 'hri-core' ); ?></th><td><?php echo esc_html( 'icu' === ( $meta['mode'] ?? '' ) ? 'ICU (intl)' : ( 'ngram' === ( $meta['mode'] ?? '' ) ? 'n-gram' : '—' ) ); ?></td></tr>
			<tr><th><?php esc_html_e( 'เอกสารในดัชนี', 'hri-core' ); ?></th><td><?php echo esc_html( number_format_i18n( (int) ( $meta['docs'] ?? 0 ) ) ); ?></td></tr>
			<tr><th><?php esc_html_e( 'คำในดัชนี', 'hri-core' ); ?></th><td><?php echo esc_html( number_format_i18n( (int) ( $meta['rows'] ?? 0 ) ) ); ?></td></tr>
			<tr><th><?php esc_html_e( 'สร้างล่าสุด', 'hri-core' ); ?></th><td><?php echo ! empty( $meta['built'] ) ? esc_html( wp_date( 'Y-m-d H:i', (int) $meta['built'] ) ) : '—'; ?></td></tr>
		</tbody></table>
		<p><?php esc_html_e( 'ดัชนีอัปเดตเองเมื่อบันทึกหลักสูตร บทความ หรือหน้า กดสร้างใหม่เมื่อนำเข้าเนื้อหาจำนวนมาก หรือเมื่อผลค้นหาดูไม่ตรง', 'hri-core' ); ?></p>
		<p><a class="button" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'rebuild', 0, $url ), 'hri_search_rebuild' ) ); ?>"><?php esc_html_e( 'สร้างดัชนีใหม่ทั้งหมด', 'hri-core' ); ?></a></p>

		<h2><?php esc_html_e( 'ทดลองค้นหา', 'hri-core' ); ?></h2>
		<form method="get" action="<?php echo esc_url( admin_url( 'options-general.php' ) ); ?>">
			<input type="hidden" name="page" value="hri-search" />
			<input type="text" name="q" value="<?php echo esc_attr( $test ); ?>" class="regular-text" />
			<input type="text" name="l" value="<?php echo esc_attr( $lang ); ?>" size="4" aria-label="<?php esc_attr_e( 'ภาษา (th / en / ja)', 'hri-core' ); ?>" />
			<button class="button"><?php esc_html_e( 'ทดลอง', 'hri-core' ); ?></button>
		</form>
		<?php
		if ( '' !== $test ) {
			$res = hri_search( $test, array( 'lang' => $lang ) );
			echo '<p>' . esc_html( sprintf( /* translators: 1: results, 2: milliseconds */ __( '%1$s รายการ ใน %2$s ms', 'hri-core' ), number_format_i18n( $res['total'] ), number_format_i18n( $res['ms'], 1 ) ) ) . '</p><ul>';
			foreach ( $res['ids'] as $type => $ids ) {
				foreach ( array_slice( $ids, 0, 10 ) as $id ) {
					echo '<li>' . esc_html( $type . ' · ' . get_the_title( $id ) ) . '</li>';
				}
			}
			echo '</ul>';
		}
		?>
	</div>
	<?php
}
