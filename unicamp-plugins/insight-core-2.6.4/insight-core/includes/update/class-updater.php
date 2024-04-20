<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'InsightCore_Updater' ) ) {
	class InsightCore_Updater {

		public function __construct() {
			add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_theme_for_update' ] );

			add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_plugins_for_update' ], 9999 );
		}

		/**
		 * The filter that checks if there are updates to the theme
		 * using the WP License Manager API.
		 *
		 * @param mixed $transient The transient used for WordPress theme / plugin updates.
		 *
		 * @return mixed The transient with our (possible) additions.
		 */
		public function check_theme_for_update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$update = InsightCore::instance()->get_theme_update_info( 'get_theme_update' );

			if ( ! empty( $update ) && ! empty( $update['success'] ) && ! empty( $update['version'] ) && ! empty( $update['package'] ) ) {
				if ( version_compare( INSIGHT_CORE_THEME_VERSION, $update['version'], '<' ) ) {
					$response = array(
						'url'         => '',
						'new_version' => $update['version'],
						'package'     => $update['package'],
					);

					$transient->response[ INSIGHT_CORE_THEME_SLUG ] = $response;
				}
			}

			return $transient;
		}

		/**
		 * The filter that checks if there are updates to the plugins
		 * using the WP License Manager API.
		 *
		 * Fixed Elementor Pro can't auto update with TGM Plugin Activation
		 *
		 * @param mixed $transient The transient used for WordPress theme / plugin updates.
		 *
		 * @return mixed The transient with our (possible) additions.
		 */
		public function check_plugins_for_update( $transient ) {
			$recommended_plugins = TGM_Plugin_Activation::$instance->plugins;
			$installed_plugins   = get_plugins();

			if ( ! empty( $recommended_plugins ) && ! empty( $installed_plugins ) ) {
				$overridden_plugins = [
					'elementor-pro',
					'tutor-pro',
					'sctv-sales-countdown-timer',
					'woocommerce-multi-currency',
				];

				foreach ( $recommended_plugins as $recommended_plugin ) {
					if ( empty( $recommended_plugin['slug'] ) || empty( $recommended_plugin['file_path'] ) ) {
						continue;
					}

					if ( in_array( $recommended_plugin['slug'], $overridden_plugins, true ) && ! empty( $installed_plugins[ $recommended_plugin['file_path'] ] ) ) {
						$installed_version = $installed_plugins[ $recommended_plugin['file_path'] ]['Version'];

						if ( version_compare( $installed_version, $recommended_plugin['version'], '<' ) ) {
							if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
								$transient->response = array();
							}

							if ( ! isset( $transient->response[ $recommended_plugin['file_path'] ] ) ) {
								$transient->response[ $recommended_plugin['file_path'] ] = new stdClass();
							}

							$thisPlugin              = $transient->response[ $recommended_plugin['file_path'] ];
							$thisPlugin->slug        = $recommended_plugin['slug'];
							$thisPlugin->id          = $recommended_plugin['slug'];
							$thisPlugin->plugin      = $recommended_plugin['file_path'];
							$thisPlugin->new_version = $recommended_plugin['version'];
							$thisPlugin->package     = $recommended_plugin['source'];

							$transient->response[ $recommended_plugin['file_path'] ] = $thisPlugin;
						}
					}
				}
			}

			return $transient;
		}
	}

	new InsightCore_Updater();
}
