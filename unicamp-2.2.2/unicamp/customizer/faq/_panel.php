<?php
$panel    = 'faq';
$priority = 1;

Unicamp_Kirki::add_section( 'faq_archive', array(
	'title'    => esc_html__( 'FAQ Archive', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'faq_single', array(
	'title'    => esc_html__( 'FAQ Single', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );
