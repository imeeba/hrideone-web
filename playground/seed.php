<?php
/**
 * Sample data for previews and local testing. Safe to run repeatedly.
 *
 * Course names and slugs come from the current hrideone.com. Levels, durations, formats and the
 * topic of each Public course are PLACEHOLDERS so the filter has something to filter:
 * replace them with real data in the editor.
 *
 * Also creates the home page (front page, from the theme's home patterns), three sample blog
 * posts and the Style Guide page.
 *
 * Usage: `wp eval-file playground/seed.php`, or from a Playground blueprint after wp-load.php.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$terms = array(
	'hri_program' => array( 'inhouse' => 'In-house', 'public' => 'Public Training' ),
	'hri_pillar'  => array( 'leadership' => 'Leadership', 'business' => 'Business' ),
	'hri_topic'   => array(
		'self-improvement'       => 'Self Improvement',
		'thinking'               => 'Thinking',
		'personal-communication' => 'Personal Communication',
		'coaching'               => 'Coaching',
		'team-communication'     => 'Team Communication',
		'strategy'               => 'Strategy',
		'sales'                  => 'Sales',
		'customer-service'       => 'Customer Service',
	),
	'hri_level'   => array( 'staff' => 'พนักงาน', 'manager' => 'ผู้จัดการ', 'director' => 'ผู้อำนวยการ', 'executive' => 'ผู้บริหารระดับสูง' ),
	'hri_format'  => array( 'on-site' => 'On-site', 'online' => 'Online', 'hybrid' => 'Hybrid' ),
);

$ids = array();
foreach ( $terms as $taxonomy => $list ) {
	foreach ( $list as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( ! $term ) {
			$created = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			$term    = is_wp_error( $created ) ? null : get_term( $created['term_id'], $taxonomy );
		}
		$ids[ $taxonomy ][ $slug ] = $term ? (int) $term->term_id : 0;
	}
}

// English / Japanese term names (shown on /en/ and /ja/ pages). Machine-made: JA needs a review.
$term_names = array(
	'hri_program' => array(
		'inhouse' => array( 'In-house', '企業内研修' ),
		'public'  => array( 'Public Training', '公開講座' ),
	),
	'hri_pillar'  => array(
		'leadership' => array( 'Leadership', 'リーダーシップ' ),
		'business'   => array( 'Business', 'ビジネス' ),
	),
	'hri_topic'   => array(
		'self-improvement'       => array( 'Self Improvement', '自己成長' ),
		'thinking'               => array( 'Thinking', '思考力' ),
		'personal-communication' => array( 'Personal Communication', 'パーソナル・コミュニケーション' ),
		'coaching'               => array( 'Coaching', 'コーチング' ),
		'team-communication'     => array( 'Team Communication', 'チーム・コミュニケーション' ),
		'strategy'               => array( 'Strategy', '戦略' ),
		'sales'                  => array( 'Sales', '営業' ),
		'customer-service'       => array( 'Customer Service', 'カスタマーサービス' ),
	),
	'hri_level'   => array(
		'staff'     => array( 'Staff', '一般社員' ),
		'manager'   => array( 'Manager', 'マネージャー' ),
		'director'  => array( 'Director', 'ディレクター' ),
		'executive' => array( 'Senior Executive', '経営層' ),
	),
	'hri_format'  => array(
		'on-site' => array( 'On-site', '対面' ),
		'online'  => array( 'Online', 'オンライン' ),
		'hybrid'  => array( 'Hybrid', 'ハイブリッド' ),
	),
);
foreach ( $term_names as $taxonomy => $list ) {
	foreach ( $list as $slug => $names ) {
		if ( ! empty( $ids[ $taxonomy ][ $slug ] ) ) {
			update_term_meta( $ids[ $taxonomy ][ $slug ], 'hri_name_en', $names[0] );
			update_term_meta( $ids[ $taxonomy ][ $slug ], 'hri_name_ja', $names[1] );
		}
	}
}

$all_formats = array( 'on-site', 'online', 'hybrid' );

// slug, title, program, pillar, topic, levels, formats, days, batch, excerpt
$courses = array(
	// In-house: Leadership.
	array( 'high-performance-leadership-mastery', 'High-Performance Leadership Mastery', 'inhouse', 'leadership', 'self-improvement', array( 'manager', 'director' ), $all_formats, 2, null, '' ),
	array( 'proactive-leadership-mastery', 'Proactive Leadership Mastery', 'inhouse', 'leadership', 'self-improvement', array( 'staff', 'manager' ), $all_formats, 1, null, '' ),
	array( 'time-and-priority-mastery', 'Time and Priority Mastery', 'inhouse', 'leadership', 'self-improvement', array( 'staff', 'manager' ), $all_formats, 1, null, '' ),
	array( 'growth-mindset-mastery', 'Growth Mindset Mastery', 'inhouse', 'leadership', 'thinking', array( 'staff', 'manager' ), $all_formats, 1, null, '' ),
	array( 'communication-mastery', 'Communication Mastery', 'inhouse', 'leadership', 'personal-communication', array( 'staff', 'manager' ), $all_formats, 2, null, '' ),
	array( 'coaching-mastery', 'Coaching Mastery', 'inhouse', 'leadership', 'coaching', array( 'manager', 'director' ), $all_formats, 2, null, '' ),
	array( 'conflict-management-mastery', 'Conflict Management Mastery', 'inhouse', 'leadership', 'team-communication', array( 'staff', 'manager' ), $all_formats, 1, null, '' ),
	// In-house: Business.
	array( 'change-transformation-mastery', 'Change & Transformation Mastery', 'inhouse', 'business', 'strategy', array( 'director', 'executive' ), $all_formats, 2, null, '' ),
	array( 'b2b-selling-masterclass', 'B2B Selling Masterclass', 'inhouse', 'business', 'sales', array( 'staff', 'manager' ), $all_formats, 2, null, '' ),
	array( 'negotiation-masterclass', 'Negotiation Masterclass', 'inhouse', 'business', 'sales', array( 'staff', 'manager' ), $all_formats, 1, null, '' ),
	array( 'strategic-selling-masterclass', 'Strategic Selling Masterclass', 'inhouse', 'business', 'sales', array( 'manager', 'director' ), $all_formats, 2, null, '' ),
	array( 'consultative-selling-masterclass', 'Consultative Selling Masterclass', 'inhouse', 'business', 'sales', array( 'staff', 'manager' ), $all_formats, 2, null, '' ),
	array( 'essential-sales-masterclass', 'Essential Sales Masterclass', 'inhouse', 'business', 'sales', array( 'staff' ), $all_formats, 1, null, '' ),
	array( 'service-excellence', 'Service Excellence', 'inhouse', 'business', 'customer-service', array( 'staff' ), $all_formats, 1, null, '' ),
	array( 'complaint-handling-excellence', 'Complaint Handling Excellence', 'inhouse', 'business', 'customer-service', array( 'staff' ), $all_formats, 1, null, '' ),
	// Public Training: current batch only.
	array( 'negotiation-mastery', 'Negotiation Mastery', 'public', 'business', 'sales', array( 'manager', 'director' ), array( 'on-site' ), 0, 3, 'พลิกเกมธุรกิจด้วยสุดยอดหลักสูตรการเจรจาต่อรอง' ),
	array( 'the-next-director', 'The Next Director', 'public', 'leadership', 'self-improvement', array( 'director' ), array( 'on-site' ), 0, 3, 'พร้อมก้าวข้ามขีดจำกัด สู่บทบาท Director ที่องค์กรต้องการ' ),
	array( 'oxygen-leadership', 'Oxygen Leadership', 'public', 'leadership', 'self-improvement', array( 'manager', 'director' ), array( 'on-site' ), 0, 2, 'พลิกบทบาทผู้นำ สร้างบรรยากาศให้ทีมเติบโตอย่างยั่งยืน' ),
	array( 'the-new-manager', 'The New Manager', 'public', 'leadership', 'team-communication', array( 'manager' ), array( 'on-site' ), 0, 22, '' ),
	array( 'ten-x', 'TEN X', 'public', 'business', 'strategy', array( 'executive' ), array( 'on-site' ), 0, 6, 'หลักสูตรผู้บริหารระดับสูงเพื่อการเติบโตแบบก้าวกระโดด จัดร่วมกับ UTCC' ),
	array( 'lean-transformation-in-7-weeks', 'Lean Transformation in 7 weeks', 'public', 'business', 'strategy', array( 'manager', 'director' ), array( 'hybrid' ), 0, 3, 'ปลดล็อกศักยภาพธุรกิจ ด้วยแก่นแท้ระบบการผลิตระดับโลกจาก TOYOTA' ),
);

$map = static function ( string $taxonomy, array $slugs ) use ( $ids ): array {
	return array_values( array_filter( array_map( static fn( $slug ) => $ids[ $taxonomy ][ $slug ] ?? 0, $slugs ) ) );
};

// Sample thumbnails (abstract, 3:2). Two courses stay without one to show the fallback.
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$sample_dirs   = array( __DIR__ . '/sample', __DIR__ );
$sample_prefix = array( 'sample-', 'hri-sample-' );
$without_image = array( 'essential-sales-masterclass', 'the-new-manager' );

$sample_image = static function ( int $n ) use ( $sample_dirs, $sample_prefix ): string {
	foreach ( $sample_dirs as $dir ) {
		foreach ( $sample_prefix as $prefix ) {
			$file = "{$dir}/{$prefix}{$n}.jpg";
			if ( is_readable( $file ) ) {
				return $file;
			}
		}
	}
	return '';
};

$created = 0;
$index   = 0;
foreach ( $courses as $c ) {
	list( $slug, $title, $program, $pillar, $topic, $levels, $formats, $days, $batch, $excerpt ) = $c;

	$existing = get_posts( array( 'post_type' => 'course', 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'lang' => '' ) );
	$post_id  = wp_insert_post(
		array(
			'ID'           => $existing ? $existing[0] : 0,
			'post_type'    => 'course',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_excerpt' => $excerpt,
			'menu_order'   => 'public' === $program ? 0 : 10,
			'post_content' => '<!-- wp:paragraph --><p>ข้อมูลตัวอย่างสำหรับทดสอบระบบ ยังไม่ใช่เนื้อหาหลักสูตรจริง</p><!-- /wp:paragraph -->',
		)
	);
	if ( ! $post_id || is_wp_error( $post_id ) ) {
		continue;
	}

	wp_set_object_terms( $post_id, $map( 'hri_program', array( $program ) ), 'hri_program' );
	wp_set_object_terms( $post_id, $map( 'hri_pillar', array( $pillar ) ), 'hri_pillar' );
	wp_set_object_terms( $post_id, $map( 'hri_topic', array( $topic ) ), 'hri_topic' );
	wp_set_object_terms( $post_id, $map( 'hri_level', $levels ), 'hri_level' );
	wp_set_object_terms( $post_id, $map( 'hri_format', $formats ), 'hri_format' );

	if ( $days ) {
		update_post_meta( $post_id, 'hri_duration_days', $days );
	}
	if ( $batch ) {
		update_post_meta( $post_id, 'hri_batch_no', $batch );
		update_post_meta( $post_id, 'hri_reg_status', 'open' );
	}

	if ( ! has_post_thumbnail( $post_id ) && ! in_array( $slug, $without_image, true ) ) {
		$source = $sample_image( ( $index % 4 ) + 1 );
		if ( $source ) {
			$tmp = wp_tempnam( 'hri-sample.jpg' );
			copy( $source, $tmp );
			$attachment = media_handle_sideload( array( 'name' => "{$slug}.jpg", 'tmp_name' => $tmp ), $post_id, $title );
			if ( ! is_wp_error( $attachment ) ) {
				set_post_thumbnail( $post_id, $attachment );
				update_post_meta( $attachment, '_wp_attachment_image_alt', '' );
			}
		}
	}
	++$index;
	++$created;
}

// Trainers, course details and course pages of the Public Training courses (seed-course-data.php).
require_once is_readable( __DIR__ . '/seed-course-data.php' ) ? __DIR__ . '/seed-course-data.php' : __DIR__ . '/hri-seed-course-data.php';
require_once is_readable( __DIR__ . '/seed-home.php' ) ? __DIR__ . '/seed-home.php' : __DIR__ . '/hri-seed-home.php';

$trainer_ids = array();
$order       = 0;
foreach ( hri_seed_trainers() as $key => $t ) {
	$existing = get_posts( array( 'post_type' => 'trainer', 'name' => 'trainer-' . $key, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'lang' => '' ) );
	$id       = wp_insert_post(
		array(
			'ID'           => $existing ? $existing[0] : 0,
			'post_type'    => 'trainer',
			'post_status'  => 'publish',
			'post_name'    => 'trainer-' . $key,
			'post_title'   => $t['th'][0],
			'post_excerpt' => $t['th'][1],
			'post_content' => $t['th'][2],
			'menu_order'   => $order++,
		)
	);
	if ( $id && ! is_wp_error( $id ) ) {
		$trainer_ids[ $key ] = (int) $id;
	}
}

foreach ( hri_seed_public_courses() as $slug => $c ) {
	$found = get_posts( array( 'post_type' => 'course', 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'lang' => '' ) );
	if ( ! $found ) {
		continue;
	}
	$cid = (int) $found[0];
	foreach ( $c['meta'] as $k => $v ) {
		update_post_meta( $cid, $k, $v );
	}
	foreach ( $c['th']['text'] as $k => $v ) {
		update_post_meta( $cid, $k, $v );
	}
	update_post_meta( $cid, 'hri_trainers', implode( ',', array_filter( array_map( static fn( $k ) => $trainer_ids[ $k ] ?? 0, $c['trainers'] ) ) ) );
	$update = array( 'ID' => $cid, 'post_excerpt' => $c['th']['excerpt'] );
	if ( ! empty( $c['th']['page'] ) ) {
		$update['post_content'] = wp_slash( hri_seed_course_body( $c['th']['page'], 'public' ) );
	}
	wp_update_post( $update );
}

// In-house courses: Coaching Mastery has real content, the others get the placeholder sections.
$inhouse_data = hri_seed_inhouse_courses();
$inhouse_ids  = get_posts( array( 'post_type' => 'course', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'lang' => '', 'tax_query' => array( array( 'taxonomy' => 'hri_program', 'field' => 'slug', 'terms' => 'inhouse' ) ) ) );
foreach ( $inhouse_ids as $cid ) {
	$slug = get_post_field( 'post_name', $cid );
	$d    = $inhouse_data[ $slug ]['th'] ?? null;
	wp_update_post(
		array(
			'ID'           => $cid,
			'post_excerpt' => $d ? $d['excerpt'] : get_post_field( 'post_excerpt', $cid ),
			'post_content' => wp_slash( hri_seed_course_body( $d ? $d['page'] : 'sample', 'inhouse' ) ),
		)
	);
}

// Service pages built from the theme's section patterns (hri_page_sections()). In-house and
// Public Training include the Course Filter block locked to their program.
require_once is_readable( __DIR__ . '/seed-home.php' ) ? __DIR__ . '/seed-home.php' : __DIR__ . '/hri-seed-home.php';
$pages = array(
	'in-house'                 => 'In-house Training',
	'public-training'          => 'Public Training',
	'transformation-solutions' => 'Transformation Solutions',
	'japanese-concept'         => 'Japanese Concept',
	'contact-us'               => 'ติดต่อเรา',
	'about'                    => 'เกี่ยวกับเรา',
);
foreach ( $pages as $slug => $title ) {
	$existing = get_page_by_path( $slug );
	wp_insert_post(
		array(
			'ID'           => $existing ? $existing->ID : 0,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => wp_slash( hri_seed_page_content( $slug ) ),
		)
	);
}

// Blog posts for the home page "Knowledge" section: titles and slugs from the current site,
// body text is a placeholder.
$posts = array(
	array( 'hri-deone-launch-event-develop-leaders', 'HRI DeOne เปิดตัว อย่างเป็นทางการกับงาน “Develop Leaders, Transform the Future”', '-3 days', 1, 'business' ),
	array( 'why-leadership-remains-human-in-the-age-of-ai', 'เมื่อ AI ครองโลก ทำไม ‘เก้าอี้กัปตัน’ ถึงยังต้องเป็นของมนุษย์?', '-10 days', 2, 'business' ),
	array( 'why-success-ompanies-fail-in-15-years', 'ทำไมองค์กรที่เคยแข็งแกร่ง ถึงล้มหายไปใน 15 ปี', '-17 days', 3, 'business' ),
	// Sample posts: titles written from the real slugs (placeholders; the real posts are imported from the old site).
	array( 'top-reasons-digital-transformation-fails', 'Top Reasons Digital Transformation Fails', '-24 days', 4, 'business' ),
	array( 'unlock-data-power-for-superior-sales-strategy', 'Unlock Data Power for Superior Sales Strategy', '-31 days', 1, 'sales' ),
	array( 'pride-month-workplace-diversity-matters', 'Pride Month: Why Workplace Diversity Matters', '-38 days', 2, 'business' ),
	array( 'why-esg-matters-impact-cases-on-organizations', 'Why ESG Matters: Impact Cases on Organizations', '-45 days', 3, 'business' ),
	array( 'why-do-managers-need-to-develop-leadership-skills', 'Why Do Managers Need to Develop Leadership Skills?', '-52 days', 4, 'coaching' ),
	array( '5-ways-to-avoid-employees-feeling-alienated', '5 Ways to Avoid Employees Feeling Alienated', '-59 days', 1, 'coaching' ),
	array( '7-ways-to-change-yourself-to-share-your-opinion', '7 Ways to Change Yourself to Share Your Opinion', '-66 days', 2, 'coaching' ),
	array( '4-tips-to-solve-silo-problem', '4 Tips to Solve the Silo Problem', '-73 days', 3, 'business' ),
	array( 'professional-manager-can-create-different-results', 'A Professional Manager Can Create Different Results', '-80 days', 4, 'coaching' ),
	array( 'challenges-of-culture-in-the-new-era-organization', 'Challenges of Culture in the New-Era Organization', '-87 days', 1, 'business' ),
	array( 'too-kind-boss-is-silent-danger-in-organization', 'Too Kind Boss Is a Silent Danger in the Organization', '-94 days', 2, 'coaching' ),
	array( 'understanding-co-worker-style-improve-culture', 'Understanding Co-worker Style to Improve Culture', '-101 days', 3, 'sales' ),
);
$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $hello ) {
	wp_delete_post( $hello->ID, true ); // WordPress sample post.
}
$post_cats = array( 'business' => 'Business', 'sales' => 'Sales', 'coaching' => 'Coaching' );
foreach ( $post_cats as $cat_slug => $cat_name ) {
	if ( ! term_exists( $cat_slug, 'category' ) ) {
		wp_insert_term( $cat_name, 'category', array( 'slug' => $cat_slug ) );
	}
}
foreach ( $posts as $p ) {
	list( $slug, $title, $when, $image, $cat ) = $p;
	$existing = get_posts( array( 'post_type' => 'post', 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'lang' => '' ) );
	$post_id  = wp_insert_post(
		array(
			'ID'           => $existing ? $existing[0] : 0,
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_date'    => wp_date( 'Y-m-d H:i:s', strtotime( $when ) ),
			'post_content' => '<!-- wp:paragraph --><p>ข้อความตัวอย่าง เนื้อหาบทความจริงอยู่ที่เว็บเดิม</p><!-- /wp:paragraph -->',
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		$cat_term = get_term_by( 'slug', $cat, 'category' );
		if ( $cat_term ) {
			wp_set_object_terms( $post_id, array( (int) $cat_term->term_id ), 'category' );
		}
	}
	if ( $post_id && ! is_wp_error( $post_id ) && ! has_post_thumbnail( $post_id ) ) {
		$source = $sample_image( $image );
		if ( $source ) {
			$tmp        = wp_tempnam( 'hri-sample.jpg' );
			copy( $source, $tmp );
			$attachment = media_handle_sideload( array( 'name' => "{$slug}.jpg", 'tmp_name' => $tmp ), $post_id, $title );
			if ( ! is_wp_error( $attachment ) ) {
				set_post_thumbnail( $post_id, $attachment );
			}
		}
	}
}

// Blog page (posts page). EN / JA translations: seed-languages.php.
$blog    = get_page_by_path( 'blog' );
$blog_id = wp_insert_post( array( 'ID' => $blog ? $blog->ID : 0, 'post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'blog', 'post_title' => 'บทความ', 'post_content' => '' ) );
if ( $blog_id && ! is_wp_error( $blog_id ) ) {
	update_option( 'page_for_posts', $blog_id );
}

// Home page (front page). EN / JA translations: seed-languages.php.
$home    = get_page_by_path( 'home' );
$home_id = wp_insert_post(
	array(
		'ID'           => $home ? $home->ID : 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => 'home',
		'post_title'   => 'หน้าแรก',
		'post_content' => wp_slash( hri_seed_home_content() ),
	)
);
if ( $home_id && ! is_wp_error( $home_id ) ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
}

// Style Guide stays available at /style-guide/.
if ( ! get_page_by_path( 'style-guide' ) ) {
	wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Style Guide', 'post_name' => 'style-guide', 'post_content' => '<!-- wp:pattern {"slug":"hri/style-guide"} /-->' ) );
}

flush_rewrite_rules();
echo "Seeded {$created} courses.\n";
