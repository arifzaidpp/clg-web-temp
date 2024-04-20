<?php

namespace Unicamp\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

class Customizer extends Utils {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'unicamp_customizer_init', [ $this, 'customizer_settings' ] );
	}

	public function customizer_settings() {
		$panel    = 'zoom_meeting';
		$priority = 1;

		\Unicamp_Kirki::add_panel( $panel, array(
			'title'    => esc_html__( 'Zoom Meeting', 'unicamp' ),
			'priority' => 140,
		) );

		\Unicamp_Kirki::add_section( 'zoom_meeting_archive', array(
			'title'    => esc_html__( 'Archive', 'unicamp' ),
			'panel'    => $panel,
			'priority' => $priority++,
		) );

		\Unicamp_Kirki::add_section( 'zoom_meeting_single', array(
			'title'    => esc_html__( 'Single', 'unicamp' ),
			'panel'    => $panel,
			'priority' => $priority++,
		) );

		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/customizer/archive.php';
		require_once UNICAMP_ZOOM_MEETING_CORE_DIR . '/customizer/single.php';
	}
}

Customizer::instance()->initialize();
