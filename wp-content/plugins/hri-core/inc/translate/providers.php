<?php
/**
 * Translation providers. The site owner connects an API account of their own in wp-config.php:
 *
 *   define( 'HRI_TRANSLATE_PROVIDER', 'anthropic' );   // anthropic | fake
 *   define( 'HRI_TRANSLATE_API_KEY', '…' );            // anthropic only; never stored in the database
 *   define( 'HRI_TRANSLATE_MODEL', 'claude-sonnet-5-5' );   // optional
 *
 * Without HRI_TRANSLATE_PROVIDER the whole module stays off. `fake` makes no network call (it puts
 * "[EN] " / "[JA] " before every text, keeping tags): for tests on local and Playground.
 * Billing and quota are the owner's own: when the API says the quota or credit is used up, the
 * queue pauses and the page shows a clear message (error code `quota`).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * A provider failure. `code`: quota | auth | transient | bad_output | config.
 */
class HRI_Translate_Error extends Exception {
	/** @var string */
	public $kind;

	public function __construct( string $kind, string $message ) {
		parent::__construct( $message );
		$this->kind = $kind;
	}
}

/**
 * Configured provider name, or '' when the module is off.
 */
function hri_tr_provider_name(): string {
	$name = defined( 'HRI_TRANSLATE_PROVIDER' ) ? strtolower( (string) HRI_TRANSLATE_PROVIDER ) : '';
	return in_array( $name, array( 'anthropic', 'fake' ), true ) ? $name : '';
}

/**
 * Model used by the provider.
 */
function hri_tr_model(): string {
	if ( defined( 'HRI_TRANSLATE_MODEL' ) && '' !== (string) HRI_TRANSLATE_MODEL ) {
		return (string) HRI_TRANSLATE_MODEL;
	}
	return 'fake' === hri_tr_provider_name() ? 'fake' : 'claude-sonnet-5-5';
}

/**
 * Whether the module can translate: provider set (and key present for a real one).
 */
function hri_tr_configured(): bool {
	$name = hri_tr_provider_name();
	if ( 'fake' === $name ) {
		return true;
	}
	return 'anthropic' === $name && defined( 'HRI_TRANSLATE_API_KEY' ) && '' !== (string) HRI_TRANSLATE_API_KEY;
}

/**
 * Names of the languages in prompts.
 *
 * @return array<string,string>
 */
function hri_tr_language_names(): array {
	return array( 'en' => 'English', 'ja' => 'Japanese' );
}

/**
 * Translates strings from Thai.
 *
 * @param array<string,string> $items key => string (HTML fragment, or plain text for keys that are not `b:…`).
 * @param string               $lang  en | ja.
 * @param array<string,mixed>  $ctx   `glossary` (th => target / '' = keep), `tone`.
 * @return array{translations:array<string,string>,usage:array{in:int,out:int,calls:int}}
 * @throws HRI_Translate_Error On failure.
 */
function hri_tr_translate_items( array $items, string $lang, array $ctx = array() ): array {
	$provider = hri_tr_provider_name();
	if ( ! hri_tr_configured() ) {
		throw new HRI_Translate_Error( 'config', __( 'ยังไม่ได้ตั้งค่าการแปลอัตโนมัติ (wp-config.php)', 'hri-core' ) );
	}
	$result = 'fake' === $provider ? hri_tr_fake( $items, $lang ) : hri_tr_anthropic( $items, $lang, $ctx );
	// Whatever the provider says, the tags and attributes of every HTML string must come back unchanged.
	foreach ( $items as $key => $source ) {
		if ( ! isset( $result['translations'][ $key ] ) || ! is_string( $result['translations'][ $key ] ) || '' === trim( $result['translations'][ $key ] ) ) {
			throw new HRI_Translate_Error( 'bad_output', sprintf( 'Missing translation for %s', $key ) );
		}
		if ( ! hri_tr_is_plain( $key ) && ! hri_tr_same_markup( $source, $result['translations'][ $key ] ) ) {
			throw new HRI_Translate_Error( 'bad_output', sprintf( 'Markup changed in %s', $key ) );
		}
	}
	return $result;
}

/**
 * Tags and attributes of an HTML string as a list, with the attribute values that are translated
 * (alt, title, aria-label) left out. Two strings with the same list have the same markup.
 */
function hri_tr_markup_signature( string $html ): array {
	$sig = array();
	if ( preg_match_all( '/<(\/?)([a-zA-Z][a-zA-Z0-9-]*)([^>]*)>/', $html, $m, PREG_SET_ORDER ) ) {
		foreach ( $m as $tag ) {
			$attrs = preg_replace( '/\s+(?:alt|title|aria-label)="[^"]*"/u', '', $tag[3] ) ?? $tag[3];
			$attrs = trim( preg_replace( '/\s+/', ' ', rtrim( $attrs, " /" ) ) ?? '' );
			$sig[] = strtolower( $tag[1] . $tag[2] ) . ( '' !== $attrs ? ' ' . $attrs : '' );
		}
	}
	return $sig;
}

/**
 * Whether a translation kept the markup of its source.
 */
function hri_tr_same_markup( string $source, string $translated ): bool {
	return hri_tr_markup_signature( $source ) === hri_tr_markup_signature( $translated );
}

/**
 * Test provider: "[EN] " before every text node and alt attribute.
 *
 * @param array<string,string> $items Strings.
 * @param string               $lang  en | ja.
 * @return array{translations:array<string,string>,usage:array{in:int,out:int,calls:int}}
 */
function hri_tr_fake( array $items, string $lang ): array {
	$tag = '[' . strtoupper( $lang ) . '] ';
	$out = array();
	$in  = 0;
	foreach ( $items as $key => $source ) {
		$in += mb_strlen( $source );
		if ( hri_tr_is_plain( $key ) ) {
			$out[ $key ] = $tag . $source;
			continue;
		}
		$text        = preg_replace_callback( '/>([^<]*\p{L}[^<]*)</u', static fn( $m ) => '>' . $tag . $m[1] . '<', $source ) ?? $source;
		$text        = preg_replace_callback( '/(\s(?:alt|title|aria-label)=")([^"]*\p{L}[^"]*)(")/u', static fn( $m ) => $m[1] . $tag . $m[2] . $m[3], $text ) ?? $text;
		$out[ $key ] = $text;
	}
	return array( 'translations' => $out, 'usage' => array( 'in' => $in, 'out' => $in, 'calls' => 1 ) );
}

/**
 * System prompt for Claude.
 *
 * @param string               $lang en | ja.
 * @param array<string,string> $ctx  Context (tone, glossary).
 */
function hri_tr_system_prompt( string $lang, array $ctx ): string {
	$names = hri_tr_language_names();
	$p     = "You are the professional translator of HRI DeOne, an executive training and organizational development institute in Bangkok, Thailand. Translate Thai website content into {$names[$lang]} for senior managers and HR leaders (B2B).\n\n";
	$p    .= "INPUT: a JSON object { key: string }. Each string is either plain text or an HTML fragment from a WordPress block.\n";
	$p    .= "OUTPUT: only a JSON object with exactly the same keys and the translated strings. No commentary, no markdown fences.\n\n";
	$p    .= "RULES:\n";
	$p    .= "- HTML fragments: keep every tag and attribute exactly as it is, in the same order. Translate only the text between tags and the values of alt, title and aria-label attributes. Never translate or change URLs, class names, ids or other attributes.\n";
	$p    .= "- Plain text strings stay plain text (no tags added).\n";
	$p    .= "- Keep numbers, prices, dates, times, email addresses, phone numbers and URLs as written. Text that is already English (brand and product names, abbreviations such as KPI, OKR, AI) stays as it is.\n";
	$p    .= "- Do not add, remove or merge sentences. Do not explain.\n";
	$p    .= '- ' . ( $ctx['tone'] ?? '' ) . "\n";
	if ( ! empty( $ctx['glossary'] ) ) {
		$p .= "\nGLOSSARY (Thai => required {$names[$lang]}; 'KEEP' = leave the Thai or Latin term exactly as written):\n";
		foreach ( $ctx['glossary'] as $thai => $target ) {
			$p .= '- ' . $thai . ' => ' . ( '' === $target ? 'KEEP' : $target ) . "\n";
		}
	}
	return $p;
}

