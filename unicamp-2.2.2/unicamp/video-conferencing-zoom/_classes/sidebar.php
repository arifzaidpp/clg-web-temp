<?php

namespace Unicamp\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

class Sidebar extends Utils {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	public function register_sidebars() {
		$default_args = \Unicamp_Sidebar::instance()->get_default_sidebar_args();

		register_sidebar( array_merge( $default_args, array(
			'id'          => 'zoom_meeting_sidebar',
			'name'        => esc_html__( 'Zoom Meeting Sidebar', 'unicamp' ),
			'description' => esc_html__( 'Add widgets here.', 'unicamp' ),
		) ) );
	}
}

Sidebar::instance()->initialize();
