<?php
$section  = 'top_bar';
$priority = 1;
$prefix   = 'top_bar_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'global_top_bar',
	'label'    => esc_html__( 'Default Top Bar', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'none',
	'choices'  => Unicamp_Top_Bar::instance()->get_list(),
) );
