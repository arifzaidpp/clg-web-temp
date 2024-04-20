<?php

// Load Importer API
require_once( ABSPATH . 'wp-admin/includes/import.php' );

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) ) {
		require_once( $class_wp_importer );
	}
}

require dirname( __FILE__ ) . '/class-logger.php';
require dirname( __FILE__ ) . '/class-logger-cli.php';
require dirname( __FILE__ ) . '/class-wxr-import-info.php';

class InsightCore_Importer_2 extends WP_Importer {

	/**
	 * Maximum supported WXR version
	 */
	const MAX_WXR_VERSION = 1.2;

	/**
	 * Regular expression for checking if a post references an attachment
	 *
	 * Note: This is a quick, weak check just to exclude text-only posts. More
	 * vigorous checking is done later to verify.
	 */
	const REGEX_HAS_ATTACHMENT_REFS = '!
		(
			# Match anything with an image or attachment class
			class=[\'"].*?\b(wp-image-\d+|attachment-[\w\-]+)\b
		|
			# Match anything that looks like an upload URL
			src=[\'"][^\'"]*(
				[0-9]{4}/[0-9]{2}/[^\'"]+\.(jpg|jpeg|png|gif)
			|
				content/uploads[^\'"]+
			)[\'"]
		)!ix';

	/**
	 * Version of WXR we're importing.
	 *
	 * Defaults to 1.0 for compatibility. Typically overridden by a
	 * `<wp:wxr_version>` tag at the start of the file.
	 *
	 * @var string
	 */
	protected $version = '1.0';

	// information to import from WXR file
	protected $categories = array();
	protected $tags       = array();
	protected $base_url   = '';

	// NEW STYLE
	protected $mapping            = array();
	protected $requires_remapping = array();
	protected $exists             = array();
	protected $user_slug_override = array();

	protected $url_remap       = array();
	protected $featured_images = array();

	public $demo        = '';
	public $authors     = array();
	public $imageCount  = 0;
	public $totalImages = 0;

	public $options = array();

	/**
	 * Logger instance.
	 *
	 * @var WP_Importer_Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param array $options                   {
	 *
	 * @var bool    $prefill_existing_posts    Should we prefill `post_exists` calls? (True prefills and uses more
	 *      memory, false checks once per imported post and takes longer. Default is true.)
	 * @var bool    $prefill_existing_comments Should we prefill `comment_exists` calls? (True prefills and uses more
	 *      memory, false checks once per imported comment and takes longer. Default is true.)
	 * @var bool    $prefill_existing_terms    Should we prefill `term_exists` calls? (True prefills and uses more
	 *      memory, false checks once per imported term and takes longer. Default is true.)
	 * @var bool    $update_attachment_guids   Should attachment GUIDs be updated to the new URL? (True updates the
	 *      GUID, which keeps compatibility with v1, false doesn't update, and allows deduplication and reimporting.
	 *      Default is false.)
	 * @var bool    $fetch_attachments         Fetch attachments from the remote server. (True fetches and creates
	 *      attachment posts, false skips attachments. Default is false.)
	 * @var bool    $aggressive_url_search     Should we search/replace for URLs aggressively? (True searches all
	 *      posts' content for old URLs and replaces, false checks for `<img class="wp-image-*">` only. Default is
	 *      false.)
	 * @var int     $default_author            User ID to use if author is missing or invalid. (Default is null, which
	 *      leaves posts unassigned.)
	 * }
	 */
	public function __construct( $options = array() ) {
		// Initialize some important variables.
		$empty_types = array(
			'post'    => array(),
			'comment' => array(),
			'term'    => array(),
			'user'    => array(),
		);

		$this->mapping              = $empty_types;
		$this->mapping['user_slug'] = array();
		$this->mapping['term_id']   = array();
		$this->requires_remapping   = $empty_types;
		$this->exists               = $empty_types;

		$this->options = wp_parse_args( $options, array(
			'prefill_existing_posts'    => true,
			'prefill_existing_comments' => true,
			'prefill_existing_terms'    => true,
			'update_attachment_guids'   => false,
			'fetch_attachments'         => true,
			'aggressive_url_search'     => false,
			'default_author'            => null,
			'import_full_demo'          => true,
			'generate_thumb'            => false,
		) );

		if ( ! $this->options['import_full_demo'] ) {
			$this->demo = isset( $_REQUEST['dummy'] ) ? sanitize_text_field( $_REQUEST['dummy'] ) : '';
		} else {
			$this->demo = isset( $_REQUEST['demo'] ) ? sanitize_text_field( $_REQUEST['demo'] ) : '';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'import_start', array( $this, 'import_woocommerce_attributes' ) );
		}
	}

	public function set_logger( $logger ) {
		$this->logger = $logger;
	}

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

			$demos = $this->options['import_full_demo'] ? apply_filters( 'insight_core_import_demos', array() ) : apply_filters( 'insight_core_import_dummies', array() );

			if ( is_array( $demos ) && isset( $demos[ $this->demo ]['url'] ) ) {
				$url = $demos[ $this->demo ]['url'];
			} else {
				$url = '';
			}

			if ( $this->options['import_full_demo'] ) {
				echo '<script type="text/javascript">progress_status("dl");text_status(\'' . esc_html__( 'Downloading package...', 'insight-core' ) . '\');</script>';
			}

			// create temp folder.
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
						if ( $this->options['import_full_demo'] ) {
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

				if ( $this->options['import_full_demo'] ) {
					echo '<script type="text/javascript">progress_status(0)</script>';
				}

			} else {

				if ( $this->options['import_full_demo'] ) {
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
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			WP_Filesystem();
			global $wp_filesystem;

			$wp_filesystem->rmdir( $_tmppath, true );
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
	 * Read export txt file
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
	 * Read export json file
	 *
	 * @param $file_name
	 *
	 * @return mixed
	 */
	function get_json_data( $file_name ) {
		$file = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS . $this->demo . INSIGHT_CORE_DS . $file_name . '.json';

		if ( ! file_exists( $file ) ) {
			return '';
		}

		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		WP_Filesystem();
		global $wp_filesystem;

		$file_content = $wp_filesystem->get_contents( $file );

		return json_decode( $file_content, true );
	}

	/**
	 * Page Options
	 */
	function import_page_options() {

		$pages = $this->get_data( 'page_options' );

		if ( is_array( $pages ) ) {

			if ( ! empty( $pages['show_on_front'] ) ) {
				update_option( 'show_on_front', $pages['show_on_front'] );
			}

			if ( ! empty( $pages['page_on_front'] ) ) {
				$page = get_page_by_title( $pages['page_on_front'] );

				if ( ! empty( $page ) ) {
					update_option( 'page_on_front', $page->ID );
				}
			}

			if ( ! empty( $pages['page_for_posts'] ) ) {
				$page = get_page_by_title( $pages['page_for_posts'] );

				if ( ! empty( $page ) ) {
					update_option( 'page_for_posts', $page->ID );
				}
			}
		}
	}

	/**
	 * Elementor Options
	 */
	function import_elementor_options() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}

		$settings = $this->get_data( 'elementor' );

		if ( is_array( $settings ) ) {
			foreach ( $settings as $option => $value ) {
				update_option( $option, $value );
			}
		}
	}

	function import_learnpress() {
		if ( ! class_exists( 'LearnPress' ) ) {
			return;
		}

		/**
		 * Import Learnpress Options
		 */
		$settings = $this->get_data( 'learnpress' );

		if ( is_array( $settings ) ) {
			foreach ( $settings as $option => $value ) {
				update_option( $option, $value );
			}
		}

		/**
		 * Import Learnpress Data (Sections, Lessons, Quizzes, Questions)
		 */
		global $wpdb;

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">text_status( \'' . 'Adding <b>Course</b>' . '\' );</script>';
		}

		if ( get_option( 'insight_core_import_learnpress' ) == false && $this->options['import_full_demo'] ) {
			$xuri     = str_replace( '/', '\\/', INSIGHT_CORE_SITE_URI ) . '\\';
			$templine = '';
			$lines    = $this->get_data( 'learnpress_data', false );

			if ( ! empty( $lines ) ) {
				$lines = explode( "\n", $lines );

				foreach ( $lines as $line ) {
					if ( substr( $line, 0, 2 ) == '--' || $line == '' ) {
						continue;
					}

					$templine .= $line;
					if ( substr( trim( $line ), -1, 1 ) == ';' ) {
						ob_start();

						$wpdb->query( str_replace( array( '%INSIGHT_CORE_SITE_URI%', 'wp_', ), array(
							$xuri,
							$wpdb->prefix,
						), $templine ), false );

						$templine = '';
						ob_end_clean();
					}
				}
			}
		}

		InsightCore::update_option_count( 'insight_core_import_learnpress' );
	}

	function import_elfsight_instagram() {
		if ( ! class_exists( 'ElfsightInstagramFeedPlugin' ) ) {
			return;
		}

		global $wpdb;

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">text_status( \'' . 'Adding <b>Instagram Feed</b>' . '\' );</script>';
		}

		if ( get_option( 'insight_core_import_elfsight_instagram' ) == false && $this->options['import_full_demo'] ) {
			$xuri     = str_replace( '/', '\\/', INSIGHT_CORE_SITE_URI ) . '\\';
			$templine = '';
			$lines    = $this->get_data( 'elfsight_instagram', false );

			if ( ! empty( $lines ) ) {
				$lines = explode( "\n", $lines );

				foreach ( $lines as $line ) {
					if ( substr( $line, 0, 2 ) == '--' || $line == '' ) {
						continue;
					}

					$templine .= $line;
					if ( substr( trim( $line ), -1, 1 ) == ';' ) {
						ob_start();

						$wpdb->query( str_replace( array( '%INSIGHT_CORE_SITE_URI%', 'wp_', ), array(
							$xuri,
							$wpdb->prefix,
						), $templine ), false );

						$templine = '';
						ob_end_clean();
					}
				}
			}
		}

		InsightCore::update_option_count( 'insight_core_import_elfsight_instagram' );
	}

	function import_tutor_lms() {
		if ( ! function_exists( 'tutor' ) ) {
			return;
		}

		/**
		 * Import Tutor Options
		 */
		$settings = $this->get_data( 'tutor' );

		if ( is_array( $settings ) ) {
			foreach ( $settings as $option => $value ) {
				update_option( $option, $value );
			}
		}

		/**
		 * Import Tutor Data (Sections, Lessons, Quizzes, Questions)
		 */
		global $wpdb;

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">text_status( \'' . 'Adding <b>Course</b>' . '\' );</script>';
		}

		if ( get_option( 'insight_core_import_tutor_lms_data' ) == false && $this->options['import_full_demo'] ) {
			$xuri     = str_replace( '/', '\\/', INSIGHT_CORE_SITE_URI ) . '\\';
			$templine = '';
			$lines    = $this->get_data( 'tutor_data', false );

			if ( ! empty( $lines ) ) {
				$lines = explode( "\n", $lines );

				foreach ( $lines as $line ) {
					if ( substr( $line, 0, 2 ) == '--' || $line == '' ) {
						continue;
					}

					$templine .= $line;
					if ( substr( trim( $line ), -1, 1 ) == ';' ) {
						ob_start();

						$wpdb->query( str_replace( array( '%INSIGHT_CORE_SITE_URI%', 'wp_', ), array(
							$xuri,
							$wpdb->prefix,
						), $templine ), false );

						$templine = '';
						ob_end_clean();
					}
				}
			}
		}

		InsightCore::update_option_count( 'insight_core_import_tutor_lms_data' );
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

			if ( $this->options['import_full_demo'] ) {
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

		if ( $this->options['import_full_demo'] ) {
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

		if ( $this->options['import_full_demo'] ) {
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

		if ( $this->options['import_full_demo'] ) {
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
	 * Import WooCommerce Attributes
	 *
	 * @return array
	 */
	function import_woocommerce_attributes() {
		global $wpdb;

		$_x_path = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS . $this->demo . INSIGHT_CORE_DS;
		$file    = $_x_path . 'content.xml';

		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		$attribute_datas = $this->get_data( 'woocommerce_attributes' );

		while ( $reader->read() ) {
			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			if ( $reader->name == 'item' ) {
				$node   = $reader->expand();
				$parsed = $this->parse_post_node( $node );

				if ( is_wp_error( $parsed ) ) {
					$this->log_error( $parsed );

					// Skip the rest of this post
					$reader->next();

					return;
				}

				if ( ! empty( $parsed['terms'] ) ) {
					foreach ( $parsed['terms'] as $term ) {

						if ( strstr( $term['taxonomy'], 'pa_' ) ) {
							if ( ! taxonomy_exists( $term['taxonomy'] ) ) {
								$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['taxonomy'] ) );

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
								register_taxonomy( $term['taxonomy'], apply_filters( 'woocommerce_taxonomy_objects_' . $term['taxonomy'], array( 'product' ) ), apply_filters( 'woocommerce_taxonomy_args_' . $term['taxonomy'], array(
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

	/**
	 * Import WooCommerce Image sizes
	 *
	 * @return array
	 */
	function import_woocommerce_image_sizes() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$wc = $this->get_data( 'woocommerce' );

		if ( is_array( $wc ) && ! empty( $wc['images'] ) ) {

			if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
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
			} else {
				update_option( 'woocommerce_single_image_width', $wc['images']['single'] );
				update_option( 'woocommerce_thumbnail_image_width', $wc['images']['thumbnail'] );
				update_option( 'woocommerce_thumbnail_cropping', $wc['images']['cropping'] );
				update_option( 'woocommerce_thumbnail_cropping_custom_width', $wc['images']['cropping_custom_width'] );
				update_option( 'woocommerce_thumbnail_cropping_custom_height',
					$wc['images']['cropping_custom_height'] );
			}
		}

	}

	function import_woocommerce_options() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$woocommerce_options = $this->get_json_data( 'woocommerce_options' );

		if ( is_array( $woocommerce_options ) && ! empty( $woocommerce_options ) ) {
			foreach ( $woocommerce_options as $option_name => $value ) {
				update_option( $option_name, $value );
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

		if ( $this->options['import_full_demo'] ) {
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
	 * Import Revolution sliders
	 *
	 */
	function import_rev_sliders() {

		if ( ! class_exists( 'RevSliderAdmin' ) ) {
			return;
		}

		global $wpdb;

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Revolution Slider</b>' ), array( 'b' => array() ) ) . '\' );</script>';
		}

		if ( get_option( 'insight_core_import_revsliders' ) == false && $this->options['import_full_demo'] ) {
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
	 * @see WPForms\Admin\Tools\Views\Import::process()
	 */
	function import_wpforms() {
		if ( ! defined( 'WPFORMS_VERSION' ) ) {
			return;
		}

		$forms = $this->get_json_data( 'wpforms' );

		if ( empty( $forms ) || ! is_array( $forms ) ) {
			return;
		}

		foreach ( $forms as $form ) {
			$title = ! empty( $form['settings']['form_title'] ) ? $form['settings']['form_title'] : '';
			$desc  = ! empty( $form['settings']['form_desc'] ) ? $form['settings']['form_desc'] : '';

			$post_args = [
				'post_title'   => $title,
				'post_status'  => 'publish',
				'post_type'    => 'wpforms',
				'post_excerpt' => $desc,
			];

			if ( ! empty( $form['id'] ) ) {
				$post_args['import_id'] = $form['id'];
			}

			$new_id = wp_insert_post( $post_args );

			if ( $new_id ) {
				$form['id'] = $new_id;

				wp_update_post(
					[
						'ID'           => $new_id,
						'post_content' => wpforms_encode( $form ),
					]
				);
			}
		}
	}

	/**
	 * Import Essential Grid
	 *
	 */
	function import_essential_grid() {

		$file = INSIGHT_CORE_THEME_DIR . '/assets/import/' . $this->demo . '/essential_grid.txt';

		if ( ! class_exists( 'Essential_Grid' ) || ! file_exists( $file ) ) {
			return;
		}

		require_once( plugin_dir_path( 'essential-grid/essential-grid.php' ) );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		WP_Filesystem();
		global $wp_filesystem;

		$es_data = json_decode( $wp_filesystem->get_contents( $file ), true );

		try {

			if ( $this->options['import_full_demo'] ) {
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

		if ( $this->options['import_full_demo'] ) {
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

	public function dispatch() {
		if ( ! $this->check_writeable() ) {
			exit;
		}

		$_cpath = WP_CONTENT_DIR . INSIGHT_CORE_DS;

		$_x_path = INSIGHT_CORE_THEME_DIR . INSIGHT_CORE_DS . 'assets' . INSIGHT_CORE_DS . 'import' . INSIGHT_CORE_DS . $this->demo . INSIGHT_CORE_DS;

		$_tmppath = $_cpath . INSIGHT_CORE_THEME_SLUG . '-' . $this->demo . '-tmp';


		/**
		 * PHP setup environment
		 */
		@ini_set( 'max_execution_time', 0 );
		@ini_set( 'output_buffering', 'on' );
		@ini_set( 'zlib.output_compression', 0 );
		@ob_implicit_flush();

		delete_option( 'ic_download_tmp_package' );

		$file = $_x_path . 'content.xml';

		if ( ! file_exists( $file ) ) {
			wp_die( sprintf( 'Can not find import file: %s', $file ) );

			return;
		}

		// START DOWNLOAD IMAGES
		$this->download_package( $_tmppath );

		//  FINISH DOWNLOAD & COPY FILE FROM TMP TO UPLOAD & UNPACKAGE
		$this->unpackage( $_cpath, $_tmppath );

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">progress_status(0);text_status( \'' . esc_html__( 'Preparing for add media...', 'insight-core' ) . '\' );</script>';
		}

		@ob_flush();
		@flush();

		// Import wp contents
		set_time_limit( 0 );

		$info = $this->get_preliminary_information( $file );

		$this->totalImages = $info->media_count;

		$this->authors = $info->users;

		$this->set_user_mapping();

		$this->import_woocommerce_options();
		$this->import_woocommerce_image_sizes();

		/**
		 * Update elementor options before import content.
		 */
		$this->import_elementor_options();

		$this->import( $file );

		$this->import_learnpress();

		$this->import_elfsight_instagram();

		$this->import_tutor_lms();

		$this->import_page_options();

		$this->import_sidebars();

		$this->import_widgets();

		$this->import_menus();

		$this->import_customizer_options();

		$this->import_woocommerce_pages();

		$this->import_essential_grid();

		$this->fix_essential_grid();

		$this->import_rev_sliders();

		$this->import_wpforms();

		do_action( 'insight_core_importer_dispatch_after', $this );

		InsightCore::update_option_count( INSIGHT_CORE_THEME_SLUG . '_' . $this->demo . '_imported' );
	}

	/**
	 * Get a stream reader for the file.
	 *
	 * @param string $file Path to the XML file.
	 *
	 * @return XMLReader|WP_Error Reader instance on success, error otherwise.
	 */
	protected function get_reader( $file ) {
		// Avoid loading external entities for security
		$old_value = null;
		if ( function_exists( 'libxml_disable_entity_loader' ) ) {
			// $old_value = libxml_disable_entity_loader( true );
		}

		$reader = new XMLReader();
		$status = $reader->open( $file );

		if ( ! is_null( $old_value ) ) {
			// libxml_disable_entity_loader( $old_value );
		}

		if ( ! $status ) {
			return new WP_Error( 'wxr_importer.cannot_parse', __( 'Could not open the file for parsing', 'insight-core' ) );
		}

		return $reader;
	}

	/**
	 * The main controller for the actual import stage.
	 *
	 * @param string $file Path to the WXR file for importing
	 *
	 * @return WP_Error|WXR_Import_Info|XMLReader
	 *
	 */
	public function get_preliminary_information( $file ) {
		// Let's run the actual importer now, woot
		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		// Set the version to compatibility mode first
		$this->version = '1.0';

		// Start parsing!
		$data = new WXR_Import_Info();
		while ( $reader->read() ) {
			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:wxr_version':
					// Upgrade to the correct version
					$this->version = $reader->readString();

					if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) && WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'insight-core' ),
							$this->version,
							self::MAX_WXR_VERSION
						) );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'generator':
					$data->generator = $reader->readString();
					$reader->next();
					break;

				case 'title':
					$data->title = $reader->readString();
					$reader->next();
					break;

				case 'wp:base_site_url':
					$data->siteurl = $reader->readString();
					$reader->next();
					break;

				case 'wp:base_blog_url':
					$data->home = $reader->readString();
					$reader->next();
					break;

				case 'wp:author':
					$node = $reader->expand();

					$parsed = $this->parse_author_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$data->users[] = $parsed;

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'item':
					$node   = $reader->expand();
					$parsed = $this->parse_post_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					if ( $parsed['data']['post_type'] === 'attachment' ) {
						$data->media_count++;
					} else {
						$data->post_count++;
					}
					$data->comment_count += count( $parsed['comments'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:category':
				case 'wp:tag':
				case 'wp:term':
					$data->term_count++;

					// Handled everything in this node, move on to the next
					$reader->next();
					break;
			}
		}

		$data->version = $this->version;

		return $data;
	}

	/**
	 *  The main controller for the actual import stage.
	 *
	 * @param string $file Path to the WXR file for importing
	 *
	 * @return WP_Error|XMLReader
	 */
	public function import( $file ) {
		/**
		 * Allow svg + fonts file upload to make Importer importing properly.
		 */
		add_filter( 'upload_mimes', array( $this, 'allow_custom_file_upload' ) );
		add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
		add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

		/**
		 * Allow upload all file types compatible with future.
		 */
		if ( ! defined( 'ALLOW_UNFILTERED_UPLOADS' ) ) {
			define( 'ALLOW_UNFILTERED_UPLOADS', true );
		}

		$result = $this->import_start( $file );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Let's run the actual importer now, woot
		$reader = $this->get_reader( $file );
		if ( is_wp_error( $reader ) ) {
			return $reader;
		}

		// Set the version to compatibility mode first
		$this->version = '1.0';

		// Reset other variables
		$this->base_url = '';

		// Start parsing!
		while ( $reader->read() ) {
			// Only deal with element opens
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:wxr_version':
					// Upgrade to the correct version
					$this->version = $reader->readString();

					if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) && WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'insight-core' ),
							$this->version,
							self::MAX_WXR_VERSION
						) );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:base_site_url':
					$this->base_url = $reader->readString();

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'item':

					if ( $this->options['import_full_demo'] ) {
						echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Posts</b>' ), array( 'b' => array() ) ) . '\' );</script>';
						@ob_flush();
						@flush();
					}

					$node   = $reader->expand();
					$parsed = $this->parse_post_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$this->process_post( $parsed['data'], $parsed['meta'], $parsed['comments'], $parsed['terms'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:author':

					if ( $this->options['import_full_demo'] ) {
						echo '<script type="text/javascript">text_status( \'' . esc_html__( 'Get author mapping', 'insight-core' ) . '\' );</script>';
						@ob_flush();
						@flush();
					}

					$node = $reader->expand();

					$parsed = $this->parse_author_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_author( $parsed['data'], $parsed['meta'] );
					if ( is_wp_error( $status ) ) {
						$this->log_error( $status );
					}

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:category':

					if ( $this->options['import_full_demo'] ) {
						echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Categories</b>' ), array( 'b' => array() ) ) . '\' );</script>';
						@ob_flush();
						@flush();
					}

					$node = $reader->expand();

					$parsed = $this->parse_term_node( $node, 'category' );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:tag':

					if ( $this->options['import_full_demo'] ) {
						echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Tags</b>' ), array( 'b' => array() ) ) . '\' );</script>';
						@ob_flush();
						@flush();
					}

					$node = $reader->expand();

					$parsed = $this->parse_term_node( $node, 'tag' );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				case 'wp:term':

					if ( $this->options['import_full_demo'] ) {
						echo '<script type="text/javascript">text_status( \'' . wp_kses( __( 'Adding <b>Terms</b>' ), array( 'b' => array() ) ) . '\' );</script>';
						@ob_flush();
						@flush();
					}

					$node = $reader->expand();

					$parsed = $this->parse_term_node( $node );
					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						// Skip the rest of this post
						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					// Handled everything in this node, move on to the next
					$reader->next();
					break;

				default:
					// Skip this node, probably handled by something already
					break;
			}
		}

		// Now that we've done the main processing, do any required
		// post-processing and remapping.
		$this->post_process();

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">text_status( \'' . esc_html__( 'Updating incorrect/missing infomation in the database...', 'insight-core' ) . '\');</script>';
			@ob_flush();
			@flush();
		}

		if ( $this->options['aggressive_url_search'] ) {
			$this->replace_attachment_urls_in_content();
		}
		$this->remap_featured_images();

		$this->import_end();
	}

	public function allow_custom_file_upload( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';

		$mimes['otf']  = 'application/x-font-otf';
		$mimes['woff'] = 'application/x-font-woff';
		$mimes['ttf']  = 'application/x-font-ttf';
		$mimes['eot']  = 'application/vnd.ms-fontobject';

		return $mimes;
	}

	/**
	 * Log an error instance to the logger.
	 *
	 * @param WP_Error $error Error instance to log.
	 */
	protected function log_error( WP_Error $error ) {

		if ( WP_DEBUG ) {
			$this->logger->warning( $error->get_error_message() );
		}

		// Log the data as debug info too
		$data = $error->get_error_data();
		if ( ! empty( $data ) && WP_DEBUG ) {
			$this->logger->debug( var_export( $data, true ) );
		}
	}

	/**
	 * Parses the WXR file and prepares us for the task of processing parsed data
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	protected function import_start( $file ) {
		if ( ! is_file( $file ) ) {
			return new WP_Error( 'wxr_importer.file_missing', __( 'The file does not exist, please try again.', 'insight-core' ) );
		}

		// Suspend bunches of stuff in WP core
		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );
		wp_suspend_cache_invalidation( true );

		// Prefill exists calls if told to
		if ( $this->options['prefill_existing_posts'] ) {
			$this->prefill_existing_posts();
		}
		if ( $this->options['prefill_existing_comments'] ) {
			$this->prefill_existing_comments();
		}
		if ( $this->options['prefill_existing_terms'] ) {
			$this->prefill_existing_terms();
		}

		/**
		 * Begin the import.
		 *
		 * Fires before the import process has begun. If you need to suspend
		 * caching or heavy processing on hooks, do so here.
		 */
		do_action( 'import_start' );
	}

	/**
	 * Performs post-import cleanup of files and the cache
	 */
	protected function import_end() {
		// Re-enable stuff in core
		wp_suspend_cache_invalidation( false );
		wp_cache_flush();
		foreach ( get_taxonomies() as $tax ) {
			delete_option( "{$tax}_children" );
			_get_term_hierarchy( $tax );
		}

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );

		/**
		 * Complete the import.
		 *
		 * Fires after the import process has finished. If you need to update
		 * your cache or re-enable processing, do so here.
		 */
		do_action( 'import_end' );

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">progress_status(1)</script>';
		}
	}

	/**
	 * Set the user mapping.
	 *
	 * @param array $mapping List of map arrays (containing `old_slug`, `old_id`, `new_id`)
	 */
	public function set_user_mapping() {
		$mapping = array();
		$i       = 0;

		foreach ( $this->authors as $author ) {

			$mapping[ $i ] = array(
				'old_slug' => $author['data']['user_login'],
				'old_id'   => $author['data']['ID'],
				'new_id'   => get_current_user_id(),
			);

			$i++;
		}

		foreach ( $mapping as $map ) {
			if ( ( empty( $map['old_slug'] ) || empty( $map['old_id'] ) || empty( $map['new_id'] ) ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( __( 'Invalid author mapping', 'insight-core' ) );
					$this->logger->debug( var_export( $map, true ) );
				}
				continue;
			}

			$old_slug = $map['old_slug'];
			$old_id   = $map['old_id'];

			$this->mapping['user'][ $old_id ]        = get_current_user_id();
			$this->mapping['user_slug'][ $old_slug ] = get_current_user_id();
		}
	}

	/**
	 * Set the user slug overrides.
	 *
	 * Allows overriding the slug in the import with a custom/renamed version.
	 *
	 * @param string[] $overrides Map of old slug to new slug.
	 */
	public function set_user_slug_overrides( $overrides ) {
		foreach ( $overrides as $original => $renamed ) {
			$this->user_slug_override[ $original ] = $renamed;
		}
	}

	/**
	 * Parse a post node into post data.
	 *
	 * @param DOMElement $node Parent node of post data (typically `item`).
	 *
	 * @return array|WP_Error Post data array on success, error otherwise.
	 */
	protected function parse_post_node( $node ) {
		$data     = array();
		$meta     = array();
		$comments = array();
		$terms    = array();

		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:post_type':
					$data['post_type'] = $child->textContent;
					break;

				case 'title':
					$data['post_title'] = $child->textContent;
					break;

				case 'guid':
					$data['guid'] = $child->textContent;
					break;

				case 'dc:creator':
					$data['post_author'] = $child->textContent;
					break;

				case 'content:encoded':
					$data['post_content'] = $child->textContent;
					break;

				case 'excerpt:encoded':
					$data['post_excerpt'] = $child->textContent;
					break;

				case 'wp:post_id':
					$data['post_id'] = $child->textContent;
					break;

				case 'wp:post_date':
					$data['post_date'] = $child->textContent;
					break;

				case 'wp:post_date_gmt':
					$data['post_date_gmt'] = $child->textContent;
					break;

				case 'wp:comment_status':
					$data['comment_status'] = $child->textContent;
					break;

				case 'wp:ping_status':
					$data['ping_status'] = $child->textContent;
					break;

				case 'wp:post_name':
					$data['post_name'] = $child->textContent;
					break;

				case 'wp:status':
					$data['post_status'] = $child->textContent;

					if ( $data['post_status'] === 'auto-draft' ) {
						// Bail now
						return new WP_Error(
							'wxr_importer.post.cannot_import_draft',
							__( 'Cannot import auto-draft posts' ),
							$data
						);
					}
					break;

				case 'wp:post_parent':
					$data['post_parent'] = $child->textContent;
					break;

				case 'wp:menu_order':
					$data['menu_order'] = $child->textContent;
					break;

				case 'wp:post_password':
					$data['post_password'] = $child->textContent;
					break;

				case 'wp:is_sticky':
					$data['is_sticky'] = $child->textContent;
					break;

				case 'wp:attachment_url':
					$data['attachment_url'] = $child->textContent;
					break;

				case 'wp:postmeta':
					$meta_item = $this->parse_meta_node( $child );
					if ( ! empty( $meta_item ) ) {
						$meta[] = $meta_item;
					}
					break;

				case 'wp:comment':
					$comment_item = $this->parse_comment_node( $child );
					if ( ! empty( $comment_item ) ) {
						$comments[] = $comment_item;
					}
					break;

				case 'category':
					$term_item = $this->parse_category_node( $child );
					if ( ! empty( $term_item ) ) {
						$terms[] = $term_item;
					}
					break;
			}
		}

		return compact( 'data', 'meta', 'comments', 'terms' );
	}

	/**
	 * Create new posts based on import information
	 *
	 * Posts marked as having a parent which doesn't exist will become top level items.
	 * Doesn't create a new post if: the post type doesn't exist, the given post ID
	 * is already noted as imported or a post with the same title and date already exists.
	 * Note that new/updated terms, comments and meta are imported for the last of the above.
	 */
	protected function process_post( $data, $meta, $comments, $terms ) {
		/**
		 * Pre-process post data.
		 *
		 * @param array $data     Post data. (Return empty to skip.)
		 * @param array $meta     Meta data.
		 * @param array $comments Comments on the post.
		 * @param array $terms    Terms on the post.
		 */
		$data = apply_filters( 'wxr_importer.pre_process.post', $data, $meta, $comments, $terms );
		if ( empty( $data ) ) {
			return false;
		}

		$original_id = isset( $data['post_id'] ) ? (int) $data['post_id'] : 0;
		$parent_id   = isset( $data['post_parent'] ) ? (int) $data['post_parent'] : 0;

		// Have we already processed this?
		if ( isset( $this->mapping['post'][ $original_id ] ) ) {
			return;
		}

		$post_type_object = get_post_type_object( $data['post_type'] );

		// Is this type even valid?
		if ( ! $post_type_object ) {
			if ( WP_DEBUG ) {
				$this->logger->warning( sprintf(
					__( 'Failed to import "%s": Invalid post type %s', 'insight-core' ),
					$data['post_title'],
					$data['post_type']
				) );
			}

			return false;
		}

		$post_exists = $this->post_exists( $data );

		if ( $post_exists ) {
			if ( WP_DEBUG ) {
				$this->logger->info( sprintf(
					__( '%s "%s" already exists.', 'insight-core' ),
					$post_type_object->labels->singular_name,
					$data['post_title']
				) );
			}

			// Even though this post already exists, new comments might need importing
			$this->process_comments( $comments, $original_id, $data, $post_exists );

			return false;
		}

		// Map the parent post, or mark it as one we need to fix
		$requires_remapping = false;
		if ( $parent_id ) {
			if ( isset( $this->mapping['post'][ $parent_id ] ) ) {
				$data['post_parent'] = $this->mapping['post'][ $parent_id ];
			} else {
				$meta[]             = array( 'key' => '_wxr_import_parent', 'value' => $parent_id );
				$requires_remapping = true;

				$data['post_parent'] = 0;
			}
		}

		// Map the author, or mark it as one we need to fix
		$author = sanitize_user( $data['post_author'], true );

		if ( empty( $author ) ) {
			// Missing or invalid author, use default if available.
			$data['post_author'] = $this->options['default_author'];
		} elseif ( isset( $this->mapping['user_slug'][ $author ] ) ) {
			$data['post_author'] = $this->mapping['user_slug'][ $author ];
		} else {
			$meta[]             = array( 'key' => '_wxr_import_user_slug', 'value' => $author );
			$requires_remapping = true;

			$data['post_author'] = (int) get_current_user_id();
		}

		// Does the post look like it contains attachment images?
		if ( preg_match( self::REGEX_HAS_ATTACHMENT_REFS, $data['post_content'] ) ) {
			$meta[]             = array( 'key' => '_wxr_import_has_attachment_refs', 'value' => true );
			$requires_remapping = true;
		}

		// Whitelist to just the keys we allow
		$postdata = array(
			'import_id' => $data['post_id'],
		);
		$allowed  = array(
			'post_author'    => true,
			'post_date'      => true,
			'post_date_gmt'  => true,
			'post_content'   => true,
			'post_excerpt'   => true,
			'post_title'     => true,
			'post_status'    => true,
			'post_name'      => true,
			'comment_status' => true,
			'ping_status'    => true,
			'guid'           => true,
			'post_parent'    => true,
			'menu_order'     => true,
			'post_type'      => true,
			'post_password'  => true,
		);
		foreach ( $data as $key => $value ) {
			if ( ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$postdata[ $key ] = $data[ $key ];
		}

		$postdata = apply_filters( 'wp_import_post_data_processed', $postdata, $data );

		if ( 'attachment' === $postdata['post_type'] ) {
			if ( ! $this->options['fetch_attachments'] ) {
				if ( WP_DEBUG ) {
					$this->logger->notice( sprintf(
						__( 'Skipping attachment "%s", fetching attachments disabled' ),
						$data['post_title']
					) );
				}

				return false;
			}
			$remote_url = ! empty( $data['attachment_url'] ) ? $data['attachment_url'] : $data['guid'];
			$post_id    = $this->process_attachment( $postdata, $meta, $remote_url );
		} elseif ( 'page' === $postdata['post_type'] ) {
			unset( $postdata['guid'] );
			$post_id = wp_insert_post( $postdata, true );
			if ( $this->options['import_full_demo'] ) {
				echo '<script type="text/javascript">text_status( \'' . sprintf( wp_kses( __( 'Adding Page <b>%s</b>', 'insight-core' ), array(
						'b' => array(),
					) ), $postdata['post_title'] ) . '\' );</script>';
			}
		} elseif ( 'post' == $postdata['post_type'] ) {
			unset( $postdata['guid'] );
			$post_id = wp_insert_post( $postdata, true );
			do_action( 'wp_import_insert_post', $post_id, $original_id, $postdata, $data );
			if ( $this->options['import_full_demo'] ) {
				echo '<script type="text/javascript">text_status( \'' . sprintf( wp_kses( __( 'Adding Post <b>%s</b>', 'insight-core' ), array(
						'b' => array(),
					) ), $postdata['post_title'] ) . '\' );</script>';
			}
		} else {
			unset( $postdata['guid'] );
			$post_id = wp_insert_post( $postdata, true );
			if ( $this->options['import_full_demo'] ) {
				echo '<script type="text/javascript">text_status( \'' . sprintf( wp_kses( __( 'Adding Custom Post Type <b>%s</b>', 'insight-core' ), array(
						'b' => array(),
					) ), $postdata['post_type'] ) . '\' );</script>';
			}
		}

		@ob_flush();
		@flush();

		if ( is_wp_error( $post_id ) ) {
			if ( WP_DEBUG ) {
				$this->logger->error( sprintf(
					__( 'Failed to import "%s" (%s)', 'insight-core' ),
					$data['post_title'],
					$post_type_object->labels->singular_name
				) );
				$this->logger->debug( $post_id->get_error_message() );
			}

			/**
			 * Post processing failed.
			 *
			 * @param WP_Error $post_id  Error object.
			 * @param array    $data     Raw data imported for the post.
			 * @param array    $meta     Raw meta data, already processed by {@see process_post_meta}.
			 * @param array    $comments Raw comment data, already processed by {@see process_comments}.
			 * @param array    $terms    Raw term data, already processed.
			 */
			do_action( 'wxr_importer.process_failed.post', $post_id, $data, $meta, $comments, $terms );

			return false;
		}

		// Ensure stickiness is handled correctly too
		if ( $data['is_sticky'] === '1' ) {
			stick_post( $post_id );
		}

		// map pre-import ID to local ID
		$this->mapping['post'][ $original_id ] = (int) $post_id;
		if ( $requires_remapping ) {
			$this->requires_remapping['post'][ $post_id ] = true;
		}
		$this->mark_post_exists( $data, $post_id );

		if ( WP_DEBUG ) {
			$this->logger->info( sprintf(
				__( 'Imported "%s" (%s)', 'insight-core' ),
				$data['post_title'],
				$post_type_object->labels->singular_name
			) );
			$this->logger->debug( sprintf(
				__( 'Post %d remapped to %d', 'insight-core' ),
				$original_id,
				$post_id
			) );
		}

		// Handle the terms too
		$terms = apply_filters( 'wp_import_post_terms', $terms, $post_id, $data );

		if ( ! empty( $terms ) ) {
			$term_ids = array();
			foreach ( $terms as $term ) {
				$taxonomy = $term['taxonomy'];

				$key = sha1( $taxonomy . ':' . $term['slug'] );

				if ( isset( $this->mapping['term'][ $key ] ) ) {
					$term_ids[ $taxonomy ][] = (int) $this->mapping['term'][ $key ];
				} else {
					$meta[]             = array( 'key' => '_wxr_import_term', 'value' => $term );
					$requires_remapping = true;
				}
			}

			foreach ( $term_ids as $tax => $ids ) {
				$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
				do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $data );
			}
		}

		$this->process_comments( $comments, $post_id, $data );
		$this->process_post_meta( $meta, $post_id, $data );

		if ( 'nav_menu_item' === $data['post_type'] ) {
			$this->process_menu_item_meta( $post_id, $data, $meta );
		}

		/**
		 * Post processing completed.
		 *
		 * @param int   $post_id  New post ID.
		 * @param array $data     Raw data imported for the post.
		 * @param array $meta     Raw meta data, already processed by {@see process_post_meta}.
		 * @param array $comments Raw comment data, already processed by {@see process_comments}.
		 * @param array $terms    Raw term data, already processed.
		 */
		do_action( 'wxr_importer.processed.post', $post_id, $data, $meta, $comments, $terms );
	}

	/**
	 * Attempt to create a new menu item from import data
	 *
	 * Fails for draft, orphaned menu items and those without an associated nav_menu
	 * or an invalid nav_menu term. If the post type or term object which the menu item
	 * represents doesn't exist then the menu item will not be imported (waits until the
	 * end of the import to retry again before discarding).
	 *
	 * @param $post_id
	 * @param $data
	 * @param $meta
	 */
	protected function process_menu_item_meta( $post_id, $data, $meta ) {

		$item_type          = get_post_meta( $post_id, '_menu_item_type', true );
		$original_object_id = get_post_meta( $post_id, '_menu_item_object_id', true );
		$object_id          = null;

		if ( WP_DEBUG ) {
			$this->logger->debug( sprintf( 'Processing menu item %s', $item_type ) );
		}

		$requires_remapping = false;
		switch ( $item_type ) {
			case 'taxonomy':
				if ( isset( $this->mapping['term_id'][ $original_object_id ] ) ) {
					$object_id = $this->mapping['term_id'][ $original_object_id ];
				} else {
					add_post_meta( $post_id, '_wxr_import_menu_item', wp_slash( $original_object_id ) );
					$requires_remapping = true;
				}
				break;

			case 'post_type':
				if ( isset( $this->mapping['post'][ $original_object_id ] ) ) {
					$object_id = $this->mapping['post'][ $original_object_id ];
				} else {
					add_post_meta( $post_id, '_wxr_import_menu_item', wp_slash( $original_object_id ) );
					$requires_remapping = true;
				}
				break;

			case 'custom':
				// Custom refers to itself, wonderfully easy.
				$object_id = $post_id;
				break;

			default:
				// associated object is missing or not imported yet, we'll retry later
				$this->missing_menu_items[] = $item_type;

				if ( WP_DEBUG ) {
					$this->logger->debug( 'Unknown menu item type' );
				}
				break;
		}

		if ( $requires_remapping ) {
			$this->requires_remapping['post'][ $post_id ] = true;
		}

		if ( empty( $object_id ) ) {
			// Nothing needed here.
			return;
		}

		if ( WP_DEBUG ) {
			$this->logger->debug( sprintf( 'Menu item %d mapped to %d', $original_object_id, $object_id ) );
		}
		update_post_meta( $post_id, '_menu_item_object_id', wp_slash( $object_id ) );

		do_action( 'import_menu_item_meta_new', $post_id );
	}

	/**
	 * If fetching attachments is enabled then attempt to create a new attachment
	 *
	 * @param array  $post Attachment post details from WXR
	 * @param string $url  URL to fetch attachment from
	 *
	 * @return int|WP_Error Post ID on success, WP_Error otherwise
	 */
	protected function process_attachment( $post, $meta, $remote_url ) {

		$this->imageCount++;

		$guidraw = explode( 'wp-content', $post['guid'] ); // file name
		if ( ! empty( $guidraw[1] ) ) {
			$guidraw = WP_CONTENT_URL . $guidraw[1];
		} else {
			$guidraw = WP_CONTENT_URL . $guidraw[0];
		}

		if ( $this->options['import_full_demo'] ) {
			echo '<script type="text/javascript">' . 'progress_status( ' . ( $this->imageCount / $this->totalImages ) . ' );' . 'text_status( \'' . sprintf( __( 'Adding Media %s', 'insight-core' ), $guidraw ) . '\' );</script>';
			@ob_flush();
			@flush();
		}

		// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
		// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
		$post['upload_date'] = $post['post_date'];
		foreach ( $meta as $meta_item ) {
			if ( $meta_item['key'] !== '_wp_attached_file' ) {
				continue;
			}

			if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta_item['value'], $matches ) ) {
				$post['upload_date'] = $matches[0];
			}
			break;
		}

		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		if ( preg_match( '|^/[\w\W]+$|', $remote_url ) ) {
			$remote_url = rtrim( $this->base_url, '/' ) . $remote_url;
		}

		// get attachments in media package. If it is not exist, download remote file
		$_urlxz = explode( 'wp-content', $remote_url );

		// remove multisites sites/xx folder from file.
		$remote_file_name = preg_replace( '~sites/[0-9]*/~', '', $_urlxz[1] );

		$_urlxc = WP_CONTENT_DIR . $remote_file_name;

		/**
		 * Check if file exist in uploads/xxxx/xx folder.
		 */
		if ( file_exists( $_urlxc ) ) {
			$upload = array(
				'file' => $_urlxc,
				'url'  => WP_CONTENT_URL . $_urlxz[1],
			);
		} else { // Otherwise fetch from demo site.
			$upload = $this->fetch_remote_file( $remote_url, $post );
		}

		if ( is_wp_error( $upload ) ) {

			if ( $this->options['import_full_demo'] ) {
				if ( WP_DEBUG ) {
					var_dump( $upload );
				}
			}

			return $upload;
		}

		$info = wp_check_filetype( $upload['file'] );
		if ( ! $info ) {
			return new WP_Error( 'attachment_processing_error', __( 'Invalid file type', 'insight-core' ) );
		}

		$post['post_mime_type'] = $info['type'];

		$post['guid'] = $upload['url'];

		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment( $post, $upload['file'] );

		if ( $this->options['generate_thumb'] ) {
			wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		} else {
			wp_update_attachment_metadata( $post_id, '' );
		}

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		// Map this image URL later if we need to
		$this->url_remap[ $remote_url ] = $upload['url'];

		// If we have a HTTPS URL, ensure the HTTP URL gets replaced too
		if ( substr( $remote_url, 0, 8 ) === 'https://' ) {
			$insecure_url                     = 'http' . substr( $remote_url, 5 );
			$this->url_remap[ $insecure_url ] = $upload['url'];
		}

		if ( $this->options['aggressive_url_search'] ) {
			// remap resized image URLs, works by stripping the extension and remapping the URL stub.
			/*if ( preg_match( '!^image/!', $info['type'] ) ) {
				$parts = pathinfo( $remote_url );
				$name = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

				$parts_new = pathinfo( $upload['url'] );
				$name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

				$this->url_remap[$parts['dirname'] . '/' . $name] = $parts_new['dirname'] . '/' . $name_new;
			}*/
		}

		return $post_id;
	}

	/**
	 * Parse a meta node into meta data.
	 *
	 * @param DOMElement $node Parent node of meta data (typically `wp:postmeta` or `wp:commentmeta`).
	 *
	 * @return array|null Meta data array on success, or null on error.
	 */
	protected function parse_meta_node( $node ) {
		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:meta_key':
					$key = $child->textContent;
					break;

				case 'wp:meta_value':
					$value = $child->textContent;
					break;
			}
		}

		if ( empty( $key ) || empty( $value ) ) {
			return null;
		}

		return compact( 'key', 'value' );
	}

	/**
	 * Process and import post meta items.
	 *
	 * @param array $meta    List of meta data arrays
	 * @param int   $post_id Post to associate with
	 * @param array $post    Post data
	 *
	 * @return int|WP_Error Number of meta items imported on success, error otherwise.
	 */
	protected function process_post_meta( $meta, $post_id, $post ) {
		if ( empty( $meta ) ) {
			return true;
		}

		foreach ( $meta as $meta_item ) {
			/**
			 * Pre-process post meta data.
			 *
			 * @param array $meta_item Meta data. (Return empty to skip.)
			 * @param int   $post_id   Post the meta is attached to.
			 */
			$meta_item = apply_filters( 'wxr_importer.pre_process.post_meta', $meta_item, $post_id );
			if ( empty( $meta_item ) ) {
				return false;
			}

			$key   = apply_filters( 'import_post_meta_key', $meta_item['key'], $post_id, $post );
			$value = false;

			if ( '_edit_last' === $key ) {
				$value = intval( $meta_item['value'] );
				if ( ! isset( $this->mapping['user'][ $value ] ) ) {
					// Skip!
					continue;
				}

				$value = $this->mapping['user'][ $value ];
			}

			if ( $key ) {
				// export gets meta straight from the DB so could have a serialized string
				if ( ! $value ) {
					$value = maybe_unserialize( $meta_item['value'] );
				}

				add_post_meta( $post_id, $key, $value );
				do_action( 'import_post_meta', $post_id, $key, $value );

				// if the post has a featured image, take note of this in case of remap
				if ( '_thumbnail_id' === $key ) {
					$this->featured_images[ $post_id ] = (int) $value;
				}
			}
		}

		return true;
	}

	/**
	 * Parse a comment node into comment data.
	 *
	 * @param DOMElement $node Parent node of comment data (typically `wp:comment`).
	 *
	 * @return array Comment data array.
	 */
	protected function parse_comment_node( $node ) {
		$data = array(
			'commentmeta' => array(),
		);

		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:comment_id':
					$data['comment_id'] = $child->textContent;
					break;
				case 'wp:comment_author':
					$data['comment_author'] = $child->textContent;
					break;

				case 'wp:comment_author_email':
					$data['comment_author_email'] = $child->textContent;
					break;

				case 'wp:comment_author_IP':
					$data['comment_author_IP'] = $child->textContent;
					break;

				case 'wp:comment_author_url':
					$data['comment_author_url'] = $child->textContent;
					break;

				case 'wp:comment_user_id':
					$data['comment_user_id'] = $child->textContent;
					break;

				case 'wp:comment_date':
					$data['comment_date'] = $child->textContent;
					break;

				case 'wp:comment_date_gmt':
					$data['comment_date_gmt'] = $child->textContent;
					break;

				case 'wp:comment_content':
					$data['comment_content'] = $child->textContent;
					break;

				case 'wp:comment_approved':
					$data['comment_approved'] = $child->textContent;
					break;

				case 'wp:comment_type':
					$data['comment_type'] = $child->textContent;
					break;

				case 'wp:comment_parent':
					$data['comment_parent'] = $child->textContent;
					break;

				case 'wp:commentmeta':
					$meta_item = $this->parse_meta_node( $child );
					if ( ! empty( $meta_item ) ) {
						$data['commentmeta'][] = $meta_item;
					}
					break;
			}
		}

		return $data;
	}

