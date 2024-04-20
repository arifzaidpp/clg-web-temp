<?php
/**
 * Template for displaying courses
 *
 * @since   v.1.0.0
 *
 * @author  Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.5.8
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<div class="page-content">

		<?php if ( have_posts() ) : ?>
			<?php
			/**
			 * @hook unicamp_before_main_content
			 */
			do_action( 'unicamp_before_main_content' );
			?>

			<div class="container">

				<?php tutor_load_template( 'category.page-title' ); ?>

				<div class="row">

					<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

					<div class="page-main-content">
						<?php tutor_load_template( 'archive-course-init' ); ?>
					</div>

					<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

				</div>
			</div>
		<?php else : ?>
		<div class="container">
			<div class="row">
				<div class="page-main-content">
					<?php
					/**
					 * No course found
					 */
					tutor_load_template( 'course-none' );
					?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer();
