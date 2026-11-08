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
 * Filter groups, in display order. each group reads the terms of one taxonomy.
 *
 * @return array<string,array{label:string,taxonomy:string}>
 */
function hri_filter_groups(): array {
	return array(
		'program'  => array( 'label' => __( 'ประเภท', 'hri-core' ), 'taxonomy' => 'hri_program' ),
		'pillar'   => array( 'label' => __( 'หมวด', 'hri-core' ), 'taxonomy' => 'hri_pillar' ),
		'topic'    => array( 'label' => __( 'หัวข้อ', 'hri-core' ), 'taxonomy' => 'hri_topic' ),
		'level'    => array( 'label' => __( 'ระดับผู้เรียน', 'hri-core' ), 'taxonomy' => 'hri_level' ),
		'format'   => array( 'label' => __( 'รูปแบบ', 'hri-core' ), 'taxonomy' => 'hri_format' ),
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

	if ( '' !== $params['q'] ) {
		// Same engine as the site search (Thai word splitting, synonyms), so long Thai words and
		// alternative words find the course; results keep the engine's order (best match first).
		// Without the search index it falls back to WordPress' plain LIKE search.
		if ( function_exists( 'hri_search' ) && function_exists( 'hri_search_tables_ready' ) && hri_search_tables_ready() ) {
			$found = hri_search( $params['q'], array( 'types' => array( 'course' ) ) );
			$ids   = array_map( 'intval', $found['ids']['course'] ?? array() );
			$args['post__in'] = $ids ? $ids : array( 0 );
			if ( $ids ) {
				$args['orderby'] = 'post__in';
			}
		} else {
			$args['s'] = $params['q'];
		}
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
	// The card titles are H3: give them a parent heading (WCAG 1.3.1, axe heading-order).
	echo '<h2 class="screen-reader-text">' . esc_html__( 'รายการหลักสูตร', 'hri-core' ) . '</h2>';

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
				'prev_text' => hri_icon( 'caret-left', '', __( 'หน้าก่อนหน้า', 'hri-core' ) ),
				'next_text' => hri_icon( 'caret-right', '', __( 'หน้าถัดไป', 'hri-core' ) ),
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
 * Course IDs in the current language, optionally of one program (for filter counts).
 *
 * Counts always come from this list rather than the term's own count: the course taxonomies are
 * shared by all languages, so a term count would add up TH + EN + JA courses.
 *
 * @param string $program Program slug, '' for all programs.
 * @return int[]
 */
function hri_program_course_ids( string $program = '' ): array {
	$args = array(
		'post_type'        => 'course',
		'post_status'      => 'publish',
		'numberposts'      => -1,
		'fields'           => 'ids',
		'no_found_rows'    => true,
		'suppress_filters' => false, // Lets Polylang limit the list to the current language.
	);
	if ( '' !== $program ) {
		$args['tax_query'] = array( array( 'taxonomy' => 'hri_program', 'field' => 'slug', 'terms' => $program ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	return array_map( 'intval', get_posts( $args ) );
}

/**
 * Full block output: filter form + results.
 *
 * @param array{program?:string} $args `program` locks the list to one program (e.g. "inhouse").
 */
function hri_render_course_filter( array $args = array() ): string {
	$params = hri_filter_params( (string) ( $args['program'] ?? '' ) );
	$locked = '' !== $params['lock'];
	$scope  = hri_program_course_ids( $params['lock'] );
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
					if ( 'program' === $key && $locked ) {
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
			<?php // Fold the panel on small screens before first paint (view.js does the same later; doing it only there shifted the page, CLS 0.26). Without JS it stays open. ?>
			<script>if(matchMedia('(max-width:899px)').matches)document.currentScript.previousElementSibling.open=false</script>
		</form>

		<?php echo hri_render_course_results( $query, $params, $action ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
	</div>
	<?php
	wp_reset_postdata();
	return (string) ob_get_clean();
}
