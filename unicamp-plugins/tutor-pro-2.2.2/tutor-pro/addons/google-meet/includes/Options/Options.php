<?php
/**
 * Manage google meet options
 *
 * @since v2.1.0
 *
 * @package TutorPro\GoogleMeet\Options
 */

namespace TutorPro\GoogleMeet\Options;

use TutorPro\GoogleMeet\Validator\Validator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create/update options
 */
class Options {

	const REWRITE_PERMALINKS = 'tutor-pro-gm-rewrite-rules';

	/**
	 * An identifier to check weather need to update
	 * rewrite rules or not
	 *
	 * @param integer $value 0 when need update, 1 when updated.
	 *
	 * @return void
	 */
	public static function require_permalink_update( int $value ) {
		if ( Validator::current_user_has_access() ) {
			update_option( self::REWRITE_PERMALINKS, $value );
		}
	}
}
