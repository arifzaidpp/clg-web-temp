<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Testimonial_Carousel_3D extends Base {

	private $current_key   = null;
	private $current_slide = null;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'anime', UNICAMP_ELEMENTOR_URI . '/assets/libs/anime/anime.min.js', array(
			'jquery',
		), null, true );

		wp_register_script( 'unicamp-vertical-carousel-3d', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/vertical-carousel-3d.js', array(
			'anime',
		), null, true );

		wp_register_script( 'unicamp-widget-testimonial-carousel-3d', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/widget-testimonial-carousel-3d.js', array(
			'elementor-frontend',
			'unicamp-vertical-carousel-3d',
		), null, true );
	}

	public function get_name() {
		return 'tm-testimonial-carousel-3d';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel 3D', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-slider-3d';
	}

	public function get_keywords() {
		return [ 'testimonial', 'carousel', 'vertical', '3d' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-testimonial-carousel-3d' ];
	}

	protected function register_controls() {
		$this->add_content_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'slides_section', [
			'label' => esc_html__( 'Slides', 'unicamp' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'content', [
			'label' => esc_html__( 'Content', 'unicamp' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'image', [
			'label' => esc_html__( 'Avatar', 'unicamp' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'name', [
			'label'   => esc_html__( 'Name', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'John Doe', 'unicamp' ),
		] );

		$repeater->add_control( 'position', [
			'label'   => esc_html__( 'Position', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'CEO', 'unicamp' ),
		] );

		$placeholder_image_src = Utils::get_placeholder_image_src();

		$this->add_control( 'slides', [
			'label'     => esc_html__( 'Slides', 'unicamp' ),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default'   => [
				[
					'content'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'name'     => 'Frankie Kao',
					'position' => 'Web Design',
					'image'    => [ 'url' => $placeholder_image_src ],
				],
				[
					'content'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'name'     => 'Frankie Kao',
					'position' => 'Web Design',
					'image'    => [ 'url' => $placeholder_image_src ],
				],
				[
					'content'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'name'     => 'Frankie Kao',
					'position' => 'Web Design',
					'image'    => [ 'url' => $placeholder_image_src ],
				],
			],
			'separator' => 'after',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'    => 'image_size',
			'default' => 'full',
		] );

		$this->end_controls_section();
	}

	private function print_testimonial_cite() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['name'] ) && empty( $slide['position'] ) ) {
			return;
		}

		$html = '<div class="cite">';

		if ( ! empty( $slide['name'] ) ) {
			$html .= '<h6 class="name">' . $slide['name'] . '</h6>';
		}
		if ( ! empty( $slide['position'] ) ) {
			$html .= '<span class="position">' . $slide['position'] . '</span>';
		}
		$html .= '</div>';

		echo '' . $html;
	}

	private function print_testimonial_avatar() {
		$slide = $this->get_current_slide();
		if ( empty( $slide['image']['url'] ) ) {
			return;
		}
		?>
		<div class="image">
			<?php echo \Unicamp_Image::get_elementor_attachment( [
				'settings'       => $slide,
				'image_size_key' => 'image_size',
			] ); ?>
		</div>
		<?php
	}

	private function print_layout() {
		$slide = $this->get_current_slide();
		?>
		<?php if ( $slide['content'] ) : ?>
			<div class="content">
				<?php if ( ! empty( $slide['title'] ) ): ?>
					<h4 class="title"><?php echo esc_html( $slide['title'] ); ?></h4>
				<?php endif; ?>

				<div class="text">
					<?php echo wp_kses( $slide['content'], 'unicamp-default' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="info">
			<?php $this->print_testimonial_avatar(); ?>

			<?php $this->print_testimonial_cite(); ?>
		</div>
		<?php
	}

	protected function get_current_slide() {
		return $this->current_slide;
	}

	protected function get_current_key() {
		return $this->current_key;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="carousel-container carousel-vertical-3d">
			<div class="carousel-items testimonials-quotes">

				<?php
				foreach ( $settings['slides'] as $slide ) :
					$item_id = $slide['_id'];
					$item_key = 'item_' . $item_id;

					$this->current_key   = $item_key;
					$this->current_slide = $slide;

					$this->add_render_attribute( $item_key, [
						'class' => [
							'carousel-item',
							'elementor-repeater-item-' . $item_id,
						],
					] );

					$this->add_render_attribute( $item_key . '-testimonial', [
						'class' => 'testimonial-item',
					] );
					?>
					<div <?php $this->print_attributes_string( $item_key ); ?>>
						<div <?php $this->print_attributes_string( $item_key . '-testimonial' ); ?>>
							<div class="testimonial-main-content">
								<div class="content-wrap">
									<?php $this->print_layout(); ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach;
				?>
			</div>
		</div>
		<?php
	}
}
