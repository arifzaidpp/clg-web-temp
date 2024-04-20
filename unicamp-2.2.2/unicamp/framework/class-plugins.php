<?php
defined( 'ABSPATH' ) || exit;

/**
 * Plugin installation and activation for WordPress themes
 */
if ( ! class_exists( 'Unicamp_Register_Plugins' ) ) {
	class Unicamp_Register_Plugins {

		protected static $instance = null;

		const GOOGLE_DRIVER_API = 'AIzaSyBQsxIg32Eg17Ic0tmRvv1tBZYrT9exCwk';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_filter( 'insight_core_tgm_plugins', array( $this, 'register_required_plugins' ) );
		}

		public function register_required_plugins( $plugins ) {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$new_plugins = array(
				array(
					'name'     => esc_html__( 'Insight Core', 'unicamp' ),
					'slug'     => 'insight-core',
					'source'   => 'https://www.dropbox.com/s/u4o8qjg3pzk26xz/insight-core-2.6.4.zip?dl=1',
					'version'  => '2.6.4',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Unicamp Addons', 'unicamp' ),
					'slug'     => 'unicamp-addons',
					'source'   => $this->get_plugin_google_driver_url( '1dE4m4rG9JSN5nMWAdo79R_F73ZtQ30ug' ),
					'version'  => '1.1.0',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Elementor', 'unicamp' ),
					'slug'     => 'elementor',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Elementor Pro', 'unicamp' ),
					'slug'     => 'elementor-pro',
					'source'   => 'https://www.dropbox.com/s/vnb9c4tx8qs3kva/elementor-pro-3.12.3.zip?dl=1',
					'version'  => '3.12.3',
					'required' => true,
				),
				array(
					'name'    => esc_html__( 'Revolution Slider', 'unicamp' ),
					'slug'    => 'revslider',
					'source'  => 'https://www.dropbox.com/s/citq4q974z0069m/revslider-6.6.14.zip?dl=1',
					'version' => '6.6.14',
				),
				array(
					'name' => esc_html__( 'WP Events Manager', 'unicamp' ),
					'slug' => 'wp-events-manager',
				),
				array(
					'name' => esc_html__( 'WordPress Social Login', 'unicamp' ),
					'slug' => 'miniorange-login-openid',
				),
				array(
					'name' => esc_html__( 'Contact Form 7', 'unicamp' ),
					'slug' => 'contact-form-7',
				),
				array(
					'name' => esc_html__( 'MailChimp for WordPress', 'unicamp' ),
					'slug' => 'mailchimp-for-wp',
				),
				array(
					'name' => esc_html__( 'WooCommerce', 'unicamp' ),
					'slug' => 'woocommerce',
				),
				array(
					'name' => esc_html__( 'WPC Smart Compare for WooCommerce', 'unicamp' ),
					'slug' => 'woo-smart-compare',
				),
				array(
					'name' => esc_html__( 'WPC Smart Wishlist for WooCommerce', 'unicamp' ),
					'slug' => 'woo-smart-wishlist',
				),
				array(
					'name'    => esc_html__( 'Insight Swatches', 'unicamp' ),
					'slug'    => 'insight-swatches',
					'source'  => 'https://www.dropbox.com/s/rgobp5b4y4dm6v7/insight-swatches-1.6.0.zip?dl=1',
					'version' => '1.6.0',
				),
				array(
					'name' => esc_html__( 'WP-PostViews', 'unicamp' ),
					'slug' => 'wp-postviews',
				),
				array(
					'name' => esc_html__( 'Widget CSS Classes', 'unicamp' ),
					'slug' => 'widget-css-classes',
				),
				array(
					'name' => esc_html__( 'Radio Buttons for Taxonomies', 'unicamp' ),
					'slug' => 'radio-buttons-for-taxonomies',
				),
				array(
					'name' => esc_html__( 'Image Hotspot by DevVN', 'unicamp' ),
					'slug' => 'devvn-image-hotspot',
				),
				array(
					'name'    => esc_html__( 'Instagram Feed', 'unicamp' ),
					'slug'    => 'elfsight-instagram-feed-cc',
					'source'  => 'https://www.dropbox.com/s/o55sjvh8fs2nmoq/elfsight-instagram-feed-cc-4.0.3.zip?dl=1',
					'version' => '4.0.3',
				),
				array(
					'name'    => esc_html__( 'Tutor LMS Pro', 'unicamp' ),
					'slug'    => 'tutor-pro',
					'source'  => 'https://www.dropbox.com/scl/fi/w29twc5oetdb4npfyobea/tutor-pro-2.2.2.zip?rlkey=cr884vt7pdpox02n5zth3jn7w&dl=1',
					'version' => '2.2.2',
				),
				array(
					'name' => esc_html__( 'Tutor LMS', 'unicamp' ),
					'slug' => 'tutor',
				),
			);

			return array_merge( $plugins, $new_plugins );
		}

		public function get_plugin_google_driver_url( $file_id ) {
			return "https://www.googleapis.com/drive/v3/files/{$file_id}?alt=media&key=" . self::GOOGLE_DRIVER_API;
		}
	}

	Unicamp_Register_Plugins::instance()->initialize();
}
