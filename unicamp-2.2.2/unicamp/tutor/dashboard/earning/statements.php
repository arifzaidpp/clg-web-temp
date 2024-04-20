<?php
/**
 * Template for displaying Instructor Statements
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
	<h3><?php echo esc_html__( 'Earning Statements', 'unicamp' ); ?></h3>

	<div class="tutor-dashboard-inline-links">
		<ul>
			<li>
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning' ); ?>">
					<?php esc_html_e( 'Earnings', 'unicamp' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning/report' ); ?>">
					<?php esc_html_e( 'Reports', 'unicamp' ); ?>
				</a>
			</li>
			<li class="active">
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning/statements' ); ?>">
					<?php esc_html_e( 'Statements', 'unicamp' ); ?>
				</a>
			</li>
		</ul>
	</div>
<?php
tutor_load_template( 'dashboard.earning.earning-report-top-menu', compact( 'sub_page' ) );

$user_id = get_current_user_id();

$complete_status = tutor_utils()->get_earnings_completed_statuses();
$statuses        = $complete_status;
$complete_status = "'" . implode( "','", $complete_status ) . "'";

$statements = false;

// Pagination variables.
$per_page     = tutor_utils()->get_option( 'statement_show_per_page', 20 );
$current_page = max( 1, tutor_utils()->avalue_dot( 'current_page', $_GET ) );
$offset       = ( $current_page - 1 ) * $per_page;

switch ( $sub_page ) {

	case 'last_year':
		$year    = date( 'Y', strtotime( '-1 year' ) );
		$dataFor = 'yearly';

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'year', 'dataFor', 'per_page', 'offset' ) );
		break;

	case 'this_year':
		$year    = date( 'Y' );
		$dataFor = 'yearly';

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'year', 'dataFor', 'per_page', 'offset' ) );
		break;

	case 'last_month':
		$start_date = date( 'Y-m', strtotime( '-1 month' ) );
		$start_date = $start_date . '-1';
		$end_date   = date( 'Y-m-t', strtotime( $start_date ) );

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'start_date', 'end_date', 'per_page', 'offset' ) );
		break;

	case 'this_month':

		$start_date = date( 'Y-m-01' );
		$end_date   = date( 'Y-m-t' );

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'start_date', 'end_date', 'per_page', 'offset' ) );
		break;

	case 'last_week':

		$previous_week = strtotime( '-1 week +1 day' );
		$start_date    = strtotime( 'last sunday midnight', $previous_week );
		$end_date      = strtotime( 'next saturday', $start_date );
		$start_date    = date( 'Y-m-d', $start_date );
		$end_date      = date( 'Y-m-d', $end_date );

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'start_date', 'end_date', 'per_page', 'offset' ) );
		break;


	case 'this_week':
		$start_date = date( 'Y-m-d', strtotime( 'last sunday midnight' ) );
		$end_date   = date( 'Y-m-d', strtotime( 'next saturday' ) );

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'start_date', 'end_date', 'per_page', 'offset' ) );
		break;

	case 'date_range':

		$start_date = sanitize_text_field( tutor_utils()->avalue_dot( 'date_range_from', $_GET ) );
		$end_date   = sanitize_text_field( tutor_utils()->avalue_dot( 'date_range_to', $_GET ) );

		$statements = tutor_utils()->get_earning_statements( $user_id, compact( 'start_date', 'end_date', 'per_page', 'offset' ) );
		break;
}
?>

<?php if ( $statements->count ) : ?>
	<div class="dashboard-earning-chart">
		<p class="tutor-dashboard-pagination-results-stats">
			<?php
			echo sprintf( __( 'Showing results %d to %d of %d', 'unicamp' ), $offset + 1, min( $statements->count, $offset + 1 + tutor_utils()->count( $statements->results ) ), $statements->count );
			?>
		</p>

		<div class="dashboard-earning-statement-table dashboard-table-wrapper dashboard-table-responsive">
			<div class="dashboard-table-container">
				<table class="dashboard-table">
					<thead>
					<tr>
						<th><?php esc_html_e( 'Course Info', 'unicamp' ); ?></th>
						<th><?php esc_html_e( 'Earning', 'unicamp' ); ?></th>
						<th><?php esc_html_e( 'Commission', 'unicamp' ); ?></th>
						<th><?php esc_html_e( 'Deduct', 'unicamp' ); ?></th>
					</tr>
					</thead>

					<tbody>
					<?php foreach ( $statements->results as $statement ) : ?>
						<tr>
							<td>
								<div
									class="heading col-heading-mobile"><?php esc_html_e( 'Course Info:', 'unicamp' ); ?></div>
								<p class="small-text">
									<span
										class="statement-order-<?php echo esc_attr( $statement->order_status ); ?>"><?php echo esc_html( $statement->order_status ); ?></span>
									&nbsp; <strong><?php esc_html_e( 'Date:', 'unicamp' ); ?></strong>
									<i><?php echo wp_date( get_option( 'date_format', strtotime( $statement->created_at ) ) ) . ' ' . wp_date( get_option( 'time_format', strtotime( $statement->created_at ) ) ) ?></i>
								</p>

								<p>
									<a href="<?php echo esc_url( get_the_permalink( $statement->course_id ) ); ?>"
									   target="_blank">
										<?php echo esc_html( $statement->course_title ); ?>
									</a>
								</p>

								<p>
									<strong><?php esc_html_e( 'Price: ', 'unicamp' ); ?></strong>
									<?php echo tutor_utils()->tutor_price( $statement->course_price_total ); ?>
								</p>

								<p class="small-text">
									<strong><?php esc_html_e( 'Order ID', 'unicamp' ); ?>
										<?php echo "#{$statement->order_id}"; ?></strong>
								</p>

								<?php $order = new WC_Order( $statement->order_id ); ?>
								<div class="statement-address">
									<strong><?php esc_html_e( 'Purchaser', 'unicamp' ); ?></strong>
									<?php echo '<address>' . $order->get_formatted_billing_address() . '</address>'; ?>
								</div>
							</td>
							<td>
								<div
									class="heading col-heading-mobile"><?php esc_html_e( 'Earning:', 'unicamp' ); ?></div>
								<p><?php echo tutor_utils()->tutor_price( $statement->instructor_amount ); ?></p>
								<p class="small-text"> <?php esc_html_e( 'As per', 'unicamp' ); ?> <?php echo esc_html( $statement->instructor_rate ); ?>
									(<?php echo esc_html( $statement->commission_type ); ?>) </p>
							</td>
							<td>
								<div
									class="heading col-heading-mobile"><?php esc_html_e( 'Commission:', 'unicamp' ); ?></div>
								<p><?php echo tutor_utils()->tutor_price( $statement->admin_amount ); ?> </p>
								<p class="small-text"><?php esc_html_e( 'Rate', 'unicamp' ); ?>
									: <?php echo esc_html( $statement->admin_rate ); ?> </p>
								<p class="small-text"><?php esc_html_e( 'Type', 'unicamp' ); ?>
									: <?php echo esc_html( $statement->commission_type ); ?> </p>

							</td>
							<td>
								<div
									class="heading col-heading-mobile"><?php esc_html_e( 'Deduct:', 'unicamp' ); ?></div>
								<p><?php echo esc_html( $statement->deduct_fees_name ); ?><?php echo tutor_utils()->tutor_price( $statement->deduct_fees_amount ); ?>
								</p>
								<p class="small-text"><?php esc_html_e( 'Type', 'unicamp' ); ?>
									: <?php echo esc_html( $statement->deduct_fees_type ); ?> </p>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'There is not enough sales data to generate a statement.', 'unicamp' ); ?>
	</div>
<?php endif; ?>

<?php if ( $statements->count ) : ?>
	<div class="tutor-pagination">
		<?php
		Unicamp_Templates::render_paginate_links( [
			'format'  => '?current_page=%#%',
			'current' => $current_page,
			'total'   => ceil( $statements->count / $per_page ),
		] );
		?>
	</div>
<?php
endif;

