<?php
/**
 * The Template for displaying thumbnail in single event page.
 *
 * Override this template by copying it to unicamp-child/wp-events-manager/loop/thumbnail.php
 *
 * @author        ThimPress, leehld
 * @package       WP-Events-Manager/Template
 * @version       2.1.7
 */

defined( 'ABSPATH' ) || exit;

if ( has_post_thumbnail() ): ?>
	<div class="post-thumbnail">
		<?php Unicamp_Image::the_post_thumbnail( [
			'size' => '480x290',
		] ); ?>
	</div>
<?php endif;
