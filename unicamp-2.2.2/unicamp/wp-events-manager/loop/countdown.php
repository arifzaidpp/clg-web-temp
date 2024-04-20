<?php
/**
 * The Template for displaying countdown in single event page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/countdown.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;

$current_time = current_time( 'Y-m-d H:i' );
$time         = wpems_get_time( 'Y-m-d H:i', null, false );

if ( $time < $current_time ) {
	return;
}

$date = new DateTime( date( 'Y-m-d H:i', strtotime( $time ) ) );
?>
<div id="entry-event-countdown" class="entry-event-countdown"
     data-time="<?php echo esc_attr( $date->format( 'Y-m-d H:i:s' ) ); ?>">
</div>
