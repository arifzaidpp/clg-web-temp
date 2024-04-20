<?php
/**
 * Template for displaying instructors earnings
 *
 * @since         v.1.1.2
 *
 * @author        Themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

$user_id = get_current_user_id();

/**
 * Getting the last week.
 */
$start_date = date( "Y-m-01" );
$end_date   = date( "Y-m-t" );

$stats = tutils()->get_earning_chart( $user_id, $start_date, $end_date );
extract( $stats );
?>
<?php if ( ! $earning_sum ) : ?>
	<div class="dashboard-no-content-found">
		<?php esc_html_e( 'No Earning info available.', 'unicamp' ); ?>
	</div>
	<?php return; ?>
<?php endif; ?>

<h3><?php esc_html_e( 'Earnings', 'unicamp' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<div class="tutor-dashboard-inline-links">
		<ul>
			<li class="active">
				<a href="<?php echo tutor_utils()->get_tutor_dashboard_page_permalink( 'earning' ); ?>">
					<?php esc_html_e( 'Earnings', 'unicamp' ); ?>
				</a>
			</li>
			<li>
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

	<div class="row dashboard-info-cards dashboard-earning-cards">
		<div class="col-md-4 col-sm-6 dashboard-info-card card-my-balance">
			<div class="dashboard-info-card-box">
				<div class="dashboard-info-card-content">
					<span class="dashboard-info-card-heading">
						<?php esc_html_e( 'My Balance', 'unicamp' ); ?>
					</span>
					<span class="dashboard-info-card-value">
						<?php echo tutor_utils()->tutor_price( $earning_sum->balance ); ?>
					</span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 dashboard-info-card card-my-earnings">
			<div class="dashboard-info-card-box">
				<div class="dashboard-info-card-content">
					<span class="dashboard-info-card-heading">
						<?php esc_html_e( 'My Earnings', 'unicamp' ); ?>
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

		<div class="col-md-4 col-sm-6 dashboard-info-card card-my-all-time-withdrawals">
			<div class="dashboard-info-card-box">
				<div class="dashboard-info-card-content">
					<span class="dashboard-info-card-heading">
						<?php esc_html_e( 'All time withdrawals', 'unicamp' ); ?>
					</span>
					<span class="dashboard-info-card-value">
						<?php echo tutor_utils()->tutor_price( $earning_sum->withdraws_amount ); ?>
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
		<h4 class="chart-title"><?php esc_html_e( 'Earnings Chart for this month', 'unicamp' ); ?>
			(<?php echo date( "F" ) ?>)</h4>
		<canvas id="tutorChart" style="width: 100%; height: 400px;"></canvas>
	</div>
</div>

<?php
$primary_color = Unicamp::setting( 'primary_color', Unicamp::PRIMARY_COLOR );
$dataset_label = esc_html__( 'Earning', 'unicamp' );
?>

<script>
	var ctx = document.getElementById( "tutorChart" ).getContext( '2d' );
	var tutorChart = new Chart( ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode( array_keys( $chartData ) ); ?>,
			datasets: [
				{
					label: '<?php echo esc_js( $dataset_label ); ?>',
					backgroundColor: '<?php echo esc_js( $primary_color ); ?>',
					borderColor: '<?php echo esc_js( $primary_color ); ?>',
					data: <?php echo json_encode( array_values( $chartData ) ); ?>,
					borderWidth: 2,
					fill: false,
					lineTension: 0,
				}
			]
		},
		options: {
			scales: {
				yAxes: [
					{
						ticks: {
							min: 0, // it is for ignoring negative step.
							beginAtZero: true,
							callback: function( value, index, values ) {
								if ( Math.floor( value ) === value ) {
									return value;
								}
							}
						}
					}
				]
			},
			legend: {
				display: false
			}
		}
	} );
</script>
