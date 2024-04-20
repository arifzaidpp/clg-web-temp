<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Zoom_Meeting extends Base {

	public function get_name() {
		return 'tm-zoom-meeting';
	}

	public function get_title() {
		return esc_html__( 'Zoom Meeting', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-meetup';
	}

	public function get_keywords() {
		return [ 'zoom meeting', 'meeting zoom', 'meetup', 'zoom us' ];
	}

	protected function register_controls() {
		$this->add_content_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$this->add_control( 'meeting_id', [
			'label'       => esc_html__( 'Meeting ID', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$meeting_id = $settings['meeting_id'];

		if( empty($meeting_id) ) {
			return;
		}

		echo do_shortcode( '[tm_zoom_meeting meeting_id="' . $meeting_id . '"]' );
	}
}
