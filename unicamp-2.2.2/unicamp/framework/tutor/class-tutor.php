<?php
defined( 'ABSPATH' ) || exit;

/**
 * Entry & Utils class for tutor lms.
 */
if ( ! class_exists( 'Unicamp_Tutor' ) ) {
	class Unicamp_Tutor {

		/**
		 * Minimum Tutor LMS version required to run the theme.
		 *
		 * @since 2.5.0
		 *
		 * @var string
		 */
		const MINIMUM_TUTOR_VERSION = '2.0.1';

		/**
		 * Minimum Tutor LMS Pro version required to run the theme.
		 *
		 * @since 2.5.0
		 *
		 * @var string
		 */
		const MINIMUM_TUTOR_PRO_VERSION = '2.0.1';

		const PROFILE_QUERY_VAR = 'tutor_profile_username';

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			require_once UNICAMP_TUTOR_DIR . '/back-compatible.php';
			require_once UNICAMP_TUTOR_DIR . '/course-query.php';
			require_once UNICAMP_TUTOR_DIR . '/course-user.php';
			require_once UNICAMP_TUTOR_DIR . '/class-courses.php';
			require_once UNICAMP_TUTOR_DIR . '/class-course.php';
			require_once UNICAMP_TUTOR_DIR . '/class-lesson.php';
			require_once UNICAMP_TUTOR_DIR . '/class-q-and-a.php';
			require_once UNICAMP_TUTOR_DIR . '/archive-course.php';
			require_once UNICAMP_TUTOR_DIR . '/course-category.php';
			require_once UNICAMP_TUTOR_DIR . '/single-course.php';
			require_once UNICAMP_TUTOR_DIR . '/single-lesson.php';
			require_once UNICAMP_TUTOR_DIR . '/course-builder.php';
			require_once UNICAMP_TUTOR_DIR . '/course-review.php';
			require_once UNICAMP_TUTOR_DIR . '/course-layout-switcher.php';
			require_once UNICAMP_TUTOR_DIR . '/shortcode.php';
			require_once UNICAMP_TUTOR_DIR . '/enqueue.php';
			require_once UNICAMP_TUTOR_DIR . '/cart.php';
			require_once UNICAMP_TUTOR_DIR . '/custom-css.php';
			require_once UNICAMP_TUTOR_DIR . '/sidebar.php';
			require_once UNICAMP_TUTOR_DIR . '/dashboard.php';
			require_once UNICAMP_TUTOR_DIR . '/profile.php';
			require_once UNICAMP_TUTOR_DIR . '/zoom.php';
			require_once UNICAMP_TUTOR_DIR . '/prerequisites.php';
			require_once UNICAMP_TUTOR_DIR . '/certificate.php';
			require_once UNICAMP_TUTOR_DIR . '/instructors.php';
			require_once UNICAMP_TUTOR_DIR . '/updater.php';

			if ( ! $this->is_activated() ) {
				return;
			}

			/**
			 * @since 2.5.0
			 */
			if ( defined( 'TUTOR_VERSION' ) && version_compare( TUTOR_VERSION, self::MINIMUM_TUTOR_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_tutor_version' ] );
			}

			if ( defined( 'TUTOR_PRO_VERSION' ) && version_compare( TUTOR_PRO_VERSION, self::MINIMUM_TUTOR_PRO_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_tutor_pro_version' ] );
			}

			Unicamp_Course_Query::instance()->initialize();
			Unicamp_Course_User::instance()->initialize();
			Unicamp_Courses::instance()->initialize();
			Unicamp_Q_And_A::instance()->initialize();
			Unicamp_Archive_Course::instance()->initialize();
			Unicamp_Course_Category::instance()->initialize();
			Unicamp_Single_Course::instance()->initialize();
			Unicamp_Single_Lesson::instance()->initialize();
			Unicamp_Tutor_Course_Builder::instance()->initialize();
			Unicamp_Tutor_Course_Review::instance()->initialize();
			Unicamp_Course_Layout_Switcher::instance()->initialize();
			Unicamp_Tutor_Enqueue::instance()->initialize();
			Unicamp_Tutor_Cart::instance()->initialize();
			Unicamp_Tutor_Shortcode::instance()->initialize();
			Unicamp_Tutor_Custom_Css::instance()->initialize();
			Unicamp_Tutor_Sidebar::instance()->initialize();
			Unicamp_Tutor_Dashboard::instance()->initialize();
			Unicamp_Tutor_Profile::instance()->initialize();
			Unicamp_Tutor_Zoom::instance()->initialize();
			Unicamp_Tutor_Prerequisites::instance()->initialize();
			Unicamp_Tutor_Certificate::instance()->initialize();
			Unicamp_Tutor_Instructors::instance()->initialize();
			Unicamp_Tutor_Updater::instance()->initialize();
		}

		public function is_activated() {
			if ( defined( 'TUTOR_VERSION' ) ) {
				return true;
			}

			return false;
		}

		public function admin_notice_minimum_tutor_version() {
			unicamp_notice_required_plugin_version( 'Tutor LMS', self::MINIMUM_TUTOR_VERSION );
		}

		public function admin_notice_minimum_tutor_pro_version() {
			unicamp_notice_required_plugin_version( 'Tutor LMS Pro', self::MINIMUM_TUTOR_PRO_VERSION );
		}

		public function get_course_type() {
			return 'courses';
		}

		public function get_lesson_type() {
			return 'lesson';
		}

		public function get_quiz_type() {
			return 'tutor_quiz';
		}

		public function get_assignment_type() {
			return 'tutor_assignments';
		}

		public function get_zoom_meeting_type() {
			return 'tutor_zoom_meeting';
		}

		public function get_course_lesson_types() {
			$types = [
				$this->get_lesson_type(),
				$this->get_quiz_type(),
				$this->get_assignment_type(),
				$this->get_zoom_meeting_type(),
			];

			return $types;
		}

		public function get_tax_category() {
			return 'course-category';
		}

		public function get_tax_tag() {
			return 'course-tag';
		}

		/**
		 * Custom taxonomy by Unicamp
		 *
		 * @return string
		 */
		public function get_tax_language() {
			return 'course-language';
		}

		/**
		 * Custom taxonomy by Unicamp
		 *
		 * @return string
		 */
		public function get_tax_location() {
			return 'course-location';
		}

		/**
		 * Custom taxonomy by Unicamp
		 *
		 * @return string
		 */
		public function get_tax_visibility() {
			return 'course-visibility';
		}

		/**
		 * Get full list of course visibility term ids.
		 *
		 * @return int[]
		 */
		public function get_course_visibility_term_ids() {
			$tax_visibility = $this->get_tax_visibility();

			return array_map(
				'absint',
				wp_parse_args(
					wp_list_pluck(
						get_terms(
							array(
								'taxonomy'   => $tax_visibility,
								'hide_empty' => false,
							)
						),
						'term_taxonomy_id',
						'name'
					),
					array(
						'featured' => 0,
						'rated-1'  => 0,
						'rated-2'  => 0,
						'rated-3'  => 0,
						'rated-4'  => 0,
						'rated-5'  => 0,
					)
				)
			);
		}

		public function is_single_course() {
			return is_singular( $this->get_course_type() );
		}

		public function is_single_lesson() {
			return is_singular( $this->get_lesson_type() );
		}

		public function is_single_quiz() {
			return is_singular( $this->get_quiz_type() );
		}

		public function is_single_assignment() {
			return is_singular( $this->get_assignment_type() );
		}

		public function is_single_zoom_meeting() {
			return is_singular( $this->get_zoom_meeting_type() );
		}

		public function is_course_lesson_type( $type ) {
			if ( in_array( $type, $this->get_course_lesson_types(), true ) ) {
				return true;
			}

			return false;
		}

		public function is_student() {
			if ( ! $this->is_instructor() ) {
				return true;
			}

			return false;
		}

		public function is_instructor() {
			$user_id = get_current_user_id();

			$register_time = get_user_meta( $user_id, '_is_tutor_instructor', true );

			if ( empty( $register_time ) ) {
				return false;
			}

			$instructor_status = get_user_meta( $user_id, '_tutor_instructor_status', true );

			if ( 'approved' !== $instructor_status ) {
				return false;
			}

			return true;
		}

		public function is_pending_instructor() {
			$user_id = get_current_user_id();

			$register_time = get_user_meta( $user_id, '_is_tutor_instructor', true );

			if ( empty( $register_time ) ) {
				return false;
			}

			$instructor_status = get_user_meta( $user_id, '_tutor_instructor_status', true );

			if ( 'pending' !== $instructor_status ) {
				return false;
			}

			return true;
		}

		/**
		 * Check if current page is Dashboard page.
		 */
		public function is_dashboard() {
			global $post;

			$dashboard = tutor_utils()->dashboard_page_id();

			return isset( $post ) && $dashboard === $post->ID ? true : false;
		}

		/**
		 * Check if current page is Create Course page.
		 */
		public function is_create_course() {
			global $wp_query;

			$dashboard_page_name = '';
			if ( isset( $wp_query->query_vars['tutor_dashboard_page'] ) && $wp_query->query_vars['tutor_dashboard_page'] ) {
				$dashboard_page_name = $wp_query->query_vars['tutor_dashboard_page'];
			}

			if ( 'create-course' === $dashboard_page_name ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if current page is Profile page.
		 */
		public function is_profile() {
			global $wp_query;

			if ( ! empty( $wp_query->query[ self::PROFILE_QUERY_VAR ] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Get the instructors page ID
		 *
		 * @return int
		 */
		public function get_instructors_page_id() {
			$page_id = (int) tutor_utils()->get_option( 'instructor_listing_page' );
			$page_id = apply_filters( 'unicamp_instructors_page_id', $page_id );

			return (int) $page_id;
		}

		/**
		 * Check if current page is Instructors listing page.
		 */
		public function is_instructors_page() {
			global $post;

			$instructors_page_id = $this->get_instructors_page_id();

			return isset( $post ) && $instructors_page_id === $post->ID ? true : false;
		}

		/**
		 * Get the category page ID
		 *
		 * @return int
		 */
		public function get_category_page_id() {
			$page_id = (int) tutor_utils()->get_option( 'category_listing_page' );
			$page_id = apply_filters( 'unicamp_course_category_page_id', $page_id );

			return (int) $page_id;
		}

		/**
		 * Check if current page is Instructors listing page.
		 */
		public function is_course_category_page() {
			global $post;

			$category_page_id = $this->get_category_page_id();

			return isset( $post ) && $category_page_id === $post->ID ? true : false;
		}

		/**
		 * @return bool True if is single lesson or quiz
		 */
		public function is_single_lessons() {
			return $this->is_single_lesson() || $this->is_single_quiz() || $this->is_single_assignment() || $this->is_single_zoom_meeting();
		}

		/**
		 * Check if current page is category or tag pages
		 */
		public function is_taxonomy() {
			$taxonomies = get_object_taxonomies( $this->get_course_type() );

			return empty( $taxonomies ) ? false : is_tax( $taxonomies );
		}

		/**
		 * Check if current page is tag pages
		 */
		public function is_tag() {
			return is_tax( $this->get_tax_tag() );
		}

		/**
		 * Check if current page is category pages
		 */
		public function is_category() {
			return is_tax( $this->get_tax_category() );
		}

		/**
		 * Check if current page is archive pages
		 */
		public function is_archive() {
			return $this->is_taxonomy() || is_post_type_archive( $this->get_course_type() );
		}

		public function is_course_listing() {
			global $post;

			if ( $this->is_archive() ) {
				return true;
			}

			if ( ! empty( $post ) && isset( $post->ID ) && $this->get_page_id( 'courses' ) === $post->ID ) {
				return true;
			}

			return false;
		}

		public function get_course_ids_by_current_tax() {
			if ( ! $this->is_taxonomy() ) {
				return;
			}

			$current_tax    = get_queried_object();
			$transient_name = 'unicamp_course_ids_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$ids            = get_transient( $transient_name );

			if ( false === $ids ) {
				$args = [
					'post_type'      => Unicamp_Tutor::instance()->get_course_type(),
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'no_found_rows'  => true,
					'fields'         => 'ids',
					'tax_query'      => array(
						array(
							'taxonomy' => $current_tax->taxonomy,
							'field'    => 'term_id',
							'terms'    => [ $current_tax->term_id ],
						),
					),
				];

				$ids = get_posts( $args );

				set_transient( $transient_name, $ids, 1 * HOUR_IN_SECONDS );
			}

			return $ids;
		}

		public function get_course_listing_base_url() {
			if ( $this->is_taxonomy() ) {
				$queried_object = get_queried_object();
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			} else {
				$link = get_post_type_archive_link( Unicamp_Tutor::instance()->get_course_type() );
			}

			// Layout preset.
			if ( isset( $_GET['course_archive_preset'] ) ) {
				$link = add_query_arg( 'course_archive_preset', Unicamp_Helper::data_clean( wp_unslash( $_GET['course_archive_preset'] ) ), $link );
			}

			return $link;
		}

		/**
		 * Get course listing page URL with various filtering props supported by Unicamp.
		 *
		 * @return string
		 * @since  1.0.0
		 */
		public function get_course_listing_page_url() {
			$link = $this->get_course_listing_base_url();

			// Course category.
			if ( isset( $_GET['filter_course-category'] ) ) {
				$link = add_query_arg( 'filter_course-category', Unicamp_Helper::data_clean( wp_unslash( $_GET['filter_course-category'] ) ), $link );
			}

			// Course language.
			if ( isset( $_GET['filter_course-language'] ) ) {
				$link = add_query_arg( 'filter_course-language', Unicamp_Helper::data_clean( wp_unslash( $_GET['filter_course-language'] ) ), $link );
			}

			// Course location.
			if ( isset( $_GET['filter_course-location'] ) ) {
				$link = add_query_arg( 'filter_course-location', Unicamp_Helper::data_clean( wp_unslash( $_GET['filter_course-location'] ) ), $link );
			}

			// Course level.
			if ( isset( $_GET['level'] ) ) {
				$link = add_query_arg( 'level', Unicamp_Helper::data_clean( wp_unslash( $_GET['level'] ) ), $link );
			}

			// Course duration.
			if ( isset( $_GET['duration'] ) ) {
				$link = add_query_arg( 'duration', Unicamp_Helper::data_clean( wp_unslash( $_GET['duration'] ) ), $link );
			}

			// Course instructor.
			if ( isset( $_GET['instructor'] ) ) {
				$link = add_query_arg( 'instructor', Unicamp_Helper::data_clean( wp_unslash( $_GET['instructor'] ) ), $link );
			}

			// Price filter.
			if ( isset( $_GET['price_type'] ) ) {
				$link = add_query_arg( 'price_type', Unicamp_Helper::data_clean( wp_unslash( $_GET['price_type'] ) ), $link );
			}

			// Order by.
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', Unicamp_Helper::data_clean( wp_unslash( $_GET['orderby'] ) ), $link );
			}

			/**
			 * Search Arg.
			 * Custom args post_title_like
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( isset( $_GET['filter_name'] ) ) {
				$link = add_query_arg( 'filter_name', rawurlencode( wp_specialchars_decode( esc_attr( $_GET['filter_name'] ) ) ), $link );
			}

			// Post Type Arg.
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', Unicamp_Helper::data_clean( wp_unslash( $_GET['post_type'] ) ), $link );

				// Prevent post type and page id when pretty permalinks are disabled.
				if ( Unicamp_Tutor::instance()->is_course_listing() ) {
					$link = remove_query_arg( 'page_id', $link );
				}
			}

			// Min Rating Arg.
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', Unicamp_Helper::data_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
			}

			return apply_filters( 'unicamp_course_get_current_page_url', $link, $this );
		}

		public function get_page_id( $page = '' ) {
			$key = '';
			switch ( $page ) {
				case 'dashboard':
					$key = 'tutor_dashboard_page_id';
					break;
				case 'courses':
					$key = 'course_archive_page';
					break;
			}

			$page = (int) tutor_utils()->get_option( $key );

			return $page ? absint( $page ) : -1;
		}

		/**
		 * Get list of all categories.
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		public function get_categories( $args = array() ) {
			$defaults = array(
				'all' => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			$terms    = get_terms( [
				'taxonomy' => $this->get_tax_category(),
			] );
			$results  = array();

			if ( $args['all'] === true ) {
				$results['-1'] = esc_html__( 'All', 'unicamp' );
			}

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[ $term->slug ] = $term->name;
				}
			}

			return $results;
		}

		/**
		 * Get all categories of current post.
		 *
		 * @return false|WP_Error|WP_Term[]
		 */
		public function get_the_categories() {
			$terms = get_the_terms( get_the_ID(), $this->get_tax_category() );

			return $terms;
		}

		/**
		 * Get the first category of current post.
		 */
		public function get_the_category() {
			$terms = $this->get_the_categories();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return false;
			}

			$term = $terms[0];

			return $term;
		}

		public function get_the_tags() {
			$id    = get_the_ID();
			$terms = get_the_terms( $id, $this->get_tax_tag() );

			return $terms;
		}

		public function get_related_courses( $args ) {
			$defaults = array(
				'post_id'      => '',
				'number_posts' => 3,
			);
			$args     = wp_parse_args( $args, $defaults );

			if ( $args['number_posts'] <= 0 || $args['post_id'] === '' ) {
				return false;
			}

			$related_by = Unicamp::setting( 'course_related_by' );

			if ( empty( $related_by ) ) {
				return false;
			}

			$query_args = array(
				'post_type'      => $this->get_course_type(),
				'posts_per_page' => $args['number_posts'],
				'post_status'    => 'publish',
				'no_found_rows'  => true,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post__not_in'   => array( $args['post_id'] ),
			);

			if ( in_array( 'category', $related_by, true ) ) {
				$terms = $this->get_the_categories();

				if ( $terms && ! is_wp_error( $terms ) ) {

					$term_ids = array();

					foreach ( $terms as $category ) {
						if ( $category->parent === 0 ) {
							$term_ids[] = $category->term_id;
						} else {
							$term_ids[] = $category->parent;
							$term_ids[] = $category->term_id;
						}
					}

					// Remove duplicate values from the array.
					$unique_term_ids = array_unique( $term_ids );

					if ( empty( $query_args['tax_query'] ) ) {
						$query_args['tax_query'] = [];
					}

					$query_args['tax_query'][] = array(
						'taxonomy'         => $this->get_tax_category(),
						'terms'            => $unique_term_ids,
						'include_children' => false,
					);
				}
			}

			if ( in_array( 'tags', $related_by, true ) ) {
				$terms = $this->get_the_tags();

				if ( $terms && ! is_wp_error( $terms ) ) {
					$term_ids = array();

					foreach ( $terms as $tag ) {
						if ( $tag->parent === 0 ) {
							$term_ids[] = $tag->term_id;
						} else {
							$term_ids[] = $tag->parent;
							$term_ids[] = $tag->term_id;
						}
					}

					// Remove duplicate values from the array.
					$unique_term_ids = array_unique( $term_ids );

					if ( empty( $query_args['tax_query'] ) ) {
						$query_args['tax_query'] = [];
					}

					$query_args['tax_query'][] = array(
						'taxonomy'         => $this->get_tax_tag(),
						'terms'            => $unique_term_ids,
						'include_children' => false,
					);
				}
			}

			if ( count( $query_args['tax_query'] ) > 1 ) {
				$query_args['tax_query']['relation'] = 'OR';
			}

			$query = new WP_Query( $query_args );

			wp_reset_postdata();

			return $query;
		}

		public function get_the_price_html() {
			$course_id = get_the_ID();
			?>
			<div class="price">
				<?php
				if ( tutor_utils()->is_course_purchasable() ) {
					$product_id = tutor_utils()->get_course_product_id( $course_id );
					$product    = wc_get_product( $product_id );

					if ( $product ) {
						echo '' . $product->get_price_html();
					}
				} else {
					esc_html_e( 'Free', 'unicamp' );
				}
				?>
			</div>
			<?php

		}

		public function get_course_language( $course_id = 0 ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}
			$terms = get_the_terms( $course_id, $this->get_tax_language() );

			return $terms;
		}

		public function get_course_categories( $offset = 0, $limit = 10 ) {
			$terms = get_terms( [
				'taxonomy'   => $this->get_tax_category(),
				'number'     => $limit,
				'offset'     => $offset,
				'hide_empty' => true,
			] );

			return ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms : false;
		}

		public function entry_course_language() {
			$disable_option = ! (bool) tutor_utils()->get_option( 'enable_course_language', true, true );

			if ( $disable_option ) {
				return;
			}

			$terms = $this->get_course_language();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}
			?>
			<div class="tutor-course-language">
				<span class="meta-label">
					<i class="meta-icon far fa-globe"></i>
					<?php esc_html_e( 'Language', 'unicamp' ); ?>
				</span>
				<div class="meta-value">
					<?php foreach ( $terms as $term ): ?>
						<?php echo esc_html( $term->name ); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}

		public function get_course_category_types( $type = null ) {
			$options = [
				'minor'       => __( 'Minor', 'unicamp' ),
				'major'       => __( 'Major', 'unicamp' ),
				'certificate' => __( 'Certificate', 'unicamp' ),
			];

			$options = apply_filters( 'unicamp_course_category_types', $options );

			if ( $type ) {
				if ( isset( $options[ $type ] ) ) {
					return $options[ $type ];
				} else {
					return '';
				}
			}

			return $options;
		}

		public function get_course_category_types_html( $term_id = 0 ) {
			if ( ! $term_id ) {
				return;
			}

			$selected_types = get_term_meta( $term_id, 'types', true );

			if ( empty( $selected_types ) || ! is_array( $selected_types ) ) {
				return;
			}

			$loop_count        = 0;
			$default_separator = esc_html__( ', ', 'unicamp' );
			?>
			<div class="category-types">
				<?php foreach ( $selected_types as $selected_type ) : ?>
					<?php
					$label = Unicamp_Tutor::instance()->get_course_category_types( $selected_type );
					if ( empty( $label ) ) {
						continue;
					}

					$loop_count++;

					$separator = $loop_count > 1 ? $default_separator : '';

					printf( '%1$s<span>%2$s</span>', $separator, $label );
					?>
				<?php endforeach; ?>
			</div>
			<?php
		}

		public function entry_course_categories() {
			$terms = get_tutor_course_categories();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}
			?>
			<div class="tutor-course-categories">
				<span class="meta-label">
					<i class="meta-icon far fa-tag"></i>
					<?php esc_html_e( 'Subject', 'unicamp' ); ?>
				</span>
				<div class="meta-value">
					<?php
					foreach ( $terms as $course_category ) {
						$category_name = $course_category->name;
						$category_link = get_term_link( $course_category->term_id );
						echo "<a href='$category_link'>$category_name</a>";
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Generate text to avatar
		 * Rewrite plugin function.
		 *
		 * @see        \TUTOR\Utils::get_tutor_avatar()
		 *
		 * @deprecated 2.6.0
		 *
		 * @param null  $user_id
		 * @param mixed $width
		 *
		 * @return string
		 */
		public function get_avatar( $user_id = null, $width = 100 ) {
			_deprecated_function( __METHOD__, '2.6.0', 'unicamp_get_avatar' );

			return unicamp_get_avatar( $user_id, $width );
		}

		public function get_course_price_badge_text( $course_id = null, $format = '-%s' ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}

			$badge_text = '';

			$is_purchasable = tutor_utils()->is_course_purchasable();
			$price          = apply_filters( 'get_tutor_course_price', null, get_the_ID() );

			if ( $is_purchasable && $price ) {
				if ( tutor_utils()->has_wc() ) {
					$product_id = tutor_utils()->get_course_product_id( $course_id );
					$product    = wc_get_product( $product_id );

					if ( $product && $product->is_on_sale() ) {
						if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
							$_regular_price = $product->get_regular_price();
							$_sale_price    = $product->get_sale_price();

							$percentage = round( ( ( $_regular_price - $_sale_price ) / $_regular_price ) * 100 );

							$badge_text = sprintf( $format, "{$percentage}%" );
						} else {
							$badge_text = esc_html__( 'Sale Off', 'unicamp' );
						}
					}
				}
			}

			return $badge_text;
		}

		/**
		 * Replacement function.
		 * To make duration string can be translatable and use full text of time.
		 *
		 * @see get_tutor_course_duration_context()
		 *
		 * @param int $course_id
		 *
		 *
		 * @return bool|string
		 */
		public function get_course_duration_context( $course_id = 0 ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}
			if ( ! $course_id ) {
				return false;
			}
			$duration        = get_post_meta( $course_id, '_course_duration', true );
			$durationHours   = intval( tutor_utils()->avalue_dot( 'hours', $duration ) );
			$durationMinutes = intval( tutor_utils()->avalue_dot( 'minutes', $duration ) );
			$durationSeconds = intval( tutor_utils()->avalue_dot( 'seconds', $duration ) );

			if ( $duration ) {
				$output        = '';
				$total_hours   = 0;
				$total_minutes = 0;

				if ( $durationSeconds > 0 ) {
					$total_minutes += $durationSeconds / 60;
				}

				if ( $durationMinutes > 0 ) {
					$total_minutes += $durationMinutes;
				}

				if ( $durationHours > 0 ) {
					$total_hours += $total_minutes / 60;

					$total_hours += $durationHours;
				}

				if ( $total_hours > 0 ) {
					$total_hours = round( $total_hours, 1 );

					$output .= sprintf( '%s %s', $total_hours, _n( 'hour', 'hours', intval( $total_hours ), 'unicamp' ) );
				} else {
					$total_minutes = round( $total_minutes, 1 );

					if ( $total_minutes > 0 ) {
						$output .= sprintf( '%s %s', $total_minutes, _n( 'minute', 'minutes', intval( $total_minutes ), 'unicamp' ) );
					}
				}

				return $output;
			}

			return false;
		}

		/**
		 * Replacement function.
		 * To make duration string can be translatable.
		 *
		 * @see get_tutor_course_duration_context()
		 *
		 * @param int $course_id
		 *
		 *
		 * @return bool|string
		 */
		public function get_course_duration_short_text( $course_id = 0 ) {
			if ( ! $course_id ) {
				$course_id = get_the_ID();
			}
			if ( ! $course_id ) {
				return false;
			}
			$duration        = get_post_meta( $course_id, '_course_duration', true );
			$durationHours   = intval( tutor_utils()->avalue_dot( 'hours', $duration ) );
			$durationMinutes = intval( tutor_utils()->avalue_dot( 'minutes', $duration ) );
			$durationSeconds = intval( tutor_utils()->avalue_dot( 'seconds', $duration ) );

			if ( $duration ) {
				$output = '';
				if ( $durationHours > 0 ) {
					$output .= sprintf( __( '%1$sh', 'unicamp' ) . ' ', $durationHours );
				}

				if ( $durationMinutes > 0 ) {
					$output .= sprintf( __( '%1$sm', 'unicamp' ) . ' ', $durationMinutes );
				}

				if ( $durationSeconds > 0 ) {
					$output .= sprintf( __( '%1$ss', 'unicamp' ) . ' ', $durationSeconds );
				}

				return $output;
			}

			return false;
		}

		/**
		 * Dropdown categories.
		 *
		 * @param array $args Args to control display of dropdown.
		 */
		public function course_dropdown_categories( $args = array() ) {
			global $wp_query;

			$args = wp_parse_args(
				$args,
				array(
					'pad_counts'         => 1,
					'show_count'         => 1,
					'hierarchical'       => 1,
					'hide_empty'         => 1,
					'show_uncategorized' => 1,
					'orderby'            => 'name',
					'selected'           => isset( $wp_query->query_vars['course-category'] ) ? $wp_query->query_vars['course-category'] : '',
					'show_option_none'   => esc_html__( 'Select a category', 'unicamp' ),
					'option_none_value'  => '',
					'value_field'        => 'slug',
					'taxonomy'           => 'course-category',
					'name'               => 'course-category',
					'class'              => 'dropdown-course-category',
				)
			);

			if ( 'order' === $args['orderby'] ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'order'; // phpcs:ignore
			}

			wp_dropdown_categories( $args );
		}

		/**
		 * Dropdown categories.
		 *
		 * @param array $args Args to control display of dropdown.
		 */
		public function course_dropdown_locations( $args = array() ) {
			global $wp_query;

			$args = wp_parse_args(
				$args,
				array(
					'pad_counts'         => 1,
					'show_count'         => 1,
					'hierarchical'       => 1,
					'hide_empty'         => 1,
					'show_uncategorized' => 1,
					'orderby'            => 'name',
					'selected'           => isset( $wp_query->query_vars['course-location'] ) ? $wp_query->query_vars['course-location'] : '',
					'show_option_none'   => esc_html__( 'Select a location', 'unicamp' ),
					'option_none_value'  => '',
					'value_field'        => 'slug',
					'taxonomy'           => 'course-location',
					'name'               => 'course-location',
					'class'              => 'dropdown-course-location',
				)
			);

			if ( 'order' === $args['orderby'] ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'order'; // phpcs:ignore
			}

			wp_dropdown_categories( $args );
		}

		public function course_loop_price() {
			$is_purchasable = tutor_utils()->is_course_purchasable();
			$price          = apply_filters( 'get_tutor_course_price', null, get_the_ID() );
			$free_text      = __( 'Free', 'unicamp' );

			// Free course.
			if ( is_null( $price ) ) {
				$monetize_by = tutor_utils()->get_option( 'monetize_by' );

				switch ( $monetize_by ) {
					case 'wc':
						if ( tutor_utils()->has_wc() ) {
							$free_text = wc_price( 0 );
						}
						break;
					case 'edd':
						if ( tutor_utils()->has_edd() ) {
							$free_text = edd_currency_filter( edd_format_amount( 0 ) );
						}
						break;
				}
			}
			?>
			<div class="course-loop-price">
				<?php
				if ( $is_purchasable && $price ) {
					echo '<div class="tutor-price">' . $price . '</div>';
				} else {
					?>
					<div class="tutor-price course-free">
						<div
							class="price"><?php echo apply_filters( 'unicamp_course_price_free_html', $free_text ); ?></div>
					</div>
					<?php
				} ?>
			</div>
			<?php
		}

		/**
		 * Get first category of current course.
		 */
		public function course_loop_category() {
			$terms = get_tutor_course_categories();

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}
			?>
			<div class="course-category">
				<?php
				foreach ( $terms as $course_category ) {
					$category_name = $course_category->name;
					$category_link = get_term_link( $course_category->term_id );
					echo "<a href='$category_link'>$category_name</a>";

					break;
				}
				?>
			</div>
			<?php
		}

		/**
		 * Re-write function
		 *
		 * @see tutor_course_enroll_box()
		 *
		 * @param bool $echo
		 *
		 * @return mixed
		 */
		public function course_enroll_box( $echo = true ) {
			global $unicamp_course;

			$is_administrator      = current_user_can( 'administrator' );
			$is_instructor         = tutor_utils()->is_instructor_of_this_course();
			$course_content_access = (bool) get_tutor_option( 'course_content_access_for_ia' );

			ob_start();

			if ( $unicamp_course->is_enrolled() ) {
				tutor_load_template( 'single.course.course-enrolled-box' );
				$output = apply_filters( 'tutor_course/single/enrolled', ob_get_clean() );
			} elseif ( $course_content_access && ( $is_administrator || $is_instructor ) ) {
				tutor_load_template( 'single.course.continue-lesson' );
				$output = apply_filters( 'tutor_course/single/continue_lesson', ob_get_clean() );
			} else {
				tutor_load_template( 'single.course.course-enroll-box' );
				$output = apply_filters( 'tutor_course/single/enroll', ob_get_clean() );
			}

			if ( $echo ) {
				echo '' . $output;
			}

			return $output;
		}

		/**
		 * Re-write function
		 *
		 * @see get_lesson_type_icon()
		 *
		 * @param int  $lesson_id
		 * @param bool $html
		 * @param bool $echo
		 *
		 * @return string
		 */
		public function get_lesson_type_icon( $lesson_id = 0, $html = false, $echo = false ) {
			$post_id = tutor_utils()->get_post_id( $lesson_id );
			$video   = tutor_utils()->get_video_info( $post_id );

			$play_time = false;
			if ( $video ) {
				$play_time = $video->playtime;
			}

			$tutor_lesson_type_icon = $play_time ? 'youtube' : 'document';

			if ( $html ) {
				$tutor_lesson_type_icon = "<i class='tutor-icon-$tutor_lesson_type_icon'></i> ";
			}

			if ( $tutor_lesson_type_icon ) {
				echo '' . $tutor_lesson_type_icon;
			}

			return $tutor_lesson_type_icon;
		}

		/**
		 * Re-write function
		 *
		 * @see \Tutor\Utils::get_video_info()
		 *
		 * @param array $video Get info of given video.
		 * @param int   $post_id
		 *
		 * @return bool|object
		 */
		public function get_video_info( $video, $post_id ) {
			if ( ! $video ) {
				return false;
			}

			$info = array(
				'playtime' => '00:00',
			);

			$types = apply_filters( 'tutor_video_types', array(
				"mp4"  => "video/mp4",
				"webm" => "video/webm",
				"ogg"  => "video/ogg",
			) );

			$videoSource = tutor_utils()->avalue_dot( 'source', $video );

			if ( $videoSource === 'html5' ) {
				$sourceVideoID = tutor_utils()->avalue_dot( 'source_video_id', $video );
				$video_info    = get_post_meta( $sourceVideoID, '_wp_attachment_metadata', true );

				if ( $video_info && in_array( tutor_utils()->array_get( 'mime_type', $video_info ), $types ) ) {
					$path             = get_attached_file( $sourceVideoID );
					$info['playtime'] = $video_info['length_formatted'];
					$info['path']     = $path;
					$info['url']      = wp_get_attachment_url( $sourceVideoID );
					$info['ext']      = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
					$info['type']     = $types[ $info['ext'] ];
				}
			}

			if ( $videoSource !== 'html5' ) {
				$video = maybe_unserialize( get_post_meta( $post_id, '_video', true ) );

				$runtimeHours   = tutor_utils()->avalue_dot( 'runtime.hours', $video );
				$runtimeMinutes = tutor_utils()->avalue_dot( 'runtime.minutes', $video );
				$runtimeSeconds = tutor_utils()->avalue_dot( 'runtime.seconds', $video );

				$runtimeHours   = $runtimeHours ? $runtimeHours : '00';
				$runtimeMinutes = $runtimeMinutes ? $runtimeMinutes : '00';
				$runtimeSeconds = $runtimeSeconds ? $runtimeSeconds : '00';

				if ( '00' === $runtimeHours ) {
					$info['playtime'] = "$runtimeMinutes:$runtimeSeconds";
				} else {
					$info['playtime'] = "$runtimeHours:$runtimeMinutes:$runtimeSeconds";
				}
			}

			$info = array_merge( $info, $video );

			return (object) $info;
		}

		/**
		 * Re-write function
		 *
		 * @see tutor_single_course_add_to_cart()
		 *
		 * Remove login template, use global function.
		 *
		 * @param bool $echo
		 *
		 * @return string
		 *
		 * Get Only add to cart form
		 */
		public function single_course_add_to_cart( $echo = true ) {
			global $unicamp_course;
			$total_enrolled   = $unicamp_course->get_enrolled_users_count();
			$maximum_students = (int) tutor_utils()->get_course_settings( null, 'maximum_students' );

			Unicamp_Debug::write_log($total_enrolled);
			Unicamp_Debug::write_log($maximum_students);

			ob_start();
			$output = '';
			if ( $maximum_students && $maximum_students <= $total_enrolled ) {
				$template = 'closed-enrollment';
			} else {
				$template = 'add-to-cart';
			}

			tutor_load_template( 'single.course.' . $template );
			$output .= apply_filters( 'tutor_course/single/' . $template, ob_get_clean() );

			if ( $echo ) {
				echo '' . $output;
			}

			return $output;
		}

		/**
		 * Clone function get_total_instructors()
		 * Support filter by status
		 *
		 * @param string $search_term
		 * @param null   $status
		 *
		 * @return int
		 */
		public function get_total_instructors( $search_term = '', $status = null ) {
			$meta_key = '_is_tutor_instructor';

			global $wpdb;
			$sql_status_join = $sql_status_where = '';

			if ( $search_term ) {
				$search_term = " AND ( {$wpdb->users}.display_name LIKE '%{$search_term}%' OR {$wpdb->users}.user_email LIKE '%{$search_term}%' ) ";
			}

			if ( $status ) {
				! is_array( $status ) ? $status = array( $status ) : 0;
				$status = array_map( function( $str ) {
					return "'{$str}'";
				}, $status );
				$status = implode( ',', $status );

				$sql_status_join  = " INNER JOIN {$wpdb->usermeta} inst_status ON ( {$wpdb->users}.ID = inst_status.user_id ) ";
				$sql_status_where = " AND inst_status.meta_key='_tutor_instructor_status' AND inst_status.meta_value IN ({$status})";
			}

			$sql_query = "SELECT COUNT({$wpdb->users}.ID) FROM {$wpdb->users} 
				INNER JOIN {$wpdb->usermeta} ON ( {$wpdb->users}.ID = {$wpdb->usermeta}.user_id ) {$sql_status_join} 
				WHERE 1=1 AND ( {$wpdb->usermeta}.meta_key = '{$meta_key}' ) $search_term {$sql_status_where} ";

			$count = $wpdb->get_var( $sql_query );

			return (int) $count;
		}

		/**
		 * @param array $excludes
		 *
		 * @return array|null|object
		 *
		 * Get courses
		 *
		 * @since v.1.0.0
		 */
		public function get_courses_by_ids( $course_ids = array(), $post_status = array( 'publish' ) ) {
			global $wpdb;

			$course_ids     = (array) $course_ids;
			$includes_query = '';
			if ( count( $course_ids ) ) {
				$includes_query = implode( "','", $course_ids );
			}

			$post_status = array_map( function( $element ) {
				return "'" . $element . "'";
			}, $post_status );
			$post_status = implode( ',', $post_status );

			$course_post_type = $this->get_course_type();
			$query            = $wpdb->get_results( "SELECT *
				FROM {$wpdb->posts} WHERE post_status IN ({$post_status})
				AND ID IN('$includes_query')
				AND post_type = '{$course_post_type}' " );

			return $query;
		}

		/**
		 * @see   \Tutor\Utils::get_instructors_by_course()
		 *
		 * @param array $course_ids
		 *
		 * @return array|bool|null|object
		 *
		 * Get all instructors by course ids
		 *
		 * @since v.1.0.0
		 */
		public function get_popular_instructors_by_course_ids( array $course_ids ) {
			global $wpdb;

			$instructors = $wpdb->get_results( "SELECT ID, display_name, 
			get_course.meta_value as taught_course_id,
			tutor_job_title.meta_value as tutor_profile_job_title,
			tutor_bio.meta_value as tutor_profile_bio,
			tutor_photo.meta_value as tutor_profile_photo,
			tutor_total_students.meta_value as tutor_profile_total_students
			FROM {$wpdb->users}
			INNER JOIN {$wpdb->usermeta} get_course ON ID = get_course.user_id AND get_course.meta_key = '_tutor_instructor_course_id' AND get_course.meta_value IN (" . implode( ',', array_map( 'absint', $course_ids ) ) . ")
			LEFT JOIN {$wpdb->usermeta} tutor_job_title ON ID = tutor_job_title.user_id AND tutor_job_title.meta_key = '_tutor_profile_job_title'
			LEFT JOIN {$wpdb->usermeta} tutor_bio ON ID = tutor_bio.user_id AND tutor_bio.meta_key = '_tutor_profile_bio'
			LEFT JOIN {$wpdb->usermeta} tutor_photo ON ID = tutor_photo.user_id AND tutor_photo.meta_key = '_tutor_profile_photo'
			LEFT JOIN {$wpdb->usermeta} tutor_total_students ON ID = tutor_total_students.user_id AND tutor_total_students.meta_key = '_tutor_total_students'
			GROUP BY ID
			ORDER BY tutor_profile_total_students DESC 
			" );

			if ( is_array( $instructors ) && count( $instructors ) ) {
				return $instructors;
			}

			return false;
		}

		/**
		 * Count number of courses of given instructor.
		 *
		 * @param $instructor_id
		 *
		 * @return int
		 */
		public function get_total_courses_by_instructor( $instructor_id ) {
			global $wpdb;

			$sql = "SELECT COUNT( {$wpdb->users}.ID ) FROM {$wpdb->users}";
			$sql .= " INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id AND {$wpdb->usermeta}.meta_key = '_tutor_instructor_course_id'";
			$sql .= " INNER JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->usermeta}.meta_value";
			$sql .= " WHERE {$wpdb->users}.ID = {$instructor_id}";
			$sql .= " AND {$wpdb->posts}.post_type = 'courses' AND {$wpdb->posts}.post_status = 'publish'";

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}

		public function get_popular_instructors_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name      = 'unicamp_course_instructors_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$popular_instructors = get_transient( $transient_name );

			if ( false === $popular_instructors ) {
				$ids                 = Unicamp_Tutor::instance()->get_course_ids_by_current_tax();
				$popular_instructors = [];

				if ( $ids ) {
					$popular_instructors = Unicamp_Tutor::instance()->get_popular_instructors_by_course_ids( $ids );
				}

				set_transient( $transient_name, $popular_instructors, 1 * HOUR_IN_SECONDS );
			}

			return $popular_instructors;
		}

		public function get_featured_courses_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name = 'unicamp_featured_courses_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );

			$featured_courses = get_transient( $transient_name );

			if ( false === $featured_courses ) {
				$featured_courses = [];

				$query_args = [
					'post_type'      => $this->get_course_type(),
					'posts_per_page' => 10,
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'tax_query'      => [
						'relation' => 'AND',
						array(
							'taxonomy' => $current_tax->taxonomy,
							'terms'    => $current_tax->term_id,
						),
						array(
							'taxonomy' => 'course-visibility',
							'field'    => 'slug',
							'terms'    => [ 'featured' ],
						),
					],
				];

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {
					$featured_courses = $query;
				}

				set_transient( $transient_name, $featured_courses, 1 * HOUR_IN_SECONDS );
			}

			return $featured_courses;
		}

		public function get_popular_courses_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name  = 'unicamp_popular_courses_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$popular_courses = get_transient( $transient_name );

			if ( false === $popular_courses ) {
				$popular_courses = [];

				$query_args = [
					'post_type'      => $this->get_course_type(),
					'posts_per_page' => 10,
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'tax_query'      => [
						array(
							'taxonomy' => $current_tax->taxonomy,
							'terms'    => $current_tax->term_id,
						),
					],
					'meta_query'     => [
						'relation' => 'OR',
						array(
							'key'     => '_course_total_enrolls',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => '_course_total_enrolls',
							'compare' => 'EXISTS',
						),
					],
					'order'          => 'DESC',
					'orderby'        => 'meta_value_num',
				];

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {
					$popular_courses = $query;
				}

				set_transient( $transient_name, $popular_courses, 1 * HOUR_IN_SECONDS );
			}

			return $popular_courses;
		}

		public function get_trending_courses_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name   = 'unicamp_trending_courses_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$trending_courses = get_transient( $transient_name );

			if ( false === $trending_courses ) {
				$trending_courses = [];

				$query_args = [
					'post_type'      => $this->get_course_type(),
					'posts_per_page' => 10,
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'tax_query'      => [
						array(
							'taxonomy' => $current_tax->taxonomy,
							'terms'    => $current_tax->term_id,
						),
					],
					'meta_query'     => [
						'relation' => 'OR',
						array(
							'key'     => 'views',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'views',
							'compare' => 'EXISTS',
						),
					],
					'order'          => 'DESC',
					'orderby'        => 'meta_value_num',
				];

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {
					$trending_courses = $query;
				}

				set_transient( $transient_name, $trending_courses, 1 * HOUR_IN_SECONDS );
			}

			return $trending_courses;
		}

		public function get_popular_topics_by_current_tax() {
			$current_tax = get_queried_object();

			$transient_name = 'unicamp_course_tags_by_' . md5( $current_tax->taxonomy . $current_tax->term_id );
			$popular_topics = get_transient( $transient_name );

			if ( false === $popular_topics ) {
				$ids            = Unicamp_Tutor::instance()->get_course_ids_by_current_tax();
				$popular_topics = [];

				if ( $ids ) {
					/**
					 * Because we only query post ID's, the post caches are not updated which is
					 * good and bad
					 *
					 * GOOD -> It saves on resources because we do not need post data or post meta data
					 * BAD -> We loose the vital term cache, which will result in even more db calls
					 *
					 * To solve that, we manually update the term cache with update_object_term_cache
					 */
					//update_object_term_cache( $ids, 'courses' );

					$popular_topics = get_terms( [
						'taxonomy'   => Unicamp_Tutor::instance()->get_tax_tag(),
						'object_ids' => $ids,
						'orderby'    => 'views',
						'order'      => 'DESC',
						'meta_query' => array(
							'relation' => 'OR',
							array(
								'key'     => 'views',
								'value'   => '0',
								'compare' => '>',
								'type'    => 'NUMERIC',
							),
							array(
								'key'     => 'views',
								'compare' => 'NOT EXISTS',
								'value'   => 'null',
							),
						),
					] );

					if ( is_wp_error( $popular_topics ) ) {
						$popular_topics = [];
					}
				}

				set_transient( $transient_name, $popular_topics, 1 * HOUR_IN_SECONDS );
			}

			return $popular_topics;
		}

		public function get_course_sorting_options() {
			$sorting_options = [
				'newest_first'    => __( 'Latest', 'unicamp' ),
				'oldest_first'    => __( 'Oldest', 'unicamp' ),
				'course_title_az' => __( 'Course Title (a-z)', 'unicamp' ),
				'course_title_za' => __( 'Course Title (z-a)', 'unicamp' ),
			];

			return apply_filters( 'unicamp_course_sorting_options', $sorting_options );
		}

		public function get_course_default_sort_option() {
			return apply_filters( 'unicamp_course_default_sorting_option', 'newest_first' );
		}

		public function get_course_archive_layout() {
			$layout = apply_filters( 'unicamp_course_archive_layout', Unicamp::setting( 'course_archive_layout' ) );

			return $layout;
		}

		public function get_course_archive_style() {
			$layout = $this->get_course_archive_layout();

			if ( 'list' === $layout ) {
				$style = Unicamp::setting( 'course_archive_list_style' );
			} else {
				$style = Unicamp::setting( 'course_archive_grid_style' );
			}

			$style = apply_filters( 'unicamp_course_archive_style', $style );

			return $style;
		}

		/**
		 * @param $product_id
		 *
		 * @return array|bool|null|WP_Post
		 */
		public function get_course_by_wc_product( $product_id ) {
			if ( Unicamp_Woo::instance()->is_tutor_product( $product_id ) ) {
				$course_meta = tutor_utils()->product_belongs_with_course( $product_id );

				if ( ! empty( $course_meta ) ) {
					$course_id = $course_meta->post_id;
					$course    = get_post( $course_id );

					return $course;
				}
			}

			return false;
		}

		public function course_prerequisites() {
			if ( ! class_exists( 'TUTOR_PREREQUISITES\init' ) ) {
				return;
			}

			tutor_load_template( 'single.course.course-prerequisites-alt' );
		}

		/**
		 * Get course id by lesson id.
		 * Support for all lesson types as lesson, quiz, assignment..
		 *
		 * This function instead of for
		 *
		 * @see   \Tutor\Utils::get_course_id_by_lesson();
		 * @see   \Tutor\Utils::get_course_id_by_assignment();
		 *
		 * Some case post meta _tutor_course_id_for_xx is empty then it make course permalink is wrong.
		 *
		 * @param $lesson_id
		 *
		 * @return int $course_id
		 *
		 * @since 2.8.0
		 */
		public function get_course_id_by_lessons_id( $lesson_id ) {
			global $wpdb;

			$query_str = $wpdb->prepare( "SELECT topic.post_parent
			FROM 	{$wpdb->posts} AS topic
			INNER JOIN {$wpdb->posts} AS lesson
			ON topic.ID = lesson.post_parent
			WHERE 1 = 1 AND lesson.ID = %d AND lesson.post_status = %s;
			",
				$lesson_id,
				'publish'
			);

			$course_id = $wpdb->get_var( $query_str );

			return intval( $course_id );
		}

		/**
		 * Original function not support multi instructors add-on
		 *
		 * @see   \Tutor\Utils::get_total_students_by_instructor()
		 *
		 * @param $instructor_id
		 *
		 * Get total Students by instructor
		 * 1 enrollment = 1 student, so total enrolled for a equivalent total students (Tricks)
		 *
		 * @return int
		 *
		 * @since v.1.0.0
		 */
		public function get_total_student_enrollments_by_instructor( $instructor_id ) {
			global $wpdb;

			$course_ids       = $this->get_course_ids_by_instructor( $instructor_id );
			$course_post_type = $this->get_course_type();

			$count = $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(enrollment.ID)
			FROM 	{$wpdb->posts} enrollment 
					INNER JOIN {$wpdb->posts} course
							ON enrollment.post_parent = course.ID
			WHERE 	course.ID IN (" . implode( ',', array_map( 'absint', $course_ids ) ) . ")
					AND course.post_type = %s
					AND course.post_status = %s
					AND enrollment.post_type = %s
					AND enrollment.post_status = %s;
			",
				$course_post_type,
				'publish',
				'tutor_enrolled',
				'completed'
			) );

			return absint( $count );
		}

		/**
		 * Get courses ids by a instructor
		 *
		 * @param int   $instructor_id
		 * @param array $post_status
		 *
		 * @return array
		 *
		 * @since      2.7.4
		 */
		public function get_course_ids_by_instructor( $instructor_id = 0, $post_status = array( 'publish' ) ) {
			global $wpdb;

			$instructor_id    = tutor_utils()->get_user_id( $instructor_id );
			$course_post_type = tutor()->course_post_type;

			if ( $post_status === 'any' ) {
				$where_post_status = "";
			} else {
				$post_status       = (array) $post_status;
				$statuses          = "'" . implode( "','", $post_status ) . "'";
				$where_post_status = "AND $wpdb->posts.post_status IN({$statuses}) ";
			}

			$course_ids = $wpdb->get_col( $wpdb->prepare(
				"SELECT $wpdb->posts.ID FROM $wpdb->posts 
						INNER JOIN {$wpdb->usermeta}
							ON $wpdb->usermeta.user_id = %d
						   AND $wpdb->usermeta.meta_key = %s
						   AND $wpdb->usermeta.meta_value = $wpdb->posts.ID 
			WHERE 1 = 1 {$where_post_status}
					AND $wpdb->posts.post_type = %s
			ORDER BY $wpdb->usermeta.umeta_id ASC;
			",
				$instructor_id,
				'_tutor_instructor_course_id',
				$course_post_type
			) );

			return $course_ids;
		}

		public function get_course_ids_by_category_id( $term_id ) {
			$transient_name = 'unicamp_course_ids_by_' . md5( \Unicamp_Tutor::instance()->get_tax_category() . $term_id );
			$ids            = get_transient( $transient_name );

			if ( false === $ids ) {
				$args = [
					'post_type'      => \Unicamp_Tutor::instance()->get_course_type(),
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'no_found_rows'  => true,
					'fields'         => 'ids',
					'tax_query'      => array(
						array(
							'taxonomy' => \Unicamp_Tutor::instance()->get_tax_category(),
							'field'    => 'term_id',
							'terms'    => [ $term_id ],
						),
					),
				];

				$ids = get_posts( $args );

				set_transient( $transient_name, $ids, 1 * HOUR_IN_SECONDS );
			}

			return $ids;
		}

		/**
		 * @param $instructor_id
		 * @param $start
		 * @param $limit
		 *
		 * Get students by instructor
		 *
		 * @return mixed Array of students.
		 *
		 * @since 1.3.1
		 */
		public function get_students_by_instructor( $instructor_id = 0, $start = 0, $limit = 10 ) {
			global $wpdb;
			$my_students = [];

			$instructor_id = tutor_utils()->get_user_id( $instructor_id );

			$my_courses = $this->get_course_ids_by_instructor( $instructor_id );

			// Do nothing if this instructor has no publish courses.
			if ( ! empty( $my_courses ) ) {
				$course_ids       = "'" . implode( "','", $my_courses ) . "'";
				$where_course_ids = "AND enrollment.post_parent IN({$course_ids}) ";

				$students = $wpdb->prepare(
					"SELECT DISTINCT student.* FROM {$wpdb->users} student 
							INNER JOIN {$wpdb->posts} enrollment
									ON enrollment.post_author=student.ID
					WHERE 	1 =1 AND student.ID != %d {$where_course_ids}
							AND enrollment.post_type = %s
							AND enrollment.post_status = %s
					LIMIT 	%d, %d;
					",
					$instructor_id, // Skip my self from list
					'tutor_enrolled',
					'completed',
					$start,
					$limit
				);

				$my_students = $wpdb->get_results( $students );
			}

			return $my_students;
		}

		/**
		 * @param $instructor_id
		 *
		 * Get total Students by instructor
		 * Tutor has same name function. But the original function return total enrollments instead of total students:
		 * 1 enrollment = 1 student, so total enrolled for a equivalent total students (Tricks)
		 *
		 * This function return real student number.
		 *
		 * @return int
		 *
		 * @since 1.3.1
		 */
		public function get_total_students_by_instructor( $instructor_id ) {
			global $wpdb;
			$total_students = 0;

			$instructor_id = tutor_utils()->get_user_id( $instructor_id );

			$my_courses = $this->get_course_ids_by_instructor( $instructor_id );

			// Do nothing if this instructor has no publish courses.
			if ( ! empty( $my_courses ) ) {
				$course_ids       = "'" . implode( "','", $my_courses ) . "'";
				$where_course_ids = "AND enrollment.post_parent IN({$course_ids}) ";

				$sql_query = $wpdb->prepare(
					"SELECT COUNT( DISTINCT student.ID ) FROM {$wpdb->users} student 
							INNER JOIN {$wpdb->posts} enrollment
									ON enrollment.post_author=student.ID
					WHERE 	1 =1 AND student.ID != %d {$where_course_ids}
							AND enrollment.post_type = %s
							AND enrollment.post_status = %s;
					",
					$instructor_id, // Skip my self from list
					'tutor_enrolled',
					'completed'
				);

				$total_students = $wpdb->get_var( $sql_query );
			}

			return (int) $total_students;
		}

		/**
		 * Get all enrolled courses belong to the instructor of a student.
		 *
		 * @param int $student_id
		 * @param int $instructor_id
		 *
		 * @return bool
		 *
		 * @since 1.3.1
		 */
		public function get_enrolled_courses_by_my_student( $student_id = 0, $instructor_id = 0, $start = 0, $limit = 10 ) {
			global $wpdb;

			if ( ! $student_id ) {
				return false;
			}

			$enrolled_courses_by_my_student = [];

			$instructor_id = tutor_utils()->get_user_id( $instructor_id );
			$my_courses    = $this->get_course_ids_by_instructor( $instructor_id );

			// Do nothing if this instructor has no publish courses.
			if ( ! empty( $my_courses ) ) {
				$course_ids       = "'" . implode( "','", $my_courses ) . "'";
				$where_course_ids = "AND course.ID IN({$course_ids}) ";

				$query_string = $wpdb->prepare(
					"SELECT course.* FROM {$wpdb->posts} course 
							INNER JOIN {$wpdb->posts} enrollment
									ON enrollment.post_parent=course.ID
					WHERE 	1 =1 {$where_course_ids}
							AND enrollment.post_author = %d
							AND enrollment.post_type = %s
							AND enrollment.post_status = %s
					LIMIT 	%d, %d;
					",
					$student_id,
					'tutor_enrolled',
					'completed',
					$start,
					$limit
				);

				$enrolled_courses_by_my_student = $wpdb->get_results( $query_string );
			}

			return $enrolled_courses_by_my_student;
		}

		/**
		 * @return mixed
		 *
		 * Get user permalink for dashboard
		 *
		 * @since v.1.0.0
		 */
		public function user_profile_permalinks() {
			$permalinks = array(
				'courses_taken'   => __( 'Courses Taken', 'unicamp' ),
				/*'enrolled_course' => __( 'Enrolled Course', 'unicamp' ),
				'reviews_wrote'   => __( 'Reviews Written', 'unicamp' ),*/
			);

			return apply_filters( 'tutor_public_profile/permalinks', $permalinks );
		}

		public function profile_url( $student_id = 0 ) {
			$site_url   = trailingslashit( home_url() ) . 'profile/';
			$student_id = tutor_utils()->get_user_id( $student_id );
			$user_name  = '';
			if ( $student_id ) {
				global $wpdb;
				$user = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT user_nicename
				FROM 	{$wpdb->users}
				WHERE  	ID = %d;
				",
						$student_id
					)
				);

				if ( $user ) {
					$user_name = $user->user_nicename;
				}
			} else {
				$user_name = 'user_name';
			}

			return $site_url . $user_name;
		}
	}

	Unicamp_Tutor::instance()->initialize();
}
