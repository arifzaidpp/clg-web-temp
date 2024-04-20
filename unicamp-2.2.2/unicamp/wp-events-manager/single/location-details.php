<?php
/**
 * Template part for displaying event location & contact info on single page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/single/location-details.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

$place        = get_post_meta( get_the_ID(), 'tp_event_place', true );
$location     = get_post_meta( get_the_ID(), 'tp_event_location', true );
$phone_number = get_post_meta( get_the_ID(), 'tp_event_phone_number', true );
$website      = get_post_meta( get_the_ID(), 'tp_event_website', true );
$email        = get_post_meta( get_the_ID(), 'tp_event_email', true );
?>
<div class="event-location-details">
	<div class="row">
		<div class="col-sm-6">
			<?php if ( ! empty( $place ) ) : ?>
				<p class="entry-event-place"><?php echo esc_html( $place ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $location ) ) : ?>
				<div class="entry-event-location-address">
					<?php echo esc_html( $location ); ?>
					<p class="event-google-map-link">
						<a href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . $location ); ?>" class="link-transition-01">
							<?php esc_html_e( '+ Google Map', 'unicamp' ); ?>
						</a>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<div class="col-sm-6">
			<div class="entry-event-contact-info">
				<?php if ( ! empty( $phone_number ) ) : ?>
					<div class="meta-item entry-event-phone-number">
						<span class="meta-label"><?php esc_html_e( 'Phone Number', 'unicamp' ); ?></span>
						<span class="meta-value"><?php echo esc_html( $phone_number ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $email ) ) : ?>
					<div class="meta-item entry-event-email">
						<span class="meta-label"><?php esc_html_e( 'Email', 'unicamp' ); ?></span>
						<span class="meta-value"><?php echo esc_html( $email ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $website ) ) : ?>
					<div class="meta-item entry-event-website">
						<span class="meta-label"><?php esc_html_e( 'Website', 'unicamp' ); ?></span>
						<span class="meta-value"><?php echo esc_html( $website ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
