<?php
$section  = 'header_sticky';
$priority = 1;
$prefix   = 'header_sticky_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'toggle',
	'settings'    => $prefix . 'enable',
	'label'       => esc_html__( 'Enable', 'unicamp' ),
	'description' => esc_html__( 'Enable this option to turn on header sticky feature.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 1,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio',
	'settings'    => $prefix . 'behaviour',
	'label'       => esc_html__( 'Behaviour', 'unicamp' ),
	'description' => esc_html__( 'Controls the behaviour of header sticky when you scroll down to page', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'both',
	'choices'     => array(
		'both' => esc_html__( 'Sticky on scroll up/down', 'unicamp' ),
		'up'   => esc_html__( 'Sticky on scroll up', 'unicamp' ),
		'down' => esc_html__( 'Sticky on scroll down', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'height',
	'label'     => esc_html__( 'Height', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 80,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 200,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.headroom--not-top .page-header-inner .header-wrap',
			'property' => 'min-height',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'logo',
	'label'    => esc_html__( 'Logo', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'dark',
	'choices'  => array(
		'light' => esc_html__( 'Light', 'unicamp' ),
		'dark'  => esc_html__( 'Dark', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'background',
	'settings'    => $prefix . 'background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls the background of header when sticky.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'background-color'      => '#ffffff',
		'background-image'      => '',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
		'background-position'   => 'center center',
	),
	'output'      => array(
		array(
			'element' => '#page-header.headroom--not-top .page-header-inner',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Header Icons', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'icon_color',
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
		'normal' => '#111',
		'hover'  => '#111',
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '
			.page-header.headroom--not-top .header-icon,
			.page-header.headroom--not-top .wpml-ls-item-toggle
			',
			'property' => 'color',
			'suffix'   => ' !important',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
			.page-header.headroom--not-top .header-icon:hover
			',
			'property' => 'color',
			'suffix'   => ' !important',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.page-header.headroom--not-top .wpml-ls-slot-shortcode_actions:hover > .js-wpml-ls-item-toggle',
			'property' => 'color',
			'suffix'   => '!important',
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
	'settings'    => $prefix . 'icon_badge_color',
	'label'       => esc_html__( 'Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of icon badge.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
	),
	'output'      => array(
		array(
			'choice'   => 'color',
			'element'  => '.page-header.headroom--not-top .mini-cart .mini-cart-icon:after, .page-header.headroom--not-top .header-icon .badge',
			'property' => 'color',
			'suffix'   => ' !important',
		),
		array(
			'choice'   => 'background',
			'element'  => '.page-header.headroom--not-top .mini-cart .mini-cart-icon:after, .page-header.headroom--not-top .header-icon .badge',
			'property' => 'background-color',
			'suffix'   => ' !important',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Social Networks', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'social_networks_color',
	'label'     => esc_html__( 'Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => '#111',
		'hover'  => '#111',
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '.page-header.headroom--not-top .header-social-networks a',
			'property' => 'color',
			'suffix'   => ' !important',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.page-header.headroom--not-top .header-social-networks a:hover',
			'property' => 'color',
			'suffix'   => ' !important',
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
	'settings'    => $prefix . 'navigation_link_color',
	'label'       => esc_html__( 'Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color for main menu items.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => '#111',
		'hover'  => Unicamp::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '.page-header.headroom--not-top .menu--primary > ul > li > a',
			'property' => 'color',
			'suffix'   => ' !important',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
            .page-header.headroom--not-top .menu--primary > li:hover > a,
            .page-header.headroom--not-top .menu--primary > ul > li > a:hover,
            .page-header.headroom--not-top .menu--primary > ul > li > a:focus,
            .page-header.headroom--not-top .menu--primary > ul > .current-menu-ancestor > a,
            .page-header.headroom--not-top .menu--primary > ul > .current-menu-item > a',
			'property' => 'color',
			'suffix'   => ' !important',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button', 'unicamp' ) . '</div>',
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
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'button_color',
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
	'settings'        => $prefix . 'button_custom_color',
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
		'color'      => '#111',
		'background' => 'rgba(17, 17, 17, 0)',
		'border'     => '#eee',
	),
	'output'          => array(
		array(
			'choice'   => 'color',
			'element'  => '.header-sticky-button.tm-button',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '.header-sticky-button.tm-button',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.header-sticky-button.tm-button:before',
			'property' => 'background',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'button_color',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'button_hover_custom_color',
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
		'color'      => '#fff',
		'background' => '#111',
		'border'     => '#111',
	),
	'output'          => array(
		array(
			'choice'   => 'color',
			'element'  => '.header-sticky-button.tm-button:hover',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '.header-sticky-button.tm-button:hover',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.header-sticky-button.tm-button:after',
			'property' => 'background',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'button_color',
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
	'default'  => '<div class="group_title">' . esc_html__( 'Search Form', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'search_form_color',
	'label'     => esc_html__( 'Normal', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#696969',
		'background' => '#f5f5f5',
		'border'     => '#f5f5f5',
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'property' => 'color',
			'element'  => '#page-header.headroom--not-top .search-field',
		),
		array(
			'choice'   => 'border',
			'property' => 'border-color',
			'element'  => '#page-header.headroom--not-top .search-field',
		),
		array(
			'choice'   => 'background',
			'property' => 'background',
			'element'  => '#page-header.headroom--not-top .search-field',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'search_form_focus_color',
	'label'     => esc_html__( 'Hover', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#333',
		'background' => '#fff',
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'property' => 'color',
			'element'  => '#page-header.headroom--not-top .search-field:focus',
		),
		array(
			'choice'   => 'border',
			'property' => 'border-color',
			'element'  => '#page-header.headroom--not-top .search-field:focus',
		),
		array(
			'choice'   => 'background',
			'property' => 'background',
			'element'  => '#page-header.headroom--not-top .search-field:focus',
		),
	),
) );
