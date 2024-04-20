<?php
$section  = 'advanced';
$priority = 1;
$prefix   = 'advanced_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'toggle',
	'settings'    => 'smooth_scroll_enable',
	'label'       => esc_html__( 'Smooth Scroll', 'unicamp' ),
	'description' => esc_html__( 'Smooth scrolling experience for websites.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 0,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'toggle',
	'settings'    => 'scroll_top_enable',
	'label'       => esc_html__( 'Go To Top Button', 'unicamp' ),
	'description' => esc_html__( 'Turn on to show go to top button.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 1,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'toggle',
	'settings'    => 'content_protected_enable',
	'label'       => esc_html__( 'Content Protected', 'unicamp' ),
	'description' => esc_html__( 'Turn on to enable content protected feature.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 0,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'         => 'text',
	'settings'     => 'google_api_key',
	'label'        => esc_html__( 'Google Api Key', 'unicamp' ),
	'description'  => sprintf( wp_kses( __( 'Follow <a href="%s" target="_blank">this link</a> and click <strong>GET A KEY</strong> button.', 'unicamp' ), array(
		'a'      => array(
			'href'   => array(),
			'target' => array(),
		),
		'strong' => array(),
	) ), esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key' ) ),
	'section'      => $section,
	'priority'     => $priority++,
	'default'      => '',
	'transport'    => 'postMessage',
	'translatable' => false,
) );
