<?php
$panel    = 'search';
$priority = 1;

Unicamp_Kirki::add_section( 'search_page', array(
	'title'    => esc_html__( 'Search Page', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );

Unicamp_Kirki::add_section( 'search_popup', array(
	'title'    => esc_html__( 'Search Popup', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority ++,
) );
