<?php
/**
 * Plugin Name: Tutor Google Meet
 * Description: Google meet integration for Tutor Courses
 * Author: Themeum
 * Version: 1.0.0
 * Author URI: http://themeum.com
 * Requires at least: 5.3
 * Tested up to: 6.0.1
 *
 * @package TutorPro\GoogleMeet
 */

namespace TutorPro\GoogleMeet;

use TutorPro\GoogleMeet\Admin\Admin;
use TutorPro\GoogleMeet\Assets\Enqueue;
use TutorPro\GoogleMeet\CustomPosts\InitPostTypes;
use TutorPro\GoogleMeet\Frontend\Frontend;
use TutorPro\GoogleMeet\GoogleEvent\Events;
use TutorPro\GoogleMeet\GoogleEvent\GoogleEvent;
use TutorPro\GoogleMeet\MetaBox\MetaBox;
use TutorPro\GoogleMeet\Options\Options;
use TutorPro\GoogleMeet\Settings\Settings;
use TutorPro\GoogleMeet\TopicsEvent\TopicsEvent;
use TutorPro\GoogleMeet\Validator\Validator;

if ( ! class_exists( 'GoogleMeet' ) ) {

	/**
	 * PluginStarter main class that trigger the plugin
	 */
	final class GoogleMeet {

		/**
		 * Plugin meta data
		 *
		 * @since v1.0.0
		 *
		 * @var $plugin_data
		 */
		private static $meta_data = array();

		/**
		 * Plugin instance
		 *
		 * @since v1.0.0
		 *
		 * @var $instance
		 */
		public static $instance = null;

		/**
		 * Register hooks and load dependent files
		 *
		 * @since v1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			require_once tutor_pro()->path . '/vendor/autoload.php';
			// register_activation_hook( __FILE__, array( __CLASS__, 'register_activation' ) );
			// register_deactivation_hook( __FILE__, array( __CLASS__, 'register_deactivation' ) );
			// add_action( 'init', array( __CLASS__, 'load_textdomain' ) );

			$this->load_packages();
		}

		/**
		 * Plugin meta data
		 *
		 * @since v1.0.0
		 *
		 * @return array  contains plugin meta data
		 */
		public static function meta_data(): array {
			self::$meta_data['url']       = plugin_dir_url( __FILE__ );
			self::$meta_data['path']      = plugin_dir_path( __FILE__ );
			self::$meta_data['basename']  = plugin_basename( __FILE__ );
			self::$meta_data['templates'] = trailingslashit( plugin_dir_path( __FILE__ ) . 'templates' );
			self::$meta_data['views']     = trailingslashit( plugin_dir_path( __FILE__ ) . 'views' );
			self::$meta_data['assets']    = trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' );

			// set ENV DEV | PROD.
			self::$meta_data['env'] = 'DEV';
			return self::$meta_data;
		}

		/**
		 * Create and return instance of this plugin
		 *
		 * @return self  instance of plugin
		 */
		public static function instance() {
			// If tutor is not active then return.
			if ( ! function_exists( 'tutor' ) ) {
				return;
			}

			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Load packages
		 *
		 * @return void
		 */
		public function load_packages() {
			new Init();
			if ( Validator::is_addon_enabled() ) {
				new Settings();
				new Admin();
				new InitPostTypes();
				new GoogleEvent();
				new MetaBox();
				new Enqueue();
				new Events();
				new TopicsEvent();
				new Options();
				new Frontend();
			}
		}
	}
	// trigger.
	GoogleMeet::instance();
}
