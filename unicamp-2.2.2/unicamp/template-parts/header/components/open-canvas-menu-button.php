<?php
/**
 * Button open canvas menu on header
 *
 * @package Unicamp
 * @since   1.3.1
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$classes = "header-icon page-open-main-menu style-{$args['style']}";

if ( ! empty( $args['extra_class'] ) ) {
	$classes .= " {$args['extra_class']}";
}

$title = Unicamp::setting( 'navigation_minimal_01_menu_title' );
?>
<div id="page-open-main-menu" class="<?php echo esc_attr( $classes ); ?>">
	<span class="far fa-bars"></span>

	<?php if ( ! empty( $title ) ) : ?>
		<div class="burger-title"><?php echo esc_html( $title ); ?></div>
	<?php endif; ?>
</div>
