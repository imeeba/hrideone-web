<?php
/**
 * Sample data for previews and local testing. Safe to run repeatedly.
 *
 * Course names and slugs come from the current hrideone.com. Levels, durations, formats and the
 * topic of each Public course are PLACEHOLDERS so the filter has something to filter:
 * replace them with real data in the editor.
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
	array( 'lean-transformation-in-7-weeks', 'Lean Transformation in 7 weeks', 'public', 'business', 'strategy', array( 'manager', 'director' ), array( 'hybrid' ), 0, 3, 'ปลดล็อกศักยภาพธุรกิจ ด้วยแก่นแท้ระบบการผลิตระดับโลกจาก TOYOTA' ),
);

$map = static function ( string $taxonomy, array $slugs ) use ( $ids ): array {
	return array_values( array_filter( array_map( static fn( $slug ) => $ids[ $taxonomy ][ $slug ] ?? 0, $slugs ) ) );
};

$created = 0;
foreach ( $courses as $c ) {
	list( $slug, $title, $program, $pillar, $topic, $levels, $formats, $days, $batch, $excerpt ) = $c;

	$existing = get_posts( array( 'post_type' => 'course', 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
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
	++$created;
}

// Listing pages: the same Course Filter block, locked to one program.
$listing = static function ( string $eyebrow, string $title, string $lead, string $program ): string {
	return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"}}},"backgroundColor":"surface-brand","layout":{"type":"constrained","contentSize":"1200px"}} -->'
		. '<div class="wp-block-group alignfull has-surface-brand-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)">'
		. '<!-- wp:paragraph {"className":"is-style-eyebrow"} --><p class="is-style-eyebrow">' . esc_html( $eyebrow ) . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">' . esc_html( $title ) . '</h1><!-- /wp:heading -->'
		. '<!-- wp:paragraph {"fontSize":"large","textColor":"contrast-muted"} --><p class="has-contrast-muted-color has-text-color has-large-font-size">' . esc_html( $lead ) . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:group -->'
		. '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->'
		. '<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80)">'
		. '<!-- wp:hri/course-filter {"program":"' . $program . '"} /-->'
		. '</div><!-- /wp:group -->';
};

$pages = array(
	'in-house'        => array( 'In-house Training', $listing( 'In-house Training', 'หลักสูตร In-house', 'หลักสูตรอบรมภายในองค์กร ปรับเนื้อหาให้ตรงกับเป้าหมายและบริบทของทีมคุณ (ข้อความตัวอย่าง)', 'inhouse' ) ),
	'public-training' => array( 'Public Training', $listing( 'Public Training', 'หลักสูตร Public Training', 'หลักสูตรเปิดรับสมัครทั่วไป แสดงเฉพาะรุ่นที่เปิดอยู่ล่าสุด (ข้อความตัวอย่าง)', 'public' ) ),
);
foreach ( $pages as $slug => $page ) {
	$existing = get_page_by_path( $slug );
	wp_insert_post(
		array(
			'ID'           => $existing ? $existing->ID : 0,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $page[0],
			'post_content' => $page[1],
		)
	);
}

flush_rewrite_rules();
echo "Seeded {$created} courses.\n";
