<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Number_Field' ) ) {
	class KFF_Number_Field {
		static function template( $field, $post_metas ) {
			$field = wp_parse_args( $field, array(
				'title'   => '',
				'default' => '',
				'step'    => 1,
			) );

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$value = isset( $post_metas[ $field['id'] ] ) ? esc_attr( $post_metas[ $field['id'] ] ) : $field['default'];

			ob_start();
			?>
			<div class="kungfu-form-wrapper">
				<div class="kungfu-form-title">
					<label class="kungfu-form-label"
					       for="<?php echo $field['id']; ?>"><?php echo $field['title']; ?></label>
					<?php echo $field['subtitle']; ?>
				</div>
				<div class="kungfu-form-control">
					<input type="number"
					       class="kungfu-form-text kungfu-form-control"
					       id="<?php echo $field['id']; ?>"
					       name="<?php echo $field['id']; ?>"
					       value="<?php echo $value; ?>"
						<?php if ( isset( $field['min'] ) ): ?>
							min="<?php echo esc_attr( intval( $field['min'] ) ) ?>"
						<?php endif; ?>
						<?php if ( isset( $field['max'] ) ): ?>
							max="<?php echo esc_attr( intval( $field['max'] ) ) ?>"
						<?php endif; ?>
						<?php if ( isset( $field['step'] ) ): ?>
							step="<?php echo esc_attr( intval( $field['step'] ) ) ?>"
						<?php endif; ?>
					/>
					<?php echo $field['desc']; ?>
				</div>
			</div>
			<?php
			$output = ob_get_clean();

			return $output;
		}
	}
}
