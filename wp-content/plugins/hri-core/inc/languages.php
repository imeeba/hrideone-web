<?php
/**
 * Site languages (TH / EN / JA) and the data the language switcher shows.
 *
 * Uses Polylang when it is active (see polylang.php for the setup). Without it (before the
 * translation setup, or in a preview) a static TH / EN / 日本語 list is returned so the header
 * layout can be reviewed; only the current language is a link there.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * The site's languages, in switcher order. Thai is the default (source) language.
 *
 * label  = what the switcher button shows · name = name in that language
 * locale = WordPress locale Polylang switches to · flag = Polylang flag code (admin only)
 *
 * @return array<string,array{label:string,name:string,locale:string,flag:string}>
 */
function hri_languages(): array {
	return array(
		'th' => array( 'label' => 'TH', 'name' => 'ไทย', 'locale' => 'th', 'flag' => 'th' ),
		'en' => array( 'label' => 'EN', 'name' => 'English', 'locale' => 'en_US', 'flag' => 'us' ),
		'ja' => array( 'label' => '日本語', 'name' => '日本語', 'locale' => 'ja', 'flag' => 'jp' ),
	);
}

/**
 * Whether Polylang is active and has its languages set up.
 */
function hri_has_polylang(): bool {
	return function_exists( 'pll_languages_list' ) && function_exists( 'PLL' ) && PLL() && (bool) pll_languages_list();
}

/**
 * Current language code ('th' without Polylang).
 */
function hri_current_language(): string {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language();
		if ( is_string( $lang ) && '' !== $lang ) {
			return $lang;
		}
	}
	return 'th';
}

/**
 * Filter parameters to carry over when switching language on a course listing.
 * Course taxonomies are shared by all languages (same slugs), so a filtered list stays valid.
 */
