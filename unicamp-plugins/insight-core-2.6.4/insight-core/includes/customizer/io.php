<?php
require_once( trailingslashit( dirname( __FILE__ ) ) . 'io-control.php' );

// Customizer Import/Export
require_once( trailingslashit( dirname( __FILE__ ) ) . 'import.php' );
require_once( trailingslashit( dirname( __FILE__ ) ) . 'export.php' );

/**
 * ============================================================================
 * Register the control with the customizer
 * ============================================================================
 */
function insight_register_io( $wp_customize ) {

	// Add the export/import section.
	$wp_customize->add_section( 'io_section', array(
		'title'    => __( 'Import / Export', 'insight-core' ),
		'priority' => 15000,
	) );

	// Add the export/import setting.
	$wp_customize->add_setting( 'io_setting', array(
		'default' => '',
		'type'    => 'none',
	) );

	// Add the export/import control.
	$wp_customize->add_control( new Insight_IO_Control( $wp_customize, 'io_setting', array(
		'section'  => 'io_section',
		'priority' => 1,
	) ) );
}

add_action( 'customize_register', 'insight_register_io' );