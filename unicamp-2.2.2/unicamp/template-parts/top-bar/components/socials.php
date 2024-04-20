<?php
/**
 * Social icons on top bar
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="top-bar-social-network">
	<div class="inner">
		<?php Unicamp_Templates::social_icons( array(
			'display'        => 'icon',
			'tooltip_enable' => false,
		) ); ?>
	</div>
</div>
