<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Init' ) ) {
	class Unicamp_WP_Widget_Init {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'widgets_init', [ $this, 'register_widgets' ] );

			add_filter( 'dynamic_sidebar_params', [ $this, 'add_css_class_for_search_forms' ] );
		}

		public function add_css_class_for_search_forms( $params ) {
			$widget_id = $params[0]['widget_id'];

			// Add the same css class to all search forms.
			if ( preg_match( '/^search-[0-9]/', $widget_id )
			     || preg_match( '/^woocommerce_product_search-[0-9]/', $widget_id )
			     || preg_match( '/^unicamp-wp-widget-course-name-filter-[0-9]/', $widget_id )
			) {
				$new_before_widget = $params[0]['before_widget'];
				$new_before_widget = preg_replace( '/class="widget/', 'class="widget-search-form widget', $new_before_widget );

				$params[0]['before_widget'] = $new_before_widget;
			}

			return $params;
		}

		public function register_widgets() {
			require_once UNICAMP_WIDGETS_DIR . '/class-widget-base.php';

			require_once UNICAMP_WIDGETS_DIR . '/posts.php';

			register_widget( 'Unicamp_WP_Widget_Posts' );

			/**
			 * FAQ Module.
			 */
			require_once UNICAMP_WIDGETS_DIR . '/faq/faqs.php';
			require_once UNICAMP_WIDGETS_DIR . '/faq/faq-groups.php';

			register_widget( 'Unicamp_WP_Widget_FAQs' );
			register_widget( 'Unicamp_WP_Widget_FAQ_Group' );

			if ( Unicamp_Woo::instance()->is_activated() ) {
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/class-wc-widget-base.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-badge.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-banner.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-sorting.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-rating-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-price-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-layered-nav.php';
				require_once UNICAMP_WIDGETS_DIR . '/woocommerce/product-categories-nav.php';

				register_widget( 'Unicamp_WP_Widget_Product_Badge' );
				register_widget( 'Unicamp_WP_Widget_Product_Banner' );
				register_widget( 'Unicamp_WP_Widget_Product_Sorting' );
				register_widget( 'Unicamp_WP_Widget_Product_Rating_Filter' );
				register_widget( 'Unicamp_WP_Widget_Product_Price_Filter' );
				register_widget( 'Unicamp_WP_Widget_Product_Layered_Nav' );
				register_widget( 'Unicamp_WP_Widget_Product_Categories_Layered_Nav' );
			}

			if ( Unicamp_Tutor::instance()->is_activated() ) {
				require_once UNICAMP_WIDGETS_DIR . '/tutor/class-course-layered-nav-base.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-name-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-category-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-language-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-location-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-level-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-instructor-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-price-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-rating-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-duration-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-sorting.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-form-filter.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/courses.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-categories.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-levels.php';
				require_once UNICAMP_WIDGETS_DIR . '/tutor/course-locations.php';

				register_widget( 'Unicamp_WP_Widget_Courses' );
				register_widget( 'Unicamp_WP_Widget_Course_Categories' );
				register_widget( 'Unicamp_WP_Widget_Course_Levels' );
				register_widget( 'Unicamp_WP_Widget_Course_Locations' );
				register_widget( 'Unicamp_WP_Widget_Course_Name_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Category_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Language_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Location_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Level_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Instructor_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Price_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Rating_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Duration_Filter' );
				register_widget( 'Unicamp_WP_Widget_Course_Sorting' );
				register_widget( 'Unicamp_WP_Widget_Course_Form_Filter' );
			}

			do_action( 'unicamp_widgets_init' );
		}
	}

	Unicamp_WP_Widget_Init::instance()->initialize();
}
