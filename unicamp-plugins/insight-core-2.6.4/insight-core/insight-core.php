<?php
/*
Plugin Name: Insight Core
Description: Core functions for WordPress theme
Author: ThemeMove
Version: 2.6.4
Author URI: https://thememove.com
Text Domain: insight-core
Domain Path: /languages/
Requires at least: 5.7
Requires PHP: 7.0
*/
defined( 'ABSPATH' ) || exit;

$theme = wp_get_theme();
if ( ! empty( $theme['Template'] ) ) {
	$theme = wp_get_theme( $theme['Template'] );
}
define( 'INSIGHT_CORE_FILE', __FILE__ );
define( 'INSIGHT_CORE_VERSION', '2.6.4' );
define( 'INSIGHT_CORE_SITE_URI', site_url() );
define( 'INSIGHT_CORE_PATH', plugin_dir_url( __FILE__ ) );
define( 'INSIGHT_CORE_DIR', dirname( __FILE__ ) );
define( 'INSIGHT_CORE_DS', DIRECTORY_SEPARATOR );
define( 'INSIGHT_CORE_INC_DIR', INSIGHT_CORE_DIR . '/includes' );
define( 'INSIGHT_CORE_THEME_NAME', $theme['Name'] );
define( 'INSIGHT_CORE_THEME_SLUG', $theme['Template'] );
define( 'INSIGHT_CORE_THEME_VERSION', $theme['Version'] );
define( 'INSIGHT_CORE_THEME_DIR', get_template_directory() );
define( 'INSIGHT_CORE_THEME_URI', get_template_directory_uri() );

