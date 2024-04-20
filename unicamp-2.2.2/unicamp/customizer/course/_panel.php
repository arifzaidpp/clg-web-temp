<?php
$panel    = 'course';
$priority = 1;

Unicamp_Kirki::add_section( 'course_archive', array(
	'title'    => esc_html__( 'Course Archive', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'course_category', array(
	'title'    => esc_html__( 'Course Category', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'course_category_listing', array(
	'title'    => esc_html__( 'Course Category Listing', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'course_single', array(
	'title'    => esc_html__( 'Course Single', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );
