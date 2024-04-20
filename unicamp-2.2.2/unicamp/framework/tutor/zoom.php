<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Zoom' ) ) {
	class Unicamp_Tutor_Zoom {

		protected static $instance = null;

		const API_KEY      = 'tutor_zoom_api';
		const SETTINGS_KEY = 'tutor_zoom_settings';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			if ( ! $this->is_activate() ) {
				return;
			}

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		}

		public function is_activate() {
			if ( ! function_exists( 'TUTOR_ZOOM' ) ) {
				return false;
			}

			$addonConfig = tutor_utils()->get_addon_config( TUTOR_ZOOM()->basename );
			$isEnable    = (bool) tutor_utils()->avalue_dot( 'is_enable', $addonConfig );

			if ( $isEnable ) {
				return true;
			}

			return false;
		}

		/**
		 * @see \TUTOR_ZOOM\Zoom::frontend_scripts()
		 * Better enqueue scripts
		 */
		public function frontend_scripts() {
			$min = Unicamp_Enqueue::instance()->get_min_suffix();

			// First, dequeue all scripts & styles then enqueues them again when needed.
			wp_dequeue_script( 'tutor_zoom_timepicker_js' );
			wp_dequeue_script( 'tutor_zoom_common_js' );
			wp_dequeue_style( 'tutor_zoom_common_css' );
			wp_dequeue_style( 'tutor_zoom_timepicker_css' );
			wp_dequeue_script( 'tutor_zoom_moment_js' );
			wp_dequeue_script( 'tutor_zoom_moment_tz_js' );
			wp_dequeue_script( 'tutor_zoom_countdown_js' ); // Use theme version.
			wp_dequeue_script( 'tutor_zoom_frontend_js' ); // Use theme version.
			wp_dequeue_style( 'tutor_zoom_frontend_css' );

			if ( Unicamp_Tutor::instance()->is_create_course() ) {
				wp_enqueue_style( 'tutor_zoom_timepicker_css', TUTOR_ZOOM()->url . 'assets/css/jquery-ui-timepicker.css', false, TUTOR_ZOOM_VERSION );
				wp_enqueue_script( 'tutor_zoom_timepicker_js', TUTOR_ZOOM()->url . 'assets/js/jquery-ui-timepicker.js', array(
					'jquery',
					'jquery-ui-datepicker',
					'jquery-ui-slider',
				), TUTOR_ZOOM_VERSION, true );

				wp_enqueue_style( 'tutor_zoom_common_css', TUTOR_ZOOM()->url . 'assets/css/common.css', false, TUTOR_ZOOM_VERSION );
				wp_enqueue_script( 'tutor_zoom_common_js', TUTOR_ZOOM()->url . 'assets/js/common.js', array(
					'jquery',
					'jquery-ui-datepicker',
				), TUTOR_ZOOM_VERSION, true );

				wp_enqueue_style( 'tutor_zoom_frontend_css', TUTOR_ZOOM()->url . 'assets/css/frontend.css', false, TUTOR_ZOOM_VERSION );
			}

			if ( Unicamp_Tutor::instance()->is_single_course() || Unicamp_Tutor::instance()->is_single_zoom_meeting() ) {
				wp_enqueue_style( 'tutor_zoom_frontend_css', TUTOR_ZOOM()->url . 'assets/css/frontend.css', false, TUTOR_ZOOM_VERSION );

				wp_enqueue_script( 'tutor_zoom_moment_js', TUTOR_ZOOM()->url . 'assets/js/moment.min.js', array(), TUTOR_ZOOM_VERSION, true );
				wp_enqueue_script( 'tutor_zoom_moment_tz_js', TUTOR_ZOOM()->url . 'assets/js/moment-timezone-with-data.min.js', array(), TUTOR_ZOOM_VERSION, true );

				/**
				 * Use countdown in theme to avoid duplicate scripts.
				 */
				wp_enqueue_script( 'countdown' );
				wp_enqueue_script( 'unicamp-tutor-zoom', UNICAMP_THEME_URI . "/assets/js/tutor/zoom{$min}.js", [ 'jquery' ], '1.0.0', true );
			}
		}

		public function get_option_data( $key, $data ) {
			if ( empty( $data ) || ! is_array( $data ) ) {
				return false;
			}
			if ( ! $key ) {
				return $data;
			}
			if ( array_key_exists( $key, $data ) ) {
				return apply_filters( $key, $data[ $key ] );
			}

			return false;
		}

		public function get_api( $key = null ) {
			$user_id  = get_current_user_id();
			$api_data = json_decode( get_user_meta( $user_id, self::API_KEY, true ), true );

			return $this->get_option_data( $key, $api_data );
		}

		public function get_settings( $key = null ) {
			$user_id       = get_current_user_id();
			$settings_data = json_decode( get_user_meta( $user_id, self::SETTINGS_KEY, true ), true );

			return $this->get_option_data( $key, $settings_data );
		}
	}
}
