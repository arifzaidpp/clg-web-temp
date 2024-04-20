<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Client_Logo_Carousel extends Static_Carousel {

	public function get_name() {
		return 'tm-client-logo-carousel';
	}

	public function get_title() {
		return esc_html__( 'Client Logo Carousel', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-logo';
	}

	public function get_keywords() {
		return [ 'client', 'logo', 'brand' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'hover', [
			'label'   => esc_html__( 'Hover Type', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''          => esc_html__( 'None', 'unicamp' ),
				'grayscale' => esc_html__( 'Grayscale to normal', 'unicamp' ),
				'opacity'   => esc_html__( 'Opacity to normal', 'unicamp' ),
				'faded'     => esc_html__( 'Normal to opacity', 'unicamp' ),
			],
			'default' => '',
		] );

		$this->end_controls_section();

		parent::register_controls();

		$this->update_responsive_control( 'swiper_items', [
			'default'        => '6',
			'tablet_default' => '3',
			'mobile_default' => '2',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 50,
		] );

		$this->update_responsive_control( 'swiper_content_horizontal_align', [
			'default' => 'center',
		] );

		$this->update_responsive_control( 'swiper_content_vertical_align', [
			'default' => 'middle',
		] );
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control( 'logo', [
			'label'   => esc_html__( 'Logo', 'unicamp' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
			'classes' => 'unicamp-control-media-auto',
		] );

		$repeater->add_control( 'link', [
			'label'         => esc_html__( 'Link', 'unicamp' ),
			'type'          => Controls_Manager::URL,
			'placeholder'   => esc_html__( 'https://your-link.com', 'unicamp' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => true,
				'nofollow'    => true,
			],
		] );
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'logo' => [ 'url' => $placeholder_image_src ],
			],
			[
				'logo' => [ 'url' => $placeholder_image_src ],
			],
		];
	}

	protected function print_slide() {
		$item          = $this->get_current_slide();
		$item_key      = $this->get_current_key();
		$item_link_key = $item_key . '_link';

		if ( ! empty( $item['link']['url'] ) ) {
			$this->add_link_attributes( $item_link_key, $item['link'] );
		}
		?>
		<div class="item">
			<?php if ( ! empty( $item['link']['url'] ) ) { ?>
			<a <?php $this->print_render_attribute_string( $item_link_key ); ?>>
				<?php } ?>

				<div class="image">
					<img src="<?php echo esc_url( $item['logo']['url'] ); ?>"
					     alt="<?php esc_attr_e( 'Client Logo', 'unicamp' ); ?>">
				</div>

				<?php if ( ! empty( $item['link']['url'] ) ) { ?>
			</a>
		<?php } ?>
		</div>
		<?php
	}

	protected function before_slider() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( self::SLIDER_KEY, 'class', 'tm-client-logo' );

		if ( ! empty( $settings['hover'] ) ) {
			$this->add_render_attribute( self::SLIDER_KEY, 'class', 'hover-' . $settings['hover'] );
		}
	}
}