if ( ! class_exists( 'InsightCore' ) ) {
	class InsightCore {
		public static $info;

		protected static $instance = null;

		/**
		 * Kirki need loaded before theme setup
		 * Then there is no way to disable Kirki required.
		 * Manual assign themes to disable.
		 */
		const DISABLE_KIRKI_BY_THEMES = [
			'minimog',
		];

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			$this->set_info();

			add_action( 'plugins_loaded', [ $this, 'load_text_domain' ] );

			add_filter( 'widget_text', 'do_shortcode' );

			add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ), 12 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			add_action( 'do_meta_boxes', array( $this, 'remove_revolution_slider_meta_boxes' ) );

			add_filter( 'user_contactmethods', array( $this, 'add_extra_fields_for_contactmethods' ), 10, 1 );

			add_action( 'admin_init', [ $this, 'form_submit_handlers' ] );

			add_action( 'wp_ajax_insight_core_delete_exist_posts', [ $this, 'ajax_delete_exist_posts' ] );

			// Custom Functions
			include_once( INSIGHT_CORE_INC_DIR . '/functions.php' );

			// Register Posttypes
			include_once( INSIGHT_CORE_INC_DIR . '/register-posttypes.php' );

			// Register widgets
			include_once( INSIGHT_CORE_INC_DIR . '/register-widgets.php' );

			// Pages
			include_once( INSIGHT_CORE_INC_DIR . '/pages.php' );

			// TMG
			include_once( INSIGHT_CORE_INC_DIR . '/tgm-plugin-activation.php' );
			require_once( INSIGHT_CORE_INC_DIR . '/tgm-plugin-registration.php' );

			// Import & Export
			include_once( INSIGHT_CORE_INC_DIR . '/export/export.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/import/import.php' );

			// Kirki
			if ( ! in_array( INSIGHT_CORE_THEME_SLUG, self::DISABLE_KIRKI_BY_THEMES ) ) {
				include_once( INSIGHT_CORE_DIR . '/libs/kirki/kirki.php' );
				add_filter( 'kirki/config', array( $this, 'kirki_update_url' ) );
			}

			// Update
			include_once( INSIGHT_CORE_INC_DIR . '/update/class-updater.php' );

			// Others
			include_once( INSIGHT_CORE_INC_DIR . '/customizer/io.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/breadcrumb.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/better-menu-widget.php' );

			// Dashboard
			include_once( INSIGHT_CORE_INC_DIR . '/dashboard/dashboard.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/dashboard/banner.php' );
		}

		public function ajax_delete_exist_posts() {
			if ( ! check_ajax_referer( 'delete_exist_posts', 'nonce_delete_exist_posts' ) ) {
				wp_die();
			}

			if ( ! current_user_can( 'administrator' ) ) {
				wp_die();
			}

			/**
			 * Delete exist posts & their meta data to make Importer working perfectly.
			 *
			 * @since 2.2.0
			 */
			$delete_exist_posts = apply_filters( 'insight_core_import_delete_exist_posts', false );

			if ( ! $delete_exist_posts ) {
				wp_die();
			}

			$this->delete_all_exist_posts();

			wp_die();
		}

		public function delete_all_exist_posts() {
			global $wpdb;

			$wpdb->query( "DELETE FROM {$wpdb->posts}" );

			$wpdb->query( "DELETE FROM {$wpdb->postmeta}" );

			// We need to delete some options
			$options_delete = [
				'bp-pages', // Buddypress pages
			];

			foreach ( $options_delete as $option ) {
				delete_option( $option );
			}
		}

		public function set_info() {
			self::$info = array(
				'author'  => 'ThemeMove',
				'support' => 'https://thememove.ticksy.com/',
				'faqs'    => 'https://thememove.ticksy.com/articles/',
				'docs'    => 'https://document.thememove.com/',
				'api'     => 'https://api.thememove.com/update/thememove/',
				'child'   => '',
				'icon'    => INSIGHT_CORE_PATH . '/assets/images/tm-icon.png',
				'desc'    => 'Thank you for using our theme, please reward it a full five-star &#9733;&#9733;&#9733;&#9733;&#9733; rating.',
				'tf'      => 'https://themeforest.net/user/thememove/portfolio',
			);
		}

		/**
		 * Add extra fields to Contact info section in edit profile page.
		 */
		public function add_extra_fields_for_contactmethods( $contactmethods ) {
			if ( get_theme_support( 'insight-user-social-networks' ) ) {

				$default = array(
					array(
						'name'  => 'email_address',
						'label' => esc_html__( 'Email Address', 'insight-core' ),
					),
					array(
						'name'  => 'facebook',
						'label' => esc_html__( 'Facebook', 'insight-core' ),
					),
					array(
						'name'  => 'twitter',
						'label' => esc_html__( 'Twitter', 'insight-core' ),
					),
					array(
						'name'  => 'instagram',
						'label' => esc_html__( 'Instagram', 'insight-core' ),
					),
					array(
						'name'  => 'linkedin',
						'label' => esc_html__( 'Linkedin', 'insight-core' ),
					),
					array(
						'name'  => 'pinterest',
						'label' => esc_html__( 'Pinterest', 'insight-core' ),
					),
				);

				$extra_fields = apply_filters( 'insight_core_user_contactmethods', $default );

				if ( ! empty ( $extra_fields ) ) {
					foreach ( $extra_fields as $field ) {
						if ( ! isset( $contactmethods[ $field['name'] ] ) ) {
							$contactmethods[ $field['name'] ] = $field['label'];
						}
					}
				}
			}

			return $contactmethods;
		}

		public function load_text_domain() {
			load_plugin_textdomain( 'insight-core', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public function after_setup_theme() {
			// Get info
			self::$info = apply_filters( 'insight_core_info', self::$info );

			if ( empty( self::$info['envato_id'] ) ) {
				$tf        = $this->get_info( 'tf' );
				$tf        = explode( '/', $tf );
				$envato_id = end( $tf );

				self::$info['envato_id'] = $envato_id;
			}

			// Detect
			require_if_theme_supports( 'insight-detect', INSIGHT_CORE_DIR . '/libs/mobile-detect/mobile.php' );

			// CMB2
			require_if_theme_supports( 'insight-cmb2', INSIGHT_CORE_DIR . '/libs/cmb2/init.php' );
			add_filter( 'cmb2_meta_box_url', array( $this, 'cmb2_meta_box_url' ) );

			// Kungfu Framework
			require_if_theme_supports( 'insight-kungfu', INSIGHT_CORE_DIR . '/libs/kungfu/kungfu-framework.php' );

			// Mega menu
			require_if_theme_supports( 'insight-megamenu', INSIGHT_CORE_INC_DIR . '/mega-menu/mega-menu.php' );

			// Popup
			require_if_theme_supports( 'insight-popup', INSIGHT_CORE_INC_DIR . '/popup/popup.php' );

			// Footer
			require_if_theme_supports( 'insight-footer', INSIGHT_CORE_INC_DIR . '/footer/footer.php' );

			// Share
			require_if_theme_supports( 'insight-share', INSIGHT_CORE_INC_DIR . '/share.php' );

			// View
			require_if_theme_supports( 'insight-view', INSIGHT_CORE_INC_DIR . '/view.php' );
		}

		public function admin_enqueue_scripts( $hook ) {
			wp_enqueue_style( 'insight-core-backend', INSIGHT_CORE_PATH . 'assets/css/backend.css' );

			if ( strpos( $hook, 'insight-core' ) !== false ) {
				wp_enqueue_style( 'hint', INSIGHT_CORE_PATH . 'assets/css/hint.css' );
				wp_enqueue_style( 'font-awesome', INSIGHT_CORE_PATH . 'assets/css/font-awesome.min.css' );
				wp_enqueue_style( 'pe-icon-7-stroke', INSIGHT_CORE_PATH . 'assets/css/pe-icon-7-stroke.css' );
				wp_enqueue_style( 'insight-core', INSIGHT_CORE_PATH . 'assets/css/insight-core.css' );
				wp_enqueue_script( 'insight-core', INSIGHT_CORE_PATH . 'assets/js/insight-core.js', array( 'jquery' ), INSIGHT_CORE_THEME_VERSION, true );
				wp_localize_script( 'insight-core', 'ic_vars', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ic_nonce' => wp_create_nonce( 'ic_nonce' ),
				) );
			}

			$screen = get_current_screen();

			if ( 'insight-core_page_insight-core-import' === $screen->id ) {
				wp_enqueue_script( 'insight-core-importer', INSIGHT_CORE_PATH . 'assets/js/importer.js', array( 'jquery' ), INSIGHT_CORE_THEME_VERSION, true );
			}
		}

		public function cmb2_meta_box_url() {
			return INSIGHT_CORE_PATH . '/libs/cmb2/';
		}

		public function kirki_update_url( $config ) {
			$config['url_path'] = INSIGHT_CORE_PATH . '/libs/kirki';

			return $config;
		}

		// Check theme support
		public static function is_theme_support() {
			if ( current_theme_supports( 'insight-core' ) ) {
				return true;
			} else {
				return false;
			}
		}

		public function form_submit_handlers() {
			$action = isset( $_POST['insight_core_action'] ) ? sanitize_text_field( $_POST['insight_core_action'] ) : '';

			if ( empty( $action ) ) {
				return;
			}

			if ( ! check_admin_referer( 'check_update', 'insight_core_nonce' ) ) {
				return;
			}

			switch ( $action ) {
				case 'check_update' :
					$current_time = current_time( 'timestamp' );
					update_option( 'insight_core_last_check_update_time', $current_time );

					delete_transient( 'insight_core_theme_latest_version' );
					$this->get_latest_theme_version();
					break;
			}
		}

		public function get_info( $name = '' ) {
			if ( ! empty( $name ) && isset( self::$info[ $name ] ) ) {
				return self::$info[ $name ];
			}

			return self::$info;
		}

		public function get_latest_theme_version() {
			$transient_key  = 'insight_core_theme_latest_version';
			$latest_version = get_transient( $transient_key );

			if ( false === $latest_version ) {
				$theme_info = $this->get_theme_update_info( 'get_theme_version' );

				if ( ! empty( $theme_info ) && ! empty( $theme_info['version'] ) ) {
					$latest_version = $theme_info['version'];

					set_transient( $transient_key, $latest_version, 8 * HOUR_IN_SECONDS );

					$current_time = current_time( 'timestamp' );
					update_option( 'insight_core_last_check_update_time', $current_time );
				}
			}

			return $latest_version;
		}

		/**
		 * @param string $action supported actions: get_theme_version, get_theme_update
		 *
		 * @return mixed
		 */
		public function get_theme_update_info( $action = 'get_theme_version' ) {
			$purchase_code = $this->get_purchased_code();

			if ( empty( $purchase_code ) ) {
				return false;
			}

			$author    = InsightCore::instance()->get_info( 'author' );
			$envato_id = InsightCore::instance()->get_info( 'envato_id' );

			$api_endpoint_url = 'https://api.thememove.com/purchase/check-update.php';

			$api_endpoint_url = add_query_arg( [
				'action'  => $action,
				'code'    => $purchase_code,
				'product' => $envato_id,
				'author'  => $author,
			], $api_endpoint_url );

			$request = wp_remote_get( $api_endpoint_url, array( 'timeout' => 120 ) );
			$result  = json_decode( wp_remote_retrieve_body( $request ), true );

			return $result;
		}

		public function get_purchased_code() {
			$purchase_code = get_option( 'insight_core_purchase_code' );

			if ( empty( $purchase_code ) ) {
				return false;
			}

			if ( ! preg_match( "/^(\w{8})-((\w{4})-){3}(\w{12})$/", $purchase_code ) ) {
				return false;
			}

			return $purchase_code;
		}

		// Check purchase code
		public static function check_purchase_code( $code ) {
			$author = self::$info['author'];

			$api_url = 'https://api.thememove.com/purchase/tf.php';

			$api_url = add_query_arg( [
				'code'   => $code,
				'author' => $author,
			], $api_url );

			$request = wp_remote_get( $api_url, array( 'timeout' => 120 ) );
			$json    = json_decode( wp_remote_retrieve_body( $request ), true );

			return $json;
		}

		public static function check_valid_update() {
			$can_update    = false;
			$purchase_code = get_option( 'insight_core_purchase_code' ); // Purchase code in database.

			if ( empty( $purchase_code ) ) {
				return $can_update;
			}

			// Check purchase code still valid?
			$purchase_info = InsightCore::check_purchase_code( $purchase_code );
			if ( is_array( $purchase_info ) && count( $purchase_info ) > 0 ) {
				// Check item_id
				$tf      = explode( '/', self::$info['tf'] );
				$item_id = end( $tf );

				$p_item_id  = $purchase_info['item_id'];
				$can_update = ( $item_id == $p_item_id );
			}

			return $can_update;
		}

		// Update option count.
		public static function update_option_count( $option ) {
			if ( get_option( $option ) != false ) {
				update_option( $option, get_option( $option ) + 1 );
			} else {
				update_option( $option, '1' );
			}
		}

		// Update option array.
		public function update_option_array( $option, $value ) {
			if ( get_option( $option ) ) {
				$options = get_option( $option );
				if ( ! in_array( $value, $options ) ) {
					$options[] = $value;
					update_option( $option, $options );
				}
			} else {
				update_option( $option, array( $value ) );
			}
		}

		// Get action link for each plugin.
		public static function plugin_action( $item ) {
			$installed_plugins        = get_plugins();
			$item['sanitized_plugin'] = $item['name'];
			$actions                  = array();
			// We have a repo plugin
			if ( ! $item['version'] ) {
				$item['version'] = TGM_Plugin_Activation::$instance->does_plugin_have_update( $item['slug'] );
			}
			if ( ! isset( $installed_plugins[ $item['file_path'] ] ) ) {
				// Display install link
				$actions = sprintf( '<a href="%1$s" title="Install %2$s">Install</a>', esc_url( wp_nonce_url( add_query_arg( array(
					'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
					'plugin'        => urlencode( $item['slug'] ),
					'plugin_name'   => urlencode( $item['sanitized_plugin'] ),
					'plugin_source' => urlencode( $item['source'] ),
					'tgmpa-install' => 'install-plugin',
				), TGM_Plugin_Activation::$instance->get_tgmpa_url() ), 'tgmpa-install', 'tgmpa-nonce' ) ), $item['sanitized_plugin'] );
			} elseif ( is_plugin_inactive( $item['file_path'] ) ) {
				// Display activate link
				$actions = sprintf( '<a href="%1$s" title="Activate %2$s">Activate</a>', esc_url( add_query_arg( array(
					'plugin'               => urlencode( $item['slug'] ),
					'plugin_name'          => urlencode( $item['sanitized_plugin'] ),
					'plugin_source'        => urlencode( $item['source'] ),
					'tgmpa-activate'       => 'activate-plugin',
					'tgmpa-activate-nonce' => wp_create_nonce( 'tgmpa-activate' ),
				), admin_url( 'admin.php?page=insight-core' ) ) ), $item['sanitized_plugin'] );
			} elseif ( version_compare( $installed_plugins[ $item['file_path'] ]['Version'], $item['version'], '<' ) ) {
				// Display update link
				$actions = sprintf( '<a href="%1$s" title="Install %2$s">Update</a>', wp_nonce_url( add_query_arg( array(
					'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
					'plugin'        => urlencode( $item['slug'] ),
					'tgmpa-update'  => 'update-plugin',
					'plugin_source' => urlencode( $item['source'] ),
					'version'       => urlencode( $item['version'] ),
				), TGM_Plugin_Activation::$instance->get_tgmpa_url() ), 'tgmpa-update', 'tgmpa-nonce' ), $item['sanitized_plugin'] );
			} elseif ( is_plugin_active( $item['file_path'] ) ) {
				// Display deactivate link
				$actions = sprintf( '<a href="%1$s" title="Deactivate %2$s">Deactivate</a>', esc_url( add_query_arg( array(
					'plugin'                 => urlencode( $item['slug'] ),
					'plugin_name'            => urlencode( $item['sanitized_plugin'] ),
					'plugin_source'          => urlencode( $item['source'] ),
					'tgmpa-deactivate'       => 'deactivate-plugin',
					'tgmpa-deactivate-nonce' => wp_create_nonce( 'tgmpa-deactivate' ),
				), admin_url( 'admin.php?page=insight-core' ) ) ), $item['sanitized_plugin'] );
			}

			return $actions;
		}

		// Remove Rev Slider Meta box.
		public function remove_revolution_slider_meta_boxes() {
			remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', 'ic_popup', 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', 'ic_mega_menu', 'normal' );
		}

		public static function clear_log() {
			if ( file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {
				unlink( WP_CONTENT_DIR . '/debug.log' );
			}
		}

		/**
		 * @param mixed $log Anything to write to log.
		 *
		 * Make sure both WP_DEBUG and WP_DEBUG_LOG = true.
		 */
		public static function write_log( $log ) {
			if ( true === WP_DEBUG ) {
				if ( is_array( $log ) || is_object( $log ) ) {
					error_log( print_r( $log, true ) );
				} else {
					error_log( $log );
				}
			}
		}
	}

	InsightCore::instance()->initialize();

	function insight_core_activation_hook() {
		insight_core_update_vc_access_rules();

		insight_core_manage_roles_and_permissions();
	}

	function insight_core_update_vc_access_rules() {
		$pt_array = ( $pt_array = get_option( 'wpb_js_content_types' ) ) ? ( $pt_array ) : array( 'page' );

		if ( ! in_array( 'ic_mega_menu', $pt_array ) ) {
			$pt_array[] = 'ic_mega_menu';
		}

		if ( ! in_array( 'ic_footer', $pt_array ) ) {
			$pt_array[] = 'ic_footer';
		}

		if ( ! in_array( 'ic_popup', $pt_array ) ) {
			$pt_array[] = 'ic_popup';
		}

		// Update user roles
		$user_roles = get_option( 'wp_user_roles' );

		if ( ! empty( $user_roles ) ) {
			foreach ( $user_roles as $key => $value ) {
				$user_roles[ $key ]['capabilities']['vc_access_rules_post_types']              = 'custom';
				$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/page']         = true;
				$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/ic_mega_menu'] = true;
				$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/ic_footer']    = true;
				$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/ic_popup']     = true;
			}
		}

		update_option( 'wpb_js_content_types', $pt_array );
		update_option( 'wp_user_roles', $user_roles );
	}

	function insight_core_manage_roles_and_permissions() {
		$custom_post_type_permission = array(
			'edit_ic_portfolio',
			'read_ic_portfolio',
			'delete_ic_portfolio',
			'delete_ic_portfolios',
			'edit_ic_portfolios',
			'edit_others_ic_portfolios',
			'delete_other_ic_portfolios',
			'publish_ic_portfolios',
			'read_private_ic_portfolios',

			'edit_ic_project',
			'read_ic_project',
			'delete_ic_project',
			'delete_ic_projects',
			'edit_ic_projects',
			'edit_others_ic_projects',
			'delete_other_ic_projects',
			'publish_ic_projects',
			'read_private_ic_projects',

			'edit_ic_service',
			'read_ic_service',
			'delete_ic_service',
			'delete_ic_services',
			'edit_ic_services',
			'edit_others_ic_services',
			'delete_other_ic_services',
			'publish_ic_services',
			'read_private_ic_services',

			'edit_ic_testimonial',
			'read_ic_testimonial',
			'delete_ic_testimonial',
			'delete_ic_testimonials',
			'edit_ic_testimonials',
			'edit_others_ic_testimonials',
			'delete_other_ic_testimonials',
			'publish_ic_testimonials',
			'read_private_ic_testimonials',
		);

		$administrator = get_role( 'administrator' );
		if ( $administrator ) {
			foreach ( $custom_post_type_permission as $cap ) {
				$administrator->add_cap( $cap );
			}
		}
	}

	register_activation_hook( INSIGHT_CORE_FILE, 'insight_core_activation_hook' );
}
