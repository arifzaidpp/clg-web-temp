<?php
/**
 * Question and answer
 *
 * @author        themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       2.0.0
 *
 * @theme-since   1.0.0
 * @theme-version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$enable_qa_for_this_course = get_post_meta( $post->ID, '_tutor_enable_qa', true );
$enable_q_and_a_on_course  = tutor_utils()->get_option( 'enable_q_and_a_on_course' );
if ( $enable_qa_for_this_course !== 'yes' && ! $enable_q_and_a_on_course ) {
	tutor_load_template( 'single.course.q_and_a_turned_off' );

	return;
}
?>
<?php do_action( 'tutor_course/question_and_answer/before' ); ?>

<div class="tutor-single-course-segment tutor-question-and-answer-wrap">
	<div class="question-and-answer-title-wrap">
		<div class="tutor-question-top">
			<h4 class="tutor-segment-title"><?php esc_html_e( 'Question & Answer', 'unicamp' ); ?></h4>

			<?php Unicamp_Templates::render_button( [
				'link'       => [
					'url' => 'javascript:void(0);',
				],
				'text'       => esc_html__( 'Ask a new question', 'unicamp' ),
				'size'       => 'xs',
				'attributes' => [
					'data-unicamp-toggle' => 'modal',
					'data-unicamp-target' => '#modal-course-qna-add',
				],
			] ); ?>
		</div>
	</div>

	<div class="tutor_question_answer_wrap">
		<?php
		$questions = tutor_utils()->get_qa_questions( 0, 20, $search_term = '', $question_id = null, $meta_query = null, $asker_id = null, $question_status = null, $count_only = false, $args = array( 'course_id' => get_the_ID() ) );

		if ( is_array( $questions ) && count( $questions ) ) {
			foreach ( $questions as $question ) {
				$answers = tutor_utils()->get_qa_answer_by_question( $question->comment_ID );

				$questioner_profile_url = tutor_utils()->profile_url( $question->user_id );

				if ( property_exists( $question, 'meta' ) ) {
					$meta = $question->meta;
				}

				$back_url = isset( $back_url ) ? $back_url : remove_query_arg( 'question_id', tutor()->current_url );

				// Badges data.
				$_user_id = get_current_user_id();
				if ( property_exists( $question, 'user_id' ) ) {
					$is_user_asker = $question->user_id == $_user_id;
				}
				$id_slug      = $is_user_asker ? '_' . $_user_id : '';
				$is_solved    = (int) tutor_utils()->array_get( 'tutor_qna_solved' . $id_slug, $meta, 0 );
				$is_important = (int) tutor_utils()->array_get( 'tutor_qna_important' . $id_slug, $meta, 0 );
				$is_archived  = (int) tutor_utils()->array_get( 'tutor_qna_archived' . $id_slug, $meta, 0 );
				$is_read      = (int) tutor_utils()->array_get( 'tutor_qna_read' . $id_slug, $meta, 0 );

				$modal_id     = 'tutor_qna_delete_single_' . $question_id;
				$reply_hidden = ! wp_doing_ajax() ? 'display:none;' : 0;

				// At first set this as read.
				update_comment_meta( $question_id, 'tutor_qna_read' . $id_slug, 1 );
				?>
				<div class="tutor_original_question">
					<div class="tutor-question-wrap">
						<div class="tutor-question-avatar">
							<a href="<?php echo esc_url( $questioner_profile_url ); ?>"><?php echo unicamp_get_avatar( $question->user_id, 52 ); ?></a>
						</div>
						<div class="tutor-question-info">
							<div class="tutor-question-meta">
								<h4 class="question-user-name"><a
										href="<?php echo esc_url( $questioner_profile_url ); ?>"><?php echo esc_html( $question->display_name ); ?></a>
								</h4>
								<span
									class="question-post-date tutor-text-mute"><?php echo esc_html( sprintf( __( '%s ago', 'unicamp' ), human_time_diff( strtotime
									( $question->comment_date ) ) ) ); ?></span>
							</div>
							<div class="tutor-question-content">
								<div
									class="question-description"><?php echo wpautop( stripslashes( $question->comment_content ) ); ?></div>
							</div>
						</div>
					</div>

					<?php if ( is_array( $answers ) && count( $answers ) ) { ?>
						<div class="tutor_admin_answers_list_wrap">
							<?php
							foreach ( $answers as $answer ) {
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
												<h4 class="question-user-name"><a
														href="<?php echo esc_url( $responder_profile_url ); ?>"><?php echo esc_html( $answer->display_name ); ?></a>
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
								<?php
							}
							?>
						</div>
						<?php
					} ?>
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
				</div>

				<?php
			}
		}
		?>
	</div>

</div>

<?php do_action( 'tutor_course/question_and_answer/after' ); ?>

<div class="unicamp-modal modal-course-qna-add" id="modal-course-qna-add">
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h3 class="modal-title"><?php esc_html_e( 'Ask a Question', 'unicamp' ); ?></h3>
				</div>

				<div class="modal-content-body">
					<form method="post" id="tutor-ask-question-form">
						<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
						<input type="hidden" value="unicamp_qna_create_update" name="action"/>
						<input type="hidden" value="<?php echo esc_attr( get_the_ID() ); ?>" name="course_id"/>
						<input type="hidden" value="0" name="question_id"/>

						<div class="tutor-form-group">
							<textarea name="answer" cols="30" rows="10"></textarea>
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
										'data-unicamp-target'  => '#modal-course-qna-add',
										'data-unicamp-dismiss' => '1',
									],
								] ); ?>
								<div class="tm-button-wrapper">
									<button type="submit"
									        class="tutor-button tutor-success tutor_ask_question_btn"
									        name="tutor_question_search_btn"><?php esc_html_e( 'Post Question', 'unicamp' ); ?> </button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

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
						<input type="hidden" value="<?php echo esc_attr( get_the_ID() ); ?>" name="course_id"/>
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
