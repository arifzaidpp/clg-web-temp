<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Ace_Editor_Field' ) ) {
	class KFF_Ace_Editor_Field {
		static function template( $field, $post_metas ) {

			$field = wp_parse_args( $field, array(
				'title'      => '',
				'default'    => '',
				'full_width' => true,
				'mode'       => 'css',
				'theme'      => 'monokai',
				'minLines'   => 10,
				'maxLines'   => 30,
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$classes = array();

			if ( $field['full_width'] == true ) {
				$classes[] = 'kungfu-form-full';
			}

			$value = isset( $post_metas[ $field['id'] ] ) ? $post_metas[ $field['id'] ] : $field['default'];
			//$value = esc_textarea($value);
			//$value = json_decode($value);
			return sprintf(
				'<div class="kungfu-form-wrapper %s">
					<div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
					<div class="kungfu-form-control">
						<div class="kungfu-ace-editor">
							<pre id="%s_editor" class="ace-editor" data-mode="%s" data-theme="%s">%s</pre>
							%s
							<textarea class="form-textarea" name="%s">%s</textarea>
						</div>
					</div>
				</div>',
				implode( ' ', $classes ),
				$field['title'],
				$field['subtitle'],
				$field['id'],
				$field['mode'],
				$field['theme'],
				$value,
				$field['desc'],
				$field['id'],
				$value
			);
		}

		static function enqueue_scripts() {
			wp_enqueue_script( 'kungfu-ace-editor-plugin', KFF_ASSETS_URL . 'ace-editor/src-min-noconflict/ace.js', array(
				'jquery-core'
			), false, true );

			wp_enqueue_script( 'kungfu-ace-editor-js', KFF_JS_URL . 'ace-editor.js', array(
				'jquery-core'
			), false, true );
		}
	}
}