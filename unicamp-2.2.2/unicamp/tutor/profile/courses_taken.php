<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$profile_user_id = $get_user->ID;

$pageposts = tutor_utils()->get_courses_by_instructor( $profile_user_id );
?>
<h3><?php esc_html_e( 'Courses Taken', 'unicamp' ); ?></h3>

<?php if ( $pageposts ): ?>
	<?php
	global $post;
	global $unicamp_course;
	$unicamp_course_clone = $unicamp_course;
	?>

	<?php tutor_load_template( 'loop.custom.loop-grid-start' ); ?>

	<?php foreach ( $pageposts as $post ): setup_postdata( $post ); ?>
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
	<?php endforeach; ?>
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
