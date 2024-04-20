<?php
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class: Widget Base
 *
 * @version  1.0
 * @extends  WP_Widget
 */
if ( ! class_exists( 'Unicamp_WP_Widget_Base' ) ) {
	abstract class Unicamp_WP_Widget_Base extends WP_Widget {

		/**
		 * CSS class.
		 *
		 * @var string
		 */
		public $widget_cssclass;

		/**
		 * Widget description.
		 *
		 * @var string
		 */
		public $widget_description;

		/**
		 * Widget ID.
		 *
		 * @var string
		 */
		public $widget_id;

		/**
		 * Widget name.
		 *
		 * @var string
		 */
		public $widget_name;

		/**
		 * Settings.
		 *
		 * @var array
		 */
		public $settings;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'                   => $this->widget_cssclass,
				'description'                 => $this->widget_description,
				'customize_selective_refresh' => true,
			);

			parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );
		}

		/**
		 * Output the html at the start of a widget.
		 *
		 * @param  array $args
		 *
		 */
		public function widget_start( $args, $instance ) {
			echo '' . $args['before_widget'];

			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
				echo '' . $args['before_title'] . $title . $args['after_title'];
			}
		}

		/**
		 * Output the html at the end of a widget.
		 *
		 * @param  array $args
		 *
		 */
		public function widget_end( $args ) {
			echo '' . $args['after_widget'];
		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @see    WP_Widget->update
		 *
		 * @param  array $new_instance
		 * @param  array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			if ( empty( $this->settings ) ) {
				return $instance;
			}

			// Loop settings and get values to save.
			foreach ( $this->settings as $key => $setting ) {
				if ( ! isset( $setting['type'] ) ) {
					continue;
				}

				// Format the value based on settings type.
				switch ( $setting['type'] ) {
					case 'number' :
						$instance[ $key ] = absint( $new_instance[ $key ] );

						if ( isset( $setting['min'] ) && '' !== $setting['min'] ) {
							$instance[ $key ] = max( $instance[ $key ], $setting['min'] );
						}

						if ( isset( $setting['max'] ) && '' !== $setting['max'] ) {
							$instance[ $key ] = min( $instance[ $key ], $setting['max'] );
						}
						break;
					case 'textarea' :
						$instance[ $key ] = wp_kses( trim( wp_unslash( $new_instance[ $key ] ) ), wp_kses_allowed_html( 'post' ) );
						break;
					case 'checkbox' :
						$instance[ $key ] = empty( $new_instance[ $key ] ) ? 0 : 1;
						break;
					default:
						$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
						break;
				}

				/**
				 * Sanitize the value of a setting.
				 */
				$instance[ $key ] = apply_filters( 'insight_widget_settings_sanitize_option', $instance[ $key ], $new_instance, $key, $setting );
			}

			return $instance;
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @see   WP_Widget->form
		 *
		 * @param array $instance
		 */
		public function form( $instance ) {
			$this->set_form_settings();

			if ( empty( $this->settings ) ) {
				return;
			}

			foreach ( $this->settings as $key => $setting ) {

				$class = isset( $setting['class'] ) ? $setting['class'] : '';
				$value = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];

				switch ( $setting['type'] ) {

					case 'text' :
						?>
						<p>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<input class="widefat <?php echo esc_attr( $class ); ?>"
							       id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text"
							       value="<?php echo esc_attr( $value ); ?>"/>
							<?php if ( isset( $setting['desc'] ) ) : ?>
								<?php echo "<small>{$setting['desc']}</small>"; ?>
							<?php endif; ?>
						</p>
						<?php
						break;

					case 'number' :
						?>
						<p>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<input class="widefat <?php echo esc_attr( $class ); ?>"
							       id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number"
							       step="<?php echo esc_attr( $setting['step'] ); ?>"
							       min="<?php echo esc_attr( $setting['min'] ); ?>"
							       max="<?php echo esc_attr( $setting['max'] ); ?>"
							       value="<?php echo esc_attr( $value ); ?>"/>
							<?php if ( isset( $setting['desc'] ) ) : ?>
								<?php echo "<small>{$setting['desc']}</small>"; ?>
							<?php endif; ?>
						</p>
						<?php
						break;

					case 'select' :
						?>
						<p>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<select class="widefat <?php echo esc_attr( $class ); ?>"
							        id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							        name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
								<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
									<option
										value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
								<?php endforeach; ?>
							</select>
							<?php if ( isset( $setting['desc'] ) ) : ?>
								<?php echo "<small>{$setting['desc']}</small>"; ?>
							<?php endif; ?>
						</p>
						<?php
						break;

					case 'textarea' :
						?>
						<p>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<textarea class="widefat <?php echo esc_attr( $class ); ?>"
							          id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							          name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" cols="20"
							          rows="3"><?php echo esc_textarea( $value ); ?></textarea>
							<?php if ( isset( $setting['desc'] ) ) : ?>
								<?php echo "<small>{$setting['desc']}</small>"; ?>
							<?php endif; ?>
						</p>
						<?php
						break;

					case 'checkbox' :
						?>
						<p>
							<input class="checkbox <?php echo esc_attr( $class ); ?>"
							       id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox"
							       value="1" <?php checked( $value, 1 ); ?> />
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<?php if ( isset( $setting['desc'] ) ) : ?>
								<?php echo "<small>{$setting['desc']}</small>"; ?>
							<?php endif; ?>
						</p>
						<?php
						break;

					case 'image':
						?>
						<div class="kungfu-attach-wrap">
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
							<?php
							?>
							<div class="kungfu-media-image">
								<?php if ( $value ) : ?>
									<?php Unicamp_Image::the_attachment_by_id( [
										'id'   => $value,
										'size' => '150x150',
									] ); ?>
								<?php endif; ?>
							</div>
							<input type="hidden" class="kungfu-media"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
							       value="<?php echo esc_attr( $value ); ?>"/>
							<a class="kungfu-media-open kungfu-button success">
								<i class="fa fa-upload"></i><?php esc_html_e( 'Upload', 'unicamp' ); ?>
							</a>
							<a class="kungfu-media-remove kungfu-button danger"
								<?php if ( empty( $value ) ) : ?>
									style="display:none"
								<?php endif; ?>
							><i class="fa fa-trash-o"></i><?php esc_html_e( 'Remove', 'unicamp' ); ?></a>
						</div>
						<?php
						break;

					// Default: run an action.
					default :
						do_action( 'unicamp_widget_field_' . $setting['type'], $key, $value, $setting, $instance );
						break;
				}
			}
		}

		/**
		 * Placeholder function for children update settings before form render.
		 */
		public function set_form_settings() {
		}

		public function get_value( $instance, $setting_name ) {
			if ( isset( $instance["{$setting_name}"] ) ) {
				return $instance["{$setting_name}"];
			} elseif ( isset( $this->settings["{$setting_name}"]['std'] ) ) {
				return $this->settings["{$setting_name}"]['std'];
			}

			return '';
		}
	}
}
