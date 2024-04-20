<?php
$panel    = 'navigation';
$priority = 1;

Unicamp_Kirki::add_section( 'navigation', array(
	'title'    => esc_html__( 'Desktop Menu', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );

Unicamp_Kirki::add_section( 'navigation_minimal_01', array(
	'title'    => esc_html__( 'Off Canvas Menu', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );

Unicamp_Kirki::add_section( 'navigation_mobile', array(
	'title'    => esc_html__( 'Mobile Menu', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );
