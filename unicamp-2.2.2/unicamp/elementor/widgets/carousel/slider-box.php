<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Slider_Box extends Carousel_Base {

	public function get_name() {
		return 'tm-slider-box';
	}

	public function get_title() {
		return esc_html__( 'Slider Box', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-post-slider';
	}

	public function get_keywords() {
		return [ 'modern', 'slider', 'box' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_box_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );
	}

	protected function update_slider_settings( $settings, $slider_settings ) {
		$menus = array();

		foreach ( $settings['slides'] as $slide ) :
			if ( ! empty( $slide['title'] ) ):
				$menus[] = $slide['title'];
			endif;
		endforeach;

		if ( ! empty( $menus ) ) {
			$slider_settings['data-pagination-menu-id'] = '#tm-slider-box-pagination-menu';
			$slider_settings['data-pagination-menu']    = wp_json_encode( $menus );
		}

		return $slider_settings;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-slider-box' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="slider-box-bg"></div>
			<div class="row row-xs-center">
				<div class="col-md-6">
					<?php $this->print_slider( $settings ); ?>
				</div>
				<div class="col-md-6">
					<div class="slide-content-wrap">
						<div class="slide-content-inner">
							<?php if ( ! empty( $settings['title_text'] ) ) : ?>
								<h4 class="slider-title"><?php echo esc_html( $settings['title_text'] ); ?></h4>
							<?php endif; ?>

							<div class="slider-title-separator"></div>

							<?php if ( ! empty( $settings['description_text'] ) ) : ?>
								<div
									class="slider-description"><?php echo wp_kses_post( $settings['description_text'] ); ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $settings['slides'] ) ) : ?>
								<div class="swiper-pagination-menu" id="tm-slider-box-pagination-menu">
									<?php foreach ( $settings['slides'] as $slide ) :
										$slide_id = $slide['_id'];
										$item_dot = 'dot_' . $slide_id;

										$this->add_render_attribute( $item_dot, 'class', [
											'swiper-pagination-bullet',
										] );
										?>
										<div <?php $this->print_attributes_string( $item_dot ); ?>>
										<span class="swiper-pagination-bullet-text">
										<?php if ( ! empty( $slide['title'] ) ) : ?>
											<?php echo esc_html( $slide['title'] ); ?>
										<?php endif; ?>
										</span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'default'     => esc_html__( 'Add Your Heading Text Here', 'unicamp' ),
		] );

		$this->add_control( 'description_text', [
			'label'       => esc_html__( 'Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your description', 'unicamp' ),
			'rows'        => 10,
			'separator'   => 'none',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'label'     => esc_html__( 'Image Size', 'unicamp' ),
			'name'      => 'image',
			'default'   => 'full',
			'separator' => 'before',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'unicamp' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$repeater->add_control( 'title_heading', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'unicamp' ),
			'default'     => esc_html__( 'Add Your Heading Text Here', 'unicamp' ),
		] );

		$this->add_control( 'slides', [
			'label'       => esc_html__( 'Slides', 'unicamp' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title' => 'Unicamp Studio',
				],
				[
					'title' => 'Unicamp Studio',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_styling_section', [
			'label' => esc_html__( 'Box', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_shape_bg',
			'selector' => '{{WRAPPER}} .slider-box-bg:after',
		] );

		$this->end_controls_section();
	}

	protected function print_slides( array $settings ) {
		foreach ( $settings['slides'] as $slide ) :
			$slide_id = $slide['_id'];
			$item_key = 'item_' . $slide_id;

			$this->add_render_attribute( $item_key, 'class', [
				'swiper-slide',
				'elementor-repeater-item-' . $slide_id,
			] );
			?>
			<div <?php $this->print_attributes_string( $item_key ); ?>>
				<div class="slide-image unicamp-image">
					<?php
					echo \Unicamp_Image::get_elementor_attachment( [
						'settings'      => $slide,
						'size_settings' => $settings,
					] );
					?>
				</div>
			</div>
		<?php endforeach;
	}
}
