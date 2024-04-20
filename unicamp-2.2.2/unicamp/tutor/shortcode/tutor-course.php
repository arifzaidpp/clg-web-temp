<?php
/**
 * @package  TutorLMS/Templates
 * @version  1.4.3
 *
 * @package  Unicamp
 * @override 1.3.6
 */

defined( 'ABSPATH' ) || exit;

$column_per_row    = $GLOBALS['tutor_shortcode_arg']['column_per_row'];
$course_per_page   = $GLOBALS['tutor_shortcode_arg']['course_per_page'];
$course_filter     = $GLOBALS['tutor_shortcode_arg']['include_course_filter'] === null ? (bool) tutor_utils()->get_option( 'course_archive_filter', false ) : $GLOBALS['tutor_shortcode_arg']['include_course_filter'];
$supported_filters = tutor_utils()->get_option( 'supported_course_filters', array() );

if ( have_posts() ) :
	global $unicamp_course;
	$unicamp_course_clone = $unicamp_course;

	tutor_course_loop_start();

	while ( have_posts() ) : the_post();
		/***
		 * Setup course object.
		 */
		$unicamp_course = new Unicamp_Course();

		/**
		 * @hook tutor_course/archive/before_loop_course
		 * @type action
		 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
		 */
		do_action( 'tutor_course/archive/before_loop_course' );

		tutor_load_template( 'loop.course' );

		/**
		 * @hook tutor_course/archive/after_loop_course
		 * @type action
		 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
		 */
		do_action( 'tutor_course/archive/after_loop_course' );
	endwhile;

	tutor_course_loop_end();

	/**
	 * Reset course object.
	 */
	$unicamp_course = $unicamp_course_clone;

endif;
