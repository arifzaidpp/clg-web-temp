<?php
$section  = 'shop_archive';
$priority = 1;
$prefix   = 'shop_archive_';

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
	'settings'    => 'product_archive_header_type',
	'label'       => esc_html__( 'Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select default header style that displays on archive product page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Header::instance()->get_list( true, esc_html__( 'Use Global Header Style', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'product_archive_header_overlay',
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
	'settings' => 'product_archive_header_skin',
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
	'settings'    => 'product_archive_title_bar_layout',
	'label'       => esc_html__( 'Title Bar Style', 'unicamp' ),
	'description' => esc_html__( 'Select default Title Bar that displays on all archive product (included cart, checkout, my-account...) pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '',
	'choices'     => Unicamp_Title_Bar::instance()->get_list( true, esc_html__( 'Use Global Title Bar', 'unicamp' ) ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => 'product_archive_title_bar_title',
	'label'       => esc_html__( 'Heading', 'unicamp' ),
	'description' => esc_html__( 'Enter text that displays on archive product pages.', 'unicamp' ),
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
	'settings'    => 'product_archive_page_sidebar_1',
	'label'       => esc_html__( 'Sidebar 1', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 1 that will display on product archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'shop_sidebar',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'product_archive_page_sidebar_2',
	'label'       => esc_html__( 'Sidebar 2', 'unicamp' ),
	'description' => esc_html__( 'Select sidebar 2 that will display on product archive pages.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'none',
	'choices'     => $registered_sidebars,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'product_archive_page_sidebar_position',
	'label'    => esc_html__( 'Sidebar Position', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'left',
	'choices'  => $sidebar_positions,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'product_archive_single_sidebar_width',
	'label'       => esc_html__( 'Single Sidebar Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '20',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => 'product_archive_single_sidebar_offset',
	'label'       => esc_html__( 'Single Sidebar Offset', 'unicamp' ),
	'description' => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '0',
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
	'settings' => 'shop_archive_preset',
	'label'    => esc_html__( 'Shop Layout Preset', 'unicamp' ),
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
				'product_archive_page_sidebar_1'        => 'shop_sidebar',
				'product_archive_page_sidebar_position' => 'left',
				'shop_archive_number_item'              => 12,
				'shop_archive_lg_columns'               => '4',
				'shop_archive_sorting'                  => '1',
				'shop_archive_filtering'                => '0',

			),
		),
		'02' => array(
			'label'    => esc_html__( 'Layout 01', 'unicamp' ),
			'settings' => array(
				'product_archive_page_sidebar_1'        => 'shop_sidebar',
				'product_archive_page_sidebar_position' => 'right',
				'shop_archive_number_item'              => 12,
				'shop_archive_lg_columns'               => '4',
				'shop_archive_sorting'                  => '1',
				'shop_archive_filtering'                => '0',

			),
		),
		'03' => array(
			'label'    => esc_html__( 'Layout 02', 'unicamp' ),
			'settings' => array(
				'product_archive_page_sidebar_1' => 'none',
				'shop_archive_number_item'       => 15,
				'shop_archive_lg_columns'        => '4',
				'shop_archive_sorting'           => '1',
				'shop_archive_filtering'         => '1',
			),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'shop_archive_layout',
	'label'    => esc_html__( 'Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'list',
	'choices'  => array(
		'list' => esc_attr__( 'List', 'unicamp' ),
		'grid' => esc_attr__( 'Grid', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'shop_archive_sorting',
	'label'       => esc_html__( 'Sorting', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show sorting select options that displays above products list.', 'unicamp' ),
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
	'settings'    => 'shop_archive_filtering',
	'label'       => esc_html__( 'Filtering', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show filtering button that displays above products list.', 'unicamp' ),
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
	'settings'    => 'shop_archive_layout_switcher',
	'label'       => esc_html__( 'Layout Switcher', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show layout switcher button that displays above products list.', 'unicamp' ),
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
	'settings'    => 'shop_archive_hover_image',
	'label'       => esc_html__( 'Hover Image', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show first gallery image when hover', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'None', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'shop_archive_quick_view',
	'label'       => esc_html__( 'Quick View', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display quick view button', 'unicamp' ),
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
	'settings'    => 'shop_archive_compare',
	'label'       => esc_html__( 'Compare', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display compare button', 'unicamp' ),
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
	'settings'    => 'shop_archive_wishlist',
	'label'       => esc_html__( 'Wishlist', 'unicamp' ),
	'description' => esc_html__( 'Turn on to display love button', 'unicamp' ),
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
	'settings' => 'shop_archive_hide_buttons_on_mobile',
	'label'    => esc_html__( 'Hide All Buttons on mobile', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => 'shop_archive_number_item',
	'label'       => esc_html__( 'Number items', 'unicamp' ),
	'description' => esc_html__( 'Controls the number of products display on shop archive page', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 16,
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
	'default'  => '<div class="big_title">' . esc_html__( 'Shop Columns', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'shop_archive_lg_columns',
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
	'settings'  => 'shop_archive_md_columns',
	'label'     => esc_html__( 'Medium Device', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 3,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 6,
		'step' => 1,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => 'shop_archive_sm_columns',
	'label'     => esc_html__( 'Small Device', 'unicamp' ),
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
