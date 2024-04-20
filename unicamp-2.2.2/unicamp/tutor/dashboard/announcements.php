<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Template for displaying Announcements
 *
 * @since   v.1.7.9
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.7.9
 */
$per_page = tutor_utils()->get_option( 'pagination_per_page', 10 );
$paged    = max( 1, tutor_utils()->avalue_dot( 'current_page', tutor_sanitize_data( $_GET ) ) );

$order_filter  = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';
$search_filter = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
// Announcement's parent.
$course_id   = isset( $_GET['course-id'] ) ? sanitize_text_field( $_GET['course-id'] ) : '';
$date_filter = isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '';

$year  = date( 'Y', strtotime( $date_filter ) );
$month = date( 'm', strtotime( $date_filter ) );
$day   = date( 'd', strtotime( $date_filter ) );

$args = array(
	'post_type'      => 'tutor_announcements',
	'post_status'    => 'publish',
	's'              => $search_filter,
	'post_parent'    => $course_id,
	'posts_per_page' => $per_page,
	'paged'          => $paged,
	'orderBy'        => 'ID',
	'order'          => $order_filter,
);

if ( ! empty( $date_filter ) ) {
	$args['date_query'] = array(
		array(
			'year'  => $year,
			'month' => $month,
			'day'   => $day,
		),
	);
}
if ( ! current_user_can( 'administrator' ) ) {
	$args['author'] = get_current_user_id();
}
$the_query = new WP_Query( $args );

//get courses.
$courses        = ( current_user_can( 'administrator' ) ) ? tutor_utils()->get_courses() : tutor_utils()->get_courses_by_instructor();
$image_base     = tutor()->url . '/assets/images/';
$notify_checked = tutils()->get_option( 'email_to_students.new_announcement_posted' );
?>
	<h3><?php esc_html_e( 'Announcement', 'unicamp' ); ?></h3>

	<div class="tutor-dashboard-content-inner tutor-frontend-dashboard-withdrawal dashboard-content-box">
		<div class="withdraw-page-current-balance new-announcement-wrap">
			<div class="balance-info new-announcement-content">
				<div class="tutor-announcement-big-icon">
					<span class="far fa-bell"></span>
				</div>
				<div class="announcement-content-info">
					<h4><?php esc_html_e( 'Create Announcement', 'unicamp' ); ?></h4>
					<p><?php esc_html_e( 'Notify all students of your course', 'unicamp' ); ?></p>
				</div>
			</div>
			<div class="new-announcement-button">
				<button type="button" class="tutor-btn tutor-btn-primary" data-tutor-modal-target="tutor_announcement_new">
					<?php esc_html_e( 'Add New Announcement', 'tutor' ); ?>
				</button>
			</div>
		</div>
	</div>

	<!--Filter-->
	<div class="tutor-dashboard-announcement-sorting dashboard-content-box tutor-row tutor-mb-32 tutor-mt-44"
	     style="width: calc(100% + 30px);">
		<div class="tutor-col-12 tutor-col-lg-6 tutor-mt-12 tutor-mt-lg-0">
			<label class="tutor-d-block tutor-mb-12 tutor-form-label">
				<?php esc_html_e( 'Courses', 'unicamp' ); ?>
			</label>
			<select class="tutor-form-select tutor-form-control-sm tutor-announcement-course-sorting">

				<option value=""><?php esc_html_e( 'All', 'unicamp' ); ?></option>

				<?php if ( $courses ) : ?>
					<?php foreach ( $courses as $course ) : ?>
						<option
							value="<?php echo esc_attr( $course->ID ); ?>" <?php selected( $course_id, $course->ID, 'selected' ); ?>>
							<?php echo esc_html( $course->post_title ); ?>
						</option>
					<?php endforeach; ?>
				<?php else : ?>
					<option value=""><?php esc_html_e( 'No course found', 'unicamp' ); ?></option>
				<?php endif; ?>
			</select>
		</div>

		<div class="tutor-col-6 tutor-col-lg-3 tutor-mt-12 tutor-mt-lg-0">
			<label
				class="tutor-d-block tutor-mb-12 tutor-form-label"><?php esc_html_e( 'Sort By', 'unicamp' ); ?></label>
			<select class="tutor-form-select tutor-announcement-order-sorting tutor-form-control-sm" data-search="no">
				<option <?php selected( $order_filter, 'ASC' ); ?>><?php esc_html_e( 'ASC', 'unicamp' ); ?></option>
				<option <?php selected( $order_filter, 'DESC' ); ?>><?php esc_html_e( 'DESC', 'unicamp' ); ?></option>
			</select>
		</div>

		<div class="tutor-col-6 tutor-col-lg-3 tutor-mt-12 tutor-mt-lg-0">
			<label class="tutor-form-label tutor-d-block tutor-mb-12"><?php esc_html_e( 'Date', 'tutor' ); ?></label>
			<div class="tutor-v2-date-picker"></div>
		</div>
	</div>
	<!--Filter end-->

<?php
$announcements = $the_query->have_posts() ? $the_query->posts : array();

tutor_load_template_from_custom_path(
	tutor()->path . '/views/fragments/announcement-list.php',
	array(
		'announcements' => is_array( $announcements ) ? $announcements : array(),
		'the_query'     => $the_query,
		'paged'         => $paged,
	)
);
