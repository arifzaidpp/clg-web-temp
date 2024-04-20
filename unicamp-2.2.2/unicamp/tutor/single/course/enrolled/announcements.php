<?php
/**
 * Announcements
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$announcements = tutor_utils()->get_announcements( get_the_ID() );
?>

<?php do_action( 'tutor_course/announcements/before' ); ?>

<div class="tutor-single-course-segment tutor-announcements-wrap">
	<h4 class="tutor-segment-title"><?php esc_html_e( 'Announcements', 'unicamp' ); ?></h4>

	<div class="tutor-announcement-list">
		<?php if ( is_array( $announcements ) && count( $announcements ) ) { ?>
			<?php foreach ( $announcements as $announcement ) : ?>
				<div class="tutor-individual-announcement">
					<div class="announcement-header">
						<div class="announcement-icon primary-color">
							<span class="far fa-bell"></span>
						</div>
						<div class="announcement-info">
							<h3 class="announcement-title"><?php echo esc_html( $announcement->post_title ); ?></h3>
							<div class="announcement-meta">
								<span class="announcement-author">
									<?php echo sprintf( esc_html__( 'Posted by %s', 'unicamp' ), $announcement->display_name ); ?>
								</span>
								<span class="announcement-post-date">
									<?php echo sprintf( esc_html__( '%s ago', 'unicamp' ), human_time_diff( strtotime( $announcement->post_date ) ) ); ?>
								</span>
							</div>
						</div>
					</div>

					<div class="tutor-announcement-content">
						<?php echo tutor_utils()->announcement_content( wpautop( stripslashes( $announcement->post_content ) ) ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php } else { ?>
			<div class="tutor-no-announcements">
				<h2><?php esc_html_e( 'No announcements posted yet.', 'unicamp' ); ?></h2>
				<p>
					<?php esc_html_e( 'The instructor hasn\'t added any announcements to this course yet. Announcements are used to inform you of updates or additions to the course.', 'unicamp' ); ?>
				</p>
			</div>
		<?php } ?>
	</div>
</div>

<?php do_action( 'tutor_course/announcements/after' ); ?>
