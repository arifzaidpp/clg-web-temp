<?php
/**
 * Handles all necessary utility functionalities
 * 
 * @package tutor
 * 
 * @since 1.9.10
 */

namespace TUTOR_NOTIFICATIONS;

defined( 'ABSPATH' ) || exit;

/**
 * Utils class
 */
class Utils {

    /**
     * Constructor
     */
    public function __construct() {
        if ( file_exists( 'pluggable.php' ) ) {
            include( ABSPATH . 'wp-includes/pluggable.php' );
        }
    }

    /**
     * Get all notifications of current user
     * 
     * @return array $notifications
     */
    public function get_all_notifications_by_current_user() {
        global $wpdb;

        $current_user_id = absint( get_current_user_id() );
        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $notifications = $wpdb->get_results( 
            "SELECT * FROM {$tablename}
             WHERE receiver_id = $current_user_id
             ORDER BY created_at DESC"
        );
        
        return $notifications;
    }

    /**
     * Mark all notifications status as read
     * 
     * @return bool
     */
    public function mark_all_notifications_as_read() {
        global $wpdb;

        $current_user_id = absint( get_current_user_id() );
        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $updated_status  = array(
            'status' => 'READ',
        );

        $where_clause    = array(
            'receiver_id' => $current_user_id,
            'status'      => 'UNREAD'
        );
        
        $status_updated = $wpdb->update( $tablename, $updated_status, $where_clause );

        return $status_updated;
    }

    /**
     * Mark all notifications status as unread
     * 
     * @return bool
     */
    public function mark_all_notifications_as_unread() {
        global $wpdb;

        $current_user_id = absint( get_current_user_id() );
        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $updated_status  = array(
            'status' => 'UNREAD',
        );

        $where_clause    = array(
            'receiver_id' => $current_user_id,
            'status'      => 'READ'
        );
        
        $status_updated = $wpdb->update( $tablename, $updated_status, $where_clause );

        return $status_updated;
    }

    /**
     * Mark a single notification status as read
     * 
     * @param int $notification_id
     * 
     * @return bool
     */
    public function mark_single_notification_as_read( $notification_id ) {
        global $wpdb;

        $current_user_id = absint( get_current_user_id() );
        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $updated_status  = array(
            'status' => 'READ',
        );

        $where_clause    = array(
            'ID'          => (int) $notification_id,
            'receiver_id' => $current_user_id,
            'status'      => 'UNREAD'
        );
        
        $status_updated = $wpdb->update( $tablename, $updated_status, $where_clause );

        return $status_updated;
    }

    /**
     * Delete all notifications
     * 
     * @return bool
     */
    public function delete_all_notifications_by_user() {
        global $wpdb;

        $current_user_id = absint( get_current_user_id() );
        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $where_clause    = array(
            'receiver_id' => $current_user_id,
        );
        
        $status_updated = $wpdb->delete( $tablename, $where_clause );
    }
}