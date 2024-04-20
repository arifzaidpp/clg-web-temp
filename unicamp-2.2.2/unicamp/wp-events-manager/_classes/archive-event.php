<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Archive_Event' ) ) {
	class Unicamp_Archive_Event extends Unicamp_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'unicamp_title_bar_heading_text', [ $this, 'archive_title_bar_heading' ] );

			add_filter( 'body_class', [ $this, 'body_class' ] );
		}

		public function archive_title_bar_heading( $text ) {
			if ( $this->is_archive() ) {
				$text = Unicamp::setting( 'event_archive_title_bar_title' );
			}

			return $text;
		}

		public function body_class( $classes ) {
			if ( $this->is_archive() ) {
				$classes[] = 'archive-event';

				$site_background = Unicamp::setting( 'event_archive_body_background' );
				if ( ! empty( $site_background ) ) {
					$classes[] = 'site-background-' . $site_background;
				}

				$site_layout = Unicamp::setting( 'event_archive_site_layout' );
				if ( 'small' === $site_layout ) {
					$classes[] = 'site-content-small';
				}

				$style     = Unicamp::setting( 'event_archive_style' );
				$classes[] = 'archive-event-style-' . $style;

				$filtering_bar_on = Unicamp::setting( 'event_archive_filtering' );

				if ( '1' === $filtering_bar_on ) {
					$classes[] = 'page-has-filtering-bar';
				}
			}

			return $classes;
		}
	}

	Unicamp_Archive_Event::instance()->initialize();
}
