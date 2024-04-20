<?php
$panel    = 'shop';
$priority = 1;

Unicamp_Kirki::add_section( 'shop_general', array(
	'title'    => esc_html__( 'General', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'shop_archive', array(
	'title'    => esc_html__( 'Shop Archive', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'shop_single', array(
	'title'    => esc_html__( 'Shop Single', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'shopping_cart', array(
	'title'    => esc_html__( 'Shopping Cart', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );
