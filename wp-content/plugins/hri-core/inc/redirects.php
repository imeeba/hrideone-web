<?php
/**
 * 301 redirects from URLs of the old hrideone.com (Elementor + WPML) to the new site (task E).
 *
 * Rules:
 * - Only when the request is a 404 (a page the team creates later at the same URL wins).
 * - One hop, to the page of the visitor's language (Polylang free): the table holds the *Thai* target
 *   path and `hri_localize_url()` turns it into the EN / JA translation (/x, /en/x and /ja/x all work).
 * - Never hardcode a full URL (EN / JA slugs end in -en / -ja).
 * - Add or change entries with the `hri_redirect_map` filter.
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
 * Old path (no leading / trailing slash, no language prefix) => target. See the file header.
 *
 * @return array<string,string>
 */
function hri_redirect_map(): array {
	$map = array(
		// Renamed courses (the old site has the same 301s itself).
		'courses/negotiation-skills'                => '/courses/negotiation-masterclass/',
		'courses/time-and-priority-management'      => '/courses/time-and-priority-mastery/',
		'courses/master-of-communication'           => '/courses/communication-mastery/',
		'courses/growth-mindset'                    => '/courses/growth-mindset-mastery/',
		'courses/leading-high-performance-teams'    => '/courses/high-performance-leadership-mastery/',

		// Waiting for Jimmi to check them on the old site (it answered "Blocked" to Claude): no redirect yet.
		'courses/embracing-change'                  => '#', // planned: /courses/change-transformation-mastery/
		'public-training/on-class/professional-brand-new-manager' => '#', // planned: /courses/the-new-manager/

		// Courses that no longer exist.
		'courses/personality-insights-by-disc'      => '/in-house/#courses',
		'courses/the-legendary-team'                => '/in-house/#courses',
		'courses/team-engagement-building'          => '/in-house/#courses',
		'courses/logical-and-critical-thinking'     => '/in-house/#courses',
		'courses/effective-cross-functional-communication' => '/in-house/',

		// TEN X lives on its own site.
		'special-project/ten-x'                     => 'external:ten-x',

		// Sales Master / E-Learning are not migrated.
		'special-project/the-sales-master'          => '/public-training/',
		'public-training/the-sales-master'          => '/public-training/',
		'public-training/e-learning'                => '/in-house/',

		// Coaching / consulting.
		'coaching/executive-coaching'               => '/contact-us/',
		'coaching/leadership-coaching'              => '/contact-us/',
		'coaching'                                  => '/',
		'consulting'                                => '/transformation-solutions/',

		// Public courses used to live under /public-training/.
		'public-training/negotiation-mastery'       => '/courses/negotiation-mastery/',
		'public-training/the-next-director'         => '/courses/the-next-director/',
		'public-training/oxygen-leadership'         => '/courses/oxygen-leadership/',
		'public-training/lean-transformation-in-7-weeks' => '/courses/lean-transformation-in-7-weeks/',
		'public-training/professional-brand-new-manager' => '/courses/the-new-manager/',

		// In-house sub pages → filtered list / sections of the single page.
		'in-house/leadership-development'           => '/in-house/?pillar[]=leadership#courses',
		'in-house/business-development'             => '/in-house/?pillar[]=business#courses',
		'in-house/team-simulation'                  => '/in-house/',
		'transformation-solutions/business-strategy'   => '/transformation-solutions/#business-strategy',
		'transformation-solutions/technology-strategy' => '/transformation-solutions/#technology-strategy',
		'transformation-solutions/people-strategy'     => '/transformation-solutions/#people-strategy',
		'transformation-solutions/culture-strategy'    => '/transformation-solutions/#culture-strategy',
		'japanese-concept/do-wa-kei'                => '/japanese-concept/#do-wa-kei',
	);

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

		// An external target (TEN X) is allowed; everything else stays on this site.
		wp_redirect( $url, 301, 'HRI-Core' ); // phpcs:ignore WordPress.Security.SafeRedirect -- targets come from the table above.
		exit;
	},
	1
);
