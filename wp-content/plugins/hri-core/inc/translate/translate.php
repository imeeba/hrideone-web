<?php
/**
 * Automatic translation TH → EN / JA (see docs/AUTO-TRANSLATE.md).
 *
 * The marketing team writes Thai only. When a Thai page, course, trainer (or an article that was
 * ticked) is published or updated, a queue job translates it into the other languages and keeps the
 * Polylang translation up to date:
 *
 * - Segments (segments.php): title, excerpt, translated course fields and the HTML of every text
 *   block. A translated post is rebuilt from the Thai block tree, so its structure follows Thai.
 * - Per translated post the meta `_hri_tr` remembers, per segment, a hash of the Thai text it was
 *   made from (`src`) and of the machine translation (`mt`). A segment whose current text is not the
 *   machine text was edited by a person (state `human`): it is never overwritten. If the Thai text of
 *   such a segment changes afterwards the segment becomes `outdated`; the new machine translation
 *   is kept beside it as a proposal and the reviewer decides.
 * - Status of a translated post: machine | reviewed | outdated | (queued / error on the source job).
 * - Translations that exist but have no `_hri_tr` (the articles and pages translated by hand or
 *   imported) are left alone until someone chooses "use automatic translation" for the page.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_TR_META = '_hri_tr';

/**
 * Settings (Settings → การแปล) with defaults.
 *
 * @return array{types:array<string,int>,policy:array<string,string>,reviewers:array<string,string>,tone:array<string,string>}
 */
function hri_tr_settings(): array {
	$saved = get_option( 'hri_tr_settings' );
	$saved = is_array( $saved ) ? $saved : array();
	return array(
		'types'     => array_merge( array( 'page' => 1, 'course' => 1, 'trainer' => 1, 'post' => 0 ), (array) ( $saved['types'] ?? array() ) ),
		'policy'    => array_merge( array( 'en' => 'publish', 'ja' => 'publish_noindex' ), (array) ( $saved['policy'] ?? array() ) ),
		'reviewers' => array_merge( array( 'en' => '', 'ja' => '' ), (array) ( $saved['reviewers'] ?? array() ) ),
		'tone'      => array_merge( array( 'en' => '', 'ja' => '' ), (array) ( $saved['tone'] ?? array() ) ),
	);
}

/**
 * Post types that can be translated automatically.
 *
 * @return string[]
 */
function hri_tr_post_types(): array {
	return array( 'page', 'course', 'trainer', 'post' );
}

/**
 * Whether the module is usable: Polylang set up (th/en/ja) and a provider configured.
 */
function hri_tr_ready(): bool {
	return hri_has_polylang() && hri_tr_configured();
}

/**
 * Slug of the language of a post ('' when unknown).
 */
function hri_tr_post_lang( int $post_id ): string {
	return function_exists( 'pll_get_post_language' ) ? (string) pll_get_post_language( $post_id, 'slug' ) : '';
}

/**
 * Whether a post is a Thai (source language) post of a translatable type.
 */
function hri_tr_is_source( WP_Post $post ): bool {
	return in_array( $post->post_type, hri_tr_post_types(), true )
		&& function_exists( 'pll_default_language' )
		&& hri_tr_post_lang( $post->ID ) === (string) pll_default_language();
}

/**
 * Languages (en, ja) a source post is translated into.
 *
 * @return string[]
 */
function hri_tr_target_langs( WP_Post $source ): array {
	$chosen = get_post_meta( $source->ID, '_hri_tr_langs', true );
	if ( is_array( $chosen ) ) {
		$langs = $chosen;
	} else {
		$langs = ! empty( hri_tr_settings()['types'][ $source->post_type ] ) ? array( 'en', 'ja' ) : array();
	}
	return array_values( array_intersect( $langs, array( 'en', 'ja' ), (array) pll_languages_list() ) );
}

/**
 * Translation bookkeeping of a translated post.
 *
 * @return array<string,mixed>|null
 */
function hri_tr_state( int $target_id ): ?array {
	$state = get_post_meta( $target_id, HRI_TR_META, true );
	return is_array( $state ) ? $state : null;
}

/**
 * Job info of a source post and language (queued / running / error).
 *
 * @return array<string,mixed>
 */
