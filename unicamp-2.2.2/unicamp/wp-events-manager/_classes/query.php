<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event_Query' ) ) {
	class Unicamp_Event_Query {

		protected static $instance = null;

		/**
		 * Reference to the main course query on the page.
		 *
		 * @var array
		 */
		private static $event_query;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'pre_get_posts', [ $this, 'event_filtering' ], 999 );
		}

		/**
		 * @param WP_Query $query
		 */
		public function event_filtering( $query ) {
			if ( ! $query->is_main_query() || ! Unicamp_Event::instance()->is_archive() || is_admin() ) {
				return;
			}

			/**
			 * Change number post per page of main query.
			 */
			$number = Unicamp::setting( 'event_archive_number_item', 12 );

			$layout_preset = isset( $_GET['event_archive_preset'] ) ? Unicamp_Helper::data_clean( $_GET['event_archive_preset'] ) : false;

			// Hard set post per page. because override preset settings run after init hook.
			if ( $layout_preset ) {
				switch ( $layout_preset ) {
					case 'grid-02':
					case 'grid-03':
						$number = 9;
						break;
					case 'list-01':
					case 'list-02':
						$number = 6;
						break;
				}
			}

			$query->set( 'posts_per_page', $number );

			$search_term = isset( $_GET['filter_name'] ) ? Unicamp_Helper::data_clean( $_GET['filter_name'] ) : '';
			if ( ! empty( $search_term ) ) {
				$query->set( 'post_title_like', $search_term );
			}

			// Query vars that affect posts shown.
			$query->set( 'meta_query', $this->get_meta_query( $query->get( 'meta_query' ), true ) );
			$query->set( 'tax_query', $this->get_tax_query( $query->get( 'tax_query' ), true ) );

			self::$event_query = $query;

			do_action( 'unicamp_event_query', $query, $this );
		}

		/**
		 * Get the main query which product queries ran against.
		 *
		 * @return array
		 */
		public static function get_main_query() {
			return self::$event_query;
		}

		/**
		 * Appends meta queries to an array.
		 *
		 * @param  array $meta_query Meta query.
		 * @param  bool  $main_query If is main query.
		 *
		 * @return array
		 */
		public function get_meta_query( $meta_query = array(), $main_query = false ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}

			// Filter by status.
			$type = ! empty( $_GET['filter_type'] ) ? Unicamp_Helper::data_clean( $_GET['filter_type'] ) : '';
			if ( ! empty( $type ) ) {
				switch ( $type ) {
					case 'happening':
					case 'upcoming':
					case 'expired':
						$meta_query[] = array(
							'key'     => Unicamp_Event::POST_META_STATUS,
							'value'   => $type,
							'compare' => '=',
						);
						break;
				}
			}

			$start_date = ! empty( $_GET['filter_start_date'] ) ? Unicamp_Helper::data_clean( $_GET['filter_start_date'] ) : '';
			if ( ! empty( $start_date ) ) {
				// Convert date to SQL format to compare.
				$start_date = date( 'Y-m-d', strtotime( $start_date ) );

				$meta_query[] = array(
					'key'     => 'tp_event_date_start',
					'value'   => $start_date,
					'compare' => '>=',
				);
			}

			$location = ! empty( $_GET['filter_location'] ) ? Unicamp_Helper::data_clean( $_GET['filter_location'] ) : '';
			if ( ! empty( $location ) ) {
				$meta_query[] = array(
					'key'     => Unicamp_Event::POST_META_SHORT_LOCATION,
					'value'   => $location,
					'compare' => 'LIKE',
				);
			}

			return apply_filters( 'unicamp_event_query_meta_query', $meta_query, $this );
		}

		/**
		 * Appends tax queries to an array.
		 *
		 * @param  array $tax_query  Tax query.
		 * @param  bool  $main_query If is main query.
		 *
		 * @return array
		 */
		public function get_tax_query( $tax_query = array(), $main_query = false ) {
			if ( ! is_array( $tax_query ) ) {
				$tax_query = array(
					'relation' => 'AND',
				);
			}

			$category_taxonomy = Unicamp_Event::instance()->get_tax_category();
			if ( taxonomy_exists( $category_taxonomy ) && ! empty( $_GET['filter_category'] ) ) {
				$selected_cats = explode( ',', Unicamp_Helper::data_clean( $_GET['filter_category'] ) );

				$tax_query[] = array(
					'taxonomy' => $category_taxonomy,
					'field'    => 'term_id',
					'terms'    => $selected_cats,
				);
			}

			return $tax_query;
		}

		/**
		 * Get the tax query which was used by the main query.
		 *
		 * @return array
		 */
		public static function get_main_tax_query() {
			$tax_query = isset( self::$event_query->tax_query, self::$event_query->tax_query->queries ) ? self::$event_query->tax_query->queries : array();

			return $tax_query;
		}

		/**
		 * Get the meta query which was used by the main query.
		 *
		 * @return array
		 */
		public static function get_main_meta_query() {
			$args       = self::$event_query->query_vars;
			$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

			return $meta_query;
		}

		public static function get_main_author_query() {
			$args = self::$event_query->query_vars;

			$author_query = ! empty( $args['author__in'] ) ? $args['author__in'] : array();

			return $author_query;
		}

		public static function get_main_author_sql() {
			global $wpdb;

			$author_ids = self::get_main_author_query();
			$sql        = array(
				'where' => '',
			);

			if ( ! empty( $author_ids ) ) {
				$sql['where'] = ' AND ' . $wpdb->posts . '.post_author IN (' . implode( ',', $author_ids ) . ')';
			}

			return $sql;
		}

		/**
		 * Helper function to get sql string where by author.
		 *
		 * @param  array $ids
		 *
		 * @return array SQL where by author.
		 */
		public static function get_author_sql( $ids ) {
			global $wpdb;

			$sql = array(
				'where' => '',
			);

			if ( ! empty( $ids ) ) {
				$sql['where'] = ' AND ' . $wpdb->posts . '.post_author IN (' . implode( ',', $ids ) . ')';
			}

			return $sql;
		}
	}

	Unicamp_Event_Query::instance()->initialize();
}
