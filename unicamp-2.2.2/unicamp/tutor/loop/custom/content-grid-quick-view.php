<?php
/**
 * Course quick view
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

$unique_id = $unicamp_course->get_unique_id();
?>
<div id="<?php echo 'quick-view-' . $unique_id; ?>" class="course-loop-quick-view">

	<?php
	tutor_load_template( 'loop.custom.category' );

	do_action( 'tutor_course/loop/before_title' );
	do_action( 'tutor_course/loop/title' );
	do_action( 'tutor_course/loop/after_title' );

	tutor_load_template( 'loop.rating-average' );

	tutor_load_template( 'loop.custom.meta' );

	tutor_load_template( 'loop.custom.benefits' );

	tutor_load_template( 'loop.custom.get-enrolled-button' );

	tutor_load_template( 'loop.custom.wishlist-button' );
	?>
</div>
