<?php
/**
 * Template part for displaying event meta on single page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/single/content-hero-02.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

$time = wpems_get_time( 'Y-m-d H:i', null, false );
?>
<div class="entry-hero-section">
	<div class="container container-small">
		<div class="entry-header">
			<?php Unicamp_Event::instance()->entry_categories(); ?>

			<?php wpems_get_template( 'single/title.php' ); ?>

			<?php
			/**
			 * tp_event_loop_event_countdown hook
			 */
			do_action( 'tp_event_loop_event_countdown' );
			?>
		</div>
	</div>
</div>
