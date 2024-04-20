<?php
$section  = 'pre_loader';
$priority = 1;
$prefix   = 'pre_loader_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'enable',
	'label'    => esc_html__( 'Enable Preloader', 'unicamp' ),
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
	'settings' => $prefix . 'style',
	'label'    => esc_html__( 'Style', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'circle',
	'choices'  => Unicamp_Helper::get_preloader_list(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => $prefix . 'background_color',
	'label'     => esc_html__( 'Background Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => '#fff',
	'output'    => array(
		array(
			'element'  => '.page-loading',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'color-alpha',
	'settings'        => $prefix . 'shape_color',
	'label'           => esc_html__( 'Shape Color', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'default'         => Unicamp::PRIMARY_COLOR,
	'output'          => array(
		array(
			'element'  => '.page-loading .sk-wrap',
			'property' => 'color',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'pre_loader_style',
			'operator' => '!=',
			'value'    => 'gif-image',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'image',
	'settings'        => 'pre_loader_image',
	'label'           => esc_html__( 'Gif Image', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => UNICAMP_THEME_IMAGE_URI . '/main-preloader.gif',
	'active_callback' => array(
		array(
			'setting'  => 'pre_loader_style',
			'operator' => '==',
			'value'    => 'gif-image',
		),
	),
) );
