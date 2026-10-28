<?php
/**
 * 301 redirects from URLs of the old hrideone.com (Elementor + WPML) to the new site (task E).
 *
 * Rules:
 * - Only when the request is a 404 (a page the team creates later at the same URL wins).
 * - One hop, to the page of the visitor's language (Polylang free): the table holds the *Thai* target
 *   path and `hri_localize_url()` turns it into the EN / JA translation (/x, /en/x and /ja/x all work).
 * - Never hardcode a full URL (EN / JA slugs end in -en / -ja).
 * - The table is data/redirects.json; add or change entries there or with the `hri_redirect_map` filter.
 * - The query string of the old URL (?utm_…) is kept.
 *
 * Target values:
 * - '/path/?query#anchor' : Thai path of the new site (query and anchor are kept)
 * - 'external:<course slug>' : the external URL (`hri_external_url`) of that course (TEN X)
 * - '#' : placeholder, not decided yet — the entry is skipped and the URL stays a 404
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old path (no leading / trailing slash, no language prefix) => target. The table lives in
 * data/redirects.json (shared with scripts/wxr-to-blog.py); see the file header for the values.
 *
 * @return array<string,string>
 */
function hri_redirect_map(): array {
	$json = file_get_contents( HRI_CORE_DIR . 'data/redirects.json' );
	$doc  = is_string( $json ) ? json_decode( $json, true ) : null;
	$map  = is_array( $doc ) && is_array( $doc['map'] ?? null ) ? $doc['map'] : array();

	/**
	 * Filter the old-URL redirect table.
	 *
	 * @param array<string,string> $map Old path => target.
	 */
	return (array) apply_filters( 'hri_redirect_map', $map );
}

/**
 * Normalize a request path: decoded, lower-cased, no slashes at the ends, no language prefix.
 */
function hri_redirect_normalize( string $request ): string {
	$path = strtolower( trim( rawurldecode( $request ), '/' ) );
	if ( preg_match( '#^(?:en|ja)(?:/(.*))?$#', $path, $m ) ) {
		$path = $m[1] ?? '';
	}
	return $path;
}

/**
 * Full URL for a target of the table, or '' when there is none yet.
 */
function hri_redirect_target( string $target ): string {
	if ( '' === $target || '#' === $target ) {
		return '';
	}

	if ( str_starts_with( $target, 'external:' ) ) {
		$courses = get_posts(
			array(
				'post_type'   => 'course',
				'name'        => sanitize_title( substr( $target, 9 ) ),
				'post_status' => 'publish',
				'numberposts' => 1,
				'fields'      => 'ids',
				'lang'        => '',
			)
		);
		return $courses ? (string) get_post_meta( (int) $courses[0], 'hri_external_url', true ) : '';
	}

	if ( function_exists( 'hri_localize_url' ) ) {
		$url = hri_localize_url( $target );
	} else {
		$url = $target;
	}
	return str_starts_with( $url, '/' ) ? untrailingslashit( home_url() ) . $url : $url;
}

add_action(
	'template_redirect',
	static function () {
		global $wp;
		if ( ! is_404() || is_admin() || empty( $wp->request ) ) {
			return;
		}

		$map  = hri_redirect_map();
		$path = hri_redirect_normalize( (string) $wp->request );
		if ( ! isset( $map[ $path ] ) ) {
			return;
		}

		$url = hri_redirect_target( (string) $map[ $path ] );
		if ( '' === $url ) {
			return;
		}

		// Keep the query of the old URL (?utm_source=… for analytics); the target's own query comes first.
		$query = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_QUERY ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		if ( '' !== $query ) {
			$hash = '';
			if ( false !== ( $pos = strpos( $url, '#' ) ) ) { // phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition
				$hash = substr( $url, $pos );
				$url  = substr( $url, 0, $pos );
			}
			$url .= ( str_contains( $url, '?' ) ? '&' : '?' ) . $query . $hash;
		}

		// An external target (TEN X) is allowed; everything else stays on this site.
		wp_redirect( $url, 301, 'HRI-Core' ); // phpcs:ignore WordPress.Security.SafeRedirect -- targets come from the table above.
		exit;
	},
	1
);

/**
 * Old WPML URLs of EN / JA pages: /en/in-house/, /ja/public-training/, /en/courses/negotiation-mastery/ …
 * WPML kept the Thai slug in every language; Polylang free cannot (a slug is unique), so the EN / JA page is
 * "<slug>-en" / "<slug>-ja". Without this, Polylang sends /en/in-house/ to the Thai page (wrong language).
 * Runs when nothing of that language owns the slug: look for the Thai page and go to its translation.
 */
add_action(
	'parse_request',
	static function ( WP $wp ) {
		if ( ! function_exists( 'pll_get_post' ) || ! preg_match( '#^(en|ja)/(?:courses/)?([^/]+)/?$#u', rawurldecode( (string) $wp->request ), $m ) ) {
			return;
		}
		list( , $lang, $slug ) = $m;
		$slug  = sanitize_title( $slug );
		$types = array( 'page', 'course' );

		$all = get_posts( array( 'post_type' => $types, 'name' => $slug, 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids', 'lang' => '' ) );
		if ( ! $all ) {
			return;
		}
		foreach ( $all as $id ) {
			if ( pll_get_post_language( $id ) === $lang ) {
				return; // A real page of that language: not an old URL.
			}
		}
		$thai = array_values( array_filter( $all, static fn( $id ) => pll_get_post_language( $id ) === pll_default_language() ) );
		$to   = $thai ? (int) pll_get_post( (int) $thai[0], $lang ) : 0;
		if ( ! $to ) {
			return;
		}

		$query = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_QUERY ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		wp_safe_redirect( (string) get_permalink( $to ) . ( '' !== $query ? '?' . $query : '' ), 301 );
		exit;
	},
	2
);
