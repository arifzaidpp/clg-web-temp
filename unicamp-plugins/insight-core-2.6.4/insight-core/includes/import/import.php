<?php
define( 'INSIGHT_IMPORT_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'INSIGHT_IMPORT_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

class InsightCore_Import {
	public $demos            = array();
	public $dummies          = array();
	public $style            = array();
	public $support          = array();
	public $generate_thumb   = false;
	public $importer_version = '2';

	private $response = array();
	private $process  = array();
	/**
	 * @var InsightCore_Importer_2 $importer
	 */
	private $importer;
	private $file_path;
	private $_cpath;

	public function __construct() {
		$this->response  = array( 'status' => 'fail', 'message' => '' );
		$this->file_path = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS;
		$this->_cpath    = WP_CONTENT_DIR . INSIGHT_CORE_DS;

		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'init', array( $this, 'init' ) );

		// Compatible with Elementor.
		if ( defined( 'ELEMENTOR_VERSION' ) && ( is_admin() || defined( 'WP_LOAD_IMPORTERS' ) ) ) {
			add_filter( 'wp_import_post_meta', [ $this, 'on_wp_import_post_meta' ] );
			add_filter( 'wxr_importer.pre_process.post_meta', [ $this, 'on_wxr_importer_pre_process_post_meta' ] );

			add_filter( 'wp_update_attachment_metadata', [ $this, 'fix_elementor_raising_php_warning' ], 9, 2 );
		}

		// AJAX Import
		add_action( 'wp_ajax_import_dummy', array( $this, 'import_dummy' ) );
	}

	/**
	 * Fix PHP Warning on import SVG
	 * Some ways $data is string not is an array.
	 * Priority 9 to run before Elementor.
	 */
	public function fix_elementor_raising_php_warning( $data, $id ) {
		$data = (array) $data;

		return $data;
	}

	/**
	 * Process post meta before WP importer.
	 *
	 * Normalize Elementor post meta on import, We need the `wp_slash` in order
	 * to avoid the unslashing during the `add_post_meta`.
	 *
	 * Fired by `wp_import_post_meta` filter.
	 *
	 * @since  1.7.3
	 * @access public
	 * @static
	 *
	 * @param array $post_meta Post meta.
	 *
	 * @return array Updated post meta.
	 */
	public static function on_wp_import_post_meta( $post_meta ) {
		// Do nothing when WP Importer plugin version > 0.7 & Elementor 2.9.14 installed.
		/*if ( version_compare( ELEMENTOR_VERSION, '2.9.14', '>=' ) && ! self::is_wp_importer_before_0_7() ) {
			return $post_meta;
		}*/

		foreach ( $post_meta as &$meta ) {
			if ( '_elementor_data' === $meta['key'] ) {
				$meta['value'] = wp_slash( $meta['value'] );
				break;
			}
		}

		return $post_meta;
	}

	/**
	 * Process post meta before WXR importer.
	 *
	 * Normalize Elementor post meta on import with the new WP_importer, We need
	 * the `wp_slash` in order to avoid the unslashing during the `add_post_meta`.
	 *
	 * Fired by `wxr_importer.pre_process.post_meta` filter.
	 *
	 * @since  1.7.3
	 * @access public
	 * @static
	 *
	 * @param array $post_meta Post meta.
	 *
	 * @return array Updated post meta.
	 */
	public static function on_wxr_importer_pre_process_post_meta( $post_meta ) {
		// Do nothing when WP Importer plugin version > 0.7 & Elementor 2.9.14 installed.
		/*if ( version_compare( ELEMENTOR_VERSION, '2.9.14', '>=' ) && ! self::is_wp_importer_before_0_7() ) {
			return $post_meta;
		}*/

		if ( '_elementor_data' === $post_meta['key'] ) {
			$post_meta['value'] = wp_slash( $post_meta['value'] );
		}

		return $post_meta;
	}

	/**
	 * Is WP Importer Before 0.7
	 *
	 * Checks if WP Importer plugin is installed, and whether its version is older than 0.7.
	 *
	 * @return bool
	 */
	public static function is_wp_importer_before_0_7() {
		$wp_importer = get_plugins( '/wordpress-importer' );

		if ( ! empty( $wp_importer ) ) {
			$wp_importer_version = $wp_importer['wordpress-importer.php']['Version'];

			if ( version_compare( $wp_importer_version, '0.7', '<' ) ) {
				return true;
			}
		}

		return false;
	}

	public function init() {
		$this->demos            = apply_filters( 'insight_core_import_demos', array() );
		$this->dummies          = apply_filters( 'insight_core_import_dummies', array() );
		$this->generate_thumb   = apply_filters( 'insight_core_import_generate_thumb', false );
		$this->importer_version = apply_filters( 'insight_core_importer_version', '2' );
	}

	public function register_menu() {
		add_submenu_page( 'insight-core', 'Import', 'Import', 'manage_options', 'insight-core-import', array(
			&$this,
			'register_page',
		) );
	}

	public function register_page() {
		$demos            = $this->demos;
		$dummies          = $this->dummies;
		$generate_thumb   = $this->generate_thumb;
		$importer_version = $this->importer_version;

		require_once( INSIGHT_IMPORT_PATH . INSIGHT_CORE_DS . 'import-page.php' );
	}

	public function import_dummy() {

		if ( ! empty( $_GET['dummy'] ) ) {
			$this->dummy = sanitize_text_field( $_GET['dummy'] );

			if ( ! $this->is_valid_dummy_slug( $this->dummy ) ) {
				$this->send_fail_msg( esc_html__( 'Wrong dummy name', 'insight-core' ) );
			}

			$this->process = explode( ',', $this->dummies[ $this->dummy ]['process'] );

			$this->load_importers();

			if ( $this->need_process( 'media' ) ) {
				if ( ! $this->importer->check_writeable() ) {
					$this->send_fail_msg( wp_kses(
						sprintf( __( 'Could not write files into directory: <strong>%swp-content</strong>', 'insight-core' ), str_replace( '\\', '/', ABSPATH ) ), [
						'strong' => array(),
					] ) );
				}

				$_tmppath = $this->_cpath . INSIGHT_CORE_THEME_SLUG . '-' . $this->dummy . '_tmp';

				// START DOWNLOAD IMAGES
				$this->importer->download_package( $_tmppath );
				//  FINISH DOWNLOAD AND UNPACKAGE
				$this->importer->unpackage( $this->_cpath, $_tmppath );
			}

			if ( $this->need_process( 'woocommerce' ) ) {
				$this->importer->import_woocommerce_image_sizes();
			}

			if ( $this->need_process( 'xml' ) ) {
				$this->import_xml();
			}

			if ( $this->need_process( 'home' ) ) {
				$this->importer->import_page_options();
			}

			if ( $this->need_process( 'sidebars' ) ) {
				$this->importer->import_sidebars();
			}

			if ( $this->need_process( 'widgets' ) ) {
				$this->importer->import_widgets();
			}

			if ( $this->need_process( 'menus' ) ) {
				$this->importer->import_menus();
			}

			if ( $this->need_process( 'customizer' ) ) {
				$this->importer->import_customizer_options();
			}

			if ( $this->need_process( 'woocommerce' ) ) {
				$this->importer->import_woocommerce_pages();
			}

			if ( $this->need_process( 'essential_grid' ) ) {
				$this->importer->import_essential_grid();
				$this->importer->fix_essential_grid();
			}

			if ( $this->need_process( 'sliders' ) ) {
				$this->importer->import_rev_sliders();
			}

			InsightCore::update_option_count( INSIGHT_CORE_THEME_SLUG . '_' . $this->dummy . '_imported' );

			$this->send_success_msg( esc_html__( 'Import is successful!', 'insight-core' ) );

		} else {
			$this->send_fail_msg( esc_html__( 'Wrong dummy name', 'insight-core' ) );
		}

		$this->send_response();
	}

	private function need_process( $process ) {
		return in_array( $process, $this->process );
	}

	private function load_importers() {
		require_once( INSIGHT_IMPORT_PATH . INSIGHT_CORE_DS . 'importer.php' );

		// Load Importer API
		if ( class_exists( 'InsightCore_Importer' ) ) {
			$this->importer                 = new InsightCore_Importer( false );
			$this->importer->generate_thumb = $this->generate_thumb;
		} else {
			$this->send_fail_msg( esc_html__( 'Can\'t find InsightCore_Importer class', 'insight-core' ) );
		}
	}

	private function import_xml() {
		$file = $this->get_file_to_import( 'content.xml' );

		if ( ! $file ) {
			$this->send_fail_msg( sprintf( wp_kses( __( 'File does not exist: <strong>%s/content.xml</strong>', 'insight-core' ), array( 'strong' => array() ) ), $this->dummy ) );
		}

		try {

			$this->importer->import( $file );

		} catch ( Exception $ex ) {
			$this->send_fail_msg( esc_html__( 'Error while importing', 'insight-core' ) );

			if ( WP_DEBUG ) {
				var_dump( $ex );
			}
		}
	}

	private function get_file_to_import( $filename ) {
		$file = $this->file_path . $this->dummy . INSIGHT_CORE_DS . $filename;

		if ( ! file_exists( $file ) ) {
			return false;
		}

		return $file;
	}

	private function send_response() {
		if ( ! empty( $this->response ) ) {
			wp_send_json( $this->response );
		} else {
			wp_send_json( array( 'message' => 'empty response' ) );
		}
	}

	private function send_success_msg( $msg ) {
		$this->send_msg( 'success', $msg );
	}


	private function send_fail_msg( $msg ) {
		$this->send_msg( 'fail', $msg );
	}

	private function send_msg( $status, $message ) {
		$this->response = array(
			'status'  => $status,
			'message' => $message,
		);

		$this->send_response();
	}

	private function is_valid_dummy_slug( $dummy ) {
		return in_array( $dummy, array_keys( $this->dummies ) );
	}
}

new InsightCore_Import();
