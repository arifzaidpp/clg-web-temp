<?php
$section  = 'course_archive';
$priority = 1;
$prefix   = 'course_archive_';

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
	'settings'    => 'course_archive_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select header style that displays on course archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'course_archive_header_overlay',
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
	'settings' => 'course_archive_header_skin',
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
	'settings'    => 'course_archive_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on archive course pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
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
	'settings'    => 'course_archive_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on course archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'course_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'course_archive_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on course archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'course_archive_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'right',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'course_archive_single_sidebar_width',
	'label'       => esc_html__( 'Single Sidebar Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '25',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'course_archive_single_sidebar_offset',
	'label'       => esc_html__( 'Single Sidebar Offset', 'unicamp' ),
	'description' => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '30px',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_archive_page_sidebar_style',
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
	'settings' => 'course_archive_preset',
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
			'label'    => esc_html__( 'Layout 01', 'unicamp' ),
			'settings' => array(
				'course_archive_body_background'       => '',
				'course_archive_layout'                => 'grid',
				'course_archive_grid_style'            => 'grid-01',
				'course_archive_lg_columns'            => 4,
				'course_archive_grid_number_item'      => 12,
				'course_archive_page_sidebar_1'        => 'course_sidebar',
				'course_archive_page_sidebar_position' => 'right',
				'course_archive_page_sidebar_style'    => '01',
				'course_archive_sorting'               => '1',
				'course_archive_filtering'             => '0',
				'course_archive_layout_switcher'       => '1',
			),
		),
		'02' => array(
			'label'    => esc_html__( 'Layout 02', 'unicamp' ),
			'settings' => array(
				'course_archive_body_background'       => 'grey',
				'course_archive_title_bar_layout'      => '02',
				'course_archive_layout'                => 'grid',
				'course_archive_grid_style'            => 'grid-02',
				'course_archive_lg_columns'            => 4,
				'course_archive_grid_number_item'      => 12,
				'course_archive_page_sidebar_1'        => 'course_sidebar',
				'course_archive_page_sidebar_position' => 'left',
				'course_archive_page_sidebar_style'    => '02',
				'course_archive_single_sidebar_offset' => '0',
				'course_archive_sorting'               => '1',
				'course_archive_filtering'             => '0',
				'course_archive_layout_switcher'       => '1',
			),
		),
		'03' => array(
			'label'    => esc_html__( 'Layout 02', 'unicamp' ),
			'settings' => array(
				'course_archive_body_background'       => '',
				'course_archive_title_bar_layout'      => '02',
				'course_archive_layout'                => 'grid',
				'course_archive_grid_style'            => 'grid-02',
				'course_archive_lg_columns'            => 4,
				'course_archive_grid_number_item'      => 12,
				'course_archive_page_sidebar_1'        => 'course_sidebar',
				'course_archive_page_sidebar_style'    => '02',
				'course_archive_single_sidebar_offset' => '0',
				'course_archive_sorting'               => '1',
				'course_archive_filtering'             => '0',
				'course_archive_layout_switcher'       => '1',
			),
		),
		'04' => array(
			'label'    => esc_html__( 'Layout 03', 'unicamp' ),
			'settings' => array(
				'course_archive_layout'           => 'grid',
				'course_archive_grid_style'       => 'grid-01',
				'course_archive_lg_columns'       => 4,
				'course_archive_grid_number_item' => 16,
				'course_archive_page_sidebar_1'   => 'none',
				'course_archive_sorting'          => '0',
				'course_archive_filtering'        => '1',
				'course_archive_layout_switcher'  => '1',
			),
		),
		'05' => array(
			'label'    => esc_html__( 'Layout 04', 'unicamp' ),
			'settings' => array(
				'course_archive_layout'           => 'grid',
				'course_archive_grid_style'       => 'grid-01',
				'course_archive_lg_columns'       => 5,
				'course_archive_grid_number_item' => 20,
				'course_archive_page_sidebar_1'   => 'none',
				'course_archive_sorting'          => '0',
				'course_archive_filtering'        => '1',
				'course_archive_layout_switcher'  => '1',
			),
		),
		'06' => array(
			'label'    => esc_html__( 'Layout 05', 'unicamp' ),
			'settings' => array(
				'course_archive_layout'                => 'list',
				'course_archive_list_style'            => 'list',
				'course_archive_page_sidebar_1'        => 'course_sidebar',
				'course_archive_page_sidebar_position' => 'left',
				'course_archive_page_sidebar_style'    => '01',
				'course_archive_grid_number_item'      => 8,
				'course_archive_sorting'               => '1',
				'course_archive_filtering'             => '0',
			),
		),
		'07' => array(
			'label'    => esc_html__( 'Layout 06', 'unicamp' ),
			'settings' => array(
				'course_archive_layout'                => 'list',
				'course_archive_list_style'            => 'list-02',
				'course_archive_page_sidebar_1'        => 'none',
				'course_archive_page_sidebar_position' => 'left',
				'course_archive_page_sidebar_style'    => '03',
				'course_archive_grid_number_item'      => 8,
				'course_archive_sorting'               => '0',
				'course_archive_filtering'             => '1',
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'course_archive_body_background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls site background.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Helper::get_site_background_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_archive_site_layout',
	'label'    => esc_html__( 'Grid Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => Unicamp_Helper::get_site_layout_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'course_archive_sorting',
	'label'       => esc_html__( 'Sorting', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show sorting select options that displays above course list.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'course_archive_filtering',
	'label'       => esc_html__( 'Filtering', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show filtering button that displays above course list.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '0',
	'choices'     => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'course_archive_layout_switcher',
	'label'       => esc_html__( 'Layout Switcher', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show layout switcher button that displays above course list.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'course_archive_layout',
	'label'       => esc_html__( 'Layout', 'unicamp' ),
	'description' => esc_html__( 'Select course layout that display for course listing page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'grid',
	'choices'     => array(
		'list' => esc_attr__( 'List', 'unicamp' ),
		'grid' => esc_attr__( 'Grid', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_archive_grid_style',
	'label'    => esc_html__( 'Grid Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'grid-01',
	'choices'  => array(
		'grid-01' => esc_attr__( 'Grid 01', 'unicamp' ),
		'grid-02' => esc_attr__( 'Grid 02', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'course_archive_list_style',
	'label'    => esc_html__( 'List Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'list',
	'choices'  => array(
		'list'    => esc_attr__( 'List 01', 'unicamp' ),
		'list-02' => esc_attr__( 'List 02', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'course_archive_grid_number_item',
	'label'       => esc_html__( 'Number items (Grid)', 'unicamp' ),
	'description' => esc_html__( 'Controls the number of courses display on grid', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 12,
	'choices'     => array(
		'min'  => 1,
		'max'  => 50,
		'step' => 1,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'course_archive_list_number_item',
	'label'       => esc_html__( 'Number items (List)', 'unicamp' ),
	'description' => esc_html__( 'Controls the number of courses display on list', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 8,
	'choices'     => array(
		'min'  => 1,
		'max'  => 50,
		'step' => 1,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Grid Columns', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'course_archive_lg_columns',
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
	'settings'  => 'course_archive_md_columns',
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
	'settings'  => 'course_archive_sm_columns',
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
