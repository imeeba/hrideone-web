<?php
/**
 * Accessibility (WCAG 2.2 AA) helpers that work on any content, including what editors paste:
 *
 * - Every <iframe> gets a title (WCAG 4.1.2) when it has none: a Google Maps embed code pasted
 *   from Google has no title.
 * - The Accessibility Statement page (TH / EN / JA), created once by a migration or the Tools page.
 * - Editor checks: images without alt text and skipped heading levels are listed before publishing
 *   (a warning only; publishing is never blocked).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/* ----------------------------------------------------------------------
 * <iframe> without a title
 * ---------------------------------------------------------------------- */

/**
 * Title for an embed, guessed from its address.
 *
 * @param string $src The iframe's src.
 */
function hri_iframe_title( string $src ): string {
	if ( preg_match( '#google\.[a-z.]+/maps|maps\.google\.#i', $src ) ) {
		return __( 'แผนที่สำนักงาน HRI DeOne', 'hri-core' );
	}
	if ( preg_match( '#youtube(-nocookie)?\.com|youtu\.be|vimeo\.com#i', $src ) ) {
		return __( 'วิดีโอ', 'hri-core' );
	}
	return __( 'เนื้อหาจากเว็บไซต์ภายนอก', 'hri-core' );
}

/**
 * Adds a title to every iframe in the HTML that has none (or an empty one).
 *
 * @param string $html Rendered block HTML.
 */
function hri_iframe_add_titles( string $html ): string {
	if ( ! str_contains( $html, '<iframe' ) ) {
		return $html;
	}
	$tags    = new WP_HTML_Tag_Processor( $html );
	$changed = false;
	while ( $tags->next_tag( 'iframe' ) ) {
		$title = $tags->get_attribute( 'title' );
		if ( is_string( $title ) && '' !== trim( $title ) ) {
			continue;
		}
		$tags->set_attribute( 'title', hri_iframe_title( (string) $tags->get_attribute( 'src' ) ) );
		$changed = true;
	}
	return $changed ? $tags->get_updated_html() : $html;
}

add_filter(
	'render_block',
	static function ( $content ) {
		if ( is_string( $content ) && str_contains( $content, '<iframe' ) ) {
			$GLOBALS['hri_has_iframe'] = true;
			return hri_iframe_add_titles( $content );
		}
		return $content;
	},
	20
);

/*
 * Focus ring on an iframe (WCAG 2.4.7). When Tab moves into an embedded page (Google Maps), the
 * focus is inside that page, so no CSS selector matches the iframe. The window loses focus at
 * that moment: mark the iframe with a class (styled in the theme) until focus comes back.
 */
add_action(
	'wp_footer',
	static function () {
		if ( empty( $GLOBALS['hri_has_iframe'] ) ) {
			return;
		}
		echo "<script>(function(){var c='hri-iframe-focus',f=null;window.addEventListener('blur',function(){setTimeout(function(){var a=document.activeElement;if(a&&a.tagName==='IFRAME'){f=a;a.classList.add(c);}},0);});window.addEventListener('focus',function(){if(f){f.classList.remove(c);f=null;}});})();</script>\n";
	}
);

/* ----------------------------------------------------------------------
 * Accessibility Statement page
 * ---------------------------------------------------------------------- */

/**
 * Slug of the statement page per language (Polylang free needs a different slug per language).
 *
 * @return array<string,string>
 */
function hri_a11y_statement_slugs(): array {
	return array(
		'th' => 'accessibility',
		'en' => 'accessibility-en',
		'ja' => 'accessibility-ja',
	);
}

/**
 * The statement texts. Kept here (not in .po files) because they are page content the
 * marketing team edits in the editor afterwards.
 *
 * @return array<string,array{title:string,intro:string,sections:array<int,array{0:string,1:array<int,string>,2?:bool}>,contact:string,updated:string}>
 */
function hri_a11y_statement_texts(): array {
	return array(
		'th' => array(
			'title'    => 'การเข้าถึงเว็บไซต์',
			'intro'    => 'HRI DeOne ต้องการให้ทุกคนใช้เว็บไซต์นี้ได้ ไม่ว่าจะเป็นผู้พิการ ผู้สูงอายุ หรือผู้ที่ใช้มือถือ คีย์บอร์ด หรือโปรแกรมอ่านหน้าจอ',
			'sections' => array(
				array( 'มาตรฐานที่เราตั้งเป้า', array( 'เว็บไซต์นี้ออกแบบให้เป็นไปตามมาตรฐาน Web Content Accessibility Guidelines (WCAG) 2.2 ระดับ AA ของ W3C' ) ),
				array(
					'สิ่งที่เราทำ',
					array(
						'ใช้งานได้ด้วยคีย์บอร์ดทั้งหมด และมีลิงก์ "ข้ามไปยังเนื้อหา" ที่ด้านบนของทุกหน้า',
						'สีตัวอักษรตัดกับพื้นหลังตามเกณฑ์ และขยายตัวอักษรได้ถึง 200% โดยเนื้อหาไม่หาย',
						'รูปที่มีความหมายมีคำอธิบาย (alt text) และหัวข้อเรียงลำดับเพื่อให้โปรแกรมอ่านหน้าจออ่านได้ถูกต้อง',
						'ภาพเคลื่อนไหวลดลงอัตโนมัติเมื่อเครื่องของคุณตั้งค่า "ลดการเคลื่อนไหว"',
						'มีเนื้อหาครบ 3 ภาษา: ไทย อังกฤษ และญี่ปุ่น',
					),
					true,
				),
				array(
					'ข้อจำกัดที่เราทราบ',
					array(
						'แผนที่ Google Maps และวิดีโอ YouTube มาจากผู้ให้บริการภายนอก จึงอาจใช้งานกับเทคโนโลยีช่วยเหลือได้ไม่ครบ เรามีที่อยู่และข้อมูลติดต่อเป็นข้อความไว้ให้ทุกครั้ง',
						'บทความเก่าที่ย้ายมาจากเว็บไซต์เดิมบางเรื่องอาจยังมีรูปที่ไม่มีคำอธิบาย เรากำลังทยอยแก้ไข',
					),
					true,
				),
				array( 'วิธีที่เราตรวจสอบ', array( 'เราตรวจทุกครั้งที่แก้เว็บด้วยเครื่องมืออัตโนมัติ (axe) และทดสอบด้วยคีย์บอร์ด การขยายหน้าจอ และโปรแกรมอ่านหน้าจอบนมือถือ' ) ),
			),
			'contact'  => 'หากพบส่วนใดที่ใช้งานไม่ได้ หรือต้องการข้อมูลในรูปแบบอื่น กรุณาแจ้งเราทางอีเมลหรือโทรศัพท์ด้านล่าง เราจะตอบกลับภายใน 5 วันทำการ',
			'updated'  => 'ปรับปรุงล่าสุด: 8 พฤศจิกายน 2569',
		),
		'en' => array(
			'title'    => 'Accessibility Statement',
			'intro'    => 'HRI DeOne wants everyone to be able to use this website, including people with disabilities, older people, and people who use a phone, a keyboard or a screen reader.',
			'sections' => array(
				array( 'Our target', array( 'This website is designed to conform to the W3C Web Content Accessibility Guidelines (WCAG) 2.2, level AA.' ) ),
				array(
					'What we do',
					array(
						'Everything works with a keyboard, and every page starts with a "Skip to content" link.',
						'Text colors meet the contrast requirements, and text can be enlarged to 200% without losing content.',
						'Meaningful images have text alternatives, and headings are in order so screen readers read pages correctly.',
						'Animations are reduced when your device is set to "Reduce motion".',
						'All content is available in Thai, English and Japanese.',
					),
					true,
				),
				array(
					'Known limitations',
					array(
						'Google Maps and YouTube videos come from third parties and may not fully support assistive technologies. Our address and contact details are always also given as text.',
						'Some older articles moved from our previous website may still have images without descriptions. We are fixing them.',
					),
					true,
				),
				array( 'How we check', array( 'We check every change with automated tools (axe), and test with a keyboard, screen magnification and mobile screen readers.' ) ),
			),
			'contact'  => 'If something does not work for you, or you need information in another format, please contact us by email or phone below. We will reply within 5 working days.',
			'updated'  => 'Last updated: 8 November 2026',
		),
		'ja' => array(
			'title'    => 'ウェブアクセシビリティ方針',
			'intro'    => 'HRI DeOne は、障がいのある方や高齢の方、スマートフォン・キーボード・スクリーンリーダーをお使いの方を含め、すべての方に本サイトをご利用いただけることを目指しています。',
			'sections' => array(
				array( '目標とする基準', array( '本サイトは W3C の Web Content Accessibility Guidelines（WCAG）2.2 レベル AA への準拠を目標に設計しています。' ) ),
				array(
					'取り組み',
					array(
						'すべての操作をキーボードで行えます。各ページの先頭に「コンテンツへスキップ」リンクがあります。',
						'文字と背景のコントラストは基準を満たし、文字を 200% まで拡大しても内容が失われません。',
						'意味のある画像には代替テキストを付け、見出しを順序どおりに構成しています。',
						'端末で「視差効果を減らす」を設定すると、アニメーションが控えめになります。',
						'すべてのコンテンツを日本語・英語・タイ語で提供しています。',
					),
					true,
				),
				array(
					'既知の制限',
					array(
						'Google マップと YouTube 動画は外部サービスのため、支援技術に完全には対応していない場合があります。住所と連絡先は常にテキストでも掲載しています。',
						'旧サイトから移行した一部の記事には、説明のない画像が残っている場合があります。順次修正しています。',
					),
					true,
				),
				array( '確認方法', array( 'サイトを更新するたびに自動ツール（axe）で確認し、キーボード操作・画面拡大・スマートフォンのスクリーンリーダーでテストしています。' ) ),
			),
			'contact'  => 'ご利用いただけない箇所や、別の形式での情報が必要な場合は、下記のメールまたはお電話でご連絡ください。5 営業日以内にご返信いたします。',
			'updated'  => '最終更新日：2026年11月8日',
		),
	);
}

