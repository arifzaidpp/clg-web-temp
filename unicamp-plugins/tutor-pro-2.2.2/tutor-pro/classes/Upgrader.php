<?php
/**
 * Manage database changes.
 *
 * @package TutorPro
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 2.2.0
 */

namespace TUTOR_PRO;

use TUTOR\User;

/**
 * Class Upgrader
 *
 * @since 2.2.0
 */
class Upgrader {
	/**
	 * Register hooks
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'modify_table' ) );
	}

	/**
	 * Modify table
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function modify_table() {
		if ( ! User::is_admin() ) {
			return;
		}

		$version = get_option( 'tutor_version' );

		/**
		 * New `answer_explanation` field added to `tutor_quiz_questions` table.
		 *
		 * @since 2.2.0
		 */
		if ( version_compare( $version, '2.2.0', '<' ) ) {
			global $wpdb;

			$table_name  = $wpdb->prefix . 'tutor_quiz_questions';
			$column_name = 'answer_explanation';

			// Check if the column already exists.
			$column_exists = $wpdb->query( $wpdb->prepare( "SHOW COLUMNS FROM $table_name LIKE %s", $column_name ) ); //phpcs:ignore

			// If the column doesn't exist, add it to the table.
			if ( 0 === $column_exists ) {
				//phpcs:ignore
				$modified = $wpdb->query( "ALTER TABLE $table_name ADD $column_name LONGTEXT DEFAULT '' AFTER question_description" );
				if ( $modified ) {
					update_option( 'tutor_version', TUTOR_VERSION );
				}
			}
		}
	}
}
