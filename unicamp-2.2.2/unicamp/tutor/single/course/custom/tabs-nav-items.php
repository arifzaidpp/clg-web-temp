<?php
/**
 * Display tabs nav
 *
 * @since   v.1.0.0
 * @author  thememove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var Unicamp_Course $unicamp_course
 */
global $unicamp_course;

/**
 * @var WP_Query $topics
 */
$topics = $unicamp_course->get_topics();

$course_nav_items = apply_filters( 'tutor_course/single/enrolled/nav_items', [
	'questions'     => __( 'Q&A', 'unicamp' ),
	'announcements' => __( 'Announcements', 'unicamp' ),
] );
?>
<li class="active">
	<a href="#tutor-course-tab-overview"><?php esc_html_e( 'Overview', 'unicamp' ); ?></a>
</li>
<?php if ( $topics->have_posts() ): ?>
	<li>
		<a href="#tutor-course-tab-curriculum"><?php esc_html_e( 'Curriculum', 'unicamp' ); ?></a>
	</li>
<?php endif; ?>
<?php if ( $unicamp_course->is_viewable() && ! empty( $unicamp_course->get_attachments() ) ): ?>
	<li>
		<a href="#tutor-course-tab-resources"><?php esc_html_e( 'Resources', 'unicamp' ); ?></a>
	</li>
<?php endif; ?>
<?php if ( $unicamp_course->is_viewable() ): ?>
	<li>
		<a href="#tutor-course-tab-question-and-answer"><?php esc_html_e( 'Question & Answer', 'unicamp' ); ?></a>
	</li>
<?php endif; ?>
<li>
	<a href="#tutor-course-tab-instructors"><?php esc_html_e( 'Instructors', 'unicamp' ); ?></a>
</li>
<?php if ( $unicamp_course->is_viewable() ): ?>
	<li>
		<a href="#tutor-course-tab-announcements"><?php esc_html_e( 'Announcements', 'unicamp' ); ?></a>
	</li>
<?php endif; ?>
<?php if ( $unicamp_course->is_viewable() && isset( $course_nav_items['google-classroom-stream'] ) ): ?>
	<li>
		<a href="#tutor-course-tab-google-classroom-stream"><?php echo esc_html( $course_nav_items['google-classroom-stream'] ); ?></a>
	</li>
<?php endif; ?>
<?php if ( $unicamp_course->is_viewable() && isset( $course_nav_items['gradebook'] ) ): ?>
	<li>
		<a href="#tutor-course-tab-gradebook"><?php echo esc_html( $course_nav_items['gradebook'] ); ?></a>
	</li>
<?php endif; ?>
<?php if ( $unicamp_course->get_reviews() ) : ?>
	<li>
		<a href="#tutor-course-tab-reviews"><?php esc_html_e( 'Reviews', 'unicamp' ); ?></a>
	</li>
<?php endif; ?>
