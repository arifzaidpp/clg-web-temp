<?php
$section  = 'social_sharing';
$priority = 1;
$prefix   = 'social_sharing_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicheck',
	'settings'    => $prefix . 'item_enable',
	'label'       => esc_attr__( 'Sharing Links', 'unicamp' ),
	'description' => esc_html__( 'Check to the box to enable social share links.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array( 'facebook', 'twitter', 'linkedin', 'tumblr', 'email' ),
	'choices'     => array(
		'facebook' => esc_attr__( 'Facebook', 'unicamp' ),
		'twitter'  => esc_attr__( 'Twitter', 'unicamp' ),
		'linkedin' => esc_attr__( 'Linkedin', 'unicamp' ),
		'tumblr'   => esc_attr__( 'Tumblr', 'unicamp' ),
		'email'    => esc_attr__( 'Email', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'sortable',
	'settings'    => $prefix . 'order',
	'label'       => esc_attr__( 'Order', 'unicamp' ),
	'description' => esc_html__( 'Controls the order of social share links.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'twitter',
		'facebook',
		'linkedin',
		'tumblr',
		'email',
	),
	'choices'     => array(
		'facebook' => esc_attr__( 'Facebook', 'unicamp' ),
		'twitter'  => esc_attr__( 'Twitter', 'unicamp' ),
		'linkedin' => esc_attr__( 'Linkedin', 'unicamp' ),
		'tumblr'   => esc_attr__( 'Tumblr', 'unicamp' ),
		'email'    => esc_attr__( 'Email', 'unicamp' ),
	),
) );
