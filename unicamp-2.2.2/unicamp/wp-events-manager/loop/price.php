<?php
/**
 * Template part for displaying event price on loop.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/price.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$event = new WPEMS_Event( $post );
?>
<div class="event-price price">
	<?php printf( '%s', $event->is_free() ? esc_html__( 'Free', 'unicamp' ) : wpems_format_price( $event->get_price() ) ); ?>
</div>
