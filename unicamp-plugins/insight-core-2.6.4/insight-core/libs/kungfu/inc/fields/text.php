<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Text_Field' ) ) {
	class KFF_Text_Field {
		static function template( $field, $post_metas ) {
			$field = wp_parse_args( $field, array(
				'title'      => '',
				'subtitle'   => '',
				'desc'       => '',
				'default'    => '',
				'wrap_class' => '',
			) );

			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $field['default'];

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
					<input type="text" name="<?php echo esc_attr( $field['id'] ); ?>" class="kungfu-form-text kungfu-form-control" id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
					<?php if ( ! empty( $field['desc'] ) ) : ?>
						<p class="kungfu-form-description">
							<?php echo wp_kses_post( $field['desc'] ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}
}
