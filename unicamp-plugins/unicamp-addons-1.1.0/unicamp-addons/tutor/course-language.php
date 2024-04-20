<?php

namespace Unicamp_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Course_Language {

	protected static $instance = null;

	const TAXONOMY_LANGUAGE = 'course-language';

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
		add_action( 'init', [ $this, 'register_tax_language' ], 1 );

		add_action( 'admin_menu', [ $this, 'register_menu' ] );

		// Force activate menu for necessary.
		add_filter( 'parent_file', [ $this, 'parent_menu_active' ], 20, 1 );
		add_filter( 'submenu_file', [ $this, 'submenu_file_active' ], 10, 2 );

		/**
		 * Add thumbnail field html template.
		 */
		add_action( 'course-language_add_form_fields', [ $this, 'add_language_fields' ] );
		add_action( 'course-language_edit_form_fields', [ $this, 'edit_language_fields' ] );

		/**
		 * Save thumbnail field.
		 */
		add_action( 'created_term', [ $this, 'save_language_fields' ], 10, 3 );
		add_action( 'edit_term', [ $this, 'save_language_fields' ], 10, 3 );

		/**
		 * Add thumbnail to admin table columns
		 */
		add_filter( 'manage_edit-course-language_columns', [ $this, 'course_language_columns' ] );
		add_filter( 'manage_course-language_custom_column', [ $this, 'course_language_column' ], 10, 3 );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_filter( 'tutor/options/extend/attr', [ $this, 'add_visibility_setting' ] );

		/**
		 * Course frontend
		 */
		add_action( 'tutor/frontend_course_edit/after/description', [ $this, 'course_frontend_builder' ] );

		add_action( 'tutor_save_course_after', [ $this, 'save_course_language' ], 10, 2 );
	}

	public function register_tax_language() {
		$course_post_type = tutor()->course_post_type;

		$labels = array(
			'name'                       => _x( 'Course Language', 'taxonomy general name', 'unicamp-addons' ),
			'singular_name'              => _x( 'Language', 'taxonomy singular name', 'unicamp-addons' ),
			'search_items'               => esc_html__( 'Search Languages', 'unicamp-addons' ),
			'popular_items'              => esc_html__( 'Popular Languages', 'unicamp-addons' ),
			'all_items'                  => esc_html__( 'All Languages', 'unicamp-addons' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Language', 'unicamp-addons' ),
			'update_item'                => esc_html__( 'Update Language', 'unicamp-addons' ),
			'add_new_item'               => esc_html__( 'Add New Language', 'unicamp-addons' ),
			'new_item_name'              => esc_html__( 'New Language Name', 'unicamp-addons' ),
			'separate_items_with_commas' => esc_html__( 'Separate languages with commas', 'unicamp-addons' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove languages', 'unicamp-addons' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used languages', 'unicamp-addons' ),
			'not_found'                  => esc_html__( 'No languages found.', 'unicamp-addons' ),
			'menu_name'                  => esc_html__( 'Course Languages', 'unicamp-addons' ),
			'back_to_items'              => esc_html__( 'Back to Languages', 'unicamp-addons' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => apply_filters( 'unicamp_course_language_slug', 'course-language' ) ),
		);

		register_taxonomy( self::TAXONOMY_LANGUAGE, $course_post_type, $args );
	}

	public function register_menu() {
		$course_post_type = tutor()->course_post_type;

		add_submenu_page( Entry::instance()->get_menu_slug(), esc_html__( 'Languages', 'unicamp-addons' ), esc_html__( 'Languages', 'unicamp-addons' ), 'manage_tutor', 'edit-tags.php?taxonomy=course-language&post_type=' . $course_post_type, null, 2 );
	}

	public function parent_menu_active( $parent_file ) {
		$taxonomy = tutor_utils()->avalue_dot( 'taxonomy', $_GET );
		if ( $taxonomy === 'course-language' ) {
			return Entry::instance()->get_menu_slug();
		}

		return $parent_file;
	}

	public function submenu_file_active( $submenu_file, $parent_file ) {
		$taxonomy         = tutor_utils()->avalue_dot( 'taxonomy', $_GET );
		$course_post_type = tutor()->course_post_type;

		if ( 'course-language' === $taxonomy ) {
			return 'edit-tags.php?taxonomy=course-language&post_type=' . $course_post_type;
		}

		return $submenu_file;
	}

	public function add_language_fields() {
		?>
		<div class="form-field term-thumbnail-wrap">
			<label><?php esc_html_e( 'Thumbnail', 'unicamp-addons' ); ?></label>

			<div class="unicamp-addons-media-wrap">
				<div style="float: left; margin-right: 10px;" class="unicamp-addons-media-image">
					<img src="<?php echo esc_url( unicamp_addons_placeholder_img_src() ); ?>" width="60px" height="60px"
					     data-src-placeholder="<?php echo esc_attr( unicamp_addons_placeholder_img_src() ); ?>"
					/></div>
				<div style="line-height: 60px;">
					<input type="hidden" class="unicamp-addons-media-input" name="course_language_thumbnail_id"/>
					<button type="button"
					        class="unicamp-addons-media-upload button"><?php esc_html_e( 'Upload/Add image', 'unicamp-addons' ); ?></button>
					<button type="button"
					        class="unicamp-addons-media-remove button"><?php esc_html_e( 'Remove image', 'unicamp-addons' ); ?></button>
				</div>
				<div class="clear"></div>
			</div>
		</div>

		<div class="form-field term-flag-wrap">
			<label><?php esc_html_e( 'Flag', 'unicamp-addons' ); ?></label>

			<div class="unicamp-addons-media-wrap">
				<div style="float: left; margin-right: 10px;" class="unicamp-addons-media-image">
					<img src="<?php echo esc_url( unicamp_addons_placeholder_img_src() ); ?>" width="60px" height="60px"
					     data-src-placeholder="<?php echo esc_attr( unicamp_addons_placeholder_img_src() ); ?>"
					/></div>
				<div style="line-height: 60px;">
					<input type="hidden" class="unicamp-addons-media-input" name="course_language_flag_id"/>
					<button type="button"
					        class="unicamp-addons-media-upload button"><?php esc_html_e( 'Upload/Add image', 'unicamp-addons' ); ?></button>
					<button type="button"
					        class="unicamp-addons-media-remove button"><?php esc_html_e( 'Remove image', 'unicamp-addons' ); ?></button>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	public function edit_language_fields( $term ) {
		$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );

		if ( $thumbnail_id ) {
			$thumbnail = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$thumbnail = unicamp_addons_placeholder_img_src();
		}

		$flag_id = absint( get_term_meta( $term->term_id, 'flag_id', true ) );

		if ( $flag_id ) {
			$flag = wp_get_attachment_thumb_url( $flag_id );
		} else {
			$flag = unicamp_addons_placeholder_img_src();
		}
		?>

		<tr class="form-field term-thumbnail-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'unicamp-addons' ); ?></label></th>
			<td>
				<div class="unicamp-addons-media-wrap">
					<div style="float: left; margin-right: 10px;" class="unicamp-addons-media-image">
						<img src="<?php echo esc_url( $thumbnail ); ?>" width="60px" height="60px"
						     data-src-placeholder="<?php echo esc_attr( unicamp_addons_placeholder_img_src() ); ?>"/>
					</div>
					<div style="line-height: 60px;">
						<input type="hidden"
						       class="unicamp-addons-media-input"
						       name="course_language_thumbnail_id"
						       value="<?php echo esc_attr( $thumbnail_id ); ?>"/>
						<button type="button" class="unicamp-addons-media-upload button">
							<?php esc_html_e( 'Upload/Add image', 'unicamp-addons' ); ?>
						</button>
						<button type="button" class="unicamp-addons-media-remove button">
							<?php esc_html_e( 'Remove image', 'unicamp-addons' ); ?>
						</button>
					</div>
					<div class="clear"></div>
				</div>
			</td>
		</tr>

		<tr class="form-field term-flag-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Flag', 'unicamp-addons' ); ?></label></th>
			<td>
				<div class="unicamp-addons-media-wrap">
					<div style="float: left; margin-right: 10px;" class="unicamp-addons-media-image">
						<img src="<?php echo esc_url( $flag ); ?>" width="60px" height="60px"
						     data-src-placeholder="<?php echo esc_attr( unicamp_addons_placeholder_img_src() ); ?>"/>
					</div>
					<div style="line-height: 60px;">
						<input type="hidden"
						       class="unicamp-addons-media-input"
						       name="course_language_flag_id"
						       value="<?php echo esc_attr( $flag_id ); ?>"/>
						<button type="button" class="unicamp-addons-media-upload button">
							<?php esc_html_e( 'Upload/Add image', 'unicamp-addons' ); ?>
						</button>
						<button type="button" class="unicamp-addons-media-remove button">
							<?php esc_html_e( 'Remove image', 'unicamp-addons' ); ?>
						</button>
					</div>
					<div class="clear"></div>
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * @param        $term_id
	 * @param string $tt_id
	 * @param string $taxonomy
	 *
	 * Save Course Language Thumbnail
	 */
	public function save_language_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( 'course-language' !== $taxonomy ) {
			return;
		}

		if ( ! empty( $_POST['course_language_thumbnail_id'] ) ) {
			update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['course_language_thumbnail_id'] ) );
		} else {
			delete_term_meta( $term_id, 'thumbnail_id' );
		}

		if ( ! empty( $_POST['course_language_flag_id'] ) ) {
			update_term_meta( $term_id, 'flag_id', $_POST['course_language_flag_id'] );
		} else {
			delete_term_meta( $term_id, 'flag_id' );
		}
	}

	public function course_language_columns( $columns ) {
		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}

		$new_columns['thumbnail'] = __( 'Thumbnail', 'unicamp-addons' );

		$columns = array_merge( $new_columns, $columns );

		$columns['flag'] = __( 'Flag', 'unicamp-addons' );

		$columns['handle'] = '';

		return $columns;
	}

	public function course_language_column( $columns, $column, $id ) {
		if ( 'thumbnail' === $column ) {
			$thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = unicamp_addons_placeholder_img_src();
			}

			// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605 .
			$image   = str_replace( ' ', '%20', $image );
			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'unicamp-addons' ) . '" class="wp-post-image" height="48" width="48" />';
		}

		if ( 'flag' === $column ) {
			$flag_id = get_term_meta( $id, 'flag_id', true );

			if ( $flag_id ) {
				$flag = wp_get_attachment_thumb_url( $flag_id );
			} else {
				$flag = unicamp_addons_placeholder_img_src();
			}

			// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605 .
			$flag    = str_replace( ' ', '%20', $flag );
			$columns .= '<img src="' . esc_url( $flag ) . '" alt="' . esc_attr__( 'Flag', 'unicamp-addons' ) . '" class="wp-post-image" height="48" width="48" />';
		}

		if ( 'handle' === $column ) {
			$columns .= '<input type="hidden" name="term_id" value="' . esc_attr( $id ) . '" />';
		}

		return $columns;
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( $screen->id === 'edit-course-language' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'unicamp-addons-media', UNICAMP_ADDONS_ASSETS_URI . '/admin/js/media-upload.js', [ 'jquery' ], null, true );
		}
	}

	public function add_visibility_setting( $setting ) {
		$new_setting_fields = [
			[
				'key'         => 'enable_course_language',
				'type'        => 'toggle_switch',
				'label'       => __( 'Course Language', 'unicamp-addons' ),
				'label_title' => '',
				'default'     => 'on',
				'desc'        => __( 'Enable to show courses language section', 'unicamp-addons' ),
			],
		];

		$design_course_detail_fields = $setting['design']['blocks']['course-details']['fields'];

		foreach ( $design_course_detail_fields as $key => $field ) {
			if ( 'course_details_adjustments' === $field['key'] ) {
				$setting['design']['blocks']['course-details']['fields'][ $key ]['group_options'] = array_merge( $field['group_options'], $new_setting_fields );
				break;
			}
		}

		return $setting;
	}

	public function course_frontend_builder( $post ) {
		$args = [
			'taxonomy'         => self::TAXONOMY_LANGUAGE,
			'hide_empty'       => 0,
			'orderby'          => 'name',
			'hierarchical'     => 0,
			'show_option_none' => '&mdash;',
			'name'             => 'course-language',
		];

		$terms = get_the_terms( get_the_ID(), self::TAXONOMY_LANGUAGE );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$args ['selected'] = $terms[0]->term_id;
		}
		?>
		<div class="tutor-frontend-builder-item-scope">
			<div class="tutor-form-group">
				<label>
					<?php esc_html_e( 'Choose a language', 'unicamp-addons' ); ?>
				</label>
				<div class="tutor-form-field-course-language">
					<?php wp_dropdown_categories( $args ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function save_course_language( $post_ID, $post ) {
		$language = isset( $_POST['course-language'] ) && '-1' !== $_POST['course-language'] ? $_POST['course-language'] : false;

		if ( ! empty( $language ) ) {
			$integerIDs = array_map( 'intval', [ $language ] );
			$integerIDs = array_unique( $integerIDs );
			wp_set_post_terms( $post_ID, $integerIDs, 'course-language' );
		}
	}
}

Course_Language::instance()->initialize();
