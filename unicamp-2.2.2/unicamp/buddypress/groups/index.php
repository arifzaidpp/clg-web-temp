<?php
/**
 * BP Nouveau - Groups Directory
 *
 * @since   3.0.0
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<?php bp_nouveau_before_groups_directory_content(); ?>

<?php bp_nouveau_template_notices(); ?>

<div class="nav-and-filters-wrap">
	<div class="filters-wrap">
		<h5 class="title"><?php esc_html_e( 'Groups', 'unicamp' ); ?></h5>
		<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>
	</div>
	<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>
		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>
	<?php endif; ?>
</div>

<div class="screen-content">
	<div id="groups-dir-list" class="groups dir-list" data-bp-list="groups">
		<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-groups-loading' ); ?></div>
	</div><!-- #groups-dir-list -->

	<?php bp_nouveau_after_groups_directory_content(); ?>
</div><!-- // .screen-content -->

<?php bp_nouveau_after_directory_page(); ?>
