<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event_Sidebar' ) ) {
	class Unicamp_Event_Sidebar extends Unicamp_Event {

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

			// Custom sidebar width.
			add_filter( 'unicamp_one_sidebar_width', [ $this, 'one_sidebar_width' ] );

			// Custom sidebar offset.
			add_filter( 'unicamp_one_sidebar_offset', [ $this, 'one_sidebar_offset' ] );
		}

		public function register_sidebars() {
			$default_args = Unicamp_Sidebar::instance()->get_default_sidebar_args();

			register_sidebar( array_merge( $default_args, [
				'id'          => 'event_sidebar',
				'name'        => esc_html__( 'Event Listing Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets displays on event listing pages.', 'unicamp' ),
			] ) );
		}

		public function sidebar_class( $class ) {
			if ( $this->is_archive() ) {
				$sidebar_style = Unicamp::setting( 'event_archive_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			return $class;
		}

		public function one_sidebar_width( $width ) {
			if ( $this->is_archive() ) {
				$new_width = Unicamp::setting( 'event_archive_single_sidebar_width' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_width ) && '' !== $new_width ) {
				return $new_width;
			}

			return $width;
		}

		public function one_sidebar_offset( $offset ) {
			if ( $this->is_archive() ) {
				$new_offset = Unicamp::setting( 'event_archive_single_sidebar_offset' );
			}

			// Use isset instead of empty avoid skip value 0.
			if ( isset( $new_offset ) && '' !== $new_offset ) {
				return $new_offset;
			}

			return $offset;
		}
	}

	Unicamp_Event_Sidebar::instance()->initialize();
}
