<?php

namespace Unicamp_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Course_Visibility {

	protected static $instance = null;

	const TAXONOMY_NAME = 'course-visibility';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Priority 1 to make save_post action working properly.
		 */
		add_action( 'init', [ $this, 'register_taxonomy' ], 1 );

		/**
		 * Add course featured option to course general tab settings
		 */
		add_action( 'tutor_course_settings_tabs', [ $this, 'add_course_featured_to_settings' ] );

		/**
		 * Save course featured.
		 */
		add_action( 'tutor_save_course_after', [ $this, 'save_meta_featured' ], 10, 2 );
	}

	public function register_taxonomy() {
		$course_post_type = tutor()->course_post_type;

		$args = array(
			'hierarchical'      => false,
			'show_ui'           => false,
			'show_in_nav_menus' => false,
			'query_var'         => is_admin(),
			'rewrite'           => false,
			'public'            => false,
		);

		register_taxonomy( self::TAXONOMY_NAME, $course_post_type, $args );
	}

	public function add_course_featured_to_settings( $settings ) {
		/**
		 * Only administrator role can set course as featured.
		 */
		if ( current_user_can( 'administrator' ) ) {
			$course_id = get_the_ID();

			$option_is_set = false;

			if ( has_term( 'featured', self::TAXONOMY_NAME, $course_id ) ) {
				$option_is_set = true;
			}

			$settings['general']['fields']['course_featured'] = array(
				'type'    => 'toggle_switch',
				'label'   => __( 'Featured Course ?', 'unicamp-addons' ),
				'options' => array(
					array(
						'checked' => $option_is_set,
						'value'   => '1',
						'hint'    => __( 'Make This Course as Featured Course.', 'unicamp-addons' ),
					),
				),
			);
		}

		return $settings;
	}

	public function save_meta_featured( $post_ID, $post ) {
		/**
		 * Only administrator role can set course as featured.
		 */
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		$is_featured = isset( $_POST['course_featured'] ) && 'on' === $_POST['course_featured'] ? true : false;

		if ( $is_featured ) {
			wp_set_post_terms( $post_ID, array( 'featured' ), self::TAXONOMY_NAME, true );
		} else {
			$tags           = wp_get_post_terms( $post_ID, self::TAXONOMY_NAME );
			$tags_to_delete = array( 'featured' );
			$tags_to_keep   = array();
			foreach ( $tags as $t ) {
				if ( ! in_array( $t->name, $tags_to_delete ) ) {
					$tags_to_keep[] = $t->name;
				}
			}

			wp_set_post_terms( $post_ID, $tags_to_keep, self::TAXONOMY_NAME, false );
		}
	}
}

Course_Visibility::instance()->initialize();
