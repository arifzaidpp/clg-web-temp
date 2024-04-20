<?php
/**
 * The Template for displaying filtering form bar.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/global/filtering-form.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;

$filtering_bar_on = Unicamp::setting( 'event_archive_filtering' );

if ( '1' !== $filtering_bar_on ) {
	return;
}

$type = 'Unicamp_WP_Widget_Event_Filtering';
global $wp_widget_factory;

if ( ! is_object( $wp_widget_factory ) || ! isset( $wp_widget_factory->widgets ) || ! isset( $wp_widget_factory->widgets[ $type ] ) ) {
	return;
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="archive-filtering-form-bar event-filtering-form-bar">
				<?php the_widget( $type ); ?>
			</div>
		</div>
	</div>
</div>

