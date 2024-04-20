<?php
/**
 * BuddyPress Members Directory
 *
 * @since   3.0.0
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php bp_nouveau_before_members_directory_content(); ?>

<div class="nav-and-filters-wrap">
	<div class="filters-wrap">
		<h5 class="title"><?php esc_html_e( 'Members', 'unicamp' ); ?></h5>
		<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>
	</div>
	<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>
		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>
	<?php endif; ?>
</div>

<div class="screen-content">
	<div id="members-dir-list" class="members dir-list" data-bp-list="members">
		<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-members-loading' ); ?></div>
	</div><!-- #members-dir-list -->

	<?php bp_nouveau_after_members_directory_content(); ?>
</div><!-- // .screen-content -->

<?php bp_nouveau_after_directory_page(); ?>
