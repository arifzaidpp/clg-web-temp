<?php
/**
 * Template for displaying course reviews
 *
 * @author        ThemeMove
 * @theme-since   1.0.0
 * @theme-version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$sidebar = Unicamp::setting( 'course_page_sidebar_1' );

if ( empty( $sidebar ) ) {
	return;
}

Unicamp_Sidebar::instance()->generated_sidebar( $sidebar );
