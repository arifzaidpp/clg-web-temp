<?php
/**
 * Template for displaying course benefits
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

$benefits = $unicamp_course->get_benefits();

if ( empty( $benefits ) ) {
	return;
}

do_action( 'tutor_course/single/before/benefits' );
?>

<div class="tutor-single-course-segment tutor-course-benefits-wrap">
	<h4 class="tutor-segment-title"><?php esc_html_e( 'Learning Objectives', 'unicamp' ); ?></h4>
	<div class="tutor-course-benefits-content">
		<div class="tutor-course-benefits-items">
			<?php foreach ( $benefits as $benefit ) : ?>
				<div class="tutor-course-benefit-item">
					<div class="benefit-content">
						<span class="benefit-icon"></span>
						<span class="benefit-text"><?php Unicamp_Helper::e( $benefit ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php do_action( 'tutor_course/single/after/benefits' ); ?>

