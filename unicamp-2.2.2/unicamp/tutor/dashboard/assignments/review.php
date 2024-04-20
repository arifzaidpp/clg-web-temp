<?php
/**
 * Template for displaying Assignments Review Form
 *
 * @since   v.1.3.4
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$assignment_submitted_id = (int) sanitize_text_field( tutor_utils()->array_get( 'view_assignment', $_GET ) );

if ( ! $assignment_submitted_id ) : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'Sorry, but you are looking for something that isn\'t here.', 'unicamp' ); ?>
	</div>
	<?php return; ?>
<?php endif; ?>

<?php
$submitted_assignment = tutor_utils()->get_assignment_submit_info( $assignment_submitted_id );
?>
<?php if ( $submitted_assignment ) : ?>
	<?php
	$max_mark = tutor_utils()->get_assignment_option( $submitted_assignment->comment_post_ID, 'total_mark' );

	$given_mark      = get_comment_meta( $assignment_submitted_id, 'assignment_mark', true );
	$instructor_note = get_comment_meta( $assignment_submitted_id, 'instructor_note', true );

	$assignment_link = '<a href="' . get_the_permalink( $submitted_assignment->comment_post_ID ) . '" target="_blank">' . get_the_title( $submitted_assignment->comment_post_ID ) . '</a>';
	?>

	<h3>
		<?php echo sprintf( esc_html__( 'Assignment: %s', 'unicamp' ), $assignment_link ); ?>
	</h3>

	<form action="" method="post">
		<div class="dashboard-content-box">
			<div class="assignment-review-header">
				<h4>
					<?php esc_html_e( 'Course', 'unicamp' ); ?> :
					<a href="<?php echo get_the_permalink( $submitted_assignment->comment_parent ); ?>" target="_blank">
						<?php echo esc_html( get_the_title( $submitted_assignment->comment_parent ) ); ?>
					</a>
				</h4>
			</div>

			<div class="dashboard-assignment-review">
				<h4><?php esc_html_e( 'Assignment Description:', 'unicamp' ); ?></h4>
				<p><?php echo nl2br( stripslashes( $submitted_assignment->comment_content ) ); ?></p>

				<?php
				$attached_files = get_comment_meta( $submitted_assignment->comment_ID, 'uploaded_attachments', true );
				if ( $attached_files ) :
					?>
					<h5><?php esc_html_e( 'Attach assignment file(s)', 'unicamp' ); ?></h5>
					<div class="tutor-dashboard-assignment-files">
						<?php
						$attached_files = json_decode( $attached_files, true );
						?>
						<?php if ( tutor_utils()->count( $attached_files ) ) : ?>
							<?php
							$upload_dir     = wp_get_upload_dir();
							$upload_baseurl = trailingslashit( tutor_utils()->array_get( 'baseurl', $upload_dir ) );
							?>
							<?php foreach ( $attached_files as $attached_file ): ?>
								<div class="uploaded-files">
									<a href="<?php echo esc_url( $upload_baseurl . tutor_utils()->array_get( 'uploaded_path', $attached_file ) ); ?>"
									   target="_blank"> <i
											class="tutor-icon-upload-file"></i> <?php echo tutor_utils()->array_get( 'name', $attached_file ); ?>
									</a>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="tutor-assignment-evaluate-wraps">
				<h3><?php esc_html_e( 'Evaluation', 'unicamp' ); ?></h3>

				<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
				<input type="hidden" value="tutor_evaluate_assignment_submission" name="tutor_action"/>
				<input type="hidden" value="<?php echo esc_attr( $assignment_submitted_id ); ?>"
				       name="assignment_submitted_id"/>
				<div class="tutor-assignment-evaluate-row">
					<div class="tutor-option-field-label">
						<label for="evaluate-assignment-mark"><?php esc_html_e( 'Your Mark', 'unicamp' ); ?></label>
					</div>
					<div class="tutor-option-field">
						<input type="number" name="evaluate_assignment[assignment_mark]"
						       value="<?php echo esc_attr( sprintf( '%s', $given_mark ? $given_mark : 0 ) ); ?>"
						       max="<?php echo esc_attr( $max_mark ); ?>"
						       min="0"
						       id="evaluate-assignment-mark">
						<p class="desc"><?php echo sprintf( esc_html__( 'Mark this assignment out of %s', 'unicamp' ), "<strong>{$max_mark}</strong>" ); ?></p>
					</div>
				</div>
				<div class="tutor-assignment-evaluate-row">
					<div class="tutor-option-field-label">
						<label
							for="evaluate-assignment-instructor-note"><?php esc_html_e( 'Write a note', 'unicamp' ); ?></label>
					</div>
					<div class="tutor-option-field">
					<textarea
						name="evaluate_assignment[instructor_note]"
						id="evaluate-assignment-instructor-note"><?php echo esc_html( $instructor_note ); ?></textarea>
						<p class="desc"><?php esc_html_e( 'Write a note to students about this submission', 'unicamp' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="dashboard-form-submit-wrap">
			<button type="submit"
			        class="tutor-button tutor-button-primary tutor-success"><?php esc_html_e( 'Evaluate this submission', 'unicamp' ); ?></button>
		</div>
	</form>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'Assignments submission not found or not completed.', 'unicamp' ); ?>
	</div>
<?php endif;
