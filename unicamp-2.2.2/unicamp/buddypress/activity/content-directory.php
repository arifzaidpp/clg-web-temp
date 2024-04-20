<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="page-content" class="page-content">
	<div id="buddypress" class="container buddypress-wrap bp-dir-hori-nav">

		<div class="row single-headers-row">
			<div class="col-md-12">
				<div class="single-header-activity"
				     style="background-image: url( <?php echo esc_url( UNICAMP_BP_ASSETS_URI . '/images/activity-hero-bg.jpg' ); ?> );">
					<div class="single-header-activity-content">
						<h3 class="entry-activity-username"><?php echo esc_html( sprintf( __( 'Hi %s', 'unicamp' ), bp_get_loggedin_user_fullname() ) ); ?></h3>
						<h2 class="entry-activity-welcome"><?php esc_html_e( 'Welcome to your community', 'unicamp' ); ?></h2>
						<?php bp_nouveau_search_form(); ?>
					</div>
				</div>
			</div>
		</div>

		<?php if ( is_active_sidebar( 'activity_top_sidebar' ) ) : ?>
			<?php dynamic_sidebar( 'activity_top_sidebar' ); ?>
		<?php endif; ?>

		<div class="row tm-sticky-parent">
			<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

			<div id="page-main-content" class="page-main-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div>

			<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>
		</div>

	</div>
</div>
