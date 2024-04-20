<?php
/**
 * The template for displaying meeting details of zoom
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/fragments/meeting-details.php.
 *
 * @author     Deepen.
 * @created_on 11/19/19
 * @modified   3.3.0
 */

global $zoom;

if ( ! empty( $zoom['user'] ) && ! empty( $zoom['user']->first_name ) ) {
	$hosted_by = $zoom['user']->first_name . ' ' . $zoom['user']->last_name;
} else {
	$hosted_by = get_the_author();
}
?>
<div class="dpn-zvc-sidebar-box box-style-02">
	<div class="dpn-zvc-sidebar-tile">
		<h3><?php esc_html_e( 'Details', 'unicamp' ); ?></h3>
	</div>
	<div class="dpn-zvc-sidebar-content">
		<div class="dpn-zvc-sidebar-content-list">
			<span class="name"><?php esc_html_e( 'Hosted By', 'unicamp' ); ?></span>
			<span class="value"><?php echo esc_html( $hosted_by ); ?></span>
		</div>
		<?php if ( ! empty( $zoom['start_date'] ) ) { ?>
			<div class="dpn-zvc-sidebar-content-list">
				<span class="name"><?php esc_html_e( 'Start', 'unicamp' ); ?></span>
				<span
					class="value sidebar-start-time"><?php echo wp_date( 'F j, Y @ g:i a', strtotime( $zoom['start_date'] ) ); ?></span>
			</div>
		<?php } ?>
		<?php if ( ! empty( $zoom['terms'] ) ) { ?>
			<div class="dpn-zvc-sidebar-content-list">
				<span class="name"><?php esc_html_e( 'Category', 'unicamp' ); ?></span>
				<span class="value"><?php echo implode( ', ', $zoom['terms'] ); ?></span>
			</div>
		<?php } ?>
		<?php if ( ! empty( $zoom['duration'] ) ) { ?>
			<?php
			$duration = intval( $zoom['duration'] );
			?>
			<div class="dpn-zvc-sidebar-content-list">
				<span class="name"><?php esc_html_e( 'Duration', 'unicamp' ); ?></span>
				<span
					class="value"><?php printf( _n( '%s minute', '%s minutes', $duration, 'unicamp' ), $duration ); ?></span>
			</div>
		<?php } ?>
		<?php if ( ! empty( $zoom['timezone'] ) ) { ?>
			<div class="dpn-zvc-sidebar-content-list">
				<span class="name"><?php esc_html_e( 'Timezone', 'unicamp' ); ?></span>
				<span class="value"><?php echo esc_html( $zoom['timezone'] ); ?></span>
			</div>
		<?php } ?>
		<p class="dpn-zvc-display-or-hide-localtimezone-notice"><?php printf( __( '%sNote%s: Countdown time is shown based on your local timezone.', 'unicamp' ), '<strong class="heading-color">', '</strong>' ); ?></p>
	</div>
</div>
