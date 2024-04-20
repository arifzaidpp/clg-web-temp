<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Init filter hooks
 *
 * @package TutorPro\Filter
 *
 * @since v2.0.9
 */

namespace TUTOR_PRO;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contains filter hooks
 */
class Filters {

	/**
	 * Register hooks
	 */
	public function __construct() {
		add_filter( 'tutor_qna_text_editor', __CLASS__ . '::filter_text_editor' );
		// Filter MCE buttons.
		add_filter( 'mce_external_plugins', __CLASS__ . '::filter_external_plugins' );
	}

	/**
	 * For pro user show wp_editor
	 *
	 * @param string $editor  editor to filter.
	 *
	 * @return string  wp_editor
	 */
	public static function filter_text_editor( string $editor ) {
		ob_start();
		wp_editor(
			'',
			'tutor_qna_text_editor',
			tutor_utils()->text_editor_config(
				array(
					'plugins' => 'codesample',
					'tinymce' => array(
						'toolbar1' => 'bold,italic,underline,link,unlink,removeformat,image,bullist,codesample',
						'toolbar2' => '',
						'toolbar3' => '',
					),
				)
			)
		);
		return ob_get_clean();
	}

	/**
	 * Load codesample external TinyMCE plugin
	 *
	 * It will load on the single_course page only
	 *
	 * @since v2.0.10
	 *
	 * @param array $plugins  available plugins.
	 *
	 * @return return  associative array (key => plugin url)
	 */
	public static function filter_external_plugins( array $plugins ) {
		if ( is_single_course() ) {
			$plugins['codesample'] = 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.1.2/plugins/codesample/plugin.min.js';
		}
		return $plugins;
	}
}
