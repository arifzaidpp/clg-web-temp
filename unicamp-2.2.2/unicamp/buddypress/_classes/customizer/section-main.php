<?php
$section  = 'buddypress';
$priority = 1;
$prefix   = 'buddypress_';

$sidebar_positions   = Unicamp_Helper::get_list_sidebar_positions();
$registered_sidebars = Unicamp_Helper::get_registered_sidebars();

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => $prefix . 'page_top_bar_type',
	'label'    => esc_html__( 'Top Bar Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => $prefix . 'page_header_type',
	'label'    => esc_html__( 'Header Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'page_header_overlay',
	'label'    => esc_html__( 'Header Overlay', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
	'choices'  => array(
		''  => esc_html__( 'Use Global', 'unicamp' ),
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'page_header_skin',
	'label'    => esc_html__( 'Header Skin', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
	'choices'  => array(
		''      => esc_html__( 'Use Global', 'unicamp' ),
		'dark'  => esc_html__( 'Dark', 'unicamp' ),
		'light' => esc_html__( 'Light', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => $prefix . 'body_background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls site background.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Helper::get_site_background_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => $prefix . 'site_layout',
	'label'    => esc_html__( 'Grid Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => Unicamp_Helper::get_site_layout_options(),
) );
