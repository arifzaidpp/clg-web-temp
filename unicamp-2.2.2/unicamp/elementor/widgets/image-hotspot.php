<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Image_Hotspot extends Base {

	const POST_TYPE = 'points_image';

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'unicamp-widget-image-hotspot', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/widget-image-hotspot.js', array(
			'elementor-frontend',
			'powertip',
		), null, true );
	}

	public function get_script_depends() {
		return [ 'unicamp-widget-image-hotspot' ];
	}

	public function get_name() {
		return 'tm-image-hotspot';
	}

	public function get_title() {
		return esc_html__( 'Image Hotspot', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-hotspot';
	}

	public function get_keywords() {
		return [ 'image', 'hotspot' ];
	}

	public function get_hotspot_list() {
		$query_args = array(
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
		);

		$results = array();

		$query = new \WP_Query( $query_args );

		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				$title               = get_the_title();
				$post_id             = get_the_ID();
				$results[ $post_id ] = $title;
			endwhile;
			wp_reset_postdata();
		endif;

		return $results;
	}

	protected function register_controls() {
		$this->add_hotspot_section();
	}

	private function add_hotspot_section() {
		$this->start_controls_section( 'image_hotspot_section', [
			'label' => esc_html__( 'Image Hotspot', 'unicamp' ),
		] );

		$this->add_control( 'hotspot_id', [
			'label'   => esc_html__( 'Hotspot', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_hotspot_list(),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''   => esc_html__( 'None', 'unicamp' ),
				'01' => '01',
				'02' => '02',
			],
			'default' => '',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['hotspot_id'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'unicamp-image-hotspot' );
		$this->add_render_attribute( 'wrapper', 'class', 'style-' . $settings['style'] );

		$shortcode_string = '[tm_image_hotspot id="' . $settings['hotspot_id'] . '"][/tm_image_hotspot]';
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php echo do_shortcode( $shortcode_string ); ?>
		</div>
		<?php
	}
}
