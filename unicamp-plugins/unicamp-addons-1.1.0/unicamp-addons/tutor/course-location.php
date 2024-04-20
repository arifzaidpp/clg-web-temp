<?php

namespace Unicamp_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Course_Location {

	protected static $instance = null;

	const TAXONOMY_LOCATION = 'course-location';

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
		add_action( 'init', [ $this, 'register_tax_location' ], 1 );

		add_action( 'admin_menu', [ $this, 'register_menu' ] );

		// Force activate menu for necessary.
		add_filter( 'parent_file', [ $this, 'parent_menu_active' ], 20, 1 );
		add_filter( 'submenu_file', [ $this, 'submenu_file_active' ], 10, 2 );

		/**
		 * Course frontend
		 */
		add_action( 'tutor/frontend_course_edit/after/description', [ $this, 'course_frontend_builder' ] );

		add_action( 'tutor_save_course_after', [ $this, 'save_course_location' ], 10, 2 );
	}

	public function register_tax_location() {
		$course_post_type = tutor()->course_post_type;

		$labels = array(
			'name'                       => _x( 'Course Location', 'taxonomy general name', 'unicamp-addons' ),
			'singular_name'              => _x( 'Location', 'taxonomy singular name', 'unicamp-addons' ),
			'search_items'               => esc_html__( 'Search Locations', 'unicamp-addons' ),
			'popular_items'              => esc_html__( 'Popular Locations', 'unicamp-addons' ),
			'all_items'                  => esc_html__( 'All Locations', 'unicamp-addons' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Location', 'unicamp-addons' ),
			'update_item'                => esc_html__( 'Update Location', 'unicamp-addons' ),
			'add_new_item'               => esc_html__( 'Add New Location', 'unicamp-addons' ),
			'new_item_name'              => esc_html__( 'New Location Name', 'unicamp-addons' ),
			'separate_items_with_commas' => esc_html__( 'Separate locations with commas', 'unicamp-addons' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove locations', 'unicamp-addons' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used locations', 'unicamp-addons' ),
			'not_found'                  => esc_html__( 'No locations found.', 'unicamp-addons' ),
			'menu_name'                  => esc_html__( 'Course Locations', 'unicamp-addons' ),
			'back_to_items'              => esc_html__( 'Back to Locations', 'unicamp-addons' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => apply_filters( 'unicamp_course_location_slug', 'course-location' ) ),
		);

		register_taxonomy( self::TAXONOMY_LOCATION, $course_post_type, $args );
	}

	public function register_menu() {
		$course_post_type = tutor()->course_post_type;

		add_submenu_page( Entry::instance()->get_menu_slug(), esc_html__( 'Locations', 'unicamp-addons' ), esc_html__( 'Locations', 'unicamp-addons' ), 'manage_tutor', 'edit-tags.php?taxonomy=course-location&post_type=' . $course_post_type, null, 2 );
	}

	public function parent_menu_active( $parent_file ) {
		$taxonomy = tutor_utils()->avalue_dot( 'taxonomy', $_GET );
		if ( $taxonomy === 'course-location' ) {
			return Entry::instance()->get_menu_slug();
		}

		return $parent_file;
	}

	public function submenu_file_active( $submenu_file, $parent_file ) {
		$taxonomy         = tutor_utils()->avalue_dot( 'taxonomy', $_GET );
		$course_post_type = tutor()->course_post_type;

		if ( 'course-location' === $taxonomy ) {
			return 'edit-tags.php?taxonomy=course-location&post_type=' . $course_post_type;
		}

		return $submenu_file;
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( $screen->id === 'edit-course-language' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'unicamp-addons-media', UNICAMP_ADDONS_ASSETS_URI . '/admin/js/media-upload.js', [ 'jquery' ], null, true );
		}
	}

	public function course_frontend_builder( $post ) {
		$args = [
			'taxonomy'         => self::TAXONOMY_LOCATION,
			'hide_empty'       => 0,
			'orderby'          => 'name',
			'hierarchical'     => 0,
			'show_option_none' => '&mdash;',
			'name'             => 'course-location',
		];

		$terms = get_the_terms( get_the_ID(), self::TAXONOMY_LOCATION );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$args ['selected'] = $terms[0]->term_id;
		}
		?>
		<div class="tutor-frontend-builder-item-scope">
			<div class="tutor-form-group">
				<label>
					<?php esc_html_e( 'Select location', 'unicamp-addons' ); ?>
				</label>
				<div class="tutor-form-field-course-location">
					<?php wp_dropdown_categories( $args ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function save_course_location( $post_ID, $post ) {
		$location = isset( $_POST['course-location'] ) && '-1' !== $_POST['course-location'] ? $_POST['course-location'] : false;

		if ( ! empty( $location ) ) {
			$integerIDs = array_map( 'intval', [ $location ] );
			$integerIDs = array_unique( $integerIDs );
			wp_set_post_terms( $post_ID, $integerIDs, 'course-location' );
		}
	}
}

Course_Location::instance()->initialize();
