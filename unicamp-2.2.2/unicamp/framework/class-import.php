<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initial OneClick import for this theme
 */
if ( ! class_exists( 'Unicamp_Import' ) ) {
	class Unicamp_Import {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'insight_core_import_demos', [ $this, 'import_demos' ] );
			add_filter( 'insight_core_import_generate_thumb', '__return_false' );
			add_filter( 'insight_core_import_delete_exist_posts', '__return_true' );

			add_action( 'insight_core_importer_dispatch_after', [ $this, 'update_links' ] );
		}

		public function import_demos() {
			$import_img_url = UNICAMP_THEME_URI . '/assets/import';

			return array(
				'main' => array(
					'screenshot' => UNICAMP_THEME_URI . '/screenshot.jpg',
					'name'       => esc_html__( 'Main', 'unicamp' ),
					'url'        => 'https://api.thememove.com/download/unicamp-insightcore-main-1.0.0.zip',
				),
			);
		}

		/**
		 * Fix links in Elementor after import
		 *
		 * @param $importer
		 */
		public function update_links( $importer ) {
			if ( ! isset( $importer->demo ) ) {
				return;
			}

			if ( 'main' === $importer->demo ) {
				// First replace WP upload dir.
				$old_upload_dir = 'https://unicamp.thememove.com/main/wp-content/uploads/sites/2';

				$wp_upload_dir  = wp_upload_dir();
				$new_upload_dir = $wp_upload_dir['baseurl'];

				$result = $this->replace_url( $old_upload_dir, $new_upload_dir );

				// Finally replace all other links.
				$from = 'https://unicamp.thememove.com/main';
				$to   = home_url();

				$result = $this->replace_url( $from, $to );
			}
		}

		public function replace_url( $from, $to ) {
			$is_valid_urls = ( filter_var( $from, FILTER_VALIDATE_URL ) && filter_var( $to, FILTER_VALIDATE_URL ) );
			if ( ! $is_valid_urls ) {
				return false;
			}

			global $wpdb;

			// @codingStandardsIgnoreStart cannot use `$wpdb->prepare` because it remove's the backslashes
			$rows_affected = $wpdb->query(
				"UPDATE {$wpdb->postmeta} " .
				"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
				"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" ); // meta_value LIKE '[%' are json formatted
			// @codingStandardsIgnoreEnd

			return $rows_affected;
		}
	}

	Unicamp_Import::instance()->initialize();
}
