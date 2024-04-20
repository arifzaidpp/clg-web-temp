<?php
/**
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

global $post; ?>

<h3><?php esc_html_e( 'Wishlist', 'unicamp' ); ?></h3>

<div class="tutor-dashboard-content-inner">
	<?php
	$wishlists = tutor_utils()->get_wishlist();

	if ( is_array( $wishlists ) && count( $wishlists ) ) : ?>
		<?php
		global $unicamp_course;
		$unicamp_course_clone = $unicamp_course;
		?>

		<?php tutor_load_template( 'loop.custom.loop-grid-start' ); ?>

		<?php foreach ( $wishlists as $post ): ?>
			<?php
			setup_postdata( $post );

			/***
			 * Setup course object.
			 */
			$unicamp_course = new Unicamp_Course();

			/**
			 * @hook tutor_course/archive/before_loop_course
			 * @type action
			 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
			 */
			do_action( 'tutor_course/archive/before_loop_course' );

			tutor_load_template( 'loop.custom.content-course-grid-01' );

			/**
			 * @hook tutor_course/archive/after_loop_course
			 * @type action
			 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
			 */
			do_action( 'tutor_course/archive/after_loop_course' );
			?>

		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>

		<?php tutor_load_template( 'loop.custom.loop-grid-end' ); ?>
		<?
		/**
		 * Reset course object.
		 */
		$unicamp_course = $unicamp_course_clone;
		?>
	<?php else: ?>
		<div class="dashboard-no-content-found">
			<?php esc_html_e( 'You haven\'t any courses on the wishlist yet.', 'unicamp' ); ?>
		</div>
	<?php endif; ?>
</div>
