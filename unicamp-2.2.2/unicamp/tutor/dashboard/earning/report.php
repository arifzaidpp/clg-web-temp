<?php
/**
 * Template for displaying Instructor Earning Report
 *
 * @since   v.1.1.2
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$sub_page = 'this_month';
if ( ! empty( $_GET['time_period'] ) ) {
	$sub_page = sanitize_text_field( $_GET['time_period'] );
}
if ( ! empty( $_GET['date_range_from'] ) && ! empty( $_GET['date_range_to'] ) ) {
	$sub_page = 'date_range';
}
?>
<h3><?php esc_html_e( 'Earning Report', 'unicamp' ); ?></h3>

<div class="tutor-dashboard-inline-links">
	<ul>
		<li>
			<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning' ); ?>">
				<?php esc_html_e( 'Earnings', 'unicamp' ); ?>
			</a>
		</li>
		<li class="active">
			<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning/report' ); ?>">
				<?php esc_html_e( 'Reports', 'unicamp' ); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning/statements' ); ?>">
				<?php esc_html_e( 'Statements', 'unicamp' ); ?>
			</a>
		</li>
	</ul>
</div>

<?php
tutor_load_template( 'dashboard.earning.earning-report-top-menu', compact( 'sub_page' ) );
tutor_load_template( 'dashboard.earning.report-' . $sub_page );
?>
