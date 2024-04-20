<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Switch_Field' ) ) {
	class KFF_Switch_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'options' => array(),
				'default' => ''
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $field['default'];

			$classes = array( 'kungfu-switch-field' );

			$list    = '';
			$options = '';

			foreach ( $field['options'] as $val => $text ) {
				$val = (string)$val;

				$list .= sprintf( '
          <option value="%s" %s>%s</option>',
					$val,
					selected( $value, $val, false ),
					$text
				);

				$options .= sprintf(
					'<span class="option %s" data-value="%s" >%s</span>',
					$val === $value ? 'active' : '',
					$val,
					$text
				);
			}

			return sprintf(
				'<div class="kungfu-form-wrapper">
					<div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
					<div class="kungfu-form-control">
						<select name="%s" id="%s" class="%s">%s</select>
						<div class="kungfu-switch">
							%s
						</div>
						%s
					</div>
				</div>',
				$field['title'],
				$field['subtitle'],
				$field['id'],
				$field['id'],
				implode( ' ', $classes ),
				$list,
				$options,
				$field['desc']
			);
		}

		static function enqueue_scripts() {
			wp_enqueue_style( 'kungfu-switch', KFF_CSS_URL . 'switch.css' );

			/*wp_enqueue_script('kungfu-switch-plugin', KFF_JS_URL . 'rcswitcher.min.js', array(
          'jquery-core'
        ), false, true);*/

			wp_enqueue_script( 'kungfu-switch', KFF_JS_URL . 'switch.js', array(
				'jquery-core'
			), false, true );
		}
	}
}
