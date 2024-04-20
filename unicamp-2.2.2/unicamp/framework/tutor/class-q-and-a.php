<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Q_And_A' ) ) {
	class Unicamp_Q_And_A {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_unicamp_qna_create_update', [ $this, 'qna_create_update' ] );
		}

		/**
		 * @see \TUTOR\Q_and_A::tutor_qna_create_update()
		 */
		public function qna_create_update() {
			tutor_utils()->checking_nonce();

			global $wpdb;

			$qna_text = wp_kses_post( $_POST['answer'] );
			if ( ! $qna_text ) {
				// Content validation.
				wp_send_json_error( array( 'message' => esc_html__( 'Empty Content Not Allowed!', 'unicamp' ) ) );
			}

			// Prepare course, question info
			$course_id   = (int) sanitize_text_field( $_POST['course_id'] );
			$question_id = (int) sanitize_text_field( $_POST['question_id'] );

			// Prepare user info.
			$user_id = get_current_user_id();
			$user    = get_userdata( $user_id );
			$date    = date( 'Y-m-d H:i:s', tutor_time() );

			// Insert data prepare.
			$data = apply_filters( 'tutor_qna_insert_data', array(
				'comment_post_ID'  => $course_id,
				'comment_author'   => $user->user_login,
				'comment_date'     => $date,
				'comment_date_gmt' => get_gmt_from_date( $date ),
				'comment_content'  => $qna_text,
				'comment_approved' => 'approved',
				'comment_agent'    => 'TutorLMSPlugin',
				'comment_type'     => 'tutor_q_and_a',
				'comment_parent'   => $question_id,
				'user_id'          => $user_id,
			) );

			// Insert new question/answer.
			$wpdb->insert( $wpdb->comments, $data );
			$new_question_id = empty( $question_id ) ? (int) $wpdb->insert_id : $question_id;

			// Mark the question unseen if action made from student.
			$asker_id = $this->get_asker_id( $new_question_id );
			$self     = $asker_id == $user_id;
			update_comment_meta( $new_question_id, 'tutor_qna_read' . ( $self ? '' : '_' . $asker_id ), 0 );

			do_action( 'tutor_after_asked_question', $data );

			if ( ! empty( $question_id ) ) {
				wp_send_json_success( [
					'message' => esc_html__( 'Your answer submit successfully.', 'unicamp' ),
				] );
			} else {
				wp_send_json_success( [
					'message' => esc_html__( 'Your question submit successfully.', 'unicamp' ),
				] );
			}
		}

		/**
		 * Cloned function
		 *
		 * @see \TUTOR\Q_and_A::get_asker_id()
		 */
		private function get_asker_id( $question_id ) {
			global $wpdb;
			$author_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT user_id
			FROM {$wpdb->comments}
			WHERE comment_ID=%d",
					$question_id
				)
			);

			return $author_id;
		}
	}
}
