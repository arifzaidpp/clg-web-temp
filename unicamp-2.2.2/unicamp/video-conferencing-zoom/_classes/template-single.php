<?php

namespace Unicamp\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

class Template_Single extends Utils {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'unicamp_header_type', [ $this, 'set_header_type' ] );
		add_filter( 'unicamp_header_overlay', [ $this, 'set_header_overlay' ] );
		add_filter( 'unicamp_header_skin', [ $this, 'set_header_skin' ] );

		add_filter( 'unicamp_title_bar_type', [ $this, 'set_title_bar' ] );

		add_filter( 'unicamp_sidebar_1', [ $this, 'set_sidebar_1' ] );
		add_filter( 'unicamp_sidebar_2', [ $this, 'set_sidebar_2' ] );
		add_filter( 'unicamp_sidebar_position', [ $this, 'set_sidebar_position' ] );

		remove_action( 'vczoom_single_content_left', 'video_conference_zoom_featured_image', 10 );
		remove_action( 'vczoom_single_content_right', 'video_conference_zoom_countdown_timer', 10 );

		add_action( 'unicamp_single_zoom_meeting_hero_content_bottom', 'video_conference_zoom_featured_image', 10 );
		add_action( 'unicamp_single_zoom_meeting_hero_content_right', 'video_conference_zoom_countdown_timer', 10 );
	}

	public function set_header_type( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_single_header_type' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_header_overlay( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_single_header_overlay' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_header_skin( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_single_header_skin' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_title_bar( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_single_title_bar_layout', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_sidebar_1( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_page_sidebar_1', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_sidebar_2( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_page_sidebar_2', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_sidebar_position( $value ) {
		if ( $this->is_single() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_page_sidebar_position', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}
}

Template_Single::instance()->initialize();
