<?php
/**
 * Search and AI discovery files, all virtual (nothing written to disk, editable from wp-admin):
 *
 * - robots.txt: rules for AI search crawlers, an optional "do not train on this site" switch,
 *   internal search results kept out of crawls. Sitemap lines come from TSF (or core).
 * - /llms.txt: company summary + links to the important pages and every course, in all three
 *   languages, built from the site's own content (cached, refreshed when content is saved).
 * - IndexNow: tells Bing & co. when a page is published or changed (off by default; only on a
 *   production site, never on Playground/local).
 * - Settings → SEO & AI: the switches above.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_AI_SEO_OPTION = 'hri_ai_seo';

/**
 * Crawlers that fetch pages to answer a user's question or to search. Allowed unless the
 * site owner blocks them with the filter `hri_ai_search_bots`.
 *
 * @return string[]
 */
function hri_ai_search_bots(): array {
	return (array) apply_filters( 'hri_ai_search_bots', array( 'OAI-SearchBot', 'ChatGPT-User', 'Claude-SearchBot', 'Claude-User', 'PerplexityBot', 'Perplexity-User', 'Bingbot', 'Googlebot', 'Applebot' ) );
}

/**
 * Crawlers that collect pages to train models. Allowed or blocked together by one switch
 * (Settings → SEO & AI, default: allowed — the client has to decide).
 *
 * @return string[]
 */
function hri_ai_training_bots(): array {
	return (array) apply_filters( 'hri_ai_training_bots', array( 'GPTBot', 'ClaudeBot', 'Google-Extended', 'Applebot-Extended', 'CCBot', 'Bytespider' ) );
}

/**
 * The saved switches.
 *
 * @return array{training:int,indexnow:int,indexnow_key:string}
 */
function hri_ai_seo_options(): array {
	$saved = get_option( HRI_AI_SEO_OPTION );
	return array_merge(
		array(
			'training'     => 1,
			'indexnow'     => 0,
			'indexnow_key' => '',
		),
		is_array( $saved ) ? $saved : array()
	);
}

/* ----------------------------------------------------------------------
 * robots.txt
 * ---------------------------------------------------------------------- */

add_filter(
	'robots_txt',
	static function ( $output, $public ) {
		if ( ! $public ) {
			return $output; // "Discourage search engines" is on: WordPress' own Disallow: / stays.
		}
		$opts = hri_ai_seo_options();
		// Internal search result pages (any language) are not content: added to the "*" group, which ends
		// where the first Sitemap line begins.
		$rules = "Disallow: /*?s=\nDisallow: /*&s=\nDisallow: /search/\nDisallow: /*/search/\n";
		$out   = rtrim( (string) $output ) . "\n";
		$pos   = stripos( $out, "\nSitemap:" );
		if ( false !== $pos ) {
			$out = rtrim( substr( $out, 0, $pos ) ) . "\n" . $rules . substr( $out, $pos );
		} else {
			$out .= $rules;
		}
		// A crawler with its own group ignores the "*" group, so the admin rules are repeated.
		$admin = "Disallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\n";
		foreach ( hri_ai_search_bots() as $bot ) {
			$out .= "\nUser-agent: {$bot}\nAllow: /\n{$admin}";
		}
		foreach ( hri_ai_training_bots() as $bot ) {
			$out .= "\nUser-agent: {$bot}\n" . ( $opts['training'] ? "Allow: /\n{$admin}" : "Disallow: /\n" );
		}
		return $out;
	},
	20,
	2
);

/* ----------------------------------------------------------------------
 * Virtual files: /llms.txt and the IndexNow key file
 * ---------------------------------------------------------------------- */

add_action(
	'parse_request',
	static function ( $wp ) {
		$request = strtolower( trim( (string) $wp->request, '/' ) );
		if ( 'llms.txt' === $request ) {
			hri_ai_seo_send( hri_llms_txt() );
		}
		$key = hri_ai_seo_options()['indexnow_key'];
		if ( '' !== $key && $request === strtolower( $key ) . '.txt' ) {
			hri_ai_seo_send( $key );
		}
	}
);

