<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Post_Category_Color' ) ) {
	class Unicamp_Post_Category_Color {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'category_add_form_fields', [
				$this,
				'add_new_category_color_field',
			] );

			add_action( 'category_edit_form_fields', [
				$this,
				'edit_category_color_field',
			] );

			add_action( 'created_category', [ $this, 'save_term_color' ] );
			add_action( 'edited_category', [ $this, 'save_term_color' ] );

			add_action( 'admin_print_scripts', [ $this, 'colorpicker_init_inline' ], 20 );
		}

		/**
		 * Add new color picker field to "Add new Category" screen
		 *
		 * @see https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
		 *
		 * @param String $taxonomy
		 *
		 * @return void
		 */
		function add_new_category_color_field( $taxonomy ) {
			?>
			<div class="form-field term-colorpicker-wrap">
				<label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'unicamp' ); ?></label>
				<input name="_category_color" value="" class="colorpicker" id="term-colorpicker"/>
			</div>
			<?php
		}

		/**
		 * Add new color picker field to "Edit Category" screen
		 *
		 * @see https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
		 *
		 * @param WP_Term $term
		 *
		 * @return void
		 */
		function edit_category_color_field( $term ) {
			$color = get_term_meta( $term->term_id, '_category_color', true );
			$color = ( ! empty( $color ) ) ? "#{$color}" : '';
			?>
			<tr class="form-field term-colorpicker-wrap">
				<th scope="row">
					<label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'unicamp' ); ?></label>
				</th>
				<td>
					<input name="_category_color" value="<?php echo esc_attr( $color ); ?>" class="colorpicker"
					       id="term-colorpicker"/>
				</td>
			</tr>
			<?php
		}

		/**
		 * Term Metadata - Save Created and Edited Term Metadata
		 *
		 * @param Integer $term_id
		 *
		 * @return void
		 */
		function save_term_color( $term_id ) {
			// Save term color if possible.
			if ( isset( $_POST['_category_color'] ) && ! empty( $_POST['_category_color'] ) ) {
				update_term_meta( $term_id, '_category_color', sanitize_hex_color_no_hash( $_POST['_category_color'] ) );
			} else {
				delete_term_meta( $term_id, '_category_color' );
			}
		}

		/**
		 * Print javascript to initialize the colorpicker
		 *
		 * @return void
		 */
		function colorpicker_init_inline() {
			if ( null !== ( $screen = get_current_screen() ) && 'edit-category' !== $screen->id ) {
				return;
			}
			?>
			<script>
				jQuery( document ).ready( function( $ ) {
					$( '.colorpicker' ).wpColorPicker();
				} );
			</script>
			<?php
		}
	}

	Unicamp_Post_Category_Color::instance()->initialize();
}
