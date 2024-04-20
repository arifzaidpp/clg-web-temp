<?php
$section  = 'top_bar_style_02';
$priority = 1;
$prefix   = 'top_bar_style_02_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'select',
	'settings' => $prefix . 'layout',
	'label'    => esc_html__( 'Layout', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '2',
	'choices'  => array(
		'1l' => esc_html__( '1 Column - Left', 'unicamp' ),
		'1c' => esc_html__( '1 Column - Center', 'unicamp' ),
		'1r' => esc_html__( '1 Column - Right', 'unicamp' ),
		'2'  => esc_html__( '2 Columns', 'unicamp' ),
		'3'  => esc_html__( '3 Columns', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicheck',
	'settings'        => $prefix . 'left_components',
	'label'           => esc_html__( 'Left Components', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => [ 'social_links' ],
	'choices'         => Unicamp_Top_Bar::instance()->get_support_components(),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'layout',
			'operator' => 'in',
			'value'    => [
				'1l',
				'2',
				'3',
			],
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicheck',
	'settings'        => $prefix . 'center_components',
	'label'           => esc_html__( 'Center Components', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => [ 'text' ],
	'choices'         => Unicamp_Top_Bar::instance()->get_support_components(),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'layout',
			'operator' => 'in',
			'value'    => [
				'1c',
				'3',
			],
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicheck',
	'settings'        => $prefix . 'right_components',
	'label'           => esc_html__( 'Right Components', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => [ 'secondary_menu', 'search_popup' ],
	'choices'         => Unicamp_Top_Bar::instance()->get_support_components(),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'layout',
			'operator' => 'in',
			'value'    => [
				'1r',
				'2',
				'3',
			],
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'textarea',
	'settings' => $prefix . 'text',
	'label'    => esc_html__( 'Text', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'         => 'repeater',
	'settings'     => $prefix . 'info_list',
	'label'        => esc_html__( 'Info List', 'unicamp' ),
	'section'      => $section,
	'priority'     => $priority++,
	'button_label' => esc_html__( 'Add new info', 'unicamp' ),
	'row_label'    => array(
		'type'  => 'field',
		'field' => 'text',
	),
	'default'      => array(
		array(
			'text'       => '(+88) 1990 6886',
			'url'        => 'tel:+8819906886',
			'icon_class' => 'far fa-phone',
			'link_class' => '',
		),
		array(
			'text'       => 'agency@thememove.com',
			'url'        => 'mailto:agency@thememove.com',
			'icon_class' => 'far fa-envelope',
			'link_class' => 'font-400',
		),
	),
	'fields'       => array(
		'text'       => array(
			'type'    => 'textarea',
			'label'   => esc_html__( 'Title', 'unicamp' ),
			'default' => '',
		),
		'url'        => array(
			'type'    => 'text',
			'label'   => esc_html__( 'Link', 'unicamp' ),
			'default' => '',
		),
		'icon_class' => array(
			'type'    => 'text',
			'label'   => esc_html__( 'Icon Class', 'unicamp' ),
			'default' => '',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Style', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'padding_top',
	'label'     => esc_html__( 'Padding top', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 6,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 200,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.top-bar-02',
			'property' => 'padding-top',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'padding_bottom',
	'label'     => esc_html__( 'Padding bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 6,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 200,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.top-bar-02',
			'property' => 'padding-bottom',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'border_width',
	'label'     => esc_html__( 'Border Bottom Width', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 0,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.top-bar-02',
			'property' => 'border-bottom-width',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'text_typography',
	'label'       => esc_html__( 'Text Typography', 'unicamp' ),
	'description' => esc_html__( 'These settings control the typography of text', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '400',
		'line-height'    => '26px',
		'letter-spacing' => '',
		'font-size'      => '13px',
	),
	'output'      => array(
		array(
			'element' => '.top-bar-02',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'link_typography',
	'label'       => esc_html__( 'Link Typography', 'unicamp' ),
	'description' => esc_html__( 'These settings control the typography of link', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '500',
		'line-height'    => '26px',
		'letter-spacing' => '',
		'font-size'      => '13px',
	),
	'output'      => array(
		array(
			'element' => '.top-bar-02 a',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'bg_color',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls the background color of top bar.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => '#10386B',
	'output'      => array(
		array(
			'element'  => '.top-bar-02',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'border_color',
	'label'       => esc_html__( 'Border Bottom Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the border bottom color of top bar.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => 'rgba(0, 0, 0, 0)',
	'output'      => array(
		array(
			'element'  => '.top-bar-02',
			'property' => 'border-bottom-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => $prefix . 'separator_color',
	'label'     => esc_html__( 'Separator Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => 'rgba(255, 255, 255, 0.4)',
	'output'    => array(
		array(
			'element'  => '
			.top-bar-02 .top-bar-user-links a + a:before,
			.top-bar-02 .top-bar-info .info-item + .info-item:before
			',
			'property' => 'background',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color',
	'settings'    => $prefix . 'text_color',
	'label'       => esc_html__( 'Text', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of text on top bar.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => '#fff',
	'output'      => array(
		array(
			'element'  => '.top-bar-02',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'link_color',
	'label'       => esc_html__( 'Link Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of links on top bar.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => '#fff',
		'hover'  => 'rgba(255, 255, 255, 0.6)',
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '.top-bar-02 a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.top-bar-02 a:hover, .top-bar-02 a:focus',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => $prefix . 'icon_color',
	'label'     => esc_html__( 'Icon Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'   => array(
		'normal' => '#fff',
		'hover'  => 'rgba(255, 255, 255, 0.6)',
	),
	'output'    => array(
		array(
			'choice'   => 'normal',
			'element'  => '.top-bar-02 .info-list .info-icon, .top-bar-02 .top-bar-icon',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.top-bar-02 .info-list .info-link:hover .info-icon, .top-bar-02 .top-bar-icon:hover',
			'property' => 'color',
		),
	),
) );
