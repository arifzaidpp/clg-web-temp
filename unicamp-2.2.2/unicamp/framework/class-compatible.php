<?php
defined( 'ABSPATH' ) || exit;

/**
 * Prevents theme from running on WordPress versions prior to 4.3,
 *
 * Since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.3.
 */
if ( ! class_exists( 'Unicamp_Compatible' ) ) {
	class Unicamp_Compatible {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			if ( version_compare( $GLOBALS['wp_version'], '4.3', '<' ) ) {
				add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
				add_action( 'load-customize.php', array( $this, 'customize' ) );
				add_action( 'template_redirect', array( $this, 'preview' ) );
			}
		}

		/**
		 * Prevent switching to this theme on old versions of WordPress.
		 *
		 * Switches to the default theme.
		 */
		public function switch_theme() {
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

			unset( $_GET['activated'] );

			add_action( 'admin_notices', array( $this, 'upgrade_notice' ) );
		}

		/**
		 * Adds a message for unsuccessful theme switch.
		 *
		 * Prints an update nag after an unsuccessful attempt to switch to
		 * this theme on WordPress versions prior to 4.3.
		 *
		 * @global string $wp_version WordPress version.
		 */
		public function upgrade_notice() {
			$message = sprintf( UNICAMP_THEME_NAME . esc_html__( ' requires at least WordPress version 4.3. You are running version %s. Please upgrade and try again.', 'unicamp' ), $GLOBALS['wp_version'] );
			printf( '<div class="error"><p>%s</p></div>', $message );
		}

		/**
		 * Prevents the Customizer from being loaded on WordPress versions prior to 4.3.
		 *
		 * @global string $wp_version WordPress version.
		 */
		public function customize() {
			wp_die( sprintf( UNICAMP_THEME_NAME . esc_html__( ' requires at least WordPress version 4.3. You are running version %s. Please upgrade and try again.', 'unicamp' ), $GLOBALS['wp_version'] ), '', array(
				'back_link' => true,
			) );
		}

		/**
		 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.3.
		 *
		 * @global string $wp_version WordPress version.
		 */
		public function preview() {
			if ( isset( $_GET['preview'] ) ) {
				wp_die( sprintf( UNICAMP_THEME_NAME . esc_html__( ' requires at least WordPress version 4.3. You are running version %s. Please upgrade and try again.', 'unicamp' ), $GLOBALS['wp_version'] ) );
			}
		}
	}

	Unicamp_Compatible::instance()->initialize();
}
