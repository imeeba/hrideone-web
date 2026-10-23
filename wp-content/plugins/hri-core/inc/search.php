<?php
/**
 * Site search: courses, articles and pages in the visitor's language, Thai included.
 *
 * Why not WordPress' own search: it needs the typed letters to appear in a row, cannot rank, has
 * no synonyms and does not know which language the visitor reads. Thai and Japanese are written
 * without spaces, so "การเจรจา" and "เจรจา" are different strings to a LIKE query.
 *
 * How it works (no plugin, same code on MySQL and SQLite):
 * - Two tables made by this file (hri_search_docs, hri_search_idx) hold, per published course /
 *   article / page, its language, a normalised plain text and one row per word.
 * - Words come from ICU (PHP intl, IntlBreakIterator) when the host has ext-intl. Without it the
 *   fallback is character bigrams (Thai / Japanese) plus whole words (everything else).
 *   The mode is stored with the index; if the host changes, the index is rebuilt.
 * - Searching = tokenise the query the same way, expand synonyms (Settings → การค้นหา), look the
 *   words up in the index table, then rank in PHP. Words that ICU splits inside one typed word
 *   ("ผู้บริหาร" → ผู้ + บริหาร) must also appear as one piece of text, so unrelated pages that
 *   merely contain both pieces do not match.
 * - Ranking per word: title > category / topic names > excerpt > body. Courses that are full or
 *   closed come after open ones.
 * - The index is kept up to date on save / trash / delete (one write per post at the end of the
 *   request); Settings → การค้นหา and `wp hri search reindex` rebuild everything.
 *
 * Results page: templates/search.html + block hri/search-results. Form: block hri/search-form.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_SEARCH_DB_VERSION      = '1';
const HRI_SEARCH_PER_GROUP       = 6;    // Results per type on the "all" view.
const HRI_SEARCH_PER_PAGE        = 12;   // Results per page on a single-type view.
const HRI_SEARCH_MAX_CHARS       = 100;  // Longest query used (characters).
const HRI_SEARCH_MAX_UNITS       = 8;    // Most words (units) of one query.
const HRI_SEARCH_OPTION_SYN      = 'hri_search_synonyms';
const HRI_SEARCH_OPTION_INDEX    = 'hri_search_index';
const HRI_SEARCH_CONTENT_CHARS   = 60000; // Body text indexed per post (characters).
const HRI_SEARCH_ROWS_PER_DOC    = 4000;

/* -------------------------------------------------------------------------
 * Settings and tables
 * ---------------------------------------------------------------------- */

/**
 * Post types in the search. Trainers, leads and everything without a public page stay out.
 *
 * @return string[]
 */
function hri_search_post_types(): array {
	return array_values( array_filter( (array) apply_filters( 'hri_search_post_types', array( 'course', 'post', 'page' ) ), 'post_type_exists' ) );
}

/**
 * Table name: 'docs' or 'idx'.
 */
function hri_search_table( string $which ): string {
	global $wpdb;
	return $wpdb->prefix . 'hri_search_' . ( 'docs' === $which ? 'docs' : 'idx' );
}

/**
 * Create the tables once per schema version. Index data can always be rebuilt, so a schema
 * change just drops and recreates them.
 */
function hri_search_install(): void {
	global $wpdb;
	if ( HRI_SEARCH_DB_VERSION === get_option( 'hri_search_db_version' ) ) {
		return;
	}
	$docs    = hri_search_table( 'docs' );
	$idx     = hri_search_table( 'idx' );
	$charset = $wpdb->get_charset_collate();

	$wpdb->suppress_errors( true );
	$wpdb->query( "DROP TABLE IF EXISTS {$docs}" ); // phpcs:ignore WordPress.DB.PreparedSQL
	$wpdb->query( "DROP TABLE IF EXISTS {$idx}" ); // phpcs:ignore WordPress.DB.PreparedSQL

	// One row per indexed post. `txt` = normalised text without spaces (checks that a typed Thai /
	// Japanese word appears in one piece). `hint` 1 = course that is full or closed.
	$wpdb->query( // phpcs:ignore WordPress.DB.PreparedSQL
		"CREATE TABLE {$docs} (
			post_id bigint(20) unsigned NOT NULL,
			lang varchar(10) NOT NULL DEFAULT '',
			post_type varchar(20) NOT NULL DEFAULT '',
			hint tinyint(3) unsigned NOT NULL DEFAULT 0,
			menu_order int(11) NOT NULL DEFAULT 0,
			pub bigint(20) NOT NULL DEFAULT 0,
			txt longtext NOT NULL,
			PRIMARY KEY  (post_id),
			KEY lang_type (lang, post_type)
		) {$charset}"
	);

	// One row per post and word. `fl` bit mask of where the word appears (1 title, 2 category /
	// topic names, 4 excerpt, 8 body) · `tf` how often in the body. Binary collation = exact words.
	$wpdb->query( // phpcs:ignore WordPress.DB.PreparedSQL
		"CREATE TABLE {$idx} (
			post_id bigint(20) unsigned NOT NULL,
			token varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
			fl tinyint(3) unsigned NOT NULL DEFAULT 0,
			tf smallint(5) unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY  (post_id, token),
			KEY token (token)
		) {$charset}"
	);
	$wpdb->suppress_errors( false );

	if ( hri_search_tables_ready( true ) ) {
		update_option( 'hri_search_db_version', HRI_SEARCH_DB_VERSION );
		delete_option( HRI_SEARCH_OPTION_INDEX ); // Rebuilt on first use.
	}
}
add_action( 'plugins_loaded', 'hri_search_install', 20 );

/**
 * Whether both tables exist and can be read.
 */
