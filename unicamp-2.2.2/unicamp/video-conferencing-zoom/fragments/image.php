<?php
/**
 * The template for displaying meeting featured image of zoom
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/fragments/image.php.
 */
?>

<?php if ( has_post_thumbnail() ): ?>
	<div class="deepn-zvc-single-featured-img">
		<?php Unicamp_Image::the_post_thumbnail( [ 'size' => '1170x350' ] ); ?>
	</div>
<?php
endif;
