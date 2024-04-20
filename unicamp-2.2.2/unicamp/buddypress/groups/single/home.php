<?php
/**
 * BuddyPress - Groups Home
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( bp_has_groups() ) :
	while ( bp_groups() ) : bp_the_group(); ?>
		<div id="item-body" class="item-body tm-sticky-column">
			<?php bp_nouveau_group_hook( 'before', 'home_content' ); ?>

			<?php bp_nouveau_group_template_part(); ?>

			<?php bp_nouveau_group_hook( 'after', 'home_content' ); ?>
		</div>
	<?php endwhile; ?>
<?php
endif;
