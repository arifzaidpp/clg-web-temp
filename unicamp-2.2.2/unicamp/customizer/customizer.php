<?php
/**
 * Theme Customizer
 *
 * @package Unicamp
 * @since   1.0
 */

/**
 * Setup configuration
 */
Unicamp_Kirki::add_config( 'theme', array(
	'option_type' => 'theme_mod',
	'capability'  => 'edit_theme_options',
) );

/**
 * Add sections
 */

Unicamp_Kirki::add_section( 'layout', array(
	'title'    => esc_html__( 'Site Layout & Background', 'unicamp' ),
	'priority' => 10,
) );

Unicamp_Kirki::add_section( 'color_', array(
	'title'    => esc_html__( 'Colors', 'unicamp' ),
	'priority' => 20,
) );

Unicamp_Kirki::add_section( 'typography', array(
	'title'    => esc_html__( 'Typography', 'unicamp' ),
	'priority' => 20,
) );

Unicamp_Kirki::add_panel( 'top_bar', array(
	'title'    => esc_html__( 'Top bar', 'unicamp' ),
	'priority' => 30,
) );

Unicamp_Kirki::add_panel( 'header', array(
	'title'    => esc_html__( 'Header', 'unicamp' ),
	'priority' => 40,
) );

Unicamp_Kirki::add_section( 'logo', array(
	'title'       => esc_html__( 'Logo', 'unicamp' ),
	'description' => '<div class="desc">
			<strong class="insight-label insight-label-info">' . esc_html__( 'IMPORTANT NOTE: ', 'unicamp' ) . '</strong>
			<p>' . esc_html__( 'These settings can be overridden by settings from Page Options Box in separator page.', 'unicamp' ) . '</p>
			<p><img src="' . esc_url( UNICAMP_THEME_IMAGE_URI . '/customize/logo-settings.jpg' ) . '" alt="' . esc_attr__( 'logo-settings', 'unicamp' ) . '"/></p>
		</div>',
	'priority'    => 50,
) );

Unicamp_Kirki::add_panel( 'navigation', array(
	'title'    => esc_html__( 'Navigation', 'unicamp' ),
	'priority' => 60,
) );

Unicamp_Kirki::add_panel( 'title_bar', array(
	'title'    => esc_html__( 'Page Title Bar & Breadcrumb', 'unicamp' ),
	'priority' => 60,
) );

Unicamp_Kirki::add_section( 'sidebars', array(
	'title'    => esc_html__( 'Sidebars', 'unicamp' ),
	'priority' => 70,
) );

Unicamp_Kirki::add_section( 'footer', array(
	'title'    => esc_html__( 'Footer', 'unicamp' ),
	'priority' => 80,
) );

Unicamp_Kirki::add_section( 'pages', array(
	'title'    => esc_html__( 'Pages', 'unicamp' ),
	'priority' => 90,
) );

Unicamp_Kirki::add_panel( 'blog', array(
	'title'    => esc_html__( 'Blog', 'unicamp' ),
	'priority' => 100,
) );

Unicamp_Kirki::add_panel( 'course', array(
	'title'    => esc_html__( 'Course', 'unicamp' ),
	'priority' => 110,
) );

Unicamp_Kirki::add_panel( 'event', array(
	'title'    => esc_html__( 'Event', 'unicamp' ),
	'priority' => 120,
) );

Unicamp_Kirki::add_panel( 'faq', array(
	'title'    => esc_html__( 'FAQ', 'unicamp' ),
	'priority' => 130,
) );

Unicamp_Kirki::add_panel( 'shop', array(
	'title'    => esc_html__( 'Shop', 'unicamp' ),
	'priority' => 160,
) );

Unicamp_Kirki::add_section( 'contact_info', array(
	'title'    => esc_html__( 'Contact Info', 'unicamp' ),
	'priority' => 170,
) );

Unicamp_Kirki::add_section( 'socials', array(
	'title'    => esc_html__( 'Social Networks', 'unicamp' ),
	'priority' => 180,
) );

Unicamp_Kirki::add_section( 'social_sharing', array(
	'title'    => esc_html__( 'Social Sharing', 'unicamp' ),
	'priority' => 190,
) );

Unicamp_Kirki::add_panel( 'search', array(
	'title'    => esc_html__( 'Search & Popup Search', 'unicamp' ),
	'priority' => 200,
) );

Unicamp_Kirki::add_section( 'error404_page', array(
	'title'    => esc_html__( 'Error 404 Page', 'unicamp' ),
	'priority' => 210,
) );

Unicamp_Kirki::add_panel( 'shortcode', array(
	'title'    => esc_html__( 'Shortcodes', 'unicamp' ),
	'priority' => 220,
) );

Unicamp_Kirki::add_section( 'pre_loader', array(
	'title'    => esc_html__( 'Pre Loader', 'unicamp' ),
	'priority' => 230,
) );

Unicamp_Kirki::add_panel( 'advanced', array(
	'title'    => esc_html__( 'Advanced', 'unicamp' ),
	'priority' => 240,
) );

Unicamp_Kirki::add_section( 'login', array(
	'title'    => esc_html__( 'Login', 'unicamp' ),
	'priority' => 250,
) );

Unicamp_Kirki::add_section( 'notices', array(
	'title'    => esc_html__( 'Notices', 'unicamp' ),
	'priority' => 250,
) );

Unicamp_Kirki::add_section( 'settings_preset', array(
	'title'    => esc_html__( 'Preset', 'unicamp' ),
	'priority' => 260,
) );

Unicamp_Kirki::add_section( 'performance', array(
	'title'    => esc_html__( 'Performance', 'unicamp' ),
	'priority' => 270,
) );

Unicamp_Kirki::add_section( 'custom_js', array(
	'title'    => esc_html__( 'Additional JS', 'unicamp' ),
	'priority' => 280,
) );

/**
 * Load panel & section files
 */
require_once UNICAMP_CUSTOMIZER_DIR . '/section-color.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/top-bar/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/top-bar/general.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/top-bar/style-01.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/top-bar/style-02.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/top-bar/style-03.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/top-bar/style-04.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/header/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/general.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/sticky.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/more-options.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-01.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-02.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-03.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-04.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-05.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-06.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-07.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/header/style-08.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/navigation/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/navigation/desktop-menu.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/navigation/off-canvas-menu.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/navigation/mobile-menu.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/general.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-01.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-02.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-03.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-04.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-05.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-07.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/title-bar/style-08.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-footer.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/advanced/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/advanced/advanced.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/advanced/light-gallery.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-notices.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-login.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-pre-loader.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-custom-js.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-error404.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-layout.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-logo.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-pages.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/blog/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/blog/archive.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/blog/single.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/course/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/course/archive.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/course/category.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/course/course-category-listing.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/course/single.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/event/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/event/archive.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/event/single.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/faq/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/faq/archive.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/faq/single.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/shop/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/shop/general.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/shop/archive.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/shop/single.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/shop/cart.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/search/_panel.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/search/search-page.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/search/search-popup.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-preset.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-sidebars.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-contact-info.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-sharing.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-socials.php';
require_once UNICAMP_CUSTOMIZER_DIR . '/section-typography.php';

require_once UNICAMP_CUSTOMIZER_DIR . '/section-performance.php';

do_action( 'unicamp_customizer_init' );
