<?php
/**
 * Course loop instructor
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

$instructors = $unicamp_course->get_instructors();

if ( empty( $instructors ) ) {
	return;
}

$first_instructor = $instructors[0];

$profile_url = tutor_utils()->profile_url( $first_instructor->ID );
?>
<div class="course-loop-instructor">
	<a class="instructor-name"
	   href="<?php echo esc_url( $profile_url ) ?>"><?php echo esc_html( $first_instructor->display_name ); ?></a>
</div>
