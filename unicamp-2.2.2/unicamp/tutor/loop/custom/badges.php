<?php
/**
 * Course loop badges
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

$purchasable = tutor_utils()->is_course_purchasable();
?>
<div class="course-loop-badges">
	<?php if ( $unicamp_course->is_featured() ) : ?>
		<div class="tutor-course-badge hot"><?php esc_html_e( 'Featured', 'unicamp' ); ?></div>
	<?php endif; ?>

	<?php if ( ! $purchasable ) : ?>
		<div class="tutor-course-badge free"><?php esc_html_e( 'Free', 'unicamp' ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $unicamp_course->on_sale_text() ) ) : ?>
		<div class="tutor-course-badge onsale"><?php echo '' . $unicamp_course->on_sale_text(); ?></div>
	<?php endif; ?>
</div>
