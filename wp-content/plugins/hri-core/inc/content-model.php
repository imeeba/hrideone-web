<?php
/**
 * Content model: courses, their taxonomies and fields.
 *
 * One `course` post type covers both In-house and Public Training. A Public course
 * carries only its current batch (รุ่น) as fields on the course itself.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomies attached to `course`. Slugs are prefixed to avoid clashing with core query vars.
 *
 * @return array<string,array{label:string,hierarchical:bool}>
 */
function hri_course_taxonomies(): array {
	return array(
		'hri_program' => array( 'label' => __( 'ประเภท', 'hri-core' ), 'hierarchical' => false ),
		'hri_pillar'  => array( 'label' => __( 'หมวด', 'hri-core' ), 'hierarchical' => false ),
		'hri_topic'   => array( 'label' => __( 'หัวข้อ', 'hri-core' ), 'hierarchical' => true ),
		'hri_level'   => array( 'label' => __( 'ระดับผู้เรียน', 'hri-core' ), 'hierarchical' => false ),
		'hri_format'  => array( 'label' => __( 'รูปแบบ', 'hri-core' ), 'hierarchical' => false ),
	);
}

/**
 * Course taxonomy names only (no labels, so it is safe before `init`).
 *
 * @return string[]
 */
function hri_course_taxonomy_names(): array {
	return array( 'hri_program', 'hri_pillar', 'hri_topic', 'hri_level', 'hri_format' );
}

/**
 * Registration status labels for Public courses.
 *
 * @return array<string,string>
 */
function hri_reg_status_labels(): array {
	return array(
		'open'   => __( 'เปิดรับสมัคร', 'hri-core' ),
		'full'   => __( 'เต็มแล้ว', 'hri-core' ),
		'closed' => __( 'ปิดรับสมัคร', 'hri-core' ),
	);
}

/**
 * Meta fields. `sanitize` is applied on save and over REST.
 *
 * @return array<string,array{type:string,sanitize:callable}>
 */
function hri_course_meta_schema(): array {
	$date = static function ( $value ): string {
		$value = is_string( $value ) ? trim( $value ) : '';
		return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) && strtotime( $value ) ? $value : '';
	};

	return array(
		'hri_duration_days' => array( 'type' => 'integer', 'sanitize' => 'absint' ),
		'hri_audience'      => array( 'type' => 'string', 'sanitize' => 'sanitize_text_field' ),
		'hri_batch_no'      => array( 'type' => 'integer', 'sanitize' => 'absint' ),
		'hri_reg_status'    => array(
			'type'     => 'string',
			'sanitize' => static fn( $value ): string => is_string( $value ) && isset( hri_reg_status_labels()[ $value ] ) ? $value : '',
		),
		'hri_start_date'    => array( 'type' => 'string', 'sanitize' => $date ),
		'hri_end_date'      => array( 'type' => 'string', 'sanitize' => $date ),
		'hri_location'      => array( 'type' => 'string', 'sanitize' => 'sanitize_text_field' ),
		'hri_price'         => array( 'type' => 'string', 'sanitize' => 'sanitize_text_field' ),
	);
}

