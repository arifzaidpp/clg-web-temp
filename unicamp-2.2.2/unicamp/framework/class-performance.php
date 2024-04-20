<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Performance' ) ) {
	class Unicamp_Performance {

		protected static $instance = null;

		static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Remove version from any enqueued scripts.
			add_filter( 'style_loader_src', array( $this, 'at_remove_wp_ver_css_js' ), 9999 );
			add_filter( 'script_loader_src', array( $this, 'at_remove_wp_ver_css_js' ), 9999 );

			add_action( 'init', array( $this, 'disable_emojis' ), 9999 );

			add_action( 'init', array( $this, 'disable_embeds_code_init' ), 9999 );
		}

		/**
		 * @param $src
		 *
		 * @return mixed|string
		 */
		function at_remove_wp_ver_css_js( $src ) {
			$override = apply_filters( 'pre_at_remove_wp_ver_css_js', false, $src );
			if ( $override !== false ) {
				return $override;
			}

			if ( strpos( $src, 'ver=' ) ) {
				$src = remove_query_arg( 'ver', $src );
			}

			return $src;
		}

		/**
		 * Disable the emoji's
		 */
		function disable_emojis() {
			$enable = Unicamp::setting( 'disable_emoji' );
			if ( ! $enable ) {
				return;
			}

			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
			add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_remove_dns_prefetch' ), 10, 2 );
		}

		/**
		 * Filter function used to remove the tinymce emoji plugin.
		 *
		 * @param    array $plugins
		 *
		 * @return   array             Difference betwen the two arrays
		 */
		function disable_emojis_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			}

			return array();
		}

		/**
		 * Remove emoji CDN hostname from DNS prefetching hints.
		 *
		 * @param  array  $urls          URLs to print for resource hints.
		 * @param  string $relation_type The relation type the URLs are printed for.
		 *
		 * @return array                 Difference betwen the two arrays.
		 */
		function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {

			if ( 'dns-prefetch' == $relation_type ) {

				// Strip out any URLs referencing the WordPress.org emoji location.
				$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
				foreach ( $urls as $key => $url ) {
					if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
						unset( $urls[ $key ] );
					}
				}

			}

			return $urls;
		}

		function disable_embeds_code_init() {
			$enable = Unicamp::setting( 'disable_embeds' );
			if ( ! $enable ) {
				return;
			}

			// Remove the REST API endpoint.
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );

			// Turn off oEmbed auto discovery.
			add_filter( 'embed_oembed_discover', '__return_false' );

			// Don't filter oEmbed results.
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

			// Remove oEmbed discovery links.
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

			// Remove oEmbed-specific JavaScript from the front-end and back-end.
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			add_filter( 'tiny_mce_plugins', array( $this, 'disable_embeds_tiny_mce_plugin' ) );

			// Remove all embeds rewrite rules.
			add_filter( 'rewrite_rules_array', array( $this, 'disable_embeds_rewrites' ) );

			// Remove filter of the oEmbed result before any HTTP requests are made.
			remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
		}

		function disable_embeds_tiny_mce_plugin( $plugins ) {
			return array_diff( $plugins, array( 'wpembed' ) );
		}

		function disable_embeds_rewrites( $rules ) {
			foreach ( $rules as $rule => $rewrite ) {
				if ( false !== strpos( $rewrite, 'embed=true' ) ) {
					unset( $rules[ $rule ] );
				}
			}

			return $rules;
		}
	}

	Unicamp_Performance::instance()->initialize();
}
