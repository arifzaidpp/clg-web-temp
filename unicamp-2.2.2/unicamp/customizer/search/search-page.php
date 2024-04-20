<?php
$section  = 'search_page';
$priority = 1;
$prefix   = 'search_page_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'search_page_filter',
	'label'       => esc_html__( 'Search Results Filter', 'unicamp' ),
	'description' => esc_html__( 'Controls the type of content that displays in search results.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'all',
	'choices'     => array(
		'all'  => esc_html__( 'All Post Types and Pages', 'unicamp' ),
		'page' => esc_html__( 'Only Pages', 'unicamp' ),
		'post' => esc_html__( 'Only Blog Posts', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'search_page_number_results',
	'label'       => esc_html__( 'Number of Search Results Per Page', 'unicamp' ),
	'description' => esc_html__( 'Controls the number of search results per page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 10,
	'choices'     => array(
		'min'  => 1,
		'max'  => 100,
		'step' => 1,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'search_page_search_form_display',
	'label'       => esc_html__( 'Search Form Display', 'unicamp' ),
	'description' => esc_html__( 'Controls the display of the search form on the search results page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'disabled',
	'choices'     => array(
		'below'    => esc_html__( 'Below Result List', 'unicamp' ),
		'above'    => esc_html__( 'Above Result List', 'unicamp' ),
		'disabled' => esc_html__( 'Hide', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'textarea',
	'settings'    => 'search_page_no_results_text',
	'label'       => esc_html__( 'No Results Text', 'unicamp' ),
	'description' => esc_html__( 'Enter the text that displays on search no results page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'unicamp' ),
) );
