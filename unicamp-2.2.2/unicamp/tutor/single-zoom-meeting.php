<?php
/**
 * Template for displaying single live meeting page
 *
 * @since         v.1.7.1
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.7.1
 *
 * @theme-version 2.3.1
 */

defined( 'ABSPATH' ) || exit;

get_tutor_header();

global $post;
$currentPost  = $post;
$zoom_meeting = tutor_zoom_meeting_data( $post->ID );
$meeting_data = $zoom_meeting->data;
$browser_url  = "https://us04web.zoom.us/wc/join/{$meeting_data['id']}?wpk={$meeting_data['encrypted_password']}";
$browser_text = __( 'Join in Browser', 'unicamp' );

if ( get_current_user_id() == $post->post_author ) {
	$browser_url  = $meeting_data['start_url'];
	$browser_text = __( 'Start Meeting', 'unicamp' );
}
?>
	<div class="page-content">

		<?php do_action( 'tutor_zoom/single/before/wrap' ); ?>

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
					<div class="row">
						<div class="col-md-12">

							<div class="tutor-single-page-top-bar">
								<div class="tutor-topbar-item tutor-top-bar-course-link">
									<?php $course_id = get_post_meta( $post->ID, '_tutor_zm_for_course', true ); ?>
									<a href="<?php echo get_the_permalink( $course_id ); ?>"
									   class="tutor-topbar-home-btn">
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

							<div class="tutor-zoom-meeting-content">
								<?php if ( $zoom_meeting->is_expired ) : ?>
									<div class="tutor-zoom-meeting-expired-msg-wrap">
										<h2 class="meeting-title"><?php echo esc_html( $post->post_title ); ?></h2>
										<div class="msg-expired-section">
											<img
												src="<?php echo TUTOR_ZOOM()->url . 'assets/images/zoom-icon-expired.png'; ?>"
												alt="<?php esc_attr_e( 'Zoom expired', 'unicamp' ); ?>"/>
											<div>
												<h3><?php esc_html_e( 'The video conference has expired', 'unicamp' ); ?></h3>
												<p><?php esc_html_e( 'Please contact your instructor for further information', 'unicamp' ); ?></p>
											</div>
										</div>
										<div class="meeting-details-section">
											<p><?php echo wp_kses_post( $post->post_content ); ?></p>
											<div>
												<div>
													<span><?php esc_html_e( 'Meeting Date', 'unicamp' ); ?>:</span>
													<p><?php echo esc_html( $zoom_meeting->start_date ); ?></p>
												</div>
												<div>
													<span><?php esc_html_e( 'Host Email', 'unicamp' ); ?>:</span>
													<p><?php echo esc_html( $meeting_data['host_email'] ); ?></p>
												</div>
											</div>
										</div>
									</div>
								<?php else: ?>
								<div class="zoom-meeting-countdown-wrap">
									<div class="tutor-zoom-meeting-countdown"
									     data-timer=<?php echo esc_attr( $zoom_meeting->countdown_date ); ?>"
										     data-timezone="<?php echo esc_attr( $zoom_meeting->timezone ); ?>">
								</div>
								<div class="tutor-zoom-join-button-wrap">
									<a href="<?php echo esc_url( $browser_url ); ?>" target="_blank"
									   class="tutor-btn tutor-button-block zoom-meeting-join-in-web"><?php echo esc_html( $browser_text ); ?></a>
									<a href="<?php echo esc_url( $meeting_data['join_url'] ); ?>"
									   target="_blank"
									   class="tutor-btn tutor-button-block zoom-meeting-join-in-app"><?php esc_html_e( 'Join in Zoom App', 'unicamp' ); ?></a>
								</div>
							</div>
							<div class="zoom-meeting-content-wrap">
								<h2 class="meeting-title"><?php echo esc_html( $post->post_title ); ?></h2>
								<p class="meeting-summary"><?php echo wp_kses_post( $post->post_content ); ?></p>
								<div class="meeting-details">
									<div>
										<span><?php esc_html_e( 'Meeting Date', 'unicamp' ); ?></span>
										<p><?php echo esc_html( $zoom_meeting->start_date ); ?></p>
									</div>
									<div>
										<span><?php esc_html_e( 'Meeting ID', 'unicamp' ); ?></span>
										<p><?php echo esc_html( $meeting_data['id'] ); ?></p>
									</div>
									<div>
										<span><?php esc_html_e( 'Password', 'unicamp' ); ?></span>
										<p><?php echo esc_html( $meeting_data['password'] ); ?></p>
									</div>
									<div>
										<span><?php esc_html_e( 'Host Email', 'unicamp' ); ?></span>
										<p><?php echo esc_html( $meeting_data['host_email'] ); ?></p>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

<?php do_action( 'tutor_zoom/single/after/wrap' ); ?>

	</div>

<?php
get_tutor_footer();
