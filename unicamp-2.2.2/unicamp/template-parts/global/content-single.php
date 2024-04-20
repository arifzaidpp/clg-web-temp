<?php
/**
 * The template for displaying all single posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Unicamp
 * @since   1.0.0
 * @version 1.3.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="page-content" class="page-content">
	<div class="container">
		<div class="row">

			<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

			<div class="page-main-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php unicamp_load_template( 'global/content-rich-snippet' ); ?>

					<?php if ( ! unicamp_has_elementor_template( 'single' ) ) : ?>
						<?php the_content(); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>

			<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

		</div>
	</div>
</div>
