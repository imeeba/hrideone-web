<?php
/**
 * Sample English and Japanese translations for previews and local testing. Safe to run repeatedly.
 *
 * Run after seed.php and after the Polylang setup (hri_polylang_setup() / `wp hri languages`).
 * Creates an EN and a JA translation of every Thai course, of the home, In-house, Public Training
 * and Style Guide pages and of the sample blog posts. Texts are machine-made samples (JA needs a native review).
 *
 * Polylang (free) needs a different slug per language, so the samples use "-en" / "-ja" suffixes:
 * /en/courses/negotiation-mastery-en/. Real content can use any slug.
 *
 * Usage: `wp eval-file playground/seed-languages.php`, or from a Playground blueprint.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'pll_set_post_language' ) || ! function_exists( 'hri_polylang_is_set_up' ) || ! hri_polylang_is_set_up() ) {
	echo "Polylang languages are not set up: skipped.\n";
	return;
}

$targets = array( 'en', 'ja' );

$excerpts = array(
	'พลิกเกมธุรกิจด้วยสุดยอดหลักสูตรการเจรจาต่อรอง'         => array(
		'en' => 'Change the game in business with a top-tier negotiation course.',
		'ja' => '交渉力を磨き、ビジネスの流れを変える実践講座。',
	),
	'พร้อมก้าวข้ามขีดจำกัด สู่บทบาท Director ที่องค์กรต้องการ' => array(
		'en' => 'Go beyond your limits and step into the Director role your organization needs.',
		'ja' => '限界を越え、組織が求めるディレクターへ。',
	),
	'พลิกบทบาทผู้นำ สร้างบรรยากาศให้ทีมเติบโตอย่างยั่งยืน'     => array(
		'en' => 'Rethink leadership and create a climate where your team grows sustainably.',
		'ja' => 'リーダーの役割を見直し、チームが持続的に成長する環境をつくる。',
	),
	'ปลดล็อกศักยภาพธุรกิจ ด้วยแก่นแท้ระบบการผลิตระดับโลกจาก TOYOTA' => array(
		'en' => 'Unlock your business potential with the essence of the world-class TOYOTA production system.',
		'ja' => 'TOYOTAの世界水準の生産方式の本質で、ビジネスの可能性を引き出す。',
	),
);

$sample_body = array(
	'en' => '<!-- wp:paragraph --><p>Sample text for testing. This is not the real course content yet.</p><!-- /wp:paragraph -->',
	'ja' => '<!-- wp:paragraph --><p>テスト用のサンプル文です。実際の講座内容ではありません。</p><!-- /wp:paragraph -->',
);

/**
 * Create (or update) the translation of $source_id in $lang and link it.
 *
 * @param array<string,mixed> $fields wp_insert_post fields for the translation.
 */
$translate = static function ( int $source_id, string $lang, array $fields ): int {
	$existing = (int) pll_get_post( $source_id, $lang );
	$source   = get_post( $source_id );

	$post_id = wp_insert_post(
		array_merge(
			array(
				'ID'          => $existing,
				'post_type'   => $source->post_type,
				'post_status' => 'publish',
				'post_name'   => $source->post_name . '-' . $lang,
				'post_title'  => $source->post_title,
				'menu_order'  => $source->menu_order,
			),
			$fields
		)
	);
	if ( ! $post_id || is_wp_error( $post_id ) ) {
		return 0;
	}

	pll_set_post_language( $post_id, $lang );
	$translations          = pll_get_post_translations( $source_id );
	$translations['th']    = $source_id;
	$translations[ $lang ] = $post_id;
	pll_save_post_translations( $translations );

	return (int) $post_id;
};

require_once is_readable( __DIR__ . '/seed-course-data.php' ) ? __DIR__ . '/seed-course-data.php' : __DIR__ . '/hri-seed-course-data.php';
require_once is_readable( __DIR__ . '/seed-home.php' ) ? __DIR__ . '/seed-home.php' : __DIR__ . '/hri-seed-home.php';
$course_data = hri_seed_public_courses();
$inhouse     = hri_seed_inhouse_courses();

// Trainers: name, position and bio per language.
foreach ( hri_seed_trainers() as $key => $t ) {
	$found = get_posts( array( 'post_type' => 'trainer', 'name' => 'trainer-' . $key, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'lang' => '' ) );
	if ( ! $found ) {
		continue;
	}
	foreach ( $targets as $lang ) {
		$id = $translate( (int) $found[0], $lang, array( 'post_title' => $t[ $lang ][0], 'post_excerpt' => $t[ $lang ][1], 'post_content' => $t[ $lang ][2] ) );
		if ( $id ) {
			$thumb = get_post_thumbnail_id( $found[0] );
			if ( $thumb ) {
				set_post_thumbnail( $id, $thumb );
			}
			hri_seed_copy_remote_thumb( (int) $found[0], (int) $id );
		}
	}
}

// Courses: same terms, fields and image as the Thai course.
$courses = get_posts(
	array(
		'post_type'   => 'course',
		'post_status' => 'publish',
		'numberposts' => -1,
		'lang'        => 'th',
	)
);
$count   = 0;
foreach ( $courses as $course ) {
	foreach ( $targets as $lang ) {
		$excerpt = $excerpts[ $course->post_excerpt ][ $lang ] ?? '';
		$id      = $translate(
			$course->ID,
			$lang,
			array(
				'post_excerpt' => $excerpt,
				'post_content' => $sample_body[ $lang ],
			)
		);
		if ( ! $id ) {
			continue;
		}
		foreach ( hri_course_taxonomy_names() as $taxonomy ) {
			$terms = wp_get_object_terms( $course->ID, $taxonomy, array( 'fields' => 'ids' ) );
			wp_set_object_terms( $id, is_array( $terms ) ? array_map( 'intval', $terms ) : array(), $taxonomy );
		}
		foreach ( array_keys( hri_course_meta_schema() ) as $key ) {
			$value = get_post_meta( $course->ID, $key, true );
			if ( '' === $value ) {
				delete_post_meta( $id, $key );
			} else {
				update_post_meta( $id, $key, $value );
			}
		}
		$thumb = get_post_thumbnail_id( $course->ID );
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}
		hri_seed_copy_remote_thumb( (int) $course->ID, (int) $id );
		// Real texts of the Public Training courses in this language.
		if ( isset( $course_data[ $course->post_name ][ $lang ] ) ) {
			$d      = $course_data[ $course->post_name ][ $lang ];
			$update = array( 'ID' => $id, 'post_excerpt' => $d['excerpt'] );
			foreach ( $d['text'] as $k => $v ) {
				update_post_meta( $id, $k, $v );
			}
			if ( ! empty( $d['page'] ) ) {
				$update['post_content'] = wp_slash( hri_seed_course_body( $d['page'], 'public', 'en' === $lang ? 'en_US' : 'ja', $course->post_name ) );
			}
			wp_update_post( $update );
		}
		// In-house course: real content (Coaching Mastery) or the placeholder sections.
		if ( has_term( 'inhouse', 'hri_program', $course->ID ) ) {
			$d = $inhouse[ $course->post_name ][ $lang ] ?? null;
			wp_update_post(
				array(
					'ID'           => $id,
					'post_excerpt' => $d ? $d['excerpt'] : '',
					'post_content' => wp_slash( hri_seed_course_body( $d ? $d['page'] : 'sample', 'inhouse', 'en' === $lang ? 'en_US' : 'ja', $course->post_name ) ),
				)
			);
		}
		++$count;
	}
}

// Pages built from patterns: the same patterns rendered with the EN / JA theme translations.
require_once is_readable( __DIR__ . '/seed-home.php' ) ? __DIR__ . '/seed-home.php' : __DIR__ . '/hri-seed-home.php';
$pages = array(
	'in-house'                 => array( 'en' => 'In-house Training', 'ja' => '企業内研修' ),
	'public-training'          => array( 'en' => 'Public Training', 'ja' => '公開講座' ),
	'transformation-solutions' => array( 'en' => 'Transformation Solutions', 'ja' => 'トランスフォーメーション・ソリューション' ),
	'japanese-concept'         => array( 'en' => 'Japanese Concept', 'ja' => 'ジャパニーズ・コンセプト' ),
	'contact-us'               => array( 'en' => 'Contact Us', 'ja' => 'お問い合わせ' ),
	'about'                    => array( 'en' => 'About Us', 'ja' => '会社概要' ),
);
foreach ( $pages as $slug => $titles ) {
	$source = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'publish', 'numberposts' => 1, 'lang' => 'th' ) );
	if ( ! $source ) {
		continue;
	}
	foreach ( $targets as $lang ) {
		$locale = PLL()->model->get_language( $lang )->locale ?? '';
		$translate(
			$source[0]->ID,
			$lang,
			array(
				'post_title'   => $titles[ $lang ],
				'post_content' => wp_slash( hri_seed_page_content( $slug, $locale ) ),
			)
		);
	}
}

// Style Guide: same content, translated title.
$style_guide = get_posts( array( 'post_type' => 'page', 'name' => 'style-guide', 'post_status' => 'publish', 'numberposts' => 1, 'lang' => 'th' ) );
if ( $style_guide ) {
	foreach ( array( 'en' => 'Style Guide', 'ja' => 'スタイルガイド' ) as $lang => $title ) {
		$translate( $style_guide[0]->ID, $lang, array( 'post_title' => $title, 'post_content' => wp_slash( $style_guide[0]->post_content ) ) );
	}
}

// Home page: the same patterns rendered with the EN / JA theme translations.
$home = get_posts( array( 'post_type' => 'page', 'name' => 'home', 'post_status' => 'publish', 'numberposts' => 1, 'lang' => 'th' ) );
if ( $home ) {
	$titles = array( 'en' => 'Home', 'ja' => 'ホーム' );
	foreach ( $targets as $lang ) {
		$locale = PLL()->model->get_language( $lang )->locale ?? '';
		$translate(
			$home[0]->ID,
			$lang,
			array(
				'post_title'   => $titles[ $lang ],
				'post_content' => wp_slash( hri_seed_home_content( $locale ) ),
			)
		);
	}
}

// Blog: the real articles of the old site in TH / EN / JA, linked as translations
// (playground/data/blog-posts.json, made by scripts/wxr-to-blog.py). Images: copies in playground/media (scripts/demo-media.py), served by jsDelivr.
$blog_file = is_readable( __DIR__ . '/data/blog-posts.json' ) ? __DIR__ . '/data/blog-posts.json' : __DIR__ . '/hri-blog-posts.json';
$blog_data = hri_blog_import_read( $blog_file );
if ( is_wp_error( $blog_data ) ) {
	echo $blog_data->get_error_message() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
} else {
	$blog_stats = hri_blog_import( $blog_data );
	echo "Imported {$blog_stats['total']} articles (created {$blog_stats['created']}, updated {$blog_stats['updated']}).\n"; // phpcs:ignore WordPress.Security.EscapeOutput
}

// Header / footer texts (Polylang strings, Languages → Translations). Thai menu labels stay English.
$part_strings = array(
	'In-house'         => array( 'en' => 'In-house', 'ja' => '企業内研修' ),
	'Public Training'  => array( 'en' => 'Public Training', 'ja' => '公開講座' ),
	'Transformation'   => array( 'en' => 'Transformation', 'ja' => '変革ソリューション' ),
	'Japanese Concept' => array( 'en' => 'Japanese Concept', 'ja' => 'ジャパニーズ・コンセプト' ),
	'Blog'             => array( 'en' => 'Blog', 'ja' => 'ブログ' ),
	'About'            => array( 'en' => 'About', 'ja' => '会社概要' ),
	'Contact'          => array( 'en' => 'Contact', 'ja' => 'お問い合わせ' ),
	'หลักสูตรทั้งหมด'      => array( 'en' => 'All courses', 'ja' => 'すべての講座' ),
	'หลักสูตรและบริการ'    => array( 'en' => 'Programs & services', 'ja' => 'プログラム・サービス' ),
	'บริษัท'             => array( 'en' => 'Company', 'ja' => '会社情報' ),
	'ติดต่อเรา'           => array( 'en' => 'Contact us', 'ja' => 'お問い合わせ' ),
);
foreach ( $targets as $lang ) {
	$language = PLL()->model->get_language( $lang );
	if ( ! $language ) {
		continue;
	}
	$mo = new PLL_MO();
	$mo->import_from_db( $language );
	foreach ( $part_strings as $source => $translations ) {
		$mo->add_entry( $mo->make_entry( $source, $translations[ $lang ] ) );
	}
	$mo->export_to_db( $language );
}

// Blog page of each language (the posts page). JA keeps the old site's slug (/ja/ブログ/); EN cannot
// reuse "blog" (taken by TH, Polylang free) → /en/blog-en/, the old /en/blog/ redirects (hri-core blog.php).
$blog_th = get_page_by_path( 'blog' );
if ( $blog_th ) {
	foreach ( array( 'en' => array( 'Blog', 'blog-en' ), 'ja' => array( 'ブログ', 'ブログ' ) ) as $lang => $page ) {
		$translate( $blog_th->ID, $lang, array( 'post_title' => $page[0], 'post_name' => $page[1], 'post_content' => '' ) );
	}
}

// Languages cache the ID of their front page translation: rebuild it.
PLL()->model->clean_languages_cache();
flush_rewrite_rules();
echo "Translated {$count} course versions.\n";
