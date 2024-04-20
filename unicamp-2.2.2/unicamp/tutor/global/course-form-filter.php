<?php
/**
 * The Template for displaying course form filter bar.
 *
 * Override this template by copying it to unicamp-child/tutor/global/course-form-filter.php
 *
 * @author  ThemeMove
 * @package Unicamp/Tutor/Template
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$filtering_bar_on = Unicamp::setting( 'course_category_listing_filtering' );

if ( '1' !== $filtering_bar_on ) {
	return;
}

$type = 'Unicamp_WP_Widget_Course_Form_Filter';
global $wp_widget_factory;

if ( ! is_object( $wp_widget_factory ) || ! isset( $wp_widget_factory->widgets ) || ! isset( $wp_widget_factory->widgets[ $type ] ) ) {
	return;
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="archive-filtering-form-bar course-filtering-form-bar">
				<?php the_widget( $type ); ?>
			</div>
		</div>
	</div>
</div>

