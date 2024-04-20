<?php
$section  = 'title_bar';
$priority = 1;
$prefix   = 'title_bar_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => $prefix . 'layout',
	'label'       => esc_html__( 'Global Title Bar', 'unicamp' ),
	'description' => esc_html__( 'Select default title bar that displays on all pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '01',
	'choices'     => Unicamp_Title_Bar::instance()->get_list(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Heading', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'search_title',
	'label'       => esc_html__( 'Search Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on search results page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Search results for: ', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'home_title',
	'label'       => esc_html__( 'Home Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text that displays on front latest posts page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'News and Blog', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'archive_category_title',
	'label'       => esc_html__( 'Archive Category Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on archive category page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Category: ', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'archive_tag_title',
	'label'       => esc_html__( 'Archive Tag Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on archive tag page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Tag: ', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'archive_author_title',
	'label'       => esc_html__( 'Archive Author Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on archive author page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Author: ', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'archive_year_title',
	'label'       => esc_html__( 'Archive Year Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on archive year page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Year: ', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'archive_month_title',
	'label'       => esc_html__( 'Archive Month Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on archive month page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Month: ', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => $prefix . 'archive_day_title',
	'label'       => esc_html__( 'Archive Day Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text prefix that displays on archive day page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Day: ', 'unicamp' ),
) );
