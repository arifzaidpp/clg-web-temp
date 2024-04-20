<?php
/*
Plugin Name: WordPress Importer
Plugin URI: http://wordpress.org/extend/plugins/wordpress-importer/
Description: Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
Author: wordpressdotorg
Author URI: http://wordpress.org/
Version: 0.6.1
Text Domain: wordpress-importer
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/** Display verbose errors */
define( 'IMPORT_DEBUG', false );

// Load Importer API
require_once( ABSPATH . 'wp-admin/includes/import.php' );

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) ) {
		require_once $class_wp_importer;
	}
}

// include WXR file parsers
if ( ! class_exists( 'WXR_Parser' ) ) {
	require_once( dirname( __FILE__ ) . INSIGHT_CORE_DS . 'parsers.php' );
}

/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package    WordPress
 * @subpackage Importer
 */
if ( class_exists( 'WP_Importer' ) ) {

	class InsightCore_Importer extends WP_Importer {

		public $max_wxr_version = 1.2; // max. supported WXR version

		public $id; // WXR attachment ID

		// information to import from WXR file
		public $version;
		public $authors    = array();
		public $posts      = array();
		public $terms      = array();
		public $categories = array();
		public $tags       = array();
		public $base_url   = '';

		// mappings from old information to new
		public $processed_authors    = array();
		public $author_mapping       = array();
		public $processed_terms      = array();
		public $processed_posts      = array();
		public $post_orphans         = array();
		public $processed_menu_items = array();
		public $menu_item_orphans    = array();
		public $missing_menu_items   = array();

		public $fetch_attachments = true;
		public $url_remap         = array();
		public $featured_images   = array();

		public $demo        = '';
		public $imageCount  = 0;
		public $totalImages = 0;
		public $generate_thumb;
		public $importFullDemo;

		function __construct( $importFullDemo ) {
			$this->importFullDemo = $importFullDemo;

			if ( class_exists( 'WooCommerce' ) ) {
				add_action( 'import_start', array( $this, 'import_woocommerce_attributes' ) );
			}

			if ( ! $this->importFullDemo ) {
				$this->demo = isset( $_REQUEST['dummy'] ) ? sanitize_text_field( $_REQUEST['dummy'] ) : '';
			} else {
				$this->demo = isset( $_REQUEST['demo'] ) ? sanitize_text_field( $_REQUEST['demo'] ) : '';
			}
		}

		/**
		 * Check wp-content folder is writeable?
		 *
		 * */
		function check_writeable() {

			ob_start();

			$passed = true;

			if ( defined( 'WP_CONTENT_DIR' ) && ! is_writable( WP_CONTENT_DIR ) ) {
				$passed = false;
				echo '<script type="text/javascript">' . 'text_status(\'\');' . 'is_error(\'' . sprintf( wp_kses( __( 'Could not write files into directory: <strong>%swp-content</strong>', 'insight-core' ), array(
						'strong' => array(),
					) ), str_replace( '\\', '/', ABSPATH ) ) . '\');</script>';
			} else {
				echo '<script type="text/javascript">>document.getElementById("errorImportMsg").innerHTML += \'<div class="notice notice-success">' . sprintf( wp_kses( __( 'Writable Passed: <strong>%s</strong>wp-content', 'insight-core' ), array(
						'strong' => array(),
					) ), str_replace( '\\', '/', ABSPATH ) ) . '</div>\';document.getElementById("errorImportMsg").style.display = "inline-block";</script>';
			}

			echo '<br /><form action="" method="post" class="retry-form">' . '<em>' . esc_html__( 'Tips: Try to change Chmode of folder to 755 or 777', 'insight-core' ) . '</em><br/><br/>' . '<input type="submit" id="submitbtn" class="button button-primary" value="' . esc_html__( 'Click to Retry', 'insight-core' ) . '" />' . '<input type="hidden" value="1" name="import_sample_data">' . '<input type="hidden" value="' . esc_attr( $this->demo ) . '" name="demo"/>' . '</form>';

			$notice = ob_get_contents();
			ob_end_clean();

			if ( $passed === false ) {
				print ( $notice );
			}

			return $passed;
		}

		/**
		 * Download the media package
		 *
		 * @param $_tmppath
		 */
		function download_package( $_tmppath ) {

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			WP_Filesystem();

			$package = null;

			if ( ! is_dir( $_tmppath ) ) {

				$demos = $this->importFullDemo ? apply_filters( 'insight_core_import_demos', array() ) : apply_filters( 'insight_core_import_dummies', array() );

				if ( is_array( $demos ) && isset( $demos[ $this->demo ]['url'] ) ) {
					$url = $demos[ $this->demo ]['url'];
				} else {
					$url = '';
				}

				if ( $this->importFullDemo ) {
					echo '<script type="text/javascript">progress_status("dl");text_status(\'' . esc_html__( 'Downloading package...', 'insight-core' ) . '\');</script>';
				}

				// create temp folder
				$_tmp = wp_tempnam( $url );
				@unlink( $_tmp );

				if ( add_option( 'ic_download_tmp_package', $_tmp ) === false ) {
					update_option( 'ic_download_tmp_package', $_tmp );
				}

				@ob_flush();
				@flush();

				$package = download_url( $url, 18000 );

				if ( ! is_wp_error( $package ) ) {

					if ( ! is_dir( $_tmppath ) ) {

						if ( @mkdir( $_tmppath, 0755 ) ) {

							$unzip = unzip_file( $package, $_tmppath );

							if ( is_wp_error( $unzip ) ) {
								echo '<script type="text/javascript">is_error(\'' .

								     sprintf( wp_kses( __( '<strong>ERROR %s:</strong> Could not extract demo media package. Please contact our support staff.', 'insight-core' ), array(
									     'strong' => array(),
									     'a'      => array(
										     'href'   => array(),
										     'target' => array(),
									     ),
								     ) ), $unzip->get_error_code() ) . '\');</script>';

								if ( WP_DEBUG ) {
									var_dump( $unzip );
								}

								exit;
							}

							@unlink( $package );
							delete_option( 'ic_download_tmp_package' );
							if ( $this->importFullDemo ) {
								?>

								<form action="" method="post" id="form-refresh">
									<input type="hidden" name="import_sample_data" value="ok"/>
									<input type="hidden" value="<?php echo esc_attr( $this->demo ) ?>" name="demo"/>
								</form>

								<script type="text/javascript">
									importing = false;
									jQuery( "#form-refresh" ).get( 0 ).submit();
								</script>

								<?php
								exit;
							}
						}
					}

					@unlink( $package );

					if ( $this->importFullDemo ) {
						echo '<script type="text/javascript">progress_status(0)</script>';
					}

				} else {

					if ( $this->importFullDemo ) {
						echo '<script type="text/javascript">is_error(\'' .

						     sprintf( wp_kses( __( '<strong>ERROR %s:</strong> Could not download demo media package. Please use <a href="%s" target="_blank">this direct link</a> or contact our support staff.', 'insight-core' ), array(
							     'strong' => array(),
							     'a'      => array(
								     'href'   => array(),
								     'target' => array(),
							     ),
						     ) ), $package->get_error_code(), $url ) . '\');</script>';

						if ( WP_DEBUG ) {
							var_dump( $package );
						}

						exit;
					}
				}

				delete_option( 'ic_download_tmp_package' );
			}
		}

		/**
		 * Unpack the media package
		 *
		 * @param $_cpath
		 * @param $_tmppath
		 *
		 * @return bool
		 */
		function unpackage( $_cpath, $_tmppath ) {

			if ( is_dir( $_tmppath ) ) {

				$_current = $this->list_files( $_cpath . 'uploads' );
				$_new     = $this->list_files( $_tmppath );

				foreach ( $_current as $key => $value ) {
					if ( isset( $_new[ $key ] ) ) {
						unset( $_new[ $key ] );
					}
				}

				foreach ( $_new as $key => $value ) {

					if ( $value == 4 ) {
						@mkdir( $_cpath . 'uploads' . INSIGHT_CORE_DS . urldecode( $key ), 0755 );
					} else if ( strpos( $key, '.DS_Store' ) === false ) {

						@copy( $_tmppath . INSIGHT_CORE_DS . urldecode( $key ), $_cpath . 'uploads' . INSIGHT_CORE_DS . urldecode( $key ) );

						@flush();
						@ob_flush();
					}

				}

			} else {
				echo '<script type="text/javascript">is_error(\'' .

				     sprintf( wp_kses( __( '<strong>ERROR %s:</strong> Could not found temporary folder. Please contact our support staff.', 'insight-core' ), array(
					     'strong' => array(),
					     'a'      => array(
						     'href'   => array(),
						     'target' => array(),
					     ),
				     ) ), 'temp_dir_not_found' ) . '\');</script>';

				exit;
			}
		}

		/**
		 * List all files in downloaded folder
		 *
		 * @param      $dir
		 * @param null $DF
		 *
		 * @return array
		 */
		function list_files( $dir, $DF = null ) {

			if ( $DF == null ) {
				$DF = $dir;
			}

			$stack = array();

			if ( is_dir( $dir ) ) {
				$dh = opendir( $dir );
				while ( false !== ( $file = @readdir( $dh ) ) ) {

					$path = $dir . INSIGHT_CORE_DS . $file;

					if ( $file == '.DS_Store' ) {
						unlink( $dir . INSIGHT_CORE_DS . $file );
					} else if ( is_file( $path ) ) {

						$stack[ urlencode( str_replace( $DF . INSIGHT_CORE_DS, '', $path ) ) ] = 1;

					} else if ( is_dir( $path ) && $file != '.' && $file != '..' ) {

						$stack[ urlencode( str_replace( $DF . INSIGHT_CORE_DS, '', $path ) ) ] = 4;

						$stack = $stack + self::list_files( $dir . INSIGHT_CORE_DS . $file, $DF );
					}
				}

			}

			return $stack;
		}

		/**
		 * Read export files
		 *
		 * @param $type
		 *
		 * @return mixed
		 */
		function get_data( $type, $unserialize = true ) {

			$file = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS . $this->demo . INSIGHT_CORE_DS . $type . '.txt';

			if ( ! file_exists( $file ) ) {
				return '';
			}

			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			WP_Filesystem();
			global $wp_filesystem;

			$file_content = $wp_filesystem->get_contents( $file );

			return $unserialize ? @unserialize( $file_content ) : $file_content;
		}

		/**
		 * Page Options
		 *
		 * @return array
		 */
		function import_page_options() {

			$pages = $this->get_data( 'page_options' );

			if ( is_array( $pages ) ) {

				if ( ! empty( $pages['show_on_front'] ) ) {
					update_option( 'show_on_front', $pages['show_on_front'] );
				}

				if ( ! empty( $pages['page_on_front'] ) ) {
					$page = get_page_by_title( $pages['page_on_front'] );

					update_option( 'page_on_front', $page->ID );
				}

				if ( ! empty( $pages['page_for_posts'] ) ) {
					$page = get_page_by_title( $pages['page_for_posts'] );

					update_option( 'page_for_posts', $page->ID );
				}

				// Move Hello World post to trash
				wp_trash_post( 1 );

				// Move Sample Page to trash
				wp_trash_post( 2 );
			}
		}

		/**
		 * Get available widgets in current site
		 *
		 * @return array
		 */
		function available_widgets() {
			global $wp_registered_widget_controls;

			$widget_controls = $wp_registered_widget_controls;

			$available_widgets = array();

			foreach ( $widget_controls as $widget ) {

				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) { // no dupes

					$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
					$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];

				}

			}

			return $available_widgets;
		}

		/**
		 * Read widget logic file
		 *
		 * @return array|mixed|void
		 */
		function read_widget_logic_file() {
			// Widget Logic
			$widget_logic_file = INSIGHT_CORE_THEME_DIR . '/assets/import/' . $this->demo . '/widget_logic_options.txt';

			if ( file_exists( $widget_logic_file ) ) {
				global $wl_options;

				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				WP_Filesystem();
				global $wp_filesystem;

				$import = explode( "\n", $wp_filesystem->get_contents( $widget_logic_file ) );

				if ( trim( array_shift( $import ) ) == "[START=WIDGET LOGIC OPTIONS]" && trim( array_pop( $import ) ) == "[STOP=WIDGET LOGIC OPTIONS]" ) {
					foreach ( $import as $import_option ) {

						list( $key, $value ) = explode( "\t", $import_option );
						$wl_options[ $key ] = json_decode( $value );

					}
				}

				return $wl_options;
			}
		}

		/**
		 * Import sidebars
		 *
		 */
		function import_sidebars() {

			if ( class_exists( 'Kungfu_Sidebars' ) ) {

				$data = json_decode( $this->get_data( 'sidebars', false ) );

				if ( $this->importFullDemo ) {
					echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Sidebars</b>', 'insight-core' ), array( 'b' => array() ) ) . '\' );</script>';
				}

				if ( $data ) {
					update_option( 'kungfu_sidebars', (array) $data );

					$kf_sidebars = new Kungfu_Sidebars();
					$kf_sidebars->widgets_init();
				}
			}
		}

		/**
		 * Import widgets
		 *
		 * @return array
		 */
		function import_widgets() {

			global $wp_registered_sidebars;

			$data = json_decode( $this->get_data( 'widgets', false ) );
			update_option( 'sidebars_widgets', array() );

			if ( empty( $data ) || ! is_object( $data ) ) {
				echo '<script type="text/javascript">is_error(\'' .

				     sprintf( wp_kses( __( '<strong>ERROR %s:</strong> Could not read the widget sample data file. Please contact our support staff.', 'insight-core' ), array(
					     'strong' => array(),
					     'a'      => array(
						     'href'   => array(),
						     'target' => array(),
					     ),
				     ) ), 'widget_files_wrong' ) . '\');' . '</script>';

				return;
			}

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Widgets</b>' ), array( 'b' => array() ) ) . '\' );</script>';
			}

			// Get all available widgets site supports
			$available_widgets = $this->available_widgets();

			// Get all existing widget instances
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}

			// Get widget logic sample data
			$wl_options     = $this->read_widget_logic_file( $this->demo );
			$new_wl_options = array();

			foreach ( $data as $sidebar_id => $widgets ) {
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}

				if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
					$use_sidebar_id = $sidebar_id;
				} else {
					$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
				}

				foreach ( $widgets as $widget_instance_id => $widget ) {
					$fail = false;

					// Get id_base (remove -# from end) and instance ID number
					$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );

					// Does site support this widget?
					if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
						$fail = true;
					}

					$widget = json_decode( json_encode( $widget ), true );

					if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

						// Get existing widgets in this sidebar
						$sidebars_widgets = get_option( 'sidebars_widgets' );
						$sidebar_widgets  = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // check Inactive if that's where will go

						// Loop widgets with ID base
						$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
						foreach ( $single_widget_instances as $check_id => $check_widget ) {

							// Is widget in same sidebar and has identical settings?
							if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

								$fail = true;
								break;

							}

						}
					}

					if ( ! $fail ) {

						$single_widget_instances   = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
						$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
						$single_widget_instances[] = $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number                             = 1;
							$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );
						// Assign widget instance to sidebar
						$sidebars_widgets                      = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
						$new_instance_id                       = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
						$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id; // add new instance to sidebar
						update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

						// Update widget logic data
						if ( isset( $wl_options[ $widget_instance_id ] ) ) {
							$new_wl_options[ $new_instance_id ] = $wl_options[ $widget_instance_id ];
						}
					}
				}
			}

			if ( ! empty( $new_wl_options ) ) {
				update_option( 'widget_logic', $new_wl_options );
			}
		}

		/**
		 * Import menus
		 *
		 * @return array
		 */
		function import_menus() {

			global $wpdb;

			$insight_core_terms_table = $wpdb->prefix . "terms";
			$menu_data                = $this->get_data( 'menus' );
			$menu_array               = array();

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Menus</b>' ), array( 'b' => array() ) ) . '\' );</script>';
			}

			if ( ! empty( $menu_data ) ) {

				foreach ( $menu_data as $registered_menu => $menu_slug ) {

					$term_rows = $wpdb->get_results( "SELECT * FROM $insight_core_terms_table where slug='{$menu_slug}'", ARRAY_A );

					if ( isset( $term_rows[0]['term_id'] ) ) {
						$term_id_by_slug = $term_rows[0]['term_id'];
					} else {
						$term_id_by_slug = null;
					}

					$menu_array[ $registered_menu ] = $term_id_by_slug;
				}
				set_theme_mod( 'nav_menu_locations', array_map( 'absint', $menu_array ) );

			}
		}

		/**
		 * Import Customizer options
		 *
		 */
		function import_customizer_options() {

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Customizer Options</b>', 'insight-core' ), array( 'b' => array() ) ) . '\' );</script>';
			}

			$options = $this->get_data( 'customizer' );

			if ( ! empty( $options ) ) {

				$nav_menu_locations = get_theme_mod( 'nav_menu_locations' );

				// Reset customizer options
				remove_theme_mods();

				$options['nav_menu_locations'] = $nav_menu_locations;

				foreach ( $options as $name => $value ) {
					set_theme_mod( $name, $value );
				}
			}
		}

		/**
		 * Import WooCommerce
		 *
		 * @return array
		 */
		function import_woocommerce_image_sizes() {

			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}

			$wc = $this->get_data( 'woocommerce' );

			if ( is_array( $wc ) && ! empty( $wc['images'] ) ) {
				update_option( 'shop_catalog_image_size', $wc['images']['catalog'] );
				update_option( 'shop_thumbnail_image_size', $wc['images']['thumbnail'] );
				update_option( 'shop_single_image_size', $wc['images']['single'] );

				global $_wp_additional_image_sizes;
				if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {

					if ( isset( $_wp_additional_image_sizes['shop_thumbnail'] ) ) {
						$_wp_additional_image_sizes['shop_thumbnail'] = array(
							'width'  => $wc['images']['thumbnail']['width'],
							'height' => $wc['images']['thumbnail']['height'],
							'crop'   => $wc['images']['thumbnail']['crop'],
						);
					}

					if ( isset( $_wp_additional_image_sizes['shop_catalog'] ) ) {
						$_wp_additional_image_sizes['shop_catalog'] = array(
							'width'  => $wc['images']['catalog']['width'],
							'height' => $wc['images']['catalog']['height'],
							'crop'   => $wc['images']['catalog']['crop'],
						);
					}

					if ( isset( $_wp_additional_image_sizes['shop_single'] ) ) {
						$_wp_additional_image_sizes['shop_single'] = array(
							'width'  => $wc['images']['single']['width'],
							'height' => $wc['images']['single']['height'],
							'crop'   => $wc['images']['single']['crop'],
						);
					}
				}
			}

		}

		/**
		 * Import WooCommerce pages
		 *
		 * @return array
		 */
		function import_woocommerce_pages() {

			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>WooCommerce</b>' ), array( 'b' => array() ) ) . '\' );</script>';
			}

			$woopages = array(
				'woocommerce_shop_page_id'      => 'Shop',
				'woocommerce_cart_page_id'      => 'Cart',
				'woocommerce_checkout_page_id'  => 'Checkout',
				'woocommerce_myaccount_page_id' => 'My Account',
			);

			foreach ( $woopages as $woo_page_name => $woo_page_title ) {
				$woopage = get_page_by_title( $woo_page_title );
				if ( isset( $woopage ) && $woopage->ID ) {
					update_option( $woo_page_name, $woopage->ID );
				}
			}

			$notices = array_diff( get_option( 'woocommerce_admin_notices', array() ), array(
				'install',
				'update',
			) );
			update_option( 'woocommerce_admin_notices', $notices );
			delete_option( '_wc_needs_pages' );
			delete_transient( '_wc_activation_redirect' );
		}

		/**
		 * Import WooCommerce Attributes
		 * Clone from function post_importer_compatibility() in /woocommerce/includes/admin/class-wc-admin-importer.php file
		 */
		function import_woocommerce_attributes() {
			global $wpdb;

			$_x_path = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS . $this->demo . INSIGHT_CORE_DS;
			$file    = $_x_path . 'content.xml';

			if ( ! file_exists( $file ) ) {
				wp_die( sprintf( esc_html__( 'Can not find import file: %s' ), $file ) );

				return;
			}

			$import_data = $this->parse( $file );

			$attribute_datas = $this->get_data( 'woocommerce_attributes' );

			if ( isset( $import_data['posts'] ) ) {
				$posts = $import_data['posts'];

				if ( $posts && sizeof( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						if ( 'product' === $post['post_type'] ) {

							if ( ! empty( $post['terms'] ) ) {
								foreach ( $post['terms'] as $term ) {
									if ( strstr( $term['domain'], 'pa_' ) ) {
										if ( ! taxonomy_exists( $term['domain'] ) ) {
											$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

											// Create the taxonomy
											if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
												$attribute = array(
													'attribute_label'   => $attribute_name,
													'attribute_name'    => $attribute_name,
													'attribute_type'    => 'select',
													'attribute_orderby' => 'menu_order',
													'attribute_public'  => 0,
												);

												if ( ! empty( $attribute_datas ) && ! empty( $attribute_datas[ $attribute_name ] ) ) {
													$current_attribute = $attribute_datas[ $attribute_name ];

													$attribute = wp_parse_args( [
														'attribute_label'   => $current_attribute['attribute_label'],
														'attribute_type'    => $current_attribute['attribute_type'],
														'attribute_orderby' => $current_attribute['attribute_orderby'],
													], $attribute );
												}

												$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
												delete_transient( 'wc_attribute_taxonomies' );
											}

											// Register the taxonomy now so that the import works!
											register_taxonomy( $term['domain'], apply_filters( 'woocommerce_taxonomy_objects_' . $term['domain'], array( 'product' ) ), apply_filters( 'woocommerce_taxonomy_args_' . $term['domain'], array(
												'hierarchical' => true,
												'show_ui'      => false,
												'query_var'    => true,
												'rewrite'      => false,
											) ) );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Import Revolution sliders
		 *
		 */
		function import_rev_sliders() {

			if ( ! class_exists( 'RevSliderAdmin' ) ) {
				return;
			}

			global $wpdb;

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Revolution Slider</b>' ), array( 'b' => array() ) ) . '\' );</script>';
			}

			if ( get_option( 'insight_core_import_revsliders' ) == false && $this->importFullDemo ) {
				$xuri     = str_replace( '/', '\\/', INSIGHT_CORE_SITE_URI ) . '\\';
				$templine = '';
				$lines    = $this->get_data( 'rev_sliders', false );

				if ( ! empty( $lines ) ) {
					$lines = explode( "\n", $lines );

					foreach ( $lines as $line ) {
						if ( substr( $line, 0, 2 ) == '--' || $line == '' ) {
							continue;
						}

						$templine .= $line;
						if ( substr( trim( $line ), -1, 1 ) == ';' ) {
							ob_start();
							$wpdb->query( str_replace( array(
								'%INSIGHT_CORE_SITE_URI%',
								'wp_',
							), array(
								$xuri,
								$wpdb->prefix,
							), $templine ), false );
							$templine = '';
							ob_end_clean();
						}
					}
				}
			} else {

				$rev_files = glob( INSIGHT_CORE_THEME_DIR . '/assets/import/' . $this->demo . '/rev_sliders/*.zip' );

				if ( ! empty( $rev_files ) ) {
					foreach ( $rev_files as $rev_file ) {
						$_FILES['import_file']['error']    = UPLOAD_ERR_OK;
						$_FILES['import_file']['tmp_name'] = $rev_file;
						ob_start();
						$slider = new RevSlider();
						$slider->importSliderFromPost( true, 'none' );
						ob_end_clean();
					}
				}
			}
			InsightCore::update_option_count( 'insight_core_import_revsliders' );
		}

		/**
		 * Import Essential Grid
		 *
		 */
		function import_essential_grid() {

			if ( ! class_exists( 'Essential_Grid' ) ) {
				return;
			}

			require_once( plugin_dir_path( 'essential-grid/essential-grid.php' ) );

			$file = INSIGHT_CORE_THEME_DIR . '/assets/import/' . $this->demo . '/essential_grid.txt';

			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			WP_Filesystem();
			global $wp_filesystem;

			$es_data = json_decode( $wp_filesystem->get_contents( $file ), true );

			try {

				if ( $this->importFullDemo ) {
					echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Essential Grid</b>' ), array( 'b' => array() ) ) . '\' );</script>';
				}

				$im = new Essential_Grid_Import();

				$overwriteData = array(
					'global-styles-overwrite' => 'overwrite',
				);

				// Create Overwrite & Ids data
				$skins        = @$es_data['skins'];
				$export_skins = array();
				if ( ! empty( $skins ) && is_array( $skins ) ) {
					foreach ( $skins as $skin ) {
						$export_skins[]                                   = $skin['id'];
						$overwriteData[ 'skin-overwrite-' . $skin['id'] ] = 'overwrite';
					}
				}

				$export_navigation_skins = array();
				$navigation_skins        = @$es_data['navigation-skins'];

				foreach ( (array) $navigation_skins as $nav_skin ) {
					$export_navigation_skins[]                                = $nav_skin['id'];
					$overwriteData[ 'nav-skin-overwrite-' . $nav_skin['id'] ] = 'overwrite';
				}

				$export_grids = array();
				$grids        = @$es_data['grids'];
				if ( ! empty( $grids ) && is_array( $grids ) ) {
					foreach ( $grids as $grid ) {
						$export_grids[]                                   = $grid['id'];
						$overwriteData[ 'grid-overwrite-' . $grid['id'] ] = 'overwrite';
					}
				}

				$export_elements = array();
				$elements        = @$es_data['elements'];
				if ( ! empty( $elements ) && is_array( $elements ) ) {
					foreach ( $elements as $element ) {
						$export_elements[]                                       = $element['id'];
						$overwriteData[ 'elements-overwrite-' . $element['id'] ] = 'overwrite';
					}
				}

				$export_custom_meta = array();
				$custom_metas       = @$es_data['custom-meta'];
				if ( ! empty( $custom_metas ) && is_array( $custom_metas ) ) {
					foreach ( $custom_metas as $custom_meta ) {
						$export_custom_meta[]                                               = $custom_meta['handle'];
						$overwriteData[ 'custom-meta-overwrite-' . $custom_meta['handle'] ] = 'overwrite';
					}
				}

				$export_punch_fonts = array();
				$custom_fonts       = @$es_data['punch-fonts'];
				if ( ! empty( $custom_fonts ) && is_array( $custom_fonts ) ) {
					foreach ( $custom_fonts as $custom_font ) {
						$export_punch_fonts[]                                               = $custom_font['handle'];
						$overwriteData[ 'punch-fonts-overwrite-' . $custom_font['handle'] ] = 'overwrite';
					}
				}

				$im->set_overwrite_data( $overwriteData ); //set overwrite data global to class

				// Import data
				$skins = @$es_data['skins'];
				if ( ! empty( $skins ) && is_array( $skins ) ) {
					if ( ! empty( $skins ) ) {
						$skins_imported = $im->import_skins( $skins, $export_skins );
					}
				}

				$navigation_skins = @$es_data['navigation-skins'];
				if ( ! empty( $navigation_skins ) && is_array( $navigation_skins ) ) {
					if ( ! empty( $navigation_skins ) ) {
						$navigation_skins_imported = $im->import_navigation_skins( @$navigation_skins, $export_navigation_skins );
					}
				}

				$grids = @$es_data['grids'];
				if ( ! empty( $grids ) && is_array( $grids ) ) {
					if ( ! empty( $grids ) ) {
						$grids_imported = $im->import_grids( $grids, $export_grids );
					}
				}

				$elements = @$es_data['elements'];
				if ( ! empty( $elements ) && is_array( $elements ) ) {
					if ( ! empty( $elements ) ) {
						$elements_imported = $im->import_elements( @$elements, $export_elements );
					}
				}

				$custom_metas = @$es_data['custom-meta'];
				if ( ! empty( $custom_metas ) && is_array( $custom_metas ) ) {
					if ( ! empty( $custom_metas ) ) {
						$custom_metas_imported = $im->import_custom_meta( $custom_metas, $export_custom_meta );
					}
				}

				$custom_fonts = @$es_data['punch-fonts'];
				if ( ! empty( $custom_fonts ) && is_array( $custom_fonts ) ) {
					if ( ! empty( $custom_fonts ) ) {
						$custom_fonts_imported = $im->import_punch_fonts( $custom_fonts, $export_punch_fonts );
					}
				}

				if ( true ) {
					$global_css = @$es_data['global-css'];

					$tglobal_css = stripslashes( $global_css );
					if ( empty( $tglobal_css ) ) {
						$tglobal_css = $global_css;
					}

					$global_styles_imported = $im->import_global_styles( $tglobal_css );
				}
			} catch ( Exception $ex ) {
				echo '<script type="text/javascript">is_error(\'' .

				     sprintf( wp_kses( __( '<strong>ERROR %s:</strong> Could not download demo media package. Please contact our support staff.', 'insight-core' ), array(
					     'strong' => array(),
					     'a'      => array(
						     'href'   => array(),
						     'target' => array(),
					     ),
				     ) ), $ex->getCode() ) . '\');</script>';

				if ( WP_DEBUG ) {
					var_dump( $ex );
				}

				return;
			}
		}

		/**
		 * Fix for Ess Grid 2.x
		 */
		function fix_essential_grid() {
			if ( ! class_exists( 'Essential_Grid' ) ) {
				return;
			}

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Fix <b>Essential Grid</b> Filter' ), array( 'b' => array() ) ) . '\' );</script>';
			}

			global $wpdb;
			global $table_prefix;
			$myrows = $wpdb->get_results( 'SELECT id, postparams, params FROM ' . $table_prefix . 'eg_grids' );
			foreach ( $myrows as $myrow ) {
				$postparams = json_decode( $myrow->postparams );
				if ( isset( $postparams->post_category ) && ( $postparams->post_category != "" ) ) {
					$categories                  = explode( ',', $postparams->post_category );
					$params                      = json_decode( $myrow->params );
					$params->{'filter-selected'} = $categories;
					$wpdb->update( $table_prefix . 'eg_grids', array(
						'params' => json_encode( $params ),
					), array( 'ID' => $myrow->id ), array(
						'%s',
					), array( '%d' ) );
				}
			}
		}

		/**
		 * Registered callback function for the WordPress Importer
		 *
		 * Manages the three separate stages of the WXR import process
		 */
		function dispatch() {

			if ( ! $this->check_writeable() ) {
				exit;
			}

			$_cpath = WP_CONTENT_DIR . INSIGHT_CORE_DS;

			// What is the demo you want to import?
			$_x_path = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS . $this->demo . INSIGHT_CORE_DS;

			$_tmppath = $_cpath . INSIGHT_CORE_THEME_SLUG . '-' . $this->demo . '-tmp';

			@ini_set( 'max_execution_time', 3000 );
			@ini_set( 'output_buffering', 'on' );
			@ini_set( 'zlib.output_compression', 0 );
			@ob_implicit_flush();

			delete_option( 'ic_download_tmp_package' );

			$file = $_x_path . 'content.xml';

			if ( ! file_exists( $file ) ) {
				wp_die( sprintf( esc_html__( 'Can not find import file: %s' ), $file ) );

				return;
			}

			// START DOWNLOAD IMAGES
			$this->download_package( $_tmppath );

			//  FINISH DOWNLOAD AND UNPACKAGE
			$this->unpackage( $_cpath, $_tmppath );

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">progress_status(0);text_status( \'' . esc_html__( 'Preparing for add media...', 'insight-core' ) . '\' );</script>';
			}

			@ob_flush();
			@flush();

			// Import wp contents
			set_time_limit( 0 );

			$this->import_woocommerce_image_sizes();

			$this->import( $file );

			$this->import_page_options();

			$this->import_sidebars();

			$this->import_widgets();

			$this->import_menus();

			$this->import_customizer_options();

			$this->import_woocommerce_pages();

			$this->import_essential_grid();

			$this->fix_essential_grid();

			$this->import_rev_sliders();

			InsightCore::update_option_count( INSIGHT_CORE_THEME_SLUG . '_' . $this->demo . '_imported' );
		}

		/**
		 * The main controller for the actual import stage.
		 *
		 * @param string $file Path to the WXR file for importing
		 */
		function import( $file ) {

			add_filter( 'import_post_meta_key', array(
				$this,
				'is_valid_meta_key',
			) );
			add_filter( 'http_request_timeout', array(
				$this,
				'bump_request_timeout',
			) );

			$this->import_start( $file );

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . esc_html__( 'Get author mapping', 'insight-core' ) . '\' );</script>';
				@ob_flush();
				@flush();
			}

			$this->get_author_mapping();

			wp_suspend_cache_invalidation( true );

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Categories</b>' ), array( 'b' => array() ) ) . '\' );</script>';
				@ob_flush();
				@flush();
			}

			$this->process_categories();

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Tags</b>' ), array( 'b' => array() ) ) . '\' );</script>';
				@ob_flush();
				@flush();
			}

			$this->process_tags();

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Terms</b>' ), array( 'b' => array() ) ) . '\' );</script>';
				@ob_flush();
				@flush();
			}

			$this->process_terms();

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Posts</b>' ), array( 'b' => array() ) ) . '\' );</script>';
				@ob_flush();
				@flush();
			}

			$this->process_posts();
			wp_suspend_cache_invalidation( false );

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">text_status( \'' . esc_html__( 'Updating incorrect/missing infomation in the database...', 'insight-core' ) . '\');</script>';
				@ob_flush();
				@flush();
			}

			// update incorrect/missing information in the DB
			$this->backfill_parents();
			$this->backfill_attachment_urls();
			$this->remap_featured_images();

			$this->import_end();
		}

		/**
		 * Parses the WXR file and prepares us for the task of processing parsed data
		 *
		 * @param string $file Path to the WXR file for importing
		 */
		function import_start( $file ) {
			if ( ! is_file( $file ) ) {
				echo '<p><strong>' . __( 'Sorry, there has been an error.', 'insight-core' ) . '</strong><br />';
				echo __( 'The file does not exist, please try again.', 'insight-core' ) . '</p>';
				$this->footer();
				die();
			}

			$import_data = $this->parse( $file );

			if ( is_wp_error( $import_data ) ) {
				echo '<p><strong>' . __( 'Sorry, there has been an error.', 'insight-core' ) . '</strong><br />';
				print( $import_data->get_error_message() ) . '</p>';
				$this->footer();
				die();
			}

			$this->version = $import_data['version'];
			$this->get_authors_from_import( $import_data );
			$this->posts      = $import_data['posts'];
			$this->terms      = $import_data['terms'];
			$this->categories = $import_data['categories'];
			$this->tags       = $import_data['tags'];
			$this->base_url   = esc_url( $import_data['base_url'] );

			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );

			do_action( 'import_start' );
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end() {
			wp_import_cleanup( $this->id );

			wp_cache_flush();
			foreach ( get_taxonomies() as $tax ) {
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}

			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );

			do_action( 'import_end' );
		}

		/**
		 * Handles the WXR upload and initial parsing of the file to prepare for
		 * displaying author import options
		 *
		 * @return bool False if error uploading or invalid file, true otherwise
		 */
		function handle_upload() {
			$file = wp_import_handle_upload();

			if ( isset( $file['error'] ) ) {
				echo '<p><strong>' . __( 'Sorry, there has been an error.', 'insight-core' ) . '</strong><br />';
				print( $file['error'] ) . '</p>';

				return false;
			} else if ( ! file_exists( $file['file'] ) ) {
				echo '<p><strong>' . __( 'Sorry, there has been an error.', 'insight-core' ) . '</strong><br />';
				printf( __( 'The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', 'insight-core' ), $file['file'] );
				echo '</p>';

				return false;
			}

			$this->id    = (int) $file['id'];
			$import_data = $this->parse( $file['file'] );
			if ( is_wp_error( $import_data ) ) {
				echo '<p><strong>' . __( 'Sorry, there has been an error.', 'insight-core' ) . '</strong><br />';
				print( $import_data->get_error_message() ) . '</p>';

				return false;
			}

			$this->version = $import_data['version'];
			if ( $this->version > $this->max_wxr_version ) {
				echo '<div class="error"><p><strong>';
				printf( __( 'This WXR file (version %s) may not be supported by this version of the importer. Please consider updating.', 'insight-core' ), esc_html( $import_data['version'] ) );
				echo '</strong></p></div>';
			}

			$this->get_authors_from_import( $import_data );

			return true;
		}

		/**
		 * Retrieve authors from parsed WXR data
		 *
		 * Uses the provided author information from WXR 1.1 files
		 * or extracts info from each post for WXR 1.0 files
		 *
		 * @param array $import_data Data returned by a WXR parser
		 */
		function get_authors_from_import( $import_data ) {
			if ( ! empty( $import_data['authors'] ) ) {
				$this->authors = $import_data['authors'];
				// no author information, grab it from the posts
			} else {
				foreach ( $import_data['posts'] as $post ) {
					$login = sanitize_user( $post['post_author'], true );
					if ( empty( $login ) ) {

						continue;
					}

					if ( ! isset( $this->authors[ $login ] ) ) {
						$this->authors[ $login ] = array(
							'author_login'        => $login,
							'author_display_name' => $post['post_author'],
						);
					}
				}
			}
		}

		/**
		 * Display pre-import options, author importing/mapping and option to
		 * fetch attachments
		 */
		function import_options() {
			$j = 0;
			?>
			<form action="<?php echo admin_url( 'admin.php?import=wordpress&amp;step=2' ); ?>" method="post">
				<?php wp_nonce_field( 'import-wordpress' ); ?>
				<input type="hidden" name="import_id" value="<?php echo esc_attr( $this->id ); ?>"/>

				<?php if ( ! empty( $this->authors ) ) : ?>
					<h3><?php _e( 'Assign Authors', 'insight-core' ); ?></h3>
					<p><?php _e( 'To make it easier for you to edit and save the imported content, you may want to reassign the author of the imported item to an existing user of this site. For example, you may want to import all the entries as <code>admin</code>s entries.', 'insight-core' ); ?></p>
					<?php if ( $this->allow_create_users() ) : ?>
						<p><?php printf( __( 'If a new user is created by WordPress, a new password will be randomly generated and the new user&#8217;s role will be set as %s. Manually changing the new user&#8217;s details will be necessary.', 'insight-core' ), esc_html( get_option( 'default_role' ) ) ); ?></p>
					<?php endif; ?>
					<ol id="authors">
						<?php foreach ( $this->authors as $author ) : ?>
							<li><?php $this->author_select( $j++, $author ); ?></li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>

				<?php if ( $this->allow_fetch_attachments() ) : ?>
					<h3><?php _e( 'Import Attachments', 'insight-core' ); ?></h3>
					<p>
						<input type="checkbox" value="1" name="fetch_attachments" id="import-attachments"/>
						<label
							for="import-attachments"><?php _e( 'Download and import file attachments', 'insight-core' ); ?></label>
					</p>
				<?php endif; ?>

				<p class="submit"><input type="submit" class="button"
				                         value="<?php esc_attr_e( 'Submit', 'insight-core' ); ?>"/></p>
			</form>
			<?php
		}

		/**
		 * Display import options for an individual author. That is, either create
		 * a new user based on import info or map to an existing user
		 *
		 * @param int   $n      Index for each author in the form
		 * @param array $author Author information, e.g. login, display name, email
		 */
		function author_select( $n, $author ) {
			_e( 'Import author:', 'insight-core' );
			echo ' <strong>' . esc_html( $author['author_display_name'] );
			if ( $this->version != '1.0' ) {
				echo ' (' . esc_html( $author['author_login'] ) . ')';
			}
			echo '</strong><br />';

			if ( $this->version != '1.0' ) {
				echo '<div style="margin-left:18px">';
			}

			$create_users = $this->allow_create_users();
			if ( $create_users ) {
				if ( $this->version != '1.0' ) {
					_e( 'or create new user with login name:', 'insight-core' );
					$value = '';
				} else {
					_e( 'as a new user:', 'insight-core' );
					$value = esc_attr( sanitize_user( $author['author_login'], true ) );
				}

				echo ' <input type="text" name="user_new[' . $n . ']" value="' . $value . '" /><br />';
			}

			if ( ! $create_users && $this->version == '1.0' ) {
				_e( 'assign posts to an existing user:', 'insight-core' );
			} else {
				_e( 'or assign posts to an existing user:', 'insight-core' );
			}
			wp_dropdown_users( array(
				'name'            => "user_map[$n]",
				'multi'           => true,
				'show_option_all' => __( '- Select -', 'insight-core' ),
			) );
			echo '<input type="hidden" name="imported_authors[' . $n . ']" value="' . esc_attr( $author['author_login'] ) . '" />';

			if ( $this->version != '1.0' ) {
				echo '</div>';
			}
		}

		/**
		 * Map old author logins to local user IDs based on decisions made
		 * in import options form. Can map to an existing user, create a new user
		 * or falls back to the current user in case of error with either of the previous
		 */
		function get_author_mapping() {
			if ( ! isset( $_POST['imported_authors'] ) ) {
				return;
			}

			$create_users = $this->allow_create_users();

			foreach ( (array) $_POST['imported_authors'] as $i => $old_login ) {
				// Multisite adds strtolower to sanitize_user. Need to sanitize here to stop breakage in process_posts.
				$santized_old_login = sanitize_user( $old_login, true );
				$old_id             = isset( $this->authors[ $old_login ]['author_id'] ) ? intval( $this->authors[ $old_login ]['author_id'] ) : false;

				if ( ! empty( $_POST['user_map'][ $i ] ) ) {
					$user = get_userdata( intval( $_POST['user_map'][ $i ] ) );
					if ( isset( $user->ID ) ) {
						if ( $old_id ) {
							$this->processed_authors[ $old_id ] = $user->ID;
						}
						$this->author_mapping[ $santized_old_login ] = $user->ID;
					}
				} else if ( $create_users ) {
					if ( ! empty( $_POST['user_new'][ $i ] ) ) {
						$user_id = wp_create_user( $_POST['user_new'][ $i ], wp_generate_password() );
					} else if ( $this->version != '1.0' ) {
						$user_data = array(
							'user_login'   => $old_login,
							'user_pass'    => wp_generate_password(),
							'user_email'   => isset( $this->authors[ $old_login ]['author_email'] ) ? $this->authors[ $old_login ]['author_email'] : '',
							'display_name' => $this->authors[ $old_login ]['author_display_name'],
							'first_name'   => isset( $this->authors[ $old_login ]['author_first_name'] ) ? $this->authors[ $old_login ]['author_first_name'] : '',
							'last_name'    => isset( $this->authors[ $old_login ]['author_last_name'] ) ? $this->authors[ $old_login ]['author_last_name'] : '',
						);
						$user_id   = wp_insert_user( $user_data );
					}

					if ( ! is_wp_error( $user_id ) ) {
						if ( $old_id ) {
							$this->processed_authors[ $old_id ] = $user_id;
						}
						$this->author_mapping[ $santized_old_login ] = $user_id;
					} else {
						printf( __( 'Failed to create new user for %s. Their posts will be attributed to the current user.', 'insight-core' ), esc_html( $this->authors[ $old_login ]['author_display_name'] ) );
						if ( defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
							echo ' ' . $user_id->get_error_message();
						}
						echo '<br />';
					}
				}

				// failsafe: if the user_id was invalid, default to the current user
				if ( ! isset( $this->author_mapping[ $santized_old_login ] ) ) {
					if ( $old_id ) {
						$this->processed_authors[ $old_id ] = (int) get_current_user_id();
					}
					$this->author_mapping[ $santized_old_login ] = (int) get_current_user_id();
				}
			}
		}

		/**
		 * Create new categories based on import information
		 *
		 * Doesn't create a new category if its slug already exists
		 */
		function process_categories() {

			global $wpdb;

			$this->categories = apply_filters( 'wp_import_categories', $this->categories );

			if ( empty( $this->categories ) ) {
				return;
			}

			foreach ( $this->categories as $cat ) {
				// if the category already exists leave it alone
				$term_id = term_exists( $cat['category_nicename'], 'category' );
				if ( $term_id ) {
					if ( is_array( $term_id ) ) {
						$term_id = $term_id['term_id'];
					}
					if ( isset( $cat['term_id'] ) ) {
						$this->processed_terms[ intval( $cat['term_id'] ) ] = (int) $term_id;
					}
					continue;
				}

				$category_parent      = empty( $cat['category_parent'] ) ? 0 : category_exists( $cat['category_parent'] );
				$category_description = isset( $cat['category_description'] ) ? $cat['category_description'] : '';

				$catarr = array(
					'category_nicename'    => $cat['category_nicename'],
					'category_parent'      => $category_parent,
					'cat_name'             => $cat['cat_name'],
					'category_description' => $category_description,
				);

				$parent_term_id = $category_parent;
				wp_insert_term( 'Child Category', // the term
					'category', // the taxonomy
					array(
						'parent' => $parent_term_id,
					) );

				$wpdb->query( $wpdb->prepare( "REPLACE INTO  `" . $wpdb->terms . "` ( term_id, name, slug, term_group ) VALUES ( %d, %s, %s, 0 )", $cat['term_id'], $cat['cat_name'], $cat['category_nicename'] ) );

				$wpdb->query( $wpdb->prepare( "REPLACE INTO  `" . $wpdb->term_taxonomy . "` ( term_id, taxonomy ) VALUES ( %d, %s )", $cat['term_id'], 'category' ) );

				$this->processed_terms[ intval( $cat['term_id'] ) ] = $cat['term_id'];

				continue;

				$id = wp_insert_category( $catarr );
				if ( ! is_wp_error( $id ) ) {

					if ( isset( $cat['term_id'] ) ) {

						$this->processed_terms[ intval( $cat['term_id'] ) ] = $id;

					}
				} else {

					continue;
				}
			}

			unset( $this->categories );
		}

		/**
		 * Create new post tags based on import information
		 *
		 * Doesn't create a tag if its slug already exists
		 */
		function process_tags() {

			$this->tags = apply_filters( 'wp_import_tags', $this->tags );

			if ( empty( $this->tags ) ) {
				return;
			}

			foreach ( $this->tags as $tag ) {
				// if the tag already exists leave it alone
				$term_id = term_exists( $tag['tag_slug'], 'post_tag' );
				if ( $term_id ) {
					if ( is_array( $term_id ) ) {
						$term_id = $term_id['term_id'];
					}
					if ( isset( $tag['term_id'] ) ) {
						$this->processed_terms[ intval( $tag['term_id'] ) ] = (int) $term_id;
					}
					continue;
				}

				$tag_desc = isset( $tag['tag_description'] ) ? $tag['tag_description'] : '';
				$tagarr   = array(
					'slug'        => $tag['tag_slug'],
					'description' => $tag_desc,
				);

				$id = wp_insert_term( $tag['tag_name'], 'post_tag', $tagarr );
				if ( ! is_wp_error( $id ) ) {
					if ( isset( $tag['term_id'] ) ) {
						$this->processed_terms[ intval( $tag['term_id'] ) ] = $id['term_id'];
					}
				} else {

					continue;
				}
			}

			unset( $this->tags );
		}

		/**
		 * Create new terms based on import information
		 *
		 * Doesn't create a term its slug already exists
		 */
		function process_terms() {

			$this->terms = apply_filters( 'wp_import_terms', $this->terms );

			if ( empty( $this->terms ) ) {
				return;
			}

			foreach ( $this->terms as $term ) {
				// if the term already exists in the correct taxonomy leave it alone
				if ( ! isset( $term['slug'] ) || ! isset( $term['term_taxonomy'] ) ) {
					continue;
				}
				$term_id = term_exists( $term['slug'], $term['term_taxonomy'] );
				if ( $term_id ) {
					if ( is_array( $term_id ) ) {
						$term_id = $term_id['term_id'];
					}
					if ( isset( $term['term_id'] ) ) {
						$this->processed_terms[ intval( $term['term_id'] ) ] = (int) $term_id;
					}
					continue;
				}

				if ( empty( $term['term_parent'] ) ) {
					$parent = 0;
				} else {
					$parent = term_exists( $term['term_parent'], $term['term_taxonomy'] );
					if ( is_array( $parent ) ) {
						$parent = $parent['term_id'];
					}
				}
				$description = isset( $term['term_description'] ) ? $term['term_description'] : '';
				$termarr     = array(
					'slug'        => $term['slug'],
					'description' => $description,
					'parent'      => intval( $parent ),
				);

				$id = wp_insert_term( $term['term_name'], $term['term_taxonomy'], $termarr );
				if ( ! is_wp_error( $id ) ) {
					if ( isset( $term['term_id'] ) ) {
						$this->processed_terms[ intval( $term['term_id'] ) ] = $id['term_id'];
					}
				} else {

					continue;
				}
			}

			unset( $this->terms );
		}

		/**
		 * Create new posts based on import information
		 *
		 * Posts marked as having a parent which doesn't exist will become top level items.
		 * Doesn't create a new post if: the post type doesn't exist, the given post ID
		 * is already noted as imported or a post with the same title and date already exists.
		 * Note that new/updated terms, comments and meta are imported for the last of the above.
		 */
		function process_posts() {
			$this->posts = apply_filters( 'wp_import_posts', $this->posts );

			// Count total images
			foreach ( $this->posts as $post ) {

				$post = apply_filters( 'wp_import_post_data_raw', $post );

				if ( isset( $this->processed_posts[ $post['post_id'] ] ) && ! empty( $post['post_id'] ) ) {
					continue;
				}

				if ( $post['status'] == 'auto-draft' ) {
					continue;
				}

				if ( 'nav_menu_item' == $post['post_type'] ) {
					continue;
				}

				$post_type_object = get_post_type_object( $post['post_type'] );

				$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
				if ( get_post_type( $post_exists ) != $post['post_type'] ) {

					$postdata = array(
						'import_id'      => $post['post_id'],
						'post_author'    => ! empty( $author ) ? $author : '',
						'post_date'      => $post['post_date'],
						'post_date_gmt'  => $post['post_date_gmt'],
						'post_content'   => str_replace( "\n", '', $post['post_content'] ),
						'post_excerpt'   => $post['post_excerpt'],
						'post_title'     => $post['post_title'],
						'post_status'    => $post['status'],
						'post_name'      => $post['post_name'],
						'comment_status' => $post['comment_status'],
						'ping_status'    => $post['ping_status'],
						'guid'           => $post['guid'],
						'post_parent'    => ! empty( $post_parent ) ? $post_parent : '',
						'menu_order'     => $post['menu_order'],
						'post_type'      => $post['post_type'],
						'post_password'  => $post['post_password'],
					);

					$original_post_ID = $post['post_id'];
					$postdata         = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

					$postdata = wp_slash( $postdata );

					if ( 'attachment' == $postdata['post_type'] ) {
						$this->totalImages++;
					}
				}
			}

			foreach ( $this->posts as $post ) {

				$post = apply_filters( 'wp_import_post_data_raw', $post );


				if ( isset( $this->processed_posts[ $post['post_id'] ] ) && ! empty( $post['post_id'] ) ) {
					continue;
				}

				if ( $post['status'] == 'auto-draft' ) {
					continue;
				}

				if ( 'nav_menu_item' == $post['post_type'] ) {

					$this->process_menu_item( $post );

					continue;
				} else if ( $post['post_type'] == 'page' ) {

					if ( $this->importFullDemo ) {
						echo '<script type="text/javascript">text_status( \'' . sprintf( wp_kses( __( 'Adding Page <b>%s</b>', 'insight-core' ), array(
								'b' => array(),
							) ), $post['post_title'] ) . '\' );</script>';
					}

				} else if ( $post['post_type'] != 'attachment' ) {

					if ( $this->importFullDemo ) {
						echo '<script type="text/javascript">text_status( \'' . sprintf( wp_kses( __( 'Adding Custom Post Type <b>%s</b>', 'insight-core' ), array(
								'b' => array(),
							) ), $post['post_type'] ) . '\' );</script>';
					}
				}

				@ob_flush();
				@flush();

				$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );

				if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
					$comment_post_ID = $post_id = $post_exists;
					if ( $this->importFullDemo ) {
						echo '<script type="text/javascript">progress_status(1)</script>';
					}
				} else {

					$post_parent = (int) $post['post_parent'];
					if ( $post_parent ) {
						// if we already know the parent, map it to the new local ID
						if ( isset( $this->processed_posts[ $post_parent ] ) ) {
							$post_parent = $this->processed_posts[ $post_parent ];
							// otherwise record the parent for later
						} else {
							$this->post_orphans[ intval( $post['post_id'] ) ] = $post_parent;
							$post_parent                                      = 0;
						}
					}

					// map the post author
					$author = sanitize_user( $post['post_author'], true );
					if ( isset( $this->author_mapping[ $author ] ) ) {
						$author = $this->author_mapping[ $author ];
					} else {
						$author = (int) get_current_user_id();
					}

					$postdata = array(
						'import_id'      => $post['post_id'],
						'post_author'    => $author,
						'post_date'      => $post['post_date'],
						'post_date_gmt'  => $post['post_date_gmt'],
						'post_content'   => $post['post_content'],
						'post_excerpt'   => $post['post_excerpt'],
						'post_title'     => $post['post_title'],
						'post_status'    => $post['status'],
						'post_name'      => $post['post_name'],
						'comment_status' => $post['comment_status'],
						'ping_status'    => $post['ping_status'],
						'guid'           => $post['guid'],
						'post_parent'    => $post_parent,
						'menu_order'     => $post['menu_order'],
						'post_type'      => $post['post_type'],
						'post_password'  => $post['post_password'],
					);

					$original_post_ID = $post['post_id'];
					$postdata         = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

					if ( 'attachment' == $postdata['post_type'] ) {

						$remote_url = ! empty( $post['attachment_url'] ) ? $post['attachment_url'] : $post['guid'];

						// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
						// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
						$postdata['upload_date'] = $post['post_date'];
						if ( isset( $post['postmeta'] ) ) {
							foreach ( $post['postmeta'] as $meta ) {
								if ( $meta['key'] == '_wp_attached_file' ) {
									if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) ) {
										$postdata['upload_date'] = $matches[0];
									}
									break;
								}
							}
						}

						$comment_post_ID = $post_id = $this->process_attachment( $postdata, $remote_url );
					} else {
						unset( $postdata['guid'] );
						$comment_post_ID = $post_id = wp_insert_post( $postdata, true );
						do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );
					}

					if ( is_wp_error( $post_id ) ) {

						continue;
					}

					if ( $post['is_sticky'] == 1 ) {
						stick_post( $post_id );
					}
				}

				// map pre-import ID to local ID
				$this->processed_posts[ intval( $post['post_id'] ) ] = (int) $post_id;

				if ( ! isset( $post['terms'] ) ) {
					$post['terms'] = array();
				}

				$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

				// add categories, tags and other terms
				if ( ! empty( $post['terms'] ) ) {
					$terms_to_set = array();
					foreach ( $post['terms'] as $term ) {
						// back compat with WXR 1.0 map 'tag' to 'post_tag'
						$taxonomy    = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
						$term_exists = term_exists( $term['slug'], $taxonomy );
						$term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
						if ( ! $term_id ) {
							$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
							if ( ! is_wp_error( $t ) ) {
								$term_id = $t['term_id'];
								do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
							} else {

								do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
								continue;
							}
						}
						$terms_to_set[ $taxonomy ][] = intval( $term_id );
					}

					foreach ( $terms_to_set as $tax => $ids ) {
						$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
						do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
					}
					unset( $post['terms'], $terms_to_set );
				}

				if ( ! isset( $post['comments'] ) ) {
					$post['comments'] = array();
				}

				$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

				// add/update comments
				if ( ! empty( $post['comments'] ) ) {
					$num_comments      = 0;
					$inserted_comments = array();
					foreach ( $post['comments'] as $comment ) {
						$comment_id                                         = $comment['comment_id'];
						$newcomments[ $comment_id ]['comment_post_ID']      = $comment_post_ID;
						$newcomments[ $comment_id ]['comment_author']       = $comment['comment_author'];
						$newcomments[ $comment_id ]['comment_author_email'] = $comment['comment_author_email'];
						$newcomments[ $comment_id ]['comment_author_IP']    = $comment['comment_author_IP'];
						$newcomments[ $comment_id ]['comment_author_url']   = $comment['comment_author_url'];
						$newcomments[ $comment_id ]['comment_date']         = $comment['comment_date'];
						$newcomments[ $comment_id ]['comment_date_gmt']     = $comment['comment_date_gmt'];
						$newcomments[ $comment_id ]['comment_content']      = $comment['comment_content'];
						$newcomments[ $comment_id ]['comment_approved']     = $comment['comment_approved'];
						$newcomments[ $comment_id ]['comment_type']         = $comment['comment_type'];
						$newcomments[ $comment_id ]['comment_parent']       = $comment['comment_parent'];
						$newcomments[ $comment_id ]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
						if ( isset( $this->processed_authors[ $comment['comment_user_id'] ] ) ) {
							$newcomments[ $comment_id ]['user_id'] = $this->processed_authors[ $comment['comment_user_id'] ];
						}
					}
					ksort( $newcomments );

					foreach ( $newcomments as $key => $comment ) {
						// if this is a new post we can skip the comment_exists() check
						if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
							if ( isset( $inserted_comments[ $comment['comment_parent'] ] ) ) {
								$comment['comment_parent'] = $inserted_comments[ $comment['comment_parent'] ];
							}
							$comment                   = wp_filter_comment( $comment );
							$inserted_comments[ $key ] = wp_insert_comment( $comment );
							do_action( 'wp_import_insert_comment', $inserted_comments[ $key ], $comment, $comment_post_ID, $post );

							foreach ( $comment['commentmeta'] as $meta ) {
								$value = maybe_unserialize( $meta['value'] );
								add_comment_meta( $inserted_comments[ $key ], $meta['key'], $value );
							}

							$num_comments++;
						}
					}
					unset( $newcomments, $inserted_comments, $post['comments'] );
				}

				if ( ! isset( $post['postmeta'] ) ) {
					$post['postmeta'] = array();
				}

				$post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );

				// add/update post meta
				if ( ! empty( $post['postmeta'] ) ) {
					foreach ( $post['postmeta'] as $meta ) {
						$key   = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
						$value = false;

						if ( '_edit_last' == $key ) {
							if ( isset( $this->processed_authors[ intval( $meta['value'] ) ] ) ) {
								$value = $this->processed_authors[ intval( $meta['value'] ) ];
							} else {
								$key = false;
							}
						}

						if ( $key ) {
							// export gets meta straight from the DB so could have a serialized string
							if ( ! $value ) {
								$value = maybe_unserialize( $meta['value'] );
							}
							if ( ! $value ) {
								$value = maybe_unserialize( str_replace( "\n", ' ' . "\n", $meta['value'] ) );
							}

							add_post_meta( $post_id, $key, $value );
							do_action( 'import_post_meta', $post_id, $key, $value );

							// if the post has a featured image, take note of this in case of remap
							if ( '_thumbnail_id' == $key ) {
								$this->featured_images[ $post_id ] = (int) $value;
							}
						}
					}
				}
			}

			unset( $this->posts );
		}

		/**
		 * Attempt to create a new menu item from import data
		 *
		 * Fails for draft, orphaned menu items and those without an associated nav_menu
		 * or an invalid nav_menu term. If the post type or term object which the menu item
		 * represents doesn't exist then the menu item will not be imported (waits until the
		 * end of the import to retry again before discarding).
		 *
		 * @param array $item Menu item details from WXR file
		 */
		function process_menu_item( $item ) {

			// skip draft, orphaned menu items
			if ( 'draft' == $item['status'] ) {
				return;
			}

			$menu_slug = false;
			if ( isset( $item['terms'] ) ) {
				// loop through terms, assume first nav_menu term is correct menu
				foreach ( $item['terms'] as $term ) {
					if ( 'nav_menu' == $term['domain'] ) {
						$menu_slug = $term['slug'];
						break;
					}
				}
			}

			// no nav_menu term associated with this menu item
			if ( ! $menu_slug ) {
				_e( 'Menu item skipped due to missing menu slug', 'insight-core' );
				echo '<br />';

				return;
			}

			$menu_id = term_exists( $menu_slug, 'nav_menu' );
			if ( ! $menu_id ) {
				printf( __( 'Menu item skipped due to invalid menu slug: %s', 'insight-core' ), esc_html( $menu_slug ) );
				echo '<br />';

				return;
			} else {
				$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
			}

			foreach ( $item['postmeta'] as $meta ) {
				if ( version_compare( PHP_VERSION, '7.0.0' ) >= 0 ) {
					${$meta['key']} = $meta['value'];
				} else {
					$$meta['key'] = $meta['value'];
				}
			}

			if ( 'taxonomy' == $_menu_item_type && isset( $this->processed_terms[ intval( $_menu_item_object_id ) ] ) ) {
				$_menu_item_object_id = $this->processed_terms[ intval( $_menu_item_object_id ) ];
			} else if ( 'post_type' == $_menu_item_type && isset( $this->processed_posts[ intval( $_menu_item_object_id ) ] ) ) {
				$_menu_item_object_id = $this->processed_posts[ intval( $_menu_item_object_id ) ];
			} else if ( 'custom' != $_menu_item_type ) {
				// associated object is missing or not imported yet, we'll retry later
				$this->missing_menu_items[] = $item;

				return;
			}

			if ( isset( $this->processed_menu_items[ intval( $_menu_item_menu_item_parent ) ] ) ) {
				$_menu_item_menu_item_parent = $this->processed_menu_items[ intval( $_menu_item_menu_item_parent ) ];
			} else if ( $_menu_item_menu_item_parent ) {
				$this->menu_item_orphans[ intval( $item['post_id'] ) ] = (int) $_menu_item_menu_item_parent;
				$_menu_item_menu_item_parent                           = 0;
			}

			// wp_update_nav_menu_item expects CSS classes as a space separated string
			$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
			if ( is_array( $_menu_item_classes ) ) {
				$_menu_item_classes = implode( ' ', $_menu_item_classes );
			}

			$args = array(
				'menu-item-object-id'   => $_menu_item_object_id,
				'menu-item-object'      => $_menu_item_object,
				'menu-item-parent-id'   => $_menu_item_menu_item_parent,
				'menu-item-position'    => intval( $item['menu_order'] ),
				'menu-item-type'        => $_menu_item_type,
				'menu-item-title'       => $item['post_title'],
				'menu-item-url'         => $_menu_item_url,
				'menu-item-description' => $item['post_content'],
				'menu-item-attr-title'  => $item['post_excerpt'],
				'menu-item-target'      => $_menu_item_target,
				'menu-item-classes'     => $_menu_item_classes,
				'menu-item-xfn'         => $_menu_item_xfn,
				'menu-item-status'      => $item['status'],
			);

			$id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( $id && ! is_wp_error( $id ) ) {
				$this->processed_menu_items[ intval( $item['post_id'] ) ] = (int) $id;

				do_action( 'import_menu_item_meta', $id, $item );
			}
		}

		/**
		 * If fetching attachments is enabled then attempt to create a new attachment
		 *
		 * @param array  $post Attachment post details from WXR
		 * @param string $url  URL to fetch attachment from
		 *
		 * @return int|WP_Error Post ID on success, WP_Error otherwise
		 */
		function process_attachment( $post, $url ) {

			$this->imageCount++;

			$guidraw = explode( 'wp-content', $post['guid'] ); // file name
			if ( ! empty( $guidraw[1] ) ) {
				$guidraw = WP_CONTENT_URL . $guidraw[1];
			} else {
				$guidraw = WP_CONTENT_URL . $guidraw[0];
			}

			if ( $this->importFullDemo ) {
				echo '<script type="text/javascript">' . 'progress_status( ' . ( $this->imageCount / $this->totalImages ) . ' );' . 'text_status( \'' . sprintf( __( 'Adding Media %s', 'insight-core' ), $guidraw ) . '\' );</script>';
				@ob_flush();
				@flush();
			}

			if ( ! $this->fetch_attachments ) {
				return new WP_Error( 'attachment_processing_error', __( 'Fetching attachments is not enabled', 'insight-core' ) );
			}

			// if the URL is absolute, but does not contain address, then upload it assuming base_HOME_URL
			if ( preg_match( '|^/[\w\W]+$|', $url ) ) {
				$url = rtrim( $this->base_url, '/' ) . $url;
			}

			// get attachments in media package. If it is not exist, download remote file
			$_urlxz = explode( 'wp-content', $url );
			$_urlxc = WP_CONTENT_DIR . $_urlxz[1];

			if ( file_exists( $_urlxc ) ) {
				$upload = array(
					'file' => $_urlxc,
					'url'  => WP_CONTENT_URL . $_urlxz[1],
				);

			} else {
				$upload = $this->fetch_remote_file( $url, $post );
			}

			if ( is_wp_error( $upload ) ) {

				if ( $this->importFullDemo ) {
					if ( WP_DEBUG ) {
						var_dump( $upload );
					}
				}

				return $upload;
			}

			if ( $info = wp_check_filetype( $upload['file'] ) ) {
				$post['post_mime_type'] = $info['type'];
			} else {
				return new WP_Error( 'attachment_processing_error', __( 'Invalid file type', 'insight-core' ) );
			}

			$post['guid'] = $upload['url'];

			// as per wp-admin/includes/upload.php
			$post_id = wp_insert_attachment( $post, $upload['file'] );

			if ( $this->generate_thumb ) {
				wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

			} else {
				wp_update_attachment_metadata( $post_id, '' );

			}

			// remap resized image URLs, works by stripping the extension and remapping the URL stub.
			if ( preg_match( '!^image/!', $info['type'] ) ) {
				$parts = pathinfo( $url );
				$name  = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

				$parts_new = pathinfo( $upload['url'] );
				$name_new  = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

				$this->url_remap[ $parts['dirname'] . '/' . $name ] = $parts_new['dirname'] . '/' . $name_new;
			}

			return $post_id;
		}

		/**
		 * Attempt to download a remote file attachment
		 *
		 * @param string $url  URL of item to fetch
		 * @param array  $post Attachment details
		 *
		 * @return array|WP_Error Local file location details on success, WP_Error otherwise
		 */
		function fetch_remote_file( $url, $post ) {

			// extract the file name and extension from the url
			$file_name = basename( $url );

			// get placeholder file in the upload dir with a unique, sanitized filename
			$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
			if ( $upload['error'] ) {
				return new WP_Error( 'upload_dir_error', $upload['error'] );
			}

			if ( $this->importFullDemo ) {
				if ( WP_DEBUG ) {
					var_dump( $url );
				}
			}

			// fetch the remote url and write it to the placeholder file
			$response = wp_remote_get( $url, array(
				'stream'   => true,
				'filename' => $upload['file'],
			) );

			// request failed
			if ( is_wp_error( $response ) ) {
				@unlink( $upload['file'] );

				return $response;
			}

			$code = (int) wp_remote_retrieve_response_code( $response );

			// make sure the fetch was successful
			if ( $code !== 200 ) {
				@unlink( $upload['file'] );

				return new WP_Error( 'import_file_error', sprintf( __( 'Remote server returned %1$d %2$s for %3$s', 'insight-core' ), $code, get_status_header_desc( $code ), $url ) );
			}

			$filesize = filesize( $upload['file'] );
			$headers  = wp_remote_retrieve_headers( $response );

			if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
				@unlink( $upload['file'] );

				return new WP_Error( 'import_file_error', __( 'Remote file is incorrect size', 'insight-core' ) );
			}

			if ( 0 == $filesize ) {
				@unlink( $upload['file'] );

				return new WP_Error( 'import_file_error', __( 'Zero size file downloaded', 'insight-core' ) );
			}

			$max_size = (int) $this->max_attachment_size();
			if ( ! empty( $max_size ) && $filesize > $max_size ) {
				@unlink( $upload['file'] );

				return new WP_Error( 'import_file_error', sprintf( __( 'Remote file is too large, limit is %s', 'insight-core' ), size_format( $max_size ) ) );
			}

			// keep track of the old and new urls so we can substitute them later
			$this->url_remap[ $url ]          = $upload['url'];
			$this->url_remap[ $post['guid'] ] = $upload['url']; // r13735, really needed?
			// keep track of the destination if the remote url is redirected somewhere else
			if ( isset( $headers['x-final-location'] ) && $headers['x-final-location'] != $url ) {
				$this->url_remap[ $headers['x-final-location'] ] = $upload['url'];
			}

			return $upload;
		}

		/**
		 * Attempt to associate posts and menu items with previously missing parents
		 *
		 * An imported post's parent may not have been imported when it was first created
		 * so try again. Similarly for child menu items and menu items which were missing
		 * the object (e.g. post) they represent in the menu
		 */
		function backfill_parents() {
			global $wpdb;

			// find parents for post orphans
			foreach ( $this->post_orphans as $child_id => $parent_id ) {
				$local_child_id = $local_parent_id = false;
				if ( isset( $this->processed_posts[ $child_id ] ) ) {
					$local_child_id = $this->processed_posts[ $child_id ];
				}
				if ( isset( $this->processed_posts[ $parent_id ] ) ) {
					$local_parent_id = $this->processed_posts[ $parent_id ];
				}

				if ( $local_child_id && $local_parent_id ) {
					$wpdb->update( $wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d' );
				}
			}

			// all other posts/terms are imported, retry menu items with missing associated object
			$missing_menu_items = $this->missing_menu_items;
			foreach ( $missing_menu_items as $item ) {

				$this->process_menu_item( $item );

			}

			// find parents for menu item orphans
			foreach ( $this->menu_item_orphans as $child_id => $parent_id ) {
				$local_child_id = $local_parent_id = 0;
				if ( isset( $this->processed_menu_items[ $child_id ] ) ) {
					$local_child_id = $this->processed_menu_items[ $child_id ];
				}
				if ( isset( $this->processed_menu_items[ $parent_id ] ) ) {
					$local_parent_id = $this->processed_menu_items[ $parent_id ];
				}

				if ( $local_child_id && $local_parent_id ) {
					update_post_meta( $local_child_id, '_menu_item_menu_item_parent', (int) $local_parent_id );
				}
			}
		}

		/**
		 * Use stored mapping information to update old attachment URLs
		 */
		function backfill_attachment_urls() {
			global $wpdb;
			// make sure we do the longest urls first, in case one is a substring of another
			uksort( $this->url_remap, array(
				&$this,
				'cmpr_strlen',
			) );

			foreach ( $this->url_remap as $from_url => $to_url ) {
				// remap urls in post_content
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url ) );
				// remap enclosure urls
				$result = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url ) );
			}
		}

		/**
		 * Update _thumbnail_id meta to new, imported attachment IDs
		 */
		function remap_featured_images() {
			// cycle through posts that have a featured image
			foreach ( $this->featured_images as $post_id => $value ) {
				if ( isset( $this->processed_posts[ $value ] ) ) {
					$new_id = $this->processed_posts[ $value ];
					// only update if there's a difference
					if ( $new_id != $value ) {
						update_post_meta( $post_id, '_thumbnail_id', $new_id );
					}
				}
			}
		}

		/**
		 * Parse a WXR file
		 *
		 * @param string $file Path to WXR file for parsing
		 *
		 * @return array Information gathered from the WXR file
		 */
		function parse( $file ) {
			$parser = new WXR_Parser();

			return $parser->parse( $file );
		}

		// Display import page title
		function header() {

		}

		// Close div.wrap
		function footer() {
			echo '</div>';
		}

		/**
		 * Display introductory text and file upload form
		 */
		function greet() {
			echo '<div class="narrow">';
			echo '<p>' . __( 'Howdy! Upload your WordPress eXtended RSS (WXR) file and we&#8217;ll import the posts, pages, comments, custom fields, categories, and tags into this site.', 'insight-core' ) . '</p>';
			echo '<p>' . __( 'Choose a WXR (.xml) file to upload, then click Upload file and import.', 'insight-core' ) . '</p>';
			wp_import_upload_form( 'admin.php?import=wordpress&amp;step=1' );
			echo '</div>';
		}

		/**
		 * Decide if the given meta key maps to information we will want to import
		 *
		 * @param string $key The meta key to check
		 *
		 * @return string|bool The key if we do want to import, false if not
		 */
		function is_valid_meta_key( $key ) {
			// skip attachment metadata since we'll regenerate it from scratch
			// skip _edit_lock as not relevant for import
			// removed _wp_attachment_metadata form 1.5.4.2
			if ( in_array( $key, array(
				'_wp_attached_file',
				'_edit_lock',
			) ) ) {
				return false;
			}

			return $key;
		}

		/**
		 * Decide whether or not the importer is allowed to create users.
		 * Default is true, can be filtered via import_allow_create_users
		 *
		 * @return bool True if creating users is allowed
		 */
		function allow_create_users() {
			return apply_filters( 'import_allow_create_users', true );
		}

		/**
		 * Decide whether or not the importer should attempt to download attachment files.
		 * Default is true, can be filtered via import_allow_fetch_attachments. The choice
		 * made at the import options screen must also be true, false here hides that checkbox.
		 *
		 * @return bool True if downloading attachments is allowed
		 */
		function allow_fetch_attachments() {
			return apply_filters( 'import_allow_fetch_attachments', true );
		}

		/**
		 * Decide what the maximum file size for downloaded attachments is.
		 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
		 *
		 * @return int Maximum attachment file size to import
		 */
		function max_attachment_size() {
			return apply_filters( 'import_attachment_size_limit', 0 );
		}

		/**
		 * Added to http_request_timeout filter to force timeout at 60 seconds during import
		 *
		 * @return int 600
		 */
		function bump_request_timeout( $val ) {
			return 600;
		}

		// return the difference in length between two strings
		function cmpr_strlen( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	}
} // class_exists( 'WP_Importer' )