function hri_tr_job( int $source_id, string $lang ): array {
	$job = get_post_meta( $source_id, "_hri_tr_job_{$lang}", true );
	return is_array( $job ) ? $job : array();
}

/**
 * Saves job info.
 *
 * @param array<string,mixed> $job Job.
 */
function hri_tr_job_save( int $source_id, string $lang, array $job ): void {
	if ( $job ) {
		update_post_meta( $source_id, "_hri_tr_job_{$lang}", $job );
	} else {
		delete_post_meta( $source_id, "_hri_tr_job_{$lang}" );
	}
}

/**
 * Adds this month's usage (shown in Settings → การแปล; nothing is limited here: the quota is the one of
 * the connected API account).
 *
 * @param array{in:int,out:int,calls:int} $usage Usage of one run.
 */
function hri_tr_usage_add( array $usage ): void {
	$all = get_option( 'hri_tr_usage' );
	$all = is_array( $all ) ? $all : array();
	$ym  = gmdate( 'Y-m' );
	$row = array_merge( array( 'in' => 0, 'out' => 0, 'calls' => 0 ), (array) ( $all[ $ym ] ?? array() ) );
	foreach ( array( 'in', 'out', 'calls' ) as $k ) {
		$row[ $k ] += (int) ( $usage[ $k ] ?? 0 );
	}
	$all[ $ym ] = $row;
	update_option( 'hri_tr_usage', array_slice( $all, -24, null, true ), false );
}

/* ----------------------------------------------------------------------
 * Hooks: ids on blocks, and queueing when the Thai post is saved
 * ---------------------------------------------------------------------- */

// Every text block of a translatable post gets its metadata.hriId when the post is saved.
add_filter(
	'wp_insert_post_data',
	static function ( $data, $postarr ) {
		if ( ! empty( $GLOBALS['hri_tr_busy'] ) || ! in_array( $data['post_type'] ?? '', hri_tr_post_types(), true ) || in_array( $data['post_status'] ?? '', array( 'auto-draft', 'inherit' ), true ) ) {
			return $data;
		}
		$content = wp_unslash( (string) ( $data['post_content'] ?? '' ) );
		$with    = hri_tr_content_with_ids( $content );
		if ( $with !== $content ) {
			$data['post_content'] = wp_slash( $with );
		}
		return $data;
	},
	20,
	2
);

add_action(
	'save_post',
	static function ( $post_id, $post ) {
		if ( ! empty( $GLOBALS['hri_tr_busy'] ) || ! $post instanceof WP_Post || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! hri_tr_ready() ) {
			return;
		}
		if ( ! in_array( $post->post_type, hri_tr_post_types(), true ) ) {
			return;
		}
		if ( hri_tr_is_source( $post ) ) {
			if ( 'publish' !== $post->post_status ) {
				return;
			}
			foreach ( hri_tr_target_langs( $post ) as $lang ) {
				$target = (int) pll_get_post( $post_id, $lang );
				// A translation without bookkeeping is a manual one: left alone.
				if ( ! $target || hri_tr_state( $target ) ) {
					hri_tr_enqueue( $post_id, $lang );
				}
			}
		} elseif ( hri_tr_state( $post_id ) ) {
			hri_tr_refresh_states( $post_id ); // A person saved a translation: find what they edited.
		}
	},
	40,
	2
);

/* ----------------------------------------------------------------------
 * Queue (WP-Cron single events, one per post and language)
 * ---------------------------------------------------------------------- */

/**
 * Puts a translation job in the queue (saving many times within the delay makes one job).
 *
 * @param int $delay Seconds to wait (default 60).
 */
function hri_tr_enqueue( int $source_id, string $lang, int $delay = 60 ): void {
	$job = hri_tr_job( $source_id, $lang );
	hri_tr_job_save( $source_id, $lang, array( 'state' => 'queued', 'attempt' => (int) ( $job['attempt'] ?? 0 ), 'at' => time() ) );
	if ( ! wp_next_scheduled( 'hri_translate_run', array( $source_id, $lang ) ) ) {
		wp_schedule_single_event( time() + max( 0, $delay ), 'hri_translate_run', array( $source_id, $lang ) );
	}
}

