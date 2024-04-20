<?php
if ( ! isset( $settings ) ) {
	$settings = array();
}
$loop_count        = 0;
$left_box_template = $right_box_template = '';
?>
<?php while ( $unicamp_query->have_posts() ) : $unicamp_query->the_post(); ?>
	<?php if ( $loop_count === 0 ) : ?>
		<?php ob_start(); ?>
		<div <?php post_class( 'grid-item' ); ?>>
			<a href="<?php the_permalink(); ?>"  class="unicamp-box link-secret">
				<div class="event-thumbnail unicamp-image">
					<?php \Unicamp_Image::the_post_thumbnail( [
						'size' => '670x670',
					] ); ?>
				</div>
				<div class="event-info">
					<div class="event-caption">
						<div class="event-date">
							<?php
							$date_start = get_post_meta( get_the_ID(), 'tp_event_date_start', true );
							$time_from  = $date_start ? strtotime( $date_start ) : time();
							?>
							<span><?php echo wp_date( 'M d', $time_from ); ?></span>
						</div>

						<h3 class="event-title post-title-2-rows"><span><?php the_title(); ?></span></h3>

						<div class="event-meta">
							<div class="meta-list">
								<?php
								$time_start = wpems_event_start( get_option( 'time_format' ) );
								$time_end   = wpems_event_end( get_option( 'time_format' ) );
								?>
								<div
									class="meta-item event-time"><?php echo esc_html( $time_start . ' - ' . $time_end ); ?></div>

								<?php $location = get_post_meta( get_the_ID(), \Unicamp_Event::POST_META_SHORT_LOCATION, true ); ?>
								<?php if ( $location ): ?>
									<div class="meta-item event-location">
										<?php echo esc_html( $location ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<?php $left_box_template .= ob_get_clean(); ?>
	<?php else: ?>
		<?php ob_start(); ?>
		<div <?php post_class( 'grid-item' ); ?>>
			<a href="<?php the_permalink(); ?>" class="unicamp-box link-secret">
				<div class="event-thumbnail unicamp-image">
					<?php \Unicamp_Image::the_post_thumbnail( [
						'size' => '200x120',
					] ); ?>

					<div class="event-date">
						<?php
						$date_start = get_post_meta( get_the_ID(), 'tp_event_date_start', true );
						$time_from  = $date_start ? strtotime( $date_start ) : time();
						?>
						<span><?php echo wp_date( 'M d', $time_from ); ?></span>
					</div>
				</div>
				<div class="event-info">
					<div class="event-caption">
						<h3 class="event-title post-title-2-rows"><?php the_title(); ?></h3>
						<div class="event-meta">
							<div class="meta-list">
								<?php
								$time_start = wpems_event_start( get_option( 'time_format' ) );
								$time_end   = wpems_event_end( get_option( 'time_format' ) );
								?>
								<div
									class="meta-item event-time"><?php echo esc_html( $time_start . ' - ' . $time_end ); ?></div>

								<?php $location = get_post_meta( get_the_ID(), \Unicamp_Event::POST_META_SHORT_LOCATION, true ); ?>
								<?php if ( $location ): ?>
									<div class="meta-item event-location">
										<?php echo esc_html( $location ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<?php $right_box_template .= ob_get_clean(); ?>
	<?php endif; ?>
	<?php $loop_count++; ?>
<?php endwhile; ?>
<div class="row row-no-gutter">
	<div class="col-md-6 featured-event">
		<?php echo '' . $left_box_template; ?>
	</div>
	<div class="col-md-6 normal-events">
		<?php echo '' . $right_box_template; ?>
	</div>
</div>

