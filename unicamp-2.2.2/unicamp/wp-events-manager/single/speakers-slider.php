<?php
/**
 * Template part for displaying our speakers block on single page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/single/speakers.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

$speakers_on = Unicamp::setting( 'single_event_speaker_enable' );

if ( '1' !== $speakers_on ) {
	return;
}

$speakers = Unicamp_Event::instance()->get_the_speakers();

if ( empty( $speakers ) ) {
	return;
}

$speaker_description = Unicamp::setting( 'single_event_speaker_text' );
?>
<div class="entry-event-section entry-event-speakers">
	<h3 class="entry-event-heading box-title-with-separator"><?php esc_html_e( 'Our Speakers', 'unicamp' ); ?></h3>

	<div class="tm-swiper tm-slider event-speakers-slider"
	     data-lg-items="1"
	     data-lg-gutter="30"
	>
		<div class="swiper-inner">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ( $speakers as $speaker ) : ?>
						<?php
						$term_thumbnail_id = get_term_meta( $speaker->term_id, 'thumbnail_id', true );
						$speaker_job       = get_term_meta( $speaker->term_id, 'speaker_job', true );
						?>
						<div class="swiper-slide">
							<div class="speaker-item">
								<?php if ( $term_thumbnail_id ) : ?>
									<div class="speaker-thumbnail">
										<?php Unicamp_Image::the_attachment_by_id( [
											'id'   => $term_thumbnail_id,
											'size' => '170x170',
										] ); ?>
									</div>
								<?php endif; ?>
								<div class="speaker-info">
									<div class="speaker-name fn"><?php echo esc_html( $speaker->name ); ?></div>
									<?php if ( ! empty( $speaker_job ) ) : ?>
										<div class="speaker-job"><?php echo esc_html( $speaker_job ); ?></div>
									<?php endif; ?>
									<div
										class="speaker-description"><?php echo esc_html( $speaker->description ); ?></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
