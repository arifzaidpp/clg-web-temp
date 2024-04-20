<?php
/**
 * Closed for Enrollment
 *
 * @since         v.1.6.4
 * @author        themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 *
 * @theme-since   2.0.0
 * @theme-version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'tutor_course/single/closed-enrollment/before' );
?>

<div class="tutor-single-add-to-cart-box">
	<div class="tutor-course-enroll-wrap">
		<button type="button" class="tutor-button tutor-button-block" disabled="disabled">
			<span><?php esc_html_e( '100% Booked', 'unicamp' ); ?></span>
			<?php esc_html_e( 'Closed for Enrollment', 'unicamp' ); ?>
		</button>
	</div>
</div>

<?php do_action( 'tutor_course/single/closed-enrollment/after' ); ?>
