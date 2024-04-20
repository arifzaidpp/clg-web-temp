<?php
/**
 * The Template for displaying content single event.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/content-single-event.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="tp_event-<?php the_ID(); ?>" <?php post_class( 'tp_single_event' ); ?>>
	<?php
	/**
	 * tp_event_before_single_event hook
	 */
	do_action( 'tp_event_before_single_event' );
	?>

	<div class="summary entry-summary">

		<div class="entry-header">
			<h3 class="entry-event-heading box-title-with-separator"><?php esc_html_e( 'About The Event', 'unicamp' ); ?></h3>
		</div>

		<div class="entry-event-section entry-event-details">
			<div class="tm-sticky-parent">
				<div class="row">
					<div class="col-md-8">
						<div class="tm-sticky-column">
							<?php
							/**
							 * tp_event_single_event_content hook
							 */
							do_action( 'tp_event_single_event_content' );
							?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="tm-sticky-column">
							<?php wpems_get_template( 'single/booking-form.php' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="entry-event-section entry-event-location">
			<h3 class="entry-event-heading box-title-with-separator"><?php esc_html_e( 'Location', 'unicamp' ); ?></h3>

			<div class="tm-sticky-parent">
				<div class="row">
					<div class="col-md-8">
						<div class="tm-sticky-column">
							<?php wpems_get_template( 'single/location.php' ); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="tm-sticky-column">
							<?php wpems_get_template( 'single/location-details-2.php' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php wpems_get_template( 'single/speakers-carousel.php' ); ?>

		<?php if ( '1' === Unicamp::setting( 'single_event_comment_enable' ) && ( comments_open() || get_comments_number() ) ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>
	</div>
</article>
