<?php
$section  = 'typography';
$priority = 1;
$prefix   = 'typography_';

$font_weights = array(
	'regular',
	'500',
	'700',
);

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="desc"><strong class="insight-label insight-label-info">' . esc_html__( 'IMPORTANT NOTE: ', 'unicamp' ) . '</strong>' . esc_html__( 'This section contains general typography options. Additional typography options for specific areas can be found within other sections. Example: For breadcrumb typography options go to the breadcrumb section.', 'unicamp' ) . '</div>',
) );

/*--------------------------------------------------------------
# Body Typography
--------------------------------------------------------------*/
Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Body Typography', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'body',
	'label'       => esc_html__( 'Font family', 'unicamp' ),
	'description' => esc_html__( 'These settings control the typography for all body text.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => Unicamp::PRIMARY_FONT,
		'variant'        => '400',
		'font-size'      => '15px',
		'line-height'    => '1.87',
		'letter-spacing' => '0em',
	),
	'choices'     => array(
		'variant' => $font_weights,
	),
	'output'      => array(
		array(
			'element' => 'body, .gmap-marker-wrap',
		),
	),
) );

/*--------------------------------------------------------------
# Heading typography
--------------------------------------------------------------*/
Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Heading Typography', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'heading',
	'label'       => esc_html__( 'Font family', 'unicamp' ),
	'description' => esc_html__( 'These settings control the typography for all heading text.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => Unicamp::HEADING_FONT,
		'variant'        => 700,
		'line-height'    => '1.3',
		'letter-spacing' => '0em',
	),
	'choices'     => array(
		'variant' => $font_weights,
	),
	'output'      => array(
		array(
			'element' => 'h1,h2,h3,h4,h5,h6,.heading,.heading-typography',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'slider',
	'settings'    => 'h1_font_size',
	'label'       => esc_html__( 'Font size', 'unicamp' ),
	'description' => esc_html__( 'H1', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 38,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'      => array(
		array(
			'element'  => 'h1',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'slider',
	'settings'    => 'h2_font_size',
	'description' => esc_html__( 'H2', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 34,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'      => array(
		array(
			'element'  => 'h2',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'slider',
	'settings'    => 'h3_font_size',
	'description' => esc_html__( 'H3', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 30,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'      => array(
		array(
			'element'  => 'h3',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'slider',
	'settings'    => 'h4_font_size',
	'description' => esc_html__( 'H4', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 26,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'      => array(
		array(
			'element'  => 'h4',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'slider',
	'settings'    => 'h5_font_size',
	'description' => esc_html__( 'H5', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 22,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'      => array(
		array(
			'element'  => 'h5',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'slider',
	'settings'    => 'h6_font_size',
	'description' => esc_html__( 'H6', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 18,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
	'output'      => array(
		array(
			'element'  => 'h6',
			'property' => 'font-size',
			'units'    => 'px',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'select',
	'settings'    => 'strong_font_weight',
	'label'       => esc_html__( 'Strong Tag Weight', 'unicamp' ),
	'description' => esc_html__( 'Controls font weight of &lt;strong&gt;, &lt;b&gt; tags', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '700',
	'transport'   => 'auto',
	'choices'     => array(
		'400' => esc_html__( '400 - Regular', 'unicamp' ),
		'500' => esc_html__( '500 - Medium', 'unicamp' ),
		'600' => esc_html__( '600 - Semi Bold', 'unicamp' ),
		'700' => esc_html__( '700 - Bold', 'unicamp' ),
		'800' => esc_html__( '800 - Extra Bold', 'unicamp' ),
		'900' => esc_html__( '900 - Ultra Bold (Black)', 'unicamp' ),
	),
	'output'      => array(
		array(
			'element'  => 'b, strong',
			'property' => 'font-weight',
		),
	),
) );

/*--------------------------------------------------------------
# Button
--------------------------------------------------------------*/
Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'custom',
	'settings' => $prefix . 'group_title_' . $priority++,
	'section'  => $section,
	'priority' => $priority++,
	'default'  => '<div class="big_title">' . esc_html__( 'Buttons', 'unicamp' ) . '</div>',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'kirki_typography',
	'settings'    => 'button_typography',
	'label'       => esc_html__( 'Font family', 'unicamp' ),
	'description' => esc_html__( 'These settings control the typography for buttons.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => 'inherit',
		'variant'        => '700',
		'font-size'      => '14px',
		'text-transform' => 'none',
		'letter-spacing' => '0em',
	),
	'choices'     => array(
		'variant' => $font_weights,
	),
	'output'      => array(
		array(
			'element' => Unicamp_Helper::get_button_typography_css_selector(),
		),
	),
) );