function hri_language_switch_query_args(): array {
	if ( ! function_exists( 'hri_filter_groups' ) ) {
		return array();
	}
	$args = array();
	foreach ( array_keys( hri_filter_groups() ) as $key ) {
		$raw = isset( $_GET[ $key ] ) ? (array) wp_unslash( $_GET[ $key ] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
		$raw = array_values( array_filter( array_map( 'sanitize_title', array_filter( $raw, 'is_string' ) ) ) );
		if ( $raw ) {
			$args[ $key ] = $raw;
		}
	}
	if ( isset( $_GET['q'] ) && is_string( $_GET['q'] ) && '' !== $_GET['q'] ) { // phpcs:ignore WordPress.Security.NonceVerification
		$args['q'] = sanitize_text_field( wp_unslash( $_GET['q'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	return $args;
}

/**
 * Query string in the same shape the filter form submits (`pillar[]=a&pillar[]=b&q=…`).
 *
 * @param array<string,string|string[]> $args
 */
function hri_build_filter_query( array $args ): string {
	$parts = array();
	foreach ( $args as $key => $value ) {
		foreach ( (array) $value as $item ) {
			$parts[] = rawurlencode( $key ) . ( is_array( $value ) ? '%5B%5D' : '' ) . '=' . rawurlencode( (string) $item );
		}
	}
	return implode( '&', $parts );
}

/**
 * Languages to show: [ [ 'code' => 'th', 'label' => 'TH', 'name' => 'ไทย', 'url' => '…', 'current' => bool ], … ]
 *
 * With Polylang, `url` is the translation of the current page, or that language's home page
 * when the page has no translation yet (`missing` is then true).
 *
 * @return array<int,array{code:string,label:string,name:string,url:string,current:bool,lang:string,missing:bool}>
 */
function hri_language_list(): array {
	$labels = hri_languages();

	if ( hri_has_polylang() ) {
		$raw   = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0, 'hide_if_no_translation' => 0 ) );
		$keep  = hri_language_switch_query_args();
		$out   = array();
		$order = array_flip( array_keys( $labels ) );

		foreach ( (array) $raw as $code => $lang ) {
			$short   = strtolower( substr( (string) $code, 0, 2 ) );
			$missing = ! empty( $lang['no_translation'] );
			$url     = (string) ( $lang['url'] ?? '' );
			if ( $url && $keep && ! $missing ) {
				$url .= ( str_contains( $url, '?' ) ? '&' : '?' ) . hri_build_filter_query( $keep );
			}
			$out[] = array(
				'code'    => $short,
				'label'   => $labels[ $short ]['label'] ?? strtoupper( $short ),
				'name'    => $labels[ $short ]['name'] ?? (string) ( $lang['name'] ?? $code ),
				'url'     => $url,
				'current' => ! empty( $lang['current_lang'] ),
				'lang'    => str_replace( '_', '-', (string) ( $lang['locale'] ?? $short ) ),
				'missing' => $missing,
			);
		}
		usort( $out, static fn( $a, $b ) => ( $order[ $a['code'] ] ?? 99 ) <=> ( $order[ $b['code'] ] ?? 99 ) );
		if ( $out ) {
			return $out;
		}
	}

	// Fallback: the site's own language is Thai, whatever the admin locale is.
	$out = array();
	foreach ( $labels as $code => $label ) {
		$is_current = 'th' === $code;
		$out[]      = array(
			'code'    => $code,
			'label'   => $label['label'],
			'name'    => $label['name'],
			'url'     => $is_current ? home_url( '/' ) : '',
			'current' => $is_current,
			'lang'    => $code,
			'missing' => false,
		);
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * Course taxonomy names per language.
 *
 * The course taxonomies are NOT translated by Polylang: one set of terms (same slugs) is shared
 * by every language, so filter URLs, the In-house / Public lock and card styling work the same in
 * TH / EN / JA. Each term has optional English and Japanese names (term meta), edited on the
 * term screen; the Thai (default) name is the term name itself.
 * ---------------------------------------------------------------------- */

/**
 * Languages that get a name field on terms (all but the default).
 *
 * @return array<string,string> code => label
 */
function hri_term_name_languages(): array {
	return array(
		'en' => __( 'ชื่อภาษาอังกฤษ (EN)', 'hri-core' ),
		'ja' => __( 'ชื่อภาษาญี่ปุ่น (JA)', 'hri-core' ),
	);
}

add_action(
	'init',
	static function () {
		if ( ! function_exists( 'hri_course_taxonomy_names' ) ) {
			return;
		}
		foreach ( hri_course_taxonomy_names() as $taxonomy ) {
			foreach ( array_keys( hri_term_name_languages() ) as $code ) {
				register_term_meta(
					$taxonomy,
					"hri_name_{$code}",
					array(
						'type'              => 'string',
						'single'            => true,
						'show_in_rest'      => true,
						'sanitize_callback' => 'sanitize_text_field',
					)
				);
			}

			add_action( "{$taxonomy}_add_form_fields", 'hri_term_name_fields_add' );
			add_action( "{$taxonomy}_edit_form_fields", 'hri_term_name_fields_edit' );
			add_action( "created_{$taxonomy}", 'hri_term_name_save' );
			add_action( "edited_{$taxonomy}", 'hri_term_name_save' );
		}
	},
	11
);

function hri_term_name_fields_add(): void {
	wp_nonce_field( 'hri_term_names', 'hri_term_names_nonce' );
	foreach ( hri_term_name_languages() as $code => $label ) {
		printf(
			'<div class="form-field"><label for="hri_name_%1$s">%2$s</label><input type="text" id="hri_name_%1$s" name="hri_name_%1$s" value=""><p>%3$s</p></div>',
			esc_attr( $code ),
			esc_html( $label ),
			esc_html__( 'เว้นว่างได้ ถ้าว่างจะแสดงชื่อหลัก', 'hri-core' )
		);
	}
}

function hri_term_name_fields_edit( WP_Term $term ): void {
	wp_nonce_field( 'hri_term_names', 'hri_term_names_nonce' );
	foreach ( hri_term_name_languages() as $code => $label ) {
		printf(
			'<tr class="form-field"><th scope="row"><label for="hri_name_%1$s">%2$s</label></th><td><input type="text" id="hri_name_%1$s" name="hri_name_%1$s" value="%3$s"><p class="description">%4$s</p></td></tr>',
			esc_attr( $code ),
			esc_html( $label ),
			esc_attr( (string) get_term_meta( $term->term_id, "hri_name_{$code}", true ) ),
			esc_html__( 'เว้นว่างได้ ถ้าว่างจะแสดงชื่อหลัก', 'hri-core' )
		);
	}
}

function hri_term_name_save( int $term_id ): void {
	if ( ! isset( $_POST['hri_term_names_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hri_term_names_nonce'] ) ), 'hri_term_names' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	foreach ( array_keys( hri_term_name_languages() ) as $code ) {
		$key   = "hri_name_{$code}";
		$value = isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' === $value ) {
			delete_term_meta( $term_id, $key );
		} else {
			update_term_meta( $term_id, $key, $value );
		}
	}
}

/**
 * A course term with its name in the visitor's language (front end only).
 * Admin screens and the editor keep the Thai name, so it stays the one that is edited.
 *
 * @param mixed $term
 * @return mixed
 */
function hri_localize_term( $term ) {
	if ( ! $term instanceof WP_Term || is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $term;
	}
	if ( ! in_array( $term->taxonomy, hri_course_taxonomy_names(), true ) ) {
		return $term;
	}
	$lang = hri_current_language();
	if ( ! isset( hri_term_name_languages()[ $lang ] ) ) {
		return $term;
	}
	$name = (string) get_term_meta( $term->term_id, "hri_name_{$lang}", true );
	if ( '' !== $name ) {
		$term       = clone $term; // Do not change a cached object.
		$term->name = $name;
	}
	return $term;
}

// get_term() covers get_the_terms(); get_terms() builds some results without it.
add_filter( 'get_term', 'hri_localize_term' );
add_filter(
	'get_terms',
	static function ( $terms ) {
		return is_array( $terms ) ? array_map( 'hri_localize_term', $terms ) : $terms;
	}
);
