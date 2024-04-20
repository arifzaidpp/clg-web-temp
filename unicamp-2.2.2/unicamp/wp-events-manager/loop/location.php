<?php
/**
 * The Template for displaying location in loop.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/location.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;

$location = get_post_meta( get_the_ID(), Unicamp_Event::POST_META_SHORT_LOCATION, true );
?>
<?php if ( $location ): ?>
	<div class="event-location">
		<?php echo esc_html( $location ); ?>
	</div>
<?php endif;
