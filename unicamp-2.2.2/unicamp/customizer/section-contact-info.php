<?php
$section  = 'contact_info';
$priority = 1;
$prefix   = 'contact_info_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'text',
	'settings' => $prefix . 'phone',
	'label'    => esc_html__( 'Phone Number', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '(+1) 234567899',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'text',
	'settings' => $prefix . 'email',
	'label'    => esc_html__( 'Email', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'info@unicamp.com',
) );
