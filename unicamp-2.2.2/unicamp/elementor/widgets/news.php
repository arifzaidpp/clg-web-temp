<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_News extends Base {

	public function get_name() {
		return 'tm-news';
	}

	public function get_title() {
		return esc_html__( 'News', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return [ 'news', 'list' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_thumbnail_style_section();

		//$this->add_content_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'list_section', [
			'label' => esc_html__( 'List', 'unicamp' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'unicamp' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '01',
			'options'      => [
				'01' => '01',
				'02' => '02',
			],
			'prefix_class' => 'unicamp-news-style-',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'label'     => esc_html__( 'Image Size', 'unicamp' ),
			'name'      => 'image',
			'default'   => 'full',
			'separator' => 'before',
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
			'label_block' => true,
		] );

		$repeater->add_control( 'sub_title', [
			'label'       => esc_html__( 'Sub Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater->add_control( 'description', [
			'label'       => esc_html__( 'Description', 'unicamp' ),
			'type'        => Controls_Manager::TEXTAREA,
			'label_block' => true,
		] );

		$repeater->add_control( 'date', [
			'label' => esc_html__( 'Date', 'unicamp' ),
			'type'  => Controls_Manager::DATE_TIME,
		] );

		$repeater->add_control( 'button_text', [
			'label'       => esc_html__( 'Button Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'unicamp' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'       => 'The Way We Live During The Pademic',
					'button_text' => 'Read more',
				],
				[
					'title'       => 'The Way We Live During The Pademic',
					'button_text' => 'Read more',
				],
				[
					'title'       => 'The Way We Live During The Pademic',
					'button_text' => 'Read more',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();
	}

	private function add_thumbnail_style_section() {
		$this->start_controls_section( 'thumbnail_style_section', [
			'label' => esc_html__( 'Thumbnail', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_width', [
			'label'      => esc_html__( 'Width', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min' => 5,
					'max' => 50,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-image' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->start_controls_tabs( 'content_colors' );

		$this->start_controls_tab( 'content_colors_normal', [
			'label' => esc_html__( 'Normal', 'unicamp' ),
		] );



		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['items'] ) ) {
			return;
		}

		$style = $settings['style'];

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-news' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php foreach ( $settings['items'] as $key => $item ) :
				$item_key = 'item_' . $item['_id'];
				$link_key = 'item_link_' . $item['_id'];
				$this->add_render_attribute( $item_key, 'class', [
					'item unicamp-box',
				] );

				$link_url      = ! empty( $item['link']['url'] ) ? $item['link']['url'] : '';
				$link_external = ! empty( $item['link']['is_external'] ) ? true : false;
				$link_rel      = ! empty( $item['link']['nofollow'] ) ? true : false;

				$this->add_link_attributes( $link_key, $item['link'] );


				$has_button  = ! empty( $item['button_text'] ) ? true : false;
				$button_args = [];

				if ( $has_button ) {
					$button_args = [
						'style'      => 'text',
						'text'       => $item['button_text'],
						'icon'       => 'fas fa-long-arrow-right',
						'icon_align' => 'right',
					];

					$button_args['link'] = [
						'url'         => $link_url,
						'is_external' => $link_external,
						'nofollow'    => $link_rel,
					];

					if ( '02' === $style ) {
						$button_args['style'] = 'flat';
					}
				}
				?>
				<div <?php $this->print_attributes_string( $item_key ); ?>>
					<?php if ( $this->has_image( $item['image'] ) ) : ?>
						<div class="news-image unicamp-image">
							<?php
							echo \Unicamp_Image::get_elementor_attachment( [
								'settings'      => $item,
								'size_settings' => $settings,
							] );
							?>
							<?php if ( $has_button && '02' === $settings['style'] ) : ?>
								<?php \Unicamp_Templates::render_button( $button_args ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="news-caption">
						<?php if ( ! empty( $item['sub_title'] ) ) { ?>
							<div class="news-sub-title"><?php echo esc_html( $item['sub_title'] ); ?></div>
						<?php } ?>

						<?php
						if ( ! empty( $item['title'] ) ) {
							$title_html = wp_kses( $item['title'], 'unicamp-default' );

							if ( ! empty( $link_url ) ) {
								$title_html = sprintf( '<a class="link-in-title" %s>%s</a>', $this->get_render_attribute_string( $link_key ), $title_html );
							}

							echo '<h3 class="news-title">' . $title_html . '</h3>';
						}
						?>

						<?php if ( ! empty( $item['description'] ) ) { ?>
							<div class="news-description"><?php echo esc_html( $item['description'] ); ?></div>
						<?php } ?>

						<?php $date = mysql2date( get_option( 'date_format' ), $item['date'] ); ?>
						<?php if ( $date ) : ?>
							<?php echo '<div class="news-date">' . $date . '</div>'; ?>
						<?php endif; ?>

						<?php if ( $has_button && '02' !== $settings['style'] ) : ?>
							<?php \Unicamp_Templates::render_button( $button_args ); ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
