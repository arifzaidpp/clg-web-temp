<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Unicamp
 * @since   1.0
 */
?>
<?php if ( ! is_active_sidebar( 'blog_sidebar' ) ) : ?>
	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'blog_sidebar' ); ?>
	</aside>
<?php endif; ?>
