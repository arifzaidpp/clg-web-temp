<?php
$panel    = 'top_bar';
$priority = 1;

Unicamp_Kirki::add_section( 'top_bar', array(
	'title'    => esc_html__( 'General', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'top_bar_style_01', array(
	'title'    => esc_html__( 'Top Bar Style 01', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'top_bar_style_02', array(
	'title'    => esc_html__( 'Top Bar Style 02', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'top_bar_style_03', array(
	'title'    => esc_html__( 'Top Bar Style 03', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'top_bar_style_04', array(
	'title'    => esc_html__( 'Top Bar Style 04', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'top_bar_style_05', array(
	'title'    => esc_html__( 'Top Bar Style 05', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );
