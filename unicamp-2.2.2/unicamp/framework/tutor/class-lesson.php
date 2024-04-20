<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Lesson' ) ) {
	class Unicamp_Lesson {

		protected $video       = null;
		protected $lesson_type = null;

		public function __construct() {
		}

		public function get_id() {
			return get_the_ID();
		}

		public function get_video() {
			if ( null === $this->video ) {
				$this->video = tutor_utils()->get_video();
			}

			return $this->video;
		}

		public function get_type() {
			if ( null === $this->lesson_type ) {
				$video      = $this->get_video();
				$post_id    = $this->get_id();
				$video_info = Unicamp_Tutor::instance()->get_video_info( $video, $post_id );

				$play_time = false;
				if ( $video_info ) {
					$play_time = $video_info->playtime;
				}

				$this->lesson_type = $play_time ? 'video' : 'document';
			}

			return $this->lesson_type;
		}
	}
}

add_action( 'template_redirect', 'unicamp_setup_lesson_object' );

function unicamp_setup_lesson_object() {
	if ( ! is_singular( 'lesson' ) ) {
		return;
	}

	/**
	 * @var Unicamp_Lesson $unicamp_lesson
	 */
	global $unicamp_lesson;

	$unicamp_lesson = new Unicamp_Lesson();
}
