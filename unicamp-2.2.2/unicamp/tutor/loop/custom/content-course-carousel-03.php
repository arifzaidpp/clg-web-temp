<?php
/**
 * A single course loop
 *
 * @package       Unicamp/TutorLMS/Templates
 * @theme-since   1.0.0
 * @theme-version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

do_action( 'tutor_course/loop/before_content' );

/**
 * @hooked tutor_course_loop_header
 * @see    tutor_course_loop_header()
 */
do_action( 'tutor_course/loop/before_header' );
?>
	<div class="tutor-course-header">
		<?php tutor_course_loop_thumbnail(); ?>

		<?php Unicamp_Tutor::instance()->course_loop_price(); ?>
	</div>
<?php
do_action( 'tutor_course/loop/after_header' );

do_action( 'tutor_course/loop/start_content_wrap' );

tutor_load_template( 'loop.custom.level' );

tutor_load_template( 'loop.custom.category' );

do_action( 'tutor_course/loop/before_title' );
do_action( 'tutor_course/loop/title' );
do_action( 'tutor_course/loop/after_title' );
?>
	<div class="course-loop-footer">
		<div class="course-loop-footer-col left">
			<?php tutor_load_template( 'loop.rating-average-02' ); ?>
		</div>
		<div class="course-loop-footer-col right">
			<div class="course-footer-meta-item meta-course-total-enrolled">
				<span class="lead-meta-label">
					<i class="meta-icon far fa-user-alt"></i>
				</span>
				<span
					class="lead-meta-value student-enrolled"><?php echo number_format_i18n( $unicamp_course->get_enrolled_users_count() ); ?></span>
			</div>
		</div>
	</div>
<?php

do_action( 'tutor_course/loop/end_content_wrap' );

do_action( 'tutor_course/loop/after_content' );

tutor_load_template( 'loop.custom.content-grid-quick-view' );
