<?php

namespace Unicamp_Elementor;

defined( 'ABSPATH' ) || exit;

class WPML_Translatable_Nodes {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'init', [ $this, 'wp_init' ] );
	}

	public function wp_init() {
		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'wpml_widgets_to_translate_filter' ] );
	}

	public function get_translatable_node() {
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-google-map.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-list.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-attribute-list.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-pricing-table.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-table.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-modern-carousel.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-rich-carousel.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-modern-slider.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-team-member-carousel.php';
		require_once UNICAMP_ELEMENTOR_DIR . '/wpml/class-translate-widget-testimonial-carousel.php';

		$widgets['tm-attribute-list'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Attribute_List',
		];

		$widgets['tm-heading'] = [
			'fields' => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Modern Heading: Primary', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				'title_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Modern Heading: Link', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Modern Heading: Description', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'sub_title_text',
					'type'        => esc_html__( 'Modern Heading: Secondary', 'unicamp' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$widgets['tm-button'] = [
			'fields' => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Button: Text', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Button: Badge', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Button: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$widgets['tm-banner'] = [
			'fields' => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Banner: Title', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Banner: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$widgets['tm-circle-progress-chart'] = [
			'fields' => [
				[
					'field'       => 'inner_content_text',
					'type'        => esc_html__( 'Circle Chart: Text', 'unicamp' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$widgets['tm-flip-box'] = [
			'fields' => [
				[
					'field'       => 'title_text_a',
					'type'        => esc_html__( 'Flip Box: Front Title', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text_a',
					'type'        => esc_html__( 'Flip Box: Front Description', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'title_text_b',
					'type'        => esc_html__( 'Flip Box: Back Title', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text_b',
					'type'        => esc_html__( 'Flip Box: Back Description', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Flip Box: Button Text', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Flip Box: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$widgets['tm-google-map'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Google_Map',
		];

		$widgets['tm-icon'] = [
			'fields' => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Icon: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$widgets['tm-icon-box'] = [
			'fields' => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Icon Box: Title', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Icon Box: Description', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				'link'        => [
					'field'       => 'url',
					'type'        => esc_html__( 'Icon Box: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Icon Box: Button', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				'button_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Icon Box: Button Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$widgets['tm-image-box'] = [
			'fields' => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Image Box: Title', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Image Box: Content', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Image Box: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Image Box: Button', 'unicamp' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$widgets['tm-list'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_List',
		];

		$widgets['tm-popup-video'] = [
			'fields' => [
				[
					'field'       => 'video_text',
					'type'        => esc_html__( 'Popup Video: Text', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				'video_url' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Popup Video: Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'poster_caption',
					'type'        => esc_html__( 'Popup Video: Caption', 'unicamp' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$widgets['tm-pricing-table'] = [
			'fields'            => [
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Pricing Table: Heading', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_heading',
					'type'        => esc_html__( 'Pricing Table: Description', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'currency',
					'type'        => esc_html__( 'Pricing Table: Currency', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'price',
					'type'        => esc_html__( 'Pricing Table: Price', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'period',
					'type'        => esc_html__( 'Pricing Table: Period', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Pricing Table: Button', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				'button_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Pricing Table: Button Link', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Pricing_Table',
		];

		$widgets['tm-table'] = [
			'fields'            => [],
			'integration-class' => [
				'\Unicamp_Elementor\Translate_Widget_Pricing_Table_Head',
				'\Unicamp_Elementor\Translate_Widget_Pricing_Table_Body',
			],
		];

		$widgets['tm-team-member'] = [
			'fields' => [
				[
					'field'       => 'name',
					'type'        => esc_html__( 'Team Member: Name', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Team Member: Content', 'unicamp' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'position',
					'type'        => esc_html__( 'Team Member: Position', 'unicamp' ),
					'editor_type' => 'LINE',
				],
				'profile' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Team Member: Profile', 'unicamp' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$widgets['tm-modern-carousel'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Modern_Carousel',
		];

		$widgets['tm-rich-carousel'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Rich_Carousel',
		];

		$widgets['tm-modern-slider'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Modern_Slider',
		];

		$widgets['tm-team-member-carousel'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Team_Member_Carousel',
		];

		$widgets['tm-testimonial-carousel'] = [
			'fields'            => [],
			'integration-class' => '\Unicamp_Elementor\Translate_Widget_Testimonial_Carousel',
		];

		return $widgets;
	}

	public function wpml_widgets_to_translate_filter( $widgets ) {
		$unicamp_widgets = $this->get_translatable_node();

		foreach ( $unicamp_widgets as $widget_name => $widget ) {
			$widgets[ $widget_name ]               = $widget;
			$widgets[ $widget_name ]['conditions'] = [
				'widgetType' => $widget_name,
			];
		}

		return $widgets;
	}
}

WPML_Translatable_Nodes::instance()->initialize();
