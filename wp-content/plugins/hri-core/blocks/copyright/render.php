<?php
/**
 * Copyright block: "© 2026 HRI DeOne Co., Ltd." with the current year.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

$name = (string) ( $attributes['name'] ?? '' );
echo '<p ' . get_block_wrapper_attributes( array( 'class' => 'hri-copyright' ) ) . '>© ' . esc_html( wp_date( 'Y' ) . ( '' !== $name ? ' ' . $name : '' ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped here.
