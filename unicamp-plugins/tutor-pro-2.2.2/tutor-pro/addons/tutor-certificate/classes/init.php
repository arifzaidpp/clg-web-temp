<?php
namespace TUTOR_CERT;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class init {
	public $version = TUTOR_CERT_VERSION;
	public $path;
	public $url;
	public $basename;

	// Module
	public $certificate;

	function __construct() {
		if ( ! function_exists( 'tutor' ) ) {
			return;
		}
		$addonConfig = tutor_utils()->get_addon_config( TUTOR_CERT()->basename );
		$isEnable    = (bool) tutor_utils()->avalue_dot( 'is_enable', $addonConfig );
		if ( ! $isEnable ) {
			return;
		}

		$this->path     = plugin_dir_path( TUTOR_CERT_FILE );
		$this->url      = plugin_dir_url( TUTOR_CERT_FILE );
		$this->basename = plugin_basename( TUTOR_CERT_FILE );

		$this->load_TUTOR_CERT();

		new Instructor_Signature();

		add_filter( 'tutor_pages', function( array $pages ) {
			return $pages + array( 'tutor_certificate_page'   => __( 'Tutor Certificate', 'tutor' ) );
		});

		add_action( 'init', function() {
			if ( ! wp_doing_ajax() ) {
				$this->generate_tutor_certificate_page();
			}
		});
	}

	/**
	 * Create certificate page & update page ID to tutor option.
	 *
	 * @since 2.1.7
	 * 
	 * @return void
	 */
	private function generate_tutor_certificate_page() {
		$certificate_page_id = (int) tutor_utils()->get_option( 'tutor_certificate_page' );
		if ( in_array( $certificate_page_id, array( 0, -1 ) )  ) {
			$post_details = array(
				'post_title'   => __( 'Tutor Certificate', 'tutor' ),
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page'
			);
			$page_id = wp_insert_post( $post_details );
			update_tutor_option( 'tutor_certificate_page', $page_id );
		}
	}

	public function load_TUTOR_CERT() {
		/**
		 * Loading Autoloader
		 */

		spl_autoload_register( array( $this, 'loader' ) );
		$this->certificate = new Certificate();

		add_filter( 'tutor/options/extend/attr', array( $this, 'add_options' ), 10 );
	}

	/**
	 * @param $className
	 *
	 * Auto Load class and the files
	 */
	private function loader( $className ) {
		if ( ! class_exists( $className ) ) {
			$className = preg_replace(
				array( '/([a-z])([A-Z])/', '/\\\/' ),
				array( '$1$2', DIRECTORY_SEPARATOR ),
				$className
			);

			$className = str_replace( 'TUTOR_CERT' . DIRECTORY_SEPARATOR, 'classes' . DIRECTORY_SEPARATOR, $className );
			$file_name = $this->path . $className . '.php';

			if ( file_exists( $file_name ) && is_readable( $file_name ) ) {
				require_once $file_name;
			}
		}
	}


	// Run the TUTOR right now
	public function run() {
		register_activation_hook( TUTOR_CERT_FILE, array( $this, 'tutor_activate' ) );
	}

	/**
	 * Do some task during plugin activation
	 */
	public function tutor_activate() {
		$version = get_option( 'TUTOR_CERT_version' );
		// Save Option
		if ( ! $version ) {
			update_option( 'TUTOR_CERT_version', TUTOR_CERT_VERSION );
		}
	}

	public function add_options( $attr ) {
		$pages = tutor_utils()->get_pages();
		$attr['tutor_certificate'] = array(
			'label'    => __( 'Certificate', 'tutor-pro' ),
			'slug'     => 'tutor_certificate',
			'desc'     => __( 'All Certificate Settings', 'tutor-pro' ),
			'template' => 'tab',
			'icon'     => 'tutor-icon-certificate-landscape',
			'blocks'   => array(
				array(
					'label'      => __( 'Certificate Settings', 'tutor-pro' ),
					'slug'       => 'certificate_settings',
					'segments'	 => array(
						array(
							'label'      => __( 'Legacy Certificate Settings', 'tutor-pro' ),
							'block_type' => 'uniform',
							'fields'     => array(
								array(
									'key'     => 'tutor_cert_authorised_name',
									'type'    => 'text',
									'default' => __( 'Authorised Name', 'tutor-pro' ),
									'label'   => __( 'Authorised Name', 'tutor-pro' ),
									'desc'    => __( 'Authorised name will be printed under signature.', 'tutor-pro' ),
								),
								array(
									'key'     => 'tutor_cert_authorised_company_name',
									'type'    => 'text',
									'default' => __( 'Company Name', 'tutor-pro' ),
									'label'   => __( 'Authorised Company Name', 'tutor-pro' ),
									'desc'    => __( 'Authorised company name will be printed under authorised name.', 'tutor-pro' ),
								),
								array(
									'key'     => 'tutor_certificate_page',
									'type'    => 'select',
									'label'   => __( 'Certificate Page', 'tutor' ),
									'default' => '0',
									'options' => $pages,
									'desc'    => __( 'Choose the page for certificate.', 'tutor' ),
								),
								array(
									'key'         => 'show_instructor_name_on_certificate',
									'type'        => 'toggle_switch',
									'default'     => 'off',
									'label'       => __( 'Show instructor name on certificate', 'tutor-pro' ),
									'label_title' => __( '', 'tutor-pro' ),
									'desc'        => __( 'Show instructor name on certificate before Authorised Name', 'tutor-pro' ),
								),
								array(
									'key'         => 'send_certificate_link_to_course_completion_email',
									'type'        => 'toggle_switch',
									'default'     => 'off',
									'label'       => __( 'Certificate link in course completion email', 'tutor-pro' ),
									'label_title' => __( '', 'tutor-pro' ),
									'desc'        => __( 'Send certificate link along with the course completion email. Student must be logged in to access the certificate if public view is not enabled.', 'tutor-pro' ),
								),
								array(
									'key'     => 'tutor_cert_signature_image_id',
									'type'    => 'upload_full',
									'label'   => __( 'Upload Signature', 'tutor-pro' ),
									'default' => TUTOR_CERT()->url . 'assets/images/signature.png',
									'desc'    => __( 'Upload a signature that will be printed at certificate', 'tutor-pro' ),
								),
							),
						)
					)
				),
			),
		);

		return $attr;
	}
}
