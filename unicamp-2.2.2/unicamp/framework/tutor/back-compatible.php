<?php
/**
 * Re-add tutor functions from version 1.x.x
 */

/**
 * @param bool $echo
 *
 * @return mixed
 *
 * @show  progress bar about course complete
 *
 * @since v.1.0.0
 */

if ( ! function_exists( 'tutor_course_completing_progress_bar' ) ) {
	function tutor_course_completing_progress_bar( $echo = true ) {
		ob_start();
		tutor_load_template( 'single.course.enrolled.completing-progress' );
		$output = apply_filters( 'tutor_course/single/completing-progress-bar', ob_get_clean() );

		if ( $echo ) {
			echo tutor_kses_html( $output );
		}

		return $output;
	}
}

/**
 * @param bool $echo
 *
 * @return string
 *
 * Get Only add to cart form
 */
if ( ! function_exists( 'tutor_single_course_add_to_cart' ) ) {
	function tutor_single_course_add_to_cart( $echo = true ) {
		ob_start();

		$output = '';

		$template = tutor_utils()->is_course_fully_booked( null ) ? 'closed-enrollment' : 'add-to-cart';

		tutor_load_template( 'single.course.' . $template );
		$output .= apply_filters( 'tutor_course/single/' . $template, ob_get_clean() );

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}
}

if ( ! function_exists( 'tutor_course_mark_complete_html' ) ) {
	function tutor_course_mark_complete_html( $echo = true ) {
		ob_start();
		tutor_load_template( 'single.course.complete_form' );
		$output = apply_filters( 'tutor_course/single/complete_form', ob_get_clean() );

		if ( $echo ) {
			echo tutor_kses_html( $output );
		}

		return $output;
	}
}

if ( ! function_exists( 'tutor_course_archive_pagination' ) ) {
	function tutor_course_archive_pagination( $echo = true ) {
		ob_start();
		tutor_load_template( 'loop.tutor-pagination' );

		$output = apply_filters( 'tutor_course_archive_pagination', ob_get_clean() );
		if ( $echo ) {
			echo tutor_kses_html( $output );
		}

		return $output;
	}
}
