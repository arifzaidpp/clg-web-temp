<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Single_Course' ) ) {
	class Unicamp_Single_Course {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_filter( 'unicamp_header_type', [ $this, 'set_header_type' ] );
			add_filter( 'unicamp_header_overlay', [ $this, 'set_header_overlay' ] );
			add_filter( 'unicamp_header_skin', [ $this, 'set_header_skin' ] );

			add_filter( 'unicamp_title_bar_type', [ $this, 'set_title_bar' ] );

			add_filter( 'unicamp/tutor_course/contents/lesson/title', [
				$this,
				'mark_lesson_title_preview',
			], 10, 2 );
		}

		public function body_class( $classes ) {
			if ( Unicamp_Tutor::instance()->is_single_course() ) {
				$style     = Unicamp::setting( 'single_course_layout' );
				$classes[] = "single-course-{$style}";
			}

			return $classes;
		}

		public function set_header_type( $value ) {
			if ( Unicamp_Tutor::instance()->is_single_course() ) {
				$new_value = Unicamp::setting( 'course_single_header_type' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_header_overlay( $value ) {
			if ( Unicamp_Tutor::instance()->is_single_course() ) {
				$new_value = Unicamp::setting( 'course_single_header_overlay' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_header_skin( $value ) {
			if ( Unicamp_Tutor::instance()->is_single_course() ) {
				$new_value = Unicamp::setting( 'course_single_header_skin' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		public function set_title_bar( $value ) {
			if ( Unicamp_Tutor::instance()->is_single_course() ) {
				$new_value = Unicamp::setting( 'course_single_title_bar_layout', '' );

				if ( '' !== $new_value ) {
					return $new_value;
				}
			}

			return $value;
		}

		/**
		 * @param $title
		 * @param $post_id
		 *
		 * @return string
		 *
		 * Mark lesson title preview from this method
		 * @see \TUTOR_CP\CoursePreview::mark_lesson_title_preview()
		 */
		public function mark_lesson_title_preview( $title, $post_id ) {
			$is_preview = (bool) get_post_meta( $post_id, '_is_preview', true );
			if ( $is_preview ) {
				$newTitle = '<span class="lesson-preview-title">' . $title . '</span><span class="lesson-preview-icon "><a class="button btn-lesson-preview" href="' . get_the_permalink( $post_id ) . '">' . esc_html__( 'Preview', 'unicamp' ) . '</a></span>';
			} else {
				$newTitle = '<span class="lesson-preview-title">' . $title . '</span><span class="lesson-preview-icon"><i class="far fa-lock-alt"></i></span>';
			}

			return $newTitle;
		}
	}
}
