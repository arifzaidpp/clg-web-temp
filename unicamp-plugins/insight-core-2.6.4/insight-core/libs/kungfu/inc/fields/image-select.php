<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Image_Select_Field' ) ) {
	class KFF_Image_Select_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'title'   => '',
				'options' => array(),
				'default' => ''
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $field['default'];

			$list = '';

			foreach ( $field['options'] as $val => $label ) {
				$list .= sprintf( '
          <img data-value="%s" src="%s" class="%s" />',
					$val,
					$label,
					$value == $val ? 'active' : ''
				);
			}

			return sprintf( '<div class="kungfu-form-wrapper">
          <div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
          <div class="kungfu-form-control">
            <div class="kungfu-form-image-select">
              <input type="hidden" name="%s" id="%s" class="image-select-input">
              %s
              %s
            </div>
          </div>
				</div>', $field['title'], $field['subtitle'], $field['id'], $field['id'], $list, $field['desc'] );
		}

		static function enqueue_scripts() {
			wp_enqueue_script( 'kungfu-image-select', KFF_JS_URL . 'image-select.js', array(
				'jquery-core'
			), false, true );
		}
	}
}