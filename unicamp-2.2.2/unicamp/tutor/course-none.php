<?php
/**
 * TUTOR Course None
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

$query_args = [
	'post_type'      => Unicamp_Tutor::instance()->get_course_type(),
	'posts_per_page' => 10,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
	'orderby'        => 'date',
	'order'          => 'DESC',
];

$query_args = apply_filters( 'unicamp_archive_course_no_results_query_args', $query_args );

$course_query = new WP_Query( $query_args );
?>
<div class="course-no-result-title">
	<h3 class="primary-heading"><?php esc_html_e( 'Sorry, we can not find any courses for this search.', 'unicamp' ); ?></h3>
	<h4 class="secondary-heading"><?php esc_html_e( 'You may want to check Our Hot Courses:', 'unicamp' ); ?></h4>
</div>

<?php if ( $course_query->have_posts() ) : ?>

	<?php
	global $unicamp_course;
	$unicamp_course_clone = $unicamp_course;
	?>

	<?php tutor_load_template( 'loop.loop-start-grid-5-columns' ); ?>

	<?php while ( $course_query->have_posts() ): $course_query->the_post(); ?>
		<?php
		/***
		 * Setup course object.
		 */
		$unicamp_course = new Unicamp_Course();
		?>
		<?php
		/**
		 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
		 *
		 * @hook   tutor_course/archive/before_loop_course
		 *
		 * @hooked tutor_course_loop_before_content
		 * @see    tutor_course_loop_before_content()
		 */
		do_action( 'tutor_course/archive/before_loop_course' );
		?>

		<?php tutor_load_template( 'loop.custom.content-course-grid-02' ); ?>

		<?php
		/**
		 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
		 *
		 * @hook   tutor_course/archive/after_loop_course
		 *
		 * @hooked tutor_course_loop_after_content
		 * @see    tutor_course_loop_after_content()
		 */
		do_action( 'tutor_course/archive/after_loop_course' );
		?>
	<?php endwhile; ?>

	<?php tutor_course_loop_end(); ?>

	<?php
	/**
	 * Reset course object.
	 */
	$unicamp_course = $unicamp_course_clone;
	?>

	<?php tutor_course_archive_pagination(); ?>
	<?php wp_reset_postdata(); ?>

<?php endif; ?>
