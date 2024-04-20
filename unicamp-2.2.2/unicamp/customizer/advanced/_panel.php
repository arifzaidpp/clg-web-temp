<?php
$panel    = 'advanced';
$priority = 1;

Unicamp_Kirki::add_section( 'advanced', array(
	'title'    => esc_html__( 'Advanced', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );

Unicamp_Kirki::add_section( 'light_gallery', array(
	'title'    => esc_html__( 'Light Gallery', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );
