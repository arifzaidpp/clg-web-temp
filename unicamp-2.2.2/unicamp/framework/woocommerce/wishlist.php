<?php

namespace Unicamp\Woo;

defined( 'ABSPATH' ) || exit;

class Wishlist {
	protected static $instance = null;

	const RECOMMEND_PLUGIN_VERSION = '2.7.1';

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

		if ( defined( 'WOOSW_VERSION' ) && version_compare( WOOSW_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
		}

		// Hide default button.
		add_filter( 'woosw_button_position_archive', '__return_zero_string' );
		add_filter( 'woosw_button_position_single', '__return_zero_string' );
	}

	public function is_activate() {
		return class_exists( 'WPCleverWoosw' );
	}

	public function admin_notice_minimum_plugin_version() {
		unicamp_notice_required_plugin_version( 'WPC Smart Compare for WooCommerce', self::RECOMMEND_PLUGIN_VERSION );
	}
}

Wishlist::instance()->initialize();
