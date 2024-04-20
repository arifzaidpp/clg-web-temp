<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Social_News extends Static_Grid {

	public function get_name() {
		return 'tm-social-news';
	}

	public function get_title() {
		return esc_html__( 'Social News', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return [ 'social' ];
	}

	protected function register_controls() {
		parent::register_controls();

		$this->add_content_style_section();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_control( 'items', [
			'title_field' => '{{{ text }}}',
		] );
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_min_height', [
			'label'      => esc_html__( 'Min Height', 'unicamp' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 1000,
				],
			],
			'size_units' => [ 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .unicamp-box' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'selectors'            => [
				'{{WRAPPER}} .unicamp-box' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'unicamp' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'unicamp' ),
			'selector' => '{{WRAPPER}} .heading',
		] );

		$this->end_controls_section();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->start_controls_tabs( 'grid_items_tabs' );

		$repeater->start_controls_tab( 'item_content_tab', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$repeater->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'facebook' => 'Facebook',
				'twitter'  => 'Twitter',
			],
			'default' => '01',
		] );

		$repeater->add_control( 'title', [
			'label'   => esc_html__( 'Title', 'unicamp' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'text', [
			'label'   => esc_html__( 'Text', 'unicamp' ),
			'type'    => Controls_Manager::TEXTAREA,
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'date', [
			'label' => esc_html__( 'Date', 'unicamp' ),
			'type'  => Controls_Manager::DATE_TIME,
		] );

		$repeater->add_control( 'link', [
			'label'         => esc_html__( 'Link', 'unicamp' ),
			'type'          => Controls_Manager::URL,
			'placeholder'   => esc_html__( 'https://your-link.com', 'unicamp' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
		] );

		$repeater->add_control( 'comments_number', [
			'label'   => esc_html__( 'Comments Number', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'likes_number', [
			'label'   => esc_html__( 'Likes Number', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'item_style_tab', [
			'label' => esc_html__( 'Style', 'unicamp' ),
		] );

		$repeater->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .unicamp-box',
			'separator' => 'before',
		] );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();
	}

	protected function get_repeater_defaults() {
		return [
			[
				'title' => '@UniCamp',
				'text'  => 'No matter the challenges to come, we will remain connected through our core values aliquam lorem ante, dapibus in, viverra quis,feugia ...',
			],
			[
				'title' => '@UniCamp',
				'text'  => 'No matter the challenges to come, we will remain connected through our core values aliquam lorem ante, dapibus in, viverra quis,feugia ...',
			],
			[
				'title' => '@UniCamp',
				'text'  => 'No matter the challenges to come, we will remain connected through our core values aliquam lorem ante, dapibus in, viverra quis,feugia ...',
			],
			[
				'title' => '@UniCamp',
				'text'  => 'No matter the challenges to come, we will remain connected through our core values aliquam lorem ante, dapibus in, viverra quis,feugia ...',
			],
			[
				'title' => '@UniCamp',
				'text'  => 'No matter the challenges to come, we will remain connected through our core values aliquam lorem ante, dapibus in, viverra quis,feugia ...',
			],
			[
				'title' => '@UniCamp',
				'text'  => 'No matter the challenges to come, we will remain connected through our core values aliquam lorem ante, dapibus in, viverra quis,feugia ...',
			],
		];
	}

	protected function print_grid_item() {
		$item     = $this->get_current_item();
		$item_key = $this->get_current_key();

		$box_tag = 'div';
		$box_key = $item_key . '_box';

		$this->add_render_attribute( $box_key, 'class', 'unicamp-box' );

		if ( ! empty( $item['link']['url'] ) ) {
			$box_tag = 'a';

			$this->add_render_attribute( $box_key, 'class', 'link-secret' );
			$this->add_link_attributes( $box_key, $item['link'] );
		}

		$like_css_class = 'fa-thumbs-up';

		$style = $item['style'];

		if ( $style ) {
			switch ( $style ) {
				case 'twitter' :
					$like_css_class = 'fa-heart';
					break;
			}
		}

		$like_css_class = 'fal ' . $like_css_class;
		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( $box_key ) ); ?>
		<span class="social-brand"></span>
		<div class="social-content">
			<?php if ( ! empty( $item['title'] ) ): ?>
				<h4 class="social-heading">
					<?php echo esc_html( $item['title'] ); ?>
				</h4>
			<?php endif; ?>
			<?php if ( ! empty( $item['text'] ) ): ?>
				<div class="social-text">
					<?php echo wp_kses( $item['text'], [
						'mark' => [],
					] ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="social-footer">
			<?php $date = mysql2date( get_option( 'date_format' ), $item['date'] ); ?>
			<?php if ( $date ) : ?>
				<?php echo '<div class="social-date">' . $date . '</div>'; ?>
			<?php endif; ?>
			<div class="social-meta">
				<?php if ( isset( $item['comments_number'] ) && '' !== $item['comments_number'] ) : ?>
					<div class="social-meta-item social-comments">
						<span class="fal fa-comment"></span>
						<?php echo esc_html( $item['comments_number'] ); ?>
					</div>
				<?php endif; ?>
				<?php if ( isset( $item['likes_number'] ) && '' !== $item['likes_number'] ) : ?>
					<div class="social-meta-item social-likes">
						<span class="<?php echo esc_attr( $like_css_class ); ?>"></span>
						<?php echo esc_html( $item['likes_number'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	protected function before_grid() {
		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-social-news' );
	}

	protected function before_grid_item_loop() {
		$item     = $this->get_current_item();
		$item_key = $this->get_current_key();

		$this->add_render_attribute( $item_key, [
			'class' => [
				'style-' . $item['style'],
			],
		] );
	}
}
