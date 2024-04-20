<?php
/**
 * BuddyPress - Groups Loop
 *
 * @since   3.0.0
 * @version 7.0.0
 */

defined( 'ABSPATH' ) || exit;

bp_nouveau_before_loop(); ?>

<?php if ( bp_get_current_group_directory_type() ) : ?>
	<p class="current-group-type"><?php bp_current_group_directory_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<?php bp_nouveau_pagination( 'top' ); ?>

	<div id="groups-list" class="<?php bp_nouveau_loop_classes(); ?>">

		<?php while ( bp_groups() ) : bp_the_group(); ?>

			<div <?php bp_group_class( array( 'item-entry rest' ) ); ?> data-bp-item-id="<?php bp_group_id(); ?>"
			                                                            data-bp-item-component="groups">
				<div class="list-wrap">

					<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
						<div class="item-avatar"
						     style="background-image: url(<?php echo bp_get_group_cover_url(); ?>);">
							<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a>
						</div>
					<?php endif; ?>

					<div class="item">

						<div class="item-block">
							<h2 class="list-title groups-title"><?php bp_group_link(); ?></h2>

							<?php if ( bp_nouveau_group_has_meta() ) : ?>
								<p class="item-meta group-details">
									<?php if ( version_compare( bp_get_version(), '7.0.0', '<' ) ) : ?>
										<?php bp_nouveau_group_meta(); ?>
									<?php else: ?>
										<?php bp_nouveau_the_group_meta( array(
											'keys' => array(
												'status',
												'count',
											),
										) ); ?>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</div>


						<?php bp_nouveau_groups_loop_item(); ?>

						<?php bp_nouveau_groups_loop_buttons(); ?>

					</div>


				</div>
			</div>

		<?php endwhile; ?>

	</div>
<?php else : ?>
	<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>
<?php endif; ?>

<?php
bp_nouveau_after_loop();
