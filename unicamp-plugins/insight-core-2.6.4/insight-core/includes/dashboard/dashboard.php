<?php
if ( ! class_exists( 'InsightCore_Dashboard' ) ) {
	class InsightCore_Dashboard {
		function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_network_dashboard_setup', array( $this, 'add_dashboard_widget' ), 1 );
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ), 1 );
		}

		function add_dashboard_widget() {
			wp_add_dashboard_widget( 'insight_core_featured_themes', 'Featured Themes by ThemeMove', array(
				$this,
				'featured_themes_content',
			), null, null, 'normal', 'high' );

			wp_add_dashboard_widget( 'insight_core_featured_plugins', 'Featured Plugins by ThemeMove', array(
				$this,
				'featured_plugins_content',
			), null, null, 'normal', 'high' );
		}

		function admin_enqueue_scripts( $hook ) {
			// Don't load scripts when not Dashboard Home.
			if ( $hook !== 'index.php' ) {
				return;
			}

			wp_enqueue_style( 'slick', INSIGHT_CORE_PATH . 'includes/dashboard/slick/slick.css' );
			wp_enqueue_script( 'slick', INSIGHT_CORE_PATH . 'includes/dashboard/slick/slick.min.js', array( 'jquery' ), null, true );
			wp_enqueue_style( 'insight-core-dashboard', INSIGHT_CORE_PATH . 'includes/dashboard/style.css' );
			wp_enqueue_script( 'insight-core-dashboard', INSIGHT_CORE_PATH . 'includes/dashboard/script.js', array( 'jquery' ), null, true );
		}

		function featured_themes_content() {
			if ( false === ( $themes = get_transient( 'insight_core_featured_themes' ) ) ) {
				$request = wp_remote_get( 'https://api.thememove.com/dashboard/themes.json', array( 'timeout' => 120 ) );
				if ( ! is_wp_error( $request ) ) {
					$themes = json_decode( wp_remote_retrieve_body( $request ) );
					set_transient( 'insight_core_featured_themes', $themes, 24 * HOUR_IN_SECONDS );
				}
			}
			if ( is_object( $themes ) ) {
				?>
				<div class="tm-components-recommended-carousel">
					<?php
					foreach ( $themes as $theme ) {
						echo '<div class="item"><a href="' . esc_url( $theme->url ) . '/" target="_blank" title="' . esc_attr( $theme->name ) . '"><img src="' . esc_url( $theme->img ) . '"/><span class="info"><span class="name">' . esc_html( $theme->name ) . '</span></span></a></div>';
					}
					?>
				</div>
				<?php
			}
		}

		function featured_plugins_content() {
			if ( false === ( $plugins = get_transient( 'insight_core_featured_plugins' ) ) ) {
				$request = wp_remote_get( 'https://api.thememove.com/dashboard/plugins.json', array( 'timeout' => 120 ) );
				if ( ! is_wp_error( $request ) ) {
					$plugins = json_decode( wp_remote_retrieve_body( $request ) );
					set_transient( 'insight_core_featured_plugins', $plugins, 24 * HOUR_IN_SECONDS );
				}
			}
			if ( is_object( $plugins ) ) {
				?>
				<div class="tm-components-recommended-carousel">
					<?php
					foreach ( $plugins as $plugin ) {
						echo '<div class="item"><a href="' . esc_url( $plugin->url ) . '/" target="_blank" title="' . esc_attr( $plugin->name ) . '"><img src="' . esc_url( $plugin->img ) . '"/><span class="info"><span class="name">' . esc_html( $plugin->name ) . '</span></span></a></div>';
					}
					?>
				</div>
				<?php
			}
		}
	}

	new InsightCore_Dashboard();
}
