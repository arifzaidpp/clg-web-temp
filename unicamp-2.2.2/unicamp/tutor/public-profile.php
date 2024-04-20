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
 * @theme-version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$user_name = sanitize_text_field( get_query_var( Unicamp_Tutor::PROFILE_QUERY_VAR ) );
$sub_page  = sanitize_text_field( get_query_var( 'profile_sub_page' ) );
$get_user  = tutor_utils()->get_user_by_login( $user_name );

if ( empty( $get_user ) ) {
	return;
}

$user_id = $get_user->ID;

global $wp_query;

$profile_sub_page = '';
if ( isset( $wp_query->query_vars['profile_sub_page'] ) && $wp_query->query_vars['profile_sub_page'] ) {
	$profile_sub_page = $wp_query->query_vars['profile_sub_page'];
}
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
										<span
											class="welcome-text"><?php esc_html_e( 'Howdy, I\'m', 'unicamp' ); ?></span>
										<?php echo esc_html( $get_user->display_name ); ?>
									</h4>

									<?php if ( user_can( $user_id, tutor()->instructor_role ) ) : ?>
										<?php
										$instructor_rating       = tutils()->get_instructor_ratings( $get_user->ID );
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

							<?php
							$tutor_user_social_icons = tutor_utils()->tutor_user_social_icons();
							?>
							<?php if ( count( $tutor_user_social_icons ) ) : ?>
								<?php
								$social_html = '';

								ob_start();
								foreach ( $tutor_user_social_icons as $key => $social_icon ) {
									$icon_url = get_user_meta( $user_id, $key, true );
									if ( $icon_url ) {
										echo "<a href='" . esc_url( $icon_url ) . "' target='_blank' class='" . $social_icon['icon_classes'] . "'></a>";
									}
								}
								$social_html = ob_get_clean();
								?>
								<?php if ( ! empty( $social_html ) ) : ?>
									<div class="tutor-dashboard-social-icons">
										<h4 class="social-icons-text-help"><?php esc_html_e( 'Follow me', "unicamp" ); ?></h4>
										<?php echo '' . $social_html; ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>

							<?php Unicamp_Header::instance()->print_open_canvas_menu_button(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container small-gutter">
			<div class="row">
				<div class="page-main-content">
					<div class="tutor-dashboard-content">
						<?php
						if ( $sub_page ) {
							tutor_load_template( 'profile.' . $sub_page, compact( 'get_user' ) );
						} else {
							tutor_load_template( 'profile.bio', compact( 'get_user' ) );
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
