<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_BP_Notifications' ) ) {
	class Unicamp_BP_Notifications extends Unicamp_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_unicamp_get_header_notifications', [ $this, 'get_header_notifications' ] );
		}

		public function get_header_notifications() {
			if ( ! is_user_logged_in() ) {
				wp_send_json_success( array(
					'message' => esc_html__( 'You need to be logged in.', 'unicamp' ),
				) );
			}

			$response = array();
			$html     = '';

			ob_start();

			bp_get_template_part( 'custom/notifications' );

			$html = ob_get_clean();

			if ( '' === $html ) {
				$html = '<li class="bs-item-wrap"><div class="notification-content">' . esc_html__( 'No new notifications!', 'unicamp' ) . '</div></li>';
			}

			$response['contents']            = $html;
			$response['total_notifications'] = bp_notifications_get_unread_notification_count( bp_displayed_user_id() );

			wp_send_json_success( $response );
		}
	}

	Unicamp_BP_Notifications::instance()->initialize();
}
