<?php
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class: Course Layered Nav Base
 *
 * @version  1.0
 * @extends  WP_Widget
 */
if ( ! class_exists( 'Unicamp_Course_Layered_Nav_Base' ) ) {
	abstract class Unicamp_Course_Layered_Nav_Base extends Unicamp_WP_Widget_Base {
		/**
		 * Return the currently viewed taxonomy name.
		 *
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 *
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 *
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}

		/**
		 * @param        $term_ids
		 * @param        $taxonomy
		 * @param string $query_type
		 *
		 * @return array|int
		 */
		protected function get_filtered_term_counts( $term_ids, $taxonomy, $query_type = 'and' ) {
			global $wpdb;

			$term_ids = (array) $term_ids;

			$tax_query  = Unicamp_Course_Query::instance()->get_main_tax_query();
			$meta_query = Unicamp_Course_Query::instance()->get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query = new WP_Meta_Query( $meta_query );
			$tax_query  = new WP_Tax_Query( $tax_query );

			$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$author_query_sql = Unicamp_Course_Query::get_main_author_sql();
			$search_query_sql = Unicamp_Course_Query::get_search_title_sql();

			$sql           = array();
			$sql['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID )";
			$sql['from']   = "FROM {$wpdb->posts}";
			$sql['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql['where']  = "
			WHERE {$wpdb->posts}.post_type IN ( 'courses' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . $author_query_sql['where'] . $search_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			$sql = apply_filters( 'unicamp_get_filtered_term_course_counts_query', $sql );

			$sql = implode( ' ', $sql );

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}
	}
}
