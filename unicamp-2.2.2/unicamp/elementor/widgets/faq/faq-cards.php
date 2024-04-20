<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_FAQ_Cards extends Posts_Base {

	public function get_name() {
		return 'tm-faq-cards';
	}

	public function get_title() {
		return esc_html__( 'FAQ Cards', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return [ 'faq' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-group-widget-grid' ];
	}

	public function get_post_type() {
		return \Unicamp_FAQ::instance()->get_post_type();
	}

	public function get_post_category() {
		return \Unicamp_FAQ::instance()->get_tax_group();
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_grid_section();

		$this->register_query_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => esc_html__( 'Style 01', 'unicamp' ),
				'02' => esc_html__( 'Style 02', 'unicamp' ),
			],
			'default' => '01',
		] );

		$this->end_controls_section();
	}

	private function add_grid_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'unicamp' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'unicamp' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 4,
			'tablet_default' => 2,
			'mobile_default' => 1,
		] );

		$this->add_responsive_control( 'grid_gutter', [
			'label'   => esc_html__( 'Gutter', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query = $this->get_query();

		$this->add_render_attribute( 'grid-wrapper', 'class', 'unicamp-grid-wrapper unicamp-course-category-cards style-' . $settings['style'] );

		$this->add_render_attribute( 'content-wrapper', 'class', 'unicamp-grid lazy-grid' );

		$grid_options = $this->get_grid_options( $settings );

		$this->add_render_attribute( 'grid-wrapper', 'data-grid', wp_json_encode( $grid_options ) );
		?>
		<?php if ( $query->have_posts() ) : ?>
			<?php
			$loop_count = 1;
			$widget     = $this->get_id();
			?>

			<div <?php $this->print_attributes_string( 'grid-wrapper' ); ?>>
				<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
					<div class="grid-sizer"></div>
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php
						$item_key = "item_{$loop_count}";
						$loop_count++;
						$box_key = $item_key . 'box';

						$link = get_the_permalink();

						$this->add_render_attribute( $box_key, [
							'class' => 'unicamp-box',
							'href'  => $link,
						] );
						?>
						<div class="grid-item">
							<a <?php $this->print_render_attribute_string( $box_key ); ?>>
								<div class="category-info">
									<h6 class="category-name"><?php echo esc_html( get_the_title() ); ?></h6>
								</div>
							</a>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata() ?>
				</div>
			</div>
		<?php
		endif;
	}
}
