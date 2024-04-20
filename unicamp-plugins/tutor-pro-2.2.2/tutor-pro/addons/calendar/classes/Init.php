<?php
/**
 * Initialize Calendar addon
 * 
 * @since 1.9.10
 */
namespace TUTOR_PRO_C;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Init {
	public $version = TUTOR_C_VERSION;
	public $path;
	public $url;
	public $basename;

	/**
     * Calendar class for handling calendar issues
     * 
     * @since 1.9.10
     */
	public $calendar;

	function __construct() {
		if ( ! function_exists('tutor')){
			return;
		}
		$addonConfig = tutor_utils()->get_addon_config(tutor_pro_calendar()->basename);
		$isEnable = (bool) tutor_utils()->avalue_dot('is_enable', $addonConfig);
		if ( ! $isEnable){
			return;
		}

		$this->path = plugin_dir_path(TUTOR_C_FILE);
		$this->url = plugin_dir_url(TUTOR_C_FILE);
		$this->basename = plugin_basename(TUTOR_C_FILE);

		$this->load_tutor_calendar();
	}

	public function load_tutor_calendar(){
		/**
		 * Loading Autoloader
		 */

		spl_autoload_register(array($this, 'loader'));

		$this->calendar = new Tutor_Calendar();
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

			$className = str_replace('TUTOR_PRO_C'.DIRECTORY_SEPARATOR, 'classes'.DIRECTORY_SEPARATOR, $className);
			$file_name = $this->path.$className.'.php';

			if (file_exists($file_name) && is_readable( $file_name ) ) {
				require_once $file_name;
			}
		}
	}


	//Run the TUTOR right now
	public function run(){
		register_activation_hook( TUTOR_C_FILE, array( $this, 'tutor_activate' ) );
		flush_rewrite_rules(true);
	}

	/**
	 * Do some task during plugin activation
	 */
	public function tutor_activate(){
		$version = get_option('TUTOR_C_VERSION');
		//Save Option
		if ( ! $version){
			update_option('TUTOR_C_VERSION', TUTOR_C_VERSION);
		}
	}
}