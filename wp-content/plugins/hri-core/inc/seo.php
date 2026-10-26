<?php
/**
 * SEO basics with The SEO Framework (TSF, free plugin): titles, meta descriptions, canonical,
 * Open Graph and the per-language XML sitemaps come from TSF; this module only sets it up for this
 * site and fills the gaps:
 *
 * - hri_seo_setup(): TSF settings this site needs (title "Page | HRI DeOne", sitemap size and colours).
 *   Runs by itself once per HRI_SEO_SETUP_VERSION in wp-admin, from `wp hri seo setup` and the blueprint.
 * - Structured data: TSF's own JSON-LD is switched off for good; hri-core prints one graph (schema.php).
 * - Pages that should not be in search (Style Guide, sample page …): noindex + out of the sitemap.
 * - Courses that live on their own website (`hri_external_url`) are redirects: out of the sitemap.
 * - Social image: the remote cover of the demo / imported posts (`_hri_thumbnail_url`).
 * - hreflang: Polylang prints th / en / ja; we add x-default (= Thai).
 *
 * Everything degrades: without TSF the site still gets x-default, noindex (core wp_robots), JSON-LD,
 * robots.txt rules and llms.txt; only titles/descriptions/sitemaps fall back to WordPress' own.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_SEO_SETUP_VERSION = '1';

/**
 * Whether The SEO Framework is running.
 */
function hri_has_tsf(): bool {
	return function_exists( 'tsf' ) && defined( 'THE_SEO_FRAMEWORK_SITE_OPTIONS' );
}

/**
 * TSF settings this site relies on. Applied over what is saved (other TSF settings are left alone,
 * so the marketing team can still change them in SEO → Settings).
 *
 * @return array<string,mixed>
 */
function hri_seo_tsf_settings(): array {
	return array(
		'title_separator'      => 'pipe',     // "หลักสูตร X | HRI DeOne".
		'title_location'       => 'right',
		'sitemap_query_limit'  => 1000,       // Per language; TSF's default (250) would cut the blog later.
		'sitemaps_modified'    => 1,
		'sitemaps_robots'      => 1,
		'sitemap_color_main'   => '0F7070',   // Token primary-strong.
		'sitemap_color_accent' => '00B0BD',   // Token primary.
		'search_noindex'       => 1,
		'date_noindex'         => 1,
		'author_noindex'       => 1,          // Author archives are not used on this site.
		'ld_json_enabled'      => 0,          // schema.php prints the structured data.
		'robotstxt_block_ai'   => 0,          // AI crawlers are set in Settings → SEO & AI (ai-seo.php).
	);
}

/**
 * Drops TSF's cached sitemaps (every language), so changed settings or noindex pages show up now.
 */
function hri_seo_clear_sitemaps(): void {
	if ( class_exists( '\The_SEO_Framework\Sitemap\Cache' ) ) {
		\The_SEO_Framework\Sitemap\Cache::clear_sitemap_caches();
		if ( method_exists( '\The_SEO_Framework\Sitemap\Cache', 'delete_sitemap_transients_for_locales' ) ) {
			\The_SEO_Framework\Sitemap\Cache::delete_sitemap_transients_for_locales( array_column( hri_languages(), 'locale' ) );
		}
	}
}

/**
 * Applies hri_seo_tsf_settings() to TSF's saved settings. Idempotent.
 *
 * @return string[] What was done.
 */
function hri_seo_setup(): array {
	if ( ! hri_has_tsf() ) {
		return array( __( 'ไม่พบปลั๊กอิน The SEO Framework (ติดตั้งและเปิดใช้งานก่อน)', 'hri-core' ) );
	}
	$saved = get_option( THE_SEO_FRAMEWORK_SITE_OPTIONS );
	if ( ! is_array( $saved ) || ! $saved ) {
		$saved = class_exists( '\The_SEO_Framework\Data\Plugin\Setup' ) ? \The_SEO_Framework\Data\Plugin\Setup::get_default_options() : array();
	}
	$changed = array();
	foreach ( hri_seo_tsf_settings() as $key => $value ) {
		if ( ! array_key_exists( $key, $saved ) || (string) $saved[ $key ] !== (string) $value ) {
			$saved[ $key ] = $value;
			$changed[]     = $key;
		}
	}
	if ( $changed ) {
		update_option( THE_SEO_FRAMEWORK_SITE_OPTIONS, $saved );
	}
	hri_seo_clear_sitemaps();
	update_option( 'hri_seo_setup_version', HRI_SEO_SETUP_VERSION, false );
	return array( $changed ? sprintf( 'The SEO Framework: %s', implode( ', ', $changed ) ) : 'The SEO Framework: ' . __( 'ตั้งค่าครบแล้ว', 'hri-core' ) );
}

// Once per setup version, the first time an admin opens wp-admin with TSF active.
add_action(
	'admin_init',
	static function () {
		if ( hri_has_tsf() && current_user_can( 'manage_options' ) && HRI_SEO_SETUP_VERSION !== get_option( 'hri_seo_setup_version' ) ) {
			hri_seo_setup();
		}
	}
);

// TSF's schema output and AI-blocking switch belong to hri-core: keep them off whatever is saved,
// and hide TSF's Schema settings box so nobody turns on a second, conflicting graph.
add_filter(
	'the_seo_framework_get_options',
	static function ( $options ) {
		if ( is_array( $options ) ) {
			$options['ld_json_enabled']    = 0;
			$options['robotstxt_block_ai'] = 0;
		}
		return $options;
	},
	20
);
add_filter( 'the_seo_framework_schema_metabox', '__return_false' );

/* ----------------------------------------------------------------------
 * Pages kept out of search engines
 * ---------------------------------------------------------------------- */

/**
 * Slugs (source language) of pages that are not for search engines. Their translations follow.
 *
 * @return string[]
 */
function hri_seo_noindex_slugs(): array {
	return (array) apply_filters( 'hri_seo_noindex_slugs', array( 'style-guide', 'sample-page', 'privacy-policy' ) );
}

/**
 * IDs (all languages) of the noindex pages, plus courses that redirect to their own website.
 *
 * @return int[]
 */
function hri_seo_noindex_ids(): array {
	static $ids = null;
	if ( null !== $ids ) {
		return $ids;
	}
	$ids = array();
	foreach ( hri_seo_noindex_slugs() as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			continue;
		}
		$ids[] = (int) $page->ID;
		if ( function_exists( 'pll_get_post_translations' ) ) {
			$ids = array_merge( $ids, array_map( 'intval', array_values( pll_get_post_translations( $page->ID ) ) ) );
		}
	}
	$external = get_posts(
		array(
			'post_type'        => 'course',
			'post_status'      => 'publish',
			'numberposts'      => -1,
			'fields'           => 'ids',
			'meta_key'         => 'hri_external_url', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_compare'     => '!=',
			'meta_value'       => '', // phpcs:ignore WordPress.DB.SlowDBQuery
			'lang'             => '',
			'suppress_filters' => false,
		)
	);
	$ids = array_values( array_unique( array_merge( $ids, array_map( 'intval', $external ) ) ) );
	$ids = array_values( array_unique( array_map( 'intval', (array) apply_filters( 'hri_seo_noindex_ids', $ids ) ) ) );
	return $ids;
}

/**
 * Whether a post should carry noindex.
 */
function hri_seo_is_noindex( int $post_id ): bool {
	return $post_id && in_array( $post_id, hri_seo_noindex_ids(), true );
}

add_filter(
	'the_seo_framework_robots_meta_array',
	static function ( $meta, $args = null ) {
		if ( is_array( $args ) ) {
			// A post (not a term, archive or user) given by ID, e.g. in the admin list.
			$id = empty( $args['tax'] ) && empty( $args['pta'] ) && empty( $args['uid'] ) ? (int) ( $args['id'] ?? 0 ) : 0;
		} else {
			$id = is_singular() ? (int) get_queried_object_id() : 0;
		}
		if ( hri_seo_is_noindex( $id ) ) {
			$meta['noindex'] = 'noindex';
		}
		return $meta;
	},
	10,
	2
);

// Without TSF: core's robots meta.
add_filter(
	'wp_robots',
	static function ( $robots ) {
		if ( ! hri_has_tsf() && is_singular() && hri_seo_is_noindex( get_queried_object_id() ) ) {
			$robots['noindex'] = true;
		}
		return $robots;
	}
);

