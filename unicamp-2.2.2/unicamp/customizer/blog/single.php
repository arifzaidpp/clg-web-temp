<?php
$section  = 'blog_single';
$priority = 1;
$prefix   = 'single_post_';

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
	'settings'    => 'blog_single_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select default header style that displays on all single blog post pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'blog_single_header_overlay',
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
	'settings' => 'blog_single_header_skin',
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
	'settings'    => 'blog_single_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on all single blog post pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
	'default'     => '',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => 'blog_single_title_bar_title',
	'label'       => esc_html__( 'Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text that displays on single blog posts. Leave blank to use post title.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'News and Blog', 'unicamp' ),
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
	'settings'    => 'post_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on single blog post pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'blog_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'post_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on single blog post pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'post_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'right',
	'choices'  => $sidebar_positions,
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
	'settings' => 'single_post_preset',
	'label'    => esc_html__( 'Post Layout Preset', 'unicamp' ),
	'section'  => $section,
	'default'  => '-1',
	'priority' => $priority++,
	'multiple' => 0,
	'choices'  => array(
		'-1'            => array(
			'label'    => esc_html__( 'None', 'unicamp' ),
			'settings' => array(),
		),
		'left-sidebar'  => array(
			'label'    => esc_html__( 'Left Sidebar', 'unicamp' ),
			'settings' => array(
				'post_page_sidebar_1'        => 'blog_sidebar',
				'post_page_sidebar_position' => 'left',
			),
		),
		'right-sidebar' => array(
			'label'    => esc_html__( 'Right Sidebar', 'unicamp' ),
			'settings' => array(
				'post_page_sidebar_1'        => 'blog_sidebar',
				'post_page_sidebar_position' => 'right',
			),
		),
		'no-sidebar'    => array(
			'label'    => esc_html__( 'No Sidebar', 'unicamp' ),
			'settings' => array(
				'post_page_sidebar_1'          => 'none',
				'blog_single_title_bar_layout' => Unicamp::TITLE_BAR_MINIMAL_TYPE,
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_feature_enable',
	'label'    => esc_html__( 'Featured Image', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_title_enable',
	'label'    => esc_html__( 'Post Title', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_categories_enable',
	'label'    => esc_html__( 'Categories', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_tags_enable',
	'label'    => esc_html__( 'Tags', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_date_enable',
	'label'    => esc_html__( 'Post Meta Date', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_view_count_enable',
	'label'    => esc_html__( 'View Count', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_comment_count_enable',
	'label'    => esc_html__( 'Comment Count', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_author_enable',
	'label'    => esc_html__( 'Author Meta', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Customize::instance()->field_social_sharing_enable( array(
	'settings' => 'single_post_share_enable',
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_author_box_enable',
	'label'    => esc_html__( 'Author Info Box', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_pagination_enable',
	'label'    => esc_html__( 'Previous/Next Pagination', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_related_enable',
	'label'    => esc_html__( 'Related Posts', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'number',
	'settings'        => 'single_post_related_number',
	'label'           => esc_html__( 'Number of related posts item', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => 5,
	'choices'         => array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	),
	'active_callback' => array(
		array(
			'setting'  => 'single_post_related_enable',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_post_comment_enable',
	'label'    => esc_html__( 'Comments List/Form', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );
