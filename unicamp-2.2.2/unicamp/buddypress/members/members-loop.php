<?php
/**
 * BuddyPress - Members Loop
 *
 * @since   3.0.0
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;

bp_nouveau_before_loop();
?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<?php bp_nouveau_pagination( 'top' ); ?>

	<div id="members-list" class="<?php bp_nouveau_loop_classes(); ?>">

		<?php while ( bp_members() ) : bp_the_member(); ?>

			<div <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_member_user_id(); ?>"
			                                                        data-bp-item-component="members">
				<div class="item-avatar">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( bp_nouveau_avatar_args() ); ?></a>
				</div>

				<div class="item">
					<div class="item-block">

						<div class="item-title member-name">
							<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
						</div>

						<?php if ( bp_nouveau_member_has_meta() ) : ?>
							<p class="item-meta last-activity">
								<?php bp_nouveau_member_meta(); ?>
							</p><!-- .item-meta -->
						<?php endif; ?>

						<?php if ( bp_nouveau_member_has_extra_content() ) : ?>
							<div class="item-extra-content">
								<?php bp_nouveau_member_extra_content(); ?>
							</div><!-- .item-extra-content -->
						<?php endif; ?>

						<?php
						bp_nouveau_members_loop_buttons(
							array(
								'container'      => 'ul',
								'button_element' => 'button',
							)
						);
						?>
					</div>
				</div><!-- // .item -->
			</div>

		<?php endwhile; ?>

	</div>

<?php
else :

	bp_nouveau_user_feedback( 'members-loop-none' );

endif;
?>

<?php bp_nouveau_after_loop(); ?>
