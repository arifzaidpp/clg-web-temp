<?php

namespace Unicamp_Elementor;

defined( 'ABSPATH' ) || exit;

class Widget_Ajax_Handler {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'wp_ajax_unicamp_elementor_widget_user_register', [ $this, 'do_user_register' ] );
		add_action( 'wp_ajax_nopriv_unicamp_elementor_widget_user_register', [ $this, 'do_user_register' ] );
	}

	public function do_user_register() {
		if ( ! check_ajax_referer( 'unicamp_elementor_widget_user_register', 'unicamp_elementor_widget_user_register_nonce' ) ) {
			wp_die();
		}

		if ( \Unicamp_Helper::is_demo_site() ) {
			echo json_encode( \Unicamp_Helper::get_failed_demo_code() );
			wp_die();
		}

		$email      = $_POST['email'];
		$password   = $_POST['password'];
		$user_login = $_POST['username'];

		$userdata = [
			'user_login' => $user_login,
			'user_email' => $email,
			'user_pass'  => $password,
		];

		// Remove all illegal characters from email.
		$email = filter_var( $email, FILTER_SANITIZE_EMAIL );

		$success = false;
		$msg     = esc_html__( 'Username/Email address is existing', 'unicamp' );

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$success = false;
			$msg     = esc_html__( 'A valid email address is required', 'unicamp' );
		} else {
			$user_id = wp_insert_user( $userdata );

			if ( ! is_wp_error( $user_id ) ) {
				$creds                  = array();
				$creds['user_login']    = $user_login;
				$creds['user_email']    = $email;
				$creds['user_password'] = $password;
				$creds['remember']      = true;
				$user                   = wp_signon( $creds, false );

				$msg     = esc_html__( 'Congratulations, register successful, Redirecting...', 'unicamp' );
				$success = true;
			}
		}

		echo json_encode( [
			'success'  => $success,
			'messages' => $msg,
		] );

		wp_die();
	}
}

Widget_Ajax_Handler::instance()->initialize();
