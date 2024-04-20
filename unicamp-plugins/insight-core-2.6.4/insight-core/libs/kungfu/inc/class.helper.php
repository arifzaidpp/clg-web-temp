<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Kungfu_Framework_Helper' ) ) {
	class Kungfu_Framework_Helper {

		static $all_fields = array();
		static $form_values = array();

		static function get_fields( $fields ) {
			self::loop_fields( $fields );

			return self::$all_fields;
		}

		static function loop_fields( $fields ) {
			foreach ( $fields as $field ) {
				self::$all_fields[] = $field['type'];
				if ( $field['type'] == 'tabpanel' || $field['type'] == 'accordion' ) {
					foreach ( $field['items'] as $item ) {
						if ( isset( $item['fields'] ) && ! empty( $item['fields'] ) ) {
							self::loop_fields( $item['fields'] );
						}
					}
				}
			}
		}

		static function render_form( $fields, $meta ) {
			foreach ( $fields as $field ) {
				$class = "KFF_" . $field['type'] . '_Field';
				echo $class::template( $field, $meta );
			}
		}

		static function get_form_values( $fields ) {
			self::loop_get_form_values( $fields );

			return self::$form_values;
			self::$form_values == array();
		}

		static function loop_get_form_values( $fields ) {
			foreach ( $fields as $field ) {
				if ( $field['type'] == 'tabpanel' || $field['type'] == 'accordion' ) {
					foreach ( $field['items'] as $item ) {
						if ( isset( $item['fields'] ) && ! empty( $item['fields'] ) ) {
							self::loop_get_form_values( $item['fields'] );
						}
					}
				} else {
					if ( isset( $field['id'] ) && isset( $_POST[ $field['id'] ] ) ) {
						$class = 'KFF_' . $field['type'] . '_Field';
						$value = stripslashes_deep( $_POST[ $field['id'] ] );
						if ( method_exists( $class, 'standardize' ) ) {
							$value = call_user_func( array( $class, 'standardize' ), array( $value ) );
						}
						self::$form_values[ $field['id'] ] = $value;
					}
				}
			}
		}

		static function reset_form_values( $fields ) {
			self::loop_reset_form_values( $fields );

			return self::$form_values;
		}

		static function loop_reset_form_values( $fields ) {
			foreach ( $fields as $field ) {
				if ( $field['type'] == 'tabpanel' || $field['type'] == 'accordion' ) {
					foreach ( $field['items'] as $item ) {
						if ( isset( $item['fields'] ) && ! empty( $item['fields'] ) ) {
							self::loop_reset_form_values( $item['fields'] );
						}
					}
				} else {
					if ( isset( $field['id'] ) && isset( $_POST[ $field['id'] ] ) ) {
						self::$form_values[ $field['id'] ] = isset( $field['default'] ) ? $field['default'] : '';
					}
				}
			}
		}
	}
}