<?php

/**
 * Plugin Name: Tutor Calendar
 * Plugin URI: https://www.themeum.com/product/tutor-certificate
 * Description: Users will get web push notification 
 * Author: Themeum
 * Version: 1.0.0
 * Author URI: http://themeum.com
 * Requires at least: 4.5
 * Tested up to: 5.8
 * Text Domain: tutor-calendar
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit;
}
/**
 * Defined the tutor main file
 */
define('TUTOR_C_VERSION', '1.0.0');
define('TUTOR_C_FILE', __FILE__);

/**
 * Showing config for addons central lists
 */
add_filter('tutor_addons_lists_config', 'tutor_pro_calendar_config');
function tutor_pro_calendar_config($config){
	$newConfig = array(
		'name'          => __('Calendar', 'tutor-pro'),
		'description'   => __('Allow students to see everything in a calendar view.', 'tutor-pro'),
	);

	$basicConfig = (array) tutor_pro_calendar();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[plugin_basename( TUTOR_C_FILE )] = $newConfig;
	return $config;
}

if ( ! function_exists('tutor_pro_calendar')) {
	function tutor_pro_calendar() {
		$info = array(
			'path'              => plugin_dir_path( TUTOR_C_FILE ),
			'url'               => plugin_dir_url( TUTOR_C_FILE ),
			'assets'            => plugin_dir_url( TUTOR_C_FILE.'assets/' ),
			'basename'          => plugin_basename( TUTOR_C_FILE ),
			'version'           => TUTOR_C_VERSION,
			'nonce_action'      => 'tutor_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}

include 'classes/Init.php';
$tutor_calendar = new TUTOR_PRO_C\Init();
$tutor_calendar->run(); //Boom