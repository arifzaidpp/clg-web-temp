<?php
$section  = 'shop_single';
$priority = 1;
$prefix   = 'single_product_';

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
	'settings'    => 'product_single_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select default header style that displays on all single product pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'product_single_header_overlay',
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
	'settings' => 'product_single_header_skin',
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
	'settings'    => 'product_single_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on all single product pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
	'default'     => Unicamp::TITLE_BAR_MINIMAL_TYPE,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => 'product_single_title_bar_title',
	'label'       => esc_html__( 'Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text that displays on single product pages. Leave blank to use product title.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Our Shop', 'unicamp' ),
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
	'settings'    => 'product_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on single product pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'single_shop_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'product_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on single product pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'product_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'left',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'product_page_single_sidebar_width',
	'label'       => esc_html__( 'Single Sidebar Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '22.223333',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'product_page_single_sidebar_offset',
	'label'       => esc_html__( 'Single Sidebar Offset', 'unicamp' ),
	'description' => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '30px',
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
	'settings' => 'shop_single_preset',
	'label'    => esc_html__( 'Single Layout Preset', 'unicamp' ),
	'section'  => $section,
	'default'  => '-1',
	'priority' => $priority++,
	'multiple' => 0,
	'choices'  => array(
		'-1'                 => array(
			'label'    => esc_html__( 'None', 'unicamp' ),
			'settings' => array(),
		),
		'list-left-sidebar'  => array(
			'label'    => esc_html__( 'List Layout - Left Sidebar', 'unicamp' ),
			'settings' => array(
				'product_page_sidebar_1'        => 'single_shop_sidebar',
				'product_page_sidebar_position' => 'left',
				'single_product_tabs_style'     => 'list',
			),
		),
		'list-right-sidebar' => array(
			'label'    => esc_html__( 'List Layout - Right Sidebar', 'unicamp' ),
			'settings' => array(
				'product_page_sidebar_1'        => 'single_shop_sidebar',
				'product_page_sidebar_position' => 'right',
				'single_product_tabs_style'     => 'list',
			),
		),
		'list-no-sidebar'    => array(
			'label'    => esc_html__( 'List Layout - No Sidebar', 'unicamp' ),
			'settings' => array(
				'product_page_sidebar_1'    => 'none',
				'single_product_tabs_style' => 'list',
			),
		),
		'tabs-left-sidebar'  => array(
			'label'    => esc_html__( 'Tabs Layout - Left Sidebar', 'unicamp' ),
			'settings' => array(
				'product_page_sidebar_1'        => 'single_shop_sidebar',
				'product_page_sidebar_position' => 'left',
				'single_product_tabs_style'     => 'tabs',
			),
		),
		'tabs-right-sidebar' => array(
			'label'    => esc_html__( 'Tabs Layout - Left Sidebar', 'unicamp' ),
			'settings' => array(
				'product_page_sidebar_1'        => 'single_shop_sidebar',
				'product_page_sidebar_position' => 'right',
				'single_product_tabs_style'     => 'tabs',
			),
		),
		'tabs-no-sidebar'    => array(
			'label'    => esc_html__( 'Tabs Layout - No Sidebar', 'unicamp' ),
			'settings' => array(
				'product_page_sidebar_1'    => 'none',
				'single_product_tabs_style' => 'tabs',
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_product_layout_style',
	'label'    => esc_html__( 'Layout Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'slider',
	'choices'  => array(
		'list'   => esc_html__( 'List', 'unicamp' ),
		'slider' => esc_html__( 'Slider', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_sticky_enable',
	'label'       => esc_html__( 'Sticky Feature/Details Columns', 'unicamp' ),
	'description' => esc_html__( 'Turn on to enable sticky of product feature & details columns.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_product_tabs_style',
	'label'    => esc_html__( 'Tabs Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'list',
	'choices'  => array(
		'list' => esc_html__( 'List', 'unicamp' ),
		'tabs' => esc_html__( 'Tabs', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'single_product_custom_attribute',
	'label'       => esc_html__( 'Custom Attribute', 'unicamp' ),
	'description' => esc_html__( 'Select a custom attribute that displays on beside product review rating.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'choices'     => Unicamp_Woo::instance()->get_custom_attributes_list(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'single_product_custom_attribute_label',
	'label'    => esc_html__( 'Custom Attribute Label', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_categories_enable',
	'label'       => esc_html__( 'Categories', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display the categories.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_tags_enable',
	'label'       => esc_html__( 'Tags', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display the tags.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_sharing_enable',
	'label'       => esc_html__( 'Sharing', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display the sharing.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_up_sells_enable',
	'label'       => esc_html__( 'Up-sells products', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display the up-sells products section.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '0',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_related_enable',
	'label'       => esc_html__( 'Related products', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display the related products section.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'number',
	'settings'        => 'product_related_number',
	'label'           => esc_html__( 'Number related products', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => 5,
	'choices'         => array(
		'min'  => 3,
		'max'  => 30,
		'step' => 1,
	),
	'active_callback' => array(
		array(
			'setting'  => 'single_product_related_enable',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );
