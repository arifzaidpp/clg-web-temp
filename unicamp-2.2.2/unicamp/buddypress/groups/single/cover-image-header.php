<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @since   3.0.0
 * @version 7.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="cover-image-container">
	<div id="header-cover-image"></div>
	<div id="item-header-cover-image">
		<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
			<div id="item-header-avatar">
				<a href="<?php echo esc_url( bp_get_group_permalink() ); ?>"
				   title="<?php echo esc_attr( bp_get_group_name() ); ?>">

					<?php bp_group_avatar(); ?>

				</a>
			</div><!-- #item-header-avatar -->
		<?php endif; ?>

		<div class="item-header-content-wrap">
			<?php if ( ! bp_nouveau_groups_front_page_description() ) : ?>
				<div class="item-header-content">
					<div class="entry-group-info">
						<div class="entry-group-main-content">
							<h4 class="entry-group-name"><?php echo esc_html( bp_get_group_name() ); ?></h4>
							<div class="entry-group-meta">
								<span class="entry-group-status <?php echo esc_attr( bp_get_group_status() ) ?>">
									<?php if ( version_compare( bp_get_version(), '7.0.0', '<' ) ) : ?>
										<?php echo esc_html( bp_nouveau_group_meta()->status ); ?>
									<?php else: ?>
										<?php bp_nouveau_the_group_meta( array( 'keys' => array( 'status' ) ) ); ?>
									<?php endif; ?>
								</span>
								<span class="entry-group-activity activity"
								      data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>">
									<?php
									/* translators: %s: last activity timestamp (e.g. "active 1 hour ago") */
									printf( __( 'active %s', 'unicamp' ), bp_get_group_last_active() );
									?>
								</span>
							</div>
						</div>
						<?php bp_nouveau_group_header_buttons(); ?>
					</div>

					<?php bp_nouveau_group_hook( 'before', 'header_meta' ); ?>

					<?php if ( bp_nouveau_group_has_meta_extra() ) : ?>
						<div class="item-meta">
							<?php if ( version_compare( bp_get_version(), '7.0.0', '<' ) ) : ?>
								<?php echo bp_nouveau_group_meta()->extra; ?>
							<?php else: ?>
								<?php echo bp_nouveau_the_group_meta( array( 'keys' => 'extra' ) ); ?>
							<?php endif; ?>
						</div><!-- .item-meta -->
					<?php endif; ?>

					<?php if ( bp_nouveau_group_has_meta( 'description' ) ) : ?>
						<div class="entry-group-description"><?php bp_group_description(); ?></div>
					<?php endif; ?>

				</div><!-- #item-header-content -->
			<?php endif; ?>
		</div>
		<?php bp_get_template_part( 'groups/single/parts/header-item-actions' ); ?>
	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->
