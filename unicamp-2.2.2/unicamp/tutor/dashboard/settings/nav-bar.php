<?php
/**
 * @package TutorLMS/Templates
 * @version 1.7.5
 */

defined( 'ABSPATH' ) || exit;

$settings_url   = tutor_utils()->get_tutor_dashboard_page_permalink( 'settings' );
$reset_password = tutor_utils()->get_tutor_dashboard_page_permalink( 'settings/reset-password' );
$withdraw       = tutor_utils()->get_tutor_dashboard_page_permalink( 'settings/withdraw-settings' );

$setting_menus = array(
	'profile'        => array(
		'url'   => $settings_url,
		'title' => __( 'Profile', 'unicamp' ),
		'role'  => false,
	),
	'reset_password' => array(
		'url'   => $reset_password,
		'title' => __( 'Reset Password', 'unicamp' ),
		'role'  => false,
	),
	'withdrawal'     => array(
		'url'   => $withdraw,
		'title' => __( 'Withdraw', 'unicamp' ),
		'role'  => 'instructor',
	),
);

$setting_menus = apply_filters( 'tutor_dashboard/nav_items/settings/nav_items', $setting_menus );

$GLOBALS['tutor_setting_nav'] = $setting_menus;
?>
<ul>
	<?php
	foreach ( $setting_menus as $menu_key => $menu ) {
		$valid = $menu_key == 'profile' || ! $menu['role'] || ( $menu['role'] == 'instructor' && current_user_can( tutor()->instructor_role ) );

		if ( $valid ) {
			$li_classes = '';
			if ( $active_setting_nav == $menu_key ) {
				$li_classes .= ' active';
			}
			?>
			<li class="<?php echo esc_attr( $active_setting_nav ); ?>">
				<a href="<?php echo esc_url( $menu['url'] ); ?>"> <?php echo esc_html( $menu['title'] ); ?></a>
			</li>
			<?php
		}
	}
	?>
</ul>