add_action(
	'hri_translate_run',
	static function ( $source_id, $lang ) {
		$source_id = (int) $source_id;
		$lang      = (string) $lang;
		$job       = hri_tr_job( $source_id, $lang );
		try {
			$result = hri_tr_run( $source_id, $lang );
			if ( ! empty( $result['pending'] ) ) { // Not finished inside the time budget: go on in a moment.
				hri_tr_job_save( $source_id, $lang, array( 'state' => 'running', 'attempt' => 0, 'at' => time() ) );
				wp_schedule_single_event( time() + 5, 'hri_translate_run', array( $source_id, $lang ) );
				return;
			}
			hri_tr_job_save( $source_id, $lang, array() );
		} catch ( HRI_Translate_Error $e ) {
			$attempt = (int) ( $job['attempt'] ?? 0 ) + 1;
			$retry   = in_array( $e->kind, array( 'transient', 'bad_output' ), true ) && $attempt < 3;
			hri_tr_job_save(
				$source_id,
				$lang,
				array(
					'state'   => $retry ? 'queued' : 'error',
					'kind'    => $e->kind,
					'message' => $e->getMessage(),
					'attempt' => $attempt,
					'at'      => time(),
				)
			);
			if ( $retry ) {
				wp_schedule_single_event( time() + 60 * $attempt * $attempt, 'hri_translate_run', array( $source_id, $lang ) );
			}
		}
	},
	10,
	2
);

/* ----------------------------------------------------------------------
 * The translation itself
 * ---------------------------------------------------------------------- */

/**
 * Translates (or updates the translation of) one source post into one language.
 *
 * @param int                 $source_id Thai post.
 * @param string              $lang      en | ja.
 * @param array<string,mixed> $opts      `adopt` (take over an existing manual translation as human text), `budget` (seconds).
 * @return array{status:string,pending?:bool,target?:int,translated?:int}
 * @throws HRI_Translate_Error On provider errors.
 */
function hri_tr_run( int $source_id, string $lang, array $opts = array() ): array {
	if ( ! hri_tr_ready() ) {
		throw new HRI_Translate_Error( 'config', __( 'ยังไม่ได้ตั้งค่าการแปลอัตโนมัติ', 'hri-core' ) );
	}
	$source = get_post( $source_id );
	if ( ! $source || 'publish' !== $source->post_status || ! hri_tr_is_source( $source ) || ! in_array( $lang, array( 'en', 'ja' ), true ) ) {
		return array( 'status' => 'skipped' );
	}
	$lock = "hri_tr_lock_{$source_id}_{$lang}";
	if ( get_transient( $lock ) ) {
		return array( 'status' => 'locked', 'pending' => true );
	}
	set_transient( $lock, 1, 5 * MINUTE_IN_SECONDS );
	try {
		return hri_tr_run_locked( $source, $lang, $opts );
	} finally {
		delete_transient( $lock );
	}
}

/**
 * hri_tr_run() while holding the lock.
 *
 * @param WP_Post             $source Thai post.
 * @param string              $lang   en | ja.
 * @param array<string,mixed> $opts   Options.
 * @return array{status:string,pending?:bool,target?:int,translated?:int}
 * @throws HRI_Translate_Error On provider errors.
 */
