<?php
if ( ! function_exists( 'insight_file_contents' ) ) {
	function insight_file_contents( $path ) {
		if ( function_exists( 'realpath' ) ) {
			$filepath = realpath( $path );
		}

		if ( ! $filepath || ! @is_file( $filepath ) ) {
			return '';
		}

		if ( ini_get( 'allow_url_fopen' ) ) {
			$file_content = '';
			$file_handle  = fopen( $filepath, 'rb' );

			if ( $file_handle !== false ) {
				while ( ! feof( $file_handle ) ) {
					$file_content .= fread( $file_handle, 8192 );
				}
				fclose( $file_handle );
			}

			return $file_content;
		} else {
			return file_get_contents( $filepath );
		}
	}
}

if ( ! function_exists( 'insight_json_encode' ) ) {
	function insight_json_encode( $data ) {
		if ( function_exists( 'wp_json_encode' ) ) {
			return wp_json_encode( $data );
		} else {
			return json_encode( $data );
		}
	}
}

function insight_import() {
	if ( ! check_ajax_referer( 'customizer_import', 'insight_customizer_import' ) ) {
		wp_die();
	}

	if ( ! current_user_can( 'administrator' ) ) {
		wp_send_json_error( [
			'messages' => esc_html__( 'Permission denied!', 'insight-core' ),
		] );
	}

	$file_upload = isset( $_FILES['import-file']['tmp_name'] ) ? esc_url_raw( $_FILES['import-file']['tmp_name'] ) : false;

	if ( empty( $file_upload ) ) {
		wp_send_json_error( [
			'messages' => esc_html__( 'Import is failed!', 'insight-core' ),
		] );
	}

	$options = maybe_unserialize( insight_file_contents( $file_upload ) );

	if ( ! empty( $options ) && is_array( $options ) ) {
		foreach ( $options as $key => $val ) {
			$sanitize_val = sanitize_option( $key, $val );

			set_theme_mod( $key, $sanitize_val );
		}

		wp_send_json_success( [
			'messages' => esc_html__( 'Import is successful!', 'insight-core' ),
		] );
	}

	wp_send_json_error( [
		'messages' => esc_html__( 'Import is failed!', 'insight-core' ),
	] );
}

add_action( 'wp_ajax_insight_customizer_options_import', 'insight_import' );
