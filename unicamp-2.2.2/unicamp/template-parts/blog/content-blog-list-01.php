<?php
/**
 * The template for displaying content blog list item.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="post-wrapper unicamp-box">
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="post-feature post-thumbnail unicamp-image">
			<a href="<?php the_permalink(); ?>">
				<?php Unicamp_Image::the_post_thumbnail( [ 'size' => '770x400' ] ); ?>
			</a>
		</div>
	<?php } ?>

	<div class="post-caption">
		<?php Unicamp_Post::instance()->the_categories( [ 'number' => 2 ] ); ?>

		<?php unicamp_load_template( 'blog/loop/title' ); ?>

		<?php unicamp_load_template( 'blog/loop/excerpt-long' ); ?>

		<div class="post-footer">
			<?php unicamp_load_template( 'blog/loop/meta-alt' ); ?>

			<?php unicamp_load_template( 'blog/loop/read-more-small' ); ?>
		</div>
	</div>
</div>
