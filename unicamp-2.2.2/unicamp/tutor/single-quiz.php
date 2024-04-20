<?php
/**
 * Template for displaying single quiz
 *
 * @since         v.1.0.0
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-version 2.3.1
 */

defined( 'ABSPATH' ) || exit;

get_tutor_header();

$course    = tutor_utils()->get_course_by_quiz( get_the_ID() );
$course_id = $course->ID;
?>
	<div class="page-content">

		<?php do_action( 'tutor_quiz/single/before/wrap' ); ?>

		<?php
		$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );
		$wrapper_class         = 'tutor-single-lesson-wrap tutor-course-single-content-wraper';

		if ( $enable_spotlight_mode ) {
			$wrapper_class .= ' tutor-spotlight-mode';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div class="tutor-lesson-sidebar-wrap">
				<?php Unicamp_Single_Lesson::instance()->lessons_sidebar(); ?>
			</div>
			<div id="tutor-single-entry-content" class="tutor-quiz-single-entry-wrap tutor-single-entry-content">
				<input type="hidden" name="tutor_quiz_id" id="tutor_quiz_id" value="<?php the_ID(); ?>">

				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="tutor-single-page-top-bar">
								<div class="tutor-topbar-item tutor-top-bar-course-link">
									<a href="<?php echo get_the_permalink( $course_id ); ?>"
									   class="tutor-topbar-home-btn">
										<i class="far fa-home"></i><?php esc_html_e( 'Go to course home', 'unicamp' ); ?>
									</a>
								</div>
								<div class="tutor-topbar-item tutor-topbar-content-title-wrap">
									<span class="lesson-type-icon">
										<i class="far fa-question-circle"></i>
									</span>
									<?php
									the_title(); ?>
								</div>

								<div class="tutor-topbar-item tutor-topbar-mark-to-done" style="width: 150px;"></div>
							</div>
							<div class="tutor-quiz-single-wrap">
								<input type="hidden" name="tutor_quiz_id" id="tutor_quiz_id" value="<?php the_ID(); ?>">

								<?php
								if ( $course ) {
									tutor_single_quiz_top();
									tutor_single_quiz_content();
									tutor_single_quiz_body();
								} else {
									tutor_single_quiz_no_course_belongs();
								}
								?>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<?php do_action( 'tutor_quiz/single/after/wrap' ); ?>

	</div>
<?php

get_tutor_footer();