add_action(
	'init',
	static function () {
		register_post_type(
			'course',
			array(
				'labels'          => array(
					'name'          => __( 'หลักสูตร', 'hri-core' ),
					'singular_name' => __( 'หลักสูตร', 'hri-core' ),
					'menu_name'     => __( 'หลักสูตร', 'hri-core' ),
					'all_items'     => __( 'หลักสูตรทั้งหมด', 'hri-core' ),
					'add_new_item'  => __( 'เพิ่มหลักสูตร', 'hri-core' ),
					'edit_item'     => __( 'แก้ไขหลักสูตร', 'hri-core' ),
					'search_items'  => __( 'ค้นหาหลักสูตร', 'hri-core' ),
					'not_found'     => __( 'ไม่พบหลักสูตร', 'hri-core' ),
				),
				'public'          => true,
				'has_archive'     => 'courses',
				'rewrite'         => array( 'slug' => 'courses', 'with_front' => false ),
				'menu_icon'       => 'dashicons-welcome-learn-more',
				'menu_position'   => 5,
				'show_in_rest'    => true,
				'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			)
		);

		foreach ( hri_course_taxonomies() as $taxonomy => $config ) {
			register_taxonomy(
				$taxonomy,
				'course',
				array(
					'labels'            => array(
						'name'          => $config['label'],
						'singular_name' => $config['label'],
						'menu_name'     => $config['label'],
					),
					'hierarchical'      => $config['hierarchical'],
					// Terms are only used for filtering, so there are no archive pages for them.
					'public'            => false,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_rest'      => true,
					'rewrite'           => false,
				)
			);
		}

		foreach ( hri_course_meta_schema() as $key => $field ) {
			register_post_meta(
				'course',
				$key,
				array(
					'single'            => true,
					'type'              => $field['type'],
					'show_in_rest'      => true,
					'sanitize_callback' => $field['sanitize'],
					'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
				)
			);
		}
	}
);

/**
 * Meta box with the course fields.
 */
add_action(
	'add_meta_boxes_course',
	static function () {
		add_meta_box( 'hri_course_details', __( 'รายละเอียดหลักสูตร', 'hri-core' ), 'hri_course_metabox', 'course', 'normal', 'high' );
	}
);

/**
 * Render one labelled input row.
 */
function hri_metabox_row( string $key, string $label, string $input, string $hint = '' ): void {
	printf(
		'<tr><th scope="row"><label for="%1$s">%2$s</label></th><td>%3$s%4$s</td></tr>',
		esc_attr( $key ),
		esc_html( $label ),
		$input, // phpcs:ignore WordPress.Security.EscapeOutput -- built with esc_attr() below.
		$hint ? '<p class="description">' . esc_html( $hint ) . '</p>' : '' // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

function hri_course_metabox( WP_Post $post ): void {
	wp_nonce_field( 'hri_course_meta', 'hri_course_meta_nonce' );
	$get = static fn( string $key ) => get_post_meta( $post->ID, $key, true );

	$input = static fn( string $key, string $type = 'text', string $extra = '' ): string => sprintf(
		'<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" class="regular-text" %4$s>',
		esc_attr( $type ),
		esc_attr( $key ),
		esc_attr( (string) $get( $key ) ),
		$extra // phpcs:ignore WordPress.Security.EscapeOutput -- fixed strings only.
	);

	echo '<table class="form-table" role="presentation"><tbody>';
	hri_metabox_row( 'hri_duration_days', __( 'ระยะเวลา (วัน)', 'hri-core' ), $input( 'hri_duration_days', 'number', 'min="1" max="365" style="width:6em"' ), __( 'ใช้กับตัวกรองระยะเวลา', 'hri-core' ) );
	hri_metabox_row( 'hri_audience', __( 'กลุ่มเป้าหมาย', 'hri-core' ), $input( 'hri_audience' ) );
	echo '</tbody></table>';

	echo '<h3 style="margin:1.5em 0 0">' . esc_html__( 'เฉพาะ Public Training (เว้นว่างถ้าเป็น In-house)', 'hri-core' ) . '</h3>';
	echo '<p class="description">' . esc_html__( 'เก็บเฉพาะรุ่นที่เปิดอยู่ล่าสุด เมื่อเปิดรุ่นใหม่ให้แก้ตัวเลขที่นี่ ไม่ต้องสร้างหน้าใหม่', 'hri-core' ) . '</p>';
	echo '<table class="form-table" role="presentation"><tbody>';

	hri_metabox_row( 'hri_batch_no', __( 'รุ่นที่', 'hri-core' ), $input( 'hri_batch_no', 'number', 'min="1" style="width:6em"' ) );

	$options = '<option value="">' . esc_html__( '— ไม่ระบุ —', 'hri-core' ) . '</option>';
	foreach ( hri_reg_status_labels() as $value => $label ) {
		$options .= sprintf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $get( 'hri_reg_status' ), $value, false ), esc_html( $label ) );
	}
	hri_metabox_row( 'hri_reg_status', __( 'สถานะการรับสมัคร', 'hri-core' ), '<select id="hri_reg_status" name="hri_reg_status">' . $options . '</select>' );

	hri_metabox_row( 'hri_start_date', __( 'วันเริ่ม', 'hri-core' ), $input( 'hri_start_date', 'date' ) );
	hri_metabox_row( 'hri_end_date', __( 'วันสิ้นสุด', 'hri-core' ), $input( 'hri_end_date', 'date' ) );
	hri_metabox_row( 'hri_location', __( 'สถานที่', 'hri-core' ), $input( 'hri_location' ) );
	hri_metabox_row( 'hri_price', __( 'ราคา', 'hri-core' ), $input( 'hri_price' ), __( 'พิมพ์เป็นข้อความได้ เช่น 12,900 บาท/ท่าน', 'hri-core' ) );
	echo '</tbody></table>';
}

add_action(
	'save_post_course',
	static function ( int $post_id ) {
		if ( ! isset( $_POST['hri_course_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hri_course_meta_nonce'] ) ), 'hri_course_meta' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		foreach ( hri_course_meta_schema() as $key => $field ) {
			$raw   = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized next line.
			$value = is_string( $raw ) ? call_user_func( $field['sanitize'], $raw ) : '';

			if ( '' === $value || 0 === $value ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}
);

/**
 * All course fields in one array (empty values omitted).
 *
 * @return array<string,mixed>
 */
function hri_course_meta( int $post_id ): array {
	$out = array();
	foreach ( array_keys( hri_course_meta_schema() ) as $key ) {
		$value = get_post_meta( $post_id, $key, true );
		if ( '' !== $value && 0 !== $value && '0' !== $value ) {
			$out[ $key ] = $value;
		}
	}
	return $out;
}