function hri_search_tables_ready( bool $recheck = false ): bool {
	global $wpdb;
	static $ready = null;
	if ( null !== $ready && ! $recheck ) {
		return $ready;
	}
	$wpdb->suppress_errors( true );
	$ok = null !== $wpdb->get_var( 'SELECT COUNT(*) FROM ' . hri_search_table( 'docs' ) ) // phpcs:ignore WordPress.DB.PreparedSQL
		&& null !== $wpdb->get_var( 'SELECT COUNT(*) FROM ' . hri_search_table( 'idx' ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	$wpdb->suppress_errors( false );
	$ready = $ok;
	return $ok;
}

/* -------------------------------------------------------------------------
 * Words: normalise → split → filter
 * ---------------------------------------------------------------------- */

/**
 * Whether PHP can split Thai / Japanese into words (ext-intl with ICU).
 */
function hri_search_icu_available(): bool {
	return class_exists( 'IntlBreakIterator' );
}

/**
 * Tokenizer for new work on this host: 'icu' or 'ngram'.
 * Force the fallback with define( 'HRI_SEARCH_TOKENIZER', 'ngram' ) (used for testing).
 */
function hri_search_runtime_mode(): string {
	$want = defined( 'HRI_SEARCH_TOKENIZER' ) ? (string) HRI_SEARCH_TOKENIZER : 'auto';
	$want = (string) apply_filters( 'hri_search_tokenizer', $want );
	if ( 'ngram' === $want ) {
		return 'ngram';
	}
	return hri_search_icu_available() ? 'icu' : 'ngram';
}

/**
 * Words that carry no meaning of their own (not indexed, dropped from a query when other words remain).
 *
 * @return array<string,true>
 */
function hri_search_stopwords(): array {
	static $words = null;
	if ( null === $words ) {
		$list = array(
			// Thai.
			'การ', 'ความ', 'ที่', 'ซึ่ง', 'อัน', 'ของ', 'และ', 'หรือ', 'แต่', 'ใน', 'ให้', 'ได้', 'เป็น', 'มี', 'ไป', 'มา', 'จะ', 'ก็', 'ว่า', 'กับ', 'จาก',
			'โดย', 'เพื่อ', 'อย่าง', 'นี้', 'นั้น', 'ไม่', 'แล้ว', 'คือ', 'ด้วย', 'แก่', 'นะ', 'ครับ', 'ค่ะ', 'คะ',
			// English.
			'a', 'an', 'the', 'of', 'and', 'or', 'to', 'in', 'on', 'for', 'with', 'by', 'at', 'is', 'are', 'be', 'as', 'it', 'this', 'that', 'from',
			'your', 'you', 'we', 'our', 'can', 'will', 'how', 'what', 'why',
			// Japanese.
			'です', 'ます', 'する', 'して', 'した', 'され', 'ない', 'こと', 'もの', 'ため', 'など', 'これ', 'それ', 'より', 'という', 'について', 'による',
			'において', 'さらに', 'また', 'その', 'この', 'あり', 'ある', 'いる', 'れる', 'られる', 'なる', 'できる', 'から', 'まで',
		);
		$list  = (array) apply_filters( 'hri_search_stopwords', $list );
		$words = array_fill_keys( array_map( 'strval', $list ), true );
	}
	return $words;
}

/**
 * Lower case, half-width → full-width katakana and full-width → half-width Latin / digits,
 * punctuation → space, and a space where Latin / digits meet Thai or Japanese ("Oxygenคือ").
 */
function hri_search_normalize( string $text ): string {
	if ( '' === $text ) {
		return '';
	}
	$text  = mb_convert_kana( $text, 'KVas', 'UTF-8' );
	$text  = mb_strtolower( $text, 'UTF-8' );
	// Zero-width characters and soft hyphen, Thai repetition mark (ๆ) and abbreviation sign (ฯ).
	$text  = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}\x{0E46}\x{0E2F}]/u', ' ', $text ) ?? '';
	$text  = preg_replace( '/[^\p{L}\p{N}\p{M}]+/u', ' ', $text ) ?? '';
	$asian = '\p{Thai}\p{Han}\p{Hiragana}\p{Katakana}';
	$text  = preg_replace( '/([\p{Latin}\p{N}])(?=[' . $asian . '])/u', '$1 ', $text ) ?? '';
	$text  = preg_replace( '/([' . $asian . '])(?=[\p{Latin}])/u', '$1 ', $text ) ?? '';
	return trim( preg_replace( '/\s+/u', ' ', $text ) ?? '' );
}

/**
 * Pieces of one normalised word (no spaces): ICU words, or the fallback bigrams.
 *
 * @return string[]
 */
function hri_search_pieces( string $chunk, string $mode ): array {
	if ( '' === $chunk ) {
		return array();
	}
	$asian = '/[\p{Thai}\p{Han}\p{Hiragana}\p{Katakana}]/u';
	if ( ! preg_match( $asian, $chunk ) ) {
		return array( $chunk ); // Latin / digits / other scripts: spaces already separate the words.
	}

	if ( 'icu' === $mode && hri_search_icu_available() ) {
		static $iterator = null;
		if ( null === $iterator ) {
			$iterator = IntlBreakIterator::createWordInstance( 'th' ); // The locale does not change the result.
		}
		$iterator->setText( $chunk );
		$out = array();
		foreach ( $iterator->getPartsIterator() as $part ) {
			if ( preg_match( '/[\p{L}\p{N}]/u', (string) $part ) ) {
				$out[] = (string) $part;
			}
		}
		return $out;
	}

	// Fallback: bigrams of every Thai / Japanese run, kanji also as single characters.
	$out = array();
	preg_match_all( '/\p{Thai}+|[\p{Han}\p{Hiragana}\p{Katakana}\x{30FC}]+|[^\p{Thai}\p{Han}\p{Hiragana}\p{Katakana}\x{30FC}]+/u', $chunk, $runs );
	foreach ( $runs[0] as $run ) {
		if ( preg_match( '/^\p{Thai}/u', $run ) ) {
			preg_match_all( '/\X/u', $run, $units );
			$units = $units[0];
		} elseif ( preg_match( '/^[\p{Han}\p{Hiragana}\p{Katakana}\x{30FC}]/u', $run ) ) {
			$units = preg_split( '//u', $run, -1, PREG_SPLIT_NO_EMPTY ) ?: array();
		} else {
			$out[] = $run;
			continue;
		}
		$count = count( $units );
		for ( $i = 0; $i < $count - 1; $i++ ) {
			$out[] = $units[ $i ] . $units[ $i + 1 ];
		}
		foreach ( $units as $unit ) {
			if ( preg_match( '/\p{Han}/u', $unit ) ) {
				$out[] = $unit;
			}
		}
	}
	return $out;
}

/**
 * English plural → singular ("negotiations" = "negotiation"). Only plain Latin words.
 */
function hri_search_fold( string $token ): string {
	if ( strlen( $token ) > 3 && 's' === substr( $token, -1 ) && preg_match( '/^[a-z]+$/', $token ) && ! preg_match( '/(ss|us|is)$/', $token ) ) {
		return substr( $token, 0, -1 );
	}
	return $token;
}

/**
 * Whether a word is worth indexing / looking up.
 */
function hri_search_keep_token( string $token, bool $drop_stop = true, string $mode = 'icu' ): bool {
	$len = mb_strlen( $token, 'UTF-8' );
	if ( $len < 1 || $len > 40 ) {
		return false;
	}
	// Stop words are whole words: the fallback's Thai / Japanese bigrams are not words.
	if ( $drop_stop && isset( hri_search_stopwords()[ $token ] ) && ( 'icu' === $mode || ! preg_match( '/[\p{Thai}\p{Han}\p{Hiragana}\p{Katakana}]/u', $token ) ) ) {
		return false;
	}
	if ( preg_match( '/\p{Han}/u', $token ) ) {
		return true; // One kanji can be a word.
	}
	if ( preg_match( '/[\p{Thai}\p{Hiragana}\p{Katakana}]/u', $token ) ) {
		return $len >= 2 && 'ー' !== $token;
	}
	return $len >= 2 || ctype_digit( $token );
}

/**
 * Words of a text, in order (duplicates kept).
 *
 * @return string[]
 */
