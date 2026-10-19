<?php
/**
 * Course page body: the sections that go in a course's content (Public and In-house).
 *
 * hri_course_page_markup() prints ordinary core block markup, like the other section helpers, so a
 * course page stays editable in the block editor. Section titles are gettext strings of the theme;
 * the course's own texts come in as an array already written in the page's language. The hero
 * (title, excerpt, key facts, buttons) and the price / registration block at the bottom come from the
 * single-course template and the course details, not from this content.
 *
 * @package hri-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pull quote: English line and its local-language line, on the light brand surface.
 */
function hri_bm_quote( string $line, string $local = '' ): string {
	return hri_bm_section( 'hri-quote-section', 'surface-brand', '', '', '70', '70' )
		. hri_bm_group( array( 'className' => 'hri-quote' ) )
		. hri_bm_p( '“' . esc_html( $line ) . '”', array( 'align' => 'center', 'className' => 'hri-quote__line', 'fontSize' => 'xx-large' ) )
		. ( $local ? hri_bm_p( esc_html( $local ), array( 'align' => 'center', 'className' => 'hri-quote__local', 'fontSize' => 'large' ) ) : '' )
		. hri_bm_group_end()
		. hri_bm_section_end();
}

/**
 * Testimonial cards.
 *
 * @param array $reviews Each: array( name, role, text ).
 */
function hri_bm_reviews( array $reviews ): string {
	$out = hri_bm_group(
		array(
			'className' => 'hri-cards hri-cards--3 hri-reviews',
			'layout'    => array( 'type' => 'grid', 'columnCount' => min( 3, count( $reviews ) ), 'minimumColumnWidth' => '16rem' ),
		)
	);
	foreach ( $reviews as $review ) {
		$out .= hri_bm_group( array( 'className' => 'is-style-card hri-card hri-review' ) )
			. hri_bm_p( '“' . esc_html( $review[2] ) . '”', array( 'className' => 'hri-review__text' ) )
			. hri_bm_p( '<strong>' . esc_html( $review[0] ) . '</strong>', array( 'className' => 'hri-review__name' ) )
			. hri_bm_p( esc_html( $review[1] ), array( 'className' => 'hri-review__role' ) )
			. hri_bm_group_end();
	}
	return $out . hri_bm_group_end();
}

/**
 * Photo slider (Gallery, style "Slider") of $count placeholder photos.
 */
function hri_bm_gallery( int $count, string $alt = '' ): string {
	$out = '<!-- wp:gallery {"columns":1,"linkTo":"none","align":"wide","className":"is-style-slider"} -->' . "\n"
		. '<figure class="wp-block-gallery alignwide has-nested-images columns-1 is-cropped is-style-slider">' . "\n";
	for ( $i = 0; $i < $count; $i++ ) {
		$out .= hri_bm_photo( $alt );
	}
	return $out . "</figure>\n<!-- /wp:gallery -->\n";
}

/**
 * Closing call-to-action band whose button is the course's own register / enquiry button.
 */
function hri_bm_course_ctaband( string $title, string $text ): string {
	return hri_bm_section( 'hri-pagecta', 'primary-deep', 'register', 'base', '70', '70' )
		. hri_bm_heading( $title, array( 'textAlign' => 'center', 'className' => 'hri-pagecta__title', 'textColor' => 'base' ) )
		. ( $text ? hri_bm_p( esc_html( $text ), array( 'align' => 'center', 'className' => 'hri-pagecta__text', 'fontSize' => 'large' ) ) : '' )
		. '<!-- wp:hri/course-register {"variant":"light","center":true} /-->' . "\n"
		. hri_bm_section_end();
}

/**
 * Output of a section pattern file (patterns/{slug}.php) as a string.
 */
function hri_bm_pattern( string $slug ): string {
	ob_start();
	include get_template_directory() . "/patterns/{$slug}.php";
	return (string) ob_get_clean();
}

/**
 * The sections of a course page.
 *
 * @param array  $d       Texts of the course (keys below, every one optional).
 *                        intro      array( title, text )
 *                        problems   array( array( title, text, help ), … )
 *                        outcomes   array( array( title, text ), … )   outcomes_lead  string
 *                        audience   string[]
 *                        quote      array( line, local )
 *                        program    array( title, lead, groups => array( array( kicker, title, list[] ), … ) )
 *                        trainers   bool    gallery   int    reviews   array( array( name, role, text ), … )
 *                        cta        array( title, text )
 *                        faq        array( array( question, answer ), … )
 * @param string $program public | inhouse (In-house adds the "Why HRI DeOne" cards and the standard FAQ).
 */
function hri_course_page_markup( array $d, string $program = 'public' ): string {
	$out = '';

	if ( ! empty( $d['intro'] ) ) {
		$out .= hri_bm_section( 'hri-intro-section', '', 'overview', '', '70', '60' )
			. hri_bm_head( __( 'ภาพรวมหลักสูตร', 'hri-theme' ), $d['intro'][0], $d['intro'][1] ?? '', '', true )
			. hri_bm_section_end();
	}

	if ( ! empty( $d['problems'] ) ) {
		$out .= hri_bm_section( 'hri-problems-section', 'base-alt', 'problems', '', '70', '70' )
			. hri_bm_head( 'Pain Points', __( 'คุณกำลังเจอปัญหาเหล่านี้อยู่ไหม?', 'hri-theme' ), '', '', true )
			. hri_bm_cards(
				array_map(
					static fn( $p ) => isset( $p['title'] ) ? $p : array( 'title' => $p[0], 'text' => $p[1] ?? '', 'help' => $p[2] ?? '' ),
					$d['problems']
				),
				2
			)
			. hri_bm_section_end();
	}

	if ( ! empty( $d['outcomes'] ) ) {
		$cards = array();
		foreach ( $d['outcomes'] as $i => $o ) {
			$cards[] = array( 'num' => sprintf( '%02d', $i + 1 ), 'title' => $o[0], 'text' => $o[1] ?? '' );
		}
		$columns = 4 === count( $cards ) ? 4 : min( 3, count( $cards ) );
		$out    .= hri_bm_section( 'hri-outcomes-section', '', 'outcomes', '', '70', '70' )
			. hri_bm_head( 'Business Outcomes', __( 'สิ่งที่จะเปลี่ยนไปหลังเรียนหลักสูตรนี้', 'hri-theme' ), $d['outcomes_lead'] ?? '', '', true )
			. hri_bm_cards( $cards, $columns, 'hri-outcomes' )
			. hri_bm_section_end();
	}

	if ( ! empty( $d['audience'] ) ) {
		$out .= hri_bm_section( 'hri-audience-section', 'base-alt', 'audience', '', '70', '70' )
			. hri_bm_head( 'Who Should Attend', __( 'หลักสูตรนี้เหมาะกับใคร', 'hri-theme' ), '', '', true )
			. hri_bm_group( array( 'className' => 'hri-audience' ) )
			. hri_bm_list( array_map( 'esc_html', $d['audience'] ), 'hri-checklist' )
			. hri_bm_group_end()
			. hri_bm_section_end();
	}

	if ( ! empty( $d['quote'] ) ) {
		$out .= hri_bm_quote( $d['quote'][0], $d['quote'][1] ?? '' );
	}

	if ( ! empty( $d['program'] ) ) {
		$groups = array();
		foreach ( $d['program']['groups'] as $g ) {
			$groups[] = array( 'kicker' => $g[0], 'title' => $g[1], 'list' => $g[2] ?? array(), 'text' => $g[3] ?? '' );
		}
		$out .= hri_bm_section( 'hri-program-section', '', 'program', '', '70', '70' )
			. hri_bm_head( 'Program', $d['program']['title'], $d['program']['lead'] ?? '', '', true )
			. hri_bm_cards( $groups, min( 3, max( 2, count( $groups ) ) ), 'hri-program' )
			. hri_bm_section_end();
	}

	if ( 'inhouse' === $program ) {
		$out .= hri_bm_pattern( 'inhouse-why' );
	}

	if ( ! empty( $d['trainers'] ) ) {
		$out .= hri_bm_section( 'hri-trainers-section', 'base-alt', 'trainers', '', '70', '70' )
			. '<!-- wp:hri/course-trainers /-->' . "\n"
			. hri_bm_section_end();
	}

	if ( ! empty( $d['gallery'] ) ) {
		$out .= hri_bm_section( 'hri-gallery-section', '', 'highlights', '', '70', '70' )
			. hri_bm_head( 'Course Highlights', __( 'บรรยากาศการเรียนรู้', 'hri-theme' ), '', '', true )
			. hri_bm_gallery( (int) $d['gallery'], __( 'บรรยากาศการอบรมรุ่นก่อน', 'hri-theme' ) )
			. hri_bm_section_end();
	}

	if ( ! empty( $d['reviews'] ) ) {
		$out .= hri_bm_section( 'hri-reviews-section', 'base-alt', 'reviews', '', '70', '70' )
			. hri_bm_head( 'Real Voices, Real Results', __( 'เสียงจากผู้เรียน', 'hri-theme' ), '', '', true )
			. hri_bm_reviews( $d['reviews'] )
			. hri_bm_section_end();
	}

	if ( ! empty( $d['cta'] ) ) {
		$out .= hri_bm_course_ctaband( $d['cta'][0], $d['cta'][1] ?? '' );
	}

	if ( 'inhouse' === $program && empty( $d['faq'] ) ) {
		$out .= hri_bm_pattern( 'inhouse-faq' );
	} elseif ( ! empty( $d['faq'] ) ) {
		$out .= hri_bm_section( 'hri-faq-section', '', 'faq', '', '70', '70' )
			. hri_bm_head( __( 'คำถามที่พบบ่อย', 'hri-theme' ), 'FAQ' )
			. hri_bm_group( array( 'className' => 'hri-faq' ) );
		foreach ( $d['faq'] as $item ) {
			$out .= hri_bm_details( $item[0], array( esc_html( $item[1] ) ), 'hri-faq__item' );
		}
		$out .= hri_bm_group_end() . hri_bm_section_end();
	}

	return $out;
}

