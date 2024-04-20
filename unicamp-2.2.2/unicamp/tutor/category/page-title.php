<?php
/**
 * Template for displaying page title section on category page.
 *
 * @since   1.0.0
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! Unicamp_Tutor::instance()->is_category() || '1' !== Unicamp::setting( 'course_category_page_heading' ) ) {
	return;
}

$queried_object = get_queried_object();
?>
<div class="row row-page-title">
	<div class="col-md-12">
		<h3 class="archive-section-heading">
			<?php printf( esc_html__( 'All %s Courses', 'unicamp' ), $queried_object->name ); ?>
		</h3>
	</div>
</div>
