<?php
/**
 * The template for displaying archive pages.
 *
 * @link     https://codex.wordpress.org/Template_Hierarchy
 *
 * @package  Unicamp
 * @since    1.0.0
 * @version  1.3.0
 */
get_header();
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

				<div class="page-main-content">
					<?php
					if ( ! unicamp_has_elementor_template( 'archive' ) ) {
						$post_type = get_post_type();
						if ( 'post' === $post_type ) {
							unicamp_load_template( 'blog/archive-blog' );
						} else {
							unicamp_load_template( 'content' );
						}
					}
					?>
				</div>

				<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php get_footer();