function hri_tr_run_locked( WP_Post $source, string $lang, array $opts ): array {
	global $wpdb;
	$started = microtime( true );
	$budget  = (float) ( $opts['budget'] ?? 25 );

	// 1. The Thai content has ids on its text blocks (a post saved before this module existed has none).
	$with_ids = hri_tr_content_with_ids( (string) $source->post_content );
	if ( $with_ids !== $source->post_content ) {
		$wpdb->update( $wpdb->posts, array( 'post_content' => $with_ids ), array( 'ID' => $source->ID ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		clean_post_cache( $source->ID );
		$source = get_post( $source->ID );
	}

	$target_id = (int) pll_get_post( $source->ID, $lang );
	$state     = $target_id ? hri_tr_state( $target_id ) : null;
	if ( $target_id && ! $state && empty( $opts['adopt'] ) ) {
		return array( 'status' => 'manual', 'target' => $target_id ); // A translation made by hand: not ours to overwrite.
	}
	$source_segments = hri_tr_post_segments( $source );
	$target_segments = $target_id ? hri_tr_post_segments( get_post( $target_id ) ) : array();
	$segs            = is_array( $state['segs'] ?? null ) ? $state['segs'] : array();

	// 2. Adopting a manual translation: its blocks get the ids of the Thai blocks (same structure needed),
	// and everything in it counts as written by a person, as of today's Thai.
	if ( $target_id && ! $state ) {
		$aligned = hri_tr_adopt_ids( $source, get_post( $target_id ) );
		if ( null === $aligned ) {
			throw new HRI_Translate_Error( 'structure', __( 'โครงสร้างบล็อกของหน้าแปลไม่ตรงกับหน้าไทย จึงเริ่มใช้การแปลอัตโนมัติกับหน้านี้ไม่ได้ — ปรับให้ตรงกันก่อน หรือสร้างหน้าแปลใหม่', 'hri-core' ) );
		}
		$target_segments = hri_tr_post_segments( get_post( $target_id ) );
		foreach ( $source_segments as $key => $value ) {
			if ( isset( $target_segments[ $key ] ) ) {
				$segs[ $key ] = array( 'src' => hri_tr_hash( $value ), 'mt' => '', 'st' => 'human' );
			}
		}
	}

	// 3. What has to be translated.
	$mode = array(); // key => new | replace | proposal.
	foreach ( $source_segments as $key => $value ) {
		$cur = $target_segments[ $key ] ?? null;
		$rec = $segs[ $key ] ?? null;
		if ( null === $cur || null === $rec ) {
			$mode[ $key ] = 'new';
			continue;
		}
		if ( hri_tr_hash( $value ) === $rec['src'] ) {
			continue; // Thai unchanged.
		}
		$human = hri_tr_hash( $cur ) !== $rec['mt'];
		if ( ! $human ) {
			$mode[ $key ] = 'replace';
		} elseif ( ( $rec['prop_src'] ?? '' ) !== hri_tr_hash( $value ) ) {
			$mode[ $key ] = 'proposal'; // Edited by a person: translate again only as a proposal.
		}
	}

	// 4. Translate in batches (results of an earlier, unfinished run are reused).
	$job_key = "hri_tr_part_{$source->ID}_{$lang}";
	$part    = get_transient( $job_key );
	$part    = is_array( $part ) ? $part : array();
	$done    = array();
	foreach ( $mode as $key => $kind ) {
		if ( isset( $part[ $key ] ) && $part[ $key ]['src'] === hri_tr_hash( $source_segments[ $key ] ) ) {
			$done[ $key ] = $part[ $key ]['text'];
		}
	}
	$todo = array_diff_key( $mode, $done );
	$used = array( 'in' => 0, 'out' => 0, 'calls' => 0 );
	if ( $todo ) {
		$tone     = hri_tr_tone( $lang );
		$batch    = array();
		$size     = 0;
		$flush    = static function () use ( &$batch, &$size, &$done, &$part, &$used, $lang, $tone, $source_segments ) {
			if ( ! $batch ) {
				return;
			}
			$res = hri_tr_translate_items( $batch, $lang, array( 'tone' => $tone, 'glossary' => hri_tr_glossary_for( $batch, $lang ) ) );
			foreach ( $res['translations'] as $key => $text ) {
				if ( isset( $batch[ $key ] ) ) {
					$done[ $key ] = $text;
					$part[ $key ] = array( 'src' => hri_tr_hash( $source_segments[ $key ] ), 'text' => $text );
				}
			}
			foreach ( $used as $k => $v ) {
				$used[ $k ] = $v + (int) ( $res['usage'][ $k ] ?? 0 );
			}
			$batch = array();
			$size  = 0;
		};
		$over_time = false;
		foreach ( array_keys( $todo ) as $key ) {
			$value = $source_segments[ $key ];
			if ( $batch && ( $size + strlen( $value ) > 3500 || count( $batch ) >= 25 ) ) {
				$flush();
				if ( microtime( true ) - $started > $budget ) {
					$over_time = true;
					break;
				}
			}
			$batch[ $key ] = $value;
			$size         += strlen( $value );
		}
		if ( ! $over_time ) {
			$flush();
		}
		if ( $used['calls'] ) {
			hri_tr_usage_add( $used );
		}
		set_transient( $job_key, $part, HOUR_IN_SECONDS );
		if ( array_diff_key( $mode, $done ) ) {
			return array( 'status' => 'running', 'pending' => true );
		}
	}
	delete_transient( $job_key );

	// 5. Make sure the translated post exists.
	if ( ! $target_id ) {
		$target_id = hri_tr_create_target( $source, $lang, $done['title'] ?? $source->post_title );
	}

	// 6. Values to write, and the new bookkeeping.
	$values   = array();
	$new_segs = array();
	$outdated = false;
	foreach ( $source_segments as $key => $value ) {
		$cur = $target_segments[ $key ] ?? null;
		$rec = $segs[ $key ] ?? null;
		$kind = $mode[ $key ] ?? '';
		if ( 'new' === $kind || 'replace' === $kind ) {
			$values[ $key ]   = $done[ $key ];
			$new_segs[ $key ] = array( 'src' => hri_tr_hash( $value ), 'mt' => hri_tr_hash( $done[ $key ] ), 'st' => 'machine' );
			continue;
		}
		$values[ $key ] = $cur ?? '';
		$rec            = $rec ?? array( 'src' => hri_tr_hash( $value ), 'mt' => '', 'st' => 'human' );
		if ( 'proposal' === $kind ) {
			$rec['st']       = 'outdated';
			$rec['prop']     = $done[ $key ];
			$rec['prop_src'] = hri_tr_hash( $value );
			$rec['new']      = $value; // The new Thai text, shown to the reviewer beside the current translation.
		}
		if ( 'outdated' === ( $rec['st'] ?? '' ) ) {
			$outdated = true;
		}
		$new_segs[ $key ] = $rec;
	}
	// Strings a person wrote in blocks that no longer exist in Thai are not lost: kept for the reviewer.
	$orphans = (array) ( $state['orphans'] ?? array() );
	foreach ( $segs as $key => $rec ) {
		if ( ! isset( $source_segments[ $key ] ) && isset( $target_segments[ $key ] ) && hri_tr_hash( $target_segments[ $key ] ) !== ( $rec['mt'] ?? '' ) ) {
			$orphans[ $key ] = $target_segments[ $key ];
		}
	}
	hri_tr_write_target( $target_id, $source, $values );

	$changed = (bool) array_intersect( array_keys( $mode ), array_keys( array_filter( $new_segs, static fn( $r ) => 'machine' === $r['st'] ) ) );
	$prev    = $state['status'] ?? 'machine';
	$status  = $outdated || $orphans ? 'outdated' : ( ( $changed || ! $state ) ? 'machine' : $prev );
	if ( ! $state && ! $changed && ! $outdated && ! $orphans ) {
		$status = 'reviewed'; // Taken over from a manual translation that was already live: not noindexed.
	}
	if ( ! $outdated && ! $orphans && 'outdated' === $prev && ! $changed ) {
		$status = 'machine';
	}
	update_post_meta(
		$target_id,
		HRI_TR_META,
		array(
			'v'       => 1,
			'status'  => $status,
			'lang'    => $lang,
			'source'  => $source->ID,
			'segs'    => $new_segs,
			'orphans' => $orphans,
			'at'      => time(),
		)
	);
	hri_tr_sync_noindex_flag( $target_id );
	return array( 'status' => $status, 'target' => $target_id, 'translated' => count( $done ) );
}

/**
 * Gives the text blocks of a manual translation the ids of the Thai blocks they belong to. Possible
 * only when both have the same blocks with text in the same order.
 *
 * @return ?bool true when aligned (content saved), null when the structures differ.
 */
function hri_tr_adopt_ids( WP_Post $source, WP_Post $target ): ?bool {
	global $wpdb;
	$sig = static function ( array $blocks ) use ( &$sig ): array {
		$out = array();
		foreach ( $blocks as $block ) {
			$idx = hri_tr_block_string_indexes( $block );
			if ( $idx ) {
				$out[] = array( $block['blockName'], $idx, $block['attrs']['metadata']['hriId'] ?? '' );
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$out = array_merge( $out, $sig( $block['innerBlocks'] ) );
			}
		}
		return $out;
	};
	$src = $sig( parse_blocks( $source->post_content ) );
	$tgt = parse_blocks( $target->post_content );
	$cur = $sig( $tgt );
	if ( count( $src ) !== count( $cur ) ) {
		return null;
	}
	foreach ( $src as $n => $row ) {
		if ( $row[0] !== $cur[ $n ][0] || $row[1] !== $cur[ $n ][1] ) {
			return null;
		}
	}
	$ids  = array_column( $src, 2 );
	$walk = static function ( array $blocks ) use ( &$walk, &$ids ): array {
		foreach ( $blocks as $n => $block ) {
			if ( hri_tr_block_string_indexes( $block ) ) {
				$id = array_shift( $ids );
				$blocks[ $n ]['attrs']['metadata']           = is_array( $block['attrs']['metadata'] ?? null ) ? $block['attrs']['metadata'] : array();
				$blocks[ $n ]['attrs']['metadata']['hriId'] = $id;
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$blocks[ $n ]['innerBlocks'] = $walk( $block['innerBlocks'] );
			}
		}
		return $blocks;
	};
	$content = serialize_blocks( $walk( $tgt ) );
	$wpdb->update( $wpdb->posts, array( 'post_content' => $content ), array( 'ID' => $target->ID ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	clean_post_cache( $target->ID );
	return true;
}

/**
 * Creates the translated post (slug set once: it never changes afterwards, so URLs stay).
 *
 * @param WP_Post $source Thai post.
 * @param string  $lang   en | ja.
 * @param string  $title  Translated title.
 * @return int New post id.
 */
function hri_tr_create_target( WP_Post $source, string $lang, string $title ): int {
	$policy = hri_tr_settings()['policy'][ $lang ] ?? 'publish';
	$parent = $source->post_parent ? (int) pll_get_post( $source->post_parent, $lang ) : 0;
	$args   = array(
		'post_type'      => $source->post_type,
		'post_status'    => 'draft' === $policy ? 'draft' : 'publish',
		'post_title'     => $title,
		'post_name'      => hri_tr_slug( $source, $lang, $title ),
		'post_author'    => $source->post_author,
		'post_parent'    => $parent,
		'menu_order'     => $source->menu_order,
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	);
	if ( 'post' === $source->post_type ) {
		$args['post_date']     = $source->post_date;
		$args['post_date_gmt'] = $source->post_date_gmt;
	}
	$GLOBALS['hri_tr_busy'] = true;
	$id                     = wp_insert_post( wp_slash( $args ), true );
	$GLOBALS['hri_tr_busy'] = false;
	if ( is_wp_error( $id ) ) {
		throw new HRI_Translate_Error( 'bad_output', $id->get_error_message() );
	}
	pll_set_post_language( $id, $lang );
	pll_save_post_translations( array_merge( pll_get_post_translations( $source->ID ), array( $lang => $id ) ) );

	// What is not text is copied as it is.
	foreach ( array( '_thumbnail_id', '_hri_thumbnail_url', '_wp_page_template' ) as $key ) {
		$value = get_post_meta( $source->ID, $key, true );
		if ( '' !== $value && false !== $value ) {
			update_post_meta( $id, $key, $value );
		}
	}
	if ( 'course' === $source->post_type && function_exists( 'hri_synced_course_meta' ) ) {
		foreach ( hri_synced_course_meta() as $key ) {
			$value = get_post_meta( $source->ID, $key, true );
			if ( '' !== $value && false !== $value ) {
				update_post_meta( $id, $key, $value );
			}
		}
		foreach ( hri_course_taxonomy_names() as $taxonomy ) {
			$terms = wp_get_object_terms( $source->ID, $taxonomy, array( 'fields' => 'ids' ) );
			if ( is_array( $terms ) && $terms ) {
				wp_set_object_terms( $id, array_map( 'intval', $terms ), $taxonomy );
			}
		}
	}
	if ( 'post' === $source->post_type ) {
		$cats = array();
		foreach ( wp_get_post_categories( $source->ID ) as $cat ) {
			$twin = (int) pll_get_term( $cat, $lang );
			if ( $twin ) {
				$cats[] = $twin;
			}
		}
		if ( $cats ) {
			wp_set_post_categories( $id, $cats );
		}
	}
	return (int) $id;
}

/**
 * URL slug of a new translation: from the English title (Polylang Free does not allow the same slug
 * in two languages, so a taken one gets "-en"); Japanese = the English base + "-ja".
 */
function hri_tr_slug( WP_Post $source, string $lang, string $title ): string {
	$latin = static fn( string $s ): bool => (bool) preg_match( '/^[a-z0-9][a-z0-9-]*$/', $s );
	$base  = '';
	if ( 'en' === $lang ) {
		$base = sanitize_title( $title );
	}
	if ( ! $latin( $base ) ) {
		$base = $latin( (string) $source->post_name ) ? (string) $source->post_name : '';
	}
	if ( ! $latin( $base ) ) {
		$base = $source->post_type . '-' . $source->ID;
	}
	$base = (string) preg_replace( '/-(?:en|ja)$/', '', $base );
	$slug = 'ja' === $lang ? "{$base}-ja" : $base;
	$taken = static fn( string $s ): bool => (bool) get_posts( array( 'post_type' => $source->post_type, 'name' => $s, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'lang' => '' ) );
	if ( $taken( $slug ) ) {
		$slug = "{$base}-{$lang}";
	}
	for ( $i = 2; $taken( $slug ); $i++ ) {
		$slug = "{$base}-{$lang}-{$i}";
	}
	return $slug;
}

/* ----------------------------------------------------------------------
 * Reviewing: what a person edited, accepting proposals, "reviewed"
 * ---------------------------------------------------------------------- */

/**
 * After a person saved a translation: marks the segments whose text is no longer the machine text as
 * `human`, and moves the post out of "reviewed" when nothing was reviewed yet is irrelevant — a save
 * never changes `reviewed`.
 */
function hri_tr_refresh_states( int $target_id ): void {
	$state = hri_tr_state( $target_id );
	$post  = get_post( $target_id );
	if ( ! $state || ! $post ) {
		return;
	}
	$current = hri_tr_post_segments( $post );
	foreach ( $state['segs'] as $key => $rec ) {
		if ( ! isset( $current[ $key ] ) ) {
			continue;
		}
		if ( 'outdated' === $rec['st'] ) {
			continue;
		}
		$state['segs'][ $key ]['st'] = hri_tr_hash( $current[ $key ] ) === $rec['mt'] ? 'machine' : 'human';
	}
	update_post_meta( $target_id, HRI_TR_META, $state );
}

/**
 * Takes the machine proposal for an outdated segment (replaces the person's text).
 */
function hri_tr_accept_proposal( int $target_id, string $key ): bool {
	$state = hri_tr_state( $target_id );
	$post  = get_post( $target_id );
	$rec   = $state['segs'][ $key ] ?? null;
	if ( ! $state || ! $post || ! $rec || 'outdated' !== $rec['st'] || empty( $rec['prop'] ) ) {
		return false;
	}
	$source = get_post( (int) $state['source'] );
	if ( ! $source ) {
		return false;
	}
	$values        = hri_tr_post_segments( $post );
	$values[ $key ] = $rec['prop'];
	hri_tr_write_target( $target_id, $source, $values );
	$state['segs'][ $key ] = array( 'src' => $rec['prop_src'], 'mt' => hri_tr_hash( $rec['prop'] ), 'st' => 'machine' );
	hri_tr_finish_review_step( $target_id, $state );
	return true;
}

/**
 * Keeps the person's text for an outdated segment (the Thai change does not need a new translation).
 */
function hri_tr_keep_human( int $target_id, string $key ): bool {
	$state = hri_tr_state( $target_id );
	$rec   = $state['segs'][ $key ] ?? null;
	if ( ! $state || ! $rec || 'outdated' !== $rec['st'] ) {
		return false;
	}
	$state['segs'][ $key ] = array( 'src' => $rec['prop_src'] ?? $rec['src'], 'mt' => $rec['mt'], 'st' => 'human' );
	hri_tr_finish_review_step( $target_id, $state );
	return true;
}

/**
 * Drops a kept orphan (a person's text from a block that no longer exists in Thai).
 */
function hri_tr_dismiss_orphan( int $target_id, string $key ): bool {
	$state = hri_tr_state( $target_id );
	if ( ! $state || ! isset( $state['orphans'][ $key ] ) ) {
		return false;
	}
	unset( $state['orphans'][ $key ] );
	hri_tr_finish_review_step( $target_id, $state );
	return true;
}

/**
 * Saves the state after a decision; nothing left to decide moves "outdated" back to "machine".
 *
 * @param array<string,mixed> $state State.
 */
function hri_tr_finish_review_step( int $target_id, array $state ): void {
	$open = ! empty( $state['orphans'] );
	foreach ( $state['segs'] as $rec ) {
		$open = $open || 'outdated' === $rec['st'];
	}
	if ( ! $open && 'outdated' === $state['status'] ) {
		$state['status'] = 'machine';
	}
	update_post_meta( $target_id, HRI_TR_META, $state );
	hri_tr_sync_noindex_flag( $target_id );
}

/**
 * "Reviewed" (not possible while something is outdated).
 */
function hri_tr_mark_reviewed( int $target_id ): bool {
	$state = hri_tr_state( $target_id );
	if ( ! $state || 'outdated' === $state['status'] ) {
		return false;
	}
	$state['status']      = 'reviewed';
	$state['reviewed_at'] = time();
	update_post_meta( $target_id, HRI_TR_META, $state );
	hri_tr_sync_noindex_flag( $target_id );
	return true;
}

/**
 * Policy "publish + noindex until reviewed": the flag `_hri_tr_noindex` marks a translation that
 * must carry noindex (read by seo.php for robots meta, sitemap and hreflang).
 */
function hri_tr_sync_noindex_flag( int $target_id ): void {
	$state = hri_tr_state( $target_id );
	$lang  = hri_tr_post_lang( $target_id );
	$noidx = $state && 'reviewed' !== ( $state['status'] ?? '' ) && 'publish_noindex' === ( hri_tr_settings()['policy'][ $lang ] ?? '' );
	if ( $noidx ) {
		update_post_meta( $target_id, '_hri_tr_noindex', '1' );
	} else {
		delete_post_meta( $target_id, '_hri_tr_noindex' );
	}
}

// Translations that are not reviewed yet (policy "publish + noindex") carry noindex, and stay out of
// the sitemap and of hreflang (seo.php).
add_filter(
	'hri_seo_noindex_ids',
	static function ( $ids ) {
		$flagged = get_posts(
			array(
				'post_type'        => hri_tr_post_types(),
				'post_status'      => 'publish',
				'numberposts'      => -1,
				'fields'           => 'ids',
				'meta_key'         => '_hri_tr_noindex', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'       => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
				'lang'             => '',
				'suppress_filters' => false,
			)
		);
		return array_merge( (array) $ids, array_map( 'intval', $flagged ) );
	}
);

/* ----------------------------------------------------------------------
 * WP-CLI
 * ---------------------------------------------------------------------- */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Translate a Thai post now (no queue).
	 *
	 * ## OPTIONS
	 *
	 * <post_id>
	 * : Thai post.
	 *
	 * [--lang=<langs>]
	 * : en, ja or en,ja (default: the languages set for the post).
	 *
	 * [--adopt]
	 * : Take over an existing manual translation as human text.
	 *
	 * ## EXAMPLES
	 *     wp hri translate run 12 --lang=en
	 *
	 * @param string[]             $args  Positional.
	 * @param array<string,string> $assoc Options.
	 */
	WP_CLI::add_command(
		'hri translate run',
		static function ( $args, $assoc ) {
			$post = get_post( (int) $args[0] );
			if ( ! $post ) {
				WP_CLI::error( 'No such post.' );
			}
			$langs = isset( $assoc['lang'] ) ? array_map( 'trim', explode( ',', $assoc['lang'] ) ) : hri_tr_target_langs( $post );
			foreach ( $langs as $lang ) {
				try {
					do {
						$res = hri_tr_run( $post->ID, $lang, array( 'adopt' => isset( $assoc['adopt'] ), 'budget' => 600 ) );
					} while ( ! empty( $res['pending'] ) && 'locked' !== $res['status'] );
					WP_CLI::log( sprintf( '%s → %s: %s (target %s, %d translated)', $post->ID, $lang, $res['status'], $res['target'] ?? '-', $res['translated'] ?? 0 ) );
				} catch ( HRI_Translate_Error $e ) {
					WP_CLI::warning( sprintf( '%s → %s: %s (%s)', $post->ID, $lang, $e->getMessage(), $e->kind ) );
				}
			}
		}
	);
}
