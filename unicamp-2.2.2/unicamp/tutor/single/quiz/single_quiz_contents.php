<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   2.3.0
 * @theme-version 2.3.0
 */

$course = tutor_utils()->get_course_by_quiz( get_the_ID() );
?>
<div class="container">
	<div class="tutor-single-page-top-bar">
		<div class="tutor-topbar-item tutor-top-bar-course-link">
			<a href="<?php echo get_the_permalink( $course->ID ); ?>"
			   class="tutor-topbar-home-btn">
				<i class="far fa-home"></i><?php esc_html_e( 'Go to course home', 'unicamp' ); ?>
			</a>
		</div>
		<div class="tutor-topbar-item tutor-topbar-content-title-wrap">
					<span class="lesson-type-icon">
						<i class="far fa-question-circle"></i>
					</span>
			<?php the_title(); ?>
		</div>
		<div class="tutor-topbar-item tutor-topbar-mark-to-done" style="width: 150px;"></div>
	</div>

	<div class="tutor-quiz-single-wrap tutor-quiz-wrapper">
		<input type="hidden" name="tutor_quiz_id" id="tutor_quiz_id" value="<?php the_ID(); ?>">

		<?php
		if ( $course ) {
			tutor_single_quiz_top();
			tutor_single_quiz_body();
		} else {
			tutor_single_quiz_no_course_belongs();
		}
		?>
	</div>
</div>
