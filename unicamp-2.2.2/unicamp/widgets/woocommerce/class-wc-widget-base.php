<?php
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class: Woocommerce Widget Base
 *
 * @version  1.0
 * @extends  WP_Widget
 */
if ( ! class_exists( 'Unicamp_WC_Widget_Base' ) ) {
	abstract class Unicamp_WC_Widget_Base extends Unicamp_WP_Widget_Base {

		/**
		 * Get current page URL with various filtering props supported by WC.
		 *
		 * @return string
		 * @since  3.3.0
		 */
		protected function get_current_page_url() {
			$link = Unicamp_Woo::instance()->get_shop_base_url();

			// Min/Max.
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
			}

			// Order by.
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg.
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

				// Prevent post type and page id when pretty permalinks are disabled.
				if ( is_shop() ) {
					$link = remove_query_arg( 'page_id', $link );
				}
			}

			// Min Rating Arg.
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
			}

			// All current filters.
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
				foreach ( $_chosen_attributes as $name => $data ) {
					$filter_name = wc_attribute_taxonomy_slug( $name );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' === $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

			return apply_filters( 'woocommerce_widget_get_current_page_url', $link, $this );
		}

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
	}
}
