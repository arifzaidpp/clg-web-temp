<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_FAQ_Template' ) ) {
	class Unicamp_FAQ_Template extends Unicamp_FAQ {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'template_include', [ $this, 'load_faq_archive_template' ], 99 );
			add_filter( 'template_include', [ $this, 'load_single_faq_template' ], 99 );
		}

		public function load_faq_archive_template( $template ) {
			global $wp_query;

			$post_type = get_query_var( 'post_type' );
			$group     = get_query_var( 'faq-group' );

			if ( ( $post_type === $this->get_post_type() || ! empty( $group ) ) && $wp_query->is_archive ) {
				$new_template = locate_template( [ 'faq/archive-faq.php' ] );

				if ( ! empty( $new_template ) ) {
					return $new_template;
				}
			}

			return $template;
		}

		public function load_single_faq_template( $template ) {
			global $wp_query;

			if ( $wp_query->is_single && ! empty( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] === $this->get_post_type() ) {
				$new_template = locate_template( [ 'faq/single-faq.php' ] );

				if ( ! empty( $new_template ) ) {
					return $new_template;
				}
			}

			return $template;
		}
	}
}
