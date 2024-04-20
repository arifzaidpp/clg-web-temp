<?php
/**
 * Template for displaying Assignments
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

global $wpdb;

$per_page     = tutor_utils()->get_option( 'pagination_per_page', 10 );
$current_page = max( 1, tutor_utils()->avalue_dot( 'current_page', tutor_sanitize_data( $_GET ) ) );
$offset       = ( $current_page - 1 ) * $per_page;

$course_id    = isset( $_GET['course-id'] ) ? sanitize_text_field( $_GET['course-id'] ) : '';
$order_filter = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
$date_filter  = isset( $_GET['date'] ) ? $_GET['date'] : '';

$current_user = get_current_user_id();
$assignments  = tutor_utils()->get_assignments_by_instructor( null, compact( 'course_id', 'order_filter', 'date_filter', 'per_page', 'offset' ) );
$courses      = ( current_user_can( 'administrator' ) ) ? tutor_utils()->get_courses() : tutor_utils()->get_courses_by_instructor();
?>
	<h3><?php esc_html_e( 'Assignments', 'unicamp' ); ?></h3>

<?php if ( $assignments->count ) : ?>
	<div class="table-assignments dashboard-table-wrapper dashboard-table-responsive">
		<div class="dashboard-table-container">
			<table class="dashboard-table">
				<thead>
				<tr>
					<th class="col-course-info"><?php esc_html_e( 'Course Name', 'unicamp' ) ?></th>
					<th class="col-total-mark"><?php esc_html_e( 'Total Mark', 'unicamp' ) ?></th>
					<th class="col-total-submit"><?php esc_html_e( 'Total Submit', 'unicamp' ) ?></th>
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
							<h5 class="assignment-title"><?php echo esc_html( $item->post_title ); ?></h5>
							<h5 class="course-title">
								<a href='<?php echo esc_url( $course_url . '?course_id=' . $course_id ) ?>'><?php echo esc_html__( 'Course: ', 'unicamp' ) . get_the_title( $course_id ); ?> </a>
							</h5>
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
							<?php
							$view_detail_btn_class = 'review-assignment-link hint--bounce ';
							$view_detail_btn_class .= ! EDUMALL_IS_RTL ? 'hint--top-left' : 'hint--top-right';

							?>
							<a class="<?php echo esc_attr( $view_detail_btn_class ); ?>"
							   aria-label="<?php esc_attr_e( 'View Details', 'unicamp' ); ?>"
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
	<?php
	if ( $assignments->count > $per_page ) {
		$pagination_data = array(
			'total_items' => $assignments->count,
			'per_page'    => $per_page,
			'paged'       => $current_page,
		);
		tutor_load_template( 'dashboard.elements.pagination', [ 'data' => $pagination_data ] );
	}
	?>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'No assignment available.', 'unicamp' ); ?>
	</div>
<?php endif;
