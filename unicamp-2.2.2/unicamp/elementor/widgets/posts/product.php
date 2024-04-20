<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Product extends Posts_Base {

	public function get_name() {
		return 'tm-product';
	}

	public function get_title() {
		return esc_html__( 'Product', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product', 'shop', 'catalog' ];
	}

	protected function get_post_type() {
		return 'product';
	}

	protected function get_post_category() {
		return 'product_cat';
	}

	public function get_script_depends() {
		return [
			'unicamp-grid-query',
			'unicamp-widget-grid-post',
		];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_grid_options_section();

		$this->add_caption_style_section();

		$this->add_pagination_section();

		parent::register_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query     = $this->get_query();
		$post_type = $this->get_post_type();

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-grid-wrapper unicamp-product style-grid' );

		if ( $settings['pagination_type'] !== '' && $query->found_posts > $settings['query_number'] ) {
			$this->add_render_attribute( 'wrapper', 'data-pagination', $settings['pagination_type'] );
		}

		if ( $settings['pagination_custom_button_id'] !== '' ) {
			$this->add_render_attribute( 'wrapper', 'data-pagination-custom-button-id', $settings['pagination_custom_button_id'] );
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $query->have_posts() ) : ?>

				<?php
				$unicamp_grid_query['source']        = $settings['query_source'];
				$unicamp_grid_query['action']        = "{$post_type}_infinite_load";
				$unicamp_grid_query['max_num_pages'] = $query->max_num_pages;
				$unicamp_grid_query['found_posts']   = $query->found_posts;
				$unicamp_grid_query['count']         = $query->post_count;
				$unicamp_grid_query['query_vars']    = $this->get_query_args();
				$unicamp_grid_query['settings']      = $settings;
				$unicamp_grid_query                  = htmlspecialchars( wp_json_encode( $unicamp_grid_query ) );
				?>
				<input type="hidden"
				       class="unicamp-query-input" <?php echo 'value="' . $unicamp_grid_query . '"'; ?>/>

				<div class="modern-grid">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php
						set_query_var( 'settings', $settings );

						remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
						wc_get_template_part( 'content', 'product' );
						?>
					<?php endwhile; ?>
				</div>

				<?php $this->print_pagination( $query, $settings ); ?>

				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'unicamp' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'full',
				'condition' => [
					'thumbnail_default_size!' => '1',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_grid_options_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'unicamp' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'unicamp' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 3,
			'tablet_default' => 2,
			'mobile_default' => 1,
			'selectors'      => [
				'{{WRAPPER}} .modern-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
			],
		] );

		$this->add_responsive_control( 'grid_column_gutter', [
			'label'     => esc_html__( 'Column Gutter', 'unicamp' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0,
			'max'       => 200,
			'step'      => 1,
			'default'   => 30,
			'selectors' => [
				'{{WRAPPER}} .modern-grid' => 'grid-column-gap: {{VALUE}}px;',
			],
		] );

		$this->add_responsive_control( 'grid_row_gutter', [
			'label'     => esc_html__( 'Row Gutter', 'unicamp' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0,
			'max'       => 200,
			'step'      => 1,
			'default'   => 30,
			'selectors' => [
				'{{WRAPPER}} .modern-grid' => 'grid-row-gap: {{VALUE}}px;',
			],
		] );

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'caption_style_section', [
			'label' => esc_html__( 'Caption', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'caption_text_align', [
			'label'                => esc_html__( 'Alignment', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .product-info' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'unicamp' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'caption_title_heading', [
			'label'     => esc_html__( 'Product Name', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_title_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .product-info .woocommerce-loop-product__title',
		] );

		$this->start_controls_tabs( 'caption_title_tabs' );

		$this->start_controls_tab( 'caption_title_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_control( 'caption_title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .woocommerce-loop-product__title' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_title_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_control( 'caption_title_hover_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .woocommerce-loop-product__title a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_price_heading', [
			'label'     => esc_html__( 'Product Price', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_price_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .product-info .price',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_price_decimals_typography',
			'label'    => esc_html__( 'Decimals Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .product-info .price .decimals-separator',
		] );

		$this->start_controls_tabs( 'caption_price_tabs' );

		$this->start_controls_tab( 'caption_regular_price_tab', [
			'label' => esc_html__( 'Regular', 'unicamp' ),
		] );

		$this->add_control( 'caption_regular_price_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price del' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_regular_price_decimals_color', [
			'label'     => esc_html__( 'Decimals Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price del .decimals-separator' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_sale_price_tab', [
			'label' => esc_html__( 'Sale', 'unicamp' ),
		] );

		$this->add_control( 'caption_sale_price_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_sale_price_decimals_color', [
			'label'     => esc_html__( 'Decimals Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price .decimals-separator' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}
