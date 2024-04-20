<?php

if ( $importer_version == '1' ) {
	require_once( dirname( __FILE__ ) . '/v1/class-importer.php' );
} else {
	require_once( dirname( __FILE__ ) . '/v2/class-importer.php' );
}

if ( ! empty( $_POST['import_sample_data'] ) ) { // WPCS: CSRF OK.

	global $wpdb;

	if ( $importer_version == '1' ) {
		$ic_importer                 = new InsightCore_Importer( true );
		$ic_importer->generate_thumb = $generate_thumb;
	} else {
		$option = array(
			'import_full_demo' => true,
			'generate_thumb'   => $generate_thumb,
		);

		$ic_importer = new InsightCore_Importer_2( $option );
		$logger      = new WP_Importer_Logger_CLI();
		$ic_importer->set_logger( $logger );
	}
	$start = microtime( true );

	$ic_importer->dispatch();

	$time_elapsed_secs = format_import_time( microtime( true ) - $start );

	echo '<script type="text/javascript">document.title="' . esc_html__( 'Initializing Data', 'insight-core' ) . '";</script>';
}

/**
 * Format import time to human readable
 *
 * @param $time
 *
 * @return string
 */
function format_import_time( $time ) {
	return gmdate( 'H:i:s', $time );
}
