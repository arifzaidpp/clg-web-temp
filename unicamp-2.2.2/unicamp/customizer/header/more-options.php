<?php
$section  = 'header_more_options';
$priority = 1;
$prefix   = 'header_more_options_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'background',
	'settings'    => $prefix . 'background',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls the background of header more options.', 'unicamp' ),
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
			'element' => '.header-more-tools-opened .header-right-inner',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'border_width',
	'label'     => esc_html__( 'Border Width', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 1,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.header-more-tools-opened .header-right-inner',
			'property' => 'border-width',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'border_color',
	'label'       => esc_html__( 'Border Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the border color of header.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => '#eee',
	'output'      => array(
		array(
			'element'  => '.header-more-tools-opened .header-right-inner',
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
	'default'      => '0 0 26px rgba(0, 0, 0, 0.05)',
	'output'       => array(
		array(
			'element'  => '.header-more-tools-opened .header-right-inner',
			'property' => 'box-shadow',
		),
	),
	'translatable' => false,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'social_networks_color',
	'label'     => esc_html__( 'Social Networks Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => '#333',
		'hover'  => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '.header-more-tools-opened .header-right-inner .header-social-networks a',
			'property' => 'color',
			'suffix'   => '!important',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.header-more-tools-opened .header-right-inner .header-social-networks a:hover',
			'property' => 'color',
			'suffix'   => '!important',
		),
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
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'button_color',
	'label'    => esc_html__( 'Button Color', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
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
		'color'      => Unicamp::PRIMARY_COLOR,
		'background' => 'rgba(0, 0, 0, 0)',
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'          => array(
		array(
			'choice'   => 'color',
			'element'  => '.header-sticky-button .tm-button',
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
		'background' => Unicamp::PRIMARY_COLOR,
		'border'     => Unicamp::PRIMARY_COLOR,
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
