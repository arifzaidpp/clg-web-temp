<?php
/**
 * Template for displaying left navigation for public profile page.
 *
 * @author  ThemeMove
 * @package Unicamp
 * @since   1.3.1
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

$user_name = sanitize_text_field( get_query_var( Unicamp_Tutor::PROFILE_QUERY_VAR ) );
$sub_page  = sanitize_text_field( get_query_var( 'profile_sub_page' ) );
$get_user  = tutor_utils()->get_user_by_login( $user_name );

if ( empty( $get_user ) ) {
	return;
}

$user_id = $get_user->ID;

global $wp_query;

$profile_sub_page = '';
if ( ! empty( $wp_query->query_vars['profile_sub_page'] ) ) {
	$profile_sub_page = $wp_query->query_vars['profile_sub_page'];
}
?>
<div id="tutor-dashboard-left-menu" class="tutor-dashboard-left-menu">
	<div id="dashboard-nav-wrapper" class="dashboard-nav-wrapper">
		<div class="dashboard-nav-header">
			<div class="dashboard-header-toggle-menu dashboard-header-close-menu">
				<span class="fal fa-times"></span>
			</div>

			<?php
			$branding_args = [
				'reverse_scheme' => true,
			];
			?>
			<?php unicamp_load_template( 'branding', null, $branding_args ); ?>
		</div>
		<div class="dashboard-nav-content">
			<div class="dashboard-nav-content-inner">
				<?php
				$permalinks          = Unicamp_Tutor::instance()->user_profile_permalinks();
				$student_profile_url = Unicamp_Tutor::instance()->profile_url( $user_id );

				?>
				<ul class="tutor-dashboard-permalinks">
					<li class="tutor-dashboard-menu-bio <?php echo '' === $profile_sub_page ? 'active' : ''; // WPCS: XSS OK. ?>">
						<a href="<?php echo esc_url( $student_profile_url ); ?>"><?php esc_html_e( 'My Profile', 'unicamp' ); ?></a>
					</li>
					<?php
					if ( is_array( $permalinks ) && count( $permalinks ) ) {
						foreach ( $permalinks as $permalink_key => $permalink ) {
							$li_class     = "tutor-dashboard-menu-{$permalink_key}";
							$active_class = $profile_sub_page == $permalink_key ? "active" : "";

							$link         = add_query_arg( 'profile_sub_page', $permalink_key, $student_profile_url );

							if ( 'courses_taken' === $permalink_key && tutor_utils()->is_instructor( $user_id ) ) {
								continue;
							}

							echo '<li class="' . $active_class . ' ' . $li_class . '"><a href="' . esc_url( $link ) . '"> ' . $permalink . ' </a> </li>';
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
