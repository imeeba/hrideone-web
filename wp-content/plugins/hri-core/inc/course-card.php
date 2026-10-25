<?php
/**
 * Course card and course facts markup. Reused by the filter block, the single template
 * and (later) the home page.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Term names of one taxonomy for a course.
 *
 * @return string[]
 */
function hri_course_term_names( int $post_id, string $taxonomy ): array {
	$terms = get_the_terms( $post_id, $taxonomy );
	return is_array( $terms ) ? wp_list_pluck( $terms, 'name' ) : array();
}

/**
 * Slug of the first term of a taxonomy ('' if none).
 */
function hri_course_first_slug( int $post_id, string $taxonomy ): string {
	$terms = get_the_terms( $post_id, $taxonomy );
	return is_array( $terms ) && $terms ? (string) reset( $terms )->slug : '';
}

/**
 * "2 วัน" style duration.
 */
function hri_format_duration( int $days ): string {
	/* translators: %d: number of days */
	return sprintf( _n( '%d วัน', '%d วัน', $days, 'hri-core' ), $days );
}

/**
 * Format a Y-m-d date for display.
 */
function hri_format_date( string $date ): string {
	return $date ? wp_date( 'j M Y', strtotime( $date . ' 12:00:00' ) ) : '';
}

/**
 * Training dates for display: the free text field when filled (non-consecutive days), otherwise
 * "29–30 Oct 2026" / "11 Nov – 26 Nov 2026" from the start and end dates.
 *
 * @param array<string,mixed> $meta Course meta from hri_course_meta().
 */
function hri_course_dates_label( array $meta ): string {
	if ( ! empty( $meta['hri_schedule_text'] ) ) {
		return (string) $meta['hri_schedule_text'];
	}
	$start = (string) ( $meta['hri_start_date'] ?? '' );
	$end   = (string) ( $meta['hri_end_date'] ?? '' );
	if ( '' === $start ) {
		return '';
	}
	if ( '' === $end || $end === $start ) {
		return hri_format_date( $start );
	}
	$a = strtotime( $start . ' 12:00:00' );
	$b = strtotime( $end . ' 12:00:00' );
	if ( wp_date( 'Y-n', $a ) === wp_date( 'Y-n', $b ) ) {
		return wp_date( 'j', $a ) . '–' . wp_date( 'j M Y', $b );
	}
	return wp_date( 'j M', $a ) . ' – ' . wp_date( 'j M Y', $b );
}

/**
 * "รุ่นที่ 3" ('' when the batch number is empty).
 *
 * @param array<string,mixed> $meta Course meta.
 */
function hri_batch_number_label( array $meta ): string {
	/* translators: %d: batch number */
	return ! empty( $meta['hri_batch_no'] ) ? sprintf( __( 'รุ่นที่ %d', 'hri-core' ), (int) $meta['hri_batch_no'] ) : '';
}

/**
 * Batch line for Public courses: "รุ่นที่ 3 · 29–30 Oct 2026".
 *
 * @param array<string,mixed> $meta Course meta from hri_course_meta().
 */
function hri_batch_label( array $meta ): string {
	return implode( ' · ', array_filter( array( hri_batch_number_label( $meta ), hri_course_dates_label( $meta ) ) ) );
}

/**
 * Price lines: Early Bird (hidden after its deadline), regular price, price note.
 *
 * @param array<string,mixed> $meta Course meta.
 * @return string[]
 */
function hri_course_price_lines( array $meta ): array {
	$early    = (string) ( $meta['hri_price_early'] ?? '' );
	$deadline = (string) ( $meta['hri_early_deadline'] ?? '' );
	$price    = (string) ( $meta['hri_price'] ?? '' );
	$lines    = array();

	if ( $early && ( '' === $deadline || $deadline >= wp_date( 'Y-m-d' ) ) ) {
		/* translators: 1: early bird price, 2: last day to register at that price */
		$lines[] = '' === $deadline ? sprintf( __( 'Early Bird %s', 'hri-core' ), $early ) : sprintf( __( 'Early Bird %1$s (สมัครก่อน %2$s)', 'hri-core' ), $early, hri_format_date( $deadline ) );
		if ( $price ) {
			/* translators: %s: regular price */
			$lines[] = sprintf( __( 'ราคาปกติ %s', 'hri-core' ), $price );
		}
	} elseif ( $price ) {
		$lines[] = $price;
	}
	if ( ! empty( $meta['hri_price_note'] ) ) {
		$lines[] = (string) $meta['hri_price_note'];
	}
	return $lines;
}

