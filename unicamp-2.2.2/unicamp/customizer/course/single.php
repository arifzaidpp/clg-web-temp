<?php
$section  = 'course_single';
$priority = 1;
$prefix   = 'single_course_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'course_single_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select default header style that displays on all single course pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'course_single_header_overlay',
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
	'settings' => 'course_single_header_skin',
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
	'default'  => '<div class="big_title">' . esc_html__( 'Page Title Bar', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'course_single_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on all single course pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
	'default'     => '04',
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
	'settings'    => 'course_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display below pricing box on single courses pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Others', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'preset',
	'settings' => 'course_single_preset',
	'label'    => esc_html__( 'Course Layout Preset', 'unicamp' ),
	'section'  => $section,
	'default'  => '-1',
	'priority' => $priority++,
	'multiple' => 0,
	'choices'  => array(
		'-1' => array(
			'label'    => esc_html__( 'None', 'unicamp' ),
			'settings' => array(),
		),
		'01' => array(
			'label'    => esc_html__( 'Preset 01', 'unicamp' ),
			'settings' => array(),
		),
		'02' => array(
			'label'    => esc_html__( 'Preset 02', 'unicamp' ),
			'settings' => array(
				'course_single_title_bar_layout' => '07',
				'single_course_top_info_skin'    => 'dark',
			),
		),
		'03' => array(
			'label'    => esc_html__( 'Preset 03', 'unicamp' ),
			'settings' => array(
				'course_single_title_bar_layout' => Unicamp::TITLE_BAR_MINIMAL_TYPE,
				'single_course_layout'           => '02',
			),
		),
		'04' => array(
			'label'    => esc_html__( 'Preset 04', 'unicamp' ),
			'settings' => array(
				'course_single_title_bar_layout' => Unicamp::TITLE_BAR_MINIMAL_TYPE,
				'course_page_sidebar_1'          => 'course_single_sidebar',
				'single_course_layout'           => '03',
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'single_course_layout',
	'label'       => esc_html__( 'Layout', 'unicamp' ),
	'description' => esc_html__( 'Select default layout for single course pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '01',
	'choices'     => array(
		'01' => esc_attr__( 'Layout 01', 'unicamp' ),
		'02' => esc_attr__( 'Layout 02', 'unicamp' ),
		'03' => esc_attr__( 'Layout 03', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'single_course_top_info_skin',
	'label'    => esc_html__( 'Top Info Skin', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'light',
	'choices'  => array(
		'light' => esc_attr__( 'Light', 'unicamp' ),
		'dark'  => esc_attr__( 'Dark', 'unicamp' ),
	),
) );
