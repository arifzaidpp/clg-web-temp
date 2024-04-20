<?php
$section  = 'footer';
$priority = 1;
$prefix   = 'footer';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'textarea',
	'settings' => 'footer_copyright_text',
	'label'    => esc_html__( 'Copyright Text', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => esc_html__( 'Copyright &copy; 2021. All rights reserved.', 'unicamp' ),
) );
