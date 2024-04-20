<?php
namespace TUTOR_ENROLLMENTS;

if ( ! defined( 'ABSPATH' ) )
	exit;

class init{
	public $version = TUTOR_ENROLLMENTS_VERSION;
	public $path;
	public $url;
	public $basename;

	//Module
	private $enrollments;
	public  $enrollment_list;

	function __construct() {
		if ( ! function_exists('tutor')){
			return;
		}

		$addonConfig = tutor_utils()->get_addon_config(TUTOR_ENROLLMENTS()->basename);
		$isEnable = (bool) tutor_utils()->array_get('is_enable', $addonConfig);
		if ( ! $isEnable){
			return;
		}

		$this->path = plugin_dir_path(TUTOR_ENROLLMENTS_FILE);
		$this->url = plugin_dir_url(TUTOR_ENROLLMENTS_FILE);
		$this->basename = plugin_basename(TUTOR_ENROLLMENTS_FILE);

		add_action( 'admin_enqueue_scripts', array($this, 'register_scritps') );

		$this->load_TUTOR_ENROLLMENTS();
	}

	// Register scripts 
	public function register_scritps() {
		if(is_admin() && isset($_GET['page']) && $_GET['page']=='enrollments') {
			wp_enqueue_script( 'enrollment-js-script', TUTOR_ENROLLMENTS()->url . 'assets/js/enroll.js', array('jquery', 'wp-i18n'), TUTOR_PRO_VERSION, true );
			wp_enqueue_style( 'enrollment-css-script', TUTOR_ENROLLMENTS()->url . 'assets/css/enroll.css', array(), TUTOR_PRO_VERSION );
		}
	}

	public function load_TUTOR_ENROLLMENTS(){
		/**
		 * Loading Autoloader
		 */

		spl_autoload_register(array($this, 'loader'));
		$this->enrollments = new Enrollments();
		$this->enrollment_list = new Enrollments_List();
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

			$className = str_replace('TUTOR_ENROLLMENTS'.DIRECTORY_SEPARATOR, 'classes'.DIRECTORY_SEPARATOR, $className);
			$file_name = $this->path.$className.'.php';

			if (file_exists($file_name)  ) {
				require_once $file_name;
			}
		}
	}

	//Run the TUTOR right now
	public function run(){
		//
	}

}