<?php
/**
 * The template for displaying shortcode join links
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/shortcode/tm-join-links.php
 *
 * @author     Thememove
 * @package    Unicamp
 * @since      2.0.0
 */

global $meetings;

if ( ! empty( $meetings['join_uri'] ) ) {
	Unicamp_Templates::render_button( [
		'link'        => [
			'url'         => esc_url( $meetings['join_uri'] ),
			'is_external' => true,
			'nofollow'    => true,
		],
		'text'        => esc_html__( 'Join via Zoom App', 'unicamp' ),
		'extra_class' => 'btn-join-link-shortcode',
	] );
}

if ( ! empty( $meetings['browser_url'] ) ) {
	Unicamp_Templates::render_button( [
		'link'        => [
			'url'         => esc_url( $meetings['browser_url'] ),
			'is_external' => true,
			'nofollow'    => true,
		],
		'text'        => esc_html__( 'Join via Web Browser', 'unicamp' ),
		'extra_class' => 'btn-join-link-shortcode',
	] );
}
