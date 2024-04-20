<?php
/**
 * Display action buttons
 *
 * @since   v.1.0.0
 * @author  thememove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;
?>
<div class="tutor-lead-info-btn-group">
	<?php do_action( 'tutor_course/single/actions_btn_group/before' ); ?>

	<?php
	if ( $wp_query->query['post_type'] !== 'lesson' ) {
		$lesson_url        = tutor_utils()->get_course_first_lesson();
		$completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();
		if ( $lesson_url ) { ?>
			<a href="<?php echo esc_url( $lesson_url ); ?>" class="tutor-button tutor-success">
				<?php
				if ( $completed_lessons ) {
					esc_html_e( 'Continue to lesson', 'unicamp' );
				} else {
					esc_html_e( 'Start Course', 'unicamp' );
				}
				?>
			</a>
		<?php }
	}
	?>
	<?php tutor_course_mark_complete_html(); ?>

	<?php do_action( 'tutor_course/single/actions_btn_group/after' ); ?>
</div>
