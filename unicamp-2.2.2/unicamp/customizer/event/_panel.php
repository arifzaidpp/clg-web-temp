<?php
$panel    = 'event';
$priority = 1;

Unicamp_Kirki::add_section( 'event_archive', array(
	'title'    => esc_html__( 'Event Archive', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'event_single', array(
	'title'    => esc_html__( 'Event Single', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );
