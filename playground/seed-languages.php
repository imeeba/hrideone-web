<?php
/**
 * Sample English and Japanese translations for previews and local testing. Safe to run repeatedly.
 *
 * Run after seed.php and after the Polylang setup (hri_polylang_setup() / `wp hri languages`).
 * Creates an EN and a JA translation of every Thai course and of the In-house, Public Training
 * and Style Guide pages. Texts are machine-made samples (JA needs a native review).
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
		++$count;
	}
}

// Pages: the Thai content with the visible Thai text replaced.
$pages = array(
	'in-house'        => array(
		'en' => array(
			'title'   => 'In-house Training',
			'replace' => array(
				'หลักสูตร In-house' => 'In-house Courses',
				'หลักสูตรอบรมภายในองค์กร ปรับเนื้อหาให้ตรงกับเป้าหมายและบริบทของทีมคุณ (ข้อความตัวอย่าง)' => 'Training delivered inside your organization, tailored to your team’s goals and context. (sample text)',
			),
		),
		'ja' => array(
			'title'   => '企業内研修',
			'replace' => array(
				'หลักสูตร In-house' => '企業内研修プログラム',
				'หลักสูตรอบรมภายในองค์กร ปรับเนื้อหาให้ตรงกับเป้าหมายและบริบทของทีมคุณ (ข้อความตัวอย่าง)' => '貴社の目標と状況に合わせて内容を設計する企業内研修です。（サンプル文）',
			),
		),
	),
	'public-training' => array(
		'en' => array(
			'title'   => 'Public Training',
			'replace' => array(
				'หลักสูตร Public Training' => 'Public Training Courses',
				'หลักสูตรเปิดรับสมัครทั่วไป แสดงเฉพาะรุ่นที่เปิดอยู่ล่าสุด (ข้อความตัวอย่าง)' => 'Open-enrollment courses. Only the latest open batch of each course is shown. (sample text)',
			),
		),
		'ja' => array(
			'title'   => '公開講座',
			'replace' => array(
				'หลักสูตร Public Training' => '公開講座一覧',
				'หลักสูตรเปิดรับสมัครทั่วไป แสดงเฉพาะรุ่นที่เปิดอยู่ล่าสุด (ข้อความตัวอย่าง)' => 'どなたでもお申し込みいただける講座です。各講座の最新の開講回のみ表示しています。（サンプル文）',
			),
		),
	),
	'style-guide'     => array(
		'en' => array( 'title' => 'Style Guide', 'replace' => array() ),
		'ja' => array( 'title' => 'スタイルガイド', 'replace' => array() ),
	),
);

foreach ( $pages as $slug => $languages ) {
	$source = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'publish', 'numberposts' => 1, 'lang' => 'th' ) );
	if ( ! $source ) {
		continue;
	}
	$source = $source[0];
	foreach ( $languages as $lang => $page ) {
		$translate(
			$source->ID,
			$lang,
			array(
				'post_title'   => $page['title'],
				'post_content' => wp_slash( strtr( $source->post_content, $page['replace'] ) ),
			)
		);
	}
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

// Languages cache the ID of their front page translation: rebuild it.
PLL()->model->clean_languages_cache();
flush_rewrite_rules();
echo "Translated {$count} course versions.\n";
