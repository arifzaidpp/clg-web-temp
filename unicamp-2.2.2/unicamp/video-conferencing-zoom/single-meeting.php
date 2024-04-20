<?php
/**
 * The Template for displaying all single meetings
 *
 * This template can be overridden by copying it to unicamp-child/video-conferencing-zoom/single-meetings.php.
 *
 * @package     Video Conferencing with Zoom API/Templates
 * @version     3.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Unicamp_Sidebar::instance()->render( 'left' ); ?>

				<div id="page-main-content" class="page-main-content">

					<?php while ( have_posts() ) : the_post(); ?>
						<?php
						if ( video_conference_zoom_check_login() ) {
							unicamp_load_template( 'global/content-rich-snippet' );

							vczapi_get_template_part( 'content', 'single-meeting' );
						} else {
							echo '<h3>' . esc_html__( 'You do not have enough privilege to access this page. Please login to continue or contact
						administrator.', 'unicamp' ) . '</h3>';
						}
						?>
					<?php endwhile; ?>

				</div>

				<?php Unicamp_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
