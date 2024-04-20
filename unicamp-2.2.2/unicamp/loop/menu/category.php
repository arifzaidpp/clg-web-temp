<ul class="children course-list">
	<?php while ( $unicamp_query->have_posts() ) :
		$unicamp_query->the_post();

		$course_id = get_the_ID();
		$classes   = [ 'course-item grid-item' ];
		?>
		<li <?php post_class( implode( ' ', $classes ) ); ?>>
			<a href="<?php the_permalink(); ?>" class="course-wrapper course-permalink link-secret unicamp-box">
				<div class="course-thumbnail unicamp-image">
					<?php if ( has_post_thumbnail() ) { ?>
						<?php Unicamp_Image::the_post_thumbnail( array( 'size' => '52x40' ) ); ?>
					<?php } else { ?>
						<?php Unicamp_Templates::image_placeholder( 52, 40 ); ?>
					<?php } ?>
				</div>

				<div class="course-caption">
					<h2 class="course-title"><?php the_title(); ?></h2>

					<div class="course-loop-price">
						<div class="course-price">
							<?php Unicamp_Tutor::instance()->get_the_price_html(); ?>
						</div>
					</div>
				</div>
			</a>
		</li>
	<?php endwhile; ?>
</ul>
