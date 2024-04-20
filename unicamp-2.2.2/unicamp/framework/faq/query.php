<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_FAQ_Query' ) ) {
	class Unicamp_FAQ_Query {

		protected static $instance = null;

		/**
		 * Reference to the main faq query on the page.
		 *
		 * @var array
		 */
		private static $faq_query;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'pre_get_posts', [ $this, 'faq_filtering' ], 999 );
		}

		/**
		 * @param WP_Query $query
		 */
		public function faq_filtering( $query ) {
			if ( ! $query->is_main_query() || ! Unicamp_FAQ::instance()->is_archive() || is_admin() ) {
				return;
			}

			/**
			 * Change number post per page of main query.
			 */
			$number = Unicamp::setting( 'faq_archive_number_item', 6 );
			$query->set( 'posts_per_page', $number );

			self::$faq_query = $query;

			do_action( 'unicamp_faq_query', $query, $this );
		}

		/**
		 * Get the main query which product queries ran against.
		 *
		 * @return array
		 */
		public static function get_main_query() {
			return self::$faq_query;
		}
	}
}
