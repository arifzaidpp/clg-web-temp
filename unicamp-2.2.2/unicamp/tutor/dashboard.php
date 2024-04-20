<?php
/**
 * Template for displaying student Public Profile
 *
 * @author        Themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.0.3
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;

$dashboard_page_slug = '';
$dashboard_page_name = '';
if ( isset( $wp_query->query_vars['tutor_dashboard_page'] ) && $wp_query->query_vars['tutor_dashboard_page'] ) {
	$dashboard_page_slug = $wp_query->query_vars['tutor_dashboard_page'];
	$dashboard_page_name = $wp_query->query_vars['tutor_dashboard_page'];
}
/**
 * Getting dashboard sub pages
 */
if ( isset( $wp_query->query_vars['tutor_dashboard_sub_page'] ) && $wp_query->query_vars['tutor_dashboard_sub_page'] ) {
	$dashboard_page_name = $wp_query->query_vars['tutor_dashboard_sub_page'];
	if ( $dashboard_page_slug ) {
		$dashboard_page_name = $dashboard_page_slug . '/' . $dashboard_page_name;
	}
}

$user_id                   = get_current_user_id();
$user                      = get_user_by( 'ID', $user_id );
$enable_profile_completion = tutils()->get_option( 'enable_profile_completion' );
?>
	<div class="page-content">
		<div class="tutor-dashboard-header-wrap">
			<div class="container small-gutter">
				<div class="row">
					<div class="col-md-12">
						<div class="tutor-dashboard-header">
							<div class="dashboard-header-toggle-menu dashboard-header-menu-open-btn">
								<?php echo Unicamp_Helper::get_file_contents( UNICAMP_THEME_SVG_DIR . '/icon-toggle-sidebar.svg' ); ?>
							</div>
							<div class="tutor-header-user-info">
								<div class="tutor-dashboard-header-avatar">
									<?php echo unicamp_get_avatar( $user_id, 150 ); ?>
								</div>
								<div class="tutor-dashboard-header-info">
									<h4 class="tutor-dashboard-header-display-name">
										<span class="welcome-text"><?php esc_html_e( 'Howdy,', 'unicamp' ); ?></span>
										<?php echo esc_html( $user->display_name ); ?>
									</h4>

									<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
										<?php
										$instructor_rating       = tutils()->get_instructor_ratings( $user->ID );
										$instructor_rating_count = sprintf(
											_n( '%s rating', '%s ratings', $instructor_rating->rating_count, 'unicamp' ),
											number_format_i18n( $instructor_rating->rating_count )
										);
										?>
										<div class="tutor-dashboard-header-stats">
											<div class="tutor-dashboard-header-ratings">
												<?php Unicamp_Templates::render_rating( $instructor_rating->rating_avg ) ?>
												<span
													class="rating-average"><?php echo esc_html( $instructor_rating->rating_avg ); ?></span>
												<span class="rating-count">
												<?php echo '(' . esc_html( $instructor_rating_count ) . ')'; ?>
											</span>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<div class="tutor-dashboard-header-button">
								<?php
								if ( current_user_can( tutor()->instructor_role ) ) {
									Unicamp_Templates::render_button( [
										'link'        => [
											'url' => '#',
										],
										'text'        => esc_html__( 'Add A New Course ', 'unicamp' ),
										'icon'        => 'edumi edumi-content-writing',
										'style'       => 'thick-border',
										'extra_class' => 'button-grey',
										'id'          => 'tutor-create-new-course',
									] );
								} else {
									if ( tutils()->get_option( 'enable_become_instructor_btn' ) ) {
										Unicamp_Templates::render_button( [
											'link'        => [
												'url' => esc_url( tutils()->instructor_register_url() ),
											],
											'text'        => esc_html__( 'Become an instructor', 'unicamp' ),
											'icon'        => 'edumi edumi-user',
											'style'       => 'thick-border',
											'extra_class' => 'button-grey open-popup-instructor-register',
										] );
									}
								}
								?>
							</div>

							<?php Unicamp_Header::instance()->print_open_canvas_menu_button(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container small-gutter">
			<div class="row">
				<div class="page-main-content">
					<?php do_action( 'tutor_dashboard/notification_area' ); ?>

					<div class="tutor-dashboard-content">
						<?php
						if ( $dashboard_page_name ) {
							do_action( 'tutor_load_dashboard_template_before', $dashboard_page_name );

							/**
							 * Load dashboard template part from other location
							 *
							 * this filter is basically added for adding templates from respective addons
							 *
							 * @since version 1.9.3
							 */
							$other_location      = '';
							$from_other_location = apply_filters( 'load_dashboard_template_part_from_other_location', $other_location );

							if ( '' === $from_other_location ) {
								tutor_load_template( "dashboard." . $dashboard_page_name );
							} else {
								//load template from other location full abspath.
								include_once $from_other_location;
							}

							do_action( 'tutor_load_dashboard_template_before', $dashboard_page_name );
						} else {
							tutor_load_template( "dashboard.dashboard" );
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
