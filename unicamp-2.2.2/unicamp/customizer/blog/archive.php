<?php
$section  = 'blog_archive';
$priority = 1;
$prefix   = 'blog_archive_';

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
	'settings'    => 'blog_archive_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select header style that displays on blog archive & taxonomy pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'blog_archive_header_overlay',
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
	'settings' => 'blog_archive_header_skin',
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
	'settings'    => 'blog_archive_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on blog archive & taxonomy pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
	'default'     => '02',
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
	'settings'    => 'blog_archive_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on blog archive & taxonomy pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'blog_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'blog_archive_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on blog archive & taxonomy pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'blog_archive_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'right',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'blog_archive_single_sidebar_width',
	'label'       => esc_html__( 'Single Sidebar Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '25',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'blog_archive_single_sidebar_offset',
	'label'       => esc_html__( 'Single Sidebar Offset', 'unicamp' ),
	'description' => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '0',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'blog_archive_page_sidebar_style',
	'label'    => esc_html__( 'Sidebar Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
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
	'settings' => 'blog_archive_preset',
	'label'    => esc_html__( 'Blog Layout Preset', 'unicamp' ),
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
			'label'    => esc_html__( 'Grid 01', 'unicamp' ),
			'settings' => array(),
		),
		'grid-02' => array(
			'label'    => esc_html__( 'Grid 02', 'unicamp' ),
			'settings' => array(
				'blog_archive_page_sidebar_1' => 'none',
			),
		),
		'grid-03' => array(
			'label'    => esc_html__( 'Grid 03', 'unicamp' ),
			'settings' => array(
				'blog_archive_body_background'       => 'grey',
				'blog_archive_page_sidebar_style'    => '02',
				'blog_archive_page_sidebar_position' => 'left',
			),
		),
		'grid-04' => array(
			'label'    => esc_html__( 'Grid 04', 'unicamp' ),
			'settings' => array(
				'blog_archive_grid_caption_style' => '02',
				'blog_archive_page_sidebar_1'     => 'none',
			),
		),
		'list-01' => array(
			'label'    => esc_html__( 'List Layout 01', 'unicamp' ),
			'settings' => array(
				'blog_archive_site_layout'           => 'small',
				'blog_archive_style'                 => 'list-01',
				'blog_archive_single_sidebar_width'  => '33.333333',
				'blog_archive_single_sidebar_offset' => '40px',
			),
		),
		'list-02' => array(
			'label'    => esc_html__( 'List Layout 02', 'unicamp' ),
			'settings' => array(
				'blog_archive_site_layout'           => 'small',
				'blog_archive_style'                 => 'list-02',
				'blog_archive_page_sidebar_position' => 'left',
				'blog_archive_single_sidebar_width'  => '33.333333',
				'blog_archive_single_sidebar_offset' => '40px',
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'blog_archive_body_background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls site background.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Helper::get_site_background_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'blog_archive_site_layout',
	'label'    => esc_html__( 'Grid Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'choices'  => Unicamp_Helper::get_site_layout_options(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'blog_archive_style',
	'label'       => esc_html__( 'Blog Style', 'unicamp' ),
	'description' => esc_html__( 'Select style that used for blog archive & taxonomy pages', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'grid',
	'choices'     => array(
		'grid'    => esc_attr__( 'Grid', 'unicamp' ),
		'list-01' => esc_attr__( 'List 01', 'unicamp' ),
		'list-02' => esc_attr__( 'List 02', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'blog_archive_masonry',
	'label'    => esc_html__( 'Masonry', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'blog_archive_grid_caption_style',
	'label'       => esc_html__( 'Blog Caption Style', 'unicamp' ),
	'description' => esc_html__( 'Select blog caption style that used for blog archive & taxonomy pages', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '01',
	'choices'     => array(
		'01' => '01',
		'02' => '02',
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'blog_archive_posts_per_page',
	'label'       => esc_html__( 'Number posts', 'unicamp' ),
	'description' => esc_html__( 'Controls the number of posts per page', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 12,
	'choices'     => array(
		'min'  => 1,
		'max'  => 50,
		'step' => 1,
	),
) );
