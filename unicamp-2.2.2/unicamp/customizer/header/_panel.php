<?php
$panel    = 'header';
$priority = 1;

Unicamp_Kirki::add_section( 'header', array(
	'title'       => esc_html__( 'General', 'unicamp' ),
	'description' => '<div class="desc">
			<strong class="insight-label insight-label-info">' . esc_html__( 'IMPORTANT NOTE: ', 'unicamp' ) . '</strong>
			<p>' . esc_html__( 'These settings can be overridden by settings from Page Options Box in separator page.', 'unicamp' ) . '</p>
			<p><img src="' . esc_url( UNICAMP_THEME_IMAGE_URI . '/customize/header-settings.jpg' ) . '" alt="' . esc_attr__( 'header-settings', 'unicamp' ) . '"/></p>
			<strong class="insight-label insight-label-info">' . esc_html__( 'Powerful header control: ', 'unicamp' ) . '</strong>
			<p>' . esc_html__( 'These header settings for whole website. If you want use different header style for different post or page. then please go to specific section.', 'unicamp' ) . '</p>
		</div>',
	'panel'       => $panel,
	'priority'    => $priority++,
) );

Unicamp_Kirki::add_section( 'header_sticky', array(
	'title'    => esc_html__( 'Header Sticky', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

Unicamp_Kirki::add_section( 'header_more_options', array(
	'title'    => esc_html__( 'Header More Options', 'unicamp' ),
	'panel'    => $panel,
	'priority' => $priority++,
) );

$header_types = Unicamp_Header::instance()->get_type();

foreach ( $header_types as $key => $name ) {
	$section_id = 'header_style_' . $key;

	Unicamp_Kirki::add_section( $section_id, array(
		'title'    => $name,
		'panel'    => $panel,
		'priority' => $priority++,
	) );
}
