<?php

if ( ! defined( 'INSIGHT_POPUP_POST_TYPE' ) ) {
	define( 'INSIGHT_POPUP_POST_TYPE', 'ic_popup' );
}

require_once( trailingslashit( dirname( __FILE__ ) ) . 'includes/CMB2_Type_Popup_Display.php' );

if ( ! class_exists( 'Insight_Popup' ) ) {

	class Insight_Popup {

		function __construct() {
			$this->insight_popup_hooks();
		}

		function insight_popup_hooks() {

			add_action( 'init', array(
				$this,
				'insight_core_register_popup',
			) );
			add_action( 'wp_head', array(
				$this,
				'insight_popup_generate_vc_custom_css',
			), 999 );
			add_action( 'wp_head', array(
				$this,
				'insight_popup_frontend_scripts',
			), 999 );
			add_action( 'admin_bar_menu', array(
				$this,
				'insight_popup_remove_wp_bar_view',
			), 999 );
			add_action( 'admin_footer', array(
				$this,
				'insight_popup_remove_vc_frontend_editor',
			) );
			add_action( 'admin_enqueue_scripts', array(
				$this,
				'insight_popup_admin_scripts',
			) );
			add_action( 'wp_footer', array(
				$this,
				'insight_popup_render',
			) );

			add_action( 'save_post', array(
				$this,
				'insight_popup_save',
			), 10, 3 );

			add_filter( 'post_row_actions', array(
				$this,
				'insight_popup_remove_row_actions',
			), 999, 2 );
			add_filter( 'cmb2_meta_boxes', array(
				$this,
				'insight_popup_metabox',
			) );
			add_filter( 'cmb2_select_attributes', array(
				$this,
				'insight_popup_cmb_display_animation_options',
			), 10, 4 );
			add_filter( 'cmb2_select_attributes', array(
				$this,
				'insight_popup_cmb_closing_animation_options',
			), 10, 4 );

		}

		/**
		 * Register Popup Post Type
		 */
		function insight_core_register_popup() {

			$labels = array(
				'name'               => _x( 'Popups', 'Post Type General Name', 'insight-core' ),
				'singular_name'      => _x( 'Popup', 'Post Type Singular Name', 'insight-core' ),
				'menu_name'          => __( 'Popup', 'insight-core' ),
				'name_admin_bar'     => __( 'Popup', 'insight-core' ),
				'parent_item_colon'  => __( 'Parent Popup:', 'insight-core' ),
				'all_items'          => __( 'All Popups', 'insight-core' ),
				'add_new_item'       => __( 'Add New Popup', 'insight-core' ),
				'add_new'            => __( 'Add New', 'insight-core' ),
				'new_item'           => __( 'New Popup', 'insight-core' ),
				'edit_item'          => __( 'Edit Popup', 'insight-core' ),
				'update_item'        => __( 'Update Popup', 'insight-core' ),
				'view_item'          => __( 'View Popup', 'insight-core' ),
				'search_items'       => __( 'Search Popup', 'insight-core' ),
				'not_found'          => __( 'Not found', 'insight-core' ),
				'not_found_in_trash' => __( 'Not found in Trash', 'insight-core' ),
			);

			$args = array(
				'label'               => __( INSIGHT_POPUP_POST_TYPE, 'insight-core' ),
				'description'         => __( 'Insight Popup', 'insight-core' ),
				'labels'              => $labels,
				'supports'            => array(
					'title',
					'editor',
					'revisions',
				),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);

			register_post_type( INSIGHT_POPUP_POST_TYPE, $args );
		}

		/**
		 * Generate VC custom CSS
		 */
		function insight_popup_generate_vc_custom_css() {

			$popups = get_posts( array(
				'post_type'   => INSIGHT_POPUP_POST_TYPE,
				'numberposts' => -1,
				'post_status' => 'publish',
			) );

			if ( ! empty( $popups ) ) {
				$popup_custom_css_array      = array();
				$shortcodes_custom_css_array = array();

				foreach ( $popups as $popup ) {
					$popup_custom_css = get_post_meta( $popup->ID, '_wpb_post_custom_css', true );

					if ( ! empty( $popup_custom_css ) ) {
						$popup_custom_css_array[] = $popup_custom_css;
					}

					$shortcodes_custom_css = get_post_meta( $popup->ID, '_wpb_shortcodes_custom_css', true );
					if ( ! empty( $shortcodes_custom_css ) ) {
						$shortcodes_custom_css_array[] = $shortcodes_custom_css;
					}
				}

				if ( ! empty( $popup_custom_css_array ) ) {
					echo '<style type="text/css" data-type="vc_custom-css">';
					echo implode( '', $popup_custom_css_array );
					echo '</style>';
				}

				if ( ! empty( $shortcodes_custom_css_array ) ) {
					echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
					echo implode( '', $shortcodes_custom_css_array );
					echo '</style>';
				}

			}
		}

		/**
		 * Remove VC Frontend Editor
		 */
		function insight_popup_remove_vc_frontend_editor() {
			?>
			<style type="text/css">
				.post-type-ic_popup .wpb_switch-to-front-composer {
					display: none;
				}
			</style>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					setTimeout( function() {
						$( '.post-type-ic_popup .wpb_switch-to-front-composer' ).remove();
						$( '.post-type-ic_popup .wpb_switch-to-composer' ).next( '.vc_spacer' ).remove();
					}, 50 );
				} );
			</script>
			<?php
		}

		/**
		 * Remove WP Bar View
		 *
		 * @param $wp_admin_bar
		 */
		function insight_popup_remove_wp_bar_view( $wp_admin_bar ) {
			if ( get_post_type() == INSIGHT_POPUP_POST_TYPE ) {
				$wp_admin_bar->remove_node( 'view' );
			}
		}

		/**
		 * Remove Row actions
		 *
		 * @param $actions
		 *
		 * @return mixed
		 */
		function insight_popup_remove_row_actions( $actions ) {
			if ( get_post_type() == INSIGHT_POPUP_POST_TYPE ) {
				unset ( $actions['inline hide-if-no-js'] );
				unset ( $actions['view'] );
				unset ( $actions['edit_vc'] );
			}

			return $actions;
		}

		function insight_popup_admin_scripts() {
			wp_enqueue_style( 'metabox', plugins_url( 'assets/css/metabox.css', __FILE__ ) );
			wp_enqueue_script( 'metabox-script', plugins_url( 'assets/js/metabox.js', __FILE__ ) );
		}

		function insight_popup_frontend_scripts() {
			wp_enqueue_style( 'magnific-popup', plugins_url( 'assets/css/magnific-popup.min.css', __FILE__ ) );
			wp_enqueue_style( 'animate', plugins_url( 'assets/css/animate.min.css', __FILE__ ) );
			wp_enqueue_style( 'insight-popup', plugins_url( 'assets/css/popup.css', __FILE__ ) );

			wp_enqueue_script( 'magnific-script', plugins_url( 'assets/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'waypoints', plugins_url( 'assets/js/jquery.waypoints.min.js', __FILE__ ) );
			wp_enqueue_script( 'js-cookie', plugins_url( 'assets/js/js.cookie.js', __FILE__ ) );
		}

		function insight_popup_metabox() {

			$prefix = 'insight_popup_';

			$fields = array(

				// ========== Active ==========
				array(
					'name' => esc_html__( 'Active', 'insight-core' ),
					'desc' => esc_html__( 'Check this box to active the popup', 'insight-core' ),
					'id'   => $prefix . 'active',
					'type' => 'checkbox',
				),

				// ========== Appearance ==========
				array(
					'name' => esc_html__( 'Appearance', 'insight-core' ),
					'type' => 'title',
					'id'   => $prefix . 'appearance',
				),
				array(
					'name'    => esc_html__( 'Max Width', 'insight-core' ),
					'id'      => $prefix . 'max_width',
					'desc'    => esc_html__( 'Enter the max width of your popup. The height of your popup will be calculated automatically.', 'insight-core' ),
					'type'    => 'number',
					'default' => 900,
					'options' => array(
						'min'    => 300,
						'max'    => 1700,
						'suffix' => 'px',
					),
				),
				array(
					'name' => esc_html__( 'Display Animation', 'insight-core' ),
					'id'   => $prefix . 'display_animation',
					'type' => 'select',
					'desc' => sprintf( wp_kses( __( 'See Animate.css demos <a href="%s" target="_blank">here</a>.', 'insight-core' ), array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ), esc_url( 'https://daneden.github.io/animate.css/' ) ),
				),
				array(
					'name' => esc_html__( 'Closing Animation', 'insight-core' ),
					'id'   => $prefix . 'closing_animation',
					'type' => 'select',
					'desc' => sprintf( wp_kses( __( 'See Animate.css demos <a href="%s" target="_blank">here</a>.', 'insight-core' ), array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ), esc_url( 'https://daneden.github.io/animate.css/' ) ),
				),

				// ========== Displaying Options ==========
				array(
					'name' => esc_html__( 'Displaying Options', 'insight-core' ),
					'type' => 'title',
					'id'   => $prefix . 'display_title',
				),
				array(
					'name'    => esc_html__( 'Show in', 'insight-core' ),
					'id'      => $prefix . 'show_post_type',
					'type'    => 'select',
					'options' => $this->insight_popup_cmb_post_type_options(),
				),
				array(
					'name'    => esc_html__( 'Show on selected pages and selected posts:', 'insight-core' ),
					'id'      => $prefix . 'posts',
					'type'    => 'insight_multiselect',
					'options' => $this->insight_popup_cmb_posts_options( array(
						'post_type' => array(
							'post',
							'page',
						),
					) ),
				),
				array(
					'name' => '',
					'desc' => esc_html__( 'Exclude Archive pages', 'insight-core' ),
					'id'   => $prefix . 'exclude_archive_page',
					'type' => 'checkbox',
				),
				array(
					'name' => esc_html__( 'When to show the popup:', 'insight-core' ),
					'id'   => $prefix . 'display',
					'type' => 'popup_display',
				),
				array(
					'name'    => esc_html__( 'Shows the PopUp if the user is', 'insight-core' ),
					'id'      => $prefix . 'display_role',
					'type'    => 'select',
					'options' => array(
						''         => esc_html__( 'Everyone', 'insight-core' ),
						'login'    => esc_html__( 'Logged in to your site', 'insight-core' ),
						'no_login' => esc_html__( 'Not logged in to your site', 'insight-core' ),
					),
				),
				array(
					'name'    => esc_html__( 'Display Mode', 'insight-core' ),
					'id'      => $prefix . 'display_mode',
					'type'    => 'select',
					'options' => array(
						''            => esc_html__( 'All devices', 'insight-core' ),
						'only_mobile' => esc_html__( 'Only on mobile devices', 'insight-core' ),
						'not_mobile'  => esc_html__( 'Only on a normal computer or laptop (i.e. not a Phone or Tablet).', 'insight-core' ),
					),
				),

				// ========== "Never see this messeage again" settings  ==========
				array(
					'name' => esc_html__( '"Never see this messeage again" settings', 'insight-core' ),
					'type' => 'title',
					'id'   => $prefix . 'hide_title',
				),
				array(
					'name' => '',
					'desc' => esc_html__( 'Add "Never see this message again" checkbox', 'insight-core' ),
					'id'   => $prefix . 'can_hide',
					'type' => 'checkbox',
				),
				array(
					'name' => '',
					'desc' => esc_html__( 'Close button acts as "Never see this message again"', 'insight-core' ),
					'id'   => $prefix . 'close_hide',
					'type' => 'checkbox',
				),
				array(
					'name'    => '',
					'desc'    => esc_html__( 'Upon expiry, user will see this popup again', 'insight-core' ),
					'id'      => $prefix . 'expire',
					'type'    => 'number',
					'default' => 365,
					'options' => array(
						'min'    => 0,
						'prefix' => esc_html__( 'Expiry time', 'insight-core' ),
						'suffix' => 'days',
					),
				),
			);

			$meta_boxes['insight_popup_metabox'] = array(
				'id'           => 'insight_popup_metabox',
				'title'        => esc_html__( 'Popup Settings', 'insight-core' ),
				'object_types' => array( INSIGHT_POPUP_POST_TYPE ),
				'context'      => 'normal',
				'priority'     => 'high',
				'fields'       => $fields,
			);

			return $meta_boxes;
		}

		function insight_popup_cmb_post_type_options() {
			$post_types = array(
				'all'  => class_exists( 'WooCommerce' ) ? esc_html__( 'All posts, pages and products', 'insight-core' ) : ( esc_html__( 'All posts and pages', 'insight-core' ) ),
				'post' => esc_html__( 'All posts', 'insight-core' ),
				'page' => esc_html__( 'All pages', 'insight-core' ),
			);

			if ( class_exists( 'WooCommerce' ) ) {
				$post_types['product'] = esc_html__( 'All products', 'insight-core' );
			}
			$post_types['custom'] = esc_html__( 'Custom', 'insight-core' );

			return $post_types;
		}

		function insight_popup_cmb_display_animation_options( $args, $defaults, $field_object, $field_types_object ) {

			// Only do this for the 'display_animation' field
			if ( 'insight_popup_display_animation' != $field_types_object->_id() ) {
				return $args;
			}

			$option_array = array(
				esc_html__( 'Attention Seekers', 'insight-core' )  => array(
					'bounce'     => 'bounce',
					'flash'      => 'flash',
					'pulse'      => 'pulse',
					'rubberBand' => 'rubberBand',
					'shake'      => 'shake',
					'swing'      => 'swing',
					'tada'       => 'tada',
					'wobble'     => 'wobble',
					'jello'      => 'jello',
				),
				esc_html__( 'Bouncing Entrances', 'insight-core' ) => array(
					'bounceIn'      => 'bounceIn',
					'bounceInDown'  => 'bounceInDown',
					'bounceInLeft'  => 'bounceInLeft',
					'bounceInRight' => 'bounceInRight',
					'bounceInUp'    => 'bounceInUp',
				),
				esc_html__( 'Fading Entrances', 'insight-core' )   => array(
					'fadeIn'         => 'fadeIn',
					'fadeInDown'     => 'fadeInDown',
					'fadeInDownBig'  => 'fadeInDownBig',
					'fadeInLeft'     => 'fadeInLeft',
					'fadeInLeftBig'  => 'fadeInLeftBig',
					'fadeInRight'    => 'fadeInRight',
					'fadeInRightBig' => 'fadeInRightBig',
					'fadeInUp'       => 'fadeInUp',
					'fadeInUpBig'    => 'fadeInUpBig',
				),
				esc_html__( 'Flippers', 'insight-core' )           => array(
					'flip'    => 'flip',
					'flipInX' => 'flipInX',
					'flipInY' => 'flipInY',
				),
				esc_html__( 'Lightspeed', 'insight-core' )         => array(
					'lightSpeedIn' => 'lightSpeedIn',
				),
				esc_html__( 'Rotating Entrances', 'insight-core' ) => array(
					'rotateIn'          => 'rotateIn',
					'rotateInDownLeft'  => 'rotateInDownLeft',
					'rotateInDownRight' => 'rotateInDownRight',
					'rotateInUpLeft'    => 'rotateInUpLeft',
					'rotateInUpRight'   => 'rotateInUpRight',
				),
				esc_html__( 'Sliding Entrances', 'insight-core' )  => array(
					'slideInUp'    => 'slideInUp',
					'slideInDown'  => 'slideInDown',
					'slideInLeft'  => 'slideInLeft',
					'slideInRight' => 'slideInRight',
				),
				esc_html__( 'Zoom Entrances', 'insight-core' )     => array(
					'zoomIn'      => 'zoomIn',
					'zoomInDown'  => 'zoomInDown',
					'zoomInLeft'  => 'zoomInLeft',
					'zoomInRight' => 'zoomInRight',
					'zoomInUp'    => 'zoomInUp',
				),
				esc_html__( 'Specials', 'insight-core' )           => array(
					'rollIn' => 'rollIn',
				),
			);

			$saved_value = $field_object->escaped_value();
			$value       = $saved_value ? $saved_value : $field_object->args( 'default' );

			$options_string = '';
			$options_string .= $field_types_object->select_option( array(
				'label'   => __( 'No animation' ),
				'value'   => '',
				'checked' => ! $value,
			) );

			foreach ( $option_array as $group_label => $group ) {

				$options_string .= '<optgroup label="' . $group_label . '">';

				foreach ( $group as $key => $label ) {
					$options_string .= $field_types_object->select_option( array(
						'label'   => $label,
						'value'   => $key,
						'checked' => $value == $key,
					) );
				}
				$options_string .= '</optgroup>';
			}

			// Ok, replace the options value
			$defaults['options'] = $options_string;

			return $defaults;
		}

		function insight_popup_cmb_closing_animation_options( $args, $defaults, $field_object, $field_types_object ) {

			// Only do this for the 'display_animation' field
			if ( 'insight_popup_closing_animation' != $field_types_object->_id() ) {
				return $args;
			}

			$option_array = array(
				esc_html__( 'Bouncing Exits', 'insight-core' ) => array(
					'bounceOut'      => 'bounceOut',
					'bounceOutDown'  => 'bounceOutDown',
					'bounceOutLeft'  => 'bounceOutLeft',
					'bounceOutRight' => 'bounceOutRight',
					'bounceOutUp'    => 'bounceOutUp',
				),
				esc_html__( 'Fading Exits', 'insight-core' )   => array(
					'fadeOut'         => 'fadeOut',
					'fadeOutDown'     => 'fadeOutDown',
					'fadeOutDownBig'  => 'fadeOutDownBig',
					'fadeOutLeft'     => 'fadeOutLeft',
					'fadeOutLeftBig'  => 'fadeOutLeftBig',
					'fadeOutRight'    => 'fadeOutRight',
					'fadeOutRightBig' => 'fadeOutRightBig',
					'fadeOutUp'       => 'fadeOutUp',
					'fadeOutUpBig'    => 'fadeOutUpBig',
				),
				esc_html__( 'Flippers', 'insight-core' )       => array(
					'flipOutX' => 'flipOutX',
					'flipOutY' => 'flipOutY',
				),
				esc_html__( 'Lightspeed', 'insight-core' )     => array(
					'lightSpeedOut' => 'lightSpeedOut',
				),
				esc_html__( 'Rotating Exits', 'insight-core' ) => array(
					'rotateOut'          => 'rotateOut',
					'rotateOutDownLeft'  => 'rotateOutDownLeft',
					'rotateOutDownRight' => 'rotateOutDownRight',
					'rotateOutUpLeft'    => 'rotateOutUpLeft',
					'rotateOutUpRight'   => 'rotateOutUpRight',
				),
				esc_html__( 'Sliding Exits', 'insight-core' )  => array(
					'slideOutUp'    => 'slideOutUp',
					'slideOutDown'  => 'slideOutDown',
					'slideOutLeft'  => 'slideOutLeft',
					'slideOutRight' => 'slideOutRight',
				),
				esc_html__( 'Zoom Exits', 'insight-core' )     => array(
					'zoomOut'      => 'zoomOut',
					'zoomOutDown'  => 'zoomOutDown',
					'zoomOutLeft'  => 'zoomOutLeft',
					'zoomOutRight' => 'zoomOutRight',
					'zoomOutUp'    => 'zoomOutUp',
				),
				esc_html__( 'Specials', 'insight-core' )       => array(
					'hinge'   => 'hinge',
					'rollOut' => 'rollOut',
				),
			);

			$saved_value = $field_object->escaped_value();
			$value       = $saved_value ? $saved_value : $field_object->args( 'default' );

			$options_string = '';
			$options_string .= $field_types_object->select_option( array(
				'label'   => __( 'No animation' ),
				'value'   => '',
				'checked' => ! $value,
			) );

			foreach ( $option_array as $group_label => $group ) {

				$options_string .= '<optgroup label="' . $group_label . '">';

				foreach ( $group as $key => $label ) {
					$options_string .= $field_types_object->select_option( array(
						'label'   => $label,
						'value'   => $key,
						'checked' => $value == $key,
					) );
				}
				$options_string .= '</optgroup>';
			}

			// Ok, replace the options value
			$defaults['options'] = $options_string;

			return $defaults;
		}

		/**
		 * Get a list of posts
		 *
		 * Generic function to return an array of posts formatted for CMB2. Simply pass
		 * in your WP_Query arguments and get back a beautifully formatted CMB2 options
		 * array.
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		function insight_popup_cmb_posts_options( $args = array() ) {

			$defaults = array(
				'posts_per_page' => -1,
			);

			if ( class_exists( 'WooCommerce' ) ) {
				array_push( $args['post_type'], 'product' );
			}

			$query = new WP_Query( array_replace_recursive( $defaults, $args ) );

			$posts = $query->get_posts();

			$post_array = array();

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$post_array[ $post->ID ] = $post->post_title;
				}
			}

			return $post_array;
		}

		/**
		 * Get popup's settings
		 *
		 * @param $post_id
		 *
		 * @return array
		 */
		function insight_popup_settings( $post_id ) {

			$settings = array();

			$settings['active']               = get_post_meta( $post_id, 'insight_popup_active', true );
			$settings['max_width']            = get_post_meta( $post_id, 'insight_popup_max_width', true );
			$settings['display_animation']    = get_post_meta( $post_id, 'insight_popup_display_animation', true );
			$settings['closing_animation']    = get_post_meta( $post_id, 'insight_popup_closing_animation', true );
			$settings['show_post_type']       = get_post_meta( $post_id, 'insight_popup_show_post_type', true );
			$settings['posts']                = get_post_meta( $post_id, 'insight_popup_posts', true );
			$settings['exclude_archive_page'] = get_post_meta( $post_id, 'insight_popup_exclude_archive_page', true );
			$settings['display']              = get_post_meta( $post_id, 'insight_popup_display', true );
			$settings['display_role']         = get_post_meta( $post_id, 'insight_popup_display_role', true );
			$settings['display_mode']         = get_post_meta( $post_id, 'insight_popup_display_mode', true );
			$settings['can_hide']             = get_post_meta( $post_id, 'insight_popup_can_hide', true );
			$settings['close_hide']           = get_post_meta( $post_id, 'insight_popup_close_hide', true );
			$settings['expire']               = get_post_meta( $post_id, 'insight_popup_expire', true );
			$settings['exclude_posts']        = get_post_meta( $post_id, 'insight_popup_exclude_posts', true );

			return $settings;
		}

		function insight_popup_render() {

			$popups = get_posts( array(
				'post_type'   => array( INSIGHT_POPUP_POST_TYPE ),
				'numberposts' => -1,
				'post_status' => 'publish',
			) );

			foreach ( $popups as $popup ) {

				$popup_id = $popup->ID;
				$settings = $this->insight_popup_settings( $popup_id );

				if ( 'on' === $settings['active'] && $this->insight_popup_check_valid_page( $settings ) && $this->insight_popup_check_valid_user( $settings['display_role'] ) && $this->insight_popup_check_valid_display_mode( $settings['display_mode'] ) ) {
					include( trailingslashit( dirname( __FILE__ ) ) . 'popup-template.php' );

					return;
				}

			}
		}

		function insight_popup_check_valid_page( $settings ) {

			global $post;

			$valid = false;

			if ( 'all' == $settings['show_post_type'] && ! in_array( $post->ID, $settings['exclude_posts'] ) ) {
				$valid = true;
			} elseif ( 'custom' == $settings['show_post_type'] ) {
				if ( in_array( $post->ID, $settings['posts'] ) && ! is_archive() ) {
					$valid = true;
				}

				if ( function_exists( 'is_shop' ) ) {
					if ( is_shop() ) {
						$valid = true;
					}
				}
			} elseif ( 'page' == $settings['show_post_type'] || 'post' == $settings['show_post_type'] || 'product' == $settings['show_post_type'] ) {
				if ( $settings['show_post_type'] == $post->post_type && ! in_array( $post->ID, $settings['exclude_posts'] ) ) {
					if ( is_archive() && $settings['exclude_archive_page'] ) {
						$valid = false;
					} else {
						$valid = true;
					}
				}
			}

			return $valid;
		}

		function insight_popup_check_valid_user( $display_role ) {

			$valid = false;

			if ( '' == $display_role ) {
				$valid = true;
			} elseif ( 'login' == $display_role && is_user_logged_in() ) {
				$valid = true;
			} elseif ( 'no_login' == $display_role && ! is_user_logged_in() ) {
				$valid = true;
			}

			return $valid;
		}

		function insight_popup_check_valid_display_mode( $display_mode ) {

			$valid = false;

			if ( function_exists( 'is_mobile' ) && function_exists( 'is_tablet' ) ) {
				if ( '' == $display_mode ) {
					$valid = true;
				} elseif ( ( is_mobile() || is_tablet() ) && 'only_mobile' == $display_mode ) {
					$valid = true;
				} elseif ( ! is_mobile() && ! is_tablet() && 'not_mobile' == $display_mode ) {
					$valid = true;
				}
			}

			return $valid;
		}

		function insight_popup_save( $popup_id, $post, $update ) {

			if ( INSIGHT_POPUP_POST_TYPE != $post->post_type ) {
				return;
			}

			if ( wp_is_post_revision( $popup_id ) || wp_is_post_autosave( $popup_id ) ) {
				return;
			}

			$exclude_posts = array();

			$popups = get_posts( array(
				'post_type'   => array( INSIGHT_POPUP_POST_TYPE ),
				'numberposts' => -1,
				'post_status' => 'publish',
			) );

			if ( ! empty( $popups ) ) {

				foreach ( $popups as $popup ) {

					$post_type = get_post_meta( $popup->ID, 'insight_popup_show_post_type', true );

					if ( 'custom' == $post_type ) {
						$posts = get_post_meta( $popup->ID, 'insight_popup_posts', true );

						if ( is_array( $posts ) ) {
							$exclude_posts = array_merge( $exclude_posts, $posts );
						}
					}
				}

				foreach ( $popups as $popup ) {
					$post_type = get_post_meta( $popup->ID, 'insight_popup_show_post_type', true );

					$new_post_type = isset( $_POST['insight_popup_show_post_type'] ) ? sanitize_text_field( $_POST['insight_popup_show_post_type'] ) : '';

					if ( $popup_id == $popup->ID && ! empty( $new_post_type ) ) {
						$post_type = $new_post_type;
					}

					if ( 'all' == $post_type ) {
						update_post_meta( $popup->ID, 'insight_popup_exclude_posts', $exclude_posts );
					} elseif ( 'page' == $post_type || 'post' == $post_type || 'product' == $post_type ) {
						if ( 'page' == $post_type ) {
							$posts = $this->insight_popup_get_ids_by_post_type( array(
								'post',
								'product',
							) );
						}

						if ( 'post' == $post_type ) {
							$posts = $this->insight_popup_get_ids_by_post_type( array(
								'page',
								'product',
							) );
						}

						if ( 'product' == $post_type ) {
							$posts = $this->insight_popup_get_ids_by_post_type( array(
								'page',
								'post',
							) );
						}

						$posts = array_merge( $exclude_posts, $posts );
						update_post_meta( $popup->ID, 'insight_popup_exclude_posts', $posts );
					}
				}
			}
		}

		function insight_popup_get_ids_by_post_type( $post_type ) {
			return get_posts( array(
				'post_type'   => $post_type,
				'numberposts' => -1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			) );
		}
	}

	new Insight_Popup();
}
