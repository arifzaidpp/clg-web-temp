<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Courses' ) ) {
	class Unicamp_Courses {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Frontend Dashboard
			 */
			add_action( 'wp_ajax_unicamp_delete_dashboard_course', [ $this, 'delete_dashboard_course' ] );

			add_action( 'tutor_save_course_after', [ $this, 'sync_course_duration' ], 10, 2 );

			/**
			 * This hook only working with Classic Editor.
			 */
			add_filter( 'wp_dropdown_users_args', [ $this, 'add_instructor_to_author_dropdown' ], 10, 2 );

			add_filter( 'wp_insert_post_data', [ $this, 'fix_post_author' ], 9999, 2 );

			add_filter( 'tutor_course_level', [ $this, 'change_course_levels' ] );

			// Fix Tag Name.
			add_filter( 'register_taxonomy_args', [ $this, 'fix_tax_tag_name' ], 99, 2 );
		}

		/**
		 * Added hook to prevent trash course.
		 *
		 * @see \TUTOR\Course::tutor_delete_dashboard_course()
		 *
		 * @return mixed|void
		 */
		public function delete_dashboard_course() {
			$course_id = intval( sanitize_text_field( $_POST['course_id'] ) );

			/**
			 * Filters whether a post trashing should take place.
			 *
			 * @since 1.2.2
			 *
			 * @param bool|null $trash Whether to go forward with trashing.
			 * @param int       $course_id
			 */
			$check = apply_filters( 'unicamp_pre_trash_course', null, $course_id );
			if ( null !== $check ) {
				return $check;
			}

			wp_trash_post( $course_id );
			wp_send_json_success( [ 'element' => 'course' ] );
		}

		/**
		 * Tutor save course duration as array serialize.
		 * So we need add extra meta key of duration to filterable.
		 * Convert duration to seconds.
		 */
		public function sync_course_duration( $post_ID, $post ) {
			$course_duration_seconds = 0;

			// Course Duration.
			if ( ! empty( $_POST['course_duration'] ) ) {
				$duration = $_POST['course_duration'];

				$hours = intval( $duration['hours'] );

				if ( $hours > 0 ) {
					$course_duration_seconds += $hours * HOUR_IN_SECONDS;
				}

				$minutes = intval( $duration['minutes'] );

				if ( $minutes > 0 ) {
					$course_duration_seconds += $minutes * MINUTE_IN_SECONDS;
				}

				$seconds = intval( $duration['seconds'] );

				if ( $seconds > 0 ) {
					$course_duration_seconds += $seconds;
				}
			}

			update_post_meta( $post_ID, '_course_duration_in_seconds', $course_duration_seconds );
		}

		public function add_instructor_to_author_dropdown( $query_args, $r ) {
			$screen = get_current_screen();

			$user  = wp_get_current_user();
			$roles = ( array ) $user->roles;

			/**
			 * Only allows administrator or editor assign course author.
			 */
			$allowed_roles = [ 'administrator', 'editor' ];

			if ( $screen->post_type == 'courses' && count( array_intersect( $roles, $allowed_roles ) ) > 0 ):
				// Add instructors.
				$query_args['role__in'] = array_merge( $query_args['role__in'], array( tutor()->instructor_role ) );

				// Unset default role
				unset( $query_args['who'] );
			endif;

			return $query_args;
		}

		/**
		 * Fix administrator can not assign course for an instructor
		 *
		 * @see tutor_add_gutenberg_author()
		 *
		 * @param array $data
		 * @param array $postarr
		 *
		 * @return array
		 */
		public function fix_post_author( $data = array(), $postarr = array() ) {
			$courses_post_type = tutor()->course_post_type;
			$post_type         = $postarr['post_type'];

			if ( $courses_post_type === $post_type ) {
				if ( isset( $_REQUEST['post_author_override'] ) ) {
					$data['post_author'] = intval( $_REQUEST['post_author_override'] );
				} else {
					global $wpdb;
					$post_ID     = (int) tutor_utils()->avalue_dot( 'ID', $postarr );
					$post_author = (int) $wpdb->get_var( "SELECT post_author FROM {$wpdb->posts} WHERE ID = {$post_ID} " );

					if ( $post_author > 0 ) {
						$data['post_author'] = $post_author;
					} else {
						$data['post_author'] = get_current_user_id();
					}
				}
			}

			return $data;
		}

		public function change_course_levels( $levels ) {
			if ( ! isset( $levels['graduate'] ) ) {
				$levels['graduate'] = esc_html__( 'Graduate', 'unicamp' );
			}

			if ( ! isset( $levels['undergraduate'] ) ) {
				$levels['undergraduate'] = esc_html__( 'Undergraduate', 'unicamp' );
			}

			return $levels;
		}

		public function fix_tax_tag_name( $args, $taxonomy ) {
			if ( $taxonomy === Unicamp_Tutor::instance()->get_tax_tag() ) {
				$args['labels']['name'] = _x( 'Course Tags', 'taxonomy general name', 'unicamp' );
			}

			return $args;
		}
	}
}
