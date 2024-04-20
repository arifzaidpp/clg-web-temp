<?php
/**
 * Course loop category
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Unicamp/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$category = Unicamp_Tutor::instance()->get_the_category();
?>
<?php if ( $category ): ?>
	<?php
	$link = get_term_link( $category );
	?>
	<div class="course-loop-category">
		<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $category->name ); ?></a>
	</div>
<?php endif; ?>