function hri_search_tokens( string $text, string $mode ): array {
	$out = array();
	foreach ( explode( ' ', hri_search_normalize( $text ) ) as $chunk ) {
		foreach ( hri_search_pieces( $chunk, $mode ) as $piece ) {
			$piece = hri_search_fold( $piece );
			if ( hri_search_keep_token( $piece, true, $mode ) ) {
				$out[] = $piece;
			}
		}
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * Index: what goes in
 * ---------------------------------------------------------------------- */

/**
 * Plain text of HTML / block markup.
 */
function hri_search_plain( string $html ): string {
	$html = strip_shortcodes( $html );
	$html = preg_replace( '/<!--.*?-->/s', ' ', $html ) ?? '';
	$html = preg_replace( '#<(script|style)\b[^>]*>.*?</\1>#is', ' ', $html ) ?? '';
	$html = preg_replace( '/<[^>]*>/', ' ', $html ) ?? '';
	$html = html_entity_decode( $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return trim( preg_replace( '/\s+/u', ' ', $html ) ?? '' );
}

/**
 * Slugs (Thai-language page) of pages that stay out of the search: layout-only or demo pages.
 *
 * @return string[]
 */
function hri_search_excluded_slugs(): array {
	return (array) apply_filters( 'hri_search_excluded_slugs', array( 'style-guide', 'sample-page', 'privacy-policy' ) );
}

/**
 * IDs of pages (all translations) kept out of the search: the blog page (only a list), and the
 * slugs above.
 *
 * @return array<int,true>
 */
function hri_search_excluded_ids( bool $reset = false ): array {
	static $cache = null;
	if ( null !== $cache && ! $reset ) {
		return $cache;
	}
	$cache = array();
	$roots = array( (int) get_option( 'page_for_posts' ) );
	foreach ( hri_search_excluded_slugs() as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page ) {
			$roots[] = (int) $page->ID;
		}
	}
	foreach ( array_filter( $roots ) as $id ) {
		$cache[ $id ] = true;
		if ( function_exists( 'pll_get_post_translations' ) ) {
			foreach ( (array) pll_get_post_translations( $id ) as $translated ) {
				$cache[ (int) $translated ] = true;
			}
		}
	}
	// A translation saved with its own slug ("style-guide-en") is found by slug too.
	foreach ( hri_search_excluded_slugs() as $slug ) {
		foreach ( array( '-en', '-ja' ) as $suffix ) {
			$page = get_page_by_path( $slug . $suffix, OBJECT, 'page' );
			if ( $page ) {
				$cache[ (int) $page->ID ] = true;
			}
		}
	}
	$cache = (array) apply_filters( 'hri_search_excluded_ids', $cache );
	return $cache;
}

/**
 * Name of a term in a language (course taxonomies keep English / Japanese names in term meta).
 */
function hri_search_term_name( WP_Term $term, string $lang ): string {
	if ( '' !== $lang && 'th' !== $lang ) {
		$name = (string) get_term_meta( $term->term_id, 'hri_name_' . $lang, true );
		if ( '' !== $name ) {
			return $name;
		}
	}
	return $term->name;
}

/**
 * Language slug of a post ('' without Polylang or without a language).
 */
function hri_search_post_lang( int $post_id ): string {
	if ( ! function_exists( 'pll_get_post_language' ) ) {
		return '';
	}
	$lang = pll_get_post_language( $post_id, 'slug' );
	return is_string( $lang ) ? $lang : '';
}

/**
 * Texts of one post by search field.
 *
 * @return array{title:string,terms:string,excerpt:string,content:string}
 */
function hri_search_fields( WP_Post $post, string $lang ): array {
	$names = array();
	if ( 'course' === $post->post_type && function_exists( 'hri_course_taxonomy_names' ) ) {
		foreach ( hri_course_taxonomy_names() as $taxonomy ) {
			foreach ( (array) wp_get_object_terms( $post->ID, $taxonomy ) as $term ) {
				if ( $term instanceof WP_Term ) {
					$names[] = hri_search_term_name( $term, $lang );
				}
			}
		}
	} elseif ( 'post' === $post->post_type ) {
		foreach ( array( 'category', 'post_tag' ) as $taxonomy ) {
			foreach ( (array) wp_get_object_terms( $post->ID, $taxonomy ) as $term ) {
				if ( $term instanceof WP_Term ) {
					$names[] = $term->name;
				}
			}
		}
	}

	$content = hri_search_plain( $post->post_content );
	if ( 'course' === $post->post_type ) {
		$content .= ' ' . (string) get_post_meta( $post->ID, 'hri_audience', true );
	}

	return array(
		'title'   => hri_search_plain( $post->post_title ),
		'terms'   => implode( ' ', $names ),
		'excerpt' => hri_search_plain( $post->post_excerpt ),
		'content' => mb_substr( $content, 0, HRI_SEARCH_CONTENT_CHARS, 'UTF-8' ),
	);
}

/**
 * Remove a post from the index.
 */
function hri_search_forget( int $post_id ): void {
	global $wpdb;
	$wpdb->suppress_errors( true );
	$wpdb->delete( hri_search_table( 'idx' ), array( 'post_id' => $post_id ), array( '%d' ) );
	$wpdb->delete( hri_search_table( 'docs' ), array( 'post_id' => $post_id ), array( '%d' ) );
	$wpdb->suppress_errors( false );
}

/**
 * (Re)index one post, or remove it when it is not published or is excluded.
 *
 * @return int Rows written to the word table.
 */
function hri_search_index_post( int $post_id, ?string $mode = null ): int {
	global $wpdb;
	$post = get_post( $post_id );
	hri_search_forget( $post_id );

	if ( ! $post || 'publish' !== $post->post_status || ! in_array( $post->post_type, hri_search_post_types(), true ) || '' !== $post->post_password ) {
		return 0;
	}
	if ( 'page' === $post->post_type && isset( hri_search_excluded_ids()[ $post_id ] ) ) {
		return 0;
	}

	$mode   = $mode ?? hri_search_runtime_mode();
	$lang   = hri_search_post_lang( $post_id );
	$fields = hri_search_fields( $post, $lang );

	$map = array(); // token => [ flags, body count ].
	foreach ( array( 'title' => 1, 'terms' => 2, 'excerpt' => 4, 'content' => 8 ) as $field => $flag ) {
		foreach ( hri_search_tokens( $fields[ $field ], $mode ) as $token ) {
			if ( ! isset( $map[ $token ] ) ) {
				$map[ $token ] = array( 0, 0 );
			}
			$map[ $token ][0] |= $flag;
			if ( 8 === $flag ) {
				$map[ $token ][1] = min( 255, $map[ $token ][1] + 1 );
			}
		}
	}
	if ( count( $map ) > HRI_SEARCH_ROWS_PER_DOC ) { // Keep the most telling words of a very long text.
		uasort( $map, static fn( $a, $b ) => array( $b[0], $b[1] ) <=> array( $a[0], $a[1] ) );
		$map = array_slice( $map, 0, HRI_SEARCH_ROWS_PER_DOC, true );
	}

	$hint = 0;
	if ( 'course' === $post->post_type && in_array( (string) get_post_meta( $post_id, 'hri_reg_status', true ), array( 'full', 'closed' ), true ) ) {
		$hint = 1;
	}

	$whole = hri_search_normalize( implode( ' ', array( $fields['title'], $fields['terms'], $fields['excerpt'], $fields['content'] ) ) );
	$wpdb->suppress_errors( true );
	$wpdb->insert(
		hri_search_table( 'docs' ),
		array(
			'post_id'    => $post_id,
			'lang'       => $lang,
			'post_type'  => $post->post_type,
			'hint'       => $hint,
			'menu_order' => (int) $post->menu_order,
			'pub'        => (int) strtotime( $post->post_date_gmt . ' UTC' ),
			'txt'        => str_replace( ' ', '', $whole ),
		),
		array( '%d', '%s', '%s', '%d', '%d', '%d', '%s' )
	);

	$rows = 0;
	foreach ( array_chunk( $map, 250, true ) as $part ) {
		$values = array();
		foreach ( $part as $token => $info ) {
			$values[] = $wpdb->prepare( '(%d, %s, %d, %d)', $post_id, (string) $token, $info[0], $info[1] );
		}
		$wpdb->query( 'INSERT INTO ' . hri_search_table( 'idx' ) . ' (post_id, token, fl, tf) VALUES ' . implode( ',', $values ) ); // phpcs:ignore WordPress.DB.PreparedSQL
		$rows += count( $values );
	}
	$wpdb->suppress_errors( false );
	return $rows;
}

/* -------------------------------------------------------------------------
 * Index: keeping it up to date and rebuilding
 * ---------------------------------------------------------------------- */

/**
 * Posts changed during this request (indexed once, at the end, when terms / meta / language are set).
 *
 * @param int|null $add Post ID to queue, null to read the queue.
 * @param bool     $clear Empty the queue.
 * @return int[]
 */
function hri_search_dirty( ?int $add = null, bool $clear = false ): array {
	static $queue = array();
	if ( null !== $add ) {
		$queue[ $add ] = $add;
	}
	$out = array_values( $queue );
	if ( $clear ) {
		$queue = array();
	}
	return $out;
}

/**
 * Queue a post for re-indexing if it is of an indexed type (revisions and autosaves are not).
 */
function hri_search_mark_dirty( int $post_id ): void {
	if ( in_array( get_post_type( $post_id ), hri_search_post_types(), true ) ) {
		hri_search_dirty( $post_id );
	}
}

add_action( 'save_post', static fn( $id ) => hri_search_mark_dirty( (int) $id ), 99 );
add_action(
	'transition_post_status',
	static function ( $new, $old, $post ) {
		if ( $post instanceof WP_Post ) {
			hri_search_mark_dirty( (int) $post->ID );
		}
	},
	99,
	3
);
add_action( 'set_object_terms', static fn( $object_id ) => hri_search_mark_dirty( (int) $object_id ), 99 );
add_action( 'deleted_post', static fn( $id ) => hri_search_forget( (int) $id ) );
foreach ( array( 'added_post_meta', 'updated_post_meta', 'deleted_post_meta' ) as $hri_search_meta_hook ) {
	add_action(
		$hri_search_meta_hook,
		static function ( $meta_id, $post_id, $key ) {
			if ( in_array( $key, array( 'hri_reg_status', 'hri_audience' ), true ) ) {
				hri_search_mark_dirty( (int) $post_id );
			}
		},
		10,
		3
	);
}
unset( $hri_search_meta_hook );

/**
 * End of the request: index the posts that changed.
 */
function hri_search_flush(): void {
	$ids = hri_search_dirty( null, true );
	if ( ! $ids || ! get_option( HRI_SEARCH_OPTION_INDEX ) || ! hri_search_tables_ready() ) {
		return; // No index yet: the first search (or the Settings page) builds it all.
	}
	$mode = (string) ( get_option( HRI_SEARCH_OPTION_INDEX )['mode'] ?? '' );
	if ( $mode !== hri_search_runtime_mode() ) {
		return; // Host changed: the whole index is rebuilt on the next search.
	}
	foreach ( $ids as $id ) {
		hri_search_index_post( $id, $mode );
	}
	hri_search_touch_stats();
}
add_action( 'shutdown', 'hri_search_flush', 5 );

/**
 * Numbers shown on the Settings page.
 */
function hri_search_touch_stats( ?string $mode = null, bool $built = false ): void {
	global $wpdb;
	$old = get_option( HRI_SEARCH_OPTION_INDEX );
	$old = is_array( $old ) ? $old : array();
	$wpdb->suppress_errors( true );
	$docs = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . hri_search_table( 'docs' ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	$rows = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . hri_search_table( 'idx' ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	$wpdb->suppress_errors( false );
	update_option(
		HRI_SEARCH_OPTION_INDEX,
		array(
			'mode'  => $mode ?? ( $old['mode'] ?? hri_search_runtime_mode() ),
			'docs'  => $docs,
			'rows'  => $rows,
			'built' => $built ? time() : (int) ( $old['built'] ?? time() ),
		),
		false
	);
}

/**
 * IDs of everything that belongs in the index.
 *
 * @return int[]
 */
function hri_search_all_ids(): array {
	global $wpdb;
	$types = hri_search_post_types();
	if ( ! $types ) {
		return array();
	}
	$marks = implode( ',', array_fill( 0, count( $types ), '%s' ) );
	return array_map(
		'intval',
		$wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type IN ({$marks}) ORDER BY ID", $types ) ) // phpcs:ignore WordPress.DB.PreparedSQL
	);
}

/**
 * Rebuild the index, a batch at a time (offset 0 starts from empty).
 *
 * @param int         $offset Position in the list of posts.
 * @param int         $limit  Posts in this batch, 0 = all.
 * @param string|null $mode   Tokenizer to use (default: this host's).
 * @return array{done:int,total:int,next:int|null,rows:int}
 */
function hri_search_rebuild( int $offset = 0, int $limit = 0, ?string $mode = null ): array {
	global $wpdb;
	hri_search_install();
	$mode = $mode ?? hri_search_runtime_mode();
	$ids  = hri_search_all_ids();
	$all  = count( $ids );

	if ( 0 === $offset ) {
		$wpdb->suppress_errors( true );
		$wpdb->query( 'DELETE FROM ' . hri_search_table( 'idx' ) ); // phpcs:ignore WordPress.DB.PreparedSQL
		$wpdb->query( 'DELETE FROM ' . hri_search_table( 'docs' ) ); // phpcs:ignore WordPress.DB.PreparedSQL
		$wpdb->suppress_errors( false );
		update_option( HRI_SEARCH_OPTION_INDEX, array( 'mode' => $mode, 'docs' => 0, 'rows' => 0, 'built' => 0 ), false );
		hri_search_excluded_ids( true );
	}

	set_transient( 'hri_search_building', 1, 90 ); // Visitors do not start a second build meanwhile.
	$batch = $limit > 0 ? array_slice( $ids, $offset, $limit ) : array_slice( $ids, $offset );
	$rows  = 0;
	foreach ( $batch as $id ) {
		$rows += hri_search_index_post( $id, $mode );
	}
	$next = $offset + count( $batch );
	$done = $next >= $all;
	if ( $done ) {
		delete_transient( 'hri_search_building' );
	}
	hri_search_touch_stats( $mode, $done );
	return array( 'done' => count( $batch ), 'total' => $all, 'next' => $done ? null : $next, 'rows' => $rows );
}

/**
 * Make sure there is an index that matches this host before a search (first search after
 * install, an import without a request-end flush, or a host / tokenizer change). Builds it in
 * this request, within a time limit; a lock stops visitors from building it all at once.
 */
function hri_search_ensure_index(): void {
	static $checked = false;
	if ( $checked ) {
		return;
	}
	$checked = true;
	hri_search_install();
	$meta = get_option( HRI_SEARCH_OPTION_INDEX );
	$mode = hri_search_runtime_mode();
	if ( is_array( $meta ) && ( $meta['mode'] ?? '' ) === $mode && ! empty( $meta['built'] ) ) {
		return;
	}
	if ( get_transient( 'hri_search_building' ) ) {
		return;
	}
	$start  = microtime( true );
	$offset = 0;
	do {
		$step   = hri_search_rebuild( $offset, 40, $mode );
		$offset = (int) $step['next'];
	} while ( null !== $step['next'] && ( microtime( true ) - $start ) < 25 );
}

/* -------------------------------------------------------------------------
 * Synonyms
 * ---------------------------------------------------------------------- */

/**
 * Starting groups, built from the real course titles, topics and categories. One group per line,
 * words separated by "=". Marketing edits them in Settings → การค้นหา.
 */
function hri_search_default_synonyms(): string {
	$groups = array(
		'เจรจา = ต่อรอง = เจรจาต่อรอง = negotiation = 交渉',
		'ผู้นำ = ภาวะผู้นำ = leadership = leader = リーダーシップ = リーダー',
		'โค้ช = โค้ชชิ่ง = coaching = coach = コーチング = コーチ',
		'ขาย = การขาย = sales = selling = 営業 = 販売',
		'บริการ = บริการลูกค้า = service = customer service = サービス = カスタマーサービス',
		'สื่อสาร = การสื่อสาร = communication = コミュニケーション',
		'ขัดแย้ง = ความขัดแย้ง = conflict = 対立',
		'เปลี่ยนแปลง = การเปลี่ยนแปลง = change = transformation = ทรานส์ฟอร์ม = ทรานฟอร์ม = 変革',
		'บริหารเวลา = จัดการเวลา = time management = 時間管理',
		'กลยุทธ์ = strategy = strategic = 戦略',
		'ผู้จัดการ = manager = マネージャー',
		'ผู้อำนวยการ = director = ディレクター',
		'ผู้บริหาร = executive = 経営層 = 経営者',
		'ข้อร้องเรียน = ร้องเรียน = ข้อติชม = complaint = クレーム = 苦情',
		'หลักสูตร = อบรม = ฝึกอบรม = course = training = 研修 = 講座',
		'ภายในองค์กร = in-house = 企業内研修',
		'ลีน = lean = リーン',
		'ธุรกิจ = business = ビジネス',
		'ทีม = team = チーム',
		'ทัศนคติ = วิธีคิด = mindset = マインドセット',
		'การคิด = thinking = 思考',
		'พัฒนาตนเอง = self improvement = 自己成長',
		'ออนไลน์ = online = オンライン',
		'ออนไซต์ = on-site = onsite = 対面',
		'ไฮบริด = hybrid = ハイブリッド',
	);
	return implode( "\n", (array) apply_filters( 'hri_search_default_synonyms', $groups ) );
}

/**
 * Saved synonym text (default until saved once).
 */
function hri_search_synonym_text(): string {
	$saved = get_option( HRI_SEARCH_OPTION_SYN, false );
	return is_string( $saved ) ? $saved : hri_search_default_synonyms();
}

/**
 * Split a saved line into its words ("=" or "," or "、").
 *
 * @return string[]
 */
function hri_search_synonym_members( string $line ): array {
	$parts = preg_split( '/\s*[=＝,、]\s*/u', trim( $line ) ) ?: array();
	$out   = array();
	foreach ( $parts as $part ) {
		$part = trim( $part );
		if ( '' !== $part ) {
			$out[] = $part;
		}
	}
	return array_values( array_unique( $out ) );
}

/**
 * How one typed word (a run without spaces) is searched.
 * tokens = words to look up in the index · contig = text that must appear in one piece ('' = no need)
 * · core = the word without function words at its ends.
 *
 * @return array{tokens:string[],contig:string,core:string}
 */
function hri_search_unit( string $chunk, string $mode, bool $drop_stop = true ): array {
	$core   = $chunk;
	$pieces = hri_search_pieces( $chunk, $mode );
	if ( $drop_stop && 'icu' === $mode && count( $pieces ) > 1 ) {
		// Leave out a function word at either end ("การเจรจา" → "เจรจา").
		while ( count( $pieces ) > 1 && isset( hri_search_stopwords()[ $pieces[0] ] ) ) {
			array_shift( $pieces );
		}
		while ( count( $pieces ) > 1 && isset( hri_search_stopwords()[ $pieces[ count( $pieces ) - 1 ] ] ) ) {
			array_pop( $pieces );
		}
		$core = implode( '', $pieces );
	}

	$tokens = array();
	foreach ( $pieces as $piece ) {
		$piece = hri_search_fold( $piece );
		if ( hri_search_keep_token( $piece, $drop_stop, $mode ) ) {
			$tokens[] = $piece;
		}
	}
	$tokens = array_values( array_unique( $tokens ) );

	// ICU split one typed word into several pieces, or the fallback made bigrams: the whole word
	// must also appear as one piece of text.
	$contig = '';
	if ( count( $pieces ) > 1 && preg_match( '/[\p{Thai}\p{Han}\p{Hiragana}\p{Katakana}]/u', $core ) ) {
		$contig = $core;
	}
	return array( 'tokens' => $tokens, 'contig' => $contig, 'core' => $core );
}

/**
 * Synonym groups ready for matching: each member as a list of chunks, per group.
 *
 * @return array{groups:array<int,array<int,array{tokens:string[],contigs:string[]}>>,lookup:array<string,int>}
 */
function hri_search_synonym_index( string $mode ): array {
	static $cache = array();
	$text = hri_search_synonym_text();
	$key  = $mode . '|' . md5( $text );
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$groups = array();
	$lookup = array();
	foreach ( preg_split( '/\R/u', $text ) ?: array() as $line ) {
		$line = trim( $line );
		if ( '' === $line || '#' === $line[0] ) {
			continue;
		}
		$alts = array();
		$keys = array();
		foreach ( hri_search_synonym_members( $line ) as $member ) {
			$norm = hri_search_normalize( $member );
			if ( '' === $norm ) {
				continue;
			}
			$tokens  = array();
			$contigs = array();
			$raw     = array();
			$cores   = array();
			foreach ( explode( ' ', $norm ) as $chunk ) {
				$unit    = hri_search_unit( $chunk, $mode );
				$tokens  = array_merge( $tokens, $unit['tokens'] );
				$contigs = '' !== $unit['contig'] ? array_merge( $contigs, array( $unit['contig'] ) ) : $contigs;
				$raw[]   = hri_search_fold( $chunk );
				$cores[] = hri_search_fold( $unit['core'] );
			}
			$tokens = array_values( array_unique( $tokens ) );
			if ( $tokens ) {
				$alts[] = array( 'tokens' => $tokens, 'contigs' => $contigs );
				$keys[] = implode( ' ', $raw );
				$keys[] = implode( ' ', $cores );
			}
		}
		if ( count( $alts ) > 1 ) {
			$groups[] = $alts;
			foreach ( $keys as $member_key ) {
				$lookup[ $member_key ] = $lookup[ $member_key ] ?? ( count( $groups ) - 1 );
			}
		}
	}
	$cache[ $key ] = array( 'groups' => $groups, 'lookup' => $lookup );
	return $cache[ $key ];
}

/* -------------------------------------------------------------------------
 * Query
 * ---------------------------------------------------------------------- */

/**
 * The query typed by the visitor, cleaned: plain text, one line, limited length.
 *
 * @param mixed $raw Value of ?s=.
 */
function hri_search_clean_query( $raw ): string {
	if ( ! is_string( $raw ) ) {
		return '';
	}
	$q = sanitize_text_field( wp_unslash( $raw ) );
	$q = trim( preg_replace( '/\s+/u', ' ', $q ) ?? '' );
	return mb_substr( $q, 0, HRI_SEARCH_MAX_CHARS, 'UTF-8' );
}

/**
 * The query of the current request.
 */
function hri_search_query_string(): string {
	return hri_search_clean_query( $_GET['s'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
}

/**
 * Query → terms. A term is one typed word (or a synonym group); it matches when any of its
 * alternatives matches. An alternative needs all its tokens, plus its `contigs` as pieces of text.
 *
 * @return array<int,array{alts:array<int,array{tokens:string[],contigs:string[],syn:bool}>}>
 */
function hri_search_parse_query( string $query, string $mode ): array {
	$norm   = hri_search_normalize( $query );
	$chunks = $norm ? array_slice( explode( ' ', $norm ), 0, HRI_SEARCH_MAX_UNITS ) : array();
	$syn    = hri_search_synonym_index( $mode );

	// Function words: dropped unless nothing else is left.
	$terms = hri_search_terms_from_chunks( $chunks, $mode, $syn, true );
	if ( ! $terms ) {
		$terms = hri_search_terms_from_chunks( $chunks, $mode, $syn, false );
	}
	return array_slice( $terms, 0, HRI_SEARCH_MAX_UNITS );
}

/**
 * @param string[] $chunks
 * @param array    $syn Output of hri_search_synonym_index().
 */
function hri_search_terms_from_chunks( array $chunks, string $mode, array $syn, bool $drop_stop ): array {
	$terms = array();
	$count = count( $chunks );
	for ( $i = 0; $i < $count; $i++ ) {
		// A synonym may be 1-3 typed words ("customer service").
		$matched = false;
		for ( $len = min( 3, $count - $i ); $len >= 1 && ! $matched; $len-- ) {
			$raw   = array();
			$cores = array();
			for ( $j = 0; $j < $len; $j++ ) {
				$unit    = hri_search_unit( $chunks[ $i + $j ], $mode, $drop_stop );
				$raw[]   = hri_search_fold( $chunks[ $i + $j ] );
				$cores[] = hri_search_fold( $unit['core'] );
			}
			foreach ( array( implode( ' ', $cores ), implode( ' ', $raw ) ) as $key ) {
				if ( isset( $syn['lookup'][ $key ] ) ) {
					$alts = array();
					foreach ( $syn['groups'][ $syn['lookup'][ $key ] ] as $alt ) {
						$alts[] = $alt + array( 'syn' => true );
					}
					$terms[] = array( 'alts' => $alts );
					$i      += $len - 1;
					$matched = true;
					break;
				}
			}
		}
		if ( $matched ) {
			continue;
		}
		$unit = hri_search_unit( $chunks[ $i ], $mode, $drop_stop );
		if ( $unit['tokens'] ) {
			$terms[] = array(
				'alts' => array(
					array(
						'tokens'  => $unit['tokens'],
						'contigs' => '' !== $unit['contig'] ? array( $unit['contig'] ) : array(),
						'syn'     => false,
					),
				),
			);
		}
	}
	return $terms;
}

/**
 * Language slug the search runs in ('' = no filter, without Polylang).
 */
function hri_search_current_lang(): string {
	if ( ! hri_has_polylang() ) {
		return '';
	}
	$lang = hri_current_language();
	return is_string( $lang ) ? $lang : '';
}

/**
 * Score of a word in a post: where it appears matters most (title ≫ names ≫ excerpt ≫ body).
 */
function hri_search_token_score( int $flags, int $tf ): int {
	return ( ( $flags & 1 ) ? 1000 : 0 ) + ( ( $flags & 2 ) ? 300 : 0 ) + ( ( $flags & 4 ) ? 100 : 0 ) + ( ( $flags & 8 ) ? min( $tf, 10 ) : 0 );
}

/**
 * Search. Returns the matching posts by type, best first.
 *
 * @param string $raw  Query as typed.
 * @param array  $args lang (default: current), types (default: all indexed types).
 * @return array{query:string,partial:bool,ids:array<string,int[]>,total:int,ms:float,mode:string}
 */
function hri_search_by_words( string $raw, array $args = array() ): array {
	global $wpdb;
	static $memo = array();

	$start = microtime( true );
	$query = hri_search_clean_query( $raw );
	$lang  = array_key_exists( 'lang', $args ) ? (string) $args['lang'] : hri_search_current_lang();
	$types = $args['types'] ?? hri_search_post_types();
	$memo_key = $query . '|' . $lang . '|' . implode( ',', $types ) . '|' . md5( hri_search_synonym_text() );
	if ( isset( $memo[ $memo_key ] ) ) {
		return $memo[ $memo_key ];
	}

	$result = array( 'query' => $query, 'partial' => false, 'ids' => array_fill_keys( $types, array() ), 'total' => 0, 'ms' => 0.0, 'mode' => '' );
	if ( '' === $query || ! $types ) {
		return $memo[ $memo_key ] = $result;
	}

	hri_search_ensure_index();
	$meta = get_option( HRI_SEARCH_OPTION_INDEX );
	$mode = is_array( $meta ) && ! empty( $meta['mode'] ) ? (string) $meta['mode'] : hri_search_runtime_mode();
	$result['mode'] = $mode;
	if ( 'icu' === $mode && ! hri_search_icu_available() ) {
		$mode = 'ngram'; // Index built elsewhere with ICU: the best we can do until it is rebuilt.
	}

	$terms = hri_search_parse_query( $query, $mode );
	if ( ! $terms || ! hri_search_tables_ready() ) {
		return $memo[ $memo_key ] = $result;
	}

	// 1. Words → rows of the index, limited to the language and types.
	$tokens = array();
	foreach ( $terms as $term ) {
		foreach ( $term['alts'] as $alt ) {
			foreach ( $alt['tokens'] as $token ) {
				$tokens[ $token ] = true;
			}
		}
	}
	$tokens = array_slice( array_keys( $tokens ), 0, 80 );
	$sql    = 'SELECT i.post_id, i.token, i.fl, i.tf, d.post_type, d.hint, d.menu_order, d.pub FROM ' . hri_search_table( 'idx' ) . ' i INNER JOIN ' . hri_search_table( 'docs' )
		. ' d ON d.post_id = i.post_id WHERE i.token IN (' . implode( ',', array_fill( 0, count( $tokens ), '%s' ) ) . ') AND d.post_type IN (' . implode( ',', array_fill( 0, count( $types ), '%s' ) ) . ')';
	$params = array_merge( $tokens, $types );
	if ( '' !== $lang ) {
		$default = function_exists( 'pll_default_language' ) ? (string) pll_default_language() : 'th';
		$sql    .= ' AND (d.lang = %s' . ( $lang === $default ? " OR d.lang = ''" : '' ) . ')';
		$params[] = $lang;
	}
	$rows = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL
	if ( ! $rows ) {
		return $memo[ $memo_key ] = $result;
	}

	$posts = array();
	foreach ( $rows as $row ) {
		$id = (int) $row['post_id'];
		if ( ! isset( $posts[ $id ] ) ) {
			$posts[ $id ] = array(
				'type'  => (string) $row['post_type'],
				'hint'  => (int) $row['hint'],
				'order' => (int) $row['menu_order'],
				'pub'   => (int) $row['pub'],
				'tok'   => array(),
			);
		}
		$posts[ $id ]['tok'][ $row['token'] ] = hri_search_token_score( (int) $row['fl'], (int) $row['tf'] );
	}

	// 2. Which alternatives are complete, and which of those need a "one piece of text" check.
	$need = array();
	foreach ( $posts as $id => $post ) {
		foreach ( $terms as $ti => $term ) {
			foreach ( $term['alts'] as $alt ) {
				if ( $alt['contigs'] && ! array_diff( $alt['tokens'], array_keys( $post['tok'] ) ) ) {
					$need[ $id ] = true;
				}
			}
		}
	}
	$texts = array();
	foreach ( array_chunk( array_keys( $need ), 100 ) as $chunk ) {
		$marks = implode( ',', array_fill( 0, count( $chunk ), '%d' ) );
		foreach ( (array) $wpdb->get_results( $wpdb->prepare( 'SELECT post_id, txt FROM ' . hri_search_table( 'docs' ) . " WHERE post_id IN ({$marks})", $chunk ), ARRAY_A ) as $row ) { // phpcs:ignore WordPress.DB.PreparedSQL
			$texts[ (int) $row['post_id'] ] = (string) $row['txt'];
		}
	}

	// 3. Score every post.
	$hits     = array();
	$term_cnt = count( $terms );
	foreach ( $posts as $id => $post ) {
		$matched = 0;
		$score   = 0.0;
		foreach ( $terms as $term ) {
			$best = null;
			foreach ( $term['alts'] as $alt ) {
				$sum = 0;
				foreach ( $alt['tokens'] as $token ) {
					if ( ! isset( $post['tok'][ $token ] ) ) {
						continue 2;
					}
					$sum += $post['tok'][ $token ];
				}
				foreach ( $alt['contigs'] as $contig ) {
					if ( ! str_contains( $texts[ $id ] ?? '', str_replace( ' ', '', $contig ) ) ) {
						continue 2;
					}
				}
				$value = ( $sum / max( 1, count( $alt['tokens'] ) ) ) * ( $alt['syn'] ? 0.9 : 1.0 ); // A word typed as is beats its synonym.
				$best  = null === $best ? $value : max( $best, $value );
			}
			if ( null !== $best ) {
				++$matched;
				$score += $best;
			}
		}
		if ( $matched > 0 ) {
			$hits[ $id ] = array( 'm' => $matched, 'score' => $score ) + $post;
		}
	}

	// All words must match; when no page has them all, show the ones with the most (and say so).
	$full = array_filter( $hits, static fn( $h ) => $h['m'] === $term_cnt );
	if ( $full ) {
		$hits = $full;
	} else {
		$result['partial'] = $term_cnt > 1 && (bool) $hits;
	}

	// 4. Order inside each type: more words first (partial results), open courses before full /
	// closed ones, then the score, then (courses) the menu order and (articles, pages) the newest.
	$by_type = array();
	foreach ( $hits as $id => $hit ) {
		$by_type[ $hit['type'] ][ $id ] = $hit;
	}
	foreach ( $by_type as $type => $group ) {
		uasort(
			$group,
			static function ( $a, $b ) use ( $type ) {
				if ( $a['m'] !== $b['m'] ) {
					return $b['m'] <=> $a['m'];
				}
				if ( 'course' === $type && $a['hint'] !== $b['hint'] ) {
					return $a['hint'] <=> $b['hint'];
				}
				if ( $a['score'] !== $b['score'] ) {
					return $b['score'] <=> $a['score'];
				}
				if ( 'course' === $type && $a['order'] !== $b['order'] ) {
					return $a['order'] <=> $b['order'];
				}
				return $b['pub'] <=> $a['pub'];
			}
		);
		$result['ids'][ $type ] = array_map( 'intval', array_keys( $group ) );
	}
	$result['total'] = count( $hits );
	$result['ms']    = round( ( microtime( true ) - $start ) * 1000, 1 );
	return $memo[ $memo_key ] = $result;
}

/**
 * Last resort when no word matched: the typed text as one piece inside the page text (finds words the
 * segmenter splits differently in a sentence, new loan words, names). Thai / CJK queries only.
 *
 * @param string $query Cleaned query.
 * @param string $lang  Language code or ''.
 * @param array  $types Post types.
 * @return int[][] Post ids by type, best first.
 */
function hri_search_by_substring( string $query, string $lang, array $types ): array {
	global $wpdb;
	$out  = array_fill_keys( $types, array() );
	$text = str_replace( ' ', '', hri_search_normalize( $query ) );
	if ( mb_strlen( $text, 'UTF-8' ) < 2 || ! preg_match( '/[\p{Thai}\p{Han}\p{Hiragana}\p{Katakana}]/u', $text ) || ! hri_search_tables_ready() ) {
		return $out;
	}
	$sql    = 'SELECT post_id, post_type, hint, menu_order, pub FROM ' . hri_search_table( 'docs' ) . ' WHERE txt LIKE %s AND post_type IN (' . implode( ',', array_fill( 0, count( $types ), '%s' ) ) . ')';
	$params = array_merge( array( '%' . $wpdb->esc_like( $text ) . '%' ), $types );
	if ( '' !== $lang ) {
		$default = function_exists( 'pll_default_language' ) ? (string) pll_default_language() : 'th';
		$sql    .= ' AND (lang = %s' . ( $lang === $default ? " OR lang = ''" : '' ) . ')';
		$params[] = $lang;
	}
	$rows = $wpdb->get_results( $wpdb->prepare( $sql . ' LIMIT 300', $params ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL
	usort(
		$rows,
		static function ( $a, $b ) {
			if ( 'course' === $a['post_type'] && 'course' === $b['post_type'] && $a['hint'] !== $b['hint'] ) {
				return (int) $a['hint'] <=> (int) $b['hint'];
			}
			if ( 'course' === $a['post_type'] && 'course' === $b['post_type'] && $a['menu_order'] !== $b['menu_order'] ) {
				return (int) $a['menu_order'] <=> (int) $b['menu_order'];
			}
			return (int) $b['pub'] <=> (int) $a['pub'];
		}
	);
	foreach ( $rows as $row ) {
		$out[ $row['post_type'] ][] = (int) $row['post_id'];
	}
	return $out;
}

/**
 * Search. Returns the matching posts by type, best first (see hri_search_by_words; falls back to
 * a plain text match when no word matched).
 *
 * @param string $raw  Query as typed.
 * @param array  $args lang (default: current), types (default: all indexed types).
 * @return array{query:string,partial:bool,ids:array<string,int[]>,total:int,ms:float,mode:string}
 */
function hri_search( string $raw, array $args = array() ): array {
	static $memo = array();
	$key = $raw . '|' . wp_json_encode( $args ) . '|' . hri_search_current_lang();
	if ( isset( $memo[ $key ] ) ) {
		return $memo[ $key ];
	}
	$start  = microtime( true );
	$result = hri_search_by_words( $raw, $args );
	if ( 0 === $result['total'] && '' !== $result['query'] ) {
		$lang  = array_key_exists( 'lang', $args ) ? (string) $args['lang'] : hri_search_current_lang();
		$types = $args['types'] ?? hri_search_post_types();
		$ids   = hri_search_by_substring( $result['query'], $lang, $types );
		$total = array_sum( array_map( 'count', $ids ) );
		if ( $total ) {
			$result['ids']     = $ids;
			$result['total']   = $total;
			$result['partial'] = false;
			$result['ms']      = round( ( microtime( true ) - $start ) * 1000, 1 );
		}
	}
	return $memo[ $key ] = $result;
}

/* -------------------------------------------------------------------------
 * WordPress integration
 * ---------------------------------------------------------------------- */

/**
 * The results page does its own searching: skip WordPress' LIKE query for the main search.
 */
add_filter(
	'posts_pre_query',
	static function ( $posts, $query ) {
		if ( $query instanceof WP_Query && ! is_admin() && $query->is_main_query() && $query->is_search() ) {
			return array();
		}
		return $posts;
	},
	10,
	2
);

/**
 * Page title of the results (core's text is English).
 */
add_filter(
	'document_title_parts',
	static function ( $parts ) {
		if ( is_search() ) {
			$q              = hri_search_query_string();
			/* translators: %s: what the visitor searched for */
			$parts['title'] = '' === $q ? __( 'ค้นหา', 'hri-core' ) : sprintf( __( 'ผลการค้นหา “%s”', 'hri-core' ), $q );
		}
		return $parts;
	}
);

/**
 * Where a search form sends the visitor: the home page of the current language, so the results
 * are in that language.
 */
function hri_search_action_url(): string {
	if ( hri_has_polylang() && function_exists( 'pll_home_url' ) ) {
		return trailingslashit( (string) pll_home_url() );
	}
	return home_url( '/' );
}

/**
 * Short text for a result: the excerpt, or the start of the page text.
 */
function hri_search_snippet( WP_Post $post, int $chars = 150 ): string {
	$text = '' !== trim( $post->post_excerpt ) ? hri_search_plain( $post->post_excerpt ) : hri_search_plain( $post->post_content );
	if ( mb_strlen( $text, 'UTF-8' ) > $chars ) {
		$text = rtrim( mb_substr( $text, 0, $chars, 'UTF-8' ) ) . '…';
	}
	return $text;
}

/**
 * Result row for an article or a page (text only: no image, so the page stays light).
 */
function hri_search_render_item( int $post_id ): string {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	$meta = array();
	if ( 'post' === $post->post_type ) {
		$cats = get_the_category( $post_id );
		if ( $cats ) {
			$meta[] = esc_html( $cats[0]->name );
		}
		$meta[] = '<time datetime="' . esc_attr( get_the_date( 'c', $post ) ) . '">' . esc_html( get_the_date( '', $post ) ) . '</time>';
	}
	$snippet = hri_search_snippet( $post );

	ob_start();
	?>
	<li class="hri-sr__item">
		<?php if ( $meta ) : ?>
			<p class="hri-sr__meta"><?php echo implode( ' · ', $meta ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?></p>
		<?php endif; ?>
		<h3 class="hri-sr__title"><a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h3>
		<?php if ( '' !== $snippet ) : ?>
			<p class="hri-sr__snippet"><?php echo esc_html( $snippet ); ?></p>
		<?php endif; ?>
	</li>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 * WP-CLI:  wp hri search reindex [--tokenizer=icu|ngram]
 *          wp hri search query <text> [--lang=th] [--limit=10]
 * ---------------------------------------------------------------------- */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Search index tools.
	 */
	class HRI_Search_CLI {
		/**
		 * Rebuild the search index (all courses, articles and pages).
		 *
		 * ## OPTIONS
		 *
		 * [--tokenizer=<mode>]
		 * : icu (default when ext-intl exists) or ngram.
		 */
		public function reindex( $args, $assoc ) {
			$mode = $assoc['tokenizer'] ?? null;
			if ( null !== $mode && ! in_array( $mode, array( 'icu', 'ngram' ), true ) ) {
				WP_CLI::error( 'tokenizer must be icu or ngram' );
			}
			if ( 'icu' === $mode && ! hri_search_icu_available() ) {
				WP_CLI::error( 'ext-intl is not available.' );
			}
			$start = microtime( true );
			hri_search_rebuild( 0, 0, $mode );
			$stats = get_option( HRI_SEARCH_OPTION_INDEX );
			WP_CLI::success( sprintf( '%d posts, %d words in %.1f s (mode %s).', $stats['docs'], $stats['rows'], microtime( true ) - $start, $stats['mode'] ) );
		}

		/**
		 * Run a search and print the results.
		 *
		 * ## OPTIONS
		 *
		 * <text>
		 * : What to search for.
		 *
		 * [--lang=<code>]
		 * : Language (th, en, ja). Default th.
		 *
		 * [--limit=<n>]
		 * : Results to list per type. Default 5.
		 */
		public function query( $args, $assoc ) {
			$lang   = $assoc['lang'] ?? 'th';
			$limit  = (int) ( $assoc['limit'] ?? 5 );
			$result = hri_search( $args[0], array( 'lang' => $lang ) );
			WP_CLI::log( sprintf( 'query "%s" · lang %s · mode %s · %d results%s · %s ms', $result['query'], $lang, $result['mode'], $result['total'], $result['partial'] ? ' (partial)' : '', $result['ms'] ) );
			foreach ( $result['ids'] as $type => $ids ) {
				WP_CLI::log( sprintf( '  %s: %d', $type, count( $ids ) ) );
				foreach ( array_slice( $ids, 0, $limit ) as $id ) {
					WP_CLI::log( sprintf( '    #%d %s', $id, get_the_title( $id ) ) );
				}
			}
		}
	}

	WP_CLI::add_command( 'hri search', 'HRI_Search_CLI' );
}

/* -------------------------------------------------------------------------
 * Empty state: where to go when nothing matches (popular courses, all courses, contact).
 * ---------------------------------------------------------------------- */

/**
 * Open courses of the current language (the first ones in the course order) + links.
 */
function hri_search_empty_suggestions(): string {
	$args = array(
		'post_type'      => 'course',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery -- 3 rows, empty-result page only.
			array( 'key' => 'hri_reg_status', 'value' => 'open' ),
		),
	);
	if ( hri_has_polylang() ) {
		$args['lang'] = hri_search_current_lang();
	}
	$ids = get_posts( $args );
	if ( ! $ids ) {
		unset( $args['meta_query'] );
		$ids = get_posts( $args );
	}

	ob_start();
	echo '<section class="hri-sr__empty" aria-labelledby="hri-sr-suggest">';
	if ( $ids ) {
		echo '<h2 class="hri-sr__heading" id="hri-sr-suggest">' . esc_html__( 'หลักสูตรแนะนำ', 'hri-core' ) . '</h2><div class="hri-grid">';
		foreach ( $ids as $id ) {
			echo hri_course_card( (int) $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the card.
		}
		echo '</div>';
	}
	printf(
		'<p class="hri-sr__links"><a class="hri-sr__link" href="%1$s">%2$s</a> <a class="hri-sr__link" href="%3$s">%4$s</a></p>',
		esc_url( function_exists( 'hri_localize_url' ) ? hri_localize_url( '/public-training/' ) : home_url( '/public-training/' ) ),
		esc_html__( 'ดูหลักสูตรทั้งหมด', 'hri-core' ),
		esc_url( hri_contact_url() ),
		esc_html__( 'ติดต่อเรา', 'hri-core' )
	);
	echo '</section>';
	return (string) ob_get_clean();
}
