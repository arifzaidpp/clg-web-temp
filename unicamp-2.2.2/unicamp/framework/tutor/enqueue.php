<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Tutor_Enqueue' ) ) {
	class Unicamp_Tutor_Enqueue {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 11 );
		}

		public function frontend_scripts() {
			$min = Unicamp_Enqueue::instance()->get_min_suffix();

			wp_dequeue_style( 'tutor-inter-fonts' );
			wp_dequeue_style( 'tutor' );
			wp_dequeue_style( 'tutor-frontend' );
			wp_dequeue_style( 'tutor-course-builder-css' );

			if ( Unicamp_Tutor::instance()->is_create_course() ) {
				wp_enqueue_style( 'unicamp-tutor-course-builder', UNICAMP_THEME_URI . '/tutor-lms-course-builder.css', null, UNICAMP_THEME_VERSION );
			}

			// Disable social share on all pages then add it for single course only.
			wp_dequeue_script( 'tutor-social-share' );

			wp_register_script( 'unicamp-course-archive', UNICAMP_THEME_ASSETS_URI . "/js/tutor/archive{$min}.js", [ 'jquery' ], 'null', true );
			wp_register_script( 'unicamp-course-single', UNICAMP_THEME_ASSETS_URI . "/js/tutor/single{$min}.js", [ 'jquery' ], 'null', true );
			wp_register_script( 'unicamp-tutor-dashboard', UNICAMP_THEME_ASSETS_URI . "/js/tutor/dashboard{$min}.js", [ 'jquery' ], 'null', true );

			wp_register_style( 'unicamp-tutor', UNICAMP_THEME_URI . "/tutor-lms{$min}.css", null, UNICAMP_THEME_VERSION );

			wp_enqueue_style( 'unicamp-tutor' );

			$wishlist_dependency = [];
			if ( ! is_user_logged_in() ) {
				$wishlist_dependency[] = 'unicamp-login';
			}
			wp_register_script( 'unicamp-course-general', UNICAMP_THEME_ASSETS_URI . "/js/tutor/general{$min}.js", $wishlist_dependency, 'null', true );

			wp_enqueue_script( 'unicamp-course-general' );

			$js_variables = array(
				'addedText' => esc_html__( 'Remove from wishlist', 'unicamp' ),
				'addText'   => esc_html__( 'Add to wishlist', 'unicamp' ),
			);
			wp_localize_script( 'unicamp-course-general', '$unicampCourseWishlist', $js_variables );

			if ( Unicamp_Tutor::instance()->is_dashboard() ) {
				wp_enqueue_script( 'unicamp-tutor-dashboard' );
			}

			if ( Unicamp_Tutor::instance()->is_category() ) {
				wp_enqueue_script( 'unicamp-tab-panel' );
			}

			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				wp_enqueue_script( 'unicamp-common-archive' );
				wp_enqueue_script( 'unicamp-course-archive' );
			}

			if ( Unicamp_Tutor::instance()->is_single_course() ) {
				wp_enqueue_script( 'tutor-social-share' );

				wp_enqueue_script( 'unicamp-course-single' );

				wp_enqueue_script( 'sticky-kit' );
			}
		}
	}
}
