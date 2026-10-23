<?php
/**
 * Blog: listing size, covers kept on the old site (preview sites) and URLs of the old blog.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Articles per page on the blog and category pages: 12 = full rows in the 3 / 2 / 1 column grid.
 */
const HRI_POSTS_PER_PAGE = 12;

add_action(
	'pre_get_posts',
	static function ( WP_Query $query ) {
		if ( ! is_admin() && $query->is_main_query() && ( $query->is_home() || $query->is_category() || $query->is_tag() ) ) {
			$query->set( 'posts_per_page', HRI_POSTS_PER_PAGE );
		}
	}
);

/* -------------------------------------------------------------------------
 * Cover image kept on the old site (import with images=remote, see blog-import.php):
 * meta _hri_thumbnail_url / _hri_thumbnail_alt, used only while the post has no featured image.
 * ---------------------------------------------------------------------- */

/**
 * Remote cover URL of a post without a featured image.
 */
function hri_remote_thumbnail_url( $post ): string {
	$post = get_post( $post );
	if ( ! $post || get_post_thumbnail_id( $post ) ) {
		return '';
	}
	return (string) get_post_meta( $post->ID, '_hri_thumbnail_url', true );
}

add_filter(
	'has_post_thumbnail',
	static function ( $has, $post ) {
		return $has || '' !== hri_remote_thumbnail_url( $post );
	},
	10,
	2
);

