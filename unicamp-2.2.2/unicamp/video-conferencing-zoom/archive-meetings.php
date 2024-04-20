<?php
/**
 * The template for displaying archive of meetings
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/archive-meetings.php.
 *
 * @author Deepen
 * @since  3.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

				<div id="page-main-content" class="page-main-content">

					<?php if ( have_posts() ) : ?>

						<?php
						$wrapper_classes = [
							'unicamp-main-post',
							'unicamp-grid-wrapper',
							'unicamp-zoom-meetings',
							'unicamp-animation-zoom-in',
						];

						$lg_columns = Unicamp::setting( 'zoom_meeting_archive_lg_columns', 3 );
						$md_columns = Unicamp::setting( 'zoom_meeting_archive_md_columns' );
						$sm_columns = Unicamp::setting( 'zoom_meeting_archive_sm_columns' );

						$grid_options = [
							'type'          => 'grid',
							'columns'       => $lg_columns,
							'columnsTablet' => $md_columns,
							'columnsMobile' => $sm_columns,
							'gutter'        => 30,
						];
						?>
						<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
						     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
						>
							<div class="unicamp-grid">
								<div class="grid-sizer"></div>

								<?php while ( have_posts() ) : the_post(); ?>
									<?php vczapi_get_template_part( 'content', 'meeting' );; ?>
								<?php endwhile; // end of the loop. ?>
							</div>

							<div class="unicamp-grid-pagination">
								<?php Unicamp_Templates::paging_nav(); ?>
							</div>
						</div>

					<?php endif; ?>
				</div>

				<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
