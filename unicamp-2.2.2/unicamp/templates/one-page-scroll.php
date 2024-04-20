<?php
/**
 * Template Name: One Page Scroll
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

get_header();
?>
	<div id="page-content" class="page-content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>
		<!--<div class="container">
			<div class="row">
				<div class="page-main-content">

				</div>
			</div>
		</div>-->
	</div>
<?php get_footer();
