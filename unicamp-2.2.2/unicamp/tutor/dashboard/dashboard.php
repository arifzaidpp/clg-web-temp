<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<h3><?php esc_html_e( 'Dashboard', 'unicamp' ); ?></h3>

<div class="tutor-dashboard-content-inner">

	<?php
	$enrolled_course   = tutor_utils()->get_enrolled_courses_by_user();
	$completed_courses = tutor_utils()->get_completed_courses_ids_by_user();

	$enrolled_course_count  = $enrolled_course ? $enrolled_course->post_count : 0;
	$completed_course_count = count( $completed_courses );
	$active_course_count    = $enrolled_course_count - $completed_course_count;
	$active_course_count    = $active_course_count > 0 ? $active_course_count : 0;
	?>

	<div class="row dashboard-info-cards">
		<div class="col-md-4 col-sm-6 dashboard-info-card enrolled-courses">
			<a class="dashboard-info-card-box"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses' ) ); ?>">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-open-book"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $enrolled_course_count ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Enrolled Courses', 'unicamp' ); ?></span>
				</div>
			</a>
		</div>

		<div class="col-md-4 col-sm-6 dashboard-info-card yellow-card active-courses">
			<a class="dashboard-info-card-box"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses/active-courses' ) ); ?>">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-streaming"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $active_course_count ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Active Courses', 'unicamp' ); ?></span>
				</div>
			</a>
		</div>

		<div class="col-md-4 col-sm-6 dashboard-info-card green-card completed-courses">
			<a class="dashboard-info-card-box"
			   href="<?php echo esc_url( tutor_utils()->get_tutor_dashboard_page_permalink( 'enrolled-courses/completed-courses' ) ); ?>">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-correct"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $completed_course_count ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Completed Courses', 'unicamp' ); ?></span>
				</div>
			</a>
		</div>

		<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
			<?php
			$total_students = tutor_utils()->get_total_students_by_instructor( get_current_user_id() );
			$my_courses     = tutor_utils()->get_courses_by_instructor( get_current_user_id(), 'publish' );
			$earning_sum    = tutor_utils()->get_earning_sum();

			$my_course_count = count( $my_courses );
			?>
			<div class="col-md-4 col-sm-6 dashboard-info-card pink-card total-students">
				<div class="dashboard-info-card-box">
					<div class="dashboard-info-card-icon">
						<span class="edumi edumi-group"></span>
					</div>
					<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $total_students ) ); ?></span>
						<span
							class="dashboard-info-card-heading"><?php esc_html_e( 'Total Students', 'unicamp' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6 dashboard-info-card purple-card total-courses">
				<div class="dashboard-info-card-box">
					<div class="dashboard-info-card-icon">
						<span class="edumi edumi-user-support"></span>
					</div>
					<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $my_course_count ) ); ?></span>
						<span
							class="dashboard-info-card-heading"><?php esc_html_e( 'Total Courses', 'unicamp' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6 dashboard-info-card orange-card card-total-earnings">
				<div class="dashboard-info-card-box">
					<div class="dashboard-info-card-icon">
						<span class="edumi edumi-coin"></span>
					</div>
					<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo tutor_utils()->tutor_price( $earning_sum->instructor_amount ); ?></span>
						<span
							class="dashboard-info-card-heading"><?php esc_html_e( 'Total Earnings', 'unicamp' ); ?></span>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
