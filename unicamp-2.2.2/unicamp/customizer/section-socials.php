<?php
$section  = 'socials';
$priority = 1;
$prefix   = 'social_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'social_link_target',
	'label'    => esc_html__( 'Open in new window', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'No', 'unicamp' ),
		'1' => esc_html__( 'Yes', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'         => 'repeater',
	'settings'     => 'social_link',
	'section'      => $section,
	'priority'     => $priority++,
	'button_label' => esc_html__( 'Add new social network', 'unicamp' ),
	'row_label'    => array(
		'type'  => 'field',
		'field' => 'tooltip',
	),
	'default'      => array(
		array(
			'tooltip'    => esc_html__( 'Twitter', 'unicamp' ),
			'icon_class' => 'fab fa-twitter',
			'link_url'   => 'https://twitter.com',
		),
		array(
			'tooltip'    => esc_html__( 'Facebook', 'unicamp' ),
			'icon_class' => 'fab fa-facebook-f',
			'link_url'   => 'https://facebook.com',
		),
		array(
			'tooltip'    => esc_html__( 'Instagram', 'unicamp' ),
			'icon_class' => 'fab fa-instagram',
			'link_url'   => 'https://instagram.com',
		),
		array(
			'tooltip'    => esc_html__( 'Linkedin', 'unicamp' ),
			'icon_class' => 'fab fa-linkedin-in',
			'link_url'   => 'https://linkedin.com',
		),
	),
	'fields'       => array(
		'tooltip'    => array(
			'type'        => 'text',
			'label'       => esc_html__( 'Tooltip', 'unicamp' ),
			'description' => esc_html__( 'Enter your hint text for your icon', 'unicamp' ),
			'default'     => '',
		),
		'icon_class' => array(
			'type'        => 'text',
			'label'       => esc_html__( 'Icon Class', 'unicamp' ),
			'description' => esc_html__( 'This will be the icon class for your link', 'unicamp' ),
			'default'     => '',
		),
		'link_url'   => array(
			'type'        => 'text',
			'label'       => esc_html__( 'Link URL', 'unicamp' ),
			'description' => esc_html__( 'This will be the link URL', 'unicamp' ),
			'default'     => '',
		),
	),
) );
