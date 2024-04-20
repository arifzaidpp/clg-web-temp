<?php
/**
 * Template for displaying categories.
 *
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$categories = \Unicamp\Zoom_Meeting\Utils::instance()->get_the_categories();

if ( empty( $categories ) ) {
	return;
}
?>
<div class="entry-categories">
	<?php foreach ( $categories as $category ) : ?>
		<?php
		$link = get_term_link( $category );
		printf( '<a href="%1$s" rel="category tag">%2$s</a>', $link, $category->name );
		?>
	<?php endforeach; ?>
</div>
