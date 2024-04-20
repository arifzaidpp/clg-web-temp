<?php
$section  = 'login';
$priority = 1;
$prefix   = 'login_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => 'login_redirect',
	'label'    => esc_html__( 'Login Redirect', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '',
	'choices'  => [
		''          => esc_html__( 'Current Page', 'unicamp' ),
		'home'      => esc_html__( 'Home', 'unicamp' ),
		'dashboard' => esc_html__( 'Dashboard', 'unicamp' ),
		'custom'    => esc_html__( 'Custom', 'unicamp' ),
	],
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'text',
	'settings'        => 'custom_login_redirect',
	'label'           => esc_html__( 'Custom Url', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default' => '',
	'active_callback' => array(
		array(
			'setting'  => 'login_redirect',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );
