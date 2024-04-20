<?php
$section  = 'navigation_mobile';
$priority = 1;
$prefix   = 'mobile_menu_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'number',
	'settings'    => $prefix . 'breakpoint',
	'label'       => esc_html__( 'Breakpoint', 'unicamp' ),
	'description' => esc_html__( 'Controls the breakpoint of the mobile menu.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'postMessage',
	'default'     => 1199,
	'choices'     => array(
		'min'  => 460,
		'max'  => 1300,
		'step' => 10,
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'select',
	'settings'  => $prefix . 'effect',
	'label'     => esc_html__( 'Opened Effect', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'postMessage',
	'default'   => UNICAMP_IS_RTL ? 'push-to-right' : 'push-to-left',
	'choices'   => array(
		'slide-to-right' => esc_html__( 'Slide To Right', 'unicamp' ),
		'slide-to-left'  => esc_html__( 'Slide To Left', 'unicamp' ),
		'push-to-right'  => esc_html__( 'Push To Right', 'unicamp' ),
		'push-to-left'   => esc_html__( 'Push To Left', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'header_bg',
	'label'       => esc_html__( 'Header Background Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the background color of the mobile menu header.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '#fff',
	'output'      => array(
		array(
			'element'  => '.page-mobile-menu-header',
			'property' => 'background',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'close_button_color',
	'label'     => esc_html__( 'Close Button Color', 'unicamp' ),
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
			'element'  => '.page-close-mobile-menu',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.page-close-mobile-menu:hover',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'background_type',
	'label'    => esc_html__( 'Background Type', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'solid',
	'choices'  => array(
		'solid'    => esc_html__( 'Classic', 'unicamp' ),
		'gradient' => esc_html__( 'Gradient', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'background',
	'settings'        => $prefix . 'background',
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'default'         => array(
		'background-color'      => '#111',
		'background-image'      => '',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
		'background-position'   => 'top center',
	),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'background_type',
			'operator' => '==',
			'value'    => 'solid',
		),
	),
) );
Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => $prefix . 'background_overlay',
	'label'     => esc_html__( 'Background Overlay', 'unicamp' ),
	'transport' => 'auto',
	'default'   => '',
	'output'    => array(
		array(
			'element'  => '.page-mobile-main-menu > .inner:before',
			'property' => 'background-color',
		),
	),
) );


Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'background_gradient_color',
	'label'           => esc_html__( 'Background Gradient', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color_1' => esc_attr__( 'Color 1', 'unicamp' ),
		'color_2' => esc_attr__( 'Color 2', 'unicamp' ),
	),
	'default'         => array(
		'color_1' => Unicamp::PRIMARY_COLOR,
		'color_2' => Unicamp::SECONDARY_COLOR,
	),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'background_type',
			'operator' => '==',
			'value'    => 'gradient',
		),
	),
) );

// Level 1.

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Level 1', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'spacing',
	'settings'  => $prefix . 'item_padding',
	'label'     => esc_html__( 'Item Padding', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => array(
		'top'    => '19px',
		'bottom' => '19px',
		'left'   => '0',
		'right'  => '0',
	),
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => array(
				'.page-mobile-main-menu .menu__container > li > a',
			),
			'property' => 'padding',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'typo',
	'label'       => esc_html__( 'Typography', 'unicamp' ),
	'description' => esc_html__( 'Controls the typography for mobile menu items.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '500',
		'line-height'    => '1.5',
		'letter-spacing' => '',
		'text-transform' => '',
	),
	'output'      => array(
		array(
			'element' => '.page-mobile-main-menu .menu__container > li >  a',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'item_font_size',
	'label'     => esc_html__( 'Font Size', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 14,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 8,
		'max'  => 50,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.page-mobile-main-menu .menu__container > li > a',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'link_color',
	'label'     => esc_html__( 'Color', 'unicamp' ),
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
			'element'  => '.page-mobile-main-menu .menu__container > li > a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
			.page-mobile-main-menu .menu__container > li > a:hover,
            .page-mobile-main-menu .menu__container > li.opened > a',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => $prefix . 'divider_color',
	'label'     => esc_html__( 'Divider Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => 'rgba(255, 255, 255, 0.15)',
	'output'    => array(
		array(
			'element'  => '
			.page-mobile-main-menu .menu__container > li + li > a,
			.page-mobile-main-menu .menu__container > li.opened > a',
			'property' => 'border-color',
		),
	),
) );

// Mobile Menu Drop down Menu.

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Sub Items', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'spacing',
	'settings'  => $prefix . 'sub_item_padding',
	'label'     => esc_html__( 'Item Padding', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => array(
		'top'    => '10px',
		'bottom' => '10px',
		'left'   => '0',
		'right'  => '0',
	),
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => array(
				'.page-mobile-main-menu .simple-menu > li > a',
				'.page-mobile-main-menu .children > li > a',
			),
			'property' => 'padding',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'sub_item_typo',
	'label'       => esc_html__( 'Typography', 'unicamp' ),
	'description' => esc_html__( 'Controls the typography for mobile menu sub items.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '500',
		'line-height'    => '1.5',
		'letter-spacing' => '',
		'text-transform' => '',
	),
	'output'      => array(
		array(
			'element' => '
			.page-mobile-main-menu .simple-menu > li > a,
			.page-mobile-main-menu .children > li > a
			',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'sub_item_font_size',
	'label'     => esc_html__( 'Font Size', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 13,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 8,
		'max'  => 50,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.page-mobile-main-menu .children > li > a',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'sub_link_color',
	'label'     => esc_html__( 'Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => 'rgba(255, 255, 255, 0.7)',
		'hover'  => '#fff',
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '
			.page-mobile-main-menu .children > li > a,
			.page-mobile-main-menu .unicamp-list .link
			',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '
			.page-mobile-main-menu .children a:hover,
            .page-mobile-main-menu .children .opened > a,
            .page-mobile-main-menu .current-menu-item > a
            ',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Toggle Button', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'toggle_button_text_color',
	'label'     => esc_html__( 'Color', 'unicamp' ),
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
			'element'  => '.page-mobile-main-menu .toggle-sub-menu',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.page-mobile-main-menu .toggle-sub-menu:hover',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'toggle_button_background_color',
	'label'     => esc_html__( 'Background', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => 'rgba(255, 255, 255, 0)',
		'hover'  => 'rgba(255, 255, 255, 0.2)',
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '.page-mobile-main-menu .toggle-sub-menu',
			'property' => 'background',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.page-mobile-main-menu .toggle-sub-menu:hover',
			'property' => 'background',
		),
	),
) );
