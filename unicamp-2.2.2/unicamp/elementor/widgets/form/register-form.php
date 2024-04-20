<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Register_Form extends Form_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_script( 'validate', UNICAMP_THEME_URI . '/assets/libs/validate/jquery.validate.min.js', [ 'jquery' ], '1.17.0', true );

		wp_register_script( 'unicamp-widget-register-form', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/widget-form-register.js', [
			'elementor-frontend',
			'validate',
		], null, true );

		$js_variables = array(
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'validatorMessages' => [
				'required'    => esc_html__( 'This field is required', 'unicamp' ),
				'remote'      => esc_html__( 'Please fix this field', 'unicamp' ),
				'email'       => esc_html__( 'A valid email address is required', 'unicamp' ),
				'url'         => esc_html__( 'Please enter a valid URL', 'unicamp' ),
				'date'        => esc_html__( 'Please enter a valid date', 'unicamp' ),
				'dateISO'     => esc_html__( 'Please enter a valid date (ISO)', 'unicamp' ),
				'number'      => esc_html__( 'Please enter a valid number.', 'unicamp' ),
				'digits'      => esc_html__( 'Please enter only digits.', 'unicamp' ),
				'creditcard'  => esc_html__( 'Please enter a valid credit card number', 'unicamp' ),
				'equalTo'     => esc_html__( 'Please enter the same value again', 'unicamp' ),
				'accept'      => esc_html__( 'Please enter a value with a valid extension', 'unicamp' ),
				'maxlength'   => esc_html__( 'Please enter no more than {0} characters', 'unicamp' ),
				'minlength'   => esc_html__( 'Please enter at least {0} characters', 'unicamp' ),
				'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long', 'unicamp' ),
				'range'       => esc_html__( 'Please enter a value between {0} and {1}', 'unicamp' ),
				'max'         => esc_html__( 'Please enter a value less than or equal to {0}', 'unicamp' ),
				'min'         => esc_html__( 'Please enter a value greater than or equal to {0}', 'unicamp' ),
			],
		);
		wp_localize_script( 'unicamp-widget-register-form', '$unicampLogin', $js_variables );


	}

	public function get_script_depends() {
		return [ 'unicamp-widget-register-form' ];
	}

	public function get_name() {
		return 'tm-register-form';
	}

	public function get_title() {
		return esc_html__( 'Register Form', 'unicamp' );
	}

	public function get_keywords() {
		return [ 'register', 'form', 'sign-up' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_button_section();

		$this->add_field_style_section();

		$this->add_button_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'form_title', [
			'label'   => esc_html__( 'Form Title', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => esc_html__( 'Register', 'unicamp' ),
		] );

		$this->add_control( 'show_labels', [
			'label'        => esc_html__( 'Label', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'label_off'    => esc_html__( 'Hide', 'unicamp' ),
			'label_on'     => esc_html__( 'Show', 'unicamp' ),
			'prefix_class' => 'labels-',
			'render_type'  => 'template',
		] );

		$this->add_control( 'show_icons', [
			'label'        => esc_html__( 'Icon', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'label_off'    => esc_html__( 'Hide', 'unicamp' ),
			'label_on'     => esc_html__( 'Show', 'unicamp' ),
			'prefix_class' => 'icons-',
			'render_type'  => 'template',
		] );

		$this->add_control( 'show_login_link', [
			'label'        => esc_html__( 'Login Link', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'label_off'    => esc_html__( 'Hide', 'unicamp' ),
			'label_on'     => esc_html__( 'Show', 'unicamp' ),
			'prefix_class' => 'labels-',
			'render_type'  => 'template',
		] );

		$this->end_controls_section();
	}

	private function add_button_section() {
		$this->start_controls_section( 'submit_section', [
			'label' => esc_html__( 'Button', 'unicamp' ),
		] );

		$this->add_control( 'button_text', [
			'label'   => esc_html__( 'Text', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => esc_html__( 'Sign Up', 'unicamp' ),
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'form', [
			'class'  => 'register-form',
			'action' => '#',
			'method' => 'POST',
		] );

		$extra_class[] = ! empty( $settings['show_labels'] ) && 'yes' === $settings['show_labels'] ? 'labels-on' : 'labels-off';

		$show_icon = ! empty( $settings['show_icons'] ) && 'yes' === $settings['show_icons'] ? true : false;

		$extra_class[] = ! empty( $settings['show_icons'] ) && 'yes' === $settings['show_icons'] ? 'icons-on' : 'icons-off';

		$this->add_render_attribute( 'form', 'class', $extra_class );

		$button_text = $settings['button_text'];

		$form_id           = $this->get_id();
		$field_username_id = "field_username_$form_id";
		$field_email_id    = "field_email_$form_id";
		$field_password_id = "field_password_$form_id";
		?>

		<div class="unicamp-register-form">
			<div class="unicamp-register-form-box">
				<?php if ( ! empty( $settings['form_title'] ) ) : ?>
					<h4 class="form-title"><?php echo esc_html( $settings['form_title'] ); ?></h4>
				<?php endif; ?>

				<?php \Unicamp_Templates::render_button( [
					'link'        => [
						'url' => 'javascript:void(0)',
					],
					'text'        => esc_html__( 'Sign up with your E-mail', 'unicamp' ),
					'icon'        => 'fal fa-envelope',
					'style'       => 'thick-border',
					'extra_class' => 'button-grey toggle-register-form',
				] ); ?>

				<?php do_action( 'unicamp_before_user_register_form' ); ?>

				<form <?php $this->print_render_attribute_string( 'form' ); ?> style="display: none;">
					<div class="form-group">
						<label for="<?php echo esc_attr( $field_username_id ); ?>"
						       class="form-label"><?php esc_html_e( 'Username', 'unicamp' ); ?></label>
						<div class="form-input-group">
							<?php if ( $show_icon ) : ?>
								<span class="form-icon"><i class="far fa-user"></i></span>
							<?php endif; ?>
							<input type="text" id="<?php echo esc_attr( $field_username_id ); ?>"
							       class="form-control form-input"
							       name="username" placeholder="<?php esc_attr_e( 'Username', 'unicamp' ); ?>"/>
						</div>
					</div>

					<div class="form-group">
						<label for="<?php echo esc_attr( $field_email_id ); ?>"
						       class="form-label"><?php esc_html_e( 'Email', 'unicamp' ); ?></label>
						<div class="form-input-group">
							<?php if ( $show_icon ) : ?>
								<span class="form-icon"><i class="far fa-envelope"></i></span>
							<?php endif; ?>
							<input type="email" id="<?php echo esc_attr( $field_email_id ); ?>"
							       class="form-control form-input"
							       name="email" placeholder="<?php esc_attr_e( 'Your Email', 'unicamp' ); ?>"/>
						</div>
					</div>

					<div class="form-group">
						<label for="<?php echo esc_attr( $field_password_id ); ?>"
						       class="form-label"><?php esc_html_e( 'Password', 'unicamp' ); ?></label>
						<div class="form-input-group form-input-password">
							<?php if ( $show_icon ) : ?>
								<span class="form-icon"><i class="far fa-key"></i></span>
							<?php endif; ?>
							<input type="password" id="<?php echo esc_attr( $field_password_id ); ?>"
							       class="form-control form-input"
							       name="password" placeholder="<?php esc_attr_e( 'Password', 'unicamp' ); ?>">
						</div>
					</div>

					<div class="form-response-messages"></div>

					<div class="form-submit">
						<?php wp_nonce_field( 'unicamp_elementor_widget_user_register', 'unicamp_elementor_widget_user_register_nonce' ); ?>
						<input type="hidden" name="action" value="unicamp_elementor_widget_user_register">
						<button type="submit"
						        class="button button-submit"><?php echo esc_html( $button_text ); ?></button>
					</div>
				</form>

				<?php do_action( 'unicamp_after_user_register_form' ); ?>

				<?php if ( 'yes' === $settings['show_login_link'] ) : ?>
					<?php if ( \Unicamp_Helper::is_elementor_editor() || ! is_user_logged_in() ) : ?>
						<p class="login-link">
							<?php printf( esc_html__( 'Already have an account? %sLog in%s', 'unicamp' ), '<a href="#" class="open-popup-login link-transition-01">', '</a>' ); ?>
						</p>
					<?php endif; ?>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}
}
