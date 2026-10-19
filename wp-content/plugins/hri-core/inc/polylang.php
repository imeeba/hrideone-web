<?php
/**
 * Polylang integration (free version, 3.7+).
 *
 * What is translated:
 * - pages, posts and courses: one post per language, linked as translations (Polylang)
 * - course taxonomies: NOT translated — shared by all languages, names per language in term
 *   meta (see languages.php)
 * - header / footer: internal links point to the translation of the linked page; menu labels,
 *   headings, paragraphs and buttons typed in those parts are Polylang strings
 *   (Languages → Translations, group "HRI header & footer")
 * - interface text in code: gettext files in hri-core/languages and hri-theme/languages
 *
 * Setup (languages, URL settings, sync) is done by hri_polylang_setup(): from WP-CLI
 * (`wp hri languages`), from the Playground blueprint, or with the button shown in
 * Languages admin while TH / EN / JA are not all set up.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Course fields that are the same in every language: Polylang copies them when a translation is
 * created and keeps them in sync afterwards. The other fields (audience, location, price) are
 * copied once and then translated (audience, location, prices, schedule text).
 *
 * @return string[]
 */
function hri_synced_course_meta(): array {
	return array( 'hri_duration_days', 'hri_batch_no', 'hri_reg_status', 'hri_start_date', 'hri_end_date', 'hri_early_deadline', 'hri_certificate', 'hri_register_url', 'hri_external_url', 'hri_trainers' );
}

/**
 * Courses are translatable (whatever the Polylang settings say).
 */
add_filter(
	'pll_get_post_types',
	static function ( $post_types, $is_settings ) {
		if ( $is_settings ) {
			unset( $post_types['course'] ); // Not offered as a setting: always on.
		} else {
			$post_types['course'] = 'course';
		}
		return $post_types;
	},
	10,
	2
);

/**
 * Course taxonomies are never translated by Polylang (shared terms, see languages.php).
 */
add_filter(
	'pll_get_taxonomies',
	static function ( $taxonomies ) {
		if ( function_exists( 'hri_course_taxonomy_names' ) ) {
			foreach ( hri_course_taxonomy_names() as $taxonomy ) {
				unset( $taxonomies[ $taxonomy ] );
			}
		}
		return $taxonomies;
	},
	20
);

/**
 * …but a course translation gets the same terms as its source, and they stay in sync.
 */
add_filter(
	'pll_copy_taxonomies',
	static function ( $taxonomies, $sync, $from ) {
		if ( function_exists( 'hri_course_taxonomy_names' ) && ( ! $from || 'course' === get_post_type( $from ) ) ) {
			$taxonomies = array_merge( (array) $taxonomies, hri_course_taxonomy_names() );
		}
		return $taxonomies;
	},
	10,
	3
);

/**
 * Course fields: copy all on creation, keep only the language-neutral ones in sync.
 */
add_filter(
	'pll_copy_post_metas',
	static function ( $keys, $sync, $from ) {
		if ( ! $from || 'course' !== get_post_type( $from ) || ! function_exists( 'hri_course_meta_schema' ) ) {
			return $keys;
		}
		$keys       = (array) $keys;
		$all        = array_keys( hri_course_meta_schema() );
		$translated = array_diff( $all, hri_synced_course_meta() );

		if ( $sync ) {
			$keys = array_diff( $keys, $translated );
			return array_values( array_unique( array_merge( $keys, hri_synced_course_meta() ) ) );
		}
		return array_values( array_unique( array_merge( $keys, $all ) ) );
	},
	10,
	3
);

/* -------------------------------------------------------------------------
 * Header menu: language-aware links and labels.
 * ---------------------------------------------------------------------- */

/**
 * URL of an internal link in the current language.
 *
 * "/in-house/" on an English page becomes the English translation of that page, or
 * "/en/in-house/" style when there is none (so the visitor stays in English).
 */
function hri_localize_url( string $url ): string {
	if ( '' === $url || ! hri_has_polylang() ) {
		return $url;
	}

	$home = home_url( '/' );
	if ( str_starts_with( $url, '/' ) && ! str_starts_with( $url, '//' ) ) {
		$path = $url;
	} elseif ( str_starts_with( $url, $home ) ) {
		$path = '/' . substr( $url, strlen( $home ) );
	} else {
		return $url; // External, anchor, mailto, tel…
	}

	// Keep a query string (filter links such as /in-house/?pillar[]=leadership) and an #anchor.
	$suffix = '';
	if ( preg_match( '/[?#].*$/s', $path, $m ) ) {
		$suffix = $m[0];
		$path   = substr( $path, 0, -strlen( $suffix ) );
	}

	// A relative path is relative to the default-language home (works in a sub-folder install too).
	$absolute = untrailingslashit( home_url() ) . $path;
	$post_id  = url_to_postid( $absolute );
	if ( $post_id ) {
		$translated = pll_get_post( $post_id );
		if ( $translated ) {
			return get_permalink( $translated ) . $suffix;
		}
	}

	if ( preg_match( '#^/courses/?$#', $path ) ) {
		return get_post_type_archive_link( 'course' ) . $suffix;
	}

	return trailingslashit( pll_home_url() ) . ltrim( $path, '/' ) . $suffix;
}

/**
 * Navigation links in the header (and any core/navigation-link): URL and label per language.
 */
add_filter(
	'render_block_data',
	static function ( $block ) {
		if ( 'core/navigation-link' !== ( $block['blockName'] ?? '' ) || ! hri_has_polylang() || is_admin() ) {
			return $block;
		}
		$attrs = $block['attrs'] ?? array();
		if ( ! empty( $attrs['url'] ) && is_string( $attrs['url'] ) ) {
			$attrs['url'] = hri_localize_url( $attrs['url'] );
		}
		if ( ! empty( $attrs['label'] ) && is_string( $attrs['label'] ) && function_exists( 'pll__' ) ) {
			$attrs['label'] = pll__( $attrs['label'] );
		}
		$block['attrs'] = $attrs;
		return $block;
	}
);

/**
 * Links typed into page content (buttons, paragraphs, headings, lists, images, covers):
 * on an English or Japanese page, "/in-house/" goes to the English or Japanese page, and on a
 * Thai page to the Thai page's permalink (also right in a sub-folder install).
 * The marketing team can type the same Thai-site path in every language.
 */
add_filter(
	'render_block',
	static function ( $content, $block ) {
		static $blocks = array( 'core/button', 'core/paragraph', 'core/heading', 'core/list-item', 'core/image', 'core/cover', 'core/media-text' );
		if ( ! in_array( $block['blockName'] ?? '', $blocks, true ) || ! str_contains( (string) $content, 'href=' ) ) {
			return $content;
		}
		if ( is_admin() || ! hri_has_polylang() ) {
			return $content;
		}
		$tags = new WP_HTML_Tag_Processor( (string) $content );
		while ( $tags->next_tag( 'a' ) ) {
			$href = $tags->get_attribute( 'href' );
			if ( is_string( $href ) && '' !== $href ) {
				$localized = hri_localize_url( $href );
				if ( $localized !== $href ) {
					$tags->set_attribute( 'href', $localized );
				}
			}
		}
		return $tags->get_updated_html();
	},
	9,
	2
);

/**
 * Texts typed into the header and footer template parts (menu labels, headings, paragraphs,
 * buttons) in the default language. They are Polylang strings, so the marketing team edits
 * the Thai text in the Site Editor and translates it in Languages → Translations
 * (group "HRI header & footer").
 *
 * @return string[] Unique texts (plain text for labels, inner HTML for the other blocks).
 */
function hri_part_strings(): array {
	static $strings = null;
	if ( null !== $strings ) {
		return $strings;
	}
	$strings = array();
	$walk    = static function ( array $blocks ) use ( &$walk, &$strings ): void {
		foreach ( $blocks as $block ) {
			$name = (string) $block['blockName'];
			if ( 'core/navigation-link' === $name && ! empty( $block['attrs']['label'] ) ) {
				$strings[] = wp_strip_all_tags( (string) $block['attrs']['label'] );
			} elseif ( in_array( $name, array( 'core/heading', 'core/paragraph', 'core/button' ), true ) ) {
				$text = hri_block_text( (string) ( $block['innerHTML'] ?? '' ) );
				if ( '' !== $text ) {
					$strings[] = $text;
				}
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$walk( $block['innerBlocks'] );
			}
		}
	};
	foreach ( array( 'header', 'footer' ) as $slug ) {
		$part = get_block_template( get_stylesheet() . '//' . $slug, 'wp_template_part' );
		if ( $part && ! empty( $part->content ) ) {
			$walk( parse_blocks( $part->content ) );
		}
	}
	$strings = array_values( array_unique( $strings ) );
	return $strings;
}

/**
 * Inner HTML of a heading / paragraph / button (the text between its outer tags).
 */
function hri_block_text( string $html ): string {
	$html = trim( $html );
	// Buttons wrap the text in <div><a>…</a></div>: take the innermost element.
	while ( preg_match( '#^<([a-z0-9]+)\b[^>]*>(.*)</\1>$#is', $html, $m ) ) {
		$inner = trim( $m[2] );
		if ( $inner === $html ) {
			break;
		}
		$html = $inner;
		if ( ! preg_match( '#^<(a|div|span)\b#i', $html ) ) {
			break;
		}
	}
	return trim( $html );
}

add_action(
	'admin_init',
	static function () {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}
		foreach ( hri_part_strings() as $text ) {
			pll_register_string( 'part: ' . wp_trim_words( wp_strip_all_tags( $text ), 6, '…' ), $text, 'HRI header & footer', strlen( $text ) > 80 );
		}
	}
);

/**
 * Replace those texts with their translation when the page is not in the default language.
 */
add_filter(
	'render_block',
	static function ( $content, $block ) {
		if ( ! in_array( $block['blockName'] ?? '', array( 'core/heading', 'core/paragraph', 'core/button' ), true ) ) {
			return $content;
		}
		if ( is_admin() || ! hri_has_polylang() || ! function_exists( 'pll__' ) || pll_current_language() === pll_default_language() ) {
			return $content;
		}
		$text = hri_block_text( (string) ( $block['innerHTML'] ?? '' ) );
		if ( '' === $text || ! in_array( $text, hri_part_strings(), true ) ) {
			return $content;
		}
		$translated = pll__( $text );
		return $translated && $translated !== $text ? str_replace( $text, wp_kses_post( $translated ), $content ) : $content;
	},
	10,
	2
);

/* -------------------------------------------------------------------------
 * Setup.
 * ---------------------------------------------------------------------- */

/**
 * Whether TH / EN / JA all exist in Polylang.
 */
function hri_polylang_is_set_up(): bool {
	if ( ! function_exists( 'PLL' ) || ! PLL() || ! isset( PLL()->model->languages ) ) {
		return false;
	}
	foreach ( array_keys( hri_languages() ) as $slug ) {
		if ( ! PLL()->model->languages->get( $slug ) ) {
			return false;
		}
	}
	return true;
}

/**
 * Create the languages and apply the agreed settings. Safe to run again.
 *
 * - URLs: Thai has no prefix (/in-house/), English /en/…, Japanese /ja/…; home pages /, /en/, /ja/
 * - No browser-language redirect (it breaks page caching and confuses search engines)
 * - Existing content without a language becomes Thai
 *
 * Needs an admin context of Polylang (WP-CLI, wp-admin, or PLL_ADMIN defined before WordPress
 * loads): without languages Polylang does not start on the front end.
 *
 * @return string[]|WP_Error Log lines, or an error.
 */
