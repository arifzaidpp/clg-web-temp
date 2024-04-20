<?php
$section  = 'header_style_05';
$priority = 1;
$prefix   = 'header_style_05_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header Style', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'border_width',
	'label'     => esc_html__( 'Border Bottom Width', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 0,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.header-05 .page-header-inner',
			'property' => 'border-bottom-width',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header Components', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'category_menu_enable',
	'label'    => esc_html__( 'Category Menu', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'search_enable',
	'label'    => esc_html__( 'Search', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
	'choices'  => array(
		'0'     => esc_html__( 'Hide', 'unicamp' ),
		//'inline' => esc_html__( 'Inline Form', 'unicamp' ),
		'popup' => esc_html__( 'Popup Search', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'login_enable',
	'label'    => esc_html__( 'Login', 'unicamp' ),
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
	'settings' => $prefix . 'cart_enable',
	'label'    => esc_html__( 'Mini Cart', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0'             => esc_html__( 'Hide', 'unicamp' ),
		'1'             => esc_html__( 'Show', 'unicamp' ),
		'hide_on_empty' => esc_html__( 'Hide On Empty', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'notification_enable',
	'label'    => esc_html__( 'Notification', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Customize::instance()->field_social_networks_enable( array(
	'settings' => $prefix . 'social_networks_enable',
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
) );

Unicamp_Customize::instance()->field_language_switcher_enable( array(
	'settings' => $prefix . 'language_switcher_enable',
	'section'  => $section,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Button', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'text',
	'settings' => $prefix . 'button_text',
	'label'    => esc_html__( 'Button Text', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'text',
	'settings' => $prefix . 'button_link',
	'label'    => esc_html__( 'Button Link', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'         => 'text',
	'settings'     => $prefix . 'button_link_rel',
	'label'        => esc_html__( 'Button Link Relationship (XFN)', 'unicamp' ),
	'section'      => $section,
	'priority'     => $priority++,
	'translatable' => false,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'button_link_target',
	'label'    => esc_html__( 'Open in new window', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
	'choices'  => array(
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => $prefix . 'button_style',
	'label'    => esc_html__( 'Button Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'thick-border',
	'choices'  => Unicamp_Header::instance()->get_button_style(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header Navigation (Level 1)', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'navigation_typography',
	'label'       => esc_html__( 'Typography', 'unicamp' ),
	'description' => esc_html__( 'These settings control the typography for menu items.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '500',
		'font-size'      => '14px',
		'line-height'    => '1.6',
		'letter-spacing' => '',
		'text-transform' => '',
	),
	'output'      => array(
		array(
			'element' => '.header-05 .menu--primary > ul > li > a',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'spacing',
	'settings'  => $prefix . 'navigation_item_padding',
	'label'     => esc_html__( 'Item Padding', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => array(
		'top'    => '25px',
		'bottom' => '25px',
		'left'   => '17px',
		'right'  => '17px',
	),
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => array(
				'.desktop-menu .header-05 .menu--primary > ul > li > a',
			),
			'property' => 'padding',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header Dark Skin', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Style', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'background',
	'settings' => $prefix . 'dark_background',
	'label'    => esc_html__( 'Background', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => array(
		'background-color'      => '#fff',
		'background-image'      => '',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'fixed',
		'background-position'   => 'center center',
	),
	'output'   => array(
		array(
			'element' => '.header-05.header-dark .page-header-inner',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'dark_border_color',
	'label'       => esc_html__( 'Border Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the border color of header.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => '#eee',
	'output'      => array(
		array(
			'element'  => '.header-05.header-dark .page-header-inner',
			'property' => 'border-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'         => 'text',
	'settings'     => $prefix . 'dark_box_shadow',
	'label'        => esc_html__( 'Box Shadow', 'unicamp' ),
	'description'  => esc_html__( 'Input box shadow for header. For e.g: 0 0 5px #ccc', 'unicamp' ),
	'section'      => $section,
	'priority'     => $priority++,
	'default'      => '0 10px 26px rgba(0, 0, 0, 0.05)',
	'output'       => array(
		array(
			'element'  => '.header-05.header-dark .page-header-inner',
			'property' => 'box-shadow',
		),
	),
	'translatable' => false,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Icon', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'dark_header_icon_color',
	'label'       => esc_html__( 'Icon Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of icons on header.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => Unicamp::HEADING_COLOR,
		'hover'  => Unicamp::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '
			.header-05.header-dark .header-icon,
			.header-05.header-dark .wpml-ls-item-toggle',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-05.header-dark .header-icon:hover',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-05.header-dark .wpml-ls-slot-shortcode_actions:hover > .js-wpml-ls-item-toggle',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Icon Badge', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'dark_icon_badge_color',
	'label'       => esc_html__( 'Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of icon badge.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
	),
	'default'     => array(
		'color'      => '#fff',
		'background' => Unicamp::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'color',
			'element'  => '.header-05.header-dark .header-icon .badge, .header-05.header-dark .mini-cart .mini-cart-icon:after',
			'property' => 'color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.header-05.header-dark .header-icon .badge, .header-05.header-dark .mini-cart .mini-cart-icon:after',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Navigation', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'dark_navigation_link_color',
	'label'       => esc_html__( 'Link Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color for main menu items.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => Unicamp::HEADING_SECONDARY_COLOR,
		'hover'  => Unicamp::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '.header-05.header-dark .menu--primary > ul > li > a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
			.header-05.header-dark .menu--primary > ul > li:hover > a,
            .header-05.header-dark .menu--primary > ul > li > a:hover,
            .header-05.header-dark .menu--primary > ul > li > a:focus,
            .header-05.header-dark .menu--primary > ul > .current-menu-ancestor > a,
            .header-05.header-dark .menu--primary > ul > .current-menu-item > a',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Search Form', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'dark_search_form_color',
	'label'           => esc_html__( 'Normal', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'         => array(
		'color'      => '#9B9B9B',
		'background' => '#F2F2F2',
		'border'     => '#F2F2F2',
	),
	'output'          => Unicamp_Header::instance()->get_search_form_kirki_output( '05', 'dark', false ),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'search_enable',
			'operator' => '==',
			'value'    => 'inline',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'dark_search_form_focus_color',
	'label'           => esc_html__( 'Hover', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'         => array(
		'color'      => '#333',
		'background' => '#fff',
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'          => Unicamp_Header::instance()->get_search_form_kirki_output( '05', 'dark', true ),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'search_enable',
			'operator' => '==',
			'value'    => 'inline',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Button', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'dark_button_color',
	'label'    => esc_html__( 'Button Color', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'custom',
	'choices'  => array(
		''       => esc_html__( 'Default', 'unicamp' ),
		'custom' => esc_html__( 'Custom', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'dark_button_custom_color',
	'label'           => esc_html__( 'Button Color', 'unicamp' ),
	'description'     => esc_html__( 'Controls the color of button.', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'         => array(
		'color'      => '#fff',
		'background' => Unicamp::PRIMARY_COLOR,
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'          => Unicamp_Header::instance()->get_button_kirki_output( '05', 'dark', false ),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'dark_button_color',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'dark_button_hover_custom_color',
	'label'           => esc_html__( 'Button Hover Color', 'unicamp' ),
	'description'     => esc_html__( 'Controls the color of button when hover.', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'         => array(
		'color'      => Unicamp::PRIMARY_COLOR,
		'background' => 'rgba(0, 0, 0, 0)',
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'          => Unicamp_Header::instance()->get_button_kirki_output( '05', 'dark', true ),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'dark_button_color',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Social Networks', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'dark_social_networks_color',
	'label'     => esc_html__( 'Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => Unicamp::HEADING_COLOR,
		'hover'  => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '.header-05.header-dark .header-social-networks a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-05.header-dark .header-social-networks a:hover',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Header Light Skin', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Style', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'light_border_color',
	'label'       => esc_html__( 'Border Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the border color of header.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => 'rgba(255, 255, 255, 0.2)',
	'output'      => array(
		array(
			'element'  => '.header-05.header-light .page-header-inner',
			'property' => 'border-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'         => 'text',
	'settings'     => $prefix . 'light_box_shadow',
	'label'        => esc_html__( 'Box Shadow', 'unicamp' ),
	'description'  => esc_html__( 'Input box shadow for header. For e.g: 0 0 5px #ccc', 'unicamp' ),
	'section'      => $section,
	'priority'     => $priority++,
	'output'       => array(
		array(
			'element'  => '.header-05.header-light .page-header-inner',
			'property' => 'box-shadow',
		),
	),
	'translatable' => false,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Icon', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'light_header_icon_color',
	'label'       => esc_html__( 'Icon Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of icons on header.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => '#fff',
		'hover'  => '#fff',
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '
			.header-05.header-light .header-icon,
			.header-05.header-light .wpml-ls-item-toggle',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-05.header-light .header-icon:hover',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-05.header-light .wpml-ls-slot-shortcode_actions:hover > .js-wpml-ls-item-toggle',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Icon Badge', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'light_icon_badge_color',
	'label'       => esc_html__( 'Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of icon badge.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
	),
	'default'     => array(
		'color'      => '#fff',
		'background' => Unicamp::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'color',
			'element'  => '.header-05.header-light .header-icon .badge, .header-05.header-light .mini-cart .mini-cart-icon:after',
			'property' => 'color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.header-05.header-light .header-icon .badge, .header-05.header-light .mini-cart .mini-cart-icon:after',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Navigation', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'light_navigation_link_color',
	'label'       => esc_html__( 'Navigation Link Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color for main menu items.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => '#fff',
		'hover'  => '#fff',
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '.header-05.header-light .menu--primary > ul > li > a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
            .header-05.header-light .menu--primary > ul > li:hover > a,
            .header-05.header-light .menu--primary > ul > li > a:hover,
            .header-05.header-light .menu--primary > ul > li > a:focus,
            .header-05.header-light .menu--primary > ul > .current-menu-ancestor > a,
            .header-05.header-light .menu--primary > ul > .current-menu-item > a',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Button', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'light_button_color',
	'label'    => esc_html__( 'Button Color', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'custom',
	'choices'  => array(
		''       => esc_html__( 'Default', 'unicamp' ),
		'custom' => esc_html__( 'Custom', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'light_button_custom_color',
	'label'           => esc_html__( 'Button Color', 'unicamp' ),
	'description'     => esc_html__( 'Controls the color of button.', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'         => array(
		'color'      => '#fff',
		'background' => 'rgba(255, 255, 255, 0)',
		'border'     => 'rgba(255, 255, 255, 0.3)',
	),
	'output'          => Unicamp_Header::instance()->get_button_kirki_output( '05', 'light', false ),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'light_button_color',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'light_button_hover_custom_color',
	'label'           => esc_html__( 'Button Hover Color', 'unicamp' ),
	'description'     => esc_html__( 'Controls the color of button when hover.', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'         => array(
		'color'      => '#111',
		'background' => '#fff',
		'border'     => '#fff',
	),
	'output'          => Unicamp_Header::instance()->get_button_kirki_output( '05', 'light', true ),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'light_button_color',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Social Networks', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'light_social_networks_color',
	'label'     => esc_html__( 'Normal Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => '#fff',
		'hover'  => '#fff',
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '.header-05.header-light .header-social-networks a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-05.header-light .header-social-networks a:hover',
			'property' => 'color',
		),
	),
) );
