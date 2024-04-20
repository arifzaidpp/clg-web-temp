<?php
$section  = 'event_archive';
$priority = 1;
$prefix   = 'event_archive_';

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
	'settings'    => 'event_archive_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select header style that displays on event archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'event_archive_header_overlay',
	'label'    => esc_html__( 'Header Overlay', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => array(
		''  => esc_html__( 'Use Global', 'unicamp' ),
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'event_archive_header_skin',
	'label'    => esc_html__( 'Header Skin', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
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
	'settings'    => 'event_archive_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on archive event pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
	'default'     => '02',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'background',
	'settings'        => 'event_archive_title_bar_background',
	'label'           => esc_html__( 'Background', 'unicamp' ),
	'description'     => esc_html__( 'Controls the background of title bar.', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => array(
		'background-color'      => '#111',
		'background-image'      => UNICAMP_THEME_IMAGE_URI . '/title-bar-bg-event.jpg',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
		'background-position'   => 'center center',
	),
	'output'          => array(
		array(
			'element' => '.archive-event .page-title-bar-02 .page-title-bar-bg',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'event_archive_title_bar_layout',
			'operator' => '==',
			'value'    => '02',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => 'event_archive_title_bar_title',
	'label'       => esc_html__( 'Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text that displays on archive event pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Events', 'unicamp' ),
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
	'settings'    => 'event_archive_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on event archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'event_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'event_archive_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on event archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'event_archive_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'left',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'event_archive_single_sidebar_width',
	'label'       => esc_html__( 'Single Sidebar Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '25',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'event_archive_single_sidebar_offset',
	'label'       => esc_html__( 'Single Sidebar Offset', 'unicamp' ),
	'description' => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '0',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'event_archive_page_sidebar_style',
	'label'    => esc_html__( 'Sidebar Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '02',
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
	'settings' => 'event_archive_preset',
	'label'    => esc_html__( 'Event Layout Preset', 'unicamp' ),
	'section'  => $section,
	'default'  => '-1',
	'priority' => $priority++,
	'multiple' => 0,
	'choices'  => array(
		'-1'      => array(
			'label'    => esc_html__( 'None', 'unicamp' ),
			'settings' => array(),
		),
		'grid-01' => array(
			'label'    => esc_html__( 'Grid Layout 01', 'unicamp' ),
			'settings' => array(
				'event_archive_style' => 'grid-01',
			),
		),
		'grid-02' => array(
			'label'    => esc_html__( 'Grid Layout 02', 'unicamp' ),
			'settings' => array(
				'event_archive_site_layout'    => 'small',
				'event_archive_style'          => 'grid-01',
				'event_archive_page_sidebar_1' => 'none',
				'event_archive_filtering'      => '1',
				'event_archive_lg_columns'     => 3,
				'event_archive_number_item'    => 9,
			),
		),
		'grid-03' => array(
			'label'    => esc_html__( 'Grid Layout 03', 'unicamp' ),
			'settings' => array(
				'event_archive_body_background' => '',
				'event_archive_style'           => 'grid-02',
				'event_archive_filtering'       => '1',
				'event_archive_page_sidebar_1'  => 'none',
				'event_archive_lg_columns'      => 3,
				'event_archive_number_item'     => 9,
			),
		),
		'grid-04' => array(
			'label'    => esc_html__( 'Grid Layout 04', 'unicamp' ),
			'settings' => array(
				'event_archive_style'                 => 'grid-03',
				'event_archive_page_sidebar_position' => 'right',
			),
		),
		'list-01' => array(
			'label'    => esc_html__( 'List Layout 01', 'unicamp' ),
			'settings' => array(
				'event_archive_site_layout'      => 'small',
				'event_archive_body_background'  => '',
				'event_archive_title_bar_layout' => '01',
				'event_archive_filtering'        => '1',
				'event_archive_page_sidebar_1'   => 'none',
				'event_archive_style'            => 'list',
				'event_archive_number_item'      => 6,
			),
		),
		'list-02' => array(
			'label'    => esc_html__( 'List Layout 02', 'unicamp' ),
			'settings' => array(
				'event_archive_site_layout'           => 'small',
				'event_archive_body_background'       => '',
				'event_archive_title_bar_layout'      => '01',
				'event_archive_page_sidebar_position' => 'right',
				'event_archive_single_sidebar_width'  => '33.333333',
				'event_archive_single_sidebar_offset' => '30px',
				'event_archive_page_sidebar_style'    => '01',
				'event_archive_style'                 => 'list-02',
				'event_archive_number_item'           => 7,
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'event_archive_body_background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls site background.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Helper::get_site_background_options(),
	'default'     => 'grey',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'event_archive_site_layout',
	'label'    => esc_html__( 'Grid Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
	'choices'  => Unicamp_Helper::get_site_layout_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'event_archive_style',
	'label'       => esc_html__( 'Style', 'unicamp' ),
	'description' => esc_html__( 'Select event style that display for event listing page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'grid-01',
	'choices'     => array(
		'grid-01' => esc_attr__( 'Grid 01', 'unicamp' ),
		'grid-02' => esc_attr__( 'Grid 02', 'unicamp' ),
		'grid-03' => esc_attr__( 'Grid 03', 'unicamp' ),
		'list'    => esc_attr__( 'List 01', 'unicamp' ),
		'list-02' => esc_attr__( 'List 02', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'event_archive_number_item',
	'label'       => esc_html__( 'Number items', 'unicamp' ),
	'description' => esc_html__( 'Controls the number of products display on events page', 'unicamp' ),
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
	'type'        => 'radio-buttonset',
	'settings'    => 'event_archive_filtering',
	'label'       => esc_html__( 'Filtering Bar', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show filtering form bar that displays above event list.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '0',
	'choices'     => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
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
	'settings'  => 'event_archive_lg_columns',
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
	'settings'  => 'event_archive_md_columns',
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
	'settings'  => 'event_archive_sm_columns',
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
