<?php
/**
 * The Template for displaying booking form in single event page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/booking-form.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;

$event    = new WPEMS_Event( $event_id );
$post     = $event->post;
$user_reg = $event->booked_quantity( get_current_user_id() );
?>
<div class="event_register_area">

	<div class="event_register_header">
		<div class="event-popup-date primary-color">
			<?php
			$time = wpems_get_time( 'Y-m-d H:i', $post, false );
			$date = wp_date( 'F d', strtotime( $time ) );
			?>
		</div>

		<h3 class="event-popup-title"><?php echo esc_html( $event->get_title() ); ?></h3>

		<?php if ( has_post_thumbnail( $post ) ): ?>
			<div class="event-popup-thumbnail">
				<?php
				Unicamp_Image::the_post_thumbnail( [
					'post_id' => $event_id,
					'size'    => '410x250',
				] );
				?>
			</div>
		<?php endif; ?>
	</div>

	<form name="event_register" class="event_register" method="POST">

		<?php
		$col_class            = 'col-md-6';
		$register_multi_times = true;
		if ( $user_reg == 0 && $event->is_free() && wpems_get_option( 'email_register_times' ) === 'once' ) {
			$register_multi_times = false;
			$col_class            = 'col-md-12';
		}
		?>

		<div class="row row-xs-center">

			<?php if ( ! $register_multi_times ): ?>
				<input type="hidden" name="qty" value="1" min="1"/>
			<?php else: ?>
				<div class="col-md-6">
					<div class="event_auth_form_field">
						<label for="event_register_qty"><?php esc_html_e( 'Quantity', 'unicamp' ); ?></label>
						<div class="quantity">
							<button type="button" class="increase"></button>
							<input type="number" name="qty" value="1" min="1"
							       max="<?php echo esc_attr( $event->get_slot_available() ); ?>"
							       id="event_register_qty"
							       class="qty"/>
							<button type="button" class="decrease"></button>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="<?php echo esc_attr( $col_class ); ?>">
				<?php $payments = wpems_gateways_enable(); ?>
				<!--Hide payment option when cost is 0-->
				<?php if ( ! $event->is_free() ) {
					if ( $payments ) { ?>
						<ul class="event_auth_payment_methods">
							<?php $i = 0; ?>
							<?php foreach ( $payments as $id => $payment ) : ?>
								<li>
									<input id="payment_method_<?php echo esc_attr( $id ) ?>" type="radio"
									       name="payment_method"
									       value="<?php echo esc_attr( $id ) ?>"
										<?php if ( $i === 0 ) {
											echo ' checked';
										} ?>
									/>
									<label
										for="payment_method_<?php echo esc_attr( $id ) ?>"><?php echo esc_html( $payment->get_title() ); ?></label>
								</li>
								<?php $i++; ?>
							<?php endforeach; ?>
						</ul>
					<?php } else {
						wpems_print_notice( 'error', esc_html__( 'There are no payment gateway available. Please contact administrator to setup it.', 'unicamp' ) );
					}
				} ?>
				<!--End hide payment option when cost is 0-->
			</div>
		</div>

		<div class="event_register_foot">
			<input type="hidden" name="event_id" value="<?php echo esc_attr( $event_id ) ?>"/>
			<input type="hidden" name="action" value="event_auth_register"/>
			<?php wp_nonce_field( 'event_auth_register_nonce', 'event_auth_register_nonce' ); ?>

			<button class="event_register_submit event_auth_button button" <?php if ( ! isset( $payments ) ) {
				echo 'disabled="disabled"';
			} ?>>
				<?php esc_html_e( 'Book Now', 'unicamp' ); ?>
			</button>
		</div>

	</form>

</div>
