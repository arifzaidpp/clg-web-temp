<?php
/**
 * The template for displaying loop read more.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

Unicamp_Templates::render_button( [
	'text'          => esc_html__( 'Read more', 'unicamp' ),
	'icon'          => 'fas fa-long-arrow-right',
	'icon_align'    => 'right',
	'size'          => 'xs',
	'wrapper_class' => 'post-read-more',
	'full_wide'     => true,
] );
