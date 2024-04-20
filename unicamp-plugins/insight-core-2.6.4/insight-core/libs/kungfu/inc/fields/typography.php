<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Typography_Field' ) ) {
	class KFF_Typography_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'default' => array()
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$fontSize = '';
			if ( ! empty( $field['default'] ) ) {
				if ( isset( $field['default']['font-size'] ) ) {
					$fontSize = $field['default']['font-size'];
				}
			}

			$fontWeight = '';


			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $fontSize;

			return sprintf(
				'<div class="kungfu-form-wrapper">
					<div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
					<div class="kungfu-form-control">
						<label>Font Size:</label>
						<input type="text" name="%s" class="kungfu-range-field" data-type="single" data-min="1" data-max="100" data-step="1" value="%s" />
						%s
					</div>
				</div>',
				$field['title'],
				$field['subtitle'],
				$field['id'],
				$value,
				$field['desc']
			);
		}

		static function enqueue_scripts() {
			wp_enqueue_style( 'kungfu-range', KFF_CSS_URL . 'range.css' );

			wp_enqueue_script( 'ion-range', KFF_JS_URL . 'ion.rangeSlider.js', array(
				'jquery-core'
			), false, true );

			wp_enqueue_script( 'kungfu-range', KFF_JS_URL . 'range.js', array(
				'jquery-core'
			), false, true );
		}
	}
}