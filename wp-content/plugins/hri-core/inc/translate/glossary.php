<?php
/**
 * Glossary and tone for machine translation.
 *
 * Entries come from three places (a later one wins):
 *  1. built-in terms (brand and program names that stay as they are),
 *  2. titles of courses and trainers that already have a translation (so a course is called the same
 *     in every article and page),
 *  3. the table in Settings → การแปล (option hri_tr_glossary: one line "ไทย | English | 日本語";
 *     an empty column = keep the Thai/Latin term as written).
 * Only entries that occur in the texts being translated are sent (saves tokens).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tone instruction per language (editable in Settings → การแปล).
 *
 * @return array<string,string>
 */
function hri_tr_default_tone(): array {
	return array(
		'en' => 'Write natural, concise, professional B2B English. Prefer active voice and plain words over jargon; do not translate word for word.',
		'ja' => 'ビジネス向けの丁寧な「です・ます」調の自然な日本語に訳してください。直訳は避け、経営層・人事責任者が読む文章にふさわしい表現にしてください。',
	);
}

/**
 * Terms that are never translated.
 *
 * @return array<string,array<string,string>> Thai/Latin term => array( en => , ja => ) (empty string = keep).
 */
function hri_tr_builtin_glossary(): array {
	$keep = array( 'en' => '', 'ja' => '' );
	return (array) apply_filters(
		'hri_translate_builtin_glossary',
		array(
			'HRI DeOne'        => $keep,
			'เอช อาร์ ไอ ดีวัน' => array( 'en' => 'HRI DeOne', 'ja' => 'HRI DeOne' ),
			'In-house'         => $keep,
			'Public Training'  => $keep,
			'DO·WA·KEI'        => $keep,
			'Early Bird'       => array( 'en' => 'Early Bird', 'ja' => 'アーリーバード' ),
			'ใบประกาศนียบัตร'  => array( 'en' => 'certificate', 'ja' => '修了証' ),
			'หลักสูตร'         => array( 'en' => 'course', 'ja' => 'コース' ),
			'ผู้บริหาร'         => array( 'en' => 'executives', 'ja' => '経営層' ),
		)
	);
}

/**
 * Table saved in Settings: lines "thai | en | ja".
 *
 * @return array<string,array<string,string>>
 */
function hri_tr_saved_glossary(): array {
	$out = array();
	foreach ( preg_split( '/\R/u', (string) get_option( 'hri_tr_glossary', '' ) ) ?: array() as $line ) {
		$cols = array_map( 'trim', explode( '|', (string) $line ) );
		if ( '' === ( $cols[0] ?? '' ) ) {
			continue;
		}
		$out[ $cols[0] ] = array( 'en' => $cols[1] ?? '', 'ja' => $cols[2] ?? '' );
	}
	return $out;
}

/**
 * Titles of courses and trainers that have translations: Thai title => title in each language.
 *
 * @return array<string,array<string,string>>
 */
function hri_tr_post_title_glossary(): array {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	if ( ! function_exists( 'pll_get_post' ) || ! function_exists( 'pll_default_language' ) ) {
		return $cache;
	}
	$posts = get_posts(
		array(
			'post_type'        => array( 'course', 'trainer' ),
			'post_status'      => 'publish',
			'numberposts'      => 300,
			'lang'             => (string) pll_default_language(),
			'suppress_filters' => false,
		)
	);
	foreach ( $posts as $post ) {
		$title = trim( wp_strip_all_tags( $post->post_title ) );
		if ( '' === $title ) {
			continue;
		}
		$row = array( 'en' => '', 'ja' => '' );
		foreach ( array_keys( $row ) as $lang ) {
			$id = (int) pll_get_post( $post->ID, $lang );
			if ( $id ) {
				$row[ $lang ] = trim( wp_strip_all_tags( get_the_title( $id ) ) );
			}
		}
		// A title that is the same in all languages is a name to keep.
		$cache[ $title ] = $row;
	}
	return $cache;
}

/**
 * Glossary entries for a language that occur in the given texts.
 *
 * @param string[] $texts Source strings.
 * @param string   $lang  en | ja.
 * @return array<string,string> Thai term => required translation ('' = keep).
 */
function hri_tr_glossary_for( array $texts, string $lang ): array {
	$haystack = implode( "\n", array_map( 'wp_strip_all_tags', $texts ) );
	$all      = array_merge( hri_tr_builtin_glossary(), hri_tr_post_title_glossary(), hri_tr_saved_glossary() );
	$out      = array();
	foreach ( $all as $term => $translations ) {
		if ( '' !== $term && false !== mb_stripos( $haystack, (string) $term ) ) {
			$target = (string) ( $translations[ $lang ] ?? '' );
			// An untranslated course/trainer title: leave it to the translator rather than force "keep".
			if ( '' === $target && isset( hri_tr_post_title_glossary()[ $term ] ) && ! isset( hri_tr_saved_glossary()[ $term ] ) ) {
				continue;
			}
			$out[ (string) $term ] = $target;
		}
	}
	return $out;
}

/**
 * Tone for a language: the saved one, else the default.
 */
function hri_tr_tone( string $lang ): string {
	$settings = hri_tr_settings();
	$saved    = trim( (string) ( $settings['tone'][ $lang ] ?? '' ) );
	return '' !== $saved ? $saved : ( hri_tr_default_tone()[ $lang ] ?? '' );
}
