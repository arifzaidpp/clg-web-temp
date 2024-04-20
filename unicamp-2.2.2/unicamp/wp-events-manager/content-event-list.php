<?php
/**
 * The Template for displaying content events style list.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/content-event-list.php
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

	<div class="unicamp-box">
		<a href="<?php the_permalink(); ?>" class="event-image unicamp-image">
			<div class="post-thumbnail">
				<?php Unicamp_Image::the_post_thumbnail( [
					'size' => '480x272',
				] ); ?>
			</div>
		</a>
		<div class="event-caption">
			<div class="event-caption-left">
				<?php Unicamp_Event::instance()->event_loop_category( [ 'number' => 2, 'separator' => ' / ' ] ); ?>

				<?php wpems_get_template( 'loop/title.php' ); ?>

				<?php wpems_get_template( 'loop/price.php' ); ?>

				<div class="event-excerpt">
					<?php Unicamp_Templates::excerpt( [
						'limit' => 26,
						'type'  => 'word',
					] ); ?>
				</div>
			</div>
			<div class="event-caption-right">
				<?php wpems_get_template( 'loop/meta.php' ); ?>

				<?php wpems_get_template( 'loop/read-more-small.php' ); ?>
			</div>
		</div>
	</div>

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
