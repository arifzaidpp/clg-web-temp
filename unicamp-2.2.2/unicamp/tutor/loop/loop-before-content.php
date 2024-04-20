<?php
/**
 * Course Loop Start
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;
global $post;

if ( empty( $unicamp_course ) ) {
	$unicamp_course = new Unicamp_Course( $post->ID );
}

$unique_id = $unicamp_course->get_unique_id();
?>
<div <?php post_class( 'grid-item' ); ?>>
	<div class="course-loop-wrapper unicamp-box unicamp-tooltip"
	     data-tooltip="<?php echo esc_attr( 'quick-view-' . $unique_id ); ?>">
