<?php
$section  = 'color_';
$priority = 1;
$prefix   = 'color_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => 'primary_color',
	'label'     => esc_html__( 'Primary Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Unicamp::PRIMARY_COLOR,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => 'secondary_color',
	'label'     => esc_html__( 'Secondary Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Unicamp::SECONDARY_COLOR,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => 'third_color',
	'label'     => esc_html__( 'Third Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Unicamp::THIRD_COLOR,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => 'body_color',
	'label'       => esc_html__( 'Text Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the default color of all text.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => Unicamp::TEXT_COLOR,
	'output'      => array(
		array(
			'element'  => 'body',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'color-alpha',
	'settings'  => 'body_lighten_color',
	'label'     => esc_html__( 'Text Lighten Color', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => Unicamp::TEXT_LIGHTEN_COLOR,
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => 'link_color',
	'label'       => esc_html__( 'Link Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the default color of all links.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'normal' => esc_attr__( 'Normal', 'unicamp' ),
		'hover'  => esc_attr__( 'Hover', 'unicamp' ),
	),
	'default'     => array(
		'normal' => '#696969',
		'hover'  => Unicamp::PRIMARY_COLOR,
	),
	'output'      => array(
		array(
			'choice'   => 'normal',
			'element'  => 'a',
			'property' => 'color',
		),
		array(
			'choice'   => 'hover',
			'element'  => 'a:hover, a:focus',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'color-alpha',
	'settings'    => 'heading_color',
	'label'       => esc_html__( 'Heading Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of heading.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => Unicamp::HEADING_COLOR,
	'output'      => array(
		array(
			'element'  => 'h1,h2,h3,h4,h5,h6,caption,th,blockquote, .heading, .heading-color',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Button Color', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button Default', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_color',
	'label'     => esc_html__( 'Normal', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => Unicamp::PRIMARY_COLOR,
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => Unicamp_Helper::get_button_css_selector(),
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => Unicamp_Helper::get_button_css_selector(),
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => Unicamp_Helper::get_button_css_selector(),
			'property' => 'background-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.wp-block-button.is-style-outline',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_hover_color',
	'label'     => esc_html__( 'Hover', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => '#111',
		'border'     => '#111',
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => Unicamp_Helper::get_button_hover_css_selector(),
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => Unicamp_Helper::get_button_hover_css_selector(),
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => Unicamp_Helper::get_button_hover_css_selector(),
			'property' => 'background-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.wp-block-button.is-style-outline .wp-block-button__link:hover',
			'property' => 'color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button Flat', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_flat_color',
	'label'     => esc_html__( 'Normal', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => Unicamp::PRIMARY_COLOR,
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '.tm-button.style-flat',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '.tm-button.style-flat',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.tm-button.style-flat:before',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_flat_hover_color',
	'label'     => esc_html__( 'Hover', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => '#111',
		'border'     => '#111',
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '.tm-button.style-flat:hover',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '.tm-button.style-flat:hover',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '.tm-button.style-flat:after',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="group_title">' . esc_html__( 'Button Border', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_border_color',
	'label'     => esc_html__( 'Normal', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => Unicamp::PRIMARY_COLOR,
		'background' => 'rgba(0, 0, 0, 0)',
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '
			.tm-button.style-border,
			.tm-button.style-thick-border
			',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '
			.tm-button.style-border,
			.tm-button.style-thick-border
			',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '
			.tm-button.style-border:before,
			.tm-button.style-thick-border:before
			',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'      => 'multicolor',
	'settings'  => 'button_style_border_hover_color',
	'label'     => esc_html__( 'Hover', 'unicamp' ),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'choices'   => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'   => array(
		'color'      => '#fff',
		'background' => Unicamp::PRIMARY_COLOR,
		'border'     => Unicamp::PRIMARY_COLOR,
	),
	'output'    => array(
		array(
			'choice'   => 'color',
			'element'  => '
			.tm-button.style-border:hover,
			.tm-button.style-thick-border:hover
			',
			'property' => 'color',
		),
		array(
			'choice'   => 'border',
			'element'  => '
			.tm-button.style-border:hover,
			.tm-button.style-thick-border:hover
			',
			'property' => 'border-color',
		),
		array(
			'choice'   => 'background',
			'element'  => '
			.tm-button.style-border:after,
			.tm-button.style-thick-border:after
			',
			'property' => 'background-color',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Form Color', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => 'form_input_color',
	'label'       => esc_html__( 'Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of form inputs.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'     => array(
		'color'      => Unicamp::TEXT_COLOR,
		'background' => '#f5f5f5',
		'border'     => '#f5f5f5',
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'multicolor',
	'settings'    => 'form_input_focus_color',
	'label'       => esc_html__( 'Focus Color', 'unicamp' ),
	'description' => esc_html__( 'Controls the color of form inputs when focus.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'choices'     => array(
		'color'      => esc_attr__( 'Color', 'unicamp' ),
		'background' => esc_attr__( 'Background', 'unicamp' ),
		'border'     => esc_attr__( 'Border', 'unicamp' ),
	),
	'default'     => array(
		'color'      => Unicamp::HEADING_COLOR,
		'background' => '#fff',
		'border'     => Unicamp::PRIMARY_COLOR,
	),
) );
