<?php
/**
 * Tutor Pro assets loader
 *
 * @package TutorPro\Assets
 */

namespace TUTOR_PRO;

use TUTOR\Input;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue styles & scripts
 */
class Assets {

	/**
	 * Register hooks
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::frontend_scripts' );
		add_action( 'login_enqueue_scripts', __CLASS__ . '::frontend_scripts' );
	}

	/**
	 * Enqueue styles & scripts for the admin side
	 *
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'tutor-pro-admin', tutor_pro()->url . 'assets/css/admin.css', array(), TUTOR_PRO_VERSION );
		wp_enqueue_script( 'tutor-pro-admin', tutor_pro()->url . 'assets/js/admin.js', array( 'jquery' ), TUTOR_PRO_VERSION, true );

		// Enqueue TinyMCE codesample assets.
		self::enqueue_tinymce_codesample_asset();
	}

	/**
	 * Enqueue style & scripts on the frontend
	 *
	 * @return void
	 */
	public static function frontend_scripts() {
		self::enqueue_tinymce_codesample_asset();

		wp_enqueue_script( 'tutor-pro-front', tutor_pro()->url . 'assets/js/front.js', array( 'wp-i18n' ), TUTOR_PRO_VERSION, true );

		if ( 'wp-login.php' === $GLOBALS['pagenow'] ) {
			$current_page = tutor_utils()->get_current_page_slug();

			wp_localize_script(
				'tutor-pro-front',
				'_tutorobject',
				array(
					'ajaxurl'      => admin_url( 'admin-ajax.php' ),
					'nonce_key'    => tutor()->nonce,
					tutor()->nonce => wp_create_nonce( tutor()->nonce_action ),
					'current_page' => $current_page,
				)
			);
		}

		/**
		 * Translation in js
		 *
		 * @since 2.0.9
		 *
		 * To prevent error WP_Scripts::localize was called incorrectly
		 * need to pass third arguments as an array. Even adding an array directly
		 * from another causing error.
		 *
		 * @since v2.1.0
		 */
		wp_localize_script( 'tutor-pro-front', '_tutor_pro_trans', self::translate_able_text() );
	}

	/**
	 * Load codesample plugin css & js to support
	 * code snippet on the lesson & quiz
	 *
	 * @since v2.0.8
	 */
	public static function enqueue_tinymce_codesample_asset() {
		global $wp_query;
		$query_vars        = $wp_query->query_vars;
		$current_post_type = get_post_type();
		$current_page      = $query_vars['tutor_dashboard_page'] ?? '';
		if ( tutor()->course_post_type === $current_post_type || 'create-course' === $current_page ) {
			if ( ! wp_script_is( 'wp-tinymce-root' ) ) {
				wp_enqueue_script( 'tutor-tiny', includes_url( 'js/tinymce' ) . '/tinymce.min.js', array( 'jquery' ), TUTOR_VERSION, true );
			}
			wp_enqueue_script( 'tutor-tinymce-codesample', tutor_pro()->url . 'assets/lib/codesample/prism.min.js', array( 'jquery' ), TUTOR_VERSION, true );
			wp_enqueue_script( 'tutor-tinymce-code', tutor_pro()->url . 'assets/lib/tinymce/code.plugin.min.js', array( 'jquery' ), TUTOR_VERSION, true );
		}
		wp_enqueue_style( 'tutor-codesample', tutor_pro()->url . 'assets/lib/codesample/prism.css', array(), TUTOR_VERSION );
	}

	/**
	 * Get translate able text
	 *
	 * @since v2.1.0
	 *
	 * @return array  translate able to use in JS
	 */
	protected static function translate_able_text() {
		return array(
			'no_data_found_in_this_section' => __( 'No data found in this section', 'tutor-pro' ),
			'search_'                       => __( 'Search...', 'tutor-pro' ),
			'calendar'                      => __( 'Calendar', 'tutor-pro' ),
		);
	}
}
