<?php
/**
 * Quiz Attempts, I attempted to courses
 *
 * @package       Unicamp/TutorLMS/Templates
 * @theme-since   1.0.0
 * @theme-version 2.9.3
 */

defined( 'ABSPATH' ) || exit;

if ( isset( $_GET['view_quiz_attempt_id'] ) ) {
	// Load single attempt details if ID provided.
	tutor_load_template( 'dashboard.my-quiz-attempts.attempts-details' );

	return;
}

$item_per_page = tutor_utils()->get_option( 'pagination_per_page' );
$current_page  = max( 1, tutor_utils()->array_get( 'current_page', $_GET ) );
$offset        = ( $current_page - 1 ) * $item_per_page;

// Filter params.
$course_filter = isset( $_GET['course-id'] ) ? sanitize_text_field( $_GET['course-id'] ) : '';
$order_filter  = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
$date_filter   = isset( $_GET['date'] ) ? $_GET['date'] : '';
$course_id     = isset( $course_id ) ? $course_id : array();

$quiz_attempts       = tutor_utils()->get_quiz_attempts_by_course_ids( $offset, $item_per_page, $course_id, '', $course_filter, $date_filter, $order_filter, get_current_user_id() );
$quiz_attempts_count = tutor_utils()->get_quiz_attempts_by_course_ids( $offset, $item_per_page, $course_id, '', $course_filter, $date_filter, $order_filter, get_current_user_id(), true );
?>
	<h3><?php esc_html_e( 'My Quiz Attempts', 'unicamp' ); ?></h3>

<?php if ( $quiz_attempts_count ) : ?>
	<div class="dashboard-quiz-attempt-history dashboard-table-wrapper dashboard-table-responsive">
		<div class="dashboard-table-container">
			<table class="dashboard-table">
				<thead>
				<tr>
					<th class="col-course-info"><?php esc_html_e( 'Course Info', 'unicamp' ); ?></th>
					<th class="col-correct-answer"><?php esc_html_e( 'Correct Answer', 'unicamp' ); ?></th>
					<th class="col-incorrect-answer"><?php esc_html_e( 'Incorrect Answer', 'unicamp' ); ?></th>
					<th class="col-earned-marks"><?php esc_html_e( 'Earned Marks', 'unicamp' ); ?></th>
					<th class="col-result"><?php esc_html_e( 'Result', 'unicamp' ); ?></th>
					<th class="col-detail-link"></th>
					<?php do_action( 'tutor_quiz/my_attempts/table/thead/col' ); ?>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $quiz_attempts as $attempt ) : ?>
					<?php
					$attempt_action    = tutor_utils()->get_tutor_dashboard_page_permalink( 'my-quiz-attempts/attempts-details/?attempt_id=' . $attempt->attempt_id );
					$earned_percentage = $attempt->earned_marks > 0 ? ( number_format( ( $attempt->earned_marks * 100 ) / $attempt->total_marks ) ) : 0;
					$passing_grade     = (int) tutor_utils()->get_quiz_option( $attempt->quiz_id, 'passing_grade', 0 );
					$answers           = tutor_utils()->get_quiz_answers_by_attempt_id( $attempt->attempt_id );
					?>
					<tr>
						<td>
							<h3 class="course-title">
								<a href="<?php echo get_the_permalink( $attempt->course_id ); ?>"
								   target="_blank"><?php echo esc_html( get_the_title( $attempt->course_id ) ); ?></a>
							</h3>
							<div class="dashboard-quiz-attempt-metas">
								<?php if ( $attempt->attempt_ended_at ): ?>
									<div class="meta-item quiz-attempt-date">
										<?php echo wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $attempt->attempt_ended_at ) ); ?>
									</div>
								<?php endif; ?>
								<div class="meta-item quiz-attempt-question-count">
									<span class="meta-name"><?php esc_html_e( 'Question: ', 'unicamp' ); ?></span>
									<span class="meta-value"><?php echo count( $answers ); ?></span>
								</div>
								<div class="meta-item quiz-attempt-total-marks">
									<span class="meta-name"><?php esc_html_e( 'Total Marks: ', 'unicamp' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $attempt->total_marks ); ?></span>
								</div>
							</div>
						</td>
						<?php
						$correct   = 0;
						$incorrect = 0;
						if ( is_array( $answers ) && count( $answers ) > 0 ) {
							foreach ( $answers as $answer ) {
								if ( (bool) isset( $answer->is_correct ) ? $answer->is_correct : '' ) {
									$correct++;
								} else {
									if ( $answer->question_type === 'open_ended' || $answer->question_type === 'short_answer' ) {
									} else {
										$incorrect++;
									}
								}
							}
						}
						?>
						<td>
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Correct Answer', 'unicamp' ); ?></div>
							<?php echo esc_html( $correct ); ?>
						</td>
						<td>
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Incorrect Answer:', 'unicamp' ); ?></div>
							<?php echo esc_html( $incorrect ); ?>
						</td>
						<td>
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Earned Marks:', 'unicamp' ); ?></div>
							<?php echo esc_html( $attempt->earned_marks . ' (' . $passing_grade . '%)' ); ?>
						</td>
						<td>
							<div class="heading col-heading-mobile"><?php esc_html_e( 'Result:', 'unicamp' ); ?></div>
							<?php
							if ( $attempt->attempt_status === 'review_required' ) {
								echo '<span class="attempt-result attempt-result-review-required">' . esc_html__( 'Under Review', 'unicamp' ) . '</span>';
							} else {
								if ( $earned_percentage >= $passing_grade ) {
									echo '<span class="attempt-result attempt-result-pass">' . esc_html__( 'Pass', 'unicamp' ) . '</span>';
								} else {
									echo '<span class="attempt-result attempt-result-fail">' . esc_html__( 'Fail', 'unicamp' ) . '</span>';
								}
							}
							?>
						</td>
						<td>
							<?php
							$url = add_query_arg( array( 'view_quiz_attempt_id' => $attempt->attempt_id ), tutor()->current_url );
							?>
							<a href="<?php echo esc_url( $url ); ?>"
							   class="link-transition-01 quiz-attempt-detail-link"><?php esc_html_e( 'Details', 'unicamp' ); ?></a>
						</td>
						<?php do_action( 'tutor_quiz/my_attempts/table/tbody/col' ); ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
	$pagination_data = array(
		'total_items' => $quiz_attempts_count,
		'per_page'    => $item_per_page,
		'paged'       => $current_page,
	);
	tutor_load_template( 'dashboard.elements.pagination', [ 'data' => $pagination_data ] );
	?>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'You have not attempted any quiz yet.', 'unicamp' ); ?>
	</div>
<?php endif;
