<?php
$section  = 'header';
$priority = 1;
$prefix   = 'header_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'global_header',
	'label'       => esc_html__( 'Global Header Style', 'unicamp' ),
	'description' => esc_html__( 'Select default header style for your site.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '01',
	'choices'     => Unicamp_Header::instance()->get_list(),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'global_header_overlay',
	'label'    => esc_html__( 'Global Header Overlay', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
	'choices'  => array(
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'global_header_skin',
	'label'    => esc_html__( 'Header Skin', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'dark',
	'choices'  => array(
		'dark'  => esc_html__( 'Dark', 'unicamp' ),
		'light' => esc_html__( 'Light', 'unicamp' ),
	),
) );
