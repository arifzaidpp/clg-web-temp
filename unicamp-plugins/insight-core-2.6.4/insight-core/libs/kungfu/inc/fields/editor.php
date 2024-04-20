<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Editor_Field' ) ) {
	class KFF_Editor_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'options'    => array(
					'textarea_rows' => 8
				),
				'full_width' => true
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$classes = array();

			if ( $field['full_width'] == true ) {
				$classes[] = 'kungfu-form-full';
			}

			$value = isset( $post_metas[ $field['id'] ] ) ? $post_metas[ $field['id'] ] : '';

			// Using output buffering because wp_editor() echos directly
			ob_start();

			// Use new wp_editor() since WP 3.3
			wp_editor( $value, $field['id'], $field['options'] );

			$editor = ob_get_clean();

			printf( '
				<div class="kungfu-form-wrapper %s">
					<div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
          <div class="kungfu-form-control">%s</div>
          %s
				</div>',
				implode( ' ', $classes ),
				$field['title'],
				$field['subtitle'],
				$editor,
				$field['desc']
			);
		}
	}
}