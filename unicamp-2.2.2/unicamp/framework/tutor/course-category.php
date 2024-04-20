<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Course_Category' ) ) {
	class Unicamp_Course_Category {

		protected static $instance = null;

		//protected static $is_course_category_page = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'template_include', [ $this, 'template_course_category' ], 99 );

			add_filter( 'tutor/options/extend/attr', [ $this, 'add_instructor_setting' ] );

			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_filter( 'unicamp_header_type', [ $this, 'set_header_type' ], 99 );
			add_filter( 'unicamp_header_overlay', [ $this, 'set_header_overlay' ], 99 );
			add_filter( 'unicamp_header_skin', [ $this, 'set_header_skin' ], 99 );
			add_filter( 'unicamp_title_bar_type', [ $this, 'set_title_bar' ], 99 );
			add_filter( 'unicamp_sidebar_1', [ $this, 'set_sidebar_1' ], 99 );
			add_filter( 'unicamp_sidebar_2', [ $this, 'set_sidebar_2' ], 99 );
			add_filter( 'unicamp_sidebar_position', [ $this, 'set_sidebar_position' ], 99 );
			add_filter( 'unicamp_one_sidebar_width', [ $this, 'set_sidebar_single_width' ], 99 );
			add_filter( 'unicamp_one_sidebar_offset', [ $this, 'set_sidebar_single_offset' ], 99 );
			add_filter( 'unicamp_page_sidebar_class', [ $this, 'set_sidebar_class' ], 99 );
			// Add inner sidebar in main sidebar.
			add_action( 'unicamp_page_sidebar_after_content', [ $this, 'add_sidebar_filter' ], 10, 2 );
		}

		public function add_instructor_setting( $setting ) {
			$pages = tutor_utils()->get_pages();

			$new_setting_fields = [
				[
					'key'     => 'category_listing_page',
					'type'    => 'select',
					'label'   => __( 'Category Listing Page', 'unicamp' ),
					'default' => '0',
					'options' => $pages,
					'desc'    => __( 'This page will be used to show all course categories', 'unicamp' ),
				],
				[
					'key'     => 'category_per_page',
					'type'    => 'number',
					'label'   => __( 'Category Listing Pagination', 'unicamp' ),
					'default' => '12',
					'desc'    => __( 'Number of categories you would like displayed "per page" in the pagination', 'unicamp' ),
				],
			];

			$general_blocks = $setting['general']['blocks'];

			foreach ( $general_blocks as $key => $general_block ) {
				if ( 'general-page' === $general_block['slug'] ) {
					$setting['general']['blocks'][ $key ]['fields'] = array_merge( $general_block['fields'], $new_setting_fields );

					break;
				}
			}

			return $setting;
		}

		public function template_course_category( $template ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$template = tutor_get_template( 'category' );
			}

			return $template;
		}

		public function body_class( $classes ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$classes[] = ' course-category-listing-page';

				$site_background = Unicamp::setting( 'course_category_listing_body_background' );
				if ( ! empty( $site_background ) ) {
					$classes[] = 'site-background-' . $site_background;
				}

				$site_layout = Unicamp::setting( 'course_category_listing_site_layout' );
				if ( 'small' === $site_layout ) {
					$classes[] = 'site-content-small';
				}

				$filtering_bar_on = Unicamp::setting( 'course_category_listing_filtering' );

				if ( '1' === $filtering_bar_on ) {
					$classes[] = 'page-has-filtering-bar';
				}
			}

			return $classes;
		}

		public function set_header_type( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_header_type' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_header_overlay( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_header_overlay' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_header_skin( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_header_skin' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_title_bar( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_title_bar_layout', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_sidebar_1( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_page_sidebar_1', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_sidebar_2( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_page_sidebar_2', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_sidebar_position( $value ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_value = Unicamp::setting( 'course_category_listing_page_sidebar_position', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_sidebar_single_width( $width ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_width = Unicamp::setting( 'course_category_listing_single_sidebar_width' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_width ) && '' !== $new_width ) {
				return $new_width;
			}

			return $width;
		}

		public function set_sidebar_single_offset( $offset ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$new_offset = Unicamp::setting( 'course_category_listing_single_sidebar_offset' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_offset ) && '' !== $new_offset ) {
				return $new_offset;
			}

			return $offset;
		}

		public function set_sidebar_class( $class ) {
			if ( Unicamp_Tutor::instance()->is_course_category_page() ) {
				$sidebar_style = Unicamp::setting( 'course_category_listing_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			return $class;
		}

		public function add_sidebar_filter( $name, $is_first_sidebar ) {
			if ( ! $is_first_sidebar || ! Unicamp_Tutor::instance()->is_course_category_page() ) {
				return;
			}
			?>
			<div class="archive-sidebar-filter">
				<p class="widget-title heading archive-sidebar-filter-heading">
					<span><?php esc_html_e( 'Filter by', 'unicamp' ); ?></span></p>
				<?php Unicamp_Sidebar::instance()->generated_sidebar( 'course_category_listing_sidebar_filters' ); ?>
			</div>
			<?php
		}
	}
}
