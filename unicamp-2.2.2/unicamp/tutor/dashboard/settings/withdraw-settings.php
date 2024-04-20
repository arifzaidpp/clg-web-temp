<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 * @theme-version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

$tutor_withdrawal_methods = apply_filters( 'tutor_withdrawal_methods_available', array() );
?>
<h3><?php esc_html_e( 'Settings', 'unicamp' ) ?></h3>

<div class="tutor-dashboard-setting-withdraw tutor-dashboard-content-inner">
	<div class="tutor-dashboard-inline-links">
		<?php tutor_load_template( 'dashboard.settings.nav-bar', [ 'active_setting_nav' => 'withdrawal' ] ); ?>
	</div>

	<form id="tutor-withdraw-account-set-form" action="" method="post"
	      class="dashboard-settings-form dashboard-settings-withdraw-form">
		<div class="dashboard-content-box dashboard-content-box-small">
			<h4 class="dashboard-content-box-title"><?php esc_html_e( 'Select a withdraw method', 'unicamp' ); ?></h4>
			<?php if ( tutor_utils()->count( $tutor_withdrawal_methods ) ) : ?>
				<?php
				$saved_account       = tutor_utils()->get_user_withdraw_method();
				$old_method_key      = tutor_utils()->avalue_dot( 'withdraw_method_key', $saved_account );
				$min_withdraw_amount = tutor_utils()->get_option( 'min_withdraw_amount' );
				?>
				<div class="withdraw-method-select-wrap">
					<?php foreach ( $tutor_withdrawal_methods as $method_id => $method ) : ?>
						<div class="withdraw-method-select <?php echo 'withdraw-method-' . $method_id; ?>"
						     data-withdraw-method="<?php echo esc_attr( $method_id ); ?>">
							<input type="radio" id="withdraw_method_select_<?php echo esc_attr( $method_id ); ?>"
							       class="withdraw-method-select-input"
							       name="tutor_selected_withdraw_method" value="<?php echo esc_attr( $method_id ); ?>"
							       style="display: none;"
								<?php checked( $method_id, $old_method_key ); ?> >

							<label for="withdraw_method_select_<?php echo esc_attr( $method_id ); ?>">
								<span
									class="method-name"><?php echo tutor_utils()->avalue_dot( 'method_name', $method ); ?></span>
								<span
									class="method-amount"><?php esc_html_e( 'Min withdraw', 'unicamp' ); ?><?php echo tutor_utils()->tutor_price( $min_withdraw_amount );
									?></span>
							</label>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="dashboard-withdraw-method-forms-wrap">
					<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
					<input type="hidden" value="tutor_save_withdraw_account" name="action"/>

					<?php do_action( 'tutor_withdraw_set_account_form_before' ); ?>

					<?php foreach ( $tutor_withdrawal_methods as $method_id => $method ) : ?>
						<?php
						$form_fields   = tutor_utils()->avalue_dot( 'form_fields', $method );
						$method_values = get_user_meta( get_current_user_id(), '_tutor_withdraw_method_data_' . $method_id, true );
						$method_values = maybe_unserialize( $method_values );
						! is_array( $method_values ) ? $method_values = array() : 0;
						?>

						<div data-withdraw-form="<?php echo esc_attr( $method_id ); ?>"
						     class="tutor-row withdraw-method-form"
						     style="<?php echo esc_attr( $old_method_key != $method_id ? 'display: none;' : '' ); ?>">
							<?php do_action( "tutor_withdraw_set_account_{$method_id}_before" ); ?>

							<?php
							if ( tutor_utils()->count( $form_fields ) ) {
								foreach ( $form_fields as $field_name => $field ) {
									?>
									<div
										class="withdraw-method-field-wrap tutor-form-group <?php echo 'withdraw-method-field-' . $field_name . ' ' . $field['type']; ?> ">
										<?php
										if ( ! empty( $field['label'] ) ) {
											echo "<label for='field_{$method_id}_$field_name'>{$field['label']}</label>";
										}

										$passing_data = apply_filters( 'tutor_withdraw_account_field_type_data', array(
											'method_id'  => $method_id,
											'method'     => $method,
											'field_name' => $field_name,
											'field'      => $field,
											'old_value'  => null,
										) );

										$old_value = tutor_utils()->avalue_dot( $field_name . ".value", $saved_account );
										if ( $old_value ) {
											$passing_data['old_value'] = $old_value;
										} else if ( isset( $method_values[ $field_name ], $method_values[ $field_name ]['value'] ) ) {
											$passing_data['old_value'] = $method_values[ $field_name ]['value'];
											$old_value                 = $passing_data['old_value'];
										}

										if ( in_array( $field['type'], array( 'text', 'number', 'email' ) ) ) {
											?>
											<input class="tutor-form-control tutor-mt-4"
											       type="<?php echo esc_attr( $field['type'] ); ?>"
											       name="withdraw_method_field[<?php echo esc_attr( $method_id ) ?>][<?php echo esc_attr( $field_name ) ?>]"
											       value="<?php echo esc_attr( $old_value ); ?>">
											<?php
										} else if ( $field['type'] == 'textarea' ) {
											?>
											<textarea class="tutor-form-control tutor-mt-4"
											          name="withdraw_method_field[<?php echo esc_attr( $method_id ) ?>][<?php echo esc_attr( $field_name ) ?>]">
                                                <?php echo wp_kses_post( $old_value ); ?>
                                            </textarea>
											<?php
										}

										if ( ! empty( $field['desc'] ) ) {
											echo wp_kses_post( "<div class='tutor-fs-7 tutor-fw-normal tutor-color-black-60 withdraw-field-desc tutor-mt-4'>{$field['desc']}</div>" );
										} ?>

									</div>
									<?php
								}
							}
							?>

							<?php do_action( "tutor_withdraw_set_account_{$method_id}_after" ); ?>
						</div>
					<?php endforeach; ?>

					<?php do_action( 'tutor_withdraw_set_account_form_after' ); ?>

				</div>
			<?php endif; ?>
		</div>
		<?php if ( tutor_utils()->count( $tutor_withdrawal_methods ) ) : ?>
			<div class="withdraw-account-save-btn-wrap">
				<button type="submit" class="tutor_set_withdraw_account_btn tutor-btn"
				        name="withdraw_btn_submit"><?php esc_html_e( 'Save Withdraw Account', 'unicamp' ); ?></button>
			</div>
		<?php endif; ?>
	</form>
</div>