/**
 * Where the main button of a course goes: array( url, label, external ).
 * Public + open → the registration link (e.g. Google Form); full / closed → ask about the next batch;
 * no link or In-house → the contact page with this course pre-selected.
 *
 * @return array{0:string,1:string,2:bool}
 */
function hri_course_cta( int $post_id ): array {
	$meta    = hri_course_meta( $post_id );
	$program = hri_course_first_slug( $post_id, 'hri_program' );
	$contact = add_query_arg( 'hri_course', get_post_field( 'post_name', $post_id ), hri_contact_url() ) . '#contact-form';

	if ( ! empty( $meta['hri_external_url'] ) ) {
		return array( (string) $meta['hri_external_url'], __( 'ดูรายละเอียดและสมัคร', 'hri-core' ), true );
	}
	if ( 'public' !== $program ) {
		return array( $contact, __( 'ขอใบเสนอราคาหลักสูตรนี้', 'hri-core' ), false );
	}
	$status = $meta['hri_reg_status'] ?? '';
	if ( in_array( $status, array( 'full', 'closed' ), true ) ) {
		return array( $contact, __( 'สอบถามรอบถัดไป', 'hri-core' ), false );
	}
	if ( ! empty( $meta['hri_register_url'] ) ) {
		return array( (string) $meta['hri_register_url'], __( 'สมัครเลย', 'hri-core' ), true );
	}
	return array( $contact, __( 'สมัคร / สอบถามหลักสูตรนี้', 'hri-core' ), false );
}

/**
 * Card markup for one course.
 */
function hri_course_card( int $post_id ): string {
	$meta    = hri_course_meta( $post_id );
	$pillar  = hri_course_first_slug( $post_id, 'hri_pillar' );
	$program = hri_course_first_slug( $post_id, 'hri_program' );
	$topic   = hri_course_term_names( $post_id, 'hri_topic' );
	$facts   = array();

	if ( $levels = hri_course_term_names( $post_id, 'hri_level' ) ) {
		$facts[] = implode( ' / ', $levels );
	}
	if ( ! empty( $meta['hri_duration_days'] ) ) {
		$facts[] = hri_format_duration( (int) $meta['hri_duration_days'] );
	}
	if ( $formats = hri_course_term_names( $post_id, 'hri_format' ) ) {
		$facts[] = implode( ' / ', $formats );
	}

	$batch  = 'public' === $program ? hri_batch_label( $meta ) : '';
	$status = $meta['hri_reg_status'] ?? '';
	$labels = hri_reg_status_labels();

	$external = (string) ( $meta['hri_external_url'] ?? '' );
	$link     = $external ?: get_permalink( $post_id );

	$media = has_post_thumbnail( $post_id )
		? get_the_post_thumbnail( $post_id, 'hri-card', array( 'loading' => 'lazy', 'alt' => '', 'sizes' => '(min-width: 1280px) 25vw, (min-width: 782px) 33vw, (min-width: 600px) 50vw, 100vw' ) )
		: '';

	ob_start();
	?>
	<article class="hri-card hri-course-card hri-card--<?php echo esc_attr( $pillar ?: 'none' ); ?>">
		<div class="hri-card__media" aria-hidden="true">
			<?php echo $media; // phpcs:ignore WordPress.Security.EscapeOutput -- core markup. ?>
			<?php if ( $program ) : ?>
				<span class="hri-card__badge"><?php echo esc_html( implode( ' / ', hri_course_term_names( $post_id, 'hri_program' ) ) ); ?></span>
			<?php endif; ?>
		</div>
		<div class="hri-card__body">
			<?php if ( $topic ) : ?>
				<p class="hri-card__eyebrow"><?php echo esc_html( implode( ' · ', $topic ) ); ?></p>
			<?php endif; ?>
			<h3 class="hri-card__title"><a href="<?php echo esc_url( $link ); ?>"<?php echo $external ? ' target="_blank" rel="noopener"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed string. ?>><?php echo esc_html( get_the_title( $post_id ) ); ?><?php if ( $external ) : ?><span class="screen-reader-text"> <?php esc_html_e( '(เปิดในแท็บใหม่)', 'hri-core' ); ?></span><?php endif; ?></a></h3>
			<?php if ( has_excerpt( $post_id ) ) : ?>
				<p class="hri-card__excerpt"><?php echo esc_html( get_the_excerpt( $post_id ) ); ?></p>
			<?php endif; ?>
			<?php if ( $batch || ( $status && isset( $labels[ $status ] ) ) ) : ?>
				<p class="hri-card__batch">
					<?php echo esc_html( $batch ); ?>
					<?php if ( $status && isset( $labels[ $status ] ) ) : ?>
						<span class="hri-chip hri-chip--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $labels[ $status ] ); ?></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ( $facts ) : ?>
				<p class="hri-card__meta"><?php echo esc_html( implode( ' · ', $facts ) ); ?></p>
			<?php endif; ?>
		</div>
	</article>
	<?php
	return (string) ob_get_clean();
}

