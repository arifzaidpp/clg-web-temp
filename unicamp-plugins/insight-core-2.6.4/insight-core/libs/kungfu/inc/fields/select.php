<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Select_Field' ) ) {
	class KFF_Select_Field {
		static function template( $field, $post_metas ) {
			$field = wp_parse_args( $field, array(
				'options'    => array(),
				'change'     => array(),
				'title'      => '',
				'subtitle'   => '',
				'desc'       => '',
				'default'    => '',
				'wrap_class' => '',
			) );

			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $field['default'];

			$dataChange = '';
			if ( ! empty( $field['change'] ) ) {
				$dataChange = "data-change='" . json_encode( $field['change'], JSON_UNESCAPED_UNICODE ) . "'";
			}

			$wrap_class = "kungfu-form-wrapper kungfu-field-{$field['id']} {$field['wrap_class']}";
			?>
			<div class="<?php echo esc_attr( $wrap_class ); ?>">
				<div class="kungfu-form-title">
					<label class="kungfu-form-label" for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ) ?></label>
					<?php if ( ! empty( $field['subtitle'] ) ) : ?>
						<p class="kungfu-form-sub-title">
							<?php echo esc_html( $field['subtitle'] ); ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="kungfu-form-control">
					<select name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="kungfu-form-select" <?php echo $dataChange; ?>>
						<?php foreach ( $field['options'] as $val => $label ) : ?>
							<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $value, $val, true ) ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php if ( ! empty( $field['desc'] ) ) : ?>
						<p class="kungfu-form-description">
							<?php echo wp_kses_post( $field['desc'] ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		static function enqueue_scripts() {
			wp_enqueue_script( 'kungfu-select', KFF_JS_URL . 'select.js', array(
				'jquery-core'
			), false, true );
		}
	}
}
