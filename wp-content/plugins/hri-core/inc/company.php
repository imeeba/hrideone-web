<?php
/**
 * Company information: one place for contact details, social links, the footer tagline and
 * the call-to-action band. Edited in Settings → ข้อมูลบริษัท; read with hri_company().
 *
 * Used by the footer, the contact blocks and (later) the Contact page and SEO schema, so a
 * phone number or address is changed once for the whole site.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_COMPANY_OPTION = 'hri_company';

/**
 * Fields: key => [ label, type, per-language ]. Per-language fields are stored as key_th,
 * key_en and key_ja.
 *
 * @return array<string,array{0:string,1:string,2:bool}>
 */
function hri_company_fields(): array {
	return array(
		'phone'        => array( __( 'เบอร์โทร', 'hri-core' ), 'text', false ),
		'email'        => array( __( 'อีเมล', 'hri-core' ), 'email', false ),
		'line_url'     => array( 'LINE (URL)', 'url', false ),
		'facebook_url' => array( 'Facebook (URL)', 'url', false ),
		'youtube_url'  => array( 'YouTube (URL)', 'url', false ),
		'linkedin_url' => array( 'LinkedIn (URL)', 'url', false ),
		'address'      => array( __( 'ที่อยู่', 'hri-core' ), 'textarea', true ),
		'tagline'      => array( __( 'ข้อความใต้โลโก้ (footer)', 'hri-core' ), 'textarea', true ),
		'cta_title'    => array( __( 'หัวข้อแถบชวนติดต่อ', 'hri-core' ), 'text', true ),
		'cta_text'     => array( __( 'ข้อความแถบชวนติดต่อ', 'hri-core' ), 'textarea', true ),
	);
}

/**
 * Values used until the settings are saved (from the current hrideone.com).
 * The CTA texts are samples.
 *
 * @return array<string,string>
 */
function hri_company_defaults(): array {
	return array(
		'phone'        => '065-097-9444',
		'email'        => 'info@hrideone.com',
		'line_url'     => 'https://page.line.me/hrideone',
		'facebook_url' => 'https://www.facebook.com/HRIDeOne',
		'youtube_url'  => 'https://www.youtube.com/@HRIDEONE',
		'linkedin_url' => '',
		'address_th'   => "HRI DEONE CO., LTD.\n118 บล็อก 28X ชั้น 4 ยูนิต X406 ซอยจุฬาลงกรณ์ 5\nแขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330",
		'address_en'   => "HRI DEONE CO., LTD.\n118 Block 28X, 4th Floor, Unit X406, Soi Chula 5,\nWangmai, Pathumwan, Bangkok 10330, Thailand",
		'address_ja'   => "HRI DEONE CO., LTD.\n118 Block 28X, 4th Floor, Unit X406, Soi Chula 5,\nWangmai, Pathumwan, Bangkok 10330, Thailand",
		'tagline_th'   => 'พันธมิตรที่ไว้วางใจได้ ในการสร้างองค์กรที่เติบโตอย่างยั่งยืนจากรุ่นสู่รุ่น',
		'tagline_en'   => 'A trusted partner in shaping organizations that thrive across generations.',
		'tagline_ja'   => '世代を超えて成長し続ける組織づくりの、信頼できるパートナー。',
		'cta_title_th' => 'พร้อมพัฒนาทีมของคุณแล้วหรือยัง',
		'cta_title_en' => 'Ready to develop your team?',
		'cta_title_ja' => 'チームの成長を、一緒に考えませんか。',
		'cta_text_th'  => 'เล่าโจทย์ขององค์กรให้เราฟัง แล้วเราจะช่วยออกแบบหลักสูตรที่เหมาะกับทีมของคุณ (ข้อความตัวอย่าง)',
		'cta_text_en'  => 'Tell us what your organization needs, and we will help you design the right program for your team. (sample text)',
		'cta_text_ja'  => '貴社の課題をお聞かせください。チームに合ったプログラムをご提案します。（サンプル文）',
	);
}

/**
 * Stored keys: plain keys, and key_{lang} for per-language fields.
 *
 * @return string[]
 */
function hri_company_keys(): array {
	$keys = array();
	foreach ( hri_company_fields() as $key => $field ) {
		if ( $field[2] ) {
			foreach ( array_keys( hri_languages() ) as $lang ) {
				$keys[] = "{$key}_{$lang}";
			}
		} else {
			$keys[] = $key;
		}
	}
	return $keys;
}

/**
 * A company value. Per-language fields return the visitor's language, falling back to Thai.
 *
 * @param string $key  Field key from hri_company_fields() (e.g. 'phone', 'address').
 * @param string $lang Language code; '' = current language.
 */
function hri_company( string $key, string $lang = '' ): string {
	static $values = null;
	if ( null === $values ) {
		$saved  = get_option( HRI_COMPANY_OPTION, array() );
		$values = array_merge( hri_company_defaults(), is_array( $saved ) ? $saved : array() );
	}

	$fields = hri_company_fields();
	if ( empty( $fields[ $key ][2] ) ) {
		return (string) ( $values[ $key ] ?? '' );
	}

	$lang  = '' !== $lang ? $lang : hri_current_language();
	$value = (string) ( $values[ "{$key}_{$lang}" ] ?? '' );
	return '' !== $value ? $value : (string) ( $values[ "{$key}_th" ] ?? '' );
}

