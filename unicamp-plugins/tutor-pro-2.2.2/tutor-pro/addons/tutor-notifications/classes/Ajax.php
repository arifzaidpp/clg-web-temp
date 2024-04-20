<?php
/**
 * Handles all ajax operations
 * 
 * @package tutor
 * 
 * @since 1.9.10
 */

namespace TUTOR_NOTIFICATIONS;
use \TUTOR_NOTIFICATIONS\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class
 */
class Ajax {

    /**
     * Public $utils_function
     * 
     * @var Utils $utils_function
     */
    public $utils_function;

    /**
     * Constructor
     */
    public function __construct() {

        $this->utils_function = new Utils();
        
        add_action( 'wp_ajax_tutor_get_all_notifications', array( $this, 'tutor_get_all_notifications' ) );
        add_action( 'wp_ajax_toggle_all_notifications_status_as_read', array( $this, 'toggle_all_notifications_status_as_read' ) );
        add_action( 'wp_ajax_toggle_single_notification_status_as_read', array( $this, 'toggle_single_notification_status_as_read' ) );
        add_action( 'wp_ajax_tutor_mark_all_notifications_as_unread', array( $this, 'tutor_mark_all_notifications_as_unread' ) );
    }

    /**
     * Get all notifications
     */
    public function tutor_get_all_notifications() {
        
        // Check and verify the request.
        tutor_utils()->checking_nonce();

        // All good, let's proceed.
        $all_notifications = $this->utils_function->get_all_notifications_by_current_user();
        wp_send_json_success( array(
            'notifications' => $all_notifications
        ) );
    }

    /**
     * Toggle notifications status as read
     */
    public function toggle_all_notifications_status_as_read() {

        // Check and verify the request.
        tutor_utils()->checking_nonce();

        // All good, let's proceed.
        $toggle_status = isset( $_POST['mark_as_read'] ) ? sanitize_text_field( $_POST['mark_as_read'] ) : false;

        if ( $toggle_status ) {
            $this->utils_function->mark_all_notifications_as_read();
            wp_send_json_success( array(
                'notifications' => $this->utils_function->get_all_notifications_by_current_user(),
            ) );
        } else {
            wp_send_json_error( array(
                'message' => __( 'Something went wrong. Please try again later', 'tutor-pro' ),
            ) );
        }
    }

    /**
     * Toggle single notification status as unread
     */
    public function toggle_single_notification_status_as_read() {

        // Check and verify the request.
        tutor_utils()->checking_nonce();

        // All good, let's proceed.
        $notification_id = isset( $_POST['notification_id'] ) ? absint( $_POST['notification_id'] ) : 0;

        if ( $notification_id ) {
            $this->utils_function->mark_single_notification_as_read( $notification_id );
            wp_send_json_success( array(
                'notifications' => $this->utils_function->get_all_notifications_by_current_user(),
            ) );
        } else {
            wp_send_json_error( array(
                'message' => __( 'Something went wrong. Please try again later', 'tutor-pro' ),
            ) );
        }
    }

    /**
     * Delete all notifications
     */
    public function tutor_mark_all_notifications_as_unread() {

        // Check and verify the request.
        tutor_utils()->checking_nonce();

        // All good, let's proceed.
        $mark_as_unread = isset( $_POST['mark_as_unread'] ) ? $_POST['mark_as_unread'] : false;

        if ( $mark_as_unread ) {
            $this->utils_function->mark_all_notifications_as_unread();
            wp_send_json_success( array(
                'notifications' => $this->utils_function->get_all_notifications_by_current_user(),
            ) );
        } else {
            wp_send_json_error( array(
                'message' => __( 'Something went wrong. Please try again later', 'tutor-pro' ),
            ) );
        }
    }
}