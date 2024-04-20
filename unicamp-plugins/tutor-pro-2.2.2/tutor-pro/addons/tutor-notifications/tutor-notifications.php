<?php
/**
 * Plugin Name: Tutor Notifications
 * Plugin URI: https://www.themeum.com/product/tutor-notifications
 * Description: Users will get sitewide notifications related to different tutor actions.
 * Author: Themeum
 * Version: 1.0.0
 * Author URI: http://themeum.com
 * Requires at least: 4.5
 * Tested up to: 5.8
 * Text Domain: tutor-notifications
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

/**
 * Defined tutor notifications main file
 */
define( 'TUTOR_NOTIFICATIONS_VERSION', '1.0.0' );
define( 'TUTOR_NOTIFICATIONS_FILE', __FILE__ );

/**
 * Showing config for addons central lists
 */
add_filter( 'tutor_addons_lists_config', 'tutor_notifications_config' );

/**
 * Tutor notifications config
 * 
 * @param  array $config
 * 
 * @return array $config
 */
function tutor_notifications_config( $config ) {
	$newConfig = array(
		'name'        => __( 'Notifications', 'tutor-pro' ),
		'description' => __( 'Get notifications on frontend dashboard for specified tutor events.', 'tutor-pro' ),
	);

	$basicConfig = (array) tutor_notifications();
	$newConfig   = array_merge( $newConfig, $basicConfig );

	$config[ plugin_basename( TUTOR_NOTIFICATIONS_FILE ) ] = $newConfig;
	return $config;
}

/**
 * Tutor notifications
 * 
 * @return object $info
 */
if ( ! function_exists( 'tutor_notifications' ) ) {
	function tutor_notifications() {
		$info = array(
			'path'         => plugin_dir_path( TUTOR_NOTIFICATIONS_FILE ),
			'url'          => plugin_dir_url( TUTOR_NOTIFICATIONS_FILE ),
			'basename'     => plugin_basename( TUTOR_NOTIFICATIONS_FILE ),
			'version'      => TUTOR_NOTIFICATIONS_VERSION,
			'nonce_action' => 'tutor_nonce_action',
			'nonce'        => '_wpnonce',
		);
		return (object) $info;
	}
}

// Include the init class for initialization.
include 'classes/Init.php';
$tutor_notifications = new TUTOR_NOTIFICATIONS\Init();
$tutor_notifications->run(); //Boom, lift off.