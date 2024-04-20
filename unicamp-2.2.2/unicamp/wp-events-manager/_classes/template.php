<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event_Template' ) ) {
	class Unicamp_Event_Template extends Unicamp_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'template_include', [ $this, 'load_event_archive_template' ], 99 );
		}

		/**
		 * Fixed archive event load archive.php instead of archive-event.php
		 * when has some query strings...
		 * Also missing template for Tag.
		 *
		 * @param string $template Path to template file.
		 *
		 * @return string Path to template file.
		 */
		public function load_event_archive_template( $template ) {
			global $wp_query;

			// Do nothing if this page is not archive page.
			if ( ! $wp_query->is_archive ) {
				return $template;
			}

			$taxonomies   = get_object_taxonomies( $this->get_event_type() );
			$is_event_tax = false;

			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					if ( ! empty( get_query_var( $taxonomy ) ) ) {
						$is_event_tax = true;
						break;
					}
				}
			}

			$post_type = get_query_var( 'post_type' );

			if ( $post_type === $this->get_event_type() || $is_event_tax ) {
				$file   = 'archive-event.php';
				$find[] = $file;
				$find[] = wpems_template_path() . '/' . $file;

				$template = locate_template( array_unique( $find ) );
				if ( ! $template ) {
					$template = untrailingslashit( WPEMS_PATH ) . '/templates/' . $file;
				}

				return $template;
			}

			return $template;
		}
	}

	Unicamp_Event_Template::instance()->initialize();
}
