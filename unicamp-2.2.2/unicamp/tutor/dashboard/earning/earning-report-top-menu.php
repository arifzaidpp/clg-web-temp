<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="tutor-date-range-filter-wrap">
	<?php
	$time_periods = array(
		'last_year'  => esc_html__( 'Last Year', 'unicamp' ),
		'this_year'  => esc_html__( 'This Year', 'unicamp' ),
		'last_month' => esc_html__( 'Last Month', 'unicamp' ),
		'this_month' => esc_html__( 'This Month', 'unicamp' ),
		'last_week'  => esc_html__( 'Last Week', 'unicamp' ),
		'this_week'  => esc_html__( 'This Week', 'unicamp' ),
	);
	?>
	<div class="report-top-sub-menu">
		<?php
		foreach ( $time_periods as $period => $period_name ) {
			$activeClass = ( $sub_page === $period ) ? 'active' : '';

			$timePeriodPageURL = add_query_arg( array( 'time_period' => $period ) );
			$timePeriodPageURL = remove_query_arg( array(
				'date_range_from',
				'date_range_to',
				'tutor_report_action',
			), $timePeriodPageURL );

			echo '<a href="' . $timePeriodPageURL . '" class="' . $activeClass . '">' . $period_name . '</a> ';
		}
		?>
	</div>
	<div class="tutor-date-range-wrap">
		<form action="" class="report-date-range-form" method="get">
			<?php
			$query_arg = $_GET;
			if ( ! empty( $query_arg ) && is_array( $query_arg ) ) {
				if ( isset( $query_arg['time_period'] ) ) {
					unset( $query_arg['time_period'] );
				}

				foreach ( $query_arg as $name => $value ) {
					echo "<input type='hidden' name='{$name}' value='{$value}' />";
				}
			}

			$date_range_from = '';
			if ( isset( $query_arg['date_range_from'] ) ) {
				$date_range_from = sanitize_text_field( $query_arg['date_range_from'] );
			}
			$date_range_to = '';
			if ( isset( $query_arg['date_range_to'] ) ) {
				$date_range_to = sanitize_text_field( $query_arg['date_range_to'] );
			}
			?>

			<div class="date-range-input">
				<input type="text" name="date_range_from" class="tutor_report_datepicker"
				       value="<?php echo esc_attr( $date_range_from ); ?>" autocomplete="off"
				       placeholder="<?php echo date( 'Y-m-d', strtotime( 'last sunday midnight' ) ); ?>"/>
				<i class="fal fa-calendar"></i>
			</div>

			<div class="date-range-input">
				<input type="text" name="date_range_to" class="tutor_report_datepicker"
				       value="<?php echo esc_attr( $date_range_to ); ?>" autocomplete="off"
				       placeholder="<?php echo date( 'Y-m-d' ); ?>"/>
				<i class="fal fa-calendar"></i>
			</div>

			<div class="date-range-input">
				<button type="submit"><i class="fal fa-search"></i></button>
			</div>
		</form>
	</div>
</div>
