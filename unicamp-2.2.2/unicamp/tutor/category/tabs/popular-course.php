<?php
/**
 * Template for displaying popular course tab
 *
 * @since   1.0.0
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var WP_Query $popular_courses
 */
$popular_courses = Unicamp_Tutor::instance()->get_popular_courses_by_current_tax();

if ( empty( $popular_courses ) ) {
	return;
}
?>

<?php
global $unicamp_course;
$unicamp_course_clone = $unicamp_course;
?>

<?php tutor_load_template( 'loop.custom.loop-carousel-start' ); ?>

<?php while ( $popular_courses->have_posts() ) : $popular_courses->the_post(); ?>
	<?php
	/**
	 * Setup course object.
	 */
	$unicamp_course = new Unicamp_Course();
	?>

	<?php tutor_load_template( 'loop.custom.loop-before-slide-content' ); ?>
	<?php tutor_load_template( 'loop.custom.content-course-carousel-02' ); ?>
	<?php tutor_load_template( 'loop.custom.loop-after-slide-content' ); ?>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>

<?php
/**
 * Reset course object.
 */
$unicamp_course = $unicamp_course_clone;
?>

<?php tutor_load_template( 'loop.custom.loop-carousel-end' ); ?>
