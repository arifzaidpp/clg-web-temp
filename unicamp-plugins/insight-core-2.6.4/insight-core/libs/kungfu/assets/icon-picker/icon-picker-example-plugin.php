<?php
/**
 * Plugin Name: Icon Picker Example Plugin
 * Description: Select icons from different icon sets
 * Author: K.M. Hansen
 * Adapted from Dashicons Picker by Brad Vincent @ http://themergency.com
 * Author URI: http://www.kmhcreative.com/labs/
 * Version: 1.0
 */

function icon_picker_register_settings() {
	register_setting( 'icon_picker_settings_group', 'icon_picker_settings' );
}

add_action( 'admin_init', 'icon_picker_register_settings' );

function icon_picker_settings_menu() {
	add_options_page( __( 'Icon Picker Example' ), __( 'Icon Picker Example' ), 'manage_options', 'icon_picker_settings', 'icon_picker_settings_page' );
}

add_action( 'admin_menu', 'icon_picker_settings_menu' );

function icon_picker_scripts() {
	$css = plugin_dir_url( __FILE__ ) . '/css/icon-picker.css';
	wp_enqueue_style( 'dashicons-picker', $css, array( 'dashicons' ), '1.0' );

//	$font1 = plugin_dir_url( __FILE__ ) . 'fonts/genericons/genericons.css';
//	wp_enqueue_style( 'genericons', $font1, '', '');

//    $font2 = plugin_dir_url( __FILE__ ) . 'fonts/font-awesome/css/font-awesome.css';
//    wp_enqueue_style( 'font-awesome', $font2,'','');

	$js = plugin_dir_url( __FILE__ ) . '/js/icon-picker.js';
	wp_enqueue_script( 'dashicons-picker', $js, array( 'jquery' ), '1.0' );
}

// Make sure we only enqueue on our options page //
global $pagenow;
if ( $pagenow == "options-general.php" && isset( $_GET['page'] ) && $_GET['page'] == 'icon_picker_settings' ) {
	add_action( 'admin_enqueue_scripts', 'icon_picker_scripts' );
}

function icon_picker_settings_page() {
	$options = get_option( 'icon_picker_settings' ); ?>

	<div class="wrap">
		<h2><?php _e( 'Icon Picker Example Settings' ); ?></h2>
		<form method="post" action="options.php" class="options_form">
			<?php settings_fields( 'icon_picker_settings_group' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="icon_picker_example_icon1"><?php _e( 'Pick Icon' ); ?></label>
					</th>
					<td>
						<input class="regular-text" type="hidden" id="icon_picker_example_icon1"
						       name="icon_picker_settings[icon1]" value="<?php if ( isset( $options['icon1'] ) ) {
							echo esc_attr( $options['icon1'] );
						} ?>"/>
						<div id="preview_icon_picker_example_icon1" data-target="#icon_picker_example_icon1"
						     class="button icon-picker <?php if ( isset( $options['icon1'] ) ) {
							     $v = explode( '|', $options['icon1'] );
							     echo $v[0] . ' ' . $v[1];
						     } ?>"></div>
						&lt;&lt; this button will show your selection, go on press it! You know you want to.
					</td>
				</tr>
				<tr>
					<th scope="row"></th>
					<td>
						<p>
							If all of the picker buttons are blank you're probably using WordPress <3.8. If "Font
							Awesome" and/or "Genericons" are blank
							you need to download and install those fonts to the "icon-picker/fonts" folder and properly
							enqueue them.
						</p>
						<p>
							This is just a sample plugin to show you how the Icon Picker works. The intended use is to
							drop the "icon-picker" folder inside
							your <strong>own</strong> plugin or theme so the picker can be used in your own
							options/settings pages.
						</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

