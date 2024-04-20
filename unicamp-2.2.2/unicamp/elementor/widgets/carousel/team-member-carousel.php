<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Team_Member_Carousel extends Static_Carousel {

	public function get_name() {
		return 'tm-team-member-carousel';
	}

	public function get_title() {
		return esc_html__( 'Team Member Carousel', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'team', 'member', 'avatar', 'carousel' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		parent::register_controls();

		$this->add_image_style_section();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_control( 'slides', [
			'title_field' => '{{{ name }}}',
		] );
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

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'images_style_section', [
			'label' => esc_html__( 'Images', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->start_controls_tabs( 'images_effects' );

		$this->start_controls_tab( 'images_effects_normal_tab', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .unicamp-image img',
		] );

		$this->add_control( 'images_opacity', [
			'label'     => esc_html__( 'Opacity', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'images_effects_hover_tab', [
			'label' => esc_html__( 'Hover', 'unicamp' ),
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .unicamp-box:hover .unicamp-image img',
		] );

		$this->add_control( 'images_opacity_hover', [
			'label'     => esc_html__( 'Opacity', 'unicamp' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-box:hover .unicamp-image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control( 'name', [
			'label'   => esc_html__( 'Name', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'John Doe', 'unicamp' ),
		] );

		$repeater->add_control( 'content', [
			'label' => esc_html__( 'Content', 'unicamp' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'image', [
			'label' => esc_html__( 'Image', 'unicamp' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'position', [
			'label'   => esc_html__( 'Position', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'CEO', 'unicamp' ),
		] );

		$repeater->add_control( 'profile', [
			'label'         => esc_html__( 'Profile', 'unicamp' ),
			'type'          => Controls_Manager::URL,
			'placeholder'   => esc_html__( 'https://your-link.com', 'unicamp' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => true,
				'nofollow'    => true,
			],
		] );

		$repeater->add_control( 'socials', [
			'label'       => esc_html__( 'Socials', 'unicamp' ),
			'description' => esc_html__( 'One social per line. Icon class & url separator with |. For eg: fab fa-facebook|https://facebook.com', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
		] );
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'name'     => esc_html__( 'Frankie Kao', 'unicamp' ),
				'position' => esc_html__( 'CEO', 'unicamp' ),
				'image'    => [ 'url' => $placeholder_image_src ],
			],
			[
				'name'     => esc_html__( 'Frankie Kao', 'unicamp' ),
				'position' => esc_html__( 'CEO', 'unicamp' ),
				'image'    => [ 'url' => $placeholder_image_src ],
			],
			[
				'name'     => esc_html__( 'Frankie Kao', 'unicamp' ),
				'position' => esc_html__( 'CEO', 'unicamp' ),
				'image'    => [ 'url' => $placeholder_image_src ],
			],
		];
	}

	protected function print_slide() {
		$slide = $this->get_current_slide();
		$current_key   = $this->get_current_key();
		$item_link_key = $current_key . '_link';

		if ( ! empty( $slide['profile']['url'] ) ) {
			$this->add_link_attributes( $item_link_key, $slide['profile'] );
		}
		?>
		<div class="tm-team-member unicamp-box">
			<div class="item">
				<?php if ( $slide['image']['url'] ) : ?>
					<div class="photo unicamp-image">
						<?php echo \Unicamp_Image::get_elementor_attachment( [
							'settings'       => $slide,
							'image_size_key' => 'image_size',
						] ); ?>

						<div class="overlay"></div>

						<?php $this->print_socials(); ?>
					</div>
				<?php endif; ?>

				<div class="info">
					<h3 class="name">
						<?php if ( ! empty( $slide['profile']['url'] ) ) { ?>
							<a <?php $this->print_render_attribute_string( $item_link_key ); ?>><?php echo esc_html( $slide['name'] ); ?></a>
						<?php } else {
							echo esc_html( $slide['name'] );
						} ?>
					</h3>

					<?php if ( ! empty( $slide['position'] ) ) : ?>
						<div class="position"><?php echo esc_html( $slide['position'] ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $slide['content'] ) ) : ?>
						<div class="description">
							<?php echo wp_kses( $slide['content'], 'unicamp-default' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_socials() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['socials'] ) ) {
			return;
		}

		$socials = explode( "\n", str_replace( "\r", "", $slide['socials'] ) );
		?>
		<div class="social-networks">
			<div class="inner">
				<?php
				foreach ( $socials as $social ) {
					$item = explode( '|', $social );
					if ( empty( $item ) || count( $item ) < 2 ) {
						continue;
					}
					$icon_class = $item[0];
					$url        = $item[1];
					?>
					<a href="<?php esc_url( $url ); ?>" target="_blank" rel="nofollow">
						<span class="link-icon <?php echo esc_attr( $icon_class ); ?>"></span>
					</a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$this->add_render_attribute( 'wrapper', 'class', 'tm-team-member-carousel' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->print_slider(); ?>
		</div>
		<?php
	}
}
