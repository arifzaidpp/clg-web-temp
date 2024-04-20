<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue child scripts
 */
if ( ! function_exists( 'unicamp_child_enqueue_scripts' ) ) {
	function unicamp_child_enqueue_scripts() {
		wp_enqueue_style( 'unicamp-child-style', get_stylesheet_directory_uri() . '/style.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'unicamp_child_enqueue_scripts', 15 );
