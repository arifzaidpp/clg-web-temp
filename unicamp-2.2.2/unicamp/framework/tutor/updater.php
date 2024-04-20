<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Updater' ) ) {
	class Unicamp_Tutor_Updater {

		protected static $instance      = null;
		public           $api_end_point = 'https://api2.thememove.com/wp-json/thememove/v2/tutor-validator';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'admin_init', [ $this, 'update_tutor_lms_pro_license' ], 2 );
		}

		public function update_tutor_lms_pro_license() {
			if ( ! $this->is_valid() ) {
				$response      = wp_remote_get( $this->api_end_point );
				$response_body = wp_remote_retrieve_body( $response );

				$license_info = json_decode( $response_body );
				$license_info = (array) $license_info;

				if ( ! empty( $license_info ) && isset( $license_info['activated'] ) ) {
					update_option( 'tutor_license_info', $license_info );
				}
			}
		}

		public function is_valid() {
			$getLicenses  = maybe_unserialize( get_option( 'tutor_license_info' ) );
			$license_info = (object) array( 'activated' => false );
			if ( is_array( $getLicenses ) && count( $getLicenses ) ) {
				$license_info = (object) $getLicenses;
			}

			if ( isset( $license_info->activated ) ) {
				return $license_info->activated;
			}

			return false;
		}
	}
}
