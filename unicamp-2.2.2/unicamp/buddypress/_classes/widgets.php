<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_BP_Widgets' ) ) {
	class Unicamp_BP_Widgets extends Unicamp_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Register widget areas.
			add_action( 'unicamp_widgets_init', [ $this, 'register_widgets' ] );
		}

		public function register_widgets() {
			if ( bp_is_active( 'groups' ) ) {
				require_once UNICAMP_BP_CORE_DIR . '/widgets/featured-group-activities.php';

				register_widget( 'Unicamp_WP_Widget_Featured_Group_Activities' );
			}
		}
	}

	Unicamp_BP_Widgets::instance()->initialize();
}