/**
 * Sends a plain text file and stops.
 *
 * @param string $body File content.
 */
function hri_ai_seo_send( string $body ): void {
	status_header( 200 );
	nocache_headers();
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	header( 'Cache-Control: public, max-age=3600' );
	echo $body; // phpcs:ignore WordPress.Security.EscapeOutput -- plain text.
	exit;
}

/**
 * Source-language slugs of the pages llms.txt lists, in this order (translations are found through Polylang).
 *
 * @return string[]
 */
function hri_llms_page_slugs(): array {
	return (array) apply_filters( 'hri_llms_page_slugs', array( 'in-house', 'public-training', 'transformation-solutions', 'japanese-concept', 'about', 'contact-us' ) );
}

/**
 * Title and URL of a source-language page in a language, or null when it has no translation.
 *
 * @param string $slug Source-language slug.
 * @param string $lang Language code.
 * @return ?array{0:string,1:string}
 */
function hri_llms_page( string $slug, string $lang ): ?array {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return null;
	}
	$id = $page->ID;
	if ( function_exists( 'pll_get_post' ) ) {
		$id = (int) pll_get_post( $page->ID, $lang );
	}
	if ( ! $id || 'publish' !== get_post_status( $id ) || hri_seo_is_noindex( $id ) ) {
		return null;
	}
	return array( wp_strip_all_tags( get_the_title( $id ) ), get_permalink( $id ) );
}

/**
 * One line of the file: "- [Title](url): description".
 */
function hri_llms_line( string $title, string $url, string $note = '' ): string {
	$title = trim( str_replace( array( '[', ']', "\n" ), array( '(', ')', ' ' ), $title ) );
	$note  = trim( preg_replace( '/\s+/u', ' ', $note ) ?? '' );
	return "- [{$title}]({$url})" . ( '' !== $note ? ': ' . $note : '' ) . "\n";
}

/**
 * The /llms.txt content (cached).
 */
function hri_llms_txt(): string {
	$cached = get_transient( 'hri_llms_txt' );
	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}
	$langs = function_exists( 'pll_languages_list' ) && pll_languages_list() ? array_values( array_intersect( array_keys( hri_languages() ), pll_languages_list() ) ) : array( 'th' );
	$names = array( 'th' => 'ภาษาไทย', 'en' => 'English', 'ja' => '日本語' );

	$out  = "# HRI DeOne\n\n";
	$out .= '> ' . hri_company( 'tagline', 'en' ) . "\n> " . hri_company( 'tagline', 'th' ) . "\n\n";
	$out .= "HRI DeOne is an executive training and organizational development institute in Bangkok, Thailand: Public Training, In-house programs, Transformation Solutions and the Japanese Concept programs. The site is available in Thai, English and Japanese.\n\n";
	$out .= 'Contact: ' . hri_company( 'email' ) . ' · ' . hri_company( 'phone' ) . "\n";

	foreach ( $langs as $lang ) {
		$home  = function_exists( 'pll_home_url' ) ? (string) pll_home_url( $lang ) : home_url( '/' );
		$out  .= "\n## " . ( $names[ $lang ] ?? $lang ) . "\n\n";
		$out  .= hri_llms_line( 'Home', $home );
		foreach ( hri_llms_page_slugs() as $slug ) {
			$page = hri_llms_page( $slug, $lang );
			if ( $page ) {
				$out .= hri_llms_line( $page[0], $page[1] );
			}
		}
		$archive = function_exists( 'hri_localize_url' ) ? hri_localize_url( '/courses/' ) : '/courses/';
		$archive = str_starts_with( $archive, '/' ) ? home_url( $archive ) : $archive;
		$out    .= hri_llms_line( 'Courses', $archive );

		$blog = get_page_by_path( 'blog', OBJECT, 'page' );
		$bid  = $blog && function_exists( 'pll_get_post' ) ? (int) pll_get_post( $blog->ID, $lang ) : ( $blog ? $blog->ID : 0 );
		if ( $bid ) {
			$out .= hri_llms_line( 'Blog', get_permalink( $bid ) );
		}

		$courses = get_posts(
			array(
				'post_type'        => 'course',
				'post_status'      => 'publish',
				'numberposts'      => 200,
				'orderby'          => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'lang'             => $lang,
				'suppress_filters' => false,
			)
		);
		if ( $courses ) {
			$out .= "\n### " . ( 'th' === $lang ? 'หลักสูตร' : ( 'ja' === $lang ? 'コース' : 'Courses' ) ) . "\n\n";
			foreach ( $courses as $course ) {
				if ( get_post_meta( $course->ID, 'hri_external_url', true ) ) {
					continue; // Lives on another website.
				}
				$out .= hri_llms_line( wp_strip_all_tags( get_the_title( $course ) ), get_permalink( $course ), hri_seo_description( $course->ID ) );
			}
		}
	}

	$out .= "\n## Optional\n\n" . hri_llms_line( 'Sitemap', home_url( '/sitemap.xml' ) );
	$out  = (string) apply_filters( 'hri_llms_txt', $out );
	set_transient( 'hri_llms_txt', $out, DAY_IN_SECONDS );
	return $out;
}

