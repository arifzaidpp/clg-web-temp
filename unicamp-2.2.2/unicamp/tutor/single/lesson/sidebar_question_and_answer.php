<?php
/**
 * Question and answer
 *
 * @author        themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       1.5.2
 *
 * @theme-since   1.0.0
 * @theme-version 3.0.3
 */
defined( 'ABSPATH' ) || exit;

global $post;
$currentPost = $post;

$course_id = tutor_utils()->get_course_id_by_content( $post );

$disable_qa_for_this_course = get_post_meta( $course_id, '_tutor_enable_qa', true ) != 'yes';
$enable_q_and_a_on_course   = tutor_utils()->get_option( 'enable_q_and_a_on_course' );
if ( ! $enable_q_and_a_on_course || $disable_qa_for_this_course ) {
	tutor_load_template( 'single.course.q_and_a_turned_off' );

	return;
}
?>
<?php do_action( 'tutor_course/question_and_answer/before' ); ?>

<div class="tutor-queston-and-answer-wrap">
	<div class="tutor_question_answer_wrap">
		<?php
		$questions = tutor_utils()->get_qa_questions( 0, 20, $search_term = '', $question_id = null, $meta_query = null, $asker_id = null, $question_status = null, $count_only = false, $args = array( 'course_id' => $course_id ) );

		if ( is_array( $questions ) && count( $questions ) ) {
			foreach ( $questions as $question ) {
				$profile_url = tutor_utils()->profile_url( $question->user_id );
				?>
				<div class="tutor_original_question">
					<div class="tutor-question-wrap">
						<div class="tutor-question-avatar">
							<a href="<?php echo esc_url( $profile_url ); ?>"><?php echo unicamp_get_avatar( $question->user_id, 52 ); ?></a>
						</div>
						<div class="tutor-question-info">
							<div class="tutor-question-meta">
								<h4 class="question-user-name">
									<a href="<?php echo esc_url( $profile_url ); ?>">
										<?php echo esc_html( $question->display_name ); ?>
									</a>
								</h4>
								<span
									class="question-post-date tutor-text-mute"><?php echo esc_html( sprintf( __( '%s ago', 'unicamp' ), human_time_diff( strtotime
									( $question->comment_date ) ) ) ); ?></span>
							</div>

							<div class="tutor-question-content">
								<?php if ( ! empty( $question->question_title ) ): ?>
									<h5 class="question-title"><?php echo esc_html( $question->question_title ); ?></h5>
								<?php endif; ?>
								<div
									class="question-description"><?php echo wpautop( stripslashes( $question->comment_content ) ); ?></div>
							</div>
						</div>
					</div>
				</div>

				<?php
				$answers = tutor_utils()->get_qa_answer_by_question( $question->comment_ID );
				?>
				<?php if ( is_array( $answers ) && count( $answers ) ) : ?>
					<div class="tutor_admin_answers_list_wrap">
						<?php
						foreach ( $answers as $answer ) :
							if ( $answer->comment_parent == 0 ) {
								continue;
							}
							$responder_profile_url = tutor_utils()->profile_url( $answer->user_id );

							$wrapper_class = 'tutor_individual_answer';

							if ( $question->user_id == $answer->user_id ) {
								$wrapper_class .= ' tutor-bg-white';
							} else {
								$wrapper_class .= ' tutor-bg-light';
							}
							?>
							<div class="<?php echo esc_attr( $wrapper_class ); ?>">
								<div class="tutor-question-wrap">
									<div class="tutor-question-avatar">
										<a href="<?php echo esc_url( $responder_profile_url ); ?>"><?php echo unicamp_get_avatar( $answer->user_id, 52 ); ?></a>
									</div>
									<div class="tutor-question-info">
										<div class="tutor-question-meta">
											<h4 class="question-user-name">
												<a href="<?php echo esc_url( $responder_profile_url ); ?>"><?php echo esc_html( $answer->display_name ); ?></a>
											</h4>
											<span
												class="question-post-date tutor-text-mute"><?php echo sprintf( __( '%s ago', 'unicamp' ), human_time_diff( strtotime
												( $answer->comment_date ) ) ); ?></span>
										</div>
										<div class="tutor-question-content">
											<div
												class="question-description"><?php echo wpautop( stripslashes( $answer->comment_content ) ); ?></div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="tutor_add_answer_row">
					<div class="tutor_add_answer_wrap"
					     data-question-id="<?php echo esc_attr( $question->comment_ID ); ?>">

						<?php Unicamp_Templates::render_button( [
							'link'          => [
								'url' => 'javascript:void(0);',
							],
							'text'          => esc_html__( 'Add an answer', 'unicamp' ),
							'size'          => 'xs',
							'attributes'    => [
								'data-unicamp-toggle' => 'modal',
								'data-unicamp-target' => '#modal-course-qna-reply',
								'data-question-id'    => $question->comment_ID,
							],
							'extra_class'   => 'button-secondary-lighten btn-add-an-answer',
							'wrapper_class' => 'tutor_wp_editor_show_btn_wrap',
						] ); ?>
					</div>
				</div>

				<?php
			}
		} else {
			?>

			<div class="tutor-lesson-sidebar-emptyqa-wrap">
				<?php echo Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/lesson-question-answer.svg' ); ?>
				<h3><?php esc_html_e( 'No questions yet', 'unicamp' ); ?></h3>
				<p><?php esc_html_e( 'Be the first to ask your question! You’ll be able to add details in the next step.', 'unicamp' ); ?></p>
			</div>

			<?php
		}
		?>
	</div>

	<div class="tutor-add-question-wrap">

		<h3><?php esc_html_e( 'Ask a new question', 'unicamp' ); ?></h3>

		<form method="post" id="tutor-ask-question-form">
			<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
			<input type="hidden" value="unicamp_qna_create_update" name="action"/>
			<input type="hidden" value="<?php echo esc_attr( $course_id ); ?>" name="course_id"/>
			<input type="hidden" value="0" name="question_id"/>

			<div class="tutor-form-group">
				<?php
				$editor_settings = array(
					'teeny'         => true,
					'media_buttons' => false,
					'quicktags'     => false,
					'editor_height' => 100,
				);
				wp_editor( null, 'answer', $editor_settings );
				?>
			</div>

			<div class="tutor-form-group">
				<button type="submit" class="tutor_ask_question_btn tutor-button tutor-success"
				        name="tutor_question_search_btn"><?php esc_html_e( 'Submit my question', 'unicamp' ); ?></button>
			</div>
		</form>
	</div>

</div>

<?php do_action( 'tutor_course/question_and_answer/after' ); ?>

<div class="unicamp-modal modal-course-qna-reply" id="modal-course-qna-reply">
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h3 class="modal-title"><?php esc_html_e( 'Add an answer', 'unicamp' ); ?></h3>
				</div>

				<div class="modal-content-body">
					<form method="post" id="tutor-reply-question-form">
						<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
						<input type="hidden" value="unicamp_qna_create_update" name="action"/>
						<input type="hidden" value="<?php echo esc_attr( $course_id ); ?>" name="course_id"/>
						<input type="hidden" value="0" name="question_id"/>

						<div class="tutor-form-group">
							<textarea name="answer" cols="30" rows="10"
							          placeholder="<?php esc_attr_e( 'Write your answer here...', 'unicamp' ); ?>"></textarea>
						</div>

						<div class="form-response-messages"></div>

						<div class="tutor-form-group">
							<div class="button-group">
								<?php Unicamp_Templates::render_button( [
									'link'        => [
										'url' => 'javascript:void(0);',
									],
									'text'        => esc_html__( 'Cancel', 'unicamp' ),
									'extra_class' => 'tutor_question_cancel button-grey',
									'attributes'  => [
										'data-unicamp-toggle'  => 'modal',
										'data-unicamp-target'  => '#modal-course-qna-reply',
										'data-unicamp-dismiss' => '1',
									],
								] ); ?>
								<div class="tm-button-wrapper">
									<button type="submit"
									        class="tutor-button tutor-success tutor_ask_question_btn"
									        name="tutor_question_search_btn"><?php esc_html_e( 'Reply', 'unicamp' ); ?> </button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