/**
 * Placeholder texts for the "course page" patterns (a new course starts from these).
 *
 * @return array<string,mixed>
 */
function hri_course_page_sample( string $program ): array {
	$problems = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: item number */
		$problems[] = array( sprintf( __( 'ปัญหาข้อที่ %d (ชื่อสั้น ๆ)', 'hri-theme' ), $i ), __( 'อธิบายปัญหาที่ผู้เรียนเจอในงานจริง 1–2 ประโยค', 'hri-theme' ), __( 'ผู้เรียนจะได้เครื่องมือหรือแนวคิดอะไรไปแก้ปัญหานี้', 'hri-theme' ) );
	}
	$outcomes = array();
	for ( $i = 1; $i <= ( 'public' === $program ? 4 : 5 ); $i++ ) {
		/* translators: %d: item number */
		$outcomes[] = array( sprintf( __( 'ผลลัพธ์ข้อที่ %d', 'hri-theme' ), $i ), __( 'สิ่งที่เปลี่ยนไปในการทำงานหลังเรียนจบ (วัดได้หรือเห็นได้ชัด)', 'hri-theme' ) );
	}
	$d = array(
		'intro'    => array( __( 'ประโยคเปิดที่ตั้งคำถามหรือมุมมองใหม่ของหลักสูตรนี้', 'hri-theme' ), __( 'ย่อหน้าสั้น ๆ ที่บอกว่าหลักสูตรนี้เหมาะกับใคร แก้ปัญหาอะไร และผู้เรียนจะได้อะไร', 'hri-theme' ) ),
		'problems' => $problems,
		'outcomes' => $outcomes,
		'cta'      => array( __( 'พร้อมเริ่มแล้วหรือยัง', 'hri-theme' ), __( 'ข้อความปิดท้ายที่ชวนให้ตัดสินใจ', 'hri-theme' ) ),
	);
	if ( 'public' === $program ) {
		$d['quote']    = array( 'One sentence that sums up the course.', __( 'คำแปลหรือประโยคสรุปภาษาไทย', 'hri-theme' ) );
		$d['program']  = array(
			'title'  => __( 'หลักสูตรรายวัน', 'hri-theme' ),
			'lead'   => '',
			'groups' => array(
				array( 'Day 1', __( 'หัวข้อวันที่ 1', 'hri-theme' ), array( __( 'หัวข้อย่อย 1', 'hri-theme' ), __( 'หัวข้อย่อย 2', 'hri-theme' ), __( 'หัวข้อย่อย 3', 'hri-theme' ) ) ),
				array( 'Day 2', __( 'หัวข้อวันที่ 2', 'hri-theme' ), array( __( 'หัวข้อย่อย 1', 'hri-theme' ), __( 'หัวข้อย่อย 2', 'hri-theme' ), __( 'หัวข้อย่อย 3', 'hri-theme' ) ) ),
			),
		);
		$d['trainers'] = true;
		$d['gallery']  = 4;
		$d['faq']      = array(
			array( __( 'หลักสูตรนี้เหมาะกับใคร?', 'hri-theme' ), __( 'ตอบกลุ่มเป้าหมายและพื้นฐานที่ควรมี', 'hri-theme' ) ),
			array( __( 'มีใบประกาศนียบัตรไหม?', 'hri-theme' ), __( 'ตอบเงื่อนไขการรับใบประกาศนียบัตร', 'hri-theme' ) ),
		);
	}
	return $d;
}
