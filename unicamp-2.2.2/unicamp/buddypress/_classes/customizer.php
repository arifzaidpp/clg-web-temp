<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_BP_Customizer' ) ) {
	class Unicamp_BP_Customizer extends Unicamp_BP {

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
			Unicamp_Kirki::add_section( 'buddypress', array(
				'title'    => esc_html__( 'BuddyPress', 'unicamp' ),
				'priority' => 170,
			) );

			require_once UNICAMP_BP_CORE_DIR . '/customizer/section-main.php';
		}
	}

	Unicamp_BP_Customizer::instance()->initialize();
}