/**
 * Block markup of the statement in one language.
 *
 * @param string $lang Language slug (th, en, ja).
 */
function hri_a11y_statement_markup( string $lang ): string {
	$texts = hri_a11y_statement_texts();
	$t     = $texts[ $lang ] ?? $texts['th'];
	$p     = static fn( string $text ): string => "<!-- wp:paragraph -->\n<p>" . esc_html( $text ) . "</p>\n<!-- /wp:paragraph -->\n";

	$out  = '<!-- wp:group {"tagName":"section","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90"}}},"layout":{"type":"constrained","contentSize":"760px"}} -->' . "\n";
	$out .= '<section class="wp-block-group" style="padding-top:var(--wp--preset--spacing--90);padding-bottom:var(--wp--preset--spacing--90)">' . "\n";
	$out .= "<!-- wp:heading {\"level\":1} -->\n<h1 class=\"wp-block-heading\">" . esc_html( $t['title'] ) . "</h1>\n<!-- /wp:heading -->\n";
	$out .= $p( $t['intro'] );
	foreach ( $t['sections'] as $section ) {
		$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $section[0] ) . "</h2>\n<!-- /wp:heading -->\n";
		if ( ! empty( $section[2] ) ) {
			$out .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
			foreach ( $section[1] as $item ) {
				$out .= "<!-- wp:list-item -->\n<li>" . esc_html( $item ) . "</li>\n<!-- /wp:list-item -->\n";
			}
			$out .= "</ul>\n<!-- /wp:list -->\n";
		} else {
			foreach ( $section[1] as $text ) {
				$out .= $p( $text );
			}
		}
	}
	$contact_heading = array( 'th' => 'แจ้งปัญหาการใช้งาน', 'en' => 'Tell us about a problem', 'ja' => 'お問い合わせ' );
	$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $contact_heading[ $lang ] ?? $contact_heading['th'] ) . "</h2>\n<!-- /wp:heading -->\n";
	$out .= $p( $t['contact'] );
	$out .= "<!-- wp:hri/contact-info /-->\n";
	$out .= "<!-- wp:paragraph {\"fontSize\":\"small\"} -->\n<p class=\"has-small-font-size\">" . esc_html( $t['updated'] ) . "</p>\n<!-- /wp:paragraph -->\n";
	$out .= "</section>\n<!-- /wp:group -->";
	return $out;
}

/**
 * Creates the statement page in every language that does not have it yet, and links the
 * translations. Never changes a page that exists (editors may have changed it).
 *
 * @return string[] One line per page created.
 */
function hri_a11y_statement_create(): array {
	$done  = array();
	$ids   = array();
	$texts = hri_a11y_statement_texts();
	$langs = hri_has_polylang() ? (array) pll_languages_list() : array( 'th' );

	foreach ( hri_a11y_statement_slugs() as $lang => $slug ) {
		if ( ! in_array( $lang, $langs, true ) ) {
			continue;
		}
		$found = get_posts(
			array(
				'post_type'        => 'page',
				'name'             => $slug,
				'post_status'      => 'any',
				'posts_per_page'   => 1,
				'fields'           => 'ids',
				'lang'             => '',
				'suppress_filters' => true,
			)
		);
		if ( $found ) {
			$ids[ $lang ] = (int) $found[0];
			continue;
		}
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_name'    => $slug,
					'post_title'   => $texts[ $lang ]['title'],
					'post_content' => hri_a11y_statement_markup( $lang ),
				)
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		if ( function_exists( 'pll_set_post_language' ) ) {
			pll_set_post_language( $id, $lang );
		}
		$ids[ $lang ] = (int) $id;
		$done[]       = "accessibility page ({$lang})";
	}

	if ( count( $ids ) > 1 && function_exists( 'pll_save_post_translations' ) ) {
		pll_save_post_translations( $ids );
	}
	if ( $done ) {
		delete_transient( 'hri_llms_txt' );
	}
	return $done;
}

/**
 * Japanese label of the footer link (Polylang string), unless someone already translated it.
 */
function hri_a11y_footer_label(): void {
	if ( ! class_exists( 'PLL_MO' ) || ! function_exists( 'PLL' ) ) {
		return;
	}
	$language = PLL()->model->get_language( 'ja' );
	if ( ! $language ) {
		return;
	}
	$mo = new PLL_MO();
	$mo->import_from_db( $language );
	if ( 'Accessibility' !== $mo->translate( 'Accessibility' ) ) {
		return;
	}
	$mo->add_entry( $mo->make_entry( 'Accessibility', 'アクセシビリティ' ) );
	$mo->export_to_db( $language );
}

/* ----------------------------------------------------------------------
 * Editor checks before publishing
 * ---------------------------------------------------------------------- */

add_action(
	'enqueue_block_editor_assets',
	static function () {
		wp_enqueue_script(
			'hri-a11y-check',
			plugins_url( 'assets/js/a11y-check.js', HRI_CORE_DIR . 'hri-core.php' ),
			array( 'wp-plugins', 'wp-editor', 'wp-element', 'wp-data', 'wp-i18n', 'wp-block-editor' ),
			HRI_CORE_VERSION,
			true
		);
		wp_localize_script(
			'hri-a11y-check',
			'hriA11yCheck',
			array(
				'title'     => __( 'ตรวจการเข้าถึง', 'hri-core' ),
				'ok'        => __( 'ไม่พบปัญหา: รูปมีคำอธิบายครบ และหัวข้อเรียงลำดับถูกต้อง', 'hri-core' ),
				/* translators: %d: number of images */
				'noAlt'     => __( 'รูป %d รูปยังไม่มีคำอธิบาย (Alt text) ถ้าเป็นรูปตกแต่งล้วนๆ ปล่อยว่างได้', 'hri-core' ),
				/* translators: 1: heading level that was skipped to, 2: previous level */
				'skip'      => __( 'หัวข้อข้ามระดับ: H%1$d ต่อจาก H%2$d (ควรเรียง H2 → H3 → H4)', 'hri-core' ),
				'h1'        => __( 'หน้านี้มี H1 มากกว่า 1 อัน', 'hri-core' ),
				'clickHint' => __( 'คลิกเพื่อไปที่บล็อกนั้น', 'hri-core' ),
			)
		);
	}
);
