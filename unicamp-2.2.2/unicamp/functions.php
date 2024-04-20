<?php
/**
 * Define constant
 */
$theme = wp_get_theme();

if ( ! empty( $theme['Template'] ) ) {
	$theme = wp_get_theme( $theme['Template'] );
}

if ( ! defined( 'UNICAMP_DS' ) ) {
	define( 'UNICAMP_DS', DIRECTORY_SEPARATOR );
}

define( 'UNICAMP_THEME_NAME', $theme['Name'] );
define( 'UNICAMP_THEME_VERSION', $theme['Version'] );
define( 'UNICAMP_THEME_DIR', get_template_directory() );
define( 'UNICAMP_THEME_URI', get_template_directory_uri() );
define( 'UNICAMP_THEME_ASSETS_DIR', get_template_directory() . '/assets' );
define( 'UNICAMP_THEME_ASSETS_URI', get_template_directory_uri() . '/assets' );
define( 'UNICAMP_THEME_IMAGE_URI', UNICAMP_THEME_ASSETS_URI . '/images' );
define( 'UNICAMP_THEME_SVG_DIR', UNICAMP_THEME_ASSETS_DIR . '/svg' );
define( 'UNICAMP_THEME_SVG_URI', UNICAMP_THEME_ASSETS_URI . '/svg' );
define( 'UNICAMP_FRAMEWORK_DIR', get_template_directory() . UNICAMP_DS . 'framework' );
define( 'UNICAMP_CUSTOMIZER_DIR', UNICAMP_THEME_DIR . UNICAMP_DS . 'customizer' );
define( 'UNICAMP_WIDGETS_DIR', get_template_directory() . UNICAMP_DS . 'widgets' );
define( 'UNICAMP_PROTOCOL', is_ssl() ? 'https' : 'http' );
define( 'UNICAMP_IS_RTL', is_rtl() ? true : false );

define( 'UNICAMP_TUTOR_DIR', get_template_directory() . UNICAMP_DS . 'framework' . UNICAMP_DS . 'tutor' );
define( 'UNICAMP_FAQ_DIR', get_template_directory() . UNICAMP_DS . 'framework' . UNICAMP_DS . 'faq' );

define( 'UNICAMP_ELEMENTOR_DIR', get_template_directory() . UNICAMP_DS . 'elementor' );
define( 'UNICAMP_ELEMENTOR_URI', get_template_directory_uri() . '/elementor' );
define( 'UNICAMP_ELEMENTOR_ASSETS', get_template_directory_uri() . '/elementor/assets' );

/**
 * Load Framework.
 */
require_once UNICAMP_FRAMEWORK_DIR . '/class-functions.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-debug.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-aqua-resizer.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-performance.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-static.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-init.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-helper.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-global.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-actions-filters.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-kses.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-notices.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-popup.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-admin.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-compatible.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-customize.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-nav-menu.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-enqueue.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-image.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-minify.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-color.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-datetime.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-import.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-kirki.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-login-register.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-metabox.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-plugins.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-custom-css.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-templates.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-walker-nav-menu.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-sidebar.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-top-bar.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-header.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-title-bar.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-footer.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-post-type-blog.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-image-hotspot.php';

require_once UNICAMP_WIDGETS_DIR . '/class-widget-init.php';

require_once UNICAMP_TUTOR_DIR . '/class-tutor.php';

require_once UNICAMP_FAQ_DIR . '/main.php';

unicamp_require_once( UNICAMP_THEME_DIR . '/wp-events-manager/_classes/main.php' );
unicamp_require_once( UNICAMP_THEME_DIR . '/video-conferencing-zoom/_classes/main.php' );
unicamp_require_once( UNICAMP_THEME_DIR . '/buddypress/_classes/main.php' );

require_once UNICAMP_FRAMEWORK_DIR . '/woocommerce/class-woo.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-content-protected.php';
require_once UNICAMP_FRAMEWORK_DIR . '/tgm-plugin-activation.php';
require_once UNICAMP_FRAMEWORK_DIR . '/tgm-plugin-registration.php';
require_once UNICAMP_FRAMEWORK_DIR . '/class-tha.php';

require_once UNICAMP_ELEMENTOR_DIR . '/class-entry.php';

/**
 * Init the theme
 */
Unicamp_Init::instance()->initialize();
