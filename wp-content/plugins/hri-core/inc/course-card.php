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
 * Batch line for Public courses: "รุ่นที่ 3 · 12 Jan 2027".
 *
 * @param array<string,mixed> $meta Course meta from hri_course_meta().
 */
function hri_batch_label( array $meta ): string {
	$parts = array();
	if ( ! empty( $meta['hri_batch_no'] ) ) {
		/* translators: %d: batch number */
		$parts[] = sprintf( __( 'รุ่นที่ %d', 'hri-core' ), (int) $meta['hri_batch_no'] );
	}
	if ( ! empty( $meta['hri_start_date'] ) ) {
		$parts[] = hri_format_date( (string) $meta['hri_start_date'] );
	}
	return implode( ' · ', $parts );
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

	$media = has_post_thumbnail( $post_id )
		? get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'lazy', 'alt' => '' ) )
		: '';

	ob_start();
	?>
	<article class="hri-card hri-card--<?php echo esc_attr( $pillar ?: 'none' ); ?>">
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
			<h3 class="hri-card__title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
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
		$add( __( 'รุ่นที่เปิด', 'hri-core' ), hri_batch_label( $meta ) );
		$end = ! empty( $meta['hri_end_date'] ) ? hri_format_date( (string) $meta['hri_end_date'] ) : '';
		$add( __( 'วันสิ้นสุด', 'hri-core' ), $end );
		$add( __( 'สถานที่', 'hri-core' ), (string) ( $meta['hri_location'] ?? '' ) );
		$add( __( 'ราคา', 'hri-core' ), (string) ( $meta['hri_price'] ?? '' ) );
		$status = $meta['hri_reg_status'] ?? '';
		$add( __( 'สถานะ', 'hri-core' ), $status ? ( hri_reg_status_labels()[ $status ] ?? '' ) : '' );
	}

	if ( ! $rows ) {
		return '';
	}

	$html = '<dl class="hri-facts">';
	foreach ( $rows as $label => $value ) {
		$html .= '<div class="hri-facts__row"><dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $value ) . '</dd></div>';
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
