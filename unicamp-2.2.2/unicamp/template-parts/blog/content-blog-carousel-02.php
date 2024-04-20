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

if ( ! isset( $settings ) ) {
	$settings = array();
}
?>
<div class="post-wrapper unicamp-box">
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="post-feature post-thumbnail unicamp-image">
			<a href="<?php the_permalink(); ?>">
				<?php
				$size = Unicamp_Image::elementor_parse_image_size( $settings, '480x524' );
				Unicamp_Image::the_post_thumbnail( array( 'size' => $size ) );
				?>
			</a>
		</div>
	<?php } ?>

	<div class="post-caption">
		<?php Unicamp_Post::instance()->the_categories( [ 'number' => 2 ] ); ?>

		<?php unicamp_load_template( 'blog/loop/title-collapsed' ); ?>

		<?php unicamp_load_template( 'blog/loop/meta' ); ?>

		<?php //unicamp_load_template( 'blog/loop/excerpt' ); ?>
	</div>
</div>