	/**
	 * Process and import comment data.
	 *
	 * @param array $comments List of comment data arrays.
	 * @param int   $post_id  Post to associate with.
	 * @param array $post     Post data.
	 *
	 * @return int|WP_Error Number of comments imported on success, error otherwise.
	 */
	protected function process_comments( $comments, $post_id, $post, $post_exists = false ) {

		$comments = apply_filters( 'wp_import_post_comments', $comments, $post_id, $post );
		if ( empty( $comments ) ) {
			return 0;
		}

		$num_comments = 0;

		// Sort by ID to avoid excessive remapping later
		usort( $comments, array( $this, 'sort_comments_by_id' ) );

		foreach ( $comments as $key => $comment ) {
			/**
			 * Pre-process comment data
			 *
			 * @param array $comment Comment data. (Return empty to skip.)
			 * @param int   $post_id Post the comment is attached to.
			 */
			$comment = apply_filters( 'wxr_importer.pre_process.comment', $comment, $post_id );
			if ( empty( $comment ) ) {
				return false;
			}

			$original_id = isset( $comment['comment_id'] ) ? (int) $comment['comment_id'] : 0;
			$parent_id   = isset( $comment['comment_parent'] ) ? (int) $comment['comment_parent'] : 0;
			$author_id   = isset( $comment['comment_user_id'] ) ? (int) $comment['comment_user_id'] : 0;

			// if this is a new post we can skip the comment_exists() check
			// TODO: Check comment_exists for performance
			if ( $post_exists ) {
				$existing = $this->comment_exists( $comment );
				if ( $existing ) {
					$this->mapping['comment'][ $original_id ] = $existing;
					continue;
				}
			}

			// Remove meta from the main array
			$meta = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
			unset( $comment['commentmeta'] );

			// Map the parent comment, or mark it as one we need to fix
			$requires_remapping = false;
			if ( $parent_id ) {
				if ( isset( $this->mapping['comment'][ $parent_id ] ) ) {
					$comment['comment_parent'] = $this->mapping['comment'][ $parent_id ];
				} else {
					// Prepare for remapping later
					$meta[]             = array( 'key' => '_wxr_import_parent', 'value' => $parent_id );
					$requires_remapping = true;

					// Wipe the parent for now
					$comment['comment_parent'] = 0;
				}
			}

			// Map the author, or mark it as one we need to fix
			if ( $author_id ) {
				if ( isset( $this->mapping['user'][ $author_id ] ) ) {
					$comment['user_id'] = $this->mapping['user'][ $author_id ];
				} else {
					// Prepare for remapping later
					$meta[]             = array( 'key' => '_wxr_import_user', 'value' => $author_id );
					$requires_remapping = true;

					// Wipe the user for now
					$comment['user_id'] = 0;
				}
			}

			// Run standard core filters
			$comment['comment_post_ID'] = $post_id;
			$comment                    = wp_filter_comment( $comment );

			// wp_insert_comment expects slashed data
			$comment_id                               = wp_insert_comment( wp_slash( $comment ) );
			$this->mapping['comment'][ $original_id ] = $comment_id;
			if ( $requires_remapping ) {
				$this->requires_remapping['comment'][ $comment_id ] = true;
			}
			$this->mark_comment_exists( $comment, $comment_id );

			/**
			 * Comment has been imported.
			 *
			 * @param int   $comment_id New comment ID
			 * @param array $comment    Comment inserted (`comment_id` item refers to the original ID)
			 * @param int   $post_id    Post parent of the comment
			 * @param array $post       Post data
			 */
			do_action( 'wp_import_insert_comment', $comment_id, $comment, $post_id, $post );

			// Process the meta items
			foreach ( $meta as $meta_item ) {
				$value = maybe_unserialize( $meta_item['value'] );
				add_comment_meta( $comment_id, wp_slash( $meta_item['key'] ), wp_slash( $value ) );
			}

			/**
			 * Post processing completed.
			 *
			 * @param int   $post_id New post ID.
			 * @param array $comment Raw data imported for the comment.
			 * @param array $meta    Raw meta data, already processed by {@see process_post_meta}.
			 * @param array $post_id Parent post ID.
			 */
			do_action( 'wxr_importer.processed.comment', $comment_id, $comment, $meta, $post_id );

			$num_comments++;
		}

		return $num_comments;
	}

