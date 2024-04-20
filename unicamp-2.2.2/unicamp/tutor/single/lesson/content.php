<?php
/**
 * Display the content
 *
 * @since         v.1.0.0
 * @author        themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'tutor_lesson/single/before/content' );

$jsonData                                 = array();
$jsonData['post_id']                      = get_the_ID();
$jsonData['best_watch_time']              = 0;
$jsonData['autoload_next_course_content'] = (bool) get_tutor_option( 'autoload_next_course_content' );

$best_watch_time = tutor_utils()->get_lesson_reading_info( get_the_ID(), 0, 'video_best_watched_time' );
if ( $best_watch_time > 0 ) {
	$jsonData['best_watch_time'] = $best_watch_time;
}
?>

<?php do_action( 'tutor_lesson/single/before/content' ); ?>

<div class="container">
	<div class="tutor-single-page-top-bar">
		<div class="tutor-topbar-item tutor-top-bar-course-link">
			<?php $course_id = get_post_meta( get_the_ID(), '_tutor_course_id_for_lesson', true ); ?>
			<a href="<?php echo get_the_permalink( $course_id ); ?>" class="tutor-topbar-home-btn">
				<i class="far fa-home"></i><?php esc_html_e( 'Go to course home', 'unicamp' ); ?>
			</a>
		</div>
		<div class="tutor-topbar-item tutor-topbar-content-title-wrap">
			<?php
			$video = tutor_utils()->get_video_info( get_the_ID() );

			$play_time = false;
			if ( $video ) {
				$play_time = $video->playtime;
			}

			$lesson_type      = $play_time ? 'video' : 'document';
			$lesson_type_icon = 'video' === $lesson_type ? 'far fa-play-circle' : 'far fa-file-alt';
			?>
			<span class="lesson-type-icon">
						<i class="<?php echo esc_attr( $lesson_type_icon ); ?>"></i>
					</span>
			<?php the_title(); ?>
		</div>

		<div class="tutor-topbar-item tutor-topbar-mark-to-done">
			<?php tutor_lesson_mark_complete_html(); ?>
		</div>
	</div>

	<div class="tutor-lesson-content-area">
		<input type="hidden" id="tutor_video_tracking_information"
		       value="<?php echo esc_attr( json_encode( $jsonData ) ); ?>">
		<?php tutor_lesson_video(); ?>
		<?php the_content(); ?>
		<?php get_tutor_posts_attachments(); ?>
		<?php tutor_next_previous_pagination(); ?>
	</div>
</div>

<?php do_action( 'tutor_lesson/single/after/content' );
