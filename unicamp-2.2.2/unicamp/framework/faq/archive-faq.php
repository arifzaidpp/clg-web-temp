<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_FAQ_Archive' ) ) {
	class Unicamp_FAQ_Archive extends Unicamp_FAQ {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'body_class', [ $this, 'body_classes' ] );

			add_filter( 'unicamp_title_bar_type', [ $this, 'change_title_bar' ] );
		}

		public function body_classes( $classes ) {
			if ( $this->is_archive() ) {
				$classes[] = 'archive-faq';
			}

			return $classes;
		}

		public function change_title_bar( $type ) {
			if ( $this->is_archive() ) {
				return '08';
			}

			return $type;
		}
	}
}
