<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'PGKFF_DIR_URL' ) ) {
	define( 'PGKFF_DIR_URL', plugin_dir_url( __FILE__ ) );
}

define( 'KFF_VERSION', '1.0.0' );
define( 'KFF_JS_URL', trailingslashit( PGKFF_DIR_URL . 'js' ) );
define( 'KFF_CSS_URL', trailingslashit( PGKFF_DIR_URL . 'css' ) );
define( 'KFF_ASSETS_URL', trailingslashit( PGKFF_DIR_URL . 'assets' ) );
define( 'KFF_FONTS_URL', trailingslashit( PGKFF_DIR_URL . 'fonts' ) );
define( 'KFF_INC_DIR', trailingslashit( plugin_dir_path( __FILE__ ) . 'inc' ) );
define( 'KFF_FIELDS_DIR', trailingslashit( KFF_INC_DIR . 'fields' ) );

// Helper class
require_once( KFF_INC_DIR . 'class.helper.php' );
// Fields class
require_once( KFF_FIELDS_DIR . 'tabpanel.php' );
require_once( KFF_FIELDS_DIR . 'accordion.php' );
require_once( KFF_FIELDS_DIR . 'text.php' );
require_once( KFF_FIELDS_DIR . 'number.php' );
require_once( KFF_FIELDS_DIR . 'editor.php' );
require_once( KFF_FIELDS_DIR . 'textarea.php' );
require_once( KFF_FIELDS_DIR . 'ace-editor.php' );
require_once( KFF_FIELDS_DIR . 'range.php' );
require_once( KFF_FIELDS_DIR . 'media.php' );
require_once( KFF_FIELDS_DIR . 'message.php' );
require_once( KFF_FIELDS_DIR . 'gallery.php' );
require_once( KFF_FIELDS_DIR . 'checkbox.php' );
require_once( KFF_FIELDS_DIR . 'switch.php' );
require_once( KFF_FIELDS_DIR . 'radio.php' );
require_once( KFF_FIELDS_DIR . 'select.php' );
require_once( KFF_FIELDS_DIR . 'image-select.php' );
require_once( KFF_FIELDS_DIR . 'color.php' );
require_once( KFF_FIELDS_DIR . 'typography.php' );
require_once( KFF_FIELDS_DIR . 'audio.php' );
require_once( KFF_FIELDS_DIR . 'attach.php' );

if ( ! class_exists( 'Kungfu_Framework' ) ) {
	class Kungfu_Framework {

		/**
		 * Plugin version, used for cache-busting of style and script file references.
		 *
		 * @since   1.0.0
		 *
		 * @var     string
		 */
		const VERSION = KFF_VERSION;

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 * and styles.
		 *
		 * @since     1.0.0
		 */
		function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'after_setup_theme', array( $this, 'require_files_if_theme_support' ), 13 );
		}

		/**
		 * Enqueue the admin page CSS and JS
		 *
		 * @return    void
		 */
		function enqueue_scripts( $hook ) {
			wp_enqueue_style( 'kungfu_admin_css', KFF_CSS_URL . 'admin-style.css', false, KFF_VERSION );
		}

		function require_files_if_theme_support() {
			require_if_theme_supports( 'insight-sidebar', KFF_INC_DIR . 'sidebars/class.sidebars.php' );
			require_if_theme_supports( 'insight-metabox', KFF_INC_DIR . 'class.meta-box.php' );
			require_if_theme_supports( 'insight-portfolio', KFF_INC_DIR . 'class.portfolio.php' );
			require_if_theme_supports( 'insight-case-study', KFF_INC_DIR . 'class.case-study.php' );
			require_if_theme_supports( 'insight-service', KFF_INC_DIR . 'class.service.php' );
			require_if_theme_supports( 'insight-project', KFF_INC_DIR . 'class.project.php' );
			require_if_theme_supports( 'insight-testimonial', KFF_INC_DIR . 'class.testimonial.php' );
		}
	}

	new Kungfu_Framework();
}
