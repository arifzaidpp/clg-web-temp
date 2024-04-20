<?php
/**
 * BuddyPress - Groups Admin
 *
 * @since   3.0.0
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

bp_get_template_part( 'groups/single/parts/admin-subnav' ); ?>

<div class="unicamp-bp-box unicamp-bp-form-box">
	<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form"
	      class="standard-form" method="post" enctype="multipart/form-data">

		<?php bp_nouveau_group_manage_screen(); ?>

	</form><!-- #group-settings-form -->
</div>