add_filter(
	'the_seo_framework_sitemap_exclude_ids',
	static function ( $ids ) {
		return array_merge( (array) $ids, hri_seo_noindex_ids() );
	}
);

// Core sitemaps (only used when TSF is off).
add_filter(
	'wp_sitemaps_posts_query_args',
	static function ( $args ) {
		$args['post__not_in'] = array_merge( (array) ( $args['post__not_in'] ?? array() ), hri_seo_noindex_ids() );
		return $args;
	}
);

// Trainers and leads have no public pages; taxonomies of courses are filter values, not pages.
add_filter(
	'wp_sitemaps_post_types',
	static function ( $types ) {
		unset( $types['trainer'], $types['hri_lead'] );
		return $types;
	}
);

/* ----------------------------------------------------------------------
 * hreflang x-default
 * ---------------------------------------------------------------------- */

// Polylang prints th / en / ja alternates for every page that has translations; x-default tells search
// engines which version to show other languages (Thai, the main language).
add_filter(
	'pll_rel_hreflang_attributes',
	static function ( $hreflangs ) {
		if ( is_array( $hreflangs ) && count( $hreflangs ) > 1 && ! isset( $hreflangs['x-default'] ) ) {
			$default = function_exists( 'pll_default_language' ) ? (string) pll_default_language( 'slug' ) : 'th';
			foreach ( array( $default, 'th' ) as $key ) {
				if ( ! empty( $hreflangs[ $key ] ) ) {
					$hreflangs['x-default'] = $hreflangs[ $key ];
					break;
				}
			}
		}
		// A noindex page must not be advertised as an alternate.
		if ( is_singular() && is_array( $hreflangs ) && function_exists( 'pll_get_post_translations' ) ) {
			foreach ( pll_get_post_translations( get_queried_object_id() ) as $lang => $id ) {
				if ( hri_seo_is_noindex( (int) $id ) ) {
					return array();
				}
			}
		}
		return $hreflangs;
	}
);

/* ----------------------------------------------------------------------
 * Home page title in the visitor's language
 * ---------------------------------------------------------------------- */

// WordPress' tagline (Settings → General) is one language, and TSF builds the home title from it
// ("HRI DeOne | tagline"). On the front end it is replaced by an interface string (translated in the
// .po files), so every language home page gets its own. A title typed in TSF's home page settings
// still wins, because TSF only uses the tagline when it has no custom title.
add_filter(
	'option_blogdescription',
	static function ( $value ) {
		if ( ! did_action( 'init' ) || is_admin() || wp_doing_ajax() || defined( 'REST_REQUEST' ) ) {
			return $value;
		}
		return __( 'สถาบันฝึกอบรมผู้บริหารและพัฒนาองค์กร', 'hri-core' );
	}
);

/* ----------------------------------------------------------------------
 * Social image and description helpers
 * ---------------------------------------------------------------------- */

// Remote covers (demo + articles imported with images=remote) are not attachments, so TSF would pick
// the first image in the content instead. Offer the cover first.
add_filter(
	'the_seo_framework_image_generation_params',
	static function ( $params, $args = null ) {
		if ( ! is_array( $params ) || ! isset( $params['cbs'] ) || ! is_array( $params['cbs'] ) ) {
			return $params;
		}
		$params['cbs'] = array( 'hri_remote_cover' => 'hri_seo_remote_cover_generator' ) + $params['cbs'];
		return $params;
	},
	10,
	2
);

/**
 * TSF image generator: the post's remote cover.
 *
 * @param ?array $args Query (id …) or null for the current one.
 * @return \Generator
 */
function hri_seo_remote_cover_generator( $args = null, $size = 'full' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$id = (int) ( is_array( $args ) ? ( $args['id'] ?? 0 ) : ( is_singular() ? get_queried_object_id() : 0 ) );
	if ( is_array( $args ) && ( ! empty( $args['tax'] ) || ! empty( $args['pta'] ) || ! empty( $args['uid'] ) ) ) {
		$id = 0;
	}
	$url = $id ? hri_remote_thumbnail_url( $id ) : '';
	if ( $url ) {
		yield array(
			'url'    => $url,
			'id'     => 0,
			'width'  => (int) get_post_meta( $id, '_hri_thumbnail_w', true ),
			'height' => (int) get_post_meta( $id, '_hri_thumbnail_h', true ),
			'alt'    => (string) get_post_meta( $id, '_hri_thumbnail_alt', true ),
		);
	}
}

