<?php
/**
 * Search results block (server-rendered): summary, type links with counts, groups of results.
 * No JavaScript. Courses use the course card; articles and pages are text only.
 *
 * ?s=<query>  ?type=course|post|page (one type, paginated)  ?pg=<n>
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'hri_search' ) ) {
	return;
}

$query   = hri_search_query_string();
$result  = hri_search( $query );
$labels  = array(
	'course' => __( 'หลักสูตร', 'hri-core' ),
	'post'   => __( 'บทความ', 'hri-core' ),
	'page'   => __( 'หน้าเว็บ', 'hri-core' ),
);
$only    = isset( $_GET['type'] ) && is_string( $_GET['type'] ) && isset( $labels[ $_GET['type'] ] ) ? $_GET['type'] : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput -- whitelisted.
$counts  = array();
foreach ( $labels as $type => $label ) {
	$counts[ $type ] = count( $result['ids'][ $type ] ?? array() );
}
$wrapper = get_block_wrapper_attributes( array( 'class' => 'hri-sr' ) );

/**
 * Link to this search with another type / page.
 */
$link = static function ( string $type = '', int $page = 1 ) use ( $query ): string {
	$args = array( 's' => $query );
	if ( '' !== $type ) {
		$args['type'] = $type;
	}
	if ( $page > 1 ) {
		$args['pg'] = $page;
	}
	return add_query_arg( rawurlencode_deep( $args ), hri_search_action_url() );
};

echo '<div ' . $wrapper . '>'; // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.

if ( '' === $query ) {
	echo '<p class="hri-sr__summary">' . esc_html__( 'พิมพ์คำที่ต้องการค้นหา เช่น เจรจา ผู้นำ โค้ชชิ่ง', 'hri-core' ) . '</p>';
	echo hri_search_empty_suggestions(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
	echo '</div>';
	return;
}

if ( 0 === $result['total'] ) {
	echo '<p class="hri-sr__summary" role="status">' . esc_html( sprintf( /* translators: %s: the search words */ __( 'ไม่พบผลการค้นหาสำหรับ “%s”', 'hri-core' ), $query ) ) . '</p>';
	echo '<p class="hri-sr__tip">' . esc_html__( 'ลองใช้คำที่สั้นลง คำที่ใกล้เคียง หรือพิมพ์เป็นภาษาอื่น เช่น negotiation แทน เจรจา', 'hri-core' ) . '</p>';
	echo hri_search_empty_suggestions(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
	echo '</div>';
	return;
}

echo '<p class="hri-sr__summary" role="status">' . esc_html( sprintf( /* translators: 1: number of results, 2: the search words */ _n( 'พบ %1$s รายการสำหรับ “%2$s”', 'พบ %1$s รายการสำหรับ “%2$s”', $result['total'], 'hri-core' ), number_format_i18n( $result['total'] ), $query ) ) . '</p>';
if ( $result['partial'] ) {
	echo '<p class="hri-sr__tip">' . esc_html__( 'ไม่มีรายการที่ตรงกับทุกคำ จึงแสดงรายการที่ตรงกับบางคำ', 'hri-core' ) . '</p>';
}

// Type links with counts (a plain list of links; "all" first).
echo '<nav class="hri-sr__types" aria-label="' . esc_attr__( 'ประเภทผลการค้นหา', 'hri-core' ) . '"><ul>';
printf(
	'<li><a href="%1$s"%2$s>%3$s <span>%4$s</span></a></li>',
	esc_url( $link() ),
	'' === $only ? ' aria-current="true"' : '',
	esc_html__( 'ทั้งหมด', 'hri-core' ),
	esc_html( number_format_i18n( $result['total'] ) )
);
foreach ( $labels as $type => $label ) {
	if ( $counts[ $type ] ) {
		printf(
			'<li><a href="%1$s"%2$s>%3$s <span>%4$s</span></a></li>',
			esc_url( $link( $type ) ),
			$only === $type ? ' aria-current="true"' : '',
			esc_html( $label ),
			esc_html( number_format_i18n( $counts[ $type ] ) )
		);
	}
}
echo '</ul></nav>';

$page = max( 1, absint( $_GET['pg'] ?? 1 ) ); // phpcs:ignore WordPress.Security.NonceVerification
foreach ( $labels as $type => $label ) {
	if ( ! $counts[ $type ] || ( '' !== $only && $only !== $type ) ) {
		continue;
	}
	$ids = $result['ids'][ $type ];
	if ( '' === $only ) {
		$shown = array_slice( $ids, 0, HRI_SEARCH_PER_GROUP );
	} else {
		$pages = (int) ceil( $counts[ $type ] / HRI_SEARCH_PER_PAGE );
		$page  = min( $page, $pages );
		$shown = array_slice( $ids, ( $page - 1 ) * HRI_SEARCH_PER_PAGE, HRI_SEARCH_PER_PAGE );
	}

	echo '<section class="hri-sr__group" aria-labelledby="hri-sr-' . esc_attr( $type ) . '">';
	echo '<h2 class="hri-sr__heading" id="hri-sr-' . esc_attr( $type ) . '">' . esc_html( $label ) . ' <span class="hri-sr__count">' . esc_html( number_format_i18n( $counts[ $type ] ) ) . '</span></h2>';
	if ( 'course' === $type ) {
		echo '<div class="hri-grid">';
		foreach ( $shown as $id ) {
			echo hri_course_card( (int) $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the card.
		}
		echo '</div>';
	} else {
		echo '<ul class="hri-sr__list">';
		foreach ( $shown as $id ) {
			echo hri_search_render_item( (int) $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
		}
		echo '</ul>';
	}
	if ( '' === $only && $counts[ $type ] > HRI_SEARCH_PER_GROUP ) {
		printf(
			'<p class="hri-sr__more"><a href="%1$s">%2$s</a></p>',
			esc_url( $link( $type ) ),
			esc_html( sprintf( /* translators: 1: type (courses…), 2: number */ __( 'ดู%1$sทั้งหมด (%2$s)', 'hri-core' ), $label, number_format_i18n( $counts[ $type ] ) ) )
		);
	}
	if ( '' !== $only && $pages > 1 ) {
		echo '<nav class="hri-pagination" aria-label="' . esc_attr__( 'หน้าผลการค้นหา', 'hri-core' ) . '"><ul class="page-numbers">';
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( $i === $page ) {
				printf( '<li><span class="page-numbers current" aria-current="page">%s</span></li>', esc_html( number_format_i18n( $i ) ) );
			} else {
				printf( '<li><a class="page-numbers" href="%1$s">%2$s</a></li>', esc_url( $link( $type, $i ) ), esc_html( number_format_i18n( $i ) ) );
			}
		}
		echo '</ul></nav>';
	}
	echo '</section>';
}
echo '</div>';
