<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 1.1.0
 */

defined( 'ABSPATH' ) || exit;

$profile_user_id = $get_user->ID;

$enrolled_course   = tutor_utils()->get_enrolled_courses_by_user( $profile_user_id );
$completed_courses = tutor_utils()->get_completed_courses_ids_by_user( $profile_user_id );

$enrolled_course_count  = $enrolled_course ? $enrolled_course->post_count : 0;
$completed_course_count = count( $completed_courses );
$active_course_count    = $enrolled_course_count - $completed_course_count;
?>
<h3><?php esc_html_e( 'About Me', 'unicamp' ); ?></h3>

<?php
$display_name = $get_user->display_name;
$phone_number = get_user_meta( $profile_user_id, 'phone_number', true );
$bio          = get_user_meta( $profile_user_id, '_tutor_profile_bio', true );
$job_title    = strip_tags( get_user_meta( $profile_user_id, '_tutor_profile_job_title', true ) );
?>
<div class="tutor-dashboard-content-inner">
	<div class="dashboard-profile-info">
		<div class="dashboard-profile-info-item profile-display-name">
			<div class="heading">
				<?php esc_html_e( 'Full Name', 'unicamp' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', $display_name ? esc_html( $display_name ) : esc_html__( '________', 'unicamp' ) ); ?></p>
			</div>
		</div>
		<?php if ( ! empty( $phone_number ) ) : ?>
			<div class="dashboard-profile-info-item profile-phone-number">
				<div class="heading">
					<?php esc_html_e( 'Phone Number', 'unicamp' ); ?>
				</div>
				<div class="content">
					<p><?php echo esc_html( $phone_number ); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $job_title ) ) : ?>
			<div class="dashboard-profile-info-item profile-job-title">
				<div class="heading">
					<?php esc_html_e( 'Job Title', 'unicamp' ); ?>
				</div>
				<div class="content">
					<p><?php echo esc_html( $job_title ); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<div class="dashboard-profile-info-item profile-bio">
			<div class="heading">
				<?php esc_html_e( 'Bio', 'unicamp' ); ?>
			</div>
			<div class="content">
				<p><?php printf( '%s', ! empty( $bio ) ? wp_kses_post( $bio ) : esc_html__( '________', 'unicamp' ) ); ?></p>
			</div>
		</div>
	</div>

</div>

<div class="row dashboard-info-cards">
	<div class="col-md-4 col-sm-6 dashboard-info-card enrolled-courses">
		<div class="dashboard-info-card-box">
			<div class="dashboard-info-card-icon">
				<span class="edumi edumi-open-book"></span>
			</div>
			<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $enrolled_course_count ) ); ?></span>
				<span
					class="dashboard-info-card-heading"><?php esc_html_e( 'Enrolled Courses', 'unicamp' ); ?></span>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-sm-6 dashboard-info-card yellow-card active-courses">
		<div class="dashboard-info-card-box">
			<div class="dashboard-info-card-icon">
				<span class="edumi edumi-streaming"></span>
			</div>
			<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $active_course_count ) ); ?></span>
				<span
					class="dashboard-info-card-heading"><?php esc_html_e( 'Active Courses', 'unicamp' ); ?></span>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-sm-6 dashboard-info-card green-card completed-courses">
		<div class="dashboard-info-card-box">
			<div class="dashboard-info-card-icon">
				<span class="edumi edumi-correct"></span>
			</div>
			<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $completed_course_count ) ); ?></span>
				<span
					class="dashboard-info-card-heading"><?php esc_html_e( 'Completed Courses', 'unicamp' ); ?></span>
			</div>
		</div>
	</div>

	<?php if ( user_can( $profile_user_id, tutor()->instructor_role ) ) : ?>
		<?php
		$total_students     = tutor_utils()->get_total_students_by_instructor( $profile_user_id );
		$instructor_courses = tutor_utils()->get_courses_by_instructor( $profile_user_id, 'publish' );
		$total_reviews      = tutor_utils()->get_reviews_by_instructor( $profile_user_id );

		$total_instructor_course = count( $instructor_courses );
		$total_instructor_rating = $total_reviews->count;
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
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $total_instructor_course ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Total Courses', 'unicamp' ); ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 dashboard-info-card orange-card card-total-rating">
			<div class="dashboard-info-card-box">
				<div class="dashboard-info-card-icon">
					<span class="edumi edumi-star"></span>
				</div>
				<div class="dashboard-info-card-content">
					<span
						class="dashboard-info-card-value"><?php echo esc_html( number_format_i18n( $total_instructor_rating ) ); ?></span>
					<span
						class="dashboard-info-card-heading"><?php esc_html_e( 'Total Reviews', 'unicamp' ); ?></span>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
