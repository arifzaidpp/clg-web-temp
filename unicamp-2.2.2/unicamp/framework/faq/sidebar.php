<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_FAQ_Sidebar' ) ) {
	class Unicamp_FAQ_Sidebar extends Unicamp_FAQ {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Register widget areas.
			add_action( 'widgets_init', [ $this, 'register_sidebars' ] );

			// Different style for sidebar.
			add_filter( 'unicamp_page_sidebar_class', [ $this, 'sidebar_class' ] );
		}

		public function register_sidebars() {
			$default_args = Unicamp_Sidebar::instance()->get_default_sidebar_args();

			register_sidebar( array_merge( $default_args, [
				'id'          => 'faq_sidebar',
				'name'        => esc_html__( 'FAQs Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on FAQs pages.', 'unicamp' ),
			] ) );
		}

		public function sidebar_class( $class ) {
			if ( $this->is_archive() ) {
				$sidebar_style = Unicamp::setting( 'faq_archive_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			if ( $this->is_single() ) {
				$sidebar_style = Unicamp::setting( 'faq_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			return $class;
		}
	}
}
