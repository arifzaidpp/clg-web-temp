<?php
/**
 * Template for displaying single course
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;
?>

<div class="page-content">

	<?php do_action( 'tutor_course/single/before/wrap' ); ?>

	<div <?php tutor_post_class(); ?>>
		<div class="tutor-full-width-course-body">
			<div class="container">
				<div class="row tm-sticky-parent">
					<div class="col-lg-8">
						<div class="tutor-single-course-main-content tm-sticky-column">

							<?php do_action( 'tutor_course/single/before/inner-wrap' ); ?>

							<div class="tutor-course-top-info">
								<?php tutor_course_lead_info(); ?>

								<div class="tutor-single-course-thumbnail">
									<?php
									if ( $unicamp_course->has_video() ) {
										tutor_course_video();
									} else {
										Unicamp_Image::the_post_thumbnail( [ 'size' => '770x400' ] );
									}
									?>
								</div>
							</div>

							<?php tutor_load_template( 'single.course.custom.tabs-nav-02' ); ?>

							<div id="tutor-course-tab-overview"
							     class="active single-course-tab single-course-tab-overview">
								<?php if ( $unicamp_course->is_enrolled() ) : ?>
									<?php tutor_course_completing_progress_bar(); ?>
								<?php endif; ?>

								<?php Unicamp_Tutor::instance()->course_prerequisites(); ?>
								<?php tutor_course_content(); ?>
								<?php tutor_course_benefits_html(); ?>
								<?php tutor_course_requirements_html(); ?>
								<?php tutor_course_target_audience_html(); ?>
							</div>

							<div id="tutor-course-tab-curriculum"
							     class="single-course-tab single-course-tab-curriculum">
								<?php tutor_course_topics(); ?>
							</div>

							<?php if ( $unicamp_course->is_viewable() ) : ?>
								<div id="tutor-course-tab-resources"
								     class="single-course-tab single-course-tab-resources">
									<?php get_tutor_posts_attachments(); ?>
								</div>

								<div id="tutor-course-tab-question-and-answer"
								     class="single-course-tab single-course-tab-question-and-answer">
									<?php tutor_course_question_and_answer(); ?>
								</div>
							<?php endif; ?>

							<div id="tutor-course-tab-instructors"
							     class="single-course-tab single-course-tab-instructors">
								<?php tutor_course_instructors_html(); ?>
							</div>

							<?php if ( $unicamp_course->is_viewable() ) : ?>
								<div id="tutor-course-tab-announcements"
								     class="single-course-tab single-course-tab-announcements">
									<?php tutor_course_announcements(); ?>
								</div>
							<?php endif; ?>

							<?php if ( $unicamp_course->is_viewable() && $unicamp_course->has_classroom_stream() ) : ?>
								<div id="tutor-course-tab-google-classroom-stream"
								     class="single-course-tab single-course-tab-google-classroom-stream">
									<?php do_action( 'tutor_course/single/enrolled/google-classroom-stream', get_the_ID() ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $unicamp_course->is_viewable() ) : ?>
								<div id="tutor-course-tab-gradebook"
								     class="single-course-tab single-course-tab-gradebook">
									<?php do_action( 'tutor_course/single/enrolled/gradebook', get_the_ID() ); ?>
								</div>
							<?php endif; ?>

							<div id="tutor-course-tab-reviews" class="single-course-tab single-course-tab-reviews">
								<?php tutor_course_target_reviews_html(); ?>
							</div>

							<?php do_action( 'tutor_course/single/after/inner-wrap' ); ?>

						</div>
					</div>
					<div class="col-lg-4">
						<div class="tutor-single-course-sidebar tm-sticky-column">

							<?php do_action( 'tutor_course/single/before/sidebar' ); ?>

							<?php Unicamp_Tutor::instance()->course_enroll_box(); ?>

							<?php do_action( 'tutor_course/single/after/sidebar' ); ?>

							<?php tutor_load_template( 'single.course.custom.sidebar' ); ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php do_action( 'tutor_course/single/after/wrap' ); ?>

</div>
