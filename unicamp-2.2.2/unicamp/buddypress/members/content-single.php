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
				<div id="item-header" role="complementary"
				     data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>"
				     data-bp-item-component="members" class="users-header single-headers">
					<?php unicamp_bp_nouveau_member_header_template_part(); ?>

					<div class="single-headers-nav">
						<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>
					</div>
				</div>

				<?php
				/**
				 * Move out from
				 *
				 * @see bp_nouveau_member_header_template_part()
				 */
				bp_nouveau_template_notices();
				?>
			</div>
		</div>

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
