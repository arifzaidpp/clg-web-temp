<?php

/**
 * Course loop price
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$price_html = '<div class="tutor-price course-free"><p class="price">' . edd_currency_filter( edd_format_amount( 0 ) ) . '</p></div>';

if ( tutor_utils()->is_course_purchasable() ) {
	$course_id  = get_the_ID();
	$product_id = tutor_utils()->get_course_product_id( $course_id );
	$download   = new EDD_Download( $product_id );

	if ( $download->ID ) {
		$price_text = edd_currency_filter( edd_format_amount( $download->price ) );
		$price_html = '<div class="tutor-price"><p class="price">' . $price_text . '</p></div>';
	}
}

echo '<div class="course-loop-price">' . $price_html . '</div>';