function hri_polylang_setup() {
	if ( ! function_exists( 'PLL' ) || ! PLL() || ! isset( PLL()->model->languages ) ) {
		return new WP_Error( 'hri_no_polylang', 'Polylang 3.7+ is not active in this context (run from WP-CLI or wp-admin).' );
	}

	$model = PLL()->model;
	$log   = array();
	$order = 0;

	foreach ( hri_languages() as $slug => $language ) {
		++$order;
		if ( $model->languages->get( $slug ) ) {
			$log[] = "Language {$slug}: exists";
			continue;
		}
		$args = array(
			'name'       => $language['name'],
			'slug'       => $slug,
			'locale'     => $language['locale'],
			'rtl'        => false,
			'term_group' => $order,
		);
		// Flag images ship with the wordpress.org build only.
		if ( defined( 'POLYLANG_DIR' ) && is_readable( POLYLANG_DIR . '/flags/' . $language['flag'] . '.png' ) ) {
			$args['flag'] = $language['flag'];
		}
		$result = $model->languages->add( $args );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		$log[] = "Language {$slug}: added";
	}

	$model->languages->clean_cache();
	$default = $model->languages->get_default();
	if ( ! $default || 'th' !== $default->slug ) {
		$model->languages->update_default( 'th' );
		$log[] = 'Default language: th';
	}

	$settings = array(
		'force_lang'    => 1,       // Language from the directory name in the URL.
		'hide_default'  => true,    // No /th/ prefix.
		'rewrite'       => true,    // /en/ instead of /language/en/.
		'redirect_lang' => true,    // Translated front pages live at /en/ and /ja/ (not /en/<page-slug>/).
		'browser'       => false,   // No browser-language detection.
		'media_support' => false,   // One media library for all languages.
		'sync'          => array( 'taxonomies', 'post_meta', '_thumbnail_id', 'menu_order', 'post_parent', 'comment_status', 'ping_status' ),
	);
	foreach ( $settings as $key => $value ) {
		$error = PLL()->options->set( $key, $value );
		if ( $error instanceof WP_Error && $error->has_errors() ) {
			$log[] = "Option {$key}: " . $error->get_error_message();
		}
	}
	PLL()->options->save();
	$log[] = 'Settings saved';

	$model->clean_languages_cache();
	$th = $model->languages->get( 'th' );
	if ( $th ) {
		$model->set_language_in_mass( $th );
		$log[] = 'Content without a language set to th';
	}

	// The languages are set up: skip Polylang's first-run wizard.
	delete_transient( 'pll_activation_redirect' );

	// Language rewrite rules are added on the next request; flush then.
	update_option( 'hri_flush_rewrite', 1 );
	return $log;
}

add_action(
	'init',
	static function () {
		if ( get_option( 'hri_flush_rewrite' ) ) {
			delete_option( 'hri_flush_rewrite' );
			flush_rewrite_rules();
		}
	},
	99
);

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Set up Polylang languages (TH default, EN, JA) and HRI settings.
	 *
	 * ## EXAMPLES
	 *     wp hri languages
	 */
	WP_CLI::add_command(
		'hri languages',
		static function () {
			$result = hri_polylang_setup();
			if ( is_wp_error( $result ) ) {
				WP_CLI::error( $result->get_error_message() );
			}
			foreach ( $result as $line ) {
				WP_CLI::log( $line );
			}
			WP_CLI::success( 'Languages ready.' );
		}
	);
}

/**
 * Admin: one-click setup while the languages are missing (hosts without WP-CLI).
 */
add_action(
	'admin_notices',
	static function () {
		if ( ! function_exists( 'PLL' ) || ! current_user_can( 'manage_options' ) || hri_polylang_is_set_up() ) {
			return;
		}
		printf(
			'<div class="notice notice-info"><p><strong>HRI DeOne:</strong> %1$s</p><form method="post" action="%2$s"><input type="hidden" name="action" value="hri_lang_setup">%3$s<p><button class="button button-primary">%4$s</button></p></form></div>',
			esc_html__( 'ยังไม่ได้ตั้งค่าภาษา TH / EN / JA ใน Polylang', 'hri-core' ),
			esc_url( admin_url( 'admin-post.php' ) ),
			wp_nonce_field( 'hri_lang_setup', '_wpnonce', true, false ),
			esc_html__( 'ตั้งค่าภาษาตามมาตรฐานเว็บ', 'hri-core' )
		);
	}
);

add_action(
	'admin_post_hri_lang_setup',
	static function () {
		check_admin_referer( 'hri_lang_setup' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'ไม่มีสิทธิ์', 'hri-core' ), 403 );
		}
		$result = hri_polylang_setup();
		if ( is_wp_error( $result ) ) {
			wp_die( esc_html( $result->get_error_message() ) );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=mlang' ) );
		exit;
	}
);
