<?php

namespace Unicamp_Addons\Event;

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

		require_once UNICAMP_ADDONS_DIR . '/wp-events-manager/event-speakers.php';
	}

	public function is_activated() {
		if ( class_exists( 'WPEMS' ) ) {
			return true;
		}

		return false;
	}
}

Entry::instance()->initialize();
