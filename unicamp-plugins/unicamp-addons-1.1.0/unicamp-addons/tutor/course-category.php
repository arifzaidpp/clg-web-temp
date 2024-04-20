<?php

namespace Unicamp_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Course_Category {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Add category icon field html template.
		 */
		add_action( 'course-category_add_form_fields', [ $this, 'add_term_fields' ] );
		add_action( 'course-category_edit_form_fields', [ $this, 'edit_term_fields' ] );

		/**
		 * Add category type field html template.
		 */
		add_action( 'course-category_add_form_fields', [ $this, 'add_category_type_field' ] );
		add_action( 'course-category_edit_form_fields', [ $this, 'edit_category_type_field' ] );

		/**
		 * Save custom fields.
		 * Priority 20 to run after Tutor
		 */
		add_action( 'created_term', [ $this, 'save_term_fields' ], 20, 3 );
		add_action( 'edit_term', [ $this, 'save_term_fields' ], 20, 3 );

		/**
		 * Add icon to admin table columns
		 */
		add_filter( 'manage_edit-course-category_columns', [ $this, 'term_columns' ] );
		add_filter( 'manage_course-category_custom_column', [ $this, 'term_column' ], 10, 3 );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function add_term_fields() {
		?>
		<div class="form-field term-icon-wrap">
			<label><?php esc_html_e( 'Icon', 'unicamp-addons' ); ?></label>

			<div class="unicamp-addons-media-wrap">
				<div style="float: left; margin-right: 10px;" class="unicamp-addons-media-image">
					<img src="<?php echo esc_url( unicamp_addons_placeholder_img_src() ); ?>" width="60px" height="60px"
					     data-src-placeholder="<?php echo esc_attr( unicamp_addons_placeholder_img_src() ); ?>"
					/></div>
				<div style="line-height: 60px;">
					<input type="hidden" class="unicamp-addons-media-input" name="course_category_icon_id"/>
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

	public function edit_term_fields( $term ) {
		$icon_id = absint( get_term_meta( $term->term_id, 'icon_id', true ) );

		if ( $icon_id ) {
			$icon = wp_get_attachment_thumb_url( $icon_id );
		} else {
			$icon = unicamp_addons_placeholder_img_src();
		}
		?>
		<tr class="form-field term-thumbnail-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon', 'unicamp-addons' ); ?></label></th>
			<td>
				<div class="unicamp-addons-media-wrap">
					<div style="float: left; margin-right: 10px;" class="unicamp-addons-media-image">
						<img src="<?php echo esc_url( $icon ); ?>" width="60px" height="60px"
						     data-src-placeholder="<?php echo esc_attr( unicamp_addons_placeholder_img_src() ); ?>"/>
					</div>
					<div style="line-height: 60px;">
						<input type="hidden"
						       class="unicamp-addons-media-input"
						       name="course_category_icon_id"
						       value="<?php echo esc_attr( $icon_id ); ?>"/>
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

	public function add_category_type_field() {
		$available_types = $this->get_course_category_types();

		if ( empty( $available_types ) ) {
			return;
		}
		?>
		<div class="form-field term-icon-wrap">
			<label><?php esc_html_e( 'Types', 'unicamp-addons' ); ?></label>

			<?php foreach ( $available_types as $value => $label ) : ?>
				<label for="<?php echo 'type-' . $value; ?>">
					<input type="checkbox" name="types[]" value="<?php echo esc_attr( $value ) ?>"
					       id="<?php echo 'type-' . $value; ?>"/>
					<?php echo esc_html( $label ); ?>
				</label>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function edit_category_type_field( $term ) {
		$available_types = $this->get_course_category_types();

		if ( empty( $available_types ) ) {
			return;
		}

		$selected_types = get_term_meta( $term->term_id, 'types', true );
		?>
		<tr class="form-field term-type-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Types', 'unicamp-addons' ); ?></label></th>
			<td>
				<?php foreach ( $available_types as $value => $label ) : ?>
					<?php
					$checked = ! empty( $selected_types ) && in_array( $value, $selected_types, true ) ? true : false;
					?>
					<label for="<?php echo 'type-' . $value; ?>">
						<input type="checkbox" name="types[]" value="<?php echo esc_attr( $value ) ?>"
						       id="<?php echo 'type-' . $value; ?>" <?php checked( $checked ); ?>/>
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		<?php
	}

	public function get_course_category_types( $type = null ) {
		$options = [
			'minor'       => __( 'Minor', 'unicamp-addons' ),
			'major'       => __( 'Major', 'unicamp-addons' ),
			'certificate' => __( 'Certificate', 'unicamp-addons' ),
		];

		$options = apply_filters( 'unicamp_course_category_types', $options );

		if ( $type ) {
			if ( isset( $options[ $type ] ) ) {
				return $options[ $type ];
			} else {
				return '';
			}
		}

		return $options;
	}

	/**
	 * @param        $term_id
	 * @param string $tt_id
	 * @param string $taxonomy
	 *
	 * Save Course Category Icon
	 */
	public function save_term_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( 'course-category' !== $taxonomy ) {
			return;
		}

		// Fix Tutor save 0 value.
		if ( empty( $_POST['course_category_thumbnail_id'] ) ) {
			delete_term_meta( $term_id, 'thumbnail_id' );
		}

		if ( ! empty( $_POST['course_category_icon_id'] ) ) {
			update_term_meta( $term_id, 'icon_id', absint( $_POST['course_category_icon_id'] ) );
		} else {
			delete_term_meta( $term_id, 'icon_id' );
		}

		if ( ! empty( $_POST['types'] ) ) {
			update_term_meta( $term_id, 'types', $_POST['types'] );
		} else {
			delete_term_meta( $term_id, 'types' );
		}
	}

	public function term_columns( $columns ) {
		if ( isset( $columns['slug'] ) ) {
			$columns['slug'] = $columns['slug'] . '<style>.column-slug { width: 160px !important;}</style>';
		}

		if ( isset( $columns['thumb'] ) ) {
			$columns['thumb'] = $columns['thumb'] . '<style>.column-thumb { width: 48px !important;}</style>';
		}

		$columns['icon'] = __( 'Icon', 'unicamp-addons' ) . '<style>.column-icon { width: 48px !important;}</style>';

		$columns['types'] = __( 'Types', 'unicamp-addons' );

		return $columns;
	}

	public function term_column( $columns, $column, $id ) {
		switch ( $column ) {
			case 'icon':

				$icon_id = get_term_meta( $id, 'icon_id', true );

				if ( $icon_id ) {
					$image = wp_get_attachment_thumb_url( $icon_id );
				} else {
					$image = unicamp_addons_placeholder_img_src();
				}

				// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605 .
				$image   = str_replace( ' ', '%20', $image );
				$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Icon', 'unicamp-addons' ) . '" class="wp-post-image" height="48" width="48" />';

				break;
			case 'types':

				$selected_types = get_term_meta( $id, 'types', true );

				if ( ! empty( $selected_types ) ) {
					$available_types = $this->get_course_category_types();
					$visible_types   = [];
					foreach ( $selected_types as $selected_type ) {
						if ( ! empty( $available_types ) && isset( $available_types[ $selected_type ] ) ) {
							$visible_types[] = $available_types[ $selected_type ];
						}
					}

					$columns .= implode( ', ', $visible_types );
				}

				break;
		}

		return $columns;
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( $screen->id === 'edit-course-category' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'unicamp-addons-media', UNICAMP_ADDONS_ASSETS_URI . '/admin/js/media-upload.js', [ 'jquery' ], null, true );
			wp_enqueue_script( 'unicamp-addons-clear-form', UNICAMP_ADDONS_ASSETS_URI . '/admin/js/clear-form.js', [ 'jquery' ], null, true );
		}
	}
}

Course_Category::instance()->initialize();
