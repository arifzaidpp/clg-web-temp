<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Widget_Typed_Headline extends Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'typed', UNICAMP_THEME_URI . '/assets/libs/typed/typed.min.js', array(), null, true );

		wp_register_script( 'unicamp-widget-typed-headline', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/widget-typed-headline.js', array(
			'elementor-frontend',
			'typed',
		), null, true );
	}

	public function get_name() {
		return 'tm-typed-headline';
	}

	public function get_title() {
		return esc_html__( 'Typed Headline', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-heading';
	}

	public function get_keywords() {
		return [ 'heading', 'title', 'text' ];
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-typed-headline' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_wrapper_style_section();

		$this->add_headline_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'before_text', [
			'label'       => esc_html__( 'Before Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active'     => true,
				'categories' => [
					TagsModule::TEXT_CATEGORY,
				],
			],
			'default'     => esc_html__( 'This page is', 'unicamp' ),
			'placeholder' => esc_html__( 'Enter your headline', 'unicamp' ),
			'label_block' => true,
			'separator'   => 'before',
		] );

		$this->add_control( 'text', [
			'label'              => esc_html__( 'Text', 'unicamp' ),
			'type'               => Controls_Manager::TEXTAREA,
			'placeholder'        => esc_html__( 'Enter each word in a separate line', 'unicamp' ),
			'separator'          => 'none',
			'default'            => "Better\nBigger\nFaster",
			'frontend_available' => true,
		] );

		$this->add_control( 'after_text', [
			'label'       => __( 'After Text', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active'     => true,
				'categories' => [
					TagsModule::TEXT_CATEGORY,
				],
			],
			'placeholder' => __( 'Enter your headline', 'unicamp' ),
			'label_block' => true,
			'separator'   => 'none',
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'HTML Tag', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default' => 'h2',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'unicamp' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->end_controls_section();
	}

	private function add_wrapper_style_section() {
		$this->start_controls_section( 'wrapper_style_section', [
			'tab'   => Controls_Manager::TAB_STYLE,
			'label' => esc_html__( 'Wrapper', 'unicamp' ),
		] );

		$this->add_responsive_control( 'align', [
			'label'                => esc_html__( 'Text Align', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}}' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'max_width', [
			'label'          => esc_html__( 'Max Width', 'unicamp' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .tm-typed-headline' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'                => esc_html__( 'Alignment', 'unicamp' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->end_controls_section();
	}

	private function add_headline_style_section() {
		$this->start_controls_section( 'headline_style_section', [
			'label' => esc_html__( 'Headline', 'unicamp' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'headline',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .unicamp-headline',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'text_stroke',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .unicamp-headline',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'text_shadow',
			'selector' => '{{WRAPPER}} .unicamp-headline',
		] );

		$this->add_control( 'blend_mode', [
			'label'     => esc_html__( 'Blend Mode', 'unicamp' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				''            => esc_html__( 'Normal', 'unicamp' ),
				'multiply'    => 'Multiply',
				'screen'      => 'Screen',
				'overlay'     => 'Overlay',
				'darken'      => 'Darken',
				'lighten'     => 'Lighten',
				'color-dodge' => 'Color Dodge',
				'saturation'  => 'Saturation',
				'color'       => 'Color',
				'difference'  => 'Difference',
				'exclusion'   => 'Exclusion',
				'hue'         => 'Hue',
				'luminosity'  => 'Luminosity',
			],
			'selectors' => [
				'{{WRAPPER}} .unicamp-headline' => 'mix-blend-mode: {{VALUE}}',
			],
			'separator' => 'none',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'headline',
			'selector' => '{{WRAPPER}} .unicamp-headline',
		] );

		$this->add_control( 'animated_text_heading', [
			'label'     => esc_html__( 'Animated Text', 'unicamp' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'animated_text',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .headline-animate-text',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'animated_text_text_stroke',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .headline-animate-text',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'animated_text',
			'selector' => '{{WRAPPER}} .headline-animate-text',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-typed-headline' );

		$this->add_render_attribute( 'headline', 'class', 'unicamp-headline' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php printf( '<%1$s %2$s>', $settings['title_size'], $this->get_render_attribute_string( 'headline' ) ); ?>
			<?php $this->print_before_text(); ?>
			<?php $this->print_animate_text(); ?>
			<?php $this->print_after_text(); ?>
			<?php printf( '</%1$s>', $settings['title_size'] ); ?>
		</div>
		<?php
	}

	private function print_before_text() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['before_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'before_text', 'class', 'headline-part headline-before-text' );
		?>
		<span <?php $this->print_render_attribute_string( 'before_text' ) ?>>
			<?php echo esc_html( $settings['before_text'] ); ?>
		</span>
		<?php
	}

	private function print_after_text() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['after_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'after_text', 'class', 'headline-part headline-after-text' );
		?>
		<span <?php $this->print_render_attribute_string( 'after_text' ) ?>>
			<?php echo esc_html( $settings['after_text'] ); ?>
		</span>
		<?php
	}

	private function print_animate_text() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['text'] ) ) {
			return;
		}

		$words = explode( "\n", str_replace( "\r", "", $settings['text'] ) );

		$this->add_render_attribute( 'animate_text', 'class', 'animate-text' );
		$this->add_render_attribute( 'animate_text', 'data-typed', wp_json_encode( $words ) );
		?>
		<div class="headline-part headline-animate-text">
			<span <?php $this->print_render_attribute_string( 'animate_text' ) ?>><?php echo esc_html( $words['0'] ); ?></span>
		</div>
		<?php
	}
}
