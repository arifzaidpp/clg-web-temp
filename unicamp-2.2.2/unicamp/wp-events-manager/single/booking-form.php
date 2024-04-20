<?php
/**
 * Template part for displaying booking box on single page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/single/booking-form.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="entry-booking-form-bar entry-event-right-bar">
	<div class="inner">
		<?php
		/**
		 * tp_event_after_single_event hook
		 *
		 * @hooked tp_event_after_single_event - 10
		 */
		do_action( 'tp_event_after_single_event' );
		?>

		<?php wpems_get_template( 'single/share.php' ); ?>
	</div>
</div>
