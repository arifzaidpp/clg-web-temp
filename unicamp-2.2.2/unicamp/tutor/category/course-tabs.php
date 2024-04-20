<?php
/**
 * Template for displaying Popular Topics section on category page.
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

/**
 * Filter tabs and allow child theme or third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 */
$course_tabs = apply_filters( 'unicamp_category_course_tabs', array() );

if ( empty( $course_tabs ) ) {
	return;
}
?>
<div class="course-cat-section category-course-tabs">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="unicamp-tabpanel unicamp-tabpanel-horizontal archive-tabs-section">
					<ul class="unicamp-nav-tabs">
						<?php foreach ( $course_tabs as $key => $course_tab ) : ?>
							<li class="<?php echo esc_attr( $key ); ?>_tab"
							    role="tab"
							    aria-controls="tab-<?php echo esc_attr( $key ); ?>">
								<a href="#tab-<?php echo esc_attr( $key ); ?>">
									<span class="nav-tab-title"><?php echo wp_kses( $course_tab['title'], [
											'mark' => [],
										] ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<div class="unicamp-tab-content">
						<?php foreach ( $course_tabs as $key => $course_tab ) : ?>
							<div class="tab-panel">
								<div class="tab-mobile-heading">
									<?php echo wp_kses( $course_tab['title'], [
										'mark' => [],
									] ); ?>
								</div>
								<div class="tab-content">
									<?php
									if ( isset( $course_tab['callback'] ) ) {
										call_user_func( $course_tab['callback'], $key, $course_tab );
									}
									?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