// Content changed: rebuild on the next request.
add_action(
	'save_post',
	static function ( $post_id, $post ) {
		if ( $post instanceof WP_Post && in_array( $post->post_type, array( 'page', 'course' ), true ) ) {
			delete_transient( 'hri_llms_txt' );
		}
	},
	10,
	2
);
add_action( 'deleted_post', static fn() => delete_transient( 'hri_llms_txt' ) );
add_action( 'update_option_hri_company', static fn() => delete_transient( 'hri_llms_txt' ) );

/* ----------------------------------------------------------------------
 * IndexNow
 * ---------------------------------------------------------------------- */

/**
 * Whether pings may be sent: switched on, a production site, not Playground.
 */
function hri_indexnow_active(): bool {
	$opts = hri_ai_seo_options();
	if ( ! $opts['indexnow'] || '' === $opts['indexnow_key'] || 'production' !== wp_get_environment_type() ) {
		return false;
	}
	$software = (string) ( $_SERVER['SERVER_SOFTWARE'] ?? '' ); // phpcs:ignore WordPress.Security
	return ! defined( 'WP_PLAYGROUND' ) && ! str_contains( $software, 'PHP.wasm' ) && ! str_contains( home_url(), '127.0.0.1' ) && ! str_contains( home_url(), 'localhost' );
}

/** @var string[] URLs waiting to be sent at shutdown. */
$GLOBALS['hri_indexnow_queue'] = array();

add_action(
	'wp_after_insert_post',
	static function ( $post_id, $post ) {
		if ( ! $post instanceof WP_Post || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! hri_indexnow_active() ) {
			return;
		}
		if ( ! in_array( $post->post_type, array( 'post', 'page', 'course' ), true ) || 'publish' !== $post->post_status || hri_seo_is_noindex( $post_id ) ) {
			return;
		}
		$GLOBALS['hri_indexnow_queue'][] = get_permalink( $post_id );
	},
	20,
	2
);

add_action(
	'shutdown',
	static function () {
		$urls = array_values( array_unique( array_filter( (array) $GLOBALS['hri_indexnow_queue'] ) ) );
		if ( ! $urls || ! hri_indexnow_active() ) {
			return;
		}
		$key = hri_ai_seo_options()['indexnow_key'];
		wp_remote_post(
			'https://api.indexnow.org/indexnow',
			array(
				'timeout'  => 5,
				'blocking' => false,
				'headers'  => array( 'Content-Type' => 'application/json; charset=utf-8' ),
				'body'     => wp_json_encode(
					array(
						'host'        => wp_parse_url( home_url(), PHP_URL_HOST ),
						'key'         => $key,
						'keyLocation' => home_url( '/' . $key . '.txt' ),
						'urlList'     => array_slice( $urls, 0, 100 ),
					)
				),
			)
		);
	}
);

/* ----------------------------------------------------------------------
 * Settings → SEO & AI
 * ---------------------------------------------------------------------- */

add_action(
	'admin_menu',
	static function () {
		add_options_page( __( 'SEO และ AI', 'hri-core' ), __( 'SEO และ AI', 'hri-core' ), 'manage_options', 'hri-ai-seo', 'hri_ai_seo_admin_page' );
	}
);

add_action(
	'admin_init',
	static function () {
		register_setting(
			'hri_ai_seo',
			HRI_AI_SEO_OPTION,
			array(
				'type'              => 'array',
				'default'           => array(),
				'sanitize_callback' => static function ( $value ): array {
					$old = hri_ai_seo_options();
					$new = array(
						'training'     => empty( $value['training'] ) ? 0 : 1,
						'indexnow'     => empty( $value['indexnow'] ) ? 0 : 1,
						'indexnow_key' => $old['indexnow_key'],
					);
					if ( $new['indexnow'] && '' === $new['indexnow_key'] ) {
						$new['indexnow_key'] = bin2hex( random_bytes( 16 ) );
					}
					return $new;
				},
			)
		);
	}
);

/**
 * The page.
 */
function hri_ai_seo_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = hri_ai_seo_options();
	echo '<div class="wrap"><h1>' . esc_html__( 'SEO และ AI', 'hri-core' ) . '</h1>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'hri_ai_seo' );
	echo '<table class="form-table" role="presentation"><tbody>';

	echo '<tr><th scope="row">' . esc_html__( 'บอตที่เก็บเนื้อหาไปฝึกโมเดล AI', 'hri-core' ) . '</th><td><label><input type="checkbox" name="' . esc_attr( HRI_AI_SEO_OPTION ) . '[training]" value="1"' . checked( $opts['training'], 1, false ) . ' /> ' . esc_html__( 'อนุญาต', 'hri-core' ) . '</label>';
	echo '<p class="description">' . esc_html( implode( ', ', hri_ai_training_bots() ) ) . '<br />' . esc_html__( 'บอตค้นหาและตอบคำถามผู้ใช้ (ChatGPT Search, Claude, Perplexity, Bing, Google) อนุญาตเสมอ เพื่อให้เว็บถูกแนะนำในคำตอบของ AI · ปิดตรงนี้ = ไม่ให้นำเนื้อหาไปฝึกโมเดล แต่ไม่รับประกันว่าทุกเจ้าจะทำตาม', 'hri-core' ) . '</p></td></tr>';

	echo '<tr><th scope="row">IndexNow</th><td><label><input type="checkbox" name="' . esc_attr( HRI_AI_SEO_OPTION ) . '[indexnow]" value="1"' . checked( $opts['indexnow'], 1, false ) . ' /> ' . esc_html__( 'แจ้ง Bing เมื่อเผยแพร่หรือแก้หน้า', 'hri-core' ) . '</label>';
	echo '<p class="description">' . esc_html__( 'ส่งจริงเฉพาะเว็บ production (ไม่ส่งจาก Playground, localhost หรือ staging)', 'hri-core' );
	if ( '' !== $opts['indexnow_key'] ) {
		echo ' · ' . esc_html__( 'ไฟล์ key:', 'hri-core' ) . ' <a href="' . esc_url( home_url( '/' . $opts['indexnow_key'] . '.txt' ) ) . '">' . esc_html( $opts['indexnow_key'] ) . '.txt</a>';
	}
	echo '</p></td></tr>';

	echo '<tr><th scope="row">' . esc_html__( 'ไฟล์ที่สร้างให้อัตโนมัติ', 'hri-core' ) . '</th><td>';
	foreach ( array( '/robots.txt', '/llms.txt', '/sitemap.xml' ) as $path ) {
		echo '<a href="' . esc_url( home_url( $path ) ) . '" target="_blank" rel="noopener">' . esc_html( $path ) . '</a> &nbsp; ';
	}
	echo '<p class="description">' . esc_html__( 'ชื่อ คำอธิบาย และรูปแชร์ของแต่ละหน้าแก้ได้ที่กล่อง SEO ในหน้าแก้ไข · ข้อมูลบริษัทที่ใช้ในไฟล์เหล่านี้แก้ที่ ตั้งค่า → ข้อมูลบริษัท', 'hri-core' ) . '</p></td></tr>';

	echo '</tbody></table>';
	submit_button();
	echo '</form></div>';
}
