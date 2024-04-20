<?php

namespace Unicamp_Elementor;

defined( 'ABSPATH' ) || exit;

class Widget_Utils {
	public static function get_control_options_horizontal_alignment() {
		return [
			'left'   => [
				'title' => esc_html__( 'Left', 'unicamp' ),
				'icon'  => 'eicon-h-align-left',
			],
			'center' => [
				'title' => esc_html__( 'Center', 'unicamp' ),
				'icon'  => 'eicon-h-align-center',
			],
			'right'  => [
				'title' => esc_html__( 'Right', 'unicamp' ),
				'icon'  => 'eicon-h-align-right',
			],
		];
	}

	public static function get_control_options_horizontal_alignment_full() {
		return [
			'left'    => [
				'title' => esc_html__( 'Left', 'unicamp' ),
				'icon'  => 'eicon-h-align-left',
			],
			'center'  => [
				'title' => esc_html__( 'Center', 'unicamp' ),
				'icon'  => 'eicon-h-align-center',
			],
			'right'   => [
				'title' => esc_html__( 'Right', 'unicamp' ),
				'icon'  => 'eicon-h-align-right',
			],
			'stretch' => [
				'title' => esc_html__( 'Stretch', 'unicamp' ),
				'icon'  => 'eicon-h-align-stretch',
			],
		];
	}

	public static function get_control_options_vertical_alignment() {
		return [
			'top'    => [
				'title' => esc_html__( 'Top', 'unicamp' ),
				'icon'  => 'eicon-v-align-top',
			],
			'middle' => [
				'title' => esc_html__( 'Middle', 'unicamp' ),
				'icon'  => 'eicon-v-align-middle',
			],
			'bottom' => [
				'title' => esc_html__( 'Bottom', 'unicamp' ),
				'icon'  => 'eicon-v-align-bottom',
			],
		];
	}

	public static function get_control_options_vertical_full_alignment() {
		return [
			'top'     => [
				'title' => esc_html__( 'Top', 'unicamp' ),
				'icon'  => 'eicon-v-align-top',
			],
			'middle'  => [
				'title' => esc_html__( 'Middle', 'unicamp' ),
				'icon'  => 'eicon-v-align-middle',
			],
			'bottom'  => [
				'title' => esc_html__( 'Bottom', 'unicamp' ),
				'icon'  => 'eicon-v-align-bottom',
			],
			'stretch' => [
				'title' => esc_html__( 'Stretch', 'unicamp' ),
				'icon'  => 'eicon-v-align-stretch',
			],
		];
	}

	public static function get_control_options_text_align() {
		return [
			'left'   => [
				'title' => esc_html__( 'Start', 'unicamp' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center' => [
				'title' => esc_html__( 'Center', 'unicamp' ),
				'icon'  => 'eicon-text-align-center',
			],
			'right'  => [
				'title' => esc_html__( 'End', 'unicamp' ),
				'icon'  => 'eicon-text-align-right',
			],
		];
	}

	public static function get_control_options_text_align_full() {
		return [
			'left'    => [
				'title' => esc_html__( 'Start', 'unicamp' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center'  => [
				'title' => esc_html__( 'Center', 'unicamp' ),
				'icon'  => 'eicon-text-align-center',
			],
			'right'   => [
				'title' => esc_html__( 'End', 'unicamp' ),
				'icon'  => 'eicon-text-align-right',
			],
			'justify' => [
				'title' => esc_html__( 'Justified', 'unicamp' ),
				'icon'  => 'eicon-text-align-justify',
			],
		];
	}

	public static function get_button_style() {
		return [
			'flat'         => esc_html__( 'Flat', 'unicamp' ),
			'border'       => esc_html__( 'Border', 'unicamp' ),
			'thick-border' => esc_html__( 'Thick Border', 'unicamp' ),
			'text'         => esc_html__( 'Text', 'unicamp' ),
			'bottom-line'  => esc_html__( 'Bottom Line', 'unicamp' ),
			'left-line'    => esc_html__( 'Left Line', 'unicamp' ),
		];
	}

	/**
	 * Get recommended social icons for control ICONS.
	 *
	 * @return array
	 */
	public static function get_recommended_social_icons() {
		return [
			'fa-brands' => [
				'android',
				'apple',
				'behance',
				'bitbucket',
				'codepen',
				'delicious',
				'deviantart',
				'digg',
				'dribbble',
				'elementor',
				'facebook',
				'flickr',
				'foursquare',
				'free-code-camp',
				'github',
				'gitlab',
				'globe',
				'houzz',
				'instagram',
				'jsfiddle',
				'linkedin',
				'medium',
				'meetup',
				'mix',
				'mixcloud',
				'odnoklassniki',
				'pinterest',
				'product-hunt',
				'reddit',
				'shopping-cart',
				'skype',
				'slideshare',
				'snapchat',
				'soundcloud',
				'spotify',
				'stack-overflow',
				'steam',
				'telegram',
				'thumb-tack',
				'tripadvisor',
				'tumblr',
				'twitch',
				'twitter',
				'viber',
				'vimeo',
				'vk',
				'weibo',
				'weixin',
				'whatsapp',
				'wordpress',
				'xing',
				'yelp',
				'youtube',
				'500px',
			],
		];
	}

	public static function get_grid_metro_size() {
		return [
			'1:1'   => esc_html__( 'Width 1 - Height 1', 'unicamp' ),
			'1:2'   => esc_html__( 'Width 1 - Height 2', 'unicamp' ),
			'1:0.7' => esc_html__( 'Width 1 - Height 70%', 'unicamp' ),
			'1:1.3' => esc_html__( 'Width 1 - Height 130%', 'unicamp' ),
			'2:1'   => esc_html__( 'Width 2 - Height 1', 'unicamp' ),
			'2:2'   => esc_html__( 'Width 2 - Height 2', 'unicamp' ),
		];
	}
}
