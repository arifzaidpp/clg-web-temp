<?php
/**
 * The template for displaying Tutor Course Widget
 *
 * @package       Tutor/Tempaltes
 * @version       1.3.1
 *
 * @theme-since   1.1.0
 * @theme-version 1.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( have_posts() ) :
	global $unicamp_course;
	$unicamp_course_clone = $unicamp_course;
	while ( have_posts() ) : the_post();
		/**
		 * Setup course object.
		 */
		$unicamp_course = new Unicamp_Course();
		?>
		<div class="<?php echo tutor_widget_course_loop_classes(); ?>">
			<?php tutor_load_template( 'loop.course' ); ?>
		</div>
	<?php
	endwhile;
	/**
	 * Reset course object.
	 */
	$unicamp_course = $unicamp_course_clone;
endif;
