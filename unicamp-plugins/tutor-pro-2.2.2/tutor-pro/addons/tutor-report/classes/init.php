<?php
namespace TUTOR_REPORT;

if ( ! defined( 'ABSPATH' ) )
	exit;

class init{
	public $version = TUTOR_REPORT_VERSION;
	public $path;
	public $url;
	public $basename;

	//Module
	public $report;
	public $analytics;
	public $course_analytics;
	public $export_analytics;

	public static $_instance = null;

	function __construct() {
		if ( ! function_exists('tutor')){
			return;
		}
		$addonConfig = tutor_utils()->get_addon_config(TUTOR_REPORT()->basename);
		$isEnable = (bool) tutor_utils()->avalue_dot('is_enable', $addonConfig);
		if ( ! $isEnable){
			return;
		}

		$this->path = plugin_dir_path(TUTOR_REPORT_FILE);
		$this->url = plugin_dir_url(TUTOR_REPORT_FILE);
		$this->basename = plugin_basename(TUTOR_REPORT_FILE);

		$this->load_TUTOR_REPORT();
	}

	public function load_TUTOR_REPORT(){
		/**
		 * Loading Autoloader
		 */

		spl_autoload_register(array($this, 'loader'));

		$this->report = new Report();
		/**
		 * Analytics class
		 * 
		 * @since 1.9.8
		 */
		$this->analytics = new Analytics();
		$this->course_analytics = new CourseAnalytics();
		$this->export_analytics = new ExportAnalytics();
	}

	/**
	 * @param $className
	 *
	 * Auto Load class and the files
	 */
	private function loader($className) {
		if ( ! class_exists($className)){
			$className = preg_replace(
				array('/([a-z])([A-Z])/', '/\\\/'),
				array('$1$2', DIRECTORY_SEPARATOR),
				$className
			);

			$className = str_replace('TUTOR_REPORT'.DIRECTORY_SEPARATOR, 'classes'.DIRECTORY_SEPARATOR, $className);
			$file_name = $this->path.$className.'.php';

			if (file_exists($file_name) && is_readable( $file_name ) ) {
				require_once $file_name;
			}
		}
	}


	//Run the TUTOR right now
	public function run(){
		register_activation_hook( TUTOR_REPORT_FILE, array( $this, 'tutor_activate' ) );
	}

	/**
	 * Do some task during plugin activation
	 */
	public function tutor_activate(){
		$version = get_option('TUTOR_REPORT_version');
		//Save Option
		if ( ! $version){
			update_option('TUTOR_REPORT_version', TUTOR_REPORT_VERSION);
		}
	}

	/**
	 * Single instance of tutor report
	 * 
	 * @since 1.9.8
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Self();
		}
		return self::$_instance;
	}

}