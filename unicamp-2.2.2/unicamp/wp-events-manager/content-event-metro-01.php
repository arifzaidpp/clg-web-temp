<?php
$loop_count = 0;
$count      = $unicamp_query->post_count;

while ( $unicamp_query->have_posts() ) : $unicamp_query->the_post();
	$classes = array( 'grid-item post-item' );
	$loop_count++;

	$width      = 1;
	$image_size = '480x290';

	if ( 1 === $loop_count ) {
		$image_size = '690x690';
		$width      = 2;
	}
	?>
	<div <?php post_class( implode( ' ', $classes ) ); ?>
		data-width="<?php echo esc_attr( $width ); ?>"
	>
		<a href="<?php the_permalink(); ?>" class="post-wrapper unicamp-box link-secret">
			<div class="post-thumbnail unicamp-image">
				<?php if ( has_post_thumbnail() ) { ?>
					<?php Unicamp_Image::the_post_thumbnail( [
						'size' => $image_size,
					] ); ?>
				<?php } else { ?>
					<?php Unicamp_Templates::image_placeholder( 480, 290 ); ?>
				<?php } ?>
			</div>
			<div class="event-info">
				<div class="event-caption">
					<div class="event-start-date">
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
						</div>
					</div>
				</div>
			</div>

		</a>
	</div>
<?php endwhile; ?>
