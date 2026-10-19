<?php
/**
 * Contact Info block: phone, e-mail, LINE and address from Settings → ข้อมูลบริษัท.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$items = array_filter( (array) ( $attributes['items'] ?? array() ), 'is_string' );
$rows  = '';

foreach ( $items as $item ) {
	switch ( $item ) {
		case 'phone':
			$phone = hri_company( 'phone' );
			if ( $phone ) {
				$rows .= sprintf( '<li class="hri-contact__item"><span class="hri-contact__label">%1$s</span><a href="%2$s">%3$s</a></li>', esc_html__( 'โทร', 'hri-core' ), esc_url( hri_phone_href( $phone ), array( 'tel' ) ), esc_html( $phone ) );
			}
			break;
		case 'email':
			$email = hri_company( 'email' );
			if ( $email && is_email( $email ) ) {
				$rows .= sprintf( '<li class="hri-contact__item"><span class="hri-contact__label">%1$s</span><a href="%2$s">%3$s</a></li>', esc_html__( 'อีเมล', 'hri-core' ), esc_url( 'mailto:' . $email, array( 'mailto' ) ), esc_html( antispambot( $email ) ) );
			}
			break;
		case 'line':
			$line = hri_company( 'line_url' );
			if ( $line ) {
				$rows .= sprintf( '<li class="hri-contact__item"><span class="hri-contact__label">LINE</span><a href="%1$s" rel="noopener" target="_blank">%2$s<span class="screen-reader-text"> %3$s</span></a></li>', esc_url( $line ), esc_html__( 'แอด LINE', 'hri-core' ), esc_html__( '(เปิดในแท็บใหม่)', 'hri-core' ) );
			}
			break;
		case 'legal':
			$legal  = hri_company( 'legal_name' );
			$tax_id = hri_company( 'tax_id' );
			if ( $legal ) {
				$rows .= sprintf( '<li class="hri-contact__item"><span class="hri-contact__label">%1$s</span><span>%2$s%3$s</span></li>', esc_html__( 'บริษัท', 'hri-core' ), esc_html( $legal ), $tax_id ? '<br>' . esc_html( sprintf( /* translators: %s: company registration number */ __( 'เลขทะเบียนนิติบุคคล %s', 'hri-core' ), $tax_id ) ) : '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
			}
			break;
		case 'address':
			$address = hri_company( 'address' );
			if ( $address ) {
				$rows .= sprintf( '<li class="hri-contact__item hri-contact__item--address"><span class="hri-contact__label">%1$s</span><address>%2$s</address></li>', esc_html__( 'ที่อยู่', 'hri-core' ), nl2br( esc_html( $address ) ) );
			}
			break;
	}
}

if ( '' !== $rows ) {
	echo '<ul ' . get_block_wrapper_attributes( array( 'class' => 'hri-contact' ) ) . '>' . $rows . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
}
