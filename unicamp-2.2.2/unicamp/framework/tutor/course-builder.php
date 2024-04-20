<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Course_Builder' ) ) {
	class Unicamp_Tutor_Course_Builder {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'tutor_course_builder_logo_src', [ $this, 'change_logo' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 11 );
		}

		public function change_logo( $src ) {
			$src = UNICAMP_THEME_IMAGE_URI . '/logo/dark-logo.png';

			return $src;
		}

		public function frontend_scripts() {
			global $wp_query;

			$dashboard_page = tutor_utils()->array_get( 'query_vars.tutor_dashboard_page', $wp_query );

			if ( 'create-course' === $dashboard_page ) {
				wp_enqueue_script( 'sticky-kit' );
			}
		}
	}
}
