<?php
$section  = 'title_bar_02';
$priority = 1;
$prefix   = 'title_bar_02_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'radio-buttonset',
	'settings' => $prefix . 'background_type',
	'label'    => esc_html__( 'Background Type', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => 'normal',
	'choices'  => array(
		'normal'   => esc_html__( 'Normal', 'unicamp' ),
		'gradient' => esc_html__( 'Gradient', 'unicamp' ),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'background',
	'settings'        => $prefix . 'background',
	'label'           => esc_html__( 'Background', 'unicamp' ),
	'description'     => esc_html__( 'Controls the background of title bar.', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => array(
		'background-color'      => '#111',
		'background-image'      => UNICAMP_THEME_IMAGE_URI . '/title-bar-02-bg.jpg',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
		'background-position'   => 'center center',
	),
	'output'          => array(
		array(
			'element' => '.page-title-bar-02 .page-title-bar-bg',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'background_type',
			'operator' => '==',
			'value'    => 'normal',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'            => 'multicolor',
	'settings'        => $prefix . 'background_gradient',
	'label'           => esc_html__( 'Background Gradient', 'unicamp' ),
	'section'         => $section,
	'priority'        => $priority++,
	'transport'       => 'auto',
	'choices'         => array(
		'color_1' => esc_attr__( 'Color 1', 'unicamp' ),
		'color_2' => esc_attr__( 'Color 2', 'unicamp' ),
	),
	'default'         => array(
		'color_1' => '#fff',
		'color_2' => '#eceefa',
	),
	'active_callback' => array(
		array(
			'setting'  => $prefix . 'background_type',
			'operator' => '==',
			'value'    => 'gradient',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'bg_overlay_color',
	'label'       => esc_html__( 'Background Overlay Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the background overlay color of title bar.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.page-title-bar-02 .page-title-bar-bg:before',
			'property' => 'background-color',
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
			'element'  => '.page-title-bar-02 .page-title-bar-inner',
			'property' => 'border-bottom-width',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'border_color',
	'label'       => esc_html__( 'Border Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the border bottom color of the page title bar.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => 'rgba(0, 0, 0, 0)',
	'output'      => array(
		array(
			'element'  => '.page-title-bar-02 .page-title-bar-inner',
			'property' => 'border-bottom-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'padding_top',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 17,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.page-title-bar-02 .page-title-bar-inner',
			'property' => 'padding-top',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'padding_bottom',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 61,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'  => '.page-title-bar-02 .page-title-bar-inner',
			'property' => 'padding-bottom',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'margin_bottom',
	'label'     => esc_html__( 'Margin Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	),
	'default'   => 60,
	'output'    => array(
		array(
			'element'  => '.page-title-bar-02',
			'property' => 'margin-bottom',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Heading', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'heading_typography',
	'label'       => esc_html__( 'Font Family', 'unicamp' ),
	'description' => esc_html__( 'Controls the font family for the page title heading.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '700',
		'font-size'      => '52px',
		'line-height'    => '1.24',
		'letter-spacing' => '',
		'text-transform' => '',
		'color'          => '#fff',
	),
	'output'      => array(
		array(
			'element' => '.page-title-bar-02 .heading',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Breadcrumb', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'breadcrumb_text_typography',
	'label'       => esc_html__( 'Typography', 'unicamp' ),
	'description' => esc_html__( 'Controls the typography for the breadcrumb text.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '500',
		'line-height'    => '26px',
		'letter-spacing' => '',
		'text-transform' => '',
		'font-size'      => '14px',
	),
	'output'      => array(
		array(
			'element' => '.page-title-bar-02 .insight_core_breadcrumb li',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'breadcrumb_text_color',
	'label'       => esc_html__( 'Text Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of text on breadcrumb.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => '#fff',
	'output'      => array(
		array(
			'element'  => '.page-title-bar-02 .insight_core_breadcrumb li',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'breadcrumb_link_typography',
	'label'       => esc_html__( 'Typography', 'unicamp' ),
	'description' => esc_html__( 'Controls the typography for the breadcrumb link.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => '',
		'variant'        => '400',
		'line-height'    => '26px',
		'letter-spacing' => '',
		'text-transform' => '',
		'font-size'      => '14px',
	),
	'output'      => array(
		array(
			'element' => '.page-title-bar-02 .insight_core_breadcrumb li a',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => $prefix . 'breadcrumb_link_color',
	'label'       => esc_html__( 'Link Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of links on breadcrumb.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => '#fff',
		'hover'  => '#fff',
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => '.page-title-bar-02 .insight_core_breadcrumb a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => '.page-title-bar-02 .insight_core_breadcrumb a:hover',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => $prefix . 'breadcrumb_separator_color',
	'label'       => esc_html__( 'Separator Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of separator on breadcrumb.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => Unicamp::TEXT_LIGHTEN_COLOR,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.page-title-bar-02 .insight_core_breadcrumb li + li:before',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Responsive Options', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Medium Device', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'md_padding_top',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner',
			'property'    => 'padding-top',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_md_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'md_padding_bottom',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner',
			'property'    => 'padding-bottom',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_md_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'md_heading_font_size',
	'label'     => esc_html__( 'Heading Font Size', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 44,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner .heading',
			'property'    => 'font-size',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_md_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Small Device', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'sm_padding_top',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner',
			'property'    => 'padding-top',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_sm_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'sm_padding_bottom',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'default'   => 50,
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner',
			'property'    => 'padding-bottom',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_sm_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'sm_heading_font_size',
	'label'     => esc_html__( 'Heading Font Size', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 36,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner .heading',
			'property'    => 'font-size',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_sm_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Extra Small Device', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'xs_padding_top',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner',
			'property'    => 'padding-top',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_xs_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'xs_padding_bottom',
	'label'     => esc_html__( 'Padding Bottom', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 50,
		'max'  => 500,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner',
			'property'    => 'padding-bottom',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_xs_media_query(),
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'slider',
	'settings'  => $prefix . 'xs_heading_font_size',
	'label'     => esc_html__( 'Heading Font Size', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'default'   => 28,
	'transport' => 'auto',
	'choices'   => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'    => array(
		array(
			'element'     => '.page-title-bar-02 .page-title-bar-inner .heading',
			'property'    => 'font-size',
			'units'       => 'px',
			'media_query' => Unicamp_Helper::get_xs_media_query(),
		),
	),
) );
