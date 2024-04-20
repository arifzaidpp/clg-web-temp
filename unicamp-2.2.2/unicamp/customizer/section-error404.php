<?php
$section  = 'error404_page';
$priority = 1;
$prefix   = 'error404_page_';

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'background',
	'settings'    => 'error404_page_background_body',
	'label'       => esc_html__( 'Background', 'unicamp' ),
	'description' => esc_html__( 'Controls outer background area in boxed mode.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'background-color'      => '',
		'background-image'      => '',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'fixed',
		'background-position'   => 'center center',
	),
	'output'      => array(
		array(
			'element' => '.error404',
		),
	),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'     => 'image',
	'settings' => 'error404_page_image',
	'label'    => esc_html__( 'Image', 'unicamp' ),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => UNICAMP_THEME_IMAGE_URI . '/page-404-image.png',
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'text',
	'settings'    => 'error404_page_title',
	'label'       => esc_html__( 'Title', 'unicamp' ),
	'description' => esc_html__( 'Controls the title that display on error 404 page.', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'Oops! That page can\'t be found.', 'unicamp' ),
) );

Unicamp_Kirki::add_field( 'theme', array(
	'type'        => 'textarea',
	'settings'    => 'error404_page_text',
	'label'       => esc_html__( 'Text', 'unicamp' ),
	'description' => esc_html__( 'Controls the text that display below title', 'unicamp' ),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'unicamp' ),
) );
