<?php
/**
 * Quiz class for PRO user.
 *
 * @package TutorPro\Quiz
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 2.2.0
 */

namespace TUTOR_PRO;

use TUTOR\Input;

/**
 * Class Quiz
 *
 * @since 2.2.0
 */
class Quiz {
	/**
	 * Register hooks
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'tutor_quiz_question_data', array( $this, 'extend_question_data' ) );
		add_action( 'tutor_quiz_question_form_after_answer_list', array( $this, 'extend_question_form' ) );
		add_action( 'tutor_quiz_attempt_details_loop_after_row', array( $this, 'correct_answer_explanation_content' ), 10, 2 );
	}

	/**
	 * Add data to question during add/edit quiz question.
	 *
	 * @since 2.2.0
	 *
	 * @param array $data question data.
	 *
	 * @return array question data.
	 */
	public function extend_question_data( $data ) {
		$data['answer_explanation'] = '';

		$question_id = Input::post( 'tutor_quiz_question_id', 0, Input::TYPE_INT );
		if ( $question_id ) {
			$data['answer_explanation'] = Input::post( 'answer_explanation', '', Input::TYPE_KSES_POST );
		}

		return $data;
	}

	/**
	 * Extend quiz question form.
	 *
	 * @since 2.2.0
	 *
	 * @param object $question question object.
	 *
	 * @return void
	 */
	public function extend_question_form( $question ) {
		?>
			<div class="tutor-mt-16 tutor-mb-16">
				<label class="tutor-form-label">
					<?php esc_html_e( 'Answer Explanation', 'tutor-pro' ); ?>
				</label>

				<textarea name="answer_explanation" 
					id="tutor_answer_explanation" class="tutor-form-control">
					<?php echo wp_kses_post( wp_unslash( $question->answer_explanation ?? '' ) ); ?>
				</textarea>

			</div>
		<?php
	}

	/**
	 * Correct answer explanation content.
	 *
	 * @since 2.2.0
	 *
	 * @param object $answer answer object.
	 * @param string $answer_status answer status.
	 *
	 * @return void
	 */
	public function correct_answer_explanation_content( $answer, $answer_status ) {
		if ( strlen( trim( $answer->answer_explanation ) ) > 0 && 'pending' !== $answer_status ) :
			?>
			<tr>
				<td colspan="100%" class="column-empty-state data-td-content" id="tutor-question-<?php echo esc_attr( $answer->question_id ); ?>" style="display:none;">
					<div class="quiz-explanation-wrapper">
						<div class="tutor-mr-16">
							<span class="tutor-icon-circle-mark-line tutor-color-success tutor-fs-5"></span>
						</div>
						<div>
							<p class="tutor-fw-medium tutor-mb-8 tutor-fw-bold"><?php esc_html_e( 'Explanation:', 'tutor-pro' ); ?></p>
							<?php echo wp_kses_post( wp_unslash( $answer->answer_explanation ) ); ?>
						</div>
					</div>
				</td>
			</tr>

			<!-- Toggle expand button -->
			<tr>
				<td colspan="100%" class="expand-btn explain-toggle" data-th="Collapse">
					<button class="tutor-iconic-btn tutor-icon-angle-down has-data-td-target" 
							data-td-target="tutor-question-<?php echo esc_attr( $answer->question_id ); ?>">
					</button>
				</td>
			</tr>
			<!-- End toggle expand button -->
			<?php
		endif;
	}
}
