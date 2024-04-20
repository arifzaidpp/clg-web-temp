<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

class Widget_Course_Category_Carousel extends Terms_Carousel_Base {

	public function get_name() {
		return 'tm-course-category-carousel';
	}

	public function get_title() {
		return esc_html__( 'Course Category Carousel', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'course', 'course-category', 'carousel' ];
	}

	protected function get_taxonomy_name() {
		return \Unicamp_Tutor::instance()->get_tax_category();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '5',
			'tablet_default' => '3',
			'mobile_default' => '2',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );
	}

	protected function register_controls() {
		$this->add_layout_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'unicamp' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'unicamp' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'unicamp' ),
			],
			'default'      => '',
			'prefix_class' => 'unicamp-animation-',
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

	protected function print_slide() {
		$settings  = $this->get_settings_for_display();
		$slide_key = $this->get_current_key();
		$category  = $this->get_current_slide();

		$box_key = $slide_key . 'box';

		$link = get_term_link( $category );

		$this->add_render_attribute( $box_key, [
			'class' => 'unicamp-box link-secret',
			'href'  => $link,
		] );

		$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
		?>
		<div class="swiper-slide">
			<a <?php $this->print_render_attribute_string( $box_key ); ?>>
				<div class="unicamp-image">
					<?php if ( $thumbnail_id ) { ?>
						<?php $size = \Unicamp_Image::elementor_parse_image_size( $settings, '260x320' ); ?>
						<?php \Unicamp_Image::the_attachment_by_id( [
							'id'   => $thumbnail_id,
							'size' => $size,
						] ); ?>
					<?php } else { ?>
						<?php \Unicamp_Templates::image_placeholder( 260, 320 ); ?>
					<?php } ?>

					<div class="category-info">
						<h6 class="category-name"><?php echo esc_html( $category->name ); ?></h6>
					</div>
				</div>
			</a>
		</div>
		<?php
	}
}
