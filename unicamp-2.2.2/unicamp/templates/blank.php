<?php
/**
 * Template Name: Blank Page
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

get_header( 'blank' );
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">
				<div id="page-main-content" class="page-main-content col-md-12">
					<?php
					if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
					<?php
					endwhile;
						the_posts_navigation();
					else :
						unicamp_load_template( 'global/content-none' );
					endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer( 'blank' );
