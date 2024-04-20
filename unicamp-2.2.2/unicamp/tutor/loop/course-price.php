<?php
/**
 * Course loop price
 *
 * @since   v.1.0.0
 * @author  themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

defined( 'ABSPATH' ) || exit;

$price_html = '<div class="tutor-price course-free"><p class="price">$0</p></div>';

if ( tutor_utils()->is_course_purchasable() ) {
	$course_id  = get_the_ID();
	$product_id = tutor_utils()->get_course_product_id( $course_id );
	$product    = wc_get_product( $product_id );

	if ( $product ) {
		$price_html = '<div class="tutor-price"><p class="price">' . $product->get_price_html() . '</p></div>';
	}
}

echo '<div class="course-loop-price">' . $price_html . '</div>';