	protected function parse_category_node( $node ) {
		$data = array(
			// Default taxonomy to "category", since this is a `<category>` tag
			'taxonomy' => 'category',
		);
		$meta = array();

		if ( $node->hasAttribute( 'domain' ) ) {
			$data['taxonomy'] = $node->getAttribute( 'domain' );
		}
		if ( $node->hasAttribute( 'nicename' ) ) {
			$data['slug'] = $node->getAttribute( 'nicename' );
		}

		$data['name'] = $node->textContent;

		if ( empty( $data['slug'] ) ) {
			return null;
		}

		// Just for extra compatibility
		if ( $data['taxonomy'] === 'tag' ) {
			$data['taxonomy'] = 'post_tag';
		}

		return $data;
	}

	/**
	 * Callback for `usort` to sort comments by ID
	 *
	 * @param array $a Comment data for the first comment
	 * @param array $b Comment data for the second comment
	 *
	 * @return int
	 */
	public static function sort_comments_by_id( $a, $b ) {
		if ( empty( $a['comment_id'] ) ) {
			return 1;
		}

		if ( empty( $b['comment_id'] ) ) {
			return -1;
		}

		return $a['comment_id'] - $b['comment_id'];
	}

	protected function parse_author_node( $node ) {
		$data = array();
		$meta = array();
		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			switch ( $child->tagName ) {
				case 'wp:author_login':
					$data['user_login'] = $child->textContent;
					break;

				case 'wp:author_id':
					$data['ID'] = $child->textContent;
					break;

				case 'wp:author_email':
					$data['user_email'] = $child->textContent;
					break;

				case 'wp:author_display_name':
					$data['display_name'] = $child->textContent;
					break;

				case 'wp:author_first_name':
					$data['first_name'] = $child->textContent;
					break;

				case 'wp:author_last_name':
					$data['last_name'] = $child->textContent;
					break;
			}
		}

