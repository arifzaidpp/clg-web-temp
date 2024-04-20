<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Color_Field' ) ) {
	class KFF_Color_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'default' => '',
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $field['default'];

			return sprintf( '
        <div class="kungfu-form-wrapper">
          <div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
          <div class="kungfu-form-control">
            <input name="%s" id="%s" class="kungfu-form-color" value="%s" />
            %s
          </div>
        </div>', $field['title'], $field['subtitle'], $field['id'], $field['id'], $value, $field['desc'] );
		}

		static function enqueue_scripts() {
			wp_enqueue_style( 'spectrum', KFF_ASSETS_URL . 'spectrum/spectrum.css', false, '1.0.0' );

			wp_enqueue_script( 'spectrum', KFF_ASSETS_URL . 'spectrum/spectrum.js', array(
				'jquery-core'
			), false, true );

			wp_enqueue_script( 'kungfu-color', KFF_JS_URL . 'color.js', array(
				'jquery-core'
			), false, true );
		}
	}
}