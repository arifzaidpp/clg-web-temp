<?php
/*
Plugin Name: Insight Swatches
Description: WooCommerce Variation Swatches by ThemeMove
Author: ThemeMove
Version: 1.6.0
Author URI: https://thememove.com
Text Domain: insight-swatches
Domain Path: /languages/
Requires at least: 5.7
Requires PHP: 7.0
WC requires at least: 3.0
WC tested up to: 6.3.0
*/

defined( 'ABSPATH' ) || exit;

! defined( 'INSIGHT_SWATCHES_VERSION' ) && define( 'INSIGHT_SWATCHES_VERSION', '1.6.0' );
! defined( 'INSIGHT_SWATCHES_URL' ) && define( 'INSIGHT_SWATCHES_URL', plugin_dir_url( __FILE__ ) );
! defined( 'INSIGHT_SWATCHES_PATH' ) && define( 'INSIGHT_SWATCHES_PATH', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'Insight_Swatches' ) ) {
	class Insight_Swatches {
		function __construct() {
			add_action( 'plugins_loaded', [ $this, 'load_text_domain' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

			add_action( 'init', [ $this, 'init' ] );
			add_action( 'woocommerce_variable_add_to_cart', [ $this, 'variable_scripts' ] );
		}

		public function load_text_domain() {
			load_plugin_textdomain( 'insight-swatches', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public function init() {
			if ( ! class_exists( 'Woocommerce' ) ) {
				return;
			}

			add_shortcode( 'insight_swatches', array( $this, 'shortcode_loop' ) );
			add_shortcode( 'insight_swatches_loop', array( $this, 'shortcode_loop' ) );
			add_shortcode( 'insight_swatches_single', array( $this, 'shortcode_single' ) );

			// add field for attributes
			add_filter( 'product_attributes_type_selector', array( $this, 'type_selector' ) );

			$attribute_taxonomies = wc_get_attribute_taxonomies();
			foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
				add_action( 'pa_' . $attribute_taxonomy->attribute_name . '_add_form_fields', array(
					$this,
					'show_field',
				) );
				add_action( 'pa_' . $attribute_taxonomy->attribute_name . '_edit_form_fields', array(
					$this,
					'show_field',
				) );
				add_action( 'create_pa_' . $attribute_taxonomy->attribute_name, array(
					$this,
					'save_field',
				) );
				add_action( 'edited_pa_' . $attribute_taxonomy->attribute_name, array( $this, 'save_field' ) );
				add_filter( "manage_edit-pa_{$attribute_taxonomy->attribute_name}_columns", array(
					$this,
					'custom_columns',
				) );
				add_filter( "manage_pa_{$attribute_taxonomy->attribute_name}_custom_column", array(
					$this,
					'custom_columns_content',
				), 10, 3 );
			}

			// variations single
			if ( apply_filters( 'insight_swatches_show_single', true ) ) {
				// enable by default
				remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
				add_action( 'woocommerce_variable_add_to_cart', array( $this, 'swatches_single' ) );
			}

			// variations loop
			if ( apply_filters( 'insight_swatches_show_loop', false ) ) {
				// disable by default
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'swatches_loop' ), 9 );
			}

			// ajax add to cart
			add_action( 'wp_ajax_nopriv_isw_add_to_cart', array( $this, 'add_to_cart' ) );
			add_action( 'wp_ajax_isw_add_to_cart', array( $this, 'add_to_cart' ) );

			add_action( 'woocommerce_add_to_cart', array( $this, 'repair_cart' ) );
		}

		function type_selector( $types ) {
			global $pagenow;
			if ( ( $pagenow === 'post-new.php' ) || ( $pagenow === 'post.php' ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				return $types;
			} else {
				$types['select'] = esc_html__( 'Select', 'insight-swatches' );
				$types['text']   = esc_html__( 'Text', 'insight-swatches' );
				$types['color']  = esc_html__( 'Color', 'insight-swatches' );
				$types['image']  = esc_html__( 'Image', 'insight-swatches' );

				return $types;
			}
		}

		function field_on_create( $taxonomy ) {
			var_dump( $taxonomy );
		}

		function show_field( $term_or_tax ) {
			if ( is_object( $term_or_tax ) ) {
				//is term
				$term_id    = $term_or_tax->term_id;
				$attr_id    = wc_attribute_taxonomy_id_by_name( $term_or_tax->taxonomy );
				$attr_info  = wc_get_attribute( $attr_id );
				$wrap_start = '<tr class="form-field"><th><label>';
				$wrap_mid   = '</label></th><td>';
				$wrap_end   = '</td></tr>';
			} else {
				//is taxonomy (on create)
				$term_id    = 0;
				$attr_id    = wc_attribute_taxonomy_id_by_name( $term_or_tax );
				$attr_info  = wc_get_attribute( $attr_id );
				$wrap_start = '<div class="form-field"><label>';
				$wrap_mid   = '</label>';
				$wrap_end   = '</div>';
			}
			$sw_tooltip = get_term_meta( $term_id, 'sw_tooltip', true );
			switch ( $attr_info->type ) {
				case 'text':
					$sw_val = get_term_meta( $term_id, 'sw_text', true );
					echo $wrap_start . esc_html__( 'SW Text', 'insight-swatches' ) . $wrap_mid . '<input id="sw_text" name="sw_text" value="' . esc_attr( $sw_val ) . '" type="text"/>' . $wrap_end;
					echo $wrap_start . esc_html__( 'SW Tooltip', 'insight-swatches' ) . $wrap_mid . '<input id="sw_tooltip" name="sw_tooltip" value="' . esc_attr( $sw_tooltip ) . '" type="text"/>' . $wrap_end;
					break;
				case 'color':
					$sw_val = get_term_meta( $term_id, 'sw_color', true );
					echo $wrap_start . esc_html__( 'SW Color', 'insight-swatches' ) . $wrap_mid . '<input class="sw_color" id="sw_color" name="sw_color" value="' . esc_attr( $sw_val ) . '" type="text"/>' . $wrap_end;
					echo $wrap_start . esc_html__( 'SW Tooltip', 'insight-swatches' ) . $wrap_mid . '<input id="sw_tooltip" name="sw_tooltip" value="' . esc_attr( $sw_tooltip ) . '" type="text"/>' . $wrap_end;
					break;
				case 'image':
					wp_enqueue_media();
					$sw_val = get_term_meta( $term_id, 'sw_image', true );
					if ( $sw_val ) {
						$image = wp_get_attachment_thumb_url( $sw_val );
					} else {
						$image = wc_placeholder_img_src();
					}
					echo $wrap_start . 'SW Image' . $wrap_mid; ?>
					<div id="sw_image_thumbnail" style="float: left; margin-right: 10px;"><img
							src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/></div>
					<div style="line-height: 60px;">
						<input type="hidden" id="sw_image" name="sw_image"
						       value="<?php echo esc_attr( $sw_val ); ?>"/>
						<button id="sw_upload_image" type="button"
						        class="sw_upload_image button"><?php esc_html_e( 'Upload/Add image', 'insight-swatches' ); ?>
						</button>
						<button id="sw_remove_image" type="button"
						        class="sw_remove_image button"><?php esc_html_e( 'Remove image', 'insight-swatches' ); ?>
						</button>
					</div>
					<?php
					echo $wrap_end;
					echo $wrap_start . 'SW Tooltip' . $wrap_mid . '<input id="sw_tooltip" name="sw_tooltip" value="' . esc_attr( $sw_tooltip ) . '" type="text"/>' . $wrap_end;
					break;
				default:
					echo '';
			}
		}

		function save_field( $term_id ) {
			if ( isset( $_POST['sw_color'] ) ) {
				update_term_meta( $term_id, 'sw_color', sanitize_text_field( $_POST['sw_color'] ) );
			}
			if ( isset( $_POST['sw_text'] ) ) {
				update_term_meta( $term_id, 'sw_text', sanitize_text_field( $_POST['sw_text'] ) );
			}
			if ( isset( $_POST['sw_image'] ) ) {
				update_term_meta( $term_id, 'sw_image', sanitize_text_field( $_POST['sw_image'] ) );
			}
			if ( isset( $_POST['sw_tooltip'] ) ) {
				update_term_meta( $term_id, 'sw_tooltip', sanitize_text_field( $_POST['sw_tooltip'] ) );
			}
		}

		public function frontend_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? '' : '.min';

			$curr_args = array(
				'ajax'             => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'sw-nonce' ),
				'product_selector' => apply_filters( 'sw_product_selector', '.product' ),
				'price_selector'   => apply_filters( 'sw_price_selector', '.price' ),
				'localization'     => array(
					'add_to_cart_text'    => esc_html__( 'Add to cart', 'insight-swatches' ),
					'read_more_text'      => esc_html__( 'Read more', 'insight-swatches' ),
					'select_options_text' => esc_html__( 'Select options', 'insight-swatches' ),
				),
			);

			wp_enqueue_style( 'isw-frontend', INSIGHT_SWATCHES_URL . 'assets/css/style.css', INSIGHT_SWATCHES_VERSION );
			wp_enqueue_script( 'isw-frontend', INSIGHT_SWATCHES_URL . "assets/js/frontend{$suffix}.js", array( 'jquery' ), INSIGHT_SWATCHES_VERSION, true );
			wp_localize_script( 'isw-frontend', 'isw_vars', $curr_args );
		}

		public function admin_scripts() {
			if ( ! class_exists( 'Woocommerce' ) ) {
				return;
			}

			$curr_args = array(
				'placeholder_img' => wc_placeholder_img_src(),
			);
			wp_enqueue_script( 'isw-backend', INSIGHT_SWATCHES_URL . 'assets/js/admin.js', array(
				'jquery',
				'wp-color-picker',
			), INSIGHT_SWATCHES_VERSION, true );
			wp_localize_script( 'isw-backend', 'isw_vars', $curr_args );
		}

		public function variable_scripts() {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		public static function get_attributes() {
			$atts             = get_object_taxonomies( 'product' );
			$ready_attributes = array();
			if ( ! empty( $atts ) ) {
				foreach ( $atts as $k ) {
					if ( substr( $k, 'pa_' ) === 0 ) {
						$ready_attributes[] = $k;
					}
				}
			}

			return $ready_attributes;
		}

		public static function variations( $variations ) {
			$new_variations = array();
			foreach ( $variations as $variation ) {
				if ( $variation['variation_id'] !== '' ) {
					$id                        = get_post_thumbnail_id( $variation['variation_id'] );
					$src                       = wp_get_attachment_image_src( $id, 'shop_catalog' );
					$srcset                    = wp_get_attachment_image_srcset( $id, 'shop_catalog' );
					$sizes                     = wp_get_attachment_image_sizes( $id, 'shop_catalog' );
					$variation['image_src']    = $src;
					$variation['image_srcset'] = $srcset;
					$variation['image_sizes']  = $sizes;
					$new_variations[]          = $variation;
				}
			}

			return $new_variations;
		}

		public function swatches_single() {
			/**
			 * @var WC_Product $product
			 */
			global $product;

			// Get Available variations?
			$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

			if ( $product->is_type( 'variable' ) ) {
				$attributes           = $product->get_attributes();
				$available_variations = $get_variations ? $product->get_available_variations() : false;
				$variation_attributes = $product->get_variation_attributes();
				$selected_attributes  = $product->get_default_attributes();
				$args                 = array(
					'attributes'           => $attributes,
					'available_variations' => $available_variations,
					'variation_attributes' => $variation_attributes,
					'selected_attributes'  => $selected_attributes,
				);

				echo Insight_Swatches_Utils::get_template( 'swatches-single.php', $args, true );
			}
		}

		function shortcode_single() {
			global $product;
			if ( $product->is_type( 'variable' ) ) {
				$attributes           = $product->get_attributes();
				$available_variations = $product->get_available_variations();
				$variation_attributes = $product->get_variation_attributes();
				$selected_attributes  = $product->get_default_attributes();
				$args                 = array(
					'attributes'           => $attributes,
					'available_variations' => $available_variations,
					'variation_attributes' => $variation_attributes,
					'selected_attributes'  => $selected_attributes,
				);
				$template             = Insight_Swatches_Utils::get_template( 'swatches-single.php', $args, true );

				return $template;
			}
		}

		public function swatches_loop() {
			global $product;
			if ( $product->is_type( 'variable' ) ) {
				$attributes           = $product->get_attributes();
				$available_variations = $product->get_available_variations();
				$variation_attributes = $product->get_variation_attributes();
				$selected_attributes  = $product->get_default_attributes();
				$args                 = array(
					'attributes'           => $attributes,
					'available_variations' => $available_variations,
					'variation_attributes' => $variation_attributes,
					'selected_attributes'  => $selected_attributes,
				);

				echo Insight_Swatches_Utils::get_template( 'swatches-loop.php', $args, true );
			}
		}

		function shortcode_loop() {
			global $product;
			if ( $product->is_type( 'variable' ) ) {
				$attributes           = $product->get_attributes();
				$available_variations = $product->get_available_variations();
				$variation_attributes = $product->get_variation_attributes();
				$selected_attributes  = $product->get_default_attributes();
				$args                 = array(
					'attributes'           => $attributes,
					'available_variations' => $available_variations,
					'variation_attributes' => $variation_attributes,
					'selected_attributes'  => $selected_attributes,
				);
				$template             = Insight_Swatches_Utils::get_template( 'swatches-loop.php', $args, true );

				return $template;
			}
		}

		public function add_to_cart() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'sw-nonce' ) ) {
				wp_die( 'Permission Denied!' );
			}

			$product_id   = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity     = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', absint( $_POST['quantity'] ) );
			$variation_id = absint( $_POST['variation_id'] );
			$variation    = array();
			if ( is_array( $_POST['variation'] ) ) {
				foreach ( $_POST['variation'] as $key => $value ) {
					$variation[ $key ] = Insight_Swatches_Utils::utf8_urldecode( $value );
				}
			}
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
				$data = WC_AJAX::get_refreshed_fragments();
			} else {
				WC_AJAX::json_headers();
				$data = array(
					'error'       => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
				);
			}

			wp_send_json( $data );
			die();
		}

		public function repair_cart() {
			if ( defined( 'DOING_AJAX' ) ) {
				wc_setcookie( 'woocommerce_items_in_cart', 1 );
				wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );
				do_action( 'woocommerce_set_cart_cookies', true );
			}
		}

		function custom_columns( $columns ) {
			$columns['sw_value']   = 'SW Value';
			$columns['sw_tooltip'] = 'SW Tooltip';

			return $columns;
		}

		function custom_columns_content( $columns, $column, $term_id ) {
			if ( $column === 'sw_value' ) {
				$term      = get_term( $term_id );
				$attr_id   = wc_attribute_taxonomy_id_by_name( $term->taxonomy );
				$attr_info = wc_get_attribute( $attr_id );
				switch ( $attr_info->type ) {
					case 'image':
						$val = get_term_meta( $term_id, 'sw_image', true );
						if ( $val ) {
							echo '<img style="display: inline-block; width: 40px; height: 40px; background-color: #eee; box-sizing: border-box; border: 1px solid #eee;" src="' . esc_url( wp_get_attachment_thumb_url( $val ) ) . '"/>';
						}
						break;
					case 'color':
						$val = get_term_meta( $term_id, 'sw_color', true );
						if ( $val ) {
							echo '<span style="display: inline-block; width: 40px; height: 40px; background-color: ' . esc_attr( $val ) . '; box-sizing: border-box; border: 1px solid #eee;"></span>';
						}

						break;
					case 'text':
						$val = get_term_meta( $term_id, 'sw_text', true );
						if ( $val ) {
							echo '<span style="display: inline-block; height: 40px; line-height: 40px; padding: 0 15px; border: 1px solid #eee; background-color: #fff; min-width: 44px; box-sizing: border-box;">' . esc_html( $val ) . '</span>';
						}
						break;
				}
			}
			if ( $column === 'sw_tooltip' ) {
				echo get_term_meta( $term_id, 'sw_tooltip', true );
			}
		}
	}
}

include_once INSIGHT_SWATCHES_PATH . 'includes/class.insight.sw.utils.php';

new Insight_Swatches();
