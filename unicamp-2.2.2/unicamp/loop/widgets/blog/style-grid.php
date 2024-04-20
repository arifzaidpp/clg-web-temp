<?php
while ( $unicamp_query->have_posts() ) :
	$unicamp_query->the_post();
	$classes = array( 'grid-item', 'post-item' );
	?>
	<div <?php post_class( implode( ' ', $classes ) ); ?>>
		<div class="post-wrapper unicamp-box">

			<?php if ( has_post_thumbnail() ) { ?>
				<div class="post-feature post-thumbnail unicamp-image">
					<a href="<?php the_permalink(); ?>">
						<?php
						$size = Unicamp_Image::elementor_parse_image_size( $settings, '480x325' );
						Unicamp_Image::the_post_thumbnail( array( 'size' => $size ) );
						?>
					</a>

					<?php if ( 'yes' === $settings['show_overlay'] ) : ?>
						<?php get_template_part( 'loop/widgets/blog/overlay/overlay', $settings['overlay_style'] ); ?>
					<?php endif; ?>
				</div>
			<?php } ?>

			<?php if ( 'yes' === $settings['show_caption'] ) : ?>
				<?php get_template_part( 'loop/widgets/blog/caption/caption', $settings['caption_style'] ); ?>
			<?php endif; ?>
		</div>
	</div>
<?php endwhile;
