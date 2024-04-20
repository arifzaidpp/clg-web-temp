<?php

class CMB2_Type_Popup_Display {

	public function __construct() {
		add_action( 'cmb2_render_popup_display', array( $this, 'insight_cmb2_render_popup_display' ), 10, 5 );
		add_filter( 'cmb2_sanitize_popup_display', array( $this, 'insight_cmb2_sanitize_popup_display' ), 12, 4 );
	}

	/**
	 * Render show options field
	 *
	 * @param $field
	 * @param $value
	 * @param $object_id
	 * @param $object_type
	 * @param $field_type_object
	 */
	public function insight_cmb2_render_popup_display( $field, $value, $object_id, $object_type, $field_type_object ) {

		$value = wp_parse_args( $value, array(
			'type'        => 'delay',
			'delay'       => '3',
			'scroll'      => '25',
			'scroll_type' => '%',
			'anchor'      => '',
		) );

		?>
		<div class="input-row">
			<label>
				<input type="radio" name="<?php echo esc_attr__( $field_type_object->_name( '[type]' ) ); ?>"
				       id="<?php echo esc_attr( $field_type_object->_id( '_delay' ) ) ?>"
				       value="delay" <?php checked( 'delay', $value['type'] ) ?>
				       data-toggle=".opt-display-delay">
				<?php echo esc_html__( 'Appear after', 'insight-core' ); ?>
			</label>
			<span class="opt-display-delay">
				<input type="number" min="0" max="999" maxlength="3"
				       name="<?php echo esc_attr__( $field_type_object->_name( '[delay]' ) ); ?>"
				       class="cmb_text_small"
				       value="<?php echo esc_attr__( $value['delay'] ); ?>" placeholder="3">
				<?php echo esc_html__( 'Seconds', 'insight-core' ) ?>
			</span>
		</div>
		<div class="input-row">
			<label>
				<input type="radio" name="<?php echo esc_attr__( $field_type_object->_name( '[type]' ) ); ?>"
				       id="<?php echo esc_attr( $field_type_object->_id( '_scroll' ) ) ?>"
				       value="scroll" <?php checked( 'scroll', $value['type'] ) ?>
				       data-toggle=".opt-display-scroll">
				<?php echo esc_html__( 'Appear after', 'insight-core' ); ?>
			</label>
			<span class="opt-display-scroll">
			<input type="number" min="0" max="9999" maxlength="4"
			       name="<?php echo esc_attr__( $field_type_object->_name( '[scroll]' ) ); ?>"
			       class="cmb_text_small"
			       value="<?php echo esc_attr__( $value['scroll'] ); ?>"
			       placeholder="25">
			<select name="<?php echo esc_attr__( $field_type_object->_name( '[scroll_type]' ) ); ?>">
				<option value="%" <?php selected( '%', $value['scroll_type'] ) ?>>%</option>
				<option value="px" <?php selected( 'px', $value['scroll_type'] ) ?>>px</option>
			</select>
		</span>
			<?php echo esc_html__( 'of the page has been scrolled.', 'insight-core' ); ?>
		</div>
		<div class="input-row">
			<label>
				<input type="radio" name="<?php echo esc_attr__( $field_type_object->_name( '[type]' ) ); ?>"
				       id="<?php echo esc_attr( $field_type_object->_id( '_anchor' ) ) ?>"
				       value="anchor" <?php checked( 'anchor', $value['type'] ) ?>
				       data-toggle=".opt-display-anchor"
				       style="">
				<?php echo esc_html__( 'Appear after user scrolled until CSS selector', 'insight-core' ); ?>
			</label>
			<span class="opt-display-anchor">
			<input type="text" maxlength="50"
			       name="<?php echo esc_attr__( $field_type_object->_name( '[anchor]' ) ); ?>"
			       value="<?php echo esc_attr__( $value['anchor'] ); ?>"
			       class="cmb_text"
			       placeholder="<?php echo esc_attr__( '.class or #id' ); ?>">
		</span>
		</div>
		<?php if ( $field_type_object->_desc() ) { ?>
			<p class="clear">
				<?php echo $field_type_object->_desc(); ?>
			</p>
		<?php }
	}

	public function insight_cmb2_sanitize_popup_display( $override_value, $value, $object_id, $field_args ) {

		if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
			// Don't do the override
			return $override_value;
		}

		$display_keys = array(
			'delay'       => '',
			'scroll'      => '',
			'scroll_type' => '',
			'anchor'      => '',
		);
		foreach ( $display_keys as $key ) {
			if ( ! empty( $value[ $key ] ) ) {
				update_post_meta( $object_id, $field_args['id'] . 'display_' . $key, $value[ $key ] );
			}
		}

		return true;
	}
}

new CMB2_Type_Popup_Display();