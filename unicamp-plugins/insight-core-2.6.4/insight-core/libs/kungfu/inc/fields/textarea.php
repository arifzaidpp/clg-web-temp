<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Textarea_Field' ) ) {
	class KFF_Textarea_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'title'      => '',
				'default'    => '',
				'full_width' => true
			) );

			$classes = array();

			if ( $field['full_width'] == true ) {
				$classes[] = 'kungfu-form-full';
			}

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$value = isset( $post_metas[ $field['id'] ] ) ? $post_metas[ $field['id'] ] : $field['default'];

			return sprintf( '<div class="kungfu-form-wrapper %s">
          <div class="kungfu-form-title">
            <label class="kungfu-form-label" for="%s">%s</label>%s
          </div>
          <div class="kungfu-form-control">
            <textarea name="%s" id="%s" class="form-textarea" rows="5">%s</textarea>
            %s
          </div>
        </div>', implode( ' ', $classes ), $field['id'], $field['title'], $field['subtitle'], $field['id'], $field['id'], $value, $field['desc'] );
		}
	}
}