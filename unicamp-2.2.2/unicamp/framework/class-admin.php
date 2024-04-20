<?php
defined( 'ABSPATH' ) || exit;

// Do nothing if not an admin page.
if ( ! is_admin() ) {
	return;
}

/**
 * Hook & filter that run only on admin pages.
 */
if ( ! class_exists( 'Unicamp_Admin' ) ) {
	class Unicamp_Admin {

		protected static $instance = null;

		/**
		 * Minimum Insight Core version required to run the theme.
		 *
		 * @var string
		 */
		const MINIMUM_INSIGHT_CORE_VERSION = '2.4.3';

		const MINIMUM_UNICAMP_ADDONS_VERSION = '1.1.0';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'after_switch_theme', array( $this, 'count_switch_time' ), 1 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_editor' ) );

			if ( class_exists( 'InsightCore' ) ) {
				if ( ! defined( 'INSIGHT_CORE_VERSION' ) || ( defined( 'INSIGHT_CORE_VERSION' ) && version_compare( INSIGHT_CORE_VERSION, self::MINIMUM_INSIGHT_CORE_VERSION, '<' ) ) ) {
					add_action( 'admin_notices', [ $this, 'admin_notice_minimum_insight_core_version' ] );
				}
			}

			if ( defined( 'UNICAMP_ADDONS_VERSION' ) && version_compare( UNICAMP_ADDONS_VERSION, self::MINIMUM_UNICAMP_ADDONS_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_unicamp_addons_version' ] );
			}
		}

		public function admin_notice_minimum_insight_core_version() {
			unicamp_notice_required_plugin_version( 'Insight Core', self::MINIMUM_INSIGHT_CORE_VERSION );
		}

		public function admin_notice_minimum_unicamp_addons_version() {
			unicamp_notice_required_plugin_version( 'Unicamp Addons', self::MINIMUM_UNICAMP_ADDONS_VERSION );
		}

		public function gutenberg_editor() {
			/**
			 * Enqueue fonts for gutenberg editor.
			 */
			wp_enqueue_style( 'font-gordita', UNICAMP_THEME_URI . '/assets/fonts/gordita/font-gordita.min.css', null, null );
		}

		public function count_switch_time() {
			$count = get_option( 'unicamp_switch_theme_count' );

			if ( $count ) {
				$count++;
			} else {
				$count = 1;
			}

			update_option( 'unicamp_switch_theme_count', $count );
		}

		/**
		 * Enqueue scrips & styles.
		 *
		 * @access public
		 */
		function enqueue_scripts() {
			$this->enqueue_fonts_for_rev_slider_page();

			wp_enqueue_style( 'unicamp-admin', UNICAMP_THEME_URI . '/assets/admin/css/style.min.css' );

			$root_css = Unicamp_Custom_Css::instance()->get_root_css();
			wp_add_inline_style( 'unicamp-admin', html_entity_decode( $root_css, ENT_QUOTES ) );
		}

		/**
		 * Enqueue fonts for Rev Slider edit page.
		 */
		function enqueue_fonts_for_rev_slider_page() {
			$screen = get_current_screen();

			if ( 'toplevel_page_revslider' !== $screen->base ) {
				return;
			}

			$typo_fields = array(
				'typography_body',
				'typography_heading',
				'button_typography',
			);

			if ( ! is_array( $typo_fields ) || empty( $typo_fields ) ) {
				return;
			}

			foreach ( $typo_fields as $field ) {
				$value = Unicamp::setting( $field );

				if ( is_array( $value ) && isset( $value['font-family'] ) && $value['font-family'] !== '' ) {
					switch ( $value['font-family'] ) {
						case 'TTCommons':
							wp_enqueue_style( 'ttcommons-font', UNICAMP_THEME_URI . '/assets/fonts/TTCommons/TTCommons.css', null, null );
							break;
						default:
							do_action( 'unicamp_enqueue_custom_font', $value['font-family'] ); // hook to custom do enqueue fonts
							break;
					}
				}
			}
		}
	}

	Unicamp_Admin::instance()->initialize();
}
