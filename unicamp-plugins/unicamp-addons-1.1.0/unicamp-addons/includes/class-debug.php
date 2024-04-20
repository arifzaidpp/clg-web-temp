<?php

namespace Unicamp_Addons;

defined( 'ABSPATH' ) || exit;

class Debug {
	/**
	 * @param mixed $var Anything to output in debug bar.
	 */
	public static function d( $var ) {
		if ( ! function_exists( 'kint_debug_ob' ) || ! class_exists( 'Debug_Bar' ) ) {
			return;
		}

		ob_start( 'kint_debug_ob' );
		d( $var );
		ob_end_flush();
	}

	/**
	 * @param mixed $log Anything to write to log.
	 *
	 * Make sure WP_DEBUG_LOG = true.
	 */
	public static function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}

	/**
	 * Get all filters apply to this hook.
	 *
	 * @param string $hook Name of filter, for eg: the_content
	 *
	 * @return mixed
	 */
	public static function get_filters_for( $hook = '' ) {
		global $wp_filter;

		if ( empty( $hook ) || ! isset( $wp_filter[ $hook ] ) ) {
			return false;
		}

		/**
		 * @var WP_Hook $wp_filter [ $hook ]
		 */
		self::d( $wp_filter[ $hook ]->callbacks );
	}
}
