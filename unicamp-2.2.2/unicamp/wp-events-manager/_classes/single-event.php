<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Single_Event' ) ) {
	class Unicamp_Single_Event extends Unicamp_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'unicamp_title_bar_type', [ $this, 'change_title_bar' ] );

			// Add gutenberg editor for single page.
			add_filter( 'register_post_type_args', [ $this, 'add_gutenberg_support' ], 10, 2 );
			add_filter( 'register_taxonomy_args', [ $this, 'add_gutenberg_support_for_taxonomy' ], 10, 2 );

			// Change map marker
			add_filter( 'tp-event-map-marker', [ $this, 'change_map_marker' ] );

			add_filter( 'body_class', [ $this, 'body_class' ] );
		}

		public function body_class( $classes ) {
			if ( $this->is_single() ) {
				$layout    = Unicamp::setting( 'single_event_style' );
				$classes[] = 'single-event-style-' . $layout;
			}

			return $classes;
		}

		public function change_title_bar( $type ) {
			if ( $this->is_single() ) {
				$style = Unicamp::setting( 'single_event_style' );
				if ( '02' === $style ) {
					return '07';
				} else {
					return Unicamp::TITLE_BAR_MINIMAL_TYPE;
				}
			}

			return $type;
		}

		public function add_gutenberg_support( $args, $post_type ) {
			if ( self::POST_TYPE === $post_type ) {
				$args['show_in_rest'] = true;
			}

			return $args;
		}

		public function add_gutenberg_support_for_taxonomy( $args, $taxonomy ) {
			if ( in_array( $taxonomy, [
				self::TAXONOMY_CATEGORY,
				self::TAXONOMY_TAGS,
			] ) ) {
				$args['show_in_rest'] = true;
			}

			return $args;
		}

		public function change_map_marker() {
			return UNICAMP_THEME_IMAGE_URI . '/map-marker.png';
		}
	}

	Unicamp_Single_Event::instance()->initialize();
}
