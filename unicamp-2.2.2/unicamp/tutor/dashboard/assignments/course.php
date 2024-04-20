<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;
$course_id   = (int) sanitize_text_field( tutor_utils()->array_get( 'course_id', $_GET ) );
$assignments = tutor_utils()->get_assignments_by_course( $course_id );

if ( $assignments->count ) : ?>
	<h3><?php echo esc_html( get_the_title( $course_id ) ); ?></h3>

	<div class="dashboard-table-wrapper dashboard-table-responsive table-assignments">
		<div class="dashboard-table-container">
			<table class="dashboard-table">
				<thead>
				<tr>
					<th class="col-course-info"><?php esc_html_e( 'Assignment', 'unicamp' ); ?></th>
					<th class="col-total-mark"><?php esc_html_e( 'Total Mark', 'unicamp' ); ?></th>
					<th class="col-total-submit"><?php esc_html_e( 'Total Submit', 'unicamp' ); ?></th>
					<th class="col-action">&nbsp;</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $assignments->results as $item ) : ?>
					<?php
					$max_mark      = tutor_utils()->get_assignment_option( $item->ID, 'total_mark' );
					$course_id     = tutor_utils()->get_course_id_by_assignment( $item->ID );
					$course_url    = tutor_utils()->get_tutor_dashboard_page_permalink( 'assignments/course' );
					$submitted_url = tutor_utils()->get_tutor_dashboard_page_permalink( 'assignments/submitted' );
					$comment_count = $wpdb->get_var( "SELECT COUNT(comment_ID) FROM {$wpdb->comments} WHERE comment_type = 'tutor_assignment' AND comment_post_ID = $item->ID" );
					// @TODO: assign post_meta is empty if user don't click on update button (http://prntscr.com/oax4t8) but post status is publish
					?>
					<tr>
						<td>
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Assignment:', 'unicamp' ); ?></div>
							<h5 class="assignment-title"><?php echo esc_html( $item->post_title ); ?></h5>
						</td>
						<td>
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Total Mark:', 'unicamp' ); ?></div>
							<?php echo esc_html( $max_mark ); ?>
						</td>
						<td>
							<div
								class="heading col-heading-mobile"><?php esc_html_e( 'Total Submit:', 'unicamp' ); ?></div>
							<?php echo esc_html( number_format_i18n( $comment_count ) ); ?>
						</td>
						<td>
							<a class="hint--bounce hint--top-left review-assignment-link"
							   aria-label="<?php esc_attr_e( 'View submitted', 'unicamp' ); ?>"
							   href="<?php echo esc_url( $submitted_url . '?assignment=' . $item->ID ); ?>">
								<i class="far fa-angle-right"></i>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'No assignment available.', 'unicamp' ); ?>
	</div>
<?php endif;
