<?php
$panel    = 'blog';
$priority = 1;

Unicamp_Kirki::add_section( 'blog_archive', array(
	'title'    => esc_html__( 'Blog Archive', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'blog_single', array(
	'title'    => esc_html__( 'Blog Single Post', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );
