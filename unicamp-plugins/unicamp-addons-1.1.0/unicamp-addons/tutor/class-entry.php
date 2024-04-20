<?php

namespace Unicamp_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Entry {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'plugin_loaded', [ $this, 'require_files' ] );
	}

	public function require_files() {
		if ( ! $this->is_activated() ) {
			return;
		}

		require_once UNICAMP_ADDONS_DIR . '/tutor/course-term-views.php';
		require_once UNICAMP_ADDONS_DIR . '/tutor/course-category.php';
		require_once UNICAMP_ADDONS_DIR . '/tutor/course-language.php';
		require_once UNICAMP_ADDONS_DIR . '/tutor/course-location.php';
		require_once UNICAMP_ADDONS_DIR . '/tutor/course-visibility.php';
	}

	public function is_activated() {
		if ( function_exists( 'tutor' ) ) {
			return true;
		}

		return false;
	}

	public function get_menu_slug() {
		return 'tutor';
	}
}

Entry::instance()->initialize();
