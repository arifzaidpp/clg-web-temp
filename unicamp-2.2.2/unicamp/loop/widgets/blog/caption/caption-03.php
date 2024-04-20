<div class="post-caption">

	<?php if ( 'yes' === $settings['show_caption_category'] ) : ?>
		<?php Unicamp_Post::instance()->the_categories( [ 'number' => 1 ] ); ?>
	<?php endif; ?>

	<?php if ( ! empty( $settings['show_caption_meta'] ) ) : ?>
		<?php $meta = $settings['show_caption_meta']; ?>
		<div class="post-meta">
			<div class="inner">
				<?php if ( in_array( 'author', $meta, true ) ): ?>
					<?php Unicamp_Post::instance()->meta_author_template(); ?>
				<?php endif; ?>

				<?php if ( in_array( 'date', $meta, true ) ): ?>
					<?php Unicamp_Post::instance()->meta_date_template(); ?>
				<?php endif; ?>

				<?php if ( in_array( 'views', $meta, true ) ): ?>
					<?php Unicamp_Post::instance()->meta_view_count_template(); ?>
				<?php endif; ?>

				<?php if ( in_array( 'comments', $meta, true ) ): ?>
					<?php Unicamp_Post::instance()->meta_comment_count_template(); ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<h3 class="post-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>

	<?php if ( 'yes' === $settings['show_caption_excerpt'] ) : ?>
		<?php
		if ( empty( $settings['excerpt_length'] ) ) {
			$settings['excerpt_length'] = 10;
		}
		?>
		<div class="post-excerpt">
			<?php Unicamp_Templates::excerpt( array(
				'limit' => $settings['excerpt_length'],
				'type'  => 'word',
			) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( 'yes' === $settings['show_caption_read_more'] || 'yes' === $settings['show_caption_share'] ): ?>
		<div class="post-footer">
			<?php if ( 'yes' === $settings['show_caption_read_more'] ): ?>
				<?php
				$read_more_text = ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : esc_html__( 'Read more', 'unicamp' );

				Unicamp_Templates::render_button( [
					'style'         => 'bottom-line',
					'text'          => $read_more_text,
					'icon'          => 'far fa-long-arrow-right',
					'icon_align'    => 'right',
					'link'          => [
						'url' => get_the_permalink(),
					],
					'size'          => 'nm',
					'wrapper_class' => 'post-read-more',
				] );
				?>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_caption_share'] ): ?>
				<?php Unicamp_Post::instance()->loop_share(); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

</div>
