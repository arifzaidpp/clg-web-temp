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

		<?php if ( bp_has_groups() ) : ?>
			<?php while ( bp_groups() ) : bp_the_group(); ?>
				<div class="row single-headers-row">
					<div class="col-md-12">
						<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>"
						     data-bp-item-component="groups" class="groups-header single-headers">
							<?php unicamp_bp_nouveau_group_header_template_part(); ?>

							<div class="single-headers-nav">
								<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>

								<?php
								/**
								 * Disable search & filter on send-invites screen
								 * because it's not working.
								 */
								?>
								<?php if ( ! bp_is_group_invites() && ! bp_is_group_admin_page() ) : ?>
									<div class="subnav-filters filters clearfix">
										<?php bp_nouveau_search_form(); ?>
										<?php bp_get_template_part( 'common/filters/groups-screens-filters' ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<?php
						/**
						 * Move out from
						 *
						 * @see bp_nouveau_group_header_template_part()
						 */
						bp_nouveau_template_notices();
						?>
					</div>
				</div>
			<?php endwhile; ?>
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