/**
 * Claude (Anthropic Messages API), one request for a batch of strings.
 *
 * @param array<string,string> $items key => string.
 * @param string               $lang  en | ja.
 * @param array<string,mixed>  $ctx   Context.
 * @return array{translations:array<string,string>,usage:array{in:int,out:int,calls:int}}
 * @throws HRI_Translate_Error On failure.
 */
function hri_tr_anthropic( array $items, string $lang, array $ctx ): array {
	$body = array(
		'model'      => hri_tr_model(),
		'max_tokens' => 8000,
		'system'     => hri_tr_system_prompt( $lang, $ctx ),
		'messages'   => array( array( 'role' => 'user', 'content' => wp_json_encode( $items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ) ),
	);
	$response = wp_remote_post(
		(string) apply_filters( 'hri_translate_anthropic_url', 'https://api.anthropic.com/v1/messages' ),
		array(
			'timeout' => 90,
			'headers' => array(
				'x-api-key'         => (string) HRI_TRANSLATE_API_KEY,
				'anthropic-version' => '2023-06-01',
				'content-type'      => 'application/json',
			),
			'body'    => wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
		)
	);
	if ( is_wp_error( $response ) ) {
		throw new HRI_Translate_Error( 'transient', $response->get_error_message() );
	}
	$status = (int) wp_remote_retrieve_response_code( $response );
	$data   = json_decode( (string) wp_remote_retrieve_body( $response ), true );
	if ( $status >= 400 || ! is_array( $data ) ) {
		$message = is_array( $data ) ? (string) ( $data['error']['message'] ?? '' ) : '';
		if ( 401 === $status || 403 === $status ) {
			throw new HRI_Translate_Error( 'auth', __( 'API key ไม่ถูกต้องหรือไม่มีสิทธิ์', 'hri-core' ) );
		}
		// 402 / 429 (rate or spend limit) and "credit balance too low" are the owner's own quota.
		if ( 402 === $status || 429 === $status || false !== stripos( $message, 'credit' ) || false !== stripos( $message, 'quota' ) || false !== stripos( $message, 'usage limit' ) ) {
			throw new HRI_Translate_Error( 'quota', __( 'โควต้าหรือเครดิต API ของบัญชีที่เชื่อมไว้หมด (หรือถูกจำกัดอัตรา) — เติม/รอแล้วกด "ลองใหม่"', 'hri-core' ) );
		}
		throw new HRI_Translate_Error( $status >= 500 || 0 === $status ? 'transient' : 'bad_output', sprintf( 'HTTP %d %s', $status, $message ) );
	}
	$text = '';
	foreach ( (array) ( $data['content'] ?? array() ) as $part ) {
		if ( is_array( $part ) && 'text' === ( $part['type'] ?? '' ) ) {
			$text .= (string) $part['text'];
		}
	}
	if ( 'max_tokens' === ( $data['stop_reason'] ?? '' ) ) {
		throw new HRI_Translate_Error( 'bad_output', 'Output was cut off (max_tokens)' );
	}
	$start = strpos( $text, '{' );
	$end   = strrpos( $text, '}' );
	$json  = false !== $start && false !== $end ? json_decode( substr( $text, $start, $end - $start + 1 ), true ) : null;
	if ( ! is_array( $json ) ) {
		throw new HRI_Translate_Error( 'bad_output', 'The answer is not valid JSON' );
	}
	return array(
		'translations' => array_map( 'strval', $json ),
		'usage'        => array(
			'in'    => (int) ( $data['usage']['input_tokens'] ?? 0 ),
			'out'   => (int) ( $data['usage']['output_tokens'] ?? 0 ),
			'calls' => 1,
		),
	);
}
