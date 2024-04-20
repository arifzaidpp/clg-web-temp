<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$show_enrolled_course = tutor_utils()->get_option( 'show_courses_completed_by_student' );
if ( ! $show_enrolled_course ) {
	return;
}

$profile_user_id = $get_user->ID;

$my_courses = tutor_utils()->get_enrolled_courses_by_user( $profile_user_id );
?>
<h3><?php esc_html_e( 'Enrolled Courses', 'unicamp' ); ?></h3>

<?php if ( $my_courses && $my_courses->have_posts() ): ?>
	<?php
	global $unicamp_course;
	$unicamp_course_clone = $unicamp_course;
	?>

	<?php tutor_load_template( 'loop.custom.loop-grid-start' ); ?>

	<?php while ( $my_courses->have_posts() ): $my_courses->the_post(); ?>
		<?php
		/***
		 * Setup course object.
		 */
		$unicamp_course = new Unicamp_Course();

		/**
		 * @hook tutor_course/archive/before_loop_course
		 *
		 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
		 */
		do_action( 'tutor_course/archive/before_loop_course' );

		tutor_load_template( 'loop.custom.content-course-grid-01' );

		/**
		 * @hook tutor_course/archive/after_loop_course
		 *
		 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
		 */
		do_action( 'tutor_course/archive/after_loop_course' );
		?>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>

	<?php tutor_load_template( 'loop.custom.loop-grid-end' ); ?>

	<?php
	/**
	 * Reset course object.
	 */
	$unicamp_course = $unicamp_course_clone;
	?>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'Sorry, but you are looking for something that isn\'t here.', 'unicamp' ); ?>
	</div>
<?php endif; ?>
