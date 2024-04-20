<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Product_Sorting' ) ) {
	class Unicamp_WP_Widget_Product_Sorting extends Unicamp_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-product-sorting';
			$this->widget_cssclass    = 'unicamp-wp-widget-product-sorting unicamp-wp-widget-filter unicamp-wp-widget-product-filter';
			$this->widget_name        = esc_html__( '[Unicamp] Product Sorting', 'unicamp' );
			$this->widget_description = esc_html__( 'Display a sorting list to sort products in your store.', 'unicamp' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Sort by', 'unicamp' ),
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'display_type' => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Display type', 'unicamp' ),
					'options' => array(
						'list'   => esc_html__( 'List', 'unicamp' ),
						'inline' => esc_html__( 'Inline', 'unicamp' ),
					),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			/**
			 * @see woocommerce_catalog_ordering()
			 */
			if ( ! woocommerce_products_will_display() ) {
				return;
			}

			$orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );

			$show_default_orderby = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );

			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order' => esc_html__( 'Default', 'unicamp' ),
				'popularity' => esc_html__( 'Popularity', 'unicamp' ),
				'rating'     => esc_html__( 'Average rating', 'unicamp' ),
				'date'       => esc_html__( 'Newness', 'unicamp' ),
				'price'      => esc_html__( 'Price: low to high', 'unicamp' ),
				'price-desc' => esc_html__( 'Price: high to low', 'unicamp' ),
			) );

			if ( ! $show_default_orderby ) {
				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( ! wc_review_ratings_enabled() ) {
				unset( $catalog_orderby_options['rating'] );
			}

			$this->widget_start( $args, $instance );

			$display_type = $this->get_value( $instance, 'display_type' );

			$class     = ' filter-radio-list';
			$class     .= ' show-display-' . $display_type;
			$base_link = $this->get_current_page_url();
			?>
			<ul class="<?php echo esc_attr( $class ); ?>">
				<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
					<?php
					$link         = $base_link;
					$link         = add_query_arg( 'orderby', $id, $link );
					$link_classes = [];

					if ( $orderby === $id ) {
						$link_classes[] = 'chosen disabled';
						$link           = false;
					}

					printf( '<li %1$s><a href="%2$s">%3$s</a></li>',
						! empty( $link_classes ) ? 'class="' . implode( ' ', $link_classes ) . '"' : '',
						! empty( $link ) ? esc_url( $link ) : 'javascript:void(0);',
						esc_html( $name )
					)
					?>
				<?php endforeach; ?>
			</ul>
			<?php
			$this->widget_end( $args );
		}
	}
}