/**
 * Cover image URL of a post: featured image, or the remote cover.
 */
function hri_seo_image_url( int $post_id, string $size = 'large' ): string {
	$id = get_post_thumbnail_id( $post_id );
	if ( $id ) {
		return (string) wp_get_attachment_image_url( $id, $size );
	}
	return hri_remote_thumbnail_url( $post_id );
}

/**
 * Meta description of a post: TSF's (custom or generated) when available, else the excerpt, else
 * the start of the text. Plain text, ≤ 160 characters.
 */
function hri_seo_description( int $post_id ): string {
	$text = '';
	if ( hri_has_tsf() && method_exists( tsf(), 'description' ) ) {
		$text = (string) tsf()->description()->get_description( array( 'id' => $post_id ) );
	}
	if ( '' === trim( $text ) ) {
		$post = get_post( $post_id );
		if ( $post ) {
			$text = has_excerpt( $post ) ? $post->post_excerpt : excerpt_remove_blocks( $post->post_content );
		}
	}
	$text = trim( (string) preg_replace( '/\s+/u', ' ', wp_strip_all_tags( html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) ) );
	return mb_strlen( $text ) > 160 ? rtrim( mb_substr( $text, 0, 157 ) ) . '…' : $text;
}

// Pages that are only a list (the blog index) have no text for TSF to build a description from.
add_filter(
	'the_seo_framework_generated_description',
	static function ( $description, $args = null ) {
		if ( '' !== trim( (string) $description ) ) {
			return $description;
		}
		$id = is_array( $args ) ? (int) ( $args['id'] ?? 0 ) : ( is_singular() || is_home() ? (int) get_queried_object_id() : 0 ); // The blog index is the posts page.
		if ( $id && 'page' === get_post_type( $id ) && 'blog' === hri_schema_source_slug( $id ) ) {
			return __( 'บทความและแนวคิดด้านภาวะผู้นำ การขาย การเจรจา และการพัฒนาองค์กร จากทีมผู้เชี่ยวชาญ HRI DeOne', 'hri-core' );
		}
		return $description;
	},
	10,
	2
);

/* ----------------------------------------------------------------------
 * "Last updated" on articles
 * ---------------------------------------------------------------------- */

// An article edited after it was published shows when (readers and AI answers prefer fresh content).
// Same value as dateModified in the JSON-LD. Less than a day apart = not an update.
add_filter(
	'render_block_core/post-date',
	static function ( $html ) {
		if ( ! is_singular( 'post' ) || ! in_the_loop() && ! doing_filter( 'the_content' ) && ! get_the_ID() ) {
			return $html;
		}
		$post = get_post();
		if ( ! $post || strtotime( $post->post_modified_gmt ) - strtotime( $post->post_date_gmt ) < DAY_IN_SECONDS ) {
			return $html;
		}
		$updated = sprintf(
			'<time class="hri-post__updated" datetime="%s">%s</time>',
			esc_attr( get_the_modified_date( 'c', $post ) ),
			esc_html( sprintf( /* translators: %s: date */ __( 'อัปเดตล่าสุด %s', 'hri-core' ), get_the_modified_date( '', $post ) ) )
		);
		return preg_replace( '#</div>\s*$#', ' · ' . $updated . '</div>', $html, 1, $count ) ?: $html;
	}
);

/* ----------------------------------------------------------------------
 * WP-CLI
 * ---------------------------------------------------------------------- */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Apply the SEO settings this site needs (The SEO Framework). Safe to run again.
	 *
	 * ## EXAMPLES
	 *     wp hri seo setup
	 */
	WP_CLI::add_command(
		'hri seo setup',
		static function () {
			foreach ( hri_seo_setup() as $line ) {
				WP_CLI::log( $line );
			}
			WP_CLI::success( 'SEO ready.' );
		}
	);
}
