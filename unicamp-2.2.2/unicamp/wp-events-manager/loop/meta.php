<?php
/**
 * Template part for displaying event meta on loop.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/meta.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

$date_start  = get_post_meta( get_the_ID(), 'tp_event_date_start', true );
$date_start  = ! empty( $date_start ) ? strtotime( $date_start ) : time();
$date_string = wp_date( get_option( 'date_format' ), $date_start );

$time_start = wpems_event_start( get_option( 'time_format' ) );
$time_end   = wpems_event_end( get_option( 'time_format' ) );
$location   = get_post_meta( get_the_ID(), Unicamp_Event::POST_META_SHORT_LOCATION, true );
?>
<div class="event-meta">
	<div class="meta-item">
		<span class="meta-label"><i class="meta-label-icon far fa-clock"></i></span>
		<span class="meta-value"><?php echo esc_html( $time_start . ' - ' . $time_end ); ?></span>
	</div>
	<div class="meta-item">
		<span class="meta-label"><i class="meta-label-icon far fa-calendar"></i></span>
		<span class="meta-value"><?php echo esc_html( $date_string ); ?></span>
	</div>
	<?php if ( $location ) : ?>
		<div class="meta-item">
			<span class="meta-label"><i class="meta-label-icon far fa-map-marker-alt"></i></span>
			<span class="meta-value"><?php echo esc_html( $location ); ?></span>
		</div>
	<?php endif; ?>
</div>
