<?php
/**
 * The Template for displaying content events style 01.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/content-event-grid-01.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * tp_event_before_loop_event hook
 */
do_action( 'tp_event_before_loop_event' );

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

$extra_classes = 'grid-item';
?>

<div id="event-<?php the_ID(); ?>" <?php post_class( $extra_classes ); ?>>

	<?php
	/**
	 * tp_event_before_loop_event_summary hook
	 *
	 * @hooked tp_event_show_event_sale_flash - 10
	 * @hooked tp_event_show_event_images - 20
	 */
	do_action( 'tp_event_before_loop_event_item' );
	?>

	<a href="<?php the_permalink() ?>" class="unicamp-box link-secret">
		<div class="event-caption">
			<?php
			$date_start = get_post_meta( get_the_ID(), 'tp_event_date_start', true );
			$time_from  = $date_start ? strtotime( $date_start ) : time();
			?>
			<div class="event-start-date">
				<span class="event-start-day"><?php echo wp_date( 'd', $time_from ); ?></span>
				<span class="event-start-month"><?php echo wp_date( 'F, Y', $time_from ); ?></span>
			</div>

			<?php wpems_get_template( 'loop/title-no-link.php' ); ?>

			<?php
			$time_start = wpems_event_start( get_option( 'time_format' ) );
			$time_end   = wpems_event_end( get_option( 'time_format' ) );
			?>
			<div class="event-time"><?php echo esc_html( $time_start . ' - ' . $time_end ); ?></div>

			<?php wpems_get_template( 'loop/location.php' ); ?>
		</div>
	</a>

	<?php
	/**
	 * tp_event_after_loop_event_item hook
	 *
	 * @hooked tp_event_show_event_sale_flash - 10
	 * @hooked tp_event_show_event_images - 20
	 */
	do_action( 'tp_event_after_loop_event_item' );
	?>
</div>

<?php do_action( 'tp_event_after_loop_event' ); ?>
