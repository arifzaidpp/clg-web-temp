<?php
defined( 'ABSPATH' ) || exit;

/**
 * Helper functions.
 *
 * All functions don't need to name prefix because they checked with function_exists.
 */


/**
 * Returns '0'.
 *
 * Useful for returning 0 to filters easily.
 *
 * @return string '0'.
 */
if ( ! function_exists( '__return_zero_string' ) ) {
	function __return_zero_string() {
		return '0';
	}
}

if ( ! function_exists( 'html_class' ) ) {
	function html_class( $class = '' ) {
		$classes = array();

		if ( is_admin_bar_showing() ) {
			$classes[] = 'has-admin-bar';
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'html_class', $classes, $class );

		if ( ! empty( $classes ) ) {
			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}
	}
}

/**
 * Hook in wp 5.2
 * Backwards Compatibility with old versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Loads a template part into a template with prefix folder given.
 *
 * @see get_template_part()
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args Optional. Additional arguments passed to the template.
 *                     Default empty array.
 *
 * @return void|false Void on success, false if the template does not exist.
 */
function unicamp_load_template( $slug, $name = null, $args = array() ) {
	get_template_part( "template-parts/{$slug}", $name, $args );
}

function unicamp_valid_file_path( $file_path ) {
	return str_replace( '/', UNICAMP_DS, $file_path );
}

function unicamp_require_once( $file_path ) {
	$file_path = unicamp_valid_file_path( $file_path );

	require_once $file_path;
}


/**
 * Admin notice waning minimum plugin version required.
 *
 * @param $plugin_name
 * @param $plugin_version
 */
function unicamp_notice_required_plugin_version( $plugin_name, $plugin_version ) {
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}

	$message = sprintf(
		esc_html__( '%1$s requires %2$s plugin version %3$s or greater!', 'unicamp' ),
		'<strong>' . UNICAMP_THEME_NAME . '</strong>',
		'<strong>' . $plugin_name . '</strong>',
		$plugin_version
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

/**
 * @param null $user_id
 * @param int  $width
 * @param int  $height
 *
 * Generate text to avatar
 *
 * Rewrite plugin function.
 *
 * @see   \TUTOR\Utils::get_tutor_avatar()
 *
 * @return string
 */
function unicamp_get_avatar( $user_id = null, $width = 150, $height = false ) {
	if ( ! $user_id ) {
		return '';
	}

	if ( ! $height ) {
		$height = $width;
	}

	// Priority use Tutor profile photo.
	if ( function_exists( 'tutor_utils' ) ) {
		$user = tutor_utils()->get_tutor_user( $user_id );

		if ( $user && $user->tutor_profile_photo ) {
			return Unicamp_Image::get_attachment_by_id( [
				'id'        => $user->tutor_profile_photo,
				'size'      => 'custom',
				'width'     => $width,
				'height'    => $height,
				'img_attrs' => [
					'class' => 'tutor-image-avatar',
				],
			] );
		}
	}

	return get_avatar( $user_id, $width );
}

/**
 * Check if has elementor template
 *
 * @param $location
 *
 * @return bool
 *
 * @since 1.3.0
 */
function unicamp_has_elementor_template( $location ) {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
		return true;
	}

	return false;
}
