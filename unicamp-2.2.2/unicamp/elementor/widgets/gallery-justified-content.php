<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Gallery_Justified_Content extends Base {

	public function get_name() {
		return 'tm-gallery-justified-content';
	}

	public function get_title() {
		return esc_html__( 'Gallery Justified Content', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-gallery-justified';
	}

	public function get_keywords() {
		return [ 'gallery', 'justified' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-gallery-justified-content' ];
	}

	public function get_style_depends() {
		return [ 'justifiedGallery' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'unicamp' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Image', 'unicamp' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Title', 'unicamp' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Description', 'unicamp' ),
			'type'  => Controls_Manager::WYSIWYG,
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
			'default'     => [
				'url' => '#',
			],
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'unicamp' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'       => 'Savanna Walker',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
				[
					'title'       => 'Savanna Walker',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
				[
					'title'       => 'Savanna Walker',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
				[
					'title'       => 'Savanna Walker',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'full',
			'separator' => 'none',
		] );

		$this->end_controls_section();

		$this->add_styling_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'styling_section', [
			'label' => esc_html__( 'Styling', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_heading', [
			'label'     => esc_html__( 'Description', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .description',
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .description' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-gallery-justified-content' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $settings['items'] && count( $settings['items'] ) > 0 ) { ?>
				<div class="unicamp-grid">
					<?php foreach ( $settings['items'] as $key => $item ) {

						$box_key = 'box_' . $item['_id'];
						$box_tag = 'div';

						$this->add_render_attribute( $box_key, 'class', 'unicamp-box' );

						if ( ! empty( $item['link']['url'] ) ) {
							$box_tag = 'a';
							$this->add_render_attribute( $box_key, 'class', 'link-secret' );

							$this->add_link_attributes( $box_key, $item['link'] );
						}
						?>
						<div class="grid-item item">

							<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( $box_key ) ); ?>


							<div class="unicamp-image image">
								<?php echo \Unicamp_Image::get_elementor_attachment( [
									'settings'      => $item,
									'size_settings' => $settings,
								] ); ?>
							</div>

							<div class="overlay"></div>

							<div class="overlay-content">
								<div class="outer">
									<div class="inner">
										<?php if ( ! empty( $item['title'] ) ) : ?>
											<h5 class="title"><?php echo esc_html( $item['title'] ); ?></h5>
										<?php endif; ?>

										<?php if ( isset( $item['description'] ) ) : ?>
											<div class="description">
												<?php echo '' . $this->parse_text_editor( $item['description'] ); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<?php printf( '</%1$s>', $box_tag ); ?>

						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