add_filter(
	'post_thumbnail_html',
	static function ( $html, $post_id, $thumbnail_id, $size, $attr ) {
		$url = $html ? '' : hri_remote_thumbnail_url( $post_id );
		if ( '' === $url ) {
			return $html;
		}
		$attr = wp_parse_args(
			is_array( $attr ) ? $attr : array(),
			array(
				'alt'      => (string) get_post_meta( $post_id, '_hri_thumbnail_alt', true ),
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
		$attr['class'] = trim( 'attachment-' . ( is_string( $size ) ? $size : 'post-thumbnail' ) . ' size-' . ( is_string( $size ) ? $size : 'post-thumbnail' ) . ' wp-post-image ' . ( $attr['class'] ?? '' ) );
		$out           = '<img src="' . esc_url( $url ) . '"';
		foreach ( $attr as $name => $value ) {
			if ( 'src' !== $name && null !== $value && false !== $value ) {
				$out .= ' ' . esc_attr( $name ) . '="' . esc_attr( (string) $value ) . '"';
			}
		}
		return $out . ' />';
	},
	10,
	5
);

/* -------------------------------------------------------------------------
 * URLs of the old blog (Elementor + WPML) → the new ones (301).
 * Article URLs are unchanged (same slugs; a renamed slug redirects through _wp_old_slug).
 * ---------------------------------------------------------------------- */

/**
 * Posts page (blog) of the current language.
 */
function hri_blog_page_url(): string {
	$page = (int) get_option( 'page_for_posts' );
	if ( $page && function_exists( 'pll_get_post' ) ) {
		$page = (int) pll_get_post( $page ) ? (int) pll_get_post( $page ) : $page;
	}
	return $page ? (string) get_permalink( $page ) : '';
}

add_action(
	'template_redirect',
	static function () {
		// Old pagination ?e-page-<widget id>=N (15 per page) → /page/M/ of the same listing (12 per page).
		foreach ( array_keys( $_GET ) as $key ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only redirect.
			if ( ! preg_match( '/^e-page-[0-9a-f]+$/', (string) $key ) ) {
				continue;
			}
			$old  = max( 1, absint( wp_unslash( $_GET[ $key ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
			$new  = (int) floor( ( ( $old - 1 ) * 15 ) / HRI_POSTS_PER_PAGE ) + 1;
			$path = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '/' ), PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$path = trailingslashit( (string) preg_replace( '#/page/\d+/?$#', '/', $path ) );
			wp_safe_redirect( $new > 1 ? "{$path}page/{$new}/" : $path, 301 );
			exit;
		}

	},
	0
);

/**
 * EN / JA URLs that Polylang would otherwise send to the Thai post or page owning the same slug
 * (runs before Polylang's canonical redirect):
 * - /en/blog/ (and /ja/blog/): the blog page of that language
 * - /en/<slug>/ of an article saved as "<slug>-en" (slug taken by the Thai version)
 */
add_action(
	'parse_request',
	static function ( WP $wp ) {
		// Old category URLs: /en/category/business/ → /en/category/business-en/ · "uncategorized" (all
		// languages, now empty) → the blog page of that language.
		if ( preg_match( '#^(?:(en|ja)/)?category/([^/]+)(?:/page/(\d+))?/?$#u', rawurldecode( (string) $wp->request ), $c ) ) {
			$lang   = '' !== $c[1] ? $c[1] : 'th';
			$slug   = sanitize_title( $c[2] );
			$target = '';
			if ( in_array( $slug, array( 'uncategorized-en-us', 'uncategorized-ja', sanitize_title( 'ไม่มีหมวดหมู่' ) ), true ) ) {
				$page   = (int) get_option( 'page_for_posts' );
				$page   = $page && function_exists( 'pll_get_post' ) && 'th' !== $lang ? (int) pll_get_post( $page, $lang ) : $page;
				$target = $page ? (string) get_permalink( $page ) : '';
			} elseif ( 'th' !== $lang && function_exists( 'pll_get_term_language' ) ) {
				$term = get_term_by( 'slug', $slug, 'category' );
				if ( ! $term || pll_get_term_language( $term->term_id ) !== $lang ) {
					$moved  = get_term_by( 'slug', "{$slug}-{$lang}", 'category' );
					$target = $moved ? (string) get_term_link( $moved ) : '';
				}
			}
			if ( $target && ! is_wp_error( $target ) ) {
				wp_safe_redirect( ! empty( $c[3] ) ? trailingslashit( $target ) . 'page/' . (int) $c[3] . '/' : $target, 301 );
				exit;
			}
			return;
		}

		if ( ! function_exists( 'pll_get_post' ) || ! preg_match( '#^(en|ja)/([^/]+)(?:/page/(\d+))?/?$#u', rawurldecode( (string) $wp->request ), $m ) ) {
			return;
		}
		list( , $lang, $slug ) = $m;
		$target = '';
		if ( 'blog' === $slug ) {
			$page   = (int) pll_get_post( (int) get_option( 'page_for_posts' ), $lang );
			$target = $page && 'blog' !== get_post_field( 'post_name', $page ) ? (string) get_permalink( $page ) : '';
			if ( $target && ! empty( $m[3] ) ) {
				$target = trailingslashit( $target ) . 'page/' . (int) $m[3] . '/';
			}
		} elseif ( empty( $m[3] ) ) {
			// Polylang ignores "lang" in a query by slug: check the language of what owns the slug.
			$own = get_posts( array( 'post_type' => array( 'post', 'page', 'course' ), 'name' => sanitize_title( $slug ), 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids', 'lang' => '' ) );
			$own = array_filter( $own, static fn( $id ) => pll_get_post_language( $id ) === $lang );
			if ( ! $own ) {
				$moved  = get_posts(
					array(
						'post_type'   => 'post',
						'post_status' => 'publish',
						'numberposts' => 1,
						'fields'      => 'ids',
						'lang'        => $lang,
						'meta_key'    => '_wp_old_slug', // phpcs:ignore WordPress.DB.SlowDBQuery -- EN / JA URLs only.
						'meta_value'  => sanitize_title( $slug ), // phpcs:ignore WordPress.DB.SlowDBQuery
					)
				);
				$target = $moved ? (string) get_permalink( $moved[0] ) : '';
			}
		}
		if ( $target ) {
			$query = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_QUERY ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			wp_safe_redirect( $target . ( '' !== $query ? '?' . $query : '' ), 301 );
			exit;
		}
	},
	1
);
