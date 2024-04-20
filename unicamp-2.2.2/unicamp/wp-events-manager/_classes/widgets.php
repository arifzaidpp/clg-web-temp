<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event_Widgets' ) ) {
	class Unicamp_Event_Widgets {

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
			unicamp_require_once( UNICAMP_EVENT_MANAGER_WIDGETS_DIR . '/events.php' );
			unicamp_require_once( UNICAMP_EVENT_MANAGER_WIDGETS_DIR . '/event-filtering.php' );

			register_widget( 'Unicamp_WP_Widget_Events' );
			register_widget( 'Unicamp_WP_Widget_Event_Filtering' );
		}
	}

	Unicamp_Event_Widgets::instance()->initialize();
}
