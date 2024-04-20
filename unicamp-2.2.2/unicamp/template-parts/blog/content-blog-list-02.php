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
	<div class="post-thumbnail-wrapper">
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="post-feature post-thumbnail unicamp-image">
				<a href="<?php the_permalink(); ?>">
					<?php Unicamp_Image::the_post_thumbnail( [ 'size' => '480x320' ] ); ?>
				</a>
			</div>
		<?php } ?>
	</div>

	<div class="post-caption">
		<?php Unicamp_Post::instance()->the_categories( [ 'number' => 2 ] ); ?>

		<?php unicamp_load_template( 'blog/loop/title-collapsed' ); ?>

		<?php unicamp_load_template( 'blog/loop/meta' ); ?>
	</div>
</div>
