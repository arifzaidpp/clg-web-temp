<?php
/**
 * Template part for displaying rich snippet. Great for SEO
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicamp
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="rich-snippet display-none">
	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	<span class="published"><?php echo get_the_date(); ?></span>
	<?php $modified = get_the_modified_time( 'Y-m-d G:i' ); ?>
	<span class="updated" data-time="<?php echo esc_attr( $modified ); ?>"><?php echo esc_html( $modified ); ?></span>
</div>
