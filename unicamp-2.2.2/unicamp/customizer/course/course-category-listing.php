<?php
$section  = 'course_category_listing';
$priority = 1;
$prefix   = 'course_category_listing_';

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
	'settings' => 'course_category_listing_header_type',
	'label'    => esc_html__( 'Header Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
	'choices'  => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'course_category_listing_header_overlay',
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
	'settings' => 'course_category_listing_header_skin',
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
	'type'     => 'select',
	'settings' => 'course_category_listing_title_bar_layout',
	'label'    => esc_html__( 'Title Bar Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
	'choices'  => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Sidebar', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_category_listing_page_sidebar_1',
	'label'    => esc_html__( 'Sidebar 1', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'course_category_listing_sidebar',
	'choices'  => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_category_listing_page_sidebar_2',
	'label'    => esc_html__( 'Sidebar 2', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'none',
	'choices'  => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'course_category_listing_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'right',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'course_category_listing_single_sidebar_width',
	'label'       => esc_html__( 'Single Sidebar Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '27',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'course_category_listing_single_sidebar_offset',
	'label'       => esc_html__( 'Single Sidebar Offset', 'unicamp' ),
	'description' => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '30px',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_category_listing_page_sidebar_style',
	'label'    => esc_html__( 'Sidebar Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '01',
	'choices'  => [
		'01' => '01',
		'02' => '02',
		'03' => '03',
	],
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
	'settings' => 'course_category_preset',
	'label'    => esc_html__( 'Course Category Preset', 'unicamp' ),
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
			'label'    => esc_html__( 'Layout 01', 'unicamp' ),
			'settings' => array(
				'course_category_listing_page_sidebar_1'        => 'course_category_listing_sidebar',
				'course_category_listing_page_sidebar_position' => 'right',
				'course_category_listing_grid_style'            => '01',
			),
		),
		'02' => array(
			'label'    => esc_html__( 'Layout 02', 'unicamp' ),
			'settings' => array(
				'course_category_listing_body_background'  => 'grey',
				'course_category_listing_site_layout'      => 'small',
				'course_category_listing_title_bar_layout' => '02',
				'course_category_listing_page_sidebar_1'   => 'none',
				'course_category_listing_grid_style'       => '02',
				'course_category_listing_filtering'        => '1',
			),
		),
		'03' => array(
			'label'    => esc_html__( 'Layout 03', 'unicamp' ),
			'settings' => array(
				'course_category_listing_page_sidebar_1'        => 'course_category_listing_sidebar',
				'course_category_listing_page_sidebar_position' => 'right',
				'course_category_listing_grid_style'            => '03',
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'course_category_listing_body_background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls site background.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Helper::get_site_background_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_category_listing_site_layout',
	'label'    => esc_html__( 'Grid Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => Unicamp_Helper::get_site_layout_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_category_listing_grid_style',
	'label'    => esc_html__( 'Grid Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '01',
	'choices'  => array(
		'01' => esc_attr__( 'Default', 'unicamp' ),
		'02' => esc_attr__( 'Style 02', 'unicamp' ),
		'03' => esc_attr__( 'Style 03', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'course_category_listing_filtering',
	'label'    => esc_html__( 'Filtering Bar', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Grid Columns', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'course_category_listing_lg_columns',
	'label'     => esc_html__( 'Large Device', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 4,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'course_category_listing_md_columns',
	'label'     => esc_html__( 'Medium Device', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 2,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'course_category_listing_sm_columns',
	'label'     => esc_html__( 'Small Device', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 1,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );
