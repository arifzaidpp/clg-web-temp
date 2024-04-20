<?php
/**
 * Template part for displaying event share on single page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/single/share.php
 *
 * @author        ThemeMove
 * @package       Unicamp/WP-Events-Manager/Template
 * @version       1.0.0
 */

defined( 'ABSPATH' ) || exit;

$social_sharing = Unicamp::setting( 'social_sharing_item_enable' );

if ( empty( $social_sharing ) ) {
	return;
}
?>
<div class="entry-event-share">
	<div class="share-list">
		<?php Unicamp_Templates::get_sharing_list(); ?>
	</div>
</div>
