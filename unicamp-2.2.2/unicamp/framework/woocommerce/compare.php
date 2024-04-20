<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Compare {
	protected static $instance = null;

	const RECOMMEND_PLUGIN_VERSION = '5.1.7';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		if ( defined( 'WOOSCP_VERSION' ) // Constant in old version
		     || ( defined( 'WOOSC_VERSION' ) && version_compare( WOOSC_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) )
		) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
		}

		// Hide default button.
		add_filter( 'woosc_button_position_archive', '__return_false' );
		add_filter( 'woosc_button_position_single', '__return_false' );
	}

	public function is_activate() {
		return class_exists( 'WPCleverWoosc' );
	}

	public function admin_notice_minimum_plugin_version() {
		unicamp_notice_required_plugin_version( 'WPC Smart Compare for WooCommerce', self::RECOMMEND_PLUGIN_VERSION );
	}
}

Compare::instance()->initialize();
