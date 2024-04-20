<?php
/**
 * Template for displaying course requirements
 *
 * @since         v.1.0.0
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   2.0.0
 * @theme-version 2.0.0
 */
defined( 'ABSPATH' ) || exit;

$course_requirements = tutor_course_requirements();

if ( empty( $course_requirements ) || ! is_countable( $course_requirements ) ) {
	return;
}
?>
<?php do_action( 'tutor_course/single/before/requirements' ); ?>

<div class="tutor-single-course-segment  tutor-course-requirements-wrap">
	<div class="course-requirements-title">
		<h4 class="tutor-segment-title"><?php esc_html_e( 'Requirements', 'unicamp' ); ?></h4>
	</div>

	<div class="tutor-course-requirements-content">
		<ul class="tutor-course-requirements-items tutor-custom-list-style">
			<?php
			foreach ( $course_requirements as $requirement ) {
				echo '<li>' . $requirement . '</li>';
			}
			?>
		</ul>
	</div>
</div>

<?php do_action( 'tutor_course/single/after/requirements' ); ?>
