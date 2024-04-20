<?php
/**
 * The template for displaying shortcode
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/shortcode/tm-zoom-meeting.php.
 *
 * @author     Thememove
 * @package    Unicamp
 * @since      2.0.0
 */

global $zoom_meetings;
?>

<div class="tm-zoom-meeting">
	<div class="zoom-content">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" height="55"
		     class="zoom-shape">
			<path class="zoom-shape-fill" d="M 0 0 L0 100 L100 100 L100 0 Q 50 200 0 0"/>
		</svg>

		<img class="zoom-shape-img shape-img-01" src="<?php echo UNICAMP_THEME_IMAGE_URI . '/shape-three-line.png'; ?>"
		     alt="<?php esc_attr_e( 'Unicamp Shape', 'unicamp' ); ?>">
		<img class="zoom-shape-img shape-img-02" src="<?php echo UNICAMP_THEME_IMAGE_URI . '/shape-cut-circle.png'; ?>"
		     alt="<?php esc_attr_e( 'Unicamp Shape', 'unicamp' ); ?>">

		<div class="zoom-main-content">
			<h2 class="zoom-topic"><?php echo esc_html( $zoom_meetings->topic ); ?></h2>
			<div class="zoom-id">
				<span class="label"><?php esc_html_e( 'Meeting ID:', 'unicamp' ); ?></span>
				<span class="value primary-color"><?php echo esc_html( $zoom_meetings->id ); ?></span>
			</div>

			<div class="zoom-meta">

				<?php
				if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 ) {
					if ( ! empty( $zoom_meetings->occurrences ) ) {
						?>
						<div class="zoom-meta-item zoom-meta-type">
							<span class="meta-label"><?php esc_html_e( 'Type', 'unicamp' ); ?></span>
							<span
								class="meta-value"><?php esc_html_e( 'Recurring Meeting', 'unicamp' ); ?></span>
						</div>
						<div class="zoom-meta-item zoom-meta-occurrences">
							<span class="meta-label"><?php esc_html_e( 'Occurrences', 'unicamp' ); ?></span>
							<span
								class="meta-value"><?php echo count( $zoom_meetings->occurrences ); ?></span>
						</div>

						<div class="zoom-meta-item zoom-meta-next-start-time">
							<span class="meta-label"><?php esc_html_e( 'Next Start Time', 'unicamp' ); ?></span>
							<span class="meta-value">
							<?php
							$now               = new DateTime( 'now -1 hour', new DateTimeZone( $zoom_meetings->timezone ) );
							$closest_occurence = false;
							if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 && ! empty( $zoom_meetings->occurrences ) ) {
								foreach ( $zoom_meetings->occurrences as $occurrence ) {
									if ( $occurrence->status === "available" ) {
										$start_date = new DateTime( $occurrence->start_time, new DateTimeZone( $zoom_meetings->timezone ) );
										if ( $start_date >= $now ) {
											$closest_occurence = $occurrence->start_time;
											break;
										}

										esc_html_e( 'Meeting has ended !', 'unicamp' );
										break;
									}
								}
							}

							if ( $closest_occurence ) {
								echo vczapi_dateConverter( $closest_occurence, $zoom_meetings->timezone, 'F j, Y @ g:i a' );
							} else {
								esc_html_e( 'Meeting has ended !', 'unicamp' );
							}
							?>
						</span>
						</div>
						<?php
					} else {
						?>
						<div class="zoom-meta-item zoom-meta-start-time">
							<span class="meta-label"><?php esc_html_e( 'Start Time', 'unicamp' ); ?></span>
							<span
								class="meta-value"><?php esc_html_e( 'Meeting has ended !', 'unicamp' ); ?></span>
						</div>
						<?php
					}
				} else if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 3 ) {
					?>
					<div class="zoom-meta-item zoom-meta-start-time">
						<span class="meta-label"><?php esc_html_e( 'Start Time', 'unicamp' ); ?></span>
						<span
							class="meta-value"><?php esc_html_e( 'This is a meeting with no Fixed Time.', 'unicamp' ); ?></span>
					</div>
				<?php } else { ?>
					<div class="zoom-meta-item zoom-meta-start-time">
						<span class="meta-label"><?php esc_html_e( 'Start Time', 'unicamp' ); ?></span>
						<span
							class="meta-value"><?php echo vczapi_dateConverter( $zoom_meetings->start_time, $zoom_meetings->timezone, 'F j, Y @ g:i a' ); ?></span>
					</div>
				<?php } ?>

				<div class="zoom-meta-item zoom-meta-timezone">
					<span class="meta-label"><?php esc_html_e( 'Timezone', 'unicamp' ); ?></span>
					<span class="meta-value"><?php echo esc_html( $zoom_meetings->timezone ); ?></span>
				</div>
				<?php if ( ! empty( $zoom_meetings->duration ) ) : ?>
					<div class="zoom-meta-item zoom-meta-duration">
						<span class="meta-label"><?php esc_html_e( 'Duration', 'unicamp' ); ?></span>
						<span
							class="meta-value"><?php echo esc_html( Unicamp_Datetime::convertToHoursMinutes( $zoom_meetings->duration ) ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $zoom_meetings->start_time ) ): ?>
				<div class="zoom-countdown"
				     data-date="<?php echo Unicamp_Datetime::convertCountdownDate( $zoom_meetings->start_time, $zoom_meetings->timezone ); ?>"
				     data-days-text="<?php esc_attr_e( 'Days', 'unicamp' ); ?>"
				     data-hours-text="<?php esc_attr_e( 'Hours', 'unicamp' ); ?>"
				     data-minutes-text="<?php esc_attr_e( 'Mins', 'unicamp' ); ?>"
				     data-seconds-text="<?php esc_attr_e( 'Secs', 'unicamp' ); ?>"
				>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="zoom-buttons">
		<?php
		\Unicamp\Zoom_Meeting\Utils::instance()->zoom_shortcode_join_link( $zoom_meetings );
		?>
	</div>
</div>
