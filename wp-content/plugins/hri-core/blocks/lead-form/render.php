<?php
/**
 * Lead Form block (server-rendered). The submission is handled by inc/leads.php.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

static $hri_lead_instance = 0;
++$hri_lead_instance;
$uid = 'hri-lead-' . $hri_lead_instance;

// Pre-selected interest: block setting, ?course=<slug> / ?interest=<service>, or the course page this block is on.
$selected = '';
$wanted   = isset( $_GET['hri_course'] ) ? sanitize_title( wp_unslash( $_GET['hri_course'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- read-only prefill.
$service  = isset( $_GET['interest'] ) ? sanitize_key( wp_unslash( $_GET['interest'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
if ( $wanted ) {
	$selected = hri_lead_course_value( $wanted );
}
if ( ! $selected && $service && isset( hri_lead_service_options()[ $service ] ) ) {
	$selected = 'service:' . $service;
}
if ( ! $selected && 'course' === ( $block->context['postType'] ?? '' ) ) {
	$selected = hri_lead_course_value( (string) (int) ( $block->context['postId'] ?? 0 ) );
}
if ( ! $selected && ! empty( $attributes['interest'] ) && isset( hri_lead_service_options()[ $attributes['interest'] ] ) ) {
	$selected = 'service:' . $attributes['interest'];
}

// Result of a no-JavaScript submit.
$state  = isset( $_GET['hri_lead'] ) ? sanitize_key( wp_unslash( $_GET['hri_lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$errors = isset( $_GET['hri_lead_fields'] ) ? array_filter( explode( ',', sanitize_text_field( wp_unslash( $_GET['hri_lead_fields'] ) ) ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification

$wrapper = get_block_wrapper_attributes( array( 'class' => 'hri-lead', 'id' => 1 === $hri_lead_instance ? 'hri-lead' : $uid ) );

if ( 'ok' === $state && 1 === $hri_lead_instance ) {
	printf(
		'<div %1$s><div class="hri-lead__done" role="status"><h3 class="hri-lead__done-title">%2$s</h3><p>%3$s</p></div></div>',
		$wrapper, // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes.
		esc_html__( 'ขอบคุณ ได้รับข้อมูลของคุณแล้ว', 'hri-core' ),
		esc_html__( 'ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง (ในวันทำการ) หากต้องการด่วน โทรหาเราได้ทันที', 'hri-core' )
	);
	return;
}

$field = static function ( string $name, string $label, string $input_html, bool $required = false, string $class = '' ) use ( $uid, $errors ): string {
	$has_error = in_array( $name, $errors, true );
	return sprintf(
		'<div class="hri-lead__field %5$s%6$s"><label for="%1$s-%2$s">%3$s%4$s</label>%7$s%8$s</div>',
		esc_attr( $uid ),
		esc_attr( $name ),
		esc_html( $label ),
		$required ? ' <span class="hri-lead__req" aria-hidden="true">*</span>' : ' <span class="hri-lead__opt">' . esc_html__( '(ไม่บังคับ)', 'hri-core' ) . '</span>',
		esc_attr( $class ),
		$has_error ? ' has-error' : '',
		$input_html, // phpcs:ignore WordPress.Security.EscapeOutput -- built with esc_*() by the caller.
		$has_error ? '<p class="hri-lead__error" id="' . esc_attr( $uid . '-' . $name ) . '-error">' . esc_html__( 'กรุณากรอกช่องนี้ให้ถูกต้อง', 'hri-core' ) . '</p>' : ''
	);
};

$input = static function ( string $name, string $type, string $autocomplete, bool $required, string $extra = '' ) use ( $uid, $errors ): string {
	return sprintf(
		'<input type="%1$s" id="%2$s-%3$s" name="%3$s" autocomplete="%4$s"%5$s%6$s%7$s>',
		esc_attr( $type ),
		esc_attr( $uid ),
		esc_attr( $name ),
		esc_attr( $autocomplete ),
		$required ? ' required aria-required="true"' : '',
		in_array( $name, $errors, true ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $uid . '-' . $name ) . '-error"' : '',
		$extra // phpcs:ignore WordPress.Security.EscapeOutput -- fixed strings only.
	);
};

// "Interested in" select: services, then Public and In-house courses.
$select  = '<select id="' . esc_attr( $uid ) . '-interest" name="interest">';
$select .= '<option value="">' . esc_html__( '— เลือกหัวข้อ —', 'hri-core' ) . '</option>';
$select .= '<optgroup label="' . esc_attr__( 'บริการ', 'hri-core' ) . '">';
foreach ( hri_lead_service_options() as $value => $label ) {
	$select .= sprintf( '<option value="service:%1$s"%2$s>%3$s</option>', esc_attr( $value ), selected( $selected, "service:{$value}", false ), esc_html( $label ) );
}
$select .= '</optgroup>';
foreach ( array( 'public' => __( 'Public Training — หลักสูตร', 'hri-core' ), 'inhouse' => __( 'In-house — หลักสูตร', 'hri-core' ) ) as $program => $group_label ) {
	$courses = hri_lead_course_options( $program );
	if ( ! $courses ) {
		continue;
	}
	$select .= '<optgroup label="' . esc_attr( $group_label ) . '">';
	foreach ( $courses as $id => $title ) {
		$select .= sprintf( '<option value="course:%1$d"%2$s>%3$s</option>', (int) $id, selected( $selected, "course:{$id}", false ), esc_html( $title ) );
	}
	$select .= '</optgroup>';
}
$select .= '</select>';

$message = sprintf( '<textarea id="%1$s-message" name="message" rows="4" maxlength="3000"></textarea>', esc_attr( $uid ) );

$source = home_url( '/' . trim( (string) ( $GLOBALS['wp']->request ?? '' ), '/' ) . '/' );
$submit = '' !== (string) ( $attributes['submitLabel'] ?? '' ) ? (string) $attributes['submitLabel'] : __( 'ส่งข้อมูล', 'hri-core' );

if ( hri_turnstile_enabled() ) {
	wp_enqueue_script( 'hri-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, array( 'in_footer' => true, 'strategy' => 'async' ) ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters -- external script, versioned by Cloudflare.
}

?>
<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput -- core helper escapes. ?>>
	<?php if ( 'error' === $state && 1 === $hri_lead_instance ) : ?>
		<p class="hri-lead__alert" role="alert"><?php esc_html_e( 'ส่งข้อมูลไม่สำเร็จ กรุณาตรวจช่องที่ทำเครื่องหมายไว้แล้วลองอีกครั้ง', 'hri-core' ); ?></p>
	<?php endif; ?>
	<form class="hri-lead__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-hri-lead
		data-endpoint="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		data-sending="<?php esc_attr_e( 'กำลังส่ง…', 'hri-core' ); ?>"
		data-failed="<?php esc_attr_e( 'ส่งข้อมูลไม่สำเร็จ กรุณาลองใหม่ หรือโทรหาเราโดยตรง', 'hri-core' ); ?>"
		data-done-title="<?php esc_attr_e( 'ขอบคุณ ได้รับข้อมูลของคุณแล้ว', 'hri-core' ); ?>"
		data-done-text="<?php esc_attr_e( 'ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง (ในวันทำการ) หากต้องการด่วน โทรหาเราได้ทันที', 'hri-core' ); ?>">
		<input type="hidden" name="action" value="hri_lead">
		<input type="hidden" name="lang" value="<?php echo esc_attr( hri_current_language() ); ?>">
		<input type="hidden" name="source" value="<?php echo esc_url( $source ); ?>">
		<div class="hri-lead__hp" aria-hidden="true"><label>Website<input type="text" name="hri_website" value="" tabindex="-1" autocomplete="off"></label></div>
		<div class="hri-lead__grid">
			<?php
			echo $field( 'name', __( 'ชื่อ-นามสกุล', 'hri-core' ), $input( 'name', 'text', 'name', true ), true ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in closures.
			echo $field( 'phone', __( 'เบอร์โทร', 'hri-core' ), $input( 'phone', 'tel', 'tel', true, ' inputmode="tel"' ), true ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $field( 'company', __( 'บริษัท / องค์กร', 'hri-core' ), $input( 'company', 'text', 'organization', true ), true ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $field( 'position', __( 'ตำแหน่ง', 'hri-core' ), $input( 'position', 'text', 'organization-title', false ), false ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $field( 'email', __( 'อีเมล', 'hri-core' ), $input( 'email', 'email', 'email', true, ' inputmode="email"' ), true, 'hri-lead__field--wide' ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $field( 'interest', __( 'สนใจเรื่อง', 'hri-core' ), $select, false, 'hri-lead__field--wide' ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $field( 'message', __( 'ข้อความ', 'hri-core' ), $message, false, 'hri-lead__field--wide' ); // phpcs:ignore WordPress.Security.EscapeOutput
			?>
		</div>
		<?php if ( hri_turnstile_enabled() ) : ?>
			<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( HRI_TURNSTILE_SITE_KEY ); ?>"></div>
		<?php endif; ?>
		<div class="hri-lead__consent<?php echo in_array( 'pdpa', $errors, true ) ? ' has-error' : ''; ?>">
			<label>
				<input type="checkbox" name="pdpa" value="1" required aria-required="true">
				<span><?php esc_html_e( 'ฉันยินยอมให้ HRI DeOne เก็บและใช้ข้อมูลนี้เพื่อติดต่อกลับเกี่ยวกับหลักสูตรและบริการ', 'hri-core' ); ?></span>
			</label>
		</div>
		<p class="hri-lead__status" data-hri-lead-status role="status"></p>
		<button type="submit" class="wp-element-button hri-lead__submit"><?php echo esc_html( $submit ); ?></button>
		<p class="hri-lead__note"><?php esc_html_e( 'ตอบกลับภายใน 24 ชั่วโมง (ในวันทำการ)', 'hri-core' ); ?></p>
	</form>
</div>
