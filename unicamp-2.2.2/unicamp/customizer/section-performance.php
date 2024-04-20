<?php
$section  = 'performance';
$priority = 1;
$prefix   = 'performance_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'toggle',
	'settings'    => 'disable_emoji',
	'label'       => esc_html__( 'Disable Emojis', 'unicamp' ),
	'description' => esc_html__( 'Remove Wordpress Emojis functionality.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 1,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'toggle',
	'settings'    => 'disable_embeds',
	'label'       => esc_html__( 'Disable Embeds', 'unicamp' ),
	'description' => esc_html__( 'Remove Wordpress Embeds functionality.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 1,
) );
