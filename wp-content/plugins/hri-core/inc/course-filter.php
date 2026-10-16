<?php
/**
 * Course filter: server-rendered results from URL parameters.
 *
 * Works without JavaScript (plain GET form). When JavaScript is available the block's
 * view.js swaps only the results area, so filtering is instant and URLs stay shareable.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

const HRI_COURSES_PER_PAGE = 12;

/**
 * Filter groups, in display order. `taxonomy` groups read terms; `duration` is a meta range.
 *
 * @return array<string,array{label:string,taxonomy?:string,options?:array<string,string>}>
 */
function hri_filter_groups(): array {
	return array(
		'program'  => array( 'label' => __( 'ประเภท', 'hri-core' ), 'taxonomy' => 'hri_program' ),
		'pillar'   => array( 'label' => __( 'หมวด', 'hri-core' ), 'taxonomy' => 'hri_pillar' ),
		'topic'    => array( 'label' => __( 'หัวข้อ', 'hri-core' ), 'taxonomy' => 'hri_topic' ),
		'level'    => array( 'label' => __( 'ระดับผู้เรียน', 'hri-core' ), 'taxonomy' => 'hri_level' ),
		'format'   => array( 'label' => __( 'รูปแบบ', 'hri-core' ), 'taxonomy' => 'hri_format' ),
		'duration' => array(
			'label'   => __( 'ระยะเวลา', 'hri-core' ),
			'options' => array(
				'1' => __( '1 วัน', 'hri-core' ),
				'2' => __( '2 วัน', 'hri-core' ),
				'3' => __( '3 วันขึ้นไป', 'hri-core' ),
			),
		),
	);
}

/**
 * Read and sanitize filter parameters from the request.
 *
 * @param string $lock Program slug the list is locked to ('' = all programs).
 * @return array{groups:array<string,string[]>,q:string,pg:int,lock:string}
 */
function hri_filter_params( string $lock = '' ): array {
	$groups = array();
	foreach ( array_keys( hri_filter_groups() ) as $key ) {
		$raw = isset( $_GET[ $key ] ) ? (array) wp_unslash( $_GET[ $key ] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
		$raw = array_filter( $raw, 'is_string' );

		$groups[ $key ] = array_values( array_unique( array_filter( array_map( 'sanitize_title', $raw ) ) ) );
	}

	$q = isset( $_GET['q'] ) && is_string( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$pg = isset( $_GET['pg'] ) && is_scalar( $_GET['pg'] ) ? max( 1, absint( $_GET['pg'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification

	// A locked list ignores the program filter: it is fixed by the block.
	$lock = sanitize_title( $lock );
	if ( '' !== $lock ) {
		$groups['program'] = array();
	}

	return array( 'groups' => $groups, 'q' => $q, 'pg' => $pg, 'lock' => $lock );
}

/**
 * Build the WP_Query for the current parameters.
 *
 * @param array{groups:array<string,string[]>,q:string,pg:int,lock?:string} $params
 */
function hri_filter_query( array $params ): WP_Query {
	$args = array(
		'post_type'      => 'course',
		'post_status'    => 'publish',
		'posts_per_page' => HRI_COURSES_PER_PAGE,
		'paged'          => $params['pg'],
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
	);

	$tax_query = array();
	if ( '' !== ( $params['lock'] ?? '' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'hri_program',
			'field'    => 'slug',
			'terms'    => array( $params['lock'] ),
		);
	}
	foreach ( hri_filter_groups() as $key => $group ) {
		if ( empty( $group['taxonomy'] ) || empty( $params['groups'][ $key ] ) ) {
			continue;
		}
		$tax_query[] = array(
			'taxonomy' => $group['taxonomy'],
			'field'    => 'slug',
			'terms'    => $params['groups'][ $key ],
			'operator' => 'IN',
		);
	}
	if ( $tax_query ) {
		$args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}

	$meta_query = array();
	foreach ( $params['groups']['duration'] ?? array() as $bucket ) {
		if ( ! isset( hri_filter_groups()['duration']['options'][ $bucket ] ) ) {
			continue;
		}
		$meta_query[] = array(
			'key'     => 'hri_duration_days',
			'value'   => (int) $bucket,
			'compare' => '3' === $bucket ? '>=' : '=',
			'type'    => 'NUMERIC',
		);
	}
	if ( $meta_query ) {
		$args['meta_query'] = array_merge( array( 'relation' => 'OR' ), $meta_query ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}

	if ( '' !== $params['q'] ) {
		$args['s'] = $params['q'];
	}

	return new WP_Query( $args );
}

/**
 * Checkbox options for a group: [ value => [label, count] ].
 *
 * @param int[]|null $object_ids When set, only count (and list) terms used by these courses.
 * @return array<string,array{0:string,1:int|null}>
 */
function hri_filter_options( array $group, ?array $object_ids = null ): array {
	if ( ! empty( $group['options'] ) ) {
		return hri_duration_options( $group['options'], $object_ids );
	}

	if ( null !== $object_ids && ! $object_ids ) {
		return array();
	}

	$args = array(
		'taxonomy'   => $group['taxonomy'],
		'hide_empty' => true,
		'orderby'    => 'term_id',
	);
	if ( null !== $object_ids ) {
		$args['object_ids'] = $object_ids;
		$args['fields']     = 'all_with_object_id';
	}

	$terms = get_terms( $args );
	if ( is_wp_error( $terms ) ) {
		return array();
	}

	// With object_ids, get_terms returns one row per (term, course): count the rows.
	$out = array();
	foreach ( $terms as $term ) {
		if ( null === $object_ids ) {
			$out[ $term->slug ] = array( $term->name, (int) $term->count );
		} else {
			$out[ $term->slug ] = array( $term->name, ( $out[ $term->slug ][1] ?? 0 ) + 1 );
		}
	}
	return $out;
}

/**
 * Duration buckets with course counts; buckets with no courses are left out.
 *
 * @param array<string,string> $buckets    value => label ("3" means 3 days or more).
 * @param int[]|null           $object_ids Limit counting to these courses.
 * @return array<string,array{0:string,1:int}>
 */
function hri_duration_options( array $buckets, ?array $object_ids = null ): array {
	$args = array(
		'post_type'        => 'course',
		'post_status'      => 'publish',
		'numberposts'      => -1,
		'fields'           => 'ids',
		'no_found_rows'    => true,
		'suppress_filters' => false,
	);
	if ( null !== $object_ids ) {
		if ( ! $object_ids ) {
			return array();
		}
		$args['post__in'] = $object_ids;
	}

	$counts = array_fill_keys( array_keys( $buckets ), 0 );
	foreach ( get_posts( $args ) as $id ) {
		$days = (int) get_post_meta( $id, 'hri_duration_days', true );
		if ( $days < 1 ) {
			continue;
		}
		$key = (string) min( $days, 3 );
		if ( isset( $counts[ $key ] ) ) {
			++$counts[ $key ];
		}
	}

	$out = array();
	foreach ( $buckets as $value => $label ) {
		if ( $counts[ $value ] > 0 ) {
			$out[ $value ] = array( $label, $counts[ $value ] );
		}
	}
	return $out;
}

/**
 * Whether any filter or search is active.
 *
 * @param array{groups:array<string,string[]>,q:string,pg:int,lock?:string} $params
 */
function hri_filter_is_active( array $params ): bool {
	return '' !== $params['q'] || (bool) array_filter( $params['groups'] );
}

/**
 * Base URL the filter form submits to.
 */
function hri_filter_action_url(): string {
	if ( is_post_type_archive( 'course' ) ) {
		return (string) get_post_type_archive_link( 'course' );
	}
	$permalink = is_singular() ? get_permalink() : '';
	return $permalink ? $permalink : home_url( '/' );
}

/**
 * Results area (count, grid, pagination). This is what view.js swaps.
 *
 * @param array{groups:array<string,string[]>,q:string,pg:int,lock?:string} $params
 */
function hri_render_course_results( WP_Query $query, array $params, string $action ): string {
	ob_start();
	echo '<div class="hri-results" data-hri-results aria-live="polite">';

	echo '<p class="hri-results__head"><span class="hri-results__count">';
	/* translators: %d: number of courses */
	echo esc_html( sprintf( __( 'พบ %d หลักสูตร', 'hri-core' ), $query->found_posts ) );
	echo '</span>';
	if ( hri_filter_is_active( $params ) ) {
		printf( ' <a class="hri-results__reset" href="%s" data-hri-reset>%s</a>', esc_url( $action ), esc_html__( 'ล้างตัวกรอง', 'hri-core' ) );
	}
	echo '</p>';

	if ( $query->have_posts() ) {
		echo '<div class="hri-grid">';
		foreach ( $query->posts as $post ) {
			echo hri_course_card( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
		}
		echo '</div>';

		$add_args = array_filter( $params['groups'] );
		if ( '' !== $params['q'] ) {
			$add_args['q'] = $params['q'];
		}
		$links = paginate_links(
			array(
				'base'      => esc_url_raw( $action ) . '%_%',
				'format'    => '?pg=%#%',
				'current'   => $params['pg'],
				'total'     => $query->max_num_pages,
				'add_args'  => $add_args,
				'type'      => 'list',
				'prev_text' => '‹',
				'next_text' => '›',
			)
		);
		if ( $links ) {
			echo '<nav class="hri-pagination" aria-label="' . esc_attr__( 'หน้า', 'hri-core' ) . '">' . $links . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	} else {
		echo '<p class="hri-results__empty">' . esc_html__( 'ไม่พบหลักสูตรที่ตรงกับตัวกรอง ลองลดเงื่อนไขหรือล้างตัวกรอง', 'hri-core' ) . '</p>';
	}

	echo '</div>';
	return (string) ob_get_clean();
}

/**
 * Course IDs of one program (for scoped filter counts).
 *
 * @return int[]
 */
function hri_program_course_ids( string $program ): array {
	return get_posts(
		array(
			'post_type'        => 'course',
			'post_status'      => 'publish',
			'numberposts'      => -1,
			'fields'           => 'ids',
			'no_found_rows'    => true,
			'suppress_filters' => false,
			'tax_query'        => array( array( 'taxonomy' => 'hri_program', 'field' => 'slug', 'terms' => $program ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
}

/**
 * Full block output: filter form + results.
 *
 * @param array{program?:string} $args `program` locks the list to one program (e.g. "inhouse").
 */
function hri_render_course_filter( array $args = array() ): string {
	$params = hri_filter_params( (string) ( $args['program'] ?? '' ) );
	$scope  = '' !== $params['lock'] ? array_map( 'intval', hri_program_course_ids( $params['lock'] ) ) : null;
	$action = hri_filter_action_url();
	$query  = hri_filter_query( $params );

	// A page number past the end (stale link) shows page 1 instead of "0 courses".
	if ( $params['pg'] > 1 && ! $query->have_posts() ) {
		$params['pg'] = 1;
		$query        = hri_filter_query( $params );
	}

	ob_start();
	?>
	<div class="hri-courses" data-hri-courses>
		<form class="hri-filter" method="get" action="<?php echo esc_url( $action ); ?>" data-hri-filter role="search" aria-label="<?php esc_attr_e( 'ค้นหาและกรองหลักสูตร', 'hri-core' ); ?>">
			<div class="hri-filter__search">
				<label class="screen-reader-text" for="hri-q"><?php esc_html_e( 'ค้นหาหลักสูตร', 'hri-core' ); ?></label>
				<input id="hri-q" type="search" name="q" value="<?php echo esc_attr( $params['q'] ); ?>" placeholder="<?php esc_attr_e( 'ค้นหาชื่อหลักสูตร', 'hri-core' ); ?>" autocomplete="off">
			</div>

			<details class="hri-filter__panel" open>
				<summary><?php esc_html_e( 'ตัวกรอง', 'hri-core' ); ?></summary>
				<?php foreach ( hri_filter_groups() as $key => $group ) : ?>
					<?php
					if ( 'program' === $key && null !== $scope ) {
						continue;
					}
					$options = hri_filter_options( $group, $scope );
					?>
					<?php if ( ! $options ) { continue; } ?>
					<fieldset class="hri-filter__group">
						<legend><?php echo esc_html( $group['label'] ); ?></legend>
						<?php foreach ( $options as $value => $option ) : ?>
							<label class="hri-check">
								<input type="checkbox" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( (string) $value ); ?>" <?php checked( in_array( (string) $value, $params['groups'][ $key ], true ) ); ?>>
								<span class="hri-check__label"><?php echo esc_html( $option[0] ); ?></span>
								<?php if ( null !== $option[1] ) : ?>
									<span class="hri-check__count"><?php echo (int) $option[1]; ?></span>
								<?php endif; ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				<?php endforeach; ?>
				<button type="submit" class="hri-filter__submit wp-element-button"><?php esc_html_e( 'ค้นหา', 'hri-core' ); ?></button>
			</details>
		</form>

		<?php echo hri_render_course_results( $query, $params, $action ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
	</div>
	<?php
	wp_reset_postdata();
	return (string) ob_get_clean();
}
