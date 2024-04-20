<?php
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ( defined( 'INSIGHT_CORE_SHOW_EXPORT_PAGE' ) && true === INSIGHT_CORE_SHOW_EXPORT_PAGE )
     || ( defined( 'WP_DEBUG' ) && true === WP_DEBUG )
) {
	class InsightCore_Export {

		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_export' ) );
			add_filter( 'export_wp_filename', array( $this, 'wp_filename' ) );
		}

		function init() {
			$export_option = isset( $_REQUEST['export_option'] ) ? sanitize_text_field( $_REQUEST['export_option'] ) : '';

			if ( ! empty( $export_option ) ) {
				switch ( $export_option ) {
					case 'content':
						$this->export_content();
						break;
					case 'sidebars':
						$this->export_sidebars();
						break;
					case 'widgets':
						$this->export_widgets();
						break;
					case 'menus':
						$this->export_menus();
						break;
					case 'page_options':
						$this->export_page_options();
						break;
					case 'customizer_options':
						$this->export_customizer_options();
						break;
					case 'woocommerce_image_sizes':
						if ( class_exists( 'WooCommerce' ) ) {
							$this->export_woocommerce_image_sizes();
						}
						break;
					case 'woocommerce_settings':
						if ( class_exists( 'WooCommerce' ) ) {
							$this->export_woocommerce_settings();
						}
						break;
					case 'woocommerce_attributes':
						if ( class_exists( 'WooCommerce' ) ) {
							$this->export_woocommerce_attributes();
						}
						break;
					case 'essential_grid':
						if ( class_exists( 'Essential_Grid' ) ) {
							$this->export_essential_grid();
						}
						break;
					case 'rev_sliders':
						if ( class_exists( 'RevSliderAdmin' ) ) {
							$this->export_rev_sliders();
						}
						break;
					case 'media_package':
						$this->export_media_packages();
						break;
					case 'media_package_placeholder':
						$this->export_media_packages_placeholder();
						break;
					case 'elementor':
						$this->export_elementor();
						break;
					case 'learnpress_settings':
						$this->export_learnpress_settings();
						break;
					case 'learnpress_data':
						$this->export_learnpress_data();
						break;
					case 'tutor_settings':
						$this->export_tutor_settings();
						break;
					case 'tutor_data':
						$this->export_tutor_data();
						break;
					case 'elfsight_instagram':
						$this->export_elfsight_instagram_data();
						break;
					default:
						break;
				}
			}
		}

		function admin_export() {
			if ( isset( $_REQUEST['export'] ) ) {
				$this->init();
			}

			add_submenu_page( 'insight-core', 'Export', 'Export', 'manage_options', 'insight-core-export', array(
				&$this,
				'export_page',
			) );
		}

		function export_page() {
			include_once( untrailingslashit( plugin_dir_path( __FILE__ ) . 'export-page.php' ) );
		}

		function wp_filename() {
			return 'content.xml';
		}

		function save_as_txt_file( $file_name, $output ) {
			header( "Content-type: application/text", true, 200 );
			header( "Content-Disposition: attachment; filename=$file_name" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );
			echo $output;
			exit;
		}

		function save_as_json_file( $file_name, $output ) {
			header( "Content-type: application/json", true, 200 );
			header( "Content-Disposition: attachment; filename=$file_name" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );
			echo $output;
			exit;
		}

		function esc_json( $json, $html = false ) {
			return _wp_specialchars(
				$json,
				$html ? ENT_NOQUOTES : ENT_QUOTES, // Escape quotes in attribute nodes only.
				'UTF-8',                           // json_encode() outputs UTF-8 (really just ASCII), not the blog's charset.
				true                               // Double escape entities: `&amp;` -> `&amp;amp;`.
			);
		}

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

		function export_content() {

			require_once( ABSPATH . 'wp-admin/includes/export.php' );

			$args = array();

			$args['content'] = 'all';

			export_wp( $args );
			exit();
		}

		function export_sidebars() {

			$sidebars = json_encode( get_option( 'kungfu_sidebars', '' ) );

			$this->save_as_txt_file( 'sidebars.txt', $sidebars );
		}

		function export_widgets() {

			// Get all available widgets site supportsss
			$available_widgets = $this->available_widgets();

			// Get all widget instances for each widget
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {

				// Get all instances for this ID base
				$instances = get_option( 'widget_' . $widget_data['id_base'] );

				// Have instances
				if ( ! empty( $instances ) ) {

					// Loop instances
					foreach ( $instances as $instance_id => $instance_data ) {

						// Key is ID (not _multiwidget)
						if ( is_numeric( $instance_id ) ) {
							$unique_instance_id                      = $widget_data['id_base'] . '-' . $instance_id;
							$widget_instances[ $unique_instance_id ] = $instance_data;
						}

					}

				}

			}


			// Gather sidebars with their widget instances
			$sidebars_widgets          = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
			$sidebars_widget_instances = array();
			foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

				// Skip inactive widgets
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}

				// Skip if no data or not an array (array_version)
				if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
					continue;
				}

				// Loop widget IDs for this sidebar
				foreach ( $widget_ids as $widget_id ) {

					// Is there an instance for this widget ID?
					if ( isset( $widget_instances[ $widget_id ] ) ) {

						// Add to array
						$sidebars_widget_instances[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];

					}

				}

			}

			$data = json_encode( $sidebars_widget_instances );

			$this->save_as_txt_file( 'widgets.txt', $data );
		}

		function export_menus() {
			global $wpdb;

			$this->data = array();
			$locations  = get_nav_menu_locations();

			$terms_table = $wpdb->prefix . "terms";
			foreach ( (array) $locations as $location => $menu_id ) {
				$menu_slug = $wpdb->get_results( "SELECT * FROM $terms_table where term_id={$menu_id}", ARRAY_A );
				if ( ! empty( $menu_slug ) ) {
					$this->data[ $location ] = $menu_slug[0]['slug'];
				}
			}

			$output = serialize( $this->data );
			$this->save_as_txt_file( "menus.txt", $output );
		}

		function export_page_options() {
			$show_on_front = get_option( "show_on_front" );

			$settings_pages = array(
				'show_on_front' => $show_on_front,
			);

			if ( $static_page_id = get_option( "page_on_front" ) ) {
				$static_page                     = get_post( $static_page_id );
				$settings_pages['page_on_front'] = $static_page->post_title;
			}

			if ( $post_page_id = get_option( 'page_for_posts' ) ) {
				$post_page                        = get_post( $post_page_id );
				$settings_pages['page_for_posts'] = $post_page->post_title;
			}

			$output = serialize( $settings_pages );

			$this->save_as_txt_file( "page_options.txt", $output );
		}

		function export_elementor() {
			$elementor_options = array(
				'elementor_active_kit',
				'_elementor_global_css',
				'elementor_cpt_support',
				'elementor_disable_color_schemes',
				'elementor_disable_typography_schemes',
				'elementor_default_generic_fonts',
				'elementor_unfiltered_files_upload',
				'elementor_scheme_color',
				'elementor_scheme_typography',
				'elementor_scheme_color-picker',
				'elementor_custom_icon_sets_config',
				'elementor_pro_theme_builder_conditions',
				'elementor_allow_svg',
				'elementor_library_category_children',
				'elementor_experiment-e_dom_optimization',
				'elementor_experiment-e_optimized_assets_loading',
				'elementor_experiment-e_font_icon_svg',
				'elementor_experiment-additional_custom_breakpoints',
			);

			$elementor_options = apply_filters( 'insight_core_export_elementor_options', $elementor_options );

			$response = array();

			if ( ! empty( $elementor_options ) ) {
				foreach ( $elementor_options as $option ) {
					$setting = get_option( $option );

					if ( $setting ) {
						$response[ $option ] = $setting;
					}
				}
			}

			$output = serialize( $response );

			$this->save_as_txt_file( "elementor.txt", $output );
		}

		function export_learnpress_settings() {
			$learnpress_options = array(
				'learn_press_logout_redirect_page_id',
				'learn_press_currency',
				'learn_press_currency_pos',
				'learn_press_thousands_separator',
				'learn_press_decimals_separator',
				'learn_press_number_of_decimals',
				'learn_press_required_review',
				'learn_press_enable_edit_published',
				'learn_press_courses_page_id',
				'learn_press_archive_course_limit',
				'learn_press_archive_course_thumbnail',
				'learn_press_course_thumbnail_image_size',
				'learn_press_become_a_teacher_page_id',
				'learn_press_instructor_registration',
				'learn_press_profile_page_id',
				'learn_press_profile_courses_limit',
				'learn_press_profile_endpoints',
				'learn_press_profile_avatar',
				'learn_press_profile_picture_thumbnail_size',
				'learn_press_profile_publicity',
				'learn_press_checkout_page_id',
				'learn_press_term_conditions_page_id',
			);

			$learnpress_options = apply_filters( 'insight_core_export_learnpress_options', $learnpress_options );

			$response = array();

			if ( ! empty( $learnpress_options ) ) {
				foreach ( $learnpress_options as $option ) {
					$setting = get_option( $option );

					if ( $setting ) {
						$response[ $option ] = $setting;
					}
				}
			}

			$output = serialize( $response );

			$this->save_as_txt_file( "learnpress.txt", $output );
		}

		function export_learnpress_data() {
			global $wpdb;

			$tables = array(
				"{$wpdb->prefix}learnpress_question_answers",
				"{$wpdb->prefix}learnpress_quiz_questions",
				"{$wpdb->prefix}learnpress_sections",
				"{$wpdb->prefix}learnpress_section_items",
			);

			$this->export_tables( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $tables, "learnpress_data.txt" );
		}

		function export_elfsight_instagram_data() {
			global $wpdb;

			$tables = array(
				"{$wpdb->prefix}elfsight_instagram_feed_cache",
				"{$wpdb->prefix}elfsight_instagram_feed_user",
				"{$wpdb->prefix}elfsight_instagram_feed_widgets",
			);

			$this->export_tables( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $tables, "elfsight_instagram.txt" );
		}

		function export_tutor_settings() {
			$options = array(
				'tutor_option',
				'tutor_addons_config',
				'tutor_withdraw_options',
			);

			$options = apply_filters( 'insight_core_export_tutor_options', $options );

			$response = array();

			if ( ! empty( $options ) ) {
				foreach ( $options as $option ) {
					$setting = get_option( $option );

					if ( $setting ) {
						$response[ $option ] = $setting;
					}
				}
			}

			$output = serialize( $response );

			$this->save_as_txt_file( 'tutor.txt', $output );
		}

		function export_tutor_data() {
			global $wpdb;

			$tables = array(
				"{$wpdb->prefix}tutor_quiz_attempts",
				"{$wpdb->prefix}tutor_quiz_attempt_answers",
				"{$wpdb->prefix}tutor_quiz_questions",
				"{$wpdb->prefix}tutor_quiz_question_answers",
			);

			$this->export_tables( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $tables, 'tutor_data.txt' );
		}

		function export_customizer_options() {

			$options = get_theme_mods();
			unset( $options['nav_menu_locations'] );

			$this->save_as_txt_file( 'customizer.txt', serialize( $options ) );
		}

		function export_woocommerce_image_sizes() {
			if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
				$data = array(
					'images' => array(
						'catalog'   => wc_get_image_size( 'shop_catalog' ),
						'thumbnail' => wc_get_image_size( 'shop_thumbnail' ),
						'single'    => wc_get_image_size( 'shop_single' ),
					),
				);
			} else {
				$data = array(
					'images' => array(
						'single'                 => get_option( 'woocommerce_single_image_width' ),
						'thumbnail'              => get_option( 'woocommerce_thumbnail_image_width' ),
						'cropping'               => get_option( 'woocommerce_thumbnail_cropping' ),
						'cropping_custom_width'  => get_option( 'woocommerce_thumbnail_cropping_custom_width', 1 ),
						'cropping_custom_height' => get_option( 'woocommerce_thumbnail_cropping_custom_height', 1 ),
					),
				);
			}

			$output = serialize( $data );

			$this->save_as_txt_file( 'woocommerce.txt', $output );
		}

		function export_woocommerce_settings() {
			$data = [];

			$options = [
				'woocommerce_enable_reviews',
				'woocommerce_review_rating_verification_label',
				'woocommerce_review_rating_verification_required',
			];

			// WPClever Tabs.
			if ( class_exists( 'WPCleverWoost' ) ) {
				$options[] = 'woost_tabs';
			}

			// WPClever Notifications.
			if ( defined( 'WPCSN_VERSION' ) ) {
				$options[] = 'wpcsn_opts';
			}

			// WPClever Compare.
			if ( class_exists( 'WPCleverWoosc' ) ) {
				$compare_options = $this->get_options_by_name_like( 'woosc_' );
				$compare_options = apply_filters( 'insight_core_export_wpclever_compare_options', $compare_options );

				$options = array_merge( $options, $compare_options );
			}

			// Sales Countdown Timer Pro.
			if ( class_exists( 'VI_SCT_SALES_COUNTDOWN_TIMER_Data' ) ) {
				$sale_countdown_options = [
					'sales_countdown_timer_params',
				];
				$sale_countdown_options = apply_filters( 'insight_core_export_sales_countdown_timer_options', $sale_countdown_options );

				$options = array_merge( $options, $sale_countdown_options );
			}

			$woocommerce_options = apply_filters( 'insight_core_export_woocommerce_options', $options );

			if ( ! empty( $woocommerce_options ) ) {
				foreach ( $woocommerce_options as $option ) {
					$setting = get_option( $option );

					if ( $setting ) {
						$data[ $option ] = $setting;
					}
				}
			}

			$output = wp_json_encode( $data );

			//$this->save_as_txt_file( 'woocommerce.txt', $output );
			$this->save_as_json_file( 'woocommerce_options.json', $output );
		}

		function export_woocommerce_attributes() {
			global $wpdb;

			$sql = "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies";

			$attributes = $wpdb->get_results( $sql );
			$results    = [];
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attribute ) {
					$results[ $attribute->attribute_name ] = (array) $attribute;
				}
			}

			$output = serialize( $results );

			$this->save_as_txt_file( 'woocommerce_attributes.txt', $output );
		}

		function export_essential_grid() {

			require_once( plugin_dir_path( 'essential-grid/essential-grid.php' ) );

			$c_grids   = new Essential_Grid();
			$item_skin = new Essential_Grid_Item_Skin();
			$item_ele  = new Essential_Grid_Item_Element();
			$nav_skin  = new Essential_Grid_Navigation();
			$metas     = new Essential_Grid_Meta();
			$fonts     = new ThemePunch_Fonts();

			$grids            = $c_grids->get_essential_grids();
			$skins            = $item_skin->get_essential_item_skins();
			$elements         = $item_ele->get_essential_item_elements();
			$navigation_skins = $nav_skin->get_essential_navigation_skins();
			$custom_metas     = $metas->get_all_meta();
			$custom_fonts     = $fonts->get_all_fonts();

			header( 'Content-Type: text/json' );
			header( 'Content-Disposition: attachment;filename=essential_grid.txt' );
			ob_start();

			$export = array();

			$ex = new Essential_Grid_Export();

			//export Grids
			if ( ! empty( $grids ) ) {
				$export['grids'] = $grids;
			}

			//export Skins
			if ( ! empty( $skins ) ) {
				$export['skins'] = $skins;
			}

			//export Elements
			if ( ! empty( $elements ) ) {
				$export['elements'] = $elements;
			}

			//export Navigation Skins
			if ( ! empty( $navigation_skins ) ) {
				$export['navigation-skins'] = $navigation_skins;
			}

			//export Custom Meta
			if ( ! empty( $custom_metas ) ) {
				$export['custom-meta'] = $custom_metas;
			}

			//export Punch Fonts
			if ( ! empty( $custom_fonts ) ) {
				$export['punch-fonts'] = $custom_fonts;
			}

			//export Global Styles
			$export['global-css'] = $ex->export_global_styles( 'on' );

			echo json_encode( $export );

			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();

			echo $content;

			exit;
		}

		function export_rev_sliders() {
			global $wpdb;

			$tables = array(
				"{$wpdb->prefix}revslider_css",
				"{$wpdb->prefix}revslider_layer_animations",
				"{$wpdb->prefix}revslider_navigations",
				"{$wpdb->prefix}revslider_sliders",
				"{$wpdb->prefix}revslider_slides",
				"{$wpdb->prefix}revslider_static_slides",
			);
			$this->export_tables( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $tables, "rev_sliders.txt" );
		}

		function export_tables(
			$host, $user, $pass, $name, $tables = false, $backup_name = false, $replacements = array(
			'OLD_DOMAIN.com',
			'NEW_DOMAIN.com',
		)
		) {
			set_time_limit( 3000 );
			$mysqli = new mysqli( $host, $user, $pass, $name );
			$mysqli->select_db( $name );
			$mysqli->query( "SET NAMES 'utf8'" );
			$queryTables = $mysqli->query( 'SHOW TABLES' );
			while ( $row = $queryTables->fetch_row() ) {
				$target_tables[] = $row[0];
			}
			if ( $tables !== false ) {
				$target_tables = array_intersect( $target_tables, $tables );
			}
			$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $name . "`\r\n--\r\n\r\n\r\n";
			foreach ( $target_tables as $table ) {
				if ( empty( $table ) ) {
					continue;
				}
				$result        = $mysqli->query( 'SELECT * FROM `' . $table . '`' );
				$fields_amount = $result->field_count;
				$rows_num      = $mysqli->affected_rows;
				$res           = $mysqli->query( 'SHOW CREATE TABLE ' . $table );
				$TableMLine    = $res->fetch_row();
				$content       .= "\n\n" . $TableMLine[1] . ";\n\n";
				for ( $i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0 ) {
					while ( $row = $result->fetch_row() ) { //when started (and every after 100 command cycle):
						if ( $st_counter % 100 == 0 || $st_counter == 0 ) {
							$content .= "\nINSERT INTO " . $table . " VALUES";
						}
						$content .= "\n(";
						for ( $j = 0; $j < $fields_amount; $j++ ) {
							$row[ $j ] = str_replace( "\n", "\\n", addslashes( $row[ $j ] ) );
							if ( isset( $row[ $j ] ) ) {
								$content .= '"' . $row[ $j ] . '"';
							} else {
								$content .= '""';
							}
							if ( $j < ( $fields_amount - 1 ) ) {
								$content .= ',';
							}
						}
						$content .= ")";
						//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
						if ( ( ( $st_counter + 1 ) % 100 == 0 && $st_counter != 0 ) || $st_counter + 1 == $rows_num ) {
							$content .= ";";
						} else {
							$content .= ",";
						}
						$st_counter = $st_counter + 1;
					}
				}
				$content .= "\n\n\n";
			}
			$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
			if ( function_exists( 'DOMAIN_or_STRING_modifier_in_DB' ) ) {
				$content = DOMAIN_or_STRING_modifier_in_DB( $replacements[0], $replacements[1], $content );
			}
			$backup_name = $backup_name ? $backup_name : $name . "___(" . date( 'H-i-s' ) . "_" . date( 'd-m-Y' ) . ")__rand" . rand( 1, 11111111 ) . ".sql";
			ob_get_clean();
			header( 'Content-Type: application/octet-stream' );
			header( "Content-Transfer-Encoding: Binary" );
			header( "Content-disposition: attachment; filename=\"" . $backup_name . "\"" );
			echo $content;
			exit;
		}

		function export_media_packages() {
			$demo_name = isset( $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : '';
			$src       = WP_CONTENT_DIR . '/uploads';
			$dst       = WP_CONTENT_DIR . INSIGHT_CORE_DS . INSIGHT_CORE_THEME_SLUG . '-' . $demo_name;

			$this->recurse_copy( $src, $dst );
		}

		function recurse_copy( $src, $dst ) {

			if ( is_dir( $src ) ) {

				$dir = opendir( $src );

				if ( ! is_dir( $dst ) ) {
					@mkdir( $dst );
				}

				while ( false !== ( $file = readdir( $dir ) ) ) {
					if ( ( $file != '.' ) && ( $file != '..' ) ) {
						if ( is_dir( $src . '/' . $file ) ) {
							$this->recurse_copy( $src . '/' . $file, $dst . '/' . $file );
						} else {
							// Skip file regenerated. For eg: name-DDDxDDD.ext or name-DDDxDDD@2x.ext
							if (
								substr( $file, 0, 1 ) !== '.' // Skip hidden files.
								&& false == preg_match( '/(-\d{1,}x\d{1,}+|@2x)\.\w{3,}$/', $file )// Skip regenerated (include retina) files.
							) {
								copy( $src . '/' . $file, $dst . '/' . $file );
							}
						}
					}
				}
				closedir( $dir );
			}

		}

		function export_media_packages_placeholder() {
			$demo_name = isset( $_POST['demo_placeholder'] ) ? sanitize_text_field( $_POST['demo_placeholder'] ) : '';
			$src       = WP_CONTENT_DIR . '/uploads';
			$dst       = WP_CONTENT_DIR . INSIGHT_CORE_DS . INSIGHT_CORE_THEME_SLUG . '-' . $demo_name;

			$this->recurse_copy_placeholder( $src, $dst );
		}

		function recurse_copy_placeholder( $src, $dst ) {

			if ( is_dir( $src ) ) {

				$dir = opendir( $src );

				if ( ! is_dir( $dst ) ) {
					@mkdir( $dst );
				}

				while ( false !== ( $file = readdir( $dir ) ) ) {
					if ( ( $file != '.' ) && ( $file != '..' ) ) {
						if ( is_dir( $src . '/' . $file ) ) {
							$this->recurse_copy_placeholder( $src . '/' . $file, $dst . '/' . $file );
						} else {
							if ( false == preg_match( '/-\d{1,}x\d{1,}+\.\w{3,}$/', $file ) ) {
								$prefix         = apply_filters( 'insight_core_placeholder_prefix', '' );
								$prevent_prefix = apply_filters( 'insight_core_placeholder_prevent_prefix', '_' );

								if ( $prefix != '' ) {
									if ( substr( $file, 0, strlen( $prefix ) ) === $prefix ) {
										// just generate placeholder for images have prefix
										list( $w, $h ) = getimagesize( $src . '/' . $file );
										$ext = pathinfo( $file, PATHINFO_EXTENSION );
										copy( 'http://placehold.jp/' . $w . 'x' . $h . '.' . $ext, $dst . '/' . $file );
									} else {
										copy( $src . '/' . $file, $dst . '/' . $file );
									}
								} elseif ( $prevent_prefix != '' ) {
									if ( substr( $file, 0, strlen( $prevent_prefix ) ) !== $prevent_prefix ) {
										// just generate placeholder for images haven't prevent_prefix
										list( $w, $h ) = getimagesize( $src . '/' . $file );
										$ext = pathinfo( $file, PATHINFO_EXTENSION );
										copy( 'http://placehold.jp/' . $w . 'x' . $h . '.' . $ext, $dst . '/' . $file );
									} else {
										copy( $src . '/' . $file, $dst . '/' . $file );
									}
								}
							}
						}
					}
				}
				closedir( $dir );
			}

		}

		public function get_options_by_name_like( $name ) {
			global $wpdb;

			$sql = $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", '%' . $wpdb->esc_like( $name ) . '%' );

			$results = $wpdb->get_results( $sql );

			$data = [];

			if ( ! empty( $results ) ) {
				foreach ( $results as $option ) {
					$data[] = $option->option_name;
				}
			}

			return $data;
		}
	}

	new InsightCore_Export();
}
