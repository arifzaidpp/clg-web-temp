<?php
/**
 * The template for displaying content blog grid item.
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
				<?php Unicamp_Image::the_post_thumbnail( [ 'size' => '260x150' ] ); ?>
			</a>
		</div>
	<?php } ?>

	<div class="post-caption">
		<?php unicamp_load_template( 'blog/loop/author' ); ?>
		<?php unicamp_load_template( 'blog/loop/title-collapsed' ); ?>
	</div>
</div>
