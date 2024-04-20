<?php
/**
 * Template for displaying instructors earnings
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

global $wpdb;

$user_id = get_current_user_id();

/**
 * Getting the Last Month.
 */
$year    = date( 'Y', strtotime( '-1 year' ) );
$dataFor = 'yearly';

$earning_sum = tutor_utils()->get_earning_sum( $user_id, compact( 'year', 'dataFor' ) );

if ( ! $earning_sum ) {
	echo '<div class="dashboard-no-content-found">' . esc_html__( 'No Earning info available.', 'unicamp' ) . '</div>';

	return;
}

$complete_status = tutor_utils()->get_earnings_completed_statuses();
$statuses        = $complete_status;
$complete_status = "'" . implode( "','", $complete_status ) . "'";

/**
 * Query This Month
 */

$salesQuery = $wpdb->get_results( "
              SELECT SUM(instructor_amount) as total_earning, 
              MONTHNAME(created_at)  as month_name 
              from {$wpdb->prefix}tutor_earnings 
              WHERE user_id = {$user_id} AND order_status IN({$complete_status}) 
              AND YEAR(created_at) = {$year} 
              GROUP BY MONTH (created_at) 
              ORDER BY MONTH(created_at) ASC ;" );

$total_earning  = wp_list_pluck( $salesQuery, 'total_earning' );
$months         = wp_list_pluck( $salesQuery, 'month_name' );
$monthWiseSales = array_combine( $months, $total_earning );

/**
 * Format yearly
 */
$emptyMonths = array();
for ( $m = 1; $m <= 12; $m++ ) {
	$emptyMonths[ date( 'F', mktime( 0, 0, 0, $m, 1, date( 'Y' ) ) ) ] = 0;
}
$chartData = array_merge( $emptyMonths, $monthWiseSales );

$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'year', 'dataFor', 'statuses' ) );

?>
<div class="row dashboard-info-cards dashboard-earning-cards">
	<div class="col-md-4 col-sm-6 dashboard-info-card card-my-earning">
		<div class="dashboard-info-card-box">
			<div class="dashboard-info-card-content">
				<span class="dashboard-info-card-heading">
					<?php esc_html_e( 'My Earning', 'unicamp' ); ?>
				</span>
				<span class="dashboard-info-card-value">
					<?php echo tutor_utils()->tutor_price( $earning_sum->instructor_amount ); ?>
				</span>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-6 dashboard-info-card card-my-all-time-sales">
		<div class="dashboard-info-card-box">
			<div class="dashboard-info-card-content">
				<span class="dashboard-info-card-heading">
					<?php esc_html_e( 'All time sales', 'unicamp' ); ?>
				</span>
				<span class="dashboard-info-card-value">
					<?php echo tutor_utils()->tutor_price( $earning_sum->course_price_total ); ?>
				</span>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-6 dashboard-info-card card-deducted-commissions">
		<div class="dashboard-info-card-box">
			<div class="dashboard-info-card-content">
				<span class="dashboard-info-card-heading">
					<?php esc_html_e( 'Deducted Commissions', 'unicamp' ); ?>
				</span>
				<span class="dashboard-info-card-value">
					<?php echo tutor_utils()->tutor_price( $earning_sum->admin_amount ); ?>
				</span>
			</div>
		</div>
	</div>
	<?php if ( $earning_sum->deduct_fees_amount > 0 ) : ?>
		<div class="col-md-4 col-sm-6 dashboard-info-card card-deducted-fees">
			<div class="dashboard-info-card-box">
				<div class="dashboard-info-card-content">
					<span class="dashboard-info-card-heading">
						<?php esc_html_e( 'Deducted Fees', 'unicamp' ); ?>
					</span>
					<span class="dashboard-info-card-value">
						<?php echo tutor_utils()->tutor_price( $earning_sum->deduct_fees_amount ); ?>
					</span>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<div class="dashboard-earning-chart">
	<h4 class="chart-title"><?php echo esc_html( sprintf( __( 'Earning Data for the year of %s', 'unicamp' ), $year ) ); ?></h4>
	<?php tutor_load_template( 'dashboard.earning.chart-body', compact( 'chartData', 'statements' ) ); ?>
</div>

<div class="dashboard-earning-chart">
	<h4 class="chart-title"><?php esc_html_e( 'Sales statements for this period', 'unicamp' ); ?></h4>
	<?php tutor_load_template( 'dashboard.earning.statement', compact( 'chartData', 'statements' ) ); ?>
</div>
