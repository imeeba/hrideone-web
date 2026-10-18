<?php
/**
 * Language list for the language switcher.
 *
 * Uses Polylang when it is active. Without it (before the translation setup, or in a
 * preview) a static TH / EN / 日本語 list is returned so the header layout can be reviewed;
 * only the current language is a link there.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Languages to show: [ [ 'code' => 'th', 'label' => 'TH', 'name' => 'ไทย', 'url' => '…', 'current' => bool ], … ]
 *
 * @return array<int,array{code:string,label:string,name:string,url:string,current:bool,lang:string}>
 */
function hri_language_list(): array {
	$labels = array(
		'th' => array( 'TH', 'ไทย', 'th' ),
		'en' => array( 'EN', 'English', 'en' ),
		'ja' => array( '日本語', '日本語', 'ja' ),
	);

	if ( function_exists( 'pll_the_languages' ) ) {
		$raw = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
		$out = array();
		foreach ( (array) $raw as $code => $lang ) {
			$short = strtolower( substr( (string) $code, 0, 2 ) );
			$out[] = array(
				'code'    => $short,
				'label'   => $labels[ $short ][0] ?? strtoupper( $short ),
				'name'    => $labels[ $short ][1] ?? (string) ( $lang['name'] ?? $code ),
				'url'     => (string) ( $lang['url'] ?? '' ),
				'current' => ! empty( $lang['current_lang'] ),
				'lang'    => (string) ( $lang['locale'] ?? $short ),
			);
		}
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
			'label'   => $label[0],
			'name'    => $label[1],
			'url'     => $is_current ? home_url( '/' ) : '',
			'current' => $is_current,
			'lang'    => $label[2],
		);
	}
	return $out;
}
