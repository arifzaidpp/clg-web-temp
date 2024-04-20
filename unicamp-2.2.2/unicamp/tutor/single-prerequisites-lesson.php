<?php
/**
 * Template for displaying single prerequisites lesson
 *
 * @author        ThemeMove
 * @package       Unicamp/TutorLMS/Templates
 * @theme-since   2.4.2
 * @theme-version 2.4.2
 */

defined( 'ABSPATH' ) || exit;

get_tutor_header();

global $post;
$currentPost = $post;

$course_id = 0;
$lesson_id = get_the_ID();
$post_type = get_post_type();

switch ( $post_type ) {
	case Unicamp_Tutor::instance()->get_assignment_type():
		$course_id = get_post_meta( get_the_ID(), '_tutor_course_id_for_assignments', true );
		break;
	case Unicamp_Tutor::instance()->get_zoom_meeting_type():
		$course_id = get_post_meta( get_the_ID(), '_tutor_zm_for_course', true );
		break;
	case Unicamp_Tutor::instance()->get_lesson_type():
		$course_id = get_post_meta( get_the_ID(), '_tutor_course_id_for_lesson', true );
		break;
	case Unicamp_Tutor::instance()->get_quiz_type():
		$course    = tutor_utils()->get_course_by_quiz( get_the_ID() );
		$course_id = $course->ID;
		break;
}
?>
	<div class="page-content">

		<?php do_action( 'tutor_lesson/single/before/wrap' ); ?>

		<?php
		$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );
		$wrapper_class         = 'tutor-single-lesson-wrap';

		if ( $enable_spotlight_mode ) {
			$wrapper_class .= ' tutor-spotlight-mode';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div class="tutor-lesson-sidebar-wrap">
				<?php Unicamp_Single_Lesson::instance()->lessons_sidebar(); ?>
			</div>
			<div id="tutor-single-entry-content"
			     class="tutor-lesson-content tutor-single-entry-content tutor-single-entry-content-<?php the_ID(); ?>">
				<div class="container">
					<?php
					global $post;

					$post = get_post( $course_id );
					setup_postdata( $post );

					tutor_load_template( 'single.course.course-prerequisites-alt' );
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>

		<?php do_action( 'tutor_lesson/single/after/wrap' ); ?>

	</div>
<?php
get_tutor_footer();