		return compact( 'data', 'meta' );
	}

	protected function process_author( $data, $meta ) {
		/**
		 * Pre-process user data.
		 *
		 * @param array $data User data. (Return empty to skip.)
		 * @param array $meta Meta data.
		 */
		$data = apply_filters( 'wxr_importer.pre_process.user', $data, $meta );
		if ( empty( $data ) ) {
			return false;
		}

		// Have we already handled this user?
		$original_id   = isset( $data['ID'] ) ? $data['ID'] : 0;
		$original_slug = $data['user_login'];

		if ( isset( $this->mapping['user'][ $original_id ] ) ) {
			$existing = $this->mapping['user'][ $original_id ];

			// Note the slug mapping if we need to too
			if ( ! isset( $this->mapping['user_slug'][ $original_slug ] ) ) {
				$this->mapping['user_slug'][ $original_slug ] = $existing;
			}

			return false;
		}

		if ( isset( $this->mapping['user_slug'][ $original_slug ] ) ) {
			$existing = $this->mapping['user_slug'][ $original_slug ];

			// Ensure we note the mapping too
			$this->mapping['user'][ $original_id ] = $existing;

			return false;
		}

		// Allow overriding the user's slug
		$login = $original_slug;
		if ( isset( $this->user_slug_override[ $login ] ) ) {
			$login = $this->user_slug_override[ $login ];
		}

		$userdata = array(
			'user_login' => sanitize_user( $login, true ),
			'user_pass'  => wp_generate_password(),
		);

		$allowed = array(
			'user_email'   => true,
			'display_name' => true,
			'first_name'   => true,
			'last_name'    => true,
		);
		foreach ( $data as $key => $value ) {
			if ( ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$userdata[ $key ] = $data[ $key ];
		}

		$user_id = wp_insert_user( wp_slash( $userdata ) );

		if ( is_wp_error( $user_id ) ) {

			if ( ! $user_id->errors['existing_user_login'] ) {
				if ( WP_DEBUG ) {
					$this->logger->error( sprintf(
						__( 'Failed to import user "%s"', 'insight-core' ),
						$userdata['user_login']
					) );
					$this->logger->debug( $user_id->get_error_message() );
				}
			}
			/**
			 * User processing failed.
			 *
			 * @param WP_Error $user_id  Error object.
			 * @param array    $userdata Raw data imported for the user.
			 */
			do_action( 'wxr_importer.process_failed.user', $user_id, $userdata );

			return false;
		}

		if ( $original_id ) {
			$this->mapping['user'][ $original_id ] = $user_id;
		}
		$this->mapping['user_slug'][ $original_slug ] = $user_id;

		if ( WP_DEBUG ) {
			$this->logger->info( sprintf(
				__( 'Imported user "%s"', 'insight-core' ),
				$userdata['user_login']
			) );
			$this->logger->debug( sprintf(
				__( 'User %d remapped to %d', 'insight-core' ),
				$original_id,
				$user_id
			) );
		}

		/**
		 * User processing completed.
		 *
		 * @param int   $user_id  New user ID.
		 * @param array $userdata Raw data imported for the user.
		 */
		do_action( 'wxr_importer.processed.user', $user_id, $userdata );
	}

	protected function parse_term_node( $node, $type = 'term' ) {
		$data = array();
		$meta = array();

		$tag_name = array(
			'id'          => 'wp:term_id',
			'taxonomy'    => 'wp:term_taxonomy',
			'slug'        => 'wp:term_slug',
			/* note: WP 4.5+ exports the slug of the parent term, not the id */
			'parent_slug' => 'wp:term_parent',
			'name'        => 'wp:term_name',
			'description' => 'wp:term_description',
			'meta'        => 'wp:termmeta',
		);
		$taxonomy = null;

		// Special casing!
		switch ( $type ) {
			case 'category':
				$tag_name['slug']        = 'wp:category_nicename';
				$tag_name['parent_slug'] = 'wp:category_parent';
				$tag_name['name']        = 'wp:cat_name';
				$tag_name['description'] = 'wp:category_description';
				$tag_name['taxonomy']    = null;

				$data['taxonomy'] = 'category';
				break;

			case 'tag':
				$tag_name['slug']        = 'wp:tag_slug';
				$tag_name['parent_slug'] = null;
				$tag_name['name']        = 'wp:tag_name';
				$tag_name['description'] = 'wp:tag_description';
				$tag_name['taxonomy']    = null;

				$data['taxonomy'] = 'post_tag';
				break;
		}

		foreach ( $node->childNodes as $child ) {
			// We only care about child elements
			if ( $child->nodeType !== XML_ELEMENT_NODE ) {
				continue;
			}

			if ( $child->tagName == $tag_name['meta'] ) {
				$result = $this->parse_meta_node( $child );

				if ( ! empty( $result ) && isset( $result['key'] ) && isset( $result['value'] ) ) {
					$meta[] = array( 'key' => $result['key'], 'value' => $result['value'] );
				}
			}

			$key = array_search( $child->tagName, $tag_name );
			if ( $key ) {
				$data[ $key ] = $child->textContent;
			}
		}

		if ( empty( $data['taxonomy'] ) ) {
			return null;
		}

		// Compatibility with WXR 1.0
		if ( $data['taxonomy'] === 'tag' ) {
			$data['taxonomy'] = 'post_tag';
		}

		return compact( 'data', 'meta' );
	}

	protected function process_term( $data, $meta ) {
		/**
		 * Pre-process term data.
		 *
		 * @param array $data Term data. (Return empty to skip.)
		 * @param array $meta Meta data.
		 */
		$data = apply_filters( 'wxr_importer.pre_process.term', $data, $meta );
		if ( empty( $data ) ) {
			return false;
		}

		$original_id = isset( $data['id'] ) ? $data['id'] : 0;
		$term_slug   = isset( $data['slug'] ) ? $data['slug'] : '';

		/* As of WP 4.5, export.php returns the SLUG for the term's parent,
		 * rather than an integer ID (this differs from a post_parent)
		 * wp_insert_term and wp_update_term use the key: 'parent' and an integer value 'id'
		 * use both keys: 'parent' and 'parent_slug'
		 */
		$parent_slug = isset( $data['parent_slug'] ) ? $data['parent_slug'] : '';

		$mapping_key = sha1( $data['taxonomy'] . ':' . $data['slug'] );
		$existing    = $this->term_exists( $data );
		if ( $existing ) {
			$this->mapping['term'][ $mapping_key ]    = $existing;
			$this->mapping['term_id'][ $original_id ] = $existing;
			$this->mapping['term_slug'][ $term_slug ] = $existing;

			return false;
		}

		// WP really likes to repeat itself in export files
		if ( isset( $this->mapping['term'][ $mapping_key ] ) ) {
			return false;
		}

		$termdata = array();
		$allowed  = array(
			'slug'        => true,
			'description' => true,
			'parent'      => true,
		);

		// Map the parent term, or mark it as one we need to fix
		$requires_remapping = false;
		if ( $parent_slug ) {
			if ( isset( $this->mapping['term_slug'][ $parent_slug ] ) ) {
				$data['parent'] = $this->mapping['term_slug'][ $parent_slug ];
			} else {
				// Prepare for remapping later
				$meta[]             = array( 'key' => '_wxr_import_parent', 'value' => $parent_slug );
				$requires_remapping = true;

				// Wipe the parent for now
				$data['parent'] = 0;
			}
		}

		foreach ( $data as $key => $value ) {
			if ( ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$termdata[ $key ] = $data[ $key ];
		}

		$result = wp_insert_term( $data['name'], $data['taxonomy'], $termdata );

		if ( is_wp_error( $result ) ) {

			if ( WP_DEBUG ) {
				$this->logger->warning( sprintf(
					__( 'Failed to import %s %s', 'insight-core' ),
					$data['taxonomy'],
					$data['name']
				) );
				$this->logger->debug( $result->get_error_message() );
			}
			do_action( 'wp_import_insert_term_failed', $result, $data );

			/**
			 * Term processing failed.
			 *
			 * @param WP_Error $result Error object.
			 * @param array    $data   Raw data imported for the term.
			 * @param array    $meta   Meta data supplied for the term.
			 */
			do_action( 'wxr_importer.process_failed.term', $result, $data, $meta );

			return false;
		}

		$term_id = $result['term_id'];
		// now prepare to map this new term
		$this->mapping['term'][ $mapping_key ]    = $term_id;
		$this->mapping['term_id'][ $original_id ] = $term_id;
		$this->mapping['term_slug'][ $term_slug ] = $term_id;

		/* the parent will be updated later in post_process_terms
		 * we will need both the term_id AND the term_taxonomy to retrieve existing
		 * term attributes. Those attributes will be returned with the corrected parent,
		 * using wp_update_term.
		 * Pass both the term_id along with the term_taxonomy as key=>value
		 * in the requires_remapping['term'] array.
		 */
		if ( $requires_remapping ) {
			$this->requires_remapping['term'][ $term_id ] = $data['taxonomy'];
		}

		/* insert termmeta, if any, including the flag to remap the parent '_wxr_import_parent' */
		if ( ! empty( $meta ) ) {
			foreach ( $meta as $meta_item ) {
				$result = add_term_meta( $term_id, $meta_item['key'], $meta_item['value'] );
				if ( is_wp_error( $result ) ) {
					if ( WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'Failed to add metakey: %s, metavalue: %s to term_id: %d', 'wordpress-importer' ),
							$meta_item['key'], $meta_item['value'], $term_id ) );
					}
					do_action( 'wxr_importer.process_failed.termmeta', $result, $data, $meta );
				} else {
					if ( WP_DEBUG ) {
						$this->logger->debug( sprintf(
							__( 'Meta for term_id %d : %s => %s ; successfully added!', 'wordpress-importer' ),
							$term_id, $meta_item['key'], $meta_item['value'] ) );
					}
				}
			}
		}

		if ( WP_DEBUG ) {
			$this->logger->info( sprintf(
				__( 'Imported "%s" (%s)', 'insight-core' ),
				$data['name'],
				$data['taxonomy']
			) );
			$this->logger->debug( sprintf(
				__( 'Term %d remapped to %d', 'insight-core' ),
				$original_id,
				$term_id
			) );
		}

		do_action( 'wp_import_insert_term', $term_id, $data );

		/**
		 * Term processing completed.
		 *
		 * @param int   $term_id New term ID.
		 * @param array $data    Raw data imported for the term.
		 */
		do_action( 'wxr_importer.processed.term', $term_id, $data );
	}

	/**
	 * Process and import term meta items.
	 *
	 * @param array $meta    List of meta data arrays
	 * @param int   $term_id Term ID to associate with
	 * @param array $term    Term data
	 *
	 * @return int|WP_Error Number of meta items imported on success, error otherwise.
	 */
	protected function process_term_meta( $meta, $term_id, $term ) {
		if ( empty( $meta ) ) {
			return true;
		}

		foreach ( $meta as $meta_item ) {
			/**
			 * Pre-process term meta data.
			 *
			 * @param array $meta_item Meta data. (Return empty to skip.)
			 * @param int   $term_id   Term the meta is attached to.
			 */
			$meta_item = apply_filters( 'wxr_importer.pre_process.term_meta', $meta_item, $term_id );
			if ( empty( $meta_item ) ) {
				return false;
			}

			$key   = apply_filters( 'import_term_meta_key', $meta_item['key'], $term_id, $term );
			$value = false;
			if ( $key ) {
				// export gets meta straight from the DB so could have a serialized string
				if ( ! $value ) {
					$value = maybe_unserialize( $meta_item['value'] );
				}

				add_term_meta( $term_id, $key, $value );
				do_action( 'import_term_meta', $term_id, $key, $value );
			}
		}

		return true;
	}

	/**
	 * Attempt to download a remote file attachment
	 *
	 * @param string $url  URL of item to fetch
	 * @param array  $post Attachment details
	 *
	 * @return array|WP_Error Local file location details on success, WP_Error otherwise
	 */
	protected function fetch_remote_file( $url, $post ) {
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
		if ( $upload['error'] ) {
			return new WP_Error( 'upload_dir_error', $upload['error'] );
		}

		// Disable dump remote files.
		/*if ( $this->options['import_full_demo'] ) {
			if ( WP_DEBUG ) {
				var_dump( $url );
			}
		}*/

		// fetch the remote url and write it to the placeholder file
		$response = wp_remote_get( $url, array(
			'stream'   => true,
			'filename' => $upload['file'],
		) );

		// request failed
		if ( is_wp_error( $response ) ) {
			unlink( $upload['file'] );

			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		// make sure the fetch was successful
		if ( $code !== 200 ) {
			unlink( $upload['file'] );

			return new WP_Error(
				'import_file_error',
				sprintf(
					__( 'Remote server returned %1$d %2$s for %3$s', 'insight-core' ),
					$code,
					get_status_header_desc( $code ),
					$url
				)
			);
		}

		$filesize = filesize( $upload['file'] );
		$headers  = wp_remote_retrieve_headers( $response );

		if ( isset( $headers['content-length'] ) && $filesize !== (int) $headers['content-length'] ) {
			@unlink( $upload['file'] );

			return new WP_Error( 'import_file_error', __( 'Remote file is incorrect size', 'insight-core' ) );
		}

		if ( 0 === $filesize ) {
			@unlink( $upload['file'] );

			return new WP_Error( 'import_file_error', __( 'Zero size file downloaded', 'insight-core' ) );
		}

		$max_size = (int) $this->max_attachment_size();
		if ( ! empty( $max_size ) && $filesize > $max_size ) {
			@unlink( $upload['file'] );
			$message = sprintf( __( 'Remote file is too large, limit is %s', 'insight-core' ), size_format( $max_size ) );

			return new WP_Error( 'import_file_error', $message );
		}

		return $upload;
	}

	protected function post_process() {
		// Time to tackle any left-over bits
		if ( ! empty( $this->requires_remapping['post'] ) ) {
			$this->post_process_posts( $this->requires_remapping['post'] );
		}
		if ( ! empty( $this->requires_remapping['comment'] ) ) {
			$this->post_process_comments( $this->requires_remapping['comment'] );
		}
		if ( ! empty( $this->requires_remapping['term'] ) ) {
			$this->post_process_terms( $this->requires_remapping['term'] );
		}
	}

	protected function post_process_posts( $todo ) {
		foreach ( $todo as $post_id => $_ ) {

			if ( WP_DEBUG ) {
				$this->logger->debug( sprintf(
				// Note: title intentionally not used to skip extra processing
				// for when debug logging is off
					__( 'Running post-processing for post %d', 'insight-core' ),
					$post_id
				) );
			}

			$data = array();

			$parent_id = get_post_meta( $post_id, '_wxr_import_parent', true );
			if ( ! empty( $parent_id ) ) {
				// Have we imported the parent now?
				if ( isset( $this->mapping['post'][ $parent_id ] ) ) {
					$data['post_parent'] = $this->mapping['post'][ $parent_id ];
				} else {
					if ( WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'Could not find the post parent for "%s" (post #%d)', 'insight-core' ),
							get_the_title( $post_id ),
							$post_id
						) );
						$this->logger->debug( sprintf(
							__( 'Post %d was imported with parent %d, but could not be found', 'insight-core' ),
							$post_id,
							$parent_id
						) );
					}
				}
			}

			$author_slug = get_post_meta( $post_id, '_wxr_import_user_slug', true );

			if ( ! empty( $author_slug ) ) {
				// Have we imported the user now?
				if ( isset( $this->mapping['user_slug'][ $author_slug ] ) ) {
					$data['post_author'] = $this->mapping['user_slug'][ $author_slug ];
				} else {
					if ( WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'Could not find the author for "%s" (post #%d)', 'insight-core' ),
							get_the_title( $post_id ),
							$post_id
						) );
						$this->logger->debug( sprintf(
							__( 'Post %d was imported with author "%s", but could not be found', 'insight-core' ),
							$post_id,
							$author_slug
						) );
					}
				}
			}

			$has_attachments = get_post_meta( $post_id, '_wxr_import_has_attachment_refs', true );
			if ( ! empty( $has_attachments ) ) {
				$post    = get_post( $post_id );
				$content = $post->post_content;

				// Replace all the URLs we've got
				$new_content = str_replace( array_keys( $this->url_remap ), $this->url_remap, $content );
				if ( $new_content !== $content ) {
					$data['post_content'] = $new_content;
				}
			}

			if ( get_post_type( $post_id ) === 'nav_menu_item' ) {
				$this->post_process_menu_item( $post_id );
			}

			// Do we have updates to make?
			if ( empty( $data ) ) {
				if ( WP_DEBUG ) {
					$this->logger->debug( sprintf(
						__( 'Post %d was marked for post-processing, but none was required.', 'insight-core' ),
						$post_id
					) );
				}
				continue;
			}

			// Run the update
			$data['ID'] = $post_id;
			$result     = wp_update_post( $data, true );
			if ( is_wp_error( $result ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'Could not update "%s" (post #%d) with mapped data', 'insight-core' ),
						get_the_title( $post_id ),
						$post_id
					) );
					$this->logger->debug( $result->get_error_message() );
				}
				continue;
			}

			// Clear out our temporary meta keys
			delete_post_meta( $post_id, '_wxr_import_parent' );
			delete_post_meta( $post_id, '_wxr_import_user_slug' );
			delete_post_meta( $post_id, '_wxr_import_has_attachment_refs' );
		}
	}

	protected function post_process_menu_item( $post_id ) {
		$menu_object_id = get_post_meta( $post_id, '_wxr_import_menu_item', true );
		if ( empty( $menu_object_id ) ) {
			// No processing needed!
			return;
		}

		$menu_item_type = get_post_meta( $post_id, '_menu_item_type', true );
		switch ( $menu_item_type ) {
			case 'taxonomy':
				if ( isset( $this->mapping['term_id'][ $menu_object_id ] ) ) {
					$menu_object = $this->mapping['term_id'][ $menu_object_id ];
				}
				break;

			case 'post_type':
				if ( isset( $this->mapping['post'][ $menu_object_id ] ) ) {
					$menu_object = $this->mapping['post'][ $menu_object_id ];
				}
				break;

			default:
				// Cannot handle this.
				return;
		}

		if ( ! empty( $menu_object ) ) {
			update_post_meta( $post_id, '_menu_item_object_id', wp_slash( $menu_object ) );
		} else {
			if ( WP_DEBUG ) {
				$this->logger->warning( sprintf(
					__( 'Could not find the menu object for "%s" (post #%d)', 'insight-core' ),
					get_the_title( $post_id ),
					$post_id
				) );
				$this->logger->debug( sprintf(
					__( 'Post %d was imported with object "%d" of type "%s", but could not be found', 'insight-core' ),
					$post_id,
					$menu_object_id,
					$menu_item_type
				) );
			}
		}

		delete_post_meta( $post_id, '_wxr_import_menu_item' );
	}

	protected function post_process_terms( $terms_to_be_remapped ) {

		/* There is no explicit 'top' or 'root' for a hierarchy of WordPress terms
		  * Terms without a parent, or parent=0 are either unconnected (orphans)
		  * or top-level siblings without an explicit root parent
		  * An unconnected term (orphan) should have a null parent_slug
		  * Top-level siblings without an explicit root parent, shall be identified
		  * with the parent_slug: top
		  * [we'll map parent_slug: top into parent 0]
		  */
		$this->mapping['term_slug']['top'] = 0;

		// the term_id and term_taxonomy are passed-in with $this->requires_remapping['term']
		foreach ( $terms_to_be_remapped as $termid => $term_taxonomy ) {
			// basic check
			if ( empty( $termid ) or ! ( is_numeric( $termid ) ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'Faulty term_id provided in terms-to-be-remapped array %s', 'wordpress-importer' ),
						$termid
					) );
				}
				continue;
			}
			/* this cast to integer may be unnecessary */
			$term_id = (int) $termid;

			if ( empty( $term_taxonomy ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'No taxonomy provided in terms-to-be-remapped array for term #%d', 'wordpress-importer' ),
						$term_id
					) );
				}
				continue;
			}

			$parent_slug = get_term_meta( $term_id, '_wxr_import_parent', true );

			if ( empty( $parent_slug ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'No parent_slug identified in remapping-array for term: %d', 'wordpress-importer' ),
						$term_id
					) );
				}
				continue;
			}

			if ( ! isset( $this->mapping['term_slug'][ $parent_slug ] ) or ! is_numeric( $this->mapping['term_slug'][ $parent_slug ] ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'The term(%d)"s parent_slug (%s) is not found in the remapping-array.', 'wordpress-importer' ),
						$term_id,
						$parent_slug
					) );
				}
				continue;
			}

			$mapped_parent = (int) $this->mapping['term_slug'][ $parent_slug ];

			$termattributes = get_term_by( 'id', $term_id, $term_taxonomy, ARRAY_A );
			// note: the default OBJECT return results in a reserved-word clash with 'parent' [$termattributes->parent], so instead return an associative array

			if ( empty( $termattributes ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'No data returned by get_term_by for term_id #%d', 'wordpress-importer' ),
						$term_id
					) );
				}
				continue;
			}
			// check if the correct parent id is already correctly mapped
			if ( isset( $termattributes['parent'] ) && $termattributes['parent'] == $mapped_parent && WP_DEBUG ) {
				// Clear out our temporary meta key
				delete_term_meta( $term_id, '_wxr_import_parent' );
				continue;
			}

			// otherwise set the mapped parent and update the term
			$termattributes['parent'] = $mapped_parent;

			$result = wp_update_term( $term_id, $termattributes['taxonomy'], $termattributes );

			if ( is_wp_error( $result ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'Could not update "%s" (term #%d) with mapped data', 'wordpress-importer' ),
						$termattributes['name'],
						$term_id
					) );
					$this->logger->debug( $result->get_error_message() );
				}
				continue;
			}
			// Clear out our temporary meta key
			delete_term_meta( $term_id, '_wxr_import_parent' );

			if ( WP_DEBUG ) {
				$this->logger->debug( sprintf(
					__( 'Term %d was successfully updated with parent %d', 'wordpress-importer' ),
					$term_id,
					$mapped_parent
				) );
			}
		}
	}

	protected function post_process_comments( $todo ) {
		foreach ( $todo as $comment_id => $_ ) {
			$data = array();

			$parent_id = get_comment_meta( $comment_id, '_wxr_import_parent', true );
			if ( ! empty( $parent_id ) ) {
				// Have we imported the parent now?
				if ( isset( $this->mapping['comment'][ $parent_id ] ) ) {
					$data['comment_parent'] = $this->mapping['comment'][ $parent_id ];
				} else {
					if ( WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'Could not find the comment parent for comment #%d', 'insight-core' ),
							$comment_id
						) );
						$this->logger->debug( sprintf(
							__( 'Comment %d was imported with parent %d, but could not be found', 'insight-core' ),
							$comment_id,
							$parent_id
						) );
					}
				}
			}

			$author_id = get_comment_meta( $comment_id, '_wxr_import_user', true );
			if ( ! empty( $author_id ) && WP_DEBUG ) {
				// Have we imported the user now?
				if ( isset( $this->mapping['user'][ $author_id ] ) ) {
					$data['user_id'] = $this->mapping['user'][ $author_id ];
				} else {
					if ( WP_DEBUG ) {
						$this->logger->warning( sprintf(
							__( 'Could not find the author for comment #%d', 'insight-core' ),
							$comment_id
						) );
						$this->logger->debug( sprintf(
							__( 'Comment %d was imported with author %d, but could not be found', 'insight-core' ),
							$comment_id,
							$author_id
						) );
					}
				}
			}

			// Do we have updates to make?
			if ( empty( $data ) ) {
				continue;
			}

			// Run the update
			$data['comment_ID'] = $comment_id;
			$result             = wp_update_comment( wp_slash( $data ) );
			if ( empty( $result ) ) {
				if ( WP_DEBUG ) {
					$this->logger->warning( sprintf(
						__( 'Could not update comment #%d with mapped data', 'insight-core' ),
						$comment_id
					) );
				}
				continue;
			}

			// Clear out our temporary meta keys
			delete_comment_meta( $comment_id, '_wxr_import_parent' );
			delete_comment_meta( $comment_id, '_wxr_import_user' );
		}
	}

	/**
	 * Use stored mapping information to update old attachment URLs
	 */
	protected function replace_attachment_urls_in_content() {
		global $wpdb;
		// make sure we do the longest urls first, in case one is a substring of another
		uksort( $this->url_remap, array( $this, 'cmpr_strlen' ) );

		foreach ( $this->url_remap as $from_url => $to_url ) {
			// remap urls in post_content
			$query = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url );
			$wpdb->query( $query );

			// remap enclosure urls
			$query  = $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url );
			$result = $wpdb->query( $query );
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
				if ( $new_id !== $value ) {
					update_post_meta( $post_id, '_thumbnail_id', $new_id );
				}
			}
		}
	}

	/**
	 * Decide if the given meta key maps to information we will want to import
	 *
	 * @param string $key The meta key to check
	 *
	 * @return string|bool The key if we do want to import, false if not
	 */
	public function is_valid_meta_key( $key ) {
		// skip attachment metadata since we'll regenerate it from scratch
		// skip _edit_lock as not relevant for import
		// removed _wp_attachment_metadata form 1.5.4.2
		if ( in_array( $key, array( '_wp_attached_file', '_edit_lock' ) ) ) {
			return false;
		}

		return $key;
	}

	/**
	 * Decide what the maximum file size for downloaded attachments is.
	 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
	 *
	 * @return int Maximum attachment file size to import
	 */
	protected function max_attachment_size() {
		return apply_filters( 'import_attachment_size_limit', 0 );
	}

	/**
	 * Added to http_request_timeout filter to force timeout at 60 seconds during import
	 *
	 * @access protected
	 * @return int 60
	 */
	function bump_request_timeout( $val ) {
		return 60;
	}

// return the difference in length between two strings
	function cmpr_strlen( $a, $b ) {
		return strlen( $b ) - strlen( $a );
	}

	/**
	 * Prefill existing post data.
	 *
	 * This preloads all GUIDs into memory, allowing us to avoid hitting the
	 * database when we need to check for existence. With larger imports, this
	 * becomes prohibitively slow to perform SELECT queries on each.
	 *
	 * By preloading all this data into memory, it's a constant-time lookup in
	 * PHP instead. However, this does use a lot more memory, so for sites doing
	 * small imports onto a large site, it may be a better tradeoff to use
	 * on-the-fly checking instead.
	 */
	protected function prefill_existing_posts() {
		global $wpdb;
		$posts = $wpdb->get_results( "SELECT ID, guid FROM {$wpdb->posts}" );

		foreach ( $posts as $item ) {
			$this->exists['post'][ $item->guid ] = $item->ID;
		}
	}

	/**
	 * Does the post exist?
	 *
	 * @param array $data Post data to check against.
	 *
	 * @return int|bool Existing post ID if it exists, false otherwise.
	 */
	protected function post_exists( $data ) {

		if ( $data['post_type'] == 'product_variation' ) {
			return post_exists( $data['post_title'], $data['post_content'], $data['post_date'] );
		}

		// Constant-time lookup if we prefilled
		$exists_key = $data['guid'];

		if ( $this->options['prefill_existing_posts'] ) {
			return isset( $this->exists['post'][ $exists_key ] ) ? $this->exists['post'][ $exists_key ] : false;
		}

		// No prefilling, but might have already handled it
		if ( isset( $this->exists['post'][ $exists_key ] ) ) {
			return $this->exists['post'][ $exists_key ];
		}

		// Still nothing, try post_exists, and cache it
		$exists                              = post_exists( $data['post_title'], $data['post_content'], $data['post_date'] );
		$this->exists['post'][ $exists_key ] = $exists;

		return $exists;
	}

	/**
	 * Mark the post as existing.
	 *
	 * @param array $data    Post data to mark as existing.
	 * @param int   $post_id Post ID.
	 */
	protected function mark_post_exists( $data, $post_id ) {
		$exists_key                          = $data['guid'];
		$this->exists['post'][ $exists_key ] = $post_id;
	}

	/**
	 * Prefill existing comment data.
	 *
	 * @see self::prefill_existing_posts() for justification of why this exists.
	 */
	protected function prefill_existing_comments() {
		global $wpdb;
		$posts = $wpdb->get_results( "SELECT comment_ID, comment_author, comment_date FROM {$wpdb->comments}" );

		foreach ( $posts as $item ) {
			$exists_key                             = sha1( $item->comment_author . ':' . $item->comment_date );
			$this->exists['comment'][ $exists_key ] = $item->comment_ID;
		}
	}

	/**
	 * Does the comment exist?
	 *
	 * @param array $data Comment data to check against.
	 *
	 * @return int|bool Existing comment ID if it exists, false otherwise.
	 */
	protected function comment_exists( $data ) {
		$exists_key = sha1( $data['comment_author'] . ':' . $data['comment_date'] );

		// Constant-time lookup if we prefilled
		if ( $this->options['prefill_existing_comments'] ) {
			return isset( $this->exists['comment'][ $exists_key ] ) ? $this->exists['comment'][ $exists_key ] : false;
		}

		// No prefilling, but might have already handled it
		if ( isset( $this->exists['comment'][ $exists_key ] ) ) {
			return $this->exists['comment'][ $exists_key ];
		}

		// Still nothing, try comment_exists, and cache it
		$exists                                 = comment_exists( $data['comment_author'], $data['comment_date'] );
		$this->exists['comment'][ $exists_key ] = $exists;

		return $exists;
	}

	/**
	 * Mark the comment as existing.
	 *
	 * @param array $data       Comment data to mark as existing.
	 * @param int   $comment_id Comment ID.
	 */
	protected function mark_comment_exists( $data, $comment_id ) {
		$exists_key                             = sha1( $data['comment_author'] . ':' . $data['comment_date'] );
		$this->exists['comment'][ $exists_key ] = $comment_id;
	}

	/**
	 * Prefill existing term data.
	 *
	 * @see self::prefill_existing_posts() for justification of why this exists.
	 */
	protected function prefill_existing_terms() {
		global $wpdb;
		$query = "SELECT t.term_id, tt.taxonomy, t.slug FROM {$wpdb->terms} AS t";
		$query .= " JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id";
		$terms = $wpdb->get_results( $query );

		foreach ( $terms as $item ) {
			$exists_key                          = sha1( $item->taxonomy . ':' . $item->slug );
			$this->exists['term'][ $exists_key ] = $item->term_id;
		}
	}

	/**
	 * Does the term exist?
	 *
	 * @param array $data Term data to check against.
	 *
	 * @return int|bool Existing term ID if it exists, false otherwise.
	 */
	protected function term_exists( $data ) {
		$exists_key = sha1( $data['taxonomy'] . ':' . $data['slug'] );

		// Constant-time lookup if we prefilled
		if ( $this->options['prefill_existing_terms'] ) {
			return isset( $this->exists['term'][ $exists_key ] ) ? $this->exists['term'][ $exists_key ] : false;
		}

		// No prefilling, but might have already handled it
		if ( isset( $this->exists['term'][ $exists_key ] ) ) {
			return $this->exists['term'][ $exists_key ];
		}

		// Still nothing, try term_exists, and cache it
		$exists = term_exists( $data['slug'], $data['taxonomy'] );
		if ( is_array( $exists ) ) {
			$exists = $exists['term_id'];
		}

		$this->exists['term'][ $exists_key ] = $exists;

		return $exists;
	}

	/**
	 * Mark the term as existing.
	 *
	 * @param array $data    Term data to mark as existing.
	 * @param int   $term_id Term ID.
	 */
	protected function mark_term_exists( $data, $term_id ) {
		$exists_key                          = sha1( $data['taxonomy'] . ':' . $data['slug'] );
		$this->exists['term'][ $exists_key ] = $term_id;
	}
}
