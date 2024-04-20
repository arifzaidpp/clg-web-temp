<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */
get_header();

if ( bp_is_user() ) {
	get_template_part( 'buddypress/members/content-single' );
} elseif ( bp_is_group_single() ) {
	get_template_part( 'buddypress/groups/content-single' );
} elseif ( bp_is_activity_directory() ) {
	get_template_part( 'buddypress/activity/content-directory' );
} else {
	unicamp_load_template( 'page/content-single' );
}

get_footer();
