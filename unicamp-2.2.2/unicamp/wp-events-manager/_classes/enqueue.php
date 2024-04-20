<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event_Enqueue' ) ) {
	class Unicamp_Event_Enqueue extends Unicamp_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 15 );

			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 99 );
		}

		/**
		 * Enqueue scripts only used.
		 * Fixed datetime-jquery conflict with Zoom meeting in Tutor LMS.
		 */
		public function admin_enqueue_scripts() {
			$screen = get_current_screen();

			if ( 'tp_event' !== $screen->id ) {
				wp_deregister_script( 'wpems-admin-js' );
				wp_deregister_script( 'wpems-admin-datetimepicker-full' );
				wp_dequeue_script( 'wpems-admin-js' );
				wp_dequeue_script( 'wpems-admin-datetimepicker-full' );

				wp_deregister_style( 'wpems-admin-css' );
				wp_deregister_style( 'wpems-admin-datetimepicker-min' );
				wp_dequeue_style( 'wpems-admin-css' );
				wp_dequeue_style( 'wpems-admin-datetimepicker-min' );
			}
		}

		public function frontend_scripts() {
			/**
			 * Dequeue because plugin prefix & enqueue all pages.
			 */
			wp_dequeue_script( 'wpems-owl-carousel-js' );
			wp_dequeue_style( 'wpems-owl-carousel-css' );

			wp_dequeue_script( 'wpems-magnific-popup-js' );
			wp_dequeue_style( 'wpems-magnific-popup-css' );

			wp_dequeue_script( 'wpems-countdown-plugin-js' );
			wp_dequeue_script( 'wpems-countdown-js' );
			wp_dequeue_style( 'wpems-countdown-css' );

			/**
			 * Re-write plugin js
			 * Use other countdown jquery.
			 * Remove unused code.
			 */
			wp_dequeue_script( 'wpems-frontend-js' );
			wp_dequeue_style( 'wpems-fronted-css' );

			wp_register_style( 'unicamp-events-manager', UNICAMP_THEME_URI . '/events-manager.css', null, UNICAMP_THEME_VERSION );

			wp_enqueue_style( 'unicamp-events-manager' );

			if ( $this->is_archive() ) {
				wp_enqueue_script( 'unicamp-grid-layout' );
			}

			if ( $this->is_single() ) {
				wp_enqueue_style( 'magnific-popup' );
				wp_enqueue_script( 'magnific-popup' );

				wp_enqueue_script( 'unicamp-quantity-button' );
				wp_enqueue_script( 'sticky-kit' );

				$js_vars = wpems_l18n();

				wp_enqueue_script( 'unicamp-events-manager', UNICAMP_THEME_ASSETS_URI . '/js/events-manager/single.js', [
					'jquery',
					'countdown',
				], null, true );

				wp_localize_script( 'unicamp-events-manager', 'WPEMS', $js_vars );
			}
		}
	}

	Unicamp_Event_Enqueue::instance()->initialize();
}
