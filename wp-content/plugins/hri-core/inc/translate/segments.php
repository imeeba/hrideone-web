<?php
/**
 * Segments: what gets translated, and how a translated post is put back together.
 *
 * A segment is a key => string pair of one source (Thai) post:
 * - `title`, `excerpt`           plain text
 * - `m:<meta key>`               plain text (course fields that are translated, not synced)
 * - `b:<hriId>:<n>`              HTML of the n-th string of a block's innerContent that holds text
 *
 * Blocks that hold text carry `metadata.hriId` (a short id added when the Thai post is saved; the
 * block editor keeps it, copies of a block get a fresh id). The id is what ties a Thai block to its
 * English / Japanese twin, so blocks can be inserted, moved or deleted in Thai without confusing the
 * translations. A translated post is always rebuilt from the Thai block tree with the translated
 * strings put in, so its structure follows the Thai post.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Blocks whose content is never translated.
 *
 * @return string[]
 */
function hri_tr_skip_blocks(): array {
	return (array) apply_filters( 'hri_translate_skip_blocks', array( 'core/code', 'core/html', 'core/shortcode', 'core/embed', 'core/freeform' ) );
}

/**
 * Course fields translated by hand or by machine (everything Polylang does not keep in sync).
 *
 * @return string[]
 */
function hri_tr_course_meta_keys(): array {
	if ( ! function_exists( 'hri_course_meta_schema' ) || ! function_exists( 'hri_synced_course_meta' ) ) {
		return array();
	}
	return array_values( array_diff( array_keys( hri_course_meta_schema() ), hri_synced_course_meta() ) );
}

/**
 * Whether a string has anything to translate: letters in the text, or in alt / title / aria-label.
 */
function hri_tr_has_text( string $html ): bool {
	if ( preg_match( '/\p{L}/u', wp_strip_all_tags( $html ) ) ) {
		return true;
	}
	return (bool) preg_match( '/\b(?:alt|title|aria-label)="[^"]*\p{L}[^"]*"/u', $html );
}

/**
 * Text of a string for comparing: tags kept, spacing and entity differences between how this plugin
 * and the block editor write the same HTML ignored.
 */
function hri_tr_norm( string $html ): string {
	$html = html_entity_decode( $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$html = preg_replace( '#<br\s*/?>#i', '<br>', $html ) ?? $html;
	$html = preg_replace( '/\s+/u', ' ', $html ) ?? $html;
	return trim( $html );
}

/**
 * Hash used for src / mt bookkeeping.
 */
function hri_tr_hash( string $html ): string {
	return substr( sha1( hri_tr_norm( $html ) ), 0, 16 );
}

/**
 * Indexes (in innerContent) of the strings of a block that need translating.
 *
 * @param array<string,mixed> $block Parsed block.
 * @return int[]
 */
function hri_tr_block_string_indexes( array $block ): array {
	if ( empty( $block['blockName'] ) || in_array( $block['blockName'], hri_tr_skip_blocks(), true ) ) {
		return array();
	}
	$out = array();
	foreach ( (array) ( $block['innerContent'] ?? array() ) as $i => $part ) {
		if ( is_string( $part ) && hri_tr_has_text( $part ) ) {
			$out[] = (int) $i;
		}
	}
	return $out;
}

/**
 * Gives every block that holds text a unique metadata.hriId (keeps the ones that are fine).
 *
 * @param array<int,array<string,mixed>> $blocks Parsed blocks.
 * @param array<string,bool>             $seen   Ids already used in this document.
 * @param bool                           $changed Set when an id was added.
 * @return array<int,array<string,mixed>>
 */
function hri_tr_ensure_ids( array $blocks, array &$seen, bool &$changed ): array {
	foreach ( $blocks as $n => $block ) {
		if ( ! empty( $block['innerBlocks'] ) ) {
			$blocks[ $n ]['innerBlocks'] = hri_tr_ensure_ids( $block['innerBlocks'], $seen, $changed );
		}
		if ( ! hri_tr_block_string_indexes( $block ) ) {
			continue;
		}
		$id = $block['attrs']['metadata']['hriId'] ?? '';
		// A copied block carries the id of its original: the first one in the document keeps it.
		if ( ! is_string( $id ) || ! preg_match( '/^[a-z0-9]{6,12}$/', $id ) || isset( $seen[ $id ] ) ) {
			do {
				$id = substr( bin2hex( random_bytes( 6 ) ), 0, 8 );
			} while ( isset( $seen[ $id ] ) );
			$blocks[ $n ]['attrs']['metadata']           = is_array( $block['attrs']['metadata'] ?? null ) ? $block['attrs']['metadata'] : array();
			$blocks[ $n ]['attrs']['metadata']['hriId'] = $id;
			$changed                                     = true;
		}
		$seen[ $id ] = true;
	}
	return $blocks;
}

/**
 * Content with hriIds on every text block (the same string when nothing had to be added).
 */
function hri_tr_content_with_ids( string $content ): string {
	if ( '' === trim( $content ) || ! has_blocks( $content ) ) {
		return $content;
	}
	$seen    = array();
	$changed = false;
	$blocks  = hri_tr_ensure_ids( parse_blocks( $content ), $seen, $changed );
	return $changed ? serialize_blocks( $blocks ) : $content;
}

/**
 * Collects the translatable strings of a block tree, in document order.
 *
 * @param array<int,array<string,mixed>> $blocks Parsed blocks.
 * @param array<string,string>           $out    key => string.
 */
function hri_tr_collect_blocks( array $blocks, array &$out ): void {
	foreach ( $blocks as $block ) {
		$id = $block['attrs']['metadata']['hriId'] ?? '';
		if ( is_string( $id ) && '' !== $id ) {
			foreach ( hri_tr_block_string_indexes( $block ) as $i ) {
				$out[ "b:{$id}:{$i}" ] = $block['innerContent'][ $i ];
			}
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			hri_tr_collect_blocks( $block['innerBlocks'], $out );
		}
	}
}

/**
 * Puts strings (key => string) into a block tree.
 *
 * @param array<int,array<string,mixed>> $blocks Parsed blocks.
 * @param array<string,string>           $map    key => string; keys not in it keep the block's own string.
 * @return array<int,array<string,mixed>>
 */
function hri_tr_apply_blocks( array $blocks, array $map ): array {
	foreach ( $blocks as $n => $block ) {
		$id = $block['attrs']['metadata']['hriId'] ?? '';
		if ( is_string( $id ) && '' !== $id ) {
			foreach ( hri_tr_block_string_indexes( $block ) as $i ) {
				if ( isset( $map[ "b:{$id}:{$i}" ] ) ) {
					$blocks[ $n ]['innerContent'][ $i ] = $map[ "b:{$id}:{$i}" ];
				}
			}
			$blocks[ $n ]['innerHTML'] = implode( '', array_filter( $blocks[ $n ]['innerContent'], 'is_string' ) );
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$blocks[ $n ]['innerBlocks'] = hri_tr_apply_blocks( $block['innerBlocks'], $map );
		}
	}
	return $blocks;
}

/**
 * All segments of a post, in the order they are translated and shown.
 *
 * @return array<string,string> key => string
 */
function hri_tr_post_segments( WP_Post $post ): array {
	$out = array();
	if ( hri_tr_has_text( (string) $post->post_title ) ) {
		$out['title'] = (string) $post->post_title;
	}
	if ( '' !== trim( (string) $post->post_excerpt ) && hri_tr_has_text( (string) $post->post_excerpt ) ) {
		$out['excerpt'] = (string) $post->post_excerpt;
	}
	if ( 'course' === $post->post_type ) {
		foreach ( hri_tr_course_meta_keys() as $key ) {
			$value = get_post_meta( $post->ID, $key, true );
			if ( is_string( $value ) && '' !== trim( $value ) && preg_match( '/\p{L}/u', $value ) && ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
				$out[ "m:{$key}" ] = $value;
			}
		}
	}
	if ( '' !== trim( (string) $post->post_content ) && has_blocks( $post->post_content ) ) {
		hri_tr_collect_blocks( parse_blocks( $post->post_content ), $out );
	}
	return $out;
}

/**
 * Whether a segment is plain text (title, excerpt, meta) rather than HTML.
 */
function hri_tr_is_plain( string $key ): bool {
	return ! str_starts_with( $key, 'b:' );
}

/**
 * Writes a translated post: the Thai block tree with translated strings in it, plus title, excerpt
 * and translated course fields. Nothing else of the post is touched.
 *
 * @param int                  $target_id Translation (already created).
 * @param WP_Post              $source    Thai post (its content already has hriIds).
 * @param array<string,string> $values    key => string for every segment to write.
 */
function hri_tr_write_target( int $target_id, WP_Post $source, array $values ): void {
	$update = array( 'ID' => $target_id );
	if ( isset( $values['title'] ) ) {
		$update['post_title'] = $values['title'];
	}
	if ( isset( $values['excerpt'] ) ) {
		$update['post_excerpt'] = $values['excerpt'];
	}
	if ( '' !== trim( (string) $source->post_content ) ) {
		$update['post_content'] = has_blocks( $source->post_content )
			? serialize_blocks( hri_tr_apply_blocks( parse_blocks( $source->post_content ), $values ) )
			: (string) $source->post_content;
	}
	$GLOBALS['hri_tr_busy'] = true;
	wp_update_post( wp_slash( $update ) );
	$GLOBALS['hri_tr_busy'] = false;
	foreach ( $values as $key => $value ) {
		if ( str_starts_with( $key, 'm:' ) ) {
			update_post_meta( $target_id, substr( $key, 2 ), $value );
		}
	}
}
