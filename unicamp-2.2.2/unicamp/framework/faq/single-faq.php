<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_FAQ_Single' ) ) {
	class Unicamp_FAQ_Single extends Unicamp_FAQ {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'unicamp_title_bar_type', [ $this, 'change_title_bar' ] );
		}

		public function change_title_bar( $type ) {
			if ( $this->is_single() ) {
				return '08';
			}

			return $type;
		}
	}
}