/**
 * Phone number for a tel: link: "065-097-9444" → "+66650979444".
 */
function hri_phone_href( string $phone ): string {
	$digits = preg_replace( '/[^\d+]/', '', $phone );
	if ( str_starts_with( (string) $digits, '0' ) ) {
		$digits = '+66' . substr( $digits, 1 );
	}
	return 'tel:' . $digits;
}

/**
 * Settings page.
 */
add_action(
	'admin_init',
	static function () {
		register_setting(
			'hri_company',
			HRI_COMPANY_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'hri_company_sanitize',
				'default'           => array(),
			)
		);
	}
);

/**
 * @param mixed $input
 * @return array<string,string>
 */
function hri_company_sanitize( $input ): array {
	$input  = is_array( $input ) ? $input : array();
	$fields = hri_company_fields();
	$out    = array();
	foreach ( hri_company_keys() as $stored ) {
		$base  = preg_replace( '/_(th|en|ja)$/', '', $stored );
		$type  = $fields[ $base ][1] ?? ( $fields[ $stored ][1] ?? 'text' );
		$value = isset( $input[ $stored ] ) && is_string( $input[ $stored ] ) ? wp_unslash( $input[ $stored ] ) : '';
		switch ( $type ) {
			case 'email':
				$value = sanitize_email( $value );
				break;
			case 'url':
				$value = esc_url_raw( trim( $value ), array( 'https', 'http' ) );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $value );
				break;
			default:
				$value = sanitize_text_field( $value );
		}
		$out[ $stored ] = $value;
	}
	return $out;
}

add_action(
	'admin_menu',
	static function () {
		add_options_page( __( 'ข้อมูลบริษัท', 'hri-core' ), __( 'ข้อมูลบริษัท', 'hri-core' ), 'manage_options', 'hri-company', 'hri_company_page' );
	}
);

function hri_company_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$saved     = get_option( HRI_COMPANY_OPTION, array() );
	$values    = array_merge( hri_company_defaults(), is_array( $saved ) ? $saved : array() );
	$languages = hri_languages();

	echo '<div class="wrap"><h1>' . esc_html__( 'ข้อมูลบริษัท', 'hri-core' ) . '</h1>';
	echo '<p>' . esc_html__( 'ใช้ใน footer และส่วนอื่นของเว็บ แก้ที่นี่ที่เดียว ช่องลิงก์ที่เว้นว่างจะไม่แสดงบนเว็บ ข้อความภาษา EN / JA ที่เว้นว่างจะใช้ภาษาไทยแทน', 'hri-core' ) . '</p>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'hri_company' );
	echo '<table class="form-table" role="presentation"><tbody>';

	foreach ( hri_company_fields() as $key => $field ) {
		list( $label, $type, $per_lang ) = $field;
		$rows = $per_lang ? array_keys( $languages ) : array( '' );
		foreach ( $rows as $lang ) {
			$stored = $lang ? "{$key}_{$lang}" : $key;
			$id     = 'hri_company_' . $stored;
			$name   = HRI_COMPANY_OPTION . '[' . $stored . ']';
			$value  = (string) ( $values[ $stored ] ?? '' );
			$title  = $lang ? $label . ' · ' . $languages[ $lang ]['label'] : $label;

			if ( 'textarea' === $type ) {
				$input = sprintf( '<textarea id="%1$s" name="%2$s" rows="3" class="large-text">%3$s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( $value ) );
			} else {
				$input = sprintf( '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text">', esc_attr( 'url' === $type ? 'url' : ( 'email' === $type ? 'email' : 'text' ) ), esc_attr( $id ), esc_attr( $name ), esc_attr( $value ) );
			}
			printf( '<tr><th scope="row"><label for="%1$s">%2$s</label></th><td>%3$s</td></tr>', esc_attr( $id ), esc_html( $title ), $input ); // phpcs:ignore WordPress.Security.EscapeOutput -- built with esc_*() above.
		}
	}

	echo '</tbody></table>';
	submit_button();
	echo '</form></div>';
}

/**
 * Contact page URL in the visitor's language.
 */
function hri_contact_url(): string {
	$url = function_exists( 'hri_localize_url' ) ? hri_localize_url( '/contact-us/' ) : '/contact-us/';
	return str_starts_with( $url, '/' ) ? home_url( $url ) : $url;
}

/**
 * Whether this is the Contact page (or its translation).
 */
function hri_is_contact_page(): bool {
	if ( is_page( 'contact-us' ) ) {
		return true;
	}
	if ( ! is_page() || ! function_exists( 'pll_get_post' ) || ! function_exists( 'pll_default_language' ) ) {
		return false;
	}
	$source = pll_get_post( get_queried_object_id(), pll_default_language() );
	return $source && 'contact-us' === get_post_field( 'post_name', $source );
}
