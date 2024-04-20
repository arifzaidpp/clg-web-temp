<?php
$section  = 'layout';
$priority = 1;
$prefix   = 'site_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => $prefix . 'layout',
	'label'       => esc_html__( 'Layout', 'unicamp' ),
	'description' => esc_html__( 'Controls the site layout.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority ++,
	'default'     => 'wide',
	'choices'     => array(
		'boxed' => esc_html__( 'Boxed', 'unicamp' ),
		'wide'  => esc_html__( 'Wide', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'dimension',
	'settings'    => $prefix . 'width',
	'label'       => esc_html__( 'Site Width', 'unicamp' ),
	'description' => esc_html__( 'Controls the overall site width. Enter value including any valid CSS unit. For e.g: 1200px.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority ++,
	'default'     => '1200px',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Boxed Mode Background', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'background',
	'settings'    => 'site_background_body',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls outer background area in boxed mode.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'background-color'      => '#ffffff',
		'background-image'      => '',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'fixed',
		'background-position'   => 'center center',
	),
	'output'      => array(
		array(
			'element' => 'body',
		),
	),
) );
