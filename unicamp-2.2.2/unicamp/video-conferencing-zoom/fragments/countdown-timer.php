<?php
/**
 * The template for displaying meeting countdown timer
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/fragments/countdown-timer.php.
 *
 * @author      Deepen Bajracharya (CodeManas)
 * @created     3.0.0
 * @updated     3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $zoom;

if ( ! vczapi_pro_version_active() && ! empty( $zoom ) && vczapi_pro_check_type( $zoom['api']->type ) ) {
	?>
	<div class="dpn-zvc-sidebar-box">
		<p><?php esc_html_e( 'PRO version is required for this meeting to be displayed.', 'unicamp' ); ?></p>
	</div>
	<?php
}

if ( ! empty( $zoom['api']->start_time ) ) {
	?>
	<div class="dpn-zvc-sidebar-box dpn-zvc-timer-wrap">
		<div class="dpn-zvc-timer" id="dpn-zvc-timer" data-date="<?php echo esc_attr( $zoom['api']->start_time ); ?>"
		     data-state="<?php echo esc_attr( ! empty( $zoom['api']->state ) ? $zoom['api']->state : false ); ?>"
		     data-tz="<?php echo esc_attr( $zoom['api']->timezone ); ?>">
			<div class="dpn-zvc-timer-cell">
				<div class="dpn-zvc-timer-cell-number">
					<div id="dpn-zvc-timer-days"></div>
				</div>
				<div class="dpn-zvc-timer-cell-string"><?php esc_html_e( 'Days', 'unicamp' ); ?></div>
			</div>
			<div class="dpn-zvc-timer-cell">
				<div class="dpn-zvc-timer-cell-number">
					<div id="dpn-zvc-timer-hours"></div>
				</div>
				<div class="dpn-zvc-timer-cell-string"><?php esc_html_e( 'Hours', 'unicamp' ); ?></div>
			</div>
			<div class="dpn-zvc-timer-cell">
				<div class="dpn-zvc-timer-cell-number">
					<div id="dpn-zvc-timer-minutes"></div>
				</div>
				<div class="dpn-zvc-timer-cell-string"><?php esc_html_e( 'Mins', 'unicamp' ); ?></div>
			</div>
			<div class="dpn-zvc-timer-cell">
				<div class="dpn-zvc-timer-cell-number">
					<div id="dpn-zvc-timer-seconds"></div>
				</div>
				<div class="dpn-zvc-timer-cell-string"><?php esc_html_e( 'Secs', 'unicamp' ); ?></div>
			</div>
		</div>
	</div>
	<?php
}
