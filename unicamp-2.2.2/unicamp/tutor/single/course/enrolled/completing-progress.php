<?php
/**
 * Progress bar
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$completed_count = tutor_utils()->get_course_completed_percent();

do_action( 'tutor_course/single/enrolled/before/lead_info/progress_bar' );
?>
<div class="tutor-single-course-segment tutor-course-status">
	<h4 class="tutor-segment-title"><?php esc_html_e( 'Course Status', 'unicamp' ); ?></h4>
	<div class="tutor-progress-bar-wrap">
		<div class="tutor-progress-bar">
			<div class="tutor-progress-filled primary-background"
			     style="<?php echo '--tutor-progress-left: ' . $completed_count . '%;'; ?>"></div>
		</div>
		<span class="tutor-progress-percent"><?php echo esc_html( $completed_count ); ?>
			% <?php esc_html_e( ' Complete', 'unicamp' ) ?></span>
	</div>
</div>

<?php
do_action( 'tutor_course/single/enrolled/after/lead_info/progress_bar' );
?>

