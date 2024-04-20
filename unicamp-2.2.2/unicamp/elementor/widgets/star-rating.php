<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Star_Rating extends Base {

	public function get_name() {
		return 'tm-star-rating';
	}

	public function get_title() {
		return esc_html__( 'Star Rating', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-rating';
	}

	public function get_keywords() {
		return [ 'star', 'rating' ];
	}

	protected function register_controls() {
		$this->add_content_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'rating_section', [
			'label' => esc_html__( 'Rating', 'unicamp' ),
		] );

		$this->add_control( 'rating', [
			'label'   => esc_html__( 'Rating', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 5,
			'step'    => 0.1,
			'default' => 5,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'rating_count', [
			'label'   => esc_html__( 'Rating Count', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'star_style', [
			'label'   => esc_html__( 'Star Style', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '01',
			'options' => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
			],
		] );

		$this->add_control( 'rating_title', [
			'label' => esc_html__( 'Title', 'unicamp' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->add_control( 'rating_description', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['rating'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-widget-star-rating' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="rating-wrap">
				<?php \Unicamp_Templates::render_rating( $settings['rating'], [
					'style'         => $settings['star_style'],
					'wrapper_class' => 'rating-score',
				] ) ?>
				<?php if ( ! empty( $settings['rating_count'] ) ) : ?>
					<div
						class="rating-count"><?php printf( '(%1$s)', number_format_i18n( $settings['rating_count'] ) ); ?></div>
				<?php endif; ?>
			</div>
			<div class="rating-info">
				<?php if ( ! empty( $settings['rating_title'] ) ) : ?>
					<span class="rating-title heading"><?php echo esc_html( $settings['rating_title'] ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $settings['rating_description'] ) ) : ?>
					<span class="rating-description"><?php echo esc_html( $settings['rating_description'] ); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
