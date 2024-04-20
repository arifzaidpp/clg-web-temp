<?php
/**
 * The Template for displaying location in single event page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/single/location.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php if ( wpems_event_location() ): ?>
	<div class="entry-location">
		<?php wpems_get_location_map(); ?>
	</div>
<?php endif; ?>
