<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-version 3.0.2
 */

defined( 'ABSPATH' ) || exit;

global $post;
global $next_id;
$course_content_id = get_the_ID();
$course_id         = tutor_utils()->get_course_id_by_subcontent( $course_content_id );

$content_id        = tutor_utils()->get_post_id( $course_content_id );
$contents          = tutor_utils()->get_course_prev_next_contents_by_id( $content_id );
$previous_id       = $contents->previous_id;
$next_id           = $contents->next_id;
$currentPost       = $post;
$quiz_id           = get_the_ID();
$is_started_quiz   = tutor_utils()->is_started_quiz();
$course            = tutor_utils()->get_course_by_quiz( get_the_ID() );
$previous_attempts = tutor_utils()->quiz_attempts();
$attempted_count   = is_array( $previous_attempts ) ? count( $previous_attempts ) : 0;

$attempts_allowed  = tutor_utils()->get_quiz_option( get_the_ID(), 'attempts_allowed', 0 );
$passing_grade     = tutor_utils()->get_quiz_option( get_the_ID(), 'passing_grade', 0 );
$attempt_remaining = (int) $attempts_allowed - (int) $attempted_count;

do_action( 'tutor_quiz/single/before/top' );
?>
	<div class="tutor-quiz-header">
		<h2 class="entry-quiz-title"><?php echo get_the_title(); ?></h2>
		<h5 class="entry-quiz-course-title">
			<?php esc_html_e( 'Course', 'unicamp' ); ?>:
			<a href="<?php echo get_the_permalink( $course->ID ); ?>"><?php echo get_the_title( $course->ID ); ?></a>
		</h5>
		<div class="entry-quiz-description">
			<?php echo get_the_content(); ?>
		</div>
		<ul class="tutor-quiz-meta">
			<?php
			$total_questions = tutor_utils()->total_questions_for_student_by_quiz( get_the_ID() );
			$time_limit      = intval( tutor_utils()->get_quiz_option( get_the_ID(), 'time_limit.time_value' ) );
			?>

			<?php if ( $total_questions ) : ?>
				<li>
					<span class="meta-label"><?php esc_html_e( 'Questions', 'unicamp' ); ?></span>
					<span class="meta-value"><?php echo esc_html( $total_questions ); ?></span>
				</li>
			<?php endif; ?>

			<?php if ( $time_limit ) : ?>
				<?php
				$time_type = tutor_utils()->get_quiz_option( get_the_ID(), 'time_limit.time_type' );

				$available_time_type = array(
					'seconds' => _n( 'Second', 'Seconds', $time_limit, 'unicamp' ),
					'minutes' => _n( 'Minute', 'Minutes', $time_limit, 'unicamp' ),
					'hours'   => _n( 'Hour', 'Hours', $time_limit, 'unicamp' ),
					'days'    => _n( 'Day', 'Days', $time_limit, 'unicamp' ),
					'weeks'   => _n( 'Week', 'Weeks', $time_limit, 'unicamp' ),
				);

				$time_type_str = isset( $available_time_type[ $time_type ] ) ? $available_time_type[ $time_type ] : $time_type;
				?>
				<li>
					<span class="meta-label"><?php esc_html_e( 'Time', 'unicamp' ); ?></span>
					<span
						class="meta-value"><?php echo esc_html( $time_limit . ' ' . $time_type_str ); ?></span>
				</li>
			<?php endif; ?>

			<li>
				<span class="meta-label"><?php esc_html_e( 'Attempts Allowed', 'unicamp' ); ?></span>
				<span class="meta-value">
					<?php echo 0 === $attempts_allowed ? esc_html__( 'No limit', 'unicamp' ) : $attempts_allowed; ?>
				</span>
			</li>

			<?php if ( $attempted_count ) : ?>
				<li>
					<span class="meta-label"><?php esc_html_e( 'Attempted', 'unicamp' ); ?></span>
					<span class="meta-value"><?php echo esc_html( $attempted_count ); ?></span>
				</li>
			<?php endif; ?>

			<?php if ( $attempts_allowed > 0 ) : ?>
				<li>
					<span class="meta-label"><?php esc_html_e( 'Attempts Remaining', 'unicamp' ); ?></span>
					<span class="meta-value">
						<?php echo 0 === $attempt_remaining ? esc_html__( 'No limit', 'unicamp' ) : $attempt_remaining; ?>
					</span>
				</li>
			<?php endif; ?>

			<?php if ( $passing_grade ): ?>
				<li>
					<span class="meta-label"><?php esc_html_e( 'Passing Grade', 'unicamp' ); ?></span>
					<span class="meta-value"><?php echo esc_html( $passing_grade . '%' ); ?></span>
				</li>
			<?php endif; ?>
		</ul>
		<?php
		if ( ( ! $is_started_quiz && $attempted_count == 0 ) && $attempt_remaining > 0 || $attempts_allowed == 0 ) {
			do_action( 'tutor_quiz/start_form/before', $quiz_id );
			$skip_url = get_the_permalink( $next_id ? $next_id : $course_id );
			?>
			<div class="tutor-quiz-btn-grp">
				<form id="tutor-start-quiz" method="post">
					<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>

					<input type="hidden" value="<?php echo $quiz_id; ?>" name="quiz_id"/>
					<input type="hidden" value="tutor_start_quiz" name="tutor_action"/>

					<button type="submit" class="tutor-btn tutor-btn-primary tutor-btn-md start-quiz-btn"
					        name="start_quiz_btn" value="start_quiz">
						<?php esc_html_e( 'Start Quiz', 'unicamp' ); ?>
					</button>
				</form>

				<button class="tutor-btn tutor-btn-disable-outline tutor-no-hover tutor-btn-md skip-quiz-btn"
				        data-tutor-modal-target="tutor-quiz-skip-to-next">
					<?php esc_html_e( 'Skip Quiz', 'unicamp' ); ?>
				</button>

				<div id="tutor-quiz-skip-to-next" class="tutor-modal">
					<span class="tutor-modal-overlay"></span>
					<button data-tutor-modal-close class="tutor-modal-close">
						<span class="tutor-icon-line-cross-line"></span>
					</button>
					<div class="tutor-modal-root">
						<div class="tutor-modal-inner">
							<div class="tutor-modal-body tutor-text-center">
								<div class="tutor-modal-icon">
									<!-- <img src="<?php echo tutor()->url; ?>assets/images/icon-trash.svg" /> -->
								</div>
								<div class="tutor-modal-text-wrap">
									<h3 class="tutor-modal-title">
										<?php esc_html_e( 'Skip This Quiz?', 'unicamp' ); ?>
									</h3>
									<p>
										<?php esc_html_e( 'Are you sure you want to skip this quiz? Please confirm your choice.', 'unicamp' ); ?>
									</p>
								</div>
								<div class="tutor-modal-btns tutor-btn-group">
									<button data-tutor-modal-close class="tutor-btn tutor-is-outline tutor-is-default">
										<?php esc_html_e( 'Cancel', 'unicamp' ); ?>
									</button>
									<a class="tutor-btn" href="<?php echo $skip_url; ?>">
										<?php esc_html_e( 'Yes, Skip This', 'unicamp' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
<?php do_action( 'tutor_quiz/single/after/top' );
