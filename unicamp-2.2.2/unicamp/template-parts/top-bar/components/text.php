<?php
/**
 * Text on top bar
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$text = $args['text'];
?>
<div class="top-bar-text">
	<?php echo wp_kses( $text, 'unicamp-default' ); ?>
</div>
