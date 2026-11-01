<?php
/**
 * Hardening that does not depend on the host or on another plugin.
 *
 *   - The theme / plugin file editor in wp-admin is off (a stolen admin login cannot edit PHP from the browser).
 *   - Usernames are not published: /wp-json/wp/v2/users, ?author=N, author archives and the oEmbed author
 *     are closed to visitors who are not logged in (the login name is half of what a password guesser needs).
 *   - Browser security headers on every front-end page.
 *   - HSTS only when HRI_HSTS is defined in wp-config.php (see docs/SECURITY.md: a lapsed certificate with HSTS
 *     on makes the site unreachable, so it is switched on only once SSL renewal is known to work).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Whether user names may be shown to the current request.
 */
function hri_security_can_see_users(): bool {
	return is_user_logged_in();
}

// REST: the block editor (logged in) still lists authors; anonymous visitors get 404.
add_filter(
	'rest_endpoints',
	static function ( array $endpoints ): array {
		if ( hri_security_can_see_users() ) {
			return $endpoints;
		}
		foreach ( array_keys( $endpoints ) as $route ) {
			if ( str_starts_with( $route, '/wp/v2/users' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
		return $endpoints;
	}
);

// ?author=1 → /author/<login>/ reveals the login name; author archives are not used by the theme.
add_action(
	'template_redirect',
	static function () {
		if ( hri_security_can_see_users() ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification -- read-only check.
		if ( is_author() || isset( $_GET['author'] ) || isset( $_GET['author_name'] ) ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
		}
	},
	0
);

// Core sitemap of users and the author in oEmbed answers.
add_filter( 'wp_sitemaps_add_provider', static fn( $provider, $name ) => 'users' === $name ? false : $provider, 10, 2 );
add_filter(
	'oembed_response_data',
	static function ( array $data ): array {
		unset( $data['author_name'], $data['author_url'] );
		return $data;
	}
);

// Browser security headers (front end; wp-admin and the login page already send their own).
add_action(
	'send_headers',
	static function () {
		if ( headers_sent() ) {
			return;
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()' );
		if ( defined( 'HRI_HSTS' ) && HRI_HSTS && is_ssl() ) {
			header( 'Strict-Transport-Security: max-age=31536000' );
		}
	}
);
