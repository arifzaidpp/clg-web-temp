<?php
/**
 * Template for displaying add to wishlist button.
 *
 * @since   v.1.0.0
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.2.2
 */

defined( 'ABSPATH' ) || exit;

global $unicamp_course;

$button_text            = $unicamp_course->is_wishlisted() ? esc_html__( 'Remove from wishlist', 'unicamp' ) : esc_html__( 'Add to wishlist', 'unicamp' );
$wrapper_button_classes = 'course-loop-wishlist-button';
$wrapper_button_classes .= $unicamp_course->is_wishlisted() ? ' added' : '';

$button_classes = 'unicamp-course-wishlist-btn';

if ( ! is_user_logged_in() ) {
	$button_classes .= ' open-popup-login';
}

Unicamp_Templates::render_button( [
	'link'          => [
		'url' => 'javascript:void(0);',
	],
	'text'          => $button_text,
	'icon'          => 'far fa-heart',
	'style'         => 'text',
	'extra_class'   => $button_classes,
	'wrapper_class' => $wrapper_button_classes,
	'attributes'    => [
		'data-course-id' => get_the_ID(),
	],
] );
