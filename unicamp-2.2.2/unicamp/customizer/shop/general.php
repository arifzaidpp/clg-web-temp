<?php
$section  = 'shop_general';
$priority = 1;
$prefix   = 'shop_general_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'shop_badge_new',
	'label'       => esc_html__( 'New Badge (Days)', 'unicamp' ),
	'description' => esc_html__( 'Show a "New" label if the product was published within selected time frame.', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '0',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'number',
	'settings'        => 'shop_badge_new_days',
	'label'           => esc_html__( 'Number of days', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'postMessage',
	'default'         => 30,
	'choices'         => array(
		'min'  => 1,
		'max'  => 100,
		'step' => 1,
	),
	'active_callback' => array(
		array(
			'setting'  => 'shop_badge_best_seller',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'shop_badge_hot',
	'label'    => esc_html__( 'Hot Badge', 'unicamp' ),
	'tooltip'  => esc_html__( 'Show a "Hot" label when product set featured.', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'shop_badge_sale',
	'label'    => esc_html__( 'Sale Badge', 'unicamp' ),
	'tooltip'  => esc_html__( 'Show a "Sale" label or "-20%" label when product on sale.', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'shop_badge_free',
	'label'    => esc_html__( 'Free Badge', 'unicamp' ),
	'tooltip'  => esc_html__( 'Show a "Free" label when product has price is 0.', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => 'shop_badge_best_seller',
	'label'    => esc_html__( 'Best Seller Badge', 'unicamp' ),
	'tooltip'  => esc_html__( 'Show a "Best Seller" label when product in of best selling list.', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '1',
	'choices'  => array(
		'0' => esc_html__( 'Hide', 'unicamp' ),
		'1' => esc_html__( 'Show', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'number',
	'settings'        => 'shop_badge_best_seller_number',
	'label'           => esc_html__( 'Number of best seller', 'unicamp' ),
	'description'     => esc_html__( 'How many products do you want to show "Best Seller" label', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'postMessage',
	'default'         => 10,
	'choices'         => array(
		'min'  => 1,
		'max'  => 100,
		'step' => 1,
	),
	'active_callback' => array(
		array(
			'setting'  => 'shop_badge_best_seller',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );
