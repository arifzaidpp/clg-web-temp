<?php
$section  = 'light_gallery';
$priority = 1;
$prefix   = 'light_gallery_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'background',
	'label'    => esc_html__( 'Background', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => 'custom',
	'choices'  => array(
		'primary'   => esc_html__( 'Primary', 'unicamp' ),
		'secondary' => esc_html__( 'Secondary', 'unicamp' ),
		'custom'    => esc_html__( 'Custom', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'color',
	'settings'        => $prefix . 'custom_background',
	'label'           => esc_html__( 'Custom Background Color', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority ++,
	'transport'       => 'auto',
	'default'         => '#000',
	'active_callback' => array(
		array(
			'setting'  => 'light_gallery_background',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'auto_play',
	'label'    => esc_html__( 'Auto Play', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => '0',
	'choices'  => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'download',
	'label'    => esc_html__( 'Download Button', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'full_screen',
	'label'    => esc_html__( 'Full Screen Button', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'share',
	'label'    => esc_html__( 'Share Button', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'zoom',
	'label'    => esc_html__( 'Zoom Buttons', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'thumbnail',
	'label'    => esc_html__( 'Thumbnail Gallery', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority ++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Off', 'unicamp' ),
		'1' => esc_html__( 'On', 'unicamp' ),
	),
) );
