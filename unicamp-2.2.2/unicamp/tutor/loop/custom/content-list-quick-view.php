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

$benefits = $unicamp_course->get_benefits();

if ( empty( $benefits ) ) {
	return;
}

$unique_id = $unicamp_course->get_unique_id();
?>
<div id="<?php echo 'quick-view-' . $unique_id; ?>" class="course-loop-quick-view">
	<?php tutor_load_template( 'loop.custom.benefits' ); ?>
</div>
