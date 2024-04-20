<?php
/**
 * Plugin Name: Tutor LMS Pro
 * Plugin URI: https://www.themeum.com/product/tutor-lms/
 * Description: Power up Tutor LMS plugins by Tutor Pro
 * Author: Themeum
 * Version: 2.2.2
 * Author URI: http://themeum.com
 * Requires at least: 5.3
 * Tested up to: 6.2
 * Text Domain: tutor-pro
 * Domain Path: /languages/
 *
 * @package TutorPro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defined the tutor main file
 */
define( 'TUTOR_PRO_VERSION', '2.2.2' );
define( 'TUTOR_PRO_FILE', __FILE__ );

/**
 * Tutor Pro dependency on Tutor core
 *
 * Define Tutor core version on that Tutor Pro is dependent to run,
 * without require version pro will just show admin notice to install require core version.
 *
 * @since v2.0.0
 *
 * Require 2.1.10
 *
 * @since 2.1.10
 */
define( 'TUTOR_CORE_REQ_VERSION', '2.2.2' );
/**
 * Load tutor-pro text domain for translation
 */
add_action(
	'init',
	function () {
		load_plugin_textdomain( 'tutor-pro', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
);

if ( ! function_exists( 'tutor_pro' ) ) {
	/**
	 * Tutor Pro helper function
	 *
	 * @return object
	 */
	function tutor_pro() {
		if ( isset( $GLOBALS['tutor_pro_plugin_info'] ) ) {
			return $GLOBALS['tutor_pro_plugin_info'];
		}

		$path = plugin_dir_path( TUTOR_PRO_FILE );
		$info = array(
			'path'         => $path,
			'templates'    => trailingslashit( $path . 'templates' ),
			'url'          => plugin_dir_url( TUTOR_PRO_FILE ),
			'icon_dir'     => plugin_dir_url( TUTOR_PRO_FILE ) . 'assets/images/',
			'basename'     => plugin_basename( TUTOR_PRO_FILE ),
			'version'      => TUTOR_PRO_VERSION,
			'nonce_action' => 'tutor_pro_nonce_action',
			'nonce'        => '_wpnonce',
		);

		$GLOBALS['tutor_pro_plugin_info'] = (object) $info;
		return $GLOBALS['tutor_pro_plugin_info'];
	}
}

require 'classes/init.php';

$tutor_pro = new \TUTOR_PRO\init();
$tutor_pro->run();
