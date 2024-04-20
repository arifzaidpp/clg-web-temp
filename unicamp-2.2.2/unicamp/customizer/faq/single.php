<?php
$section  = 'faq_single';
$priority = 1;
$prefix   = 'single_faq_';

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
	'type'        => 'select',
	'settings'    => 'faq_single_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select default header style that displays on all single faq pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'faq_single_header_overlay',
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
	'settings' => 'faq_single_header_skin',
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
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Sidebar', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'faq_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on single faq pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'faq_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'faq_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on single faq pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'faq_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'right',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'faq_page_sidebar_style',
	'label'    => esc_html__( 'Sidebar Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '03',
	'choices'  => [
		'01' => '01',
		'02' => '02',
		'03' => '03',
	],
) );
