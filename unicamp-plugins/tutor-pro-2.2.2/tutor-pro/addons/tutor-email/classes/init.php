<?php
namespace TUTOR_EMAIL;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class init {
	public $version = TUTOR_EMAIL_VERSION;
	public $path;
	public $url;
	public $basename;

	// Module
	private $email_notification;

	function __construct() {
		if ( ! function_exists( 'tutor' ) ) {
			return;
		}
		$addonConfig = tutor_utils()->get_addon_config( TUTOR_EMAIL()->basename );
		$isEnable    = (bool) tutor_utils()->avalue_dot( 'is_enable', $addonConfig );
		if ( ! $isEnable ) {
			return;
		}

		$this->path     = plugin_dir_path( TUTOR_EMAIL_FILE );
		$this->url      = plugin_dir_url( TUTOR_EMAIL_FILE );
		$this->basename = plugin_basename( TUTOR_EMAIL_FILE );

		$this->load_TUTOR_EMAIL();
		add_action( 'admin_init', array( $this, 'tutor_image_size_register' ) );
	}


	public function tutor_image_size_register() {
		add_image_size( 'tutor-email-logo-size', 220, 50 );
	}

	public function load_TUTOR_EMAIL() {
		/**
		 * Loading Autoloader
		 */

		spl_autoload_register( array( $this, 'loader' ) );
		$this->email_notification = new EmailNotification();

		add_filter( 'tutor/options/attr', array( $this, 'add_options' ), 10 ); // Priority index is important. 'Content Drip' add-on uses 11.
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

			$className = str_replace( 'TUTOR_EMAIL' . DIRECTORY_SEPARATOR, 'classes' . DIRECTORY_SEPARATOR, $className );
			$file_name = $this->path . $className . '.php';

			if ( file_exists( $file_name ) && is_readable( $file_name ) ) {
				require_once $file_name;
			}
		}
	}


	// Run the TUTOR right now
	public function run() {
		register_activation_hook( TUTOR_EMAIL_FILE, array( $this, 'tutor_activate' ) );
	}

	/**
	 * Do some task during plugin activation
	 */
	public function tutor_activate() {
		$version = get_option( 'TUTOR_EMAIL_version' );
		// Save Option
		if ( ! $version ) {
			update_option( 'TUTOR_EMAIL_version', TUTOR_EMAIL_VERSION );
		}
	}

	private function get_recipient_array( $key = null ) {
		$recipients = ( new EmailData() )->get_recipients();

		if ( $key == null ) {
			$new_array = array();
			foreach ( $recipients as $recipient ) {
				$new_array = array_merge( $new_array, $recipient );
			}

			return $new_array;
		}

		$admin_url = admin_url( 'admin.php' );
		$array     = $recipients[ $key ];
		$fields    = array();

		foreach ( $recipients[ $key ] as $event => $mail ) {
			$tooltip        = ( isset( $mail['tooltip'] ) && ! empty( $mail['tooltip'] ) ) ? $mail['tooltip'] : null;
			$email_edit_url = add_query_arg(
				array(
					'page'     => 'tutor_settings',
					'tab_page' => 'email_notification',
					'edit'     => $event,
					'to'       => $key,
				),
				$admin_url
			);

			$fields[] = array(
				'key'      => $key,
				'event'    => $event,
				'type'     => 'toggle_switch_button',
				'label'    => $mail['label'],
				'template' => $mail['template'],
				'tooltip'  => $tooltip,
				'default'  => isset( $mail['default'] ) ? esc_attr( $mail['default'] ) : esc_attr( 'off' ),
				'buttons'  => array(
					'edit' => array(
						'type' => 'anchor',
						'text' => __( 'Edit', 'tutor-pro' ),
						'url'  => $email_edit_url,
					),
				),
			);
		}

		return $fields;
	}

	/**
	 * Email option and types
	 *
	 * @param  mixed $attr.
	 * @return array
	 */
	public function add_options( $attr ) {

		$template_path = isset( $_GET['edit'] ) ? TUTOR_EMAIL()->path . '/views/pages/email-edit.php' : null;

		$template_data = ! isset( $_GET['edit'] ) ? null : array(
			'to'          => sanitize_text_field( $_GET['to'] ),
			'key'         => sanitize_text_field( $_GET['edit'] ),
			'to_readable' => ucwords( str_replace( '_', ' ', $_GET['to'] ) ),
			'mail'        => $this->get_recipient_array()[ sanitize_text_field( $_GET['edit'] ) ],
		);

		$attr['email_notification'] = array(
			'label'           => __( 'Email', 'tutor' ),
			'slug'            => 'email_notification',
			'desc'            => __( 'Email Settings', 'tutor' ),
			'template'        => 'basic',
			'icon'            => 'tutor-icon-envelope',
			'template_path'   => $template_path,
			'edit_email_data' => $template_data,
			'blocks'          => array(
				array(
					'label'      => __( 'Email Meta', 'tutor-pro' ),
					'slug'       => 'email_meta',
					'block_type' => 'uniform',
					'fields'     => array(
						array(
							'key'   => 'tutor_email_template_logo_id',
							'type'  => 'upload_full',
							'label' => __( 'Email Template Logo', 'tutor' ),
							'desc'  => array(
								'file_size'    => __( '100x36 pixels, Max height: 50px;', 'tutor-pro' ),
								'file_support' => __( 'jpg, .jpeg or .png.', 'tutor-pro' ),
							),
						),
						array(
							'key'     => 'email_logo_height',
							'type'    => 'number',
							'label'   => __( 'Email Logo Height', 'tutor' ),
							'default' => 30,
							'desc'    => __( 'Set the height of your email logo in pixels', 'tutor' ),
						),
						array(
							'key'     => 'email_disable_banner',
							'type'    => 'toggle_switch',
							'label'   => __( 'Disable Email Banner', 'tutor' ),
							'default' => 'off',
							'desc'    => __( 'Enable to hide email banner', 'tutor' ),
						),
						array(
							'key'         => 'email_from_name',
							'type'        => 'text',
							'label'       => __( 'Name', 'tutor' ),
							'placeholder' => __( 'Sener\'s Name', 'tutor' ),
							'default'     => get_option( 'blogname' ),
							'desc'        => __( 'The name under which all the emails will be sent', 'tutor' ),
						),
						array(
							'key'         => 'email_from_address',
							'type'        => 'email',
							'label'       => __( 'E-Mail Address', 'tutor' ),
							'placeholder' => __( 'Reply to E-Mail', 'tutor' ),
							'default'     => wp_get_current_user()->user_email,
							'desc'        => __( 'The E-Mail address from which all emails will be sent', 'tutor' ),
						),
						array(
							'key'         => 'email_footer_text',
							'type'        => 'editor_full',
							'label'       => __( 'E-Mail Footer Text', 'tutor' ),
							'placeholder' => __( 'Footer text for E-mail', 'tutor' ),
							'default'     => '<p style="text-align:center;color:#757C8E;">{site_name} © ' . __( '2022 All Rights Reserved', 'tutor' ) . '.</p>
							<p style="text-align:center;color:#41454F;padding-bottom:30px;"><a style="text-decoration: none;color: inherit;" href="#">' . __( 'Privacy & Policy', 'tutor' ) . '</a> <span>⋅</span> <a style="text-decoration: none;color: inherit;" href="#">' . __( 'Terms & Conditions', 'tutor' ) . '</a></p>',
							'desc'        => __( 'The text to appear in E-Mail template footer', 'tutor' ),
						),
					),
				),
				array(
					'label'      => __( 'Email to Students', 'tutor-pro' ),
					'slug'       => 'email_to_students',
					'block_type' => 'uniform',
					'fields'     => $this->get_recipient_array( 'email_to_students' ),
				),
				array(
					'label'      => __( 'Email to Teachers', 'tutor-pro' ),
					'slug'       => 'email_to_teachers',
					'block_type' => 'uniform',
					'fields'     => $this->get_recipient_array( 'email_to_teachers' ),
				),
				array(
					'label'      => __( 'Email to Admin', 'tutor-pro' ),
					'slug'       => 'email_to_admin',
					'block_type' => 'uniform',
					'fields'     => $this->get_recipient_array( 'email_to_admin' ),
				),
				array(
					'label'      => __( 'Email Sending', 'tutor-pro' ),
					'slug'       => 'email_sending',
					'block_type' => 'uniform',
					'fields'     => array(
						array(
							'key'     => 'tutor_email_disable_wpcron',
							'label'   => __( 'WP Cron for Bulk Mailing', 'tutor-pro' ),
							'type'    => 'toggle_switch',
							'default' => 'off',
							'desc'    => __( 'Enable this option to let Tutor LMS use WordPress native scheduler for email sending activities', 'tutor-pro' ),
						),
						array(
							'key'     => 'tutor_email_cron_frequency',
							'label'   => __( 'WP Email Cron Frequency', 'tutor-pro' ),
							'type'    => 'number',
							'default' => '300',
							'desc'    => __( 'Add the frequency mode in <strong>Second(s)</strong> which the Cron Setup will run', 'tutor-pro' ),
						),
						array(
							'key'     => 'tutor_bulk_email_limit',
							'label'   => __( 'Email Per Cron Execution', 'tutor-pro' ),
							'type'    => 'number',
							'default' => '10',
							'desc'    => __( 'Number of emails you\'d like to send per cron execution', 'tutor-pro' ),
						),
					),
				),
			),
		);

		return $attr;
	}
}
