<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Archive_Course' ) ) {
	class Unicamp_Archive_Course {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Course tag template.
			add_filter( 'template_include', [ $this, 'template_include' ], 99 );

			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_filter( 'post_class', [ $this, 'course_post_class' ], 10, 3 );

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

			add_filter( 'unicamp_header_type', [ $this, 'set_header_type' ] );
			add_filter( 'unicamp_header_overlay', [ $this, 'set_header_overlay' ] );
			add_filter( 'unicamp_header_skin', [ $this, 'set_header_skin' ] );

			add_filter( 'unicamp_title_bar_type', [ $this, 'set_title_bar' ] );
			add_filter( 'unicamp_title_bar_heading_text', [ $this, 'archive_title_bar_heading' ] );
			add_filter( 'unicamp_title_bar_heading_text', [ $this, 'category_title_bar_heading' ], 20, 1 );

			add_action( 'unicamp_before_main_content', [ $this, 'add_course_tabs' ], 20 );
			add_action( 'unicamp_before_main_content', [ $this, 'add_featured_courses' ], 20 );
			add_action( 'unicamp_before_main_content', [ $this, 'add_popular_topics' ], 30 );
			add_action( 'unicamp_before_main_content', [ $this, 'add_popular_instructors' ], 40 );

			add_filter( 'unicamp_category_course_tabs', [ $this, 'add_default_course_tabs' ] );
			add_filter( 'unicamp_category_course_tabs', [ $this, 'sort_course_tabs' ], 999 );

			add_filter( 'unicamp_sidebar_1', [ $this, 'set_sidebar_1' ] );
			add_filter( 'unicamp_sidebar_2', [ $this, 'set_sidebar_2' ] );
			add_filter( 'unicamp_sidebar_position', [ $this, 'set_sidebar_position' ] );
			add_filter( 'unicamp_sidebar_1', [ $this, 'disable_sidebar_no_results' ], 20 );
			add_filter( 'unicamp_page_sidebar_class', [ $this, 'sidebar_class' ] );
			add_action( 'unicamp_page_sidebar_after_content', [ $this, 'add_sidebar_filter' ], 10, 2 );
			add_filter( 'unicamp_one_sidebar_width', [ $this, 'one_sidebar_width' ] );
			add_filter( 'unicamp_one_sidebar_offset', [ $this, 'one_sidebar_offset' ] );

			add_action( 'tutor_course/archive/before_loop', [ $this, 'render_filter_bar' ] );
		}

		public function render_filter_bar() {
			ob_start();
			tutor_load_template( 'global.course-archive-filter-bar' );
			$output = apply_filters( 'tutor_course_archive_filter_bar', ob_get_clean() );

			echo $output;
		}

		public function frontend_scripts() {
			if ( Unicamp_Tutor::instance()->is_course_listing() || Unicamp_Tutor::instance()->is_course_category_page() ) {
				wp_enqueue_script( 'unicamp-grid-layout' );
			}
		}

		public function body_class( $classes ) {
			if ( Unicamp_Tutor::instance()->is_archive() ) {
				global $wp_query;

				if ( $wp_query->post_count > 0 ) {
					$layout = Unicamp_Tutor::instance()->get_course_archive_style();

					$classes[] = 'archive-course-style-' . $layout;

					$site_background = Unicamp::setting( 'course_archive_body_background' );
					if ( ! empty( $site_background ) ) {
						$classes[] = 'site-background-' . $site_background;
					}

					$site_layout = Unicamp::setting( 'course_archive_site_layout' );
					if ( 'small' === $site_layout ) {
						$classes[] = 'site-content-small';
					}
				} else {
					$classes[] = 'archive-course-style-grid-02 archive-course-no-results';
				}
			}

			return $classes;
		}

		public function set_header_type( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'course_archive_header_type' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_header_overlay( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'course_archive_header_overlay' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_header_skin( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'course_archive_header_skin' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_title_bar( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'course_archive_title_bar_layout', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function archive_title_bar_heading( $text ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$text = esc_html__( 'Courses', 'unicamp' );
			}

			return $text;
		}

		public function category_title_bar_heading( $text ) {
			if ( Unicamp_Tutor::instance()->is_taxonomy() ) {
				$queried_object = get_queried_object();

				$text = sprintf( esc_html__( '%s Courses', 'unicamp' ), $queried_object->name );
			}

			return $text;
		}

		public function course_post_class( $classes, $class = '', $post_id = 0 ) {
			if ( ! $post_id || ! in_array( get_post_type( $post_id ), array( 'courses' ), true ) ) {
				return $classes;
			}

			global $unicamp_course;

			if ( is_a( $unicamp_course, 'Unicamp_Course' ) ) {

				$price_type = $unicamp_course->get_price_type();

				$classes[] = 'course-' . $price_type;

				if ( $unicamp_course->is_purchasable() ) {
					$classes[] = 'course-purchasable';

					if ( $unicamp_course->is_on_sale() ) {
						$classes[] = 'on-sale';
					}
				}
			}

			return $classes;
		}

		/**
		 * Add course tabs section for category page.
		 */
		public function add_course_tabs() {
			if ( ! Unicamp_Tutor::instance()->is_category() || '1' !== Unicamp::setting( 'course_category_course_tabs' ) ) {
				return;
			}

			tutor_load_template( 'category.course-tabs' );
		}

		/**
		 * Add featured courses section for category page.
		 */
		public function add_featured_courses() {
			if ( ! Unicamp_Tutor::instance()->is_category() || '1' !== Unicamp::setting( 'course_category_featured_courses' ) ) {
				return;
			}

			tutor_load_template( 'category.featured-courses' );
		}

		/**
		 * Add popular topics section for category page.
		 */
		public function add_popular_topics() {
			if ( ! Unicamp_Tutor::instance()->is_category() || '1' !== Unicamp::setting( 'course_category_popular_topics' ) ) {
				return;
			}

			tutor_load_template( 'category.popular-topics' );
		}

		/**
		 * Add popular instructors section for category page.
		 */
		public function add_popular_instructors() {
			if ( ! Unicamp_Tutor::instance()->is_category() || '1' !== Unicamp::setting( 'course_category_popular_instructors' ) ) {
				return;
			}

			tutor_load_template( 'category.popular-instructors' );
		}

		public function add_default_course_tabs( $tabs ) {
			$tabs [] = [
				'title'    => esc_html__( 'Most popular', 'unicamp' ),
				'priority' => 10,
				'callback' => [ $this, 'add_popular_course_tab' ],
			];

			$tabs [] = [
				'title'    => esc_html__( 'Trending', 'unicamp' ),
				'priority' => 20,
				'callback' => [ $this, 'add_trending_course_tab' ],
			];

			return $tabs;
		}

		public function add_popular_course_tab() {
			if ( ! Unicamp_Tutor::instance()->is_category() ) {
				return;
			}

			tutor_load_template( 'category.tabs.popular-course' );
		}

		public function add_trending_course_tab() {
			if ( ! Unicamp_Tutor::instance()->is_category() ) {
				return;
			}

			tutor_load_template( 'category.tabs.trending-course' );
		}

		public function sort_course_tabs( $tabs = array() ) {
			// Make sure the $tabs parameter is an array.
			if ( ! is_array( $tabs ) ) {
				$tabs = array();
			}

			// Re-order tabs by priority.
			if ( ! function_exists( '_sort_priority_callback' ) ) {
				/**
				 * Sort Priority Callback Function
				 *
				 * @param array $a Comparison A.
				 * @param array $b Comparison B.
				 *
				 * @return bool
				 */
				function _sort_priority_callback( $a, $b ) {
					if ( ! isset( $a['priority'], $b['priority'] ) || $a['priority'] === $b['priority'] ) {
						return 0;
					}

					return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
				}
			}

			uasort( $tabs, '_sort_priority_callback' );

			return $tabs;
		}

		public function sidebar_class( $class ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$sidebar_style = Unicamp::setting( 'course_archive_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			return $class;
		}

		public function add_sidebar_filter( $name, $is_first_sidebar ) {
			if ( ! $is_first_sidebar || ! Unicamp_Tutor::instance()->is_course_listing() ) {
				return;
			}
			?>
			<div class="archive-sidebar-filter">
				<p class="widget-title heading archive-sidebar-filter-heading">
					<span><?php esc_html_e( 'Filter by', 'unicamp' ); ?></span></p>
				<?php Unicamp_Sidebar::instance()->generated_sidebar( 'course_sidebar_filters' ); ?>
			</div>
			<?php
		}

		public function one_sidebar_width( $width ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_width = Unicamp::setting( 'course_archive_single_sidebar_width' );

				if ( '' !== $new_width ) {
					return $new_width;
				}
			}

			return $width;
		}

		public function one_sidebar_offset( $offset ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_offset = Unicamp::setting( 'course_archive_single_sidebar_offset' );

				if ( '' !== $new_offset ) {
					return $new_offset;
				}
			}

			return $offset;
		}

		public function set_sidebar_1( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'course_archive_page_sidebar_1', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_sidebar_2( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'course_archive_page_sidebar_2', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_sidebar_position( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_listing() ) {
				$new_value = Unicamp::setting( 'unicamp_sidebar_position', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function disable_sidebar_no_results( $sidebar ) {
			global $wp_query;
			if ( Unicamp_Tutor::instance()->is_archive() && is_main_query() && $wp_query->post_count <= 0 ) {
				return 'none';
			}

			return $sidebar;
		}

		/**
		 * Load archive template for Course Language & Tag
		 *
		 * @param $template
		 *
		 * @return mixed
		 */
		public function template_include( $template ) {
			global $wp_query;

			$post_type       = get_query_var( 'post_type' );
			$course_tag      = get_query_var( 'course-tag' );
			$course_language = get_query_var( 'course-language' );
			$course_location = get_query_var( 'course-location' );

			if ( ( $post_type === Unicamp_Tutor::instance()->get_course_type() || ( ! empty( $course_tag ) || ! empty( $course_language ) || ! empty( $course_location ) ) ) && $wp_query->is_archive ) {
				$template = tutor_get_template( 'archive-course' );
			}

			return $template;
		}
	}
}
