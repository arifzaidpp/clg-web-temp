<?php
/**
 * Course benefits
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

$benefits = $unicamp_course->get_benefits();

if ( empty( $benefits ) ) {
	return;
}
?>
<div class="course-loop-benefits">
	<h6 class="course-loop-benefits-heading"><?php esc_html_e( 'What you\'ll learn', 'unicamp' ); ?></h6>
	<div class="course-loop-benefits-list">
		<?php foreach ( $benefits as $benefit ) : ?>
			<div class="course-loop-benefit"><?php echo esc_html( $benefit ); ?></div>
		<?php endforeach; ?>
	</div>
</div>