/**
 * Definition list of course facts for the single template.
 */
function hri_course_facts( int $post_id ): string {
	$meta    = hri_course_meta( $post_id );
	$program = hri_course_first_slug( $post_id, 'hri_program' );
	$rows    = array();

	$add = static function ( string $label, string $value ) use ( &$rows ): void {
		if ( '' !== $value ) {
			$rows[ $label ] = $value;
		}
	};

	$add( __( 'ประเภท', 'hri-core' ), implode( ' / ', hri_course_term_names( $post_id, 'hri_program' ) ) );
	$add( __( 'หมวด', 'hri-core' ), implode( ' / ', hri_course_term_names( $post_id, 'hri_pillar' ) ) );
	$add( __( 'หัวข้อ', 'hri-core' ), implode( ', ', hri_course_term_names( $post_id, 'hri_topic' ) ) );
	$add( __( 'ระดับผู้เรียน', 'hri-core' ), implode( ' / ', hri_course_term_names( $post_id, 'hri_level' ) ) );
	$add( __( 'กลุ่มเป้าหมาย', 'hri-core' ), (string) ( $meta['hri_audience'] ?? '' ) );
	$add( __( 'ระยะเวลา', 'hri-core' ), ! empty( $meta['hri_duration_days'] ) ? hri_format_duration( (int) $meta['hri_duration_days'] ) : '' );
	$add( __( 'รูปแบบ', 'hri-core' ), implode( ' / ', hri_course_term_names( $post_id, 'hri_format' ) ) );

	if ( 'public' === $program ) {
		$add( __( 'รุ่นที่', 'hri-core' ), hri_batch_number_label( $meta ) );
		$add( __( 'วันที่อบรม', 'hri-core' ), hri_course_dates_label( $meta ) );
		$add( __( 'เวลา', 'hri-core' ), (string) ( $meta['hri_time_text'] ?? '' ) );
		$add( __( 'สถานที่', 'hri-core' ), (string) ( $meta['hri_location'] ?? '' ) );
		$price = hri_course_price_lines( $meta );
		if ( $price ) {
			$rows[ __( 'ค่าลงทะเบียน', 'hri-core' ) ] = $price;
		}
		$add( __( 'ใบประกาศนียบัตร', 'hri-core' ), ! empty( $meta['hri_certificate'] ) ? __( 'มี', 'hri-core' ) : '' );
		$status = $meta['hri_reg_status'] ?? '';
		$add( __( 'สถานะ', 'hri-core' ), $status ? ( hri_reg_status_labels()[ $status ] ?? '' ) : '' );
	} else {
		$add( __( 'ใบประกาศนียบัตร', 'hri-core' ), ! empty( $meta['hri_certificate'] ) ? __( 'มี', 'hri-core' ) : '' );
	}

	if ( ! $rows ) {
		return '';
	}

	$html = '<dl class="hri-facts">';
	foreach ( $rows as $label => $value ) {
		$html .= '<div class="hri-facts__row"><dt>' . esc_html( $label ) . '</dt><dd>' . implode( '<br>', array_map( 'esc_html', (array) $value ) ) . '</dd></div>';
	}
	return $html . '</dl>';
}

/**
 * Course IDs for the Course Highlight block (home page).
 *
 * @param string $program "public" (open for registration, soonest first) or "inhouse".
 * @param int    $count   1–12.
 * @return int[]
 */
function hri_course_highlight_ids( string $program, int $count ): array {
	$program = 'inhouse' === $program ? 'inhouse' : 'public';
	$count   = max( 1, min( 12, $count ) );
	$ids     = get_posts(
		array(
			'post_type'        => 'course',
			'post_status'      => 'publish',
			'numberposts'      => 'public' === $program ? -1 : $count,
			'fields'           => 'ids',
			'orderby'          => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'no_found_rows'    => true,
			'suppress_filters' => false, // Lets Polylang limit the list to the current language.
			'tax_query'        => array( array( 'taxonomy' => 'hri_program', 'field' => 'slug', 'terms' => $program ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_query'       => 'public' === $program ? array( array( 'key' => 'hri_reg_status', 'value' => 'open' ) ) : array(), // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	$ids = array_map( 'intval', $ids );

	if ( 'public' === $program ) {
		// Soonest start date first; courses without a date keep their order, after the dated ones.
		$dates = array();
		foreach ( $ids as $position => $id ) {
			$date          = (string) get_post_meta( $id, 'hri_start_date', true );
			$dates[ $id ] = array( '' === $date ? '9999-12-31' : $date, $position );
		}
		usort( $ids, static fn( $a, $b ) => $dates[ $a ] <=> $dates[ $b ] );
		$ids = array_slice( $ids, 0, $count );
	}
	return $ids;
}

/**
 * Buttons of a course (main + optional enquiry) as core button markup.
 *
 * @param string $variant   default | light (on a dark background).
 * @param bool   $secondary Add the enquiry button next to a registration link.
 */
function hri_course_buttons( int $post_id, string $variant = 'default', bool $secondary = true, bool $center = false ): string {
	list( $url, $label, $external ) = hri_course_cta( $post_id );
	$light   = 'light' === $variant;

	// This markup skips the block parser, so core would not load the Button styles
	// (outline style, inline-block link) on pages without another Button block.
	wp_enqueue_style( 'wp-block-buttons' );
	wp_enqueue_style( 'wp-block-button' );
	$contact = add_query_arg( 'hri_course', get_post_field( 'post_name', $post_id ), hri_contact_url() ) . '#contact-form';

	$button = static function ( string $href, string $text, string $style, bool $blank ): string {
		return sprintf(
			'<div class="wp-block-button%1$s"><a class="wp-block-button__link wp-element-button" href="%2$s"%3$s>%4$s%5$s</a></div>',
			$style ? ' is-style-' . esc_attr( $style ) : '',
			esc_url( $href ),
			$blank ? ' target="_blank" rel="noopener"' : '',
			esc_html( $text ),
			$blank ? '<span class="screen-reader-text"> ' . esc_html__( '(เปิดในแท็บใหม่)', 'hri-core' ) . '</span>' : ''
		);
	};

	$html = $button( $url, $label, $light ? 'light' : '', $external );
	if ( $secondary && $url !== $contact && ! str_contains( $url, 'hri_course=' ) ) {
		$html .= $button( $contact, __( 'สอบถามหลักสูตรนี้', 'hri-core' ), $light ? 'outline-light' : 'outline', false );
	}
	return '<div class="wp-block-buttons hri-register is-layout-flex wp-block-buttons-is-layout-flex' . ( $center ? ' is-content-justification-center' : '' ) . '">' . $html . '</div>';
}

/**
 * Key facts of a course as short lines for the course hero.
 *
 * @return string[]
 */
function hri_course_hero_chips( int $post_id ): array {
	$meta    = hri_course_meta( $post_id );
	$program = hri_course_first_slug( $post_id, 'hri_program' );
	$chips   = array();

	if ( 'public' === $program ) {
		$labels = hri_reg_status_labels();
		$status = $meta['hri_reg_status'] ?? '';
		$chips[] = implode( ' · ', array_filter( array( hri_batch_number_label( $meta ), $status ? ( $labels[ $status ] ?? '' ) : '' ) ) );
		$chips[] = hri_course_dates_label( $meta );
		$chips[] = (string) ( $meta['hri_location'] ?? '' );
		$price   = hri_course_price_lines( $meta );
		$chips[] = $price[0] ?? '';
		if ( ! empty( $meta['hri_certificate'] ) ) {
			$chips[] = __( 'มีใบประกาศนียบัตร', 'hri-core' );
		}
	} else {
		$chips[] = ! empty( $meta['hri_duration_days'] ) ? hri_format_duration( (int) $meta['hri_duration_days'] ) : '';
		$chips[] = implode( ' / ', hri_course_term_names( $post_id, 'hri_format' ) );
		$chips[] = implode( ' / ', hri_course_term_names( $post_id, 'hri_level' ) );
	}
	return array_values( array_filter( $chips ) );
}
