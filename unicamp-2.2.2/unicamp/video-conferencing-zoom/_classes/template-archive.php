<?php

namespace Unicamp\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

class Template_Archive extends Utils {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'pre_get_posts', [ $this, 'change_post_per_page' ] );

		/**
		 * Fixed Category page not include template.
		 * Priority 100 to run after plugin's hook (99).
		 */
		add_filter( 'template_include', [ $this, 'load_archive_template' ], 100 );

		add_filter( 'body_class', [ $this, 'body_class' ] );

		add_filter( 'unicamp_header_type', [ $this, 'set_header_type' ] );
		add_filter( 'unicamp_header_overlay', [ $this, 'set_header_overlay' ] );
		add_filter( 'unicamp_header_skin', [ $this, 'set_header_skin' ] );

		add_filter( 'unicamp_title_bar_type', [ $this, 'set_title_bar' ] );
		add_filter( 'unicamp_title_bar_heading_text', [ $this, 'set_title_bar_heading' ] );

		add_filter( 'unicamp_sidebar_1', [ $this, 'set_sidebar_1' ] );
		add_filter( 'unicamp_sidebar_2', [ $this, 'set_sidebar_2' ] );
		add_filter( 'unicamp_sidebar_position', [ $this, 'set_sidebar_position' ] );
	}

	/**
	 * Change number post per page of main query.
	 *
	 * @param \WP_Query $query Query instance.
	 */
	public function change_post_per_page( $query ) {
		if ( $query->is_main_query() && $this->is_archive() && ! is_admin() ) {
			$number = \Unicamp::setting( 'zoom_meeting_archive_number_item', 9 );

			$query->set( 'posts_per_page', $number );
		}
	}

	/**
	 * Archive page template
	 *
	 * @param $template
	 *
	 * @return bool|string
	 * @return bool|string|void
	 * @since  3.0.0
	 *
	 * @author Deepen
	 */
	public function load_archive_template( $template ) {
		global $wp_query;

		if ( ! function_exists( 'vczapi_get_template' ) ) {
			return $template;
		}

		$post_type = get_query_var( 'post_type' );
		$category  = get_query_var( $this->get_tax_category() );

		if ( ( $post_type === $this->get_post_type() || ! empty( $category ) ) && $wp_query->is_archive ) {
			$new_template = vczapi_get_template( 'archive-meetings.php' );

			if ( ! empty( $new_template ) ) {
				return $new_template;
			}
		}

		return $template;
	}

	public function body_class( $classes ) {
		if ( $this->is_archive() ) {
			$classes[] = 'archive-zoom-meetings';
		}

		return $classes;
	}

	public function set_header_type( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_header_type' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_header_overlay( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_header_overlay' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_header_skin( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_header_skin' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_title_bar( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_title_bar_layout', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_title_bar_heading( $text ) {
		if ( $this->is_archive() ) {
			$text = esc_html__( 'Zoom Meetings and Webinars', 'unicamp' );
		}

		return $text;
	}

	public function set_sidebar_1( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_page_sidebar_1', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_sidebar_2( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_page_sidebar_2', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}

	public function set_sidebar_position( $value ) {
		if ( $this->is_archive() ) {
			$new_value = \Unicamp::setting( 'zoom_meeting_archive_page_sidebar_position', '' );

			if ( '' !== $new_value ) {
				return $new_value;
			}
		}

		return $value;
	}
}

Template_Archive::instance()->initialize();
