<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$primary_color = Unicamp::setting( 'primary_color', Unicamp::PRIMARY_COLOR );
$dataset_label = esc_html__( 'Earning', 'unicamp' );
?>
<canvas id="tutorChart" style="width: 100%; height: 400px;"></canvas>
<script>
	var ctx = document.getElementById( 'tutorChart' ).getContext( '2d' );
	var tutorChart = new Chart( ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode( array_keys( $chartData ) ); ?>,
			datasets: [
				{
					label: '<?php echo '' . $dataset_label; ?>',
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


