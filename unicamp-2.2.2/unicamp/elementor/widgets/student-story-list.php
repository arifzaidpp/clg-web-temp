<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Student_Story_List extends Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'unicamp-widget-student-story-list', UNICAMP_ELEMENTOR_URI . '/assets/js/widgets/widget-student-story-list.js', array(
			'elementor-frontend',
		), null, true );
	}

	public function get_name() {
		return 'tm-student-story-list';
	}

	public function get_title() {
		return esc_html__( 'Student Story List', 'unicamp' );
	}

	public function get_icon_part() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return [ 'story', 'stories' ];
	}

	public function get_script_depends() {
		return [
			'unicamp-widget-student-story-list',
		];
	}

	protected function register_controls() {
		$this->add_content_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'unicamp' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Text', 'unicamp' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'thumbnail', [
			'label'   => esc_html__( 'Thumbnail', 'unicamp' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
		] );

		$repeater->add_control( 'student_name', [
			'label'       => esc_html__( 'Student Name', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater->add_control( 'student_level', [
			'label'       => esc_html__( 'Student Level', 'unicamp' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$this->add_control( 'view_all_link', [
			'label'       => esc_html__( 'View All Link', 'unicamp' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'unicamp' ),
			'separator'   => 'before',
		] );

		$placeholder_image_src = Utils::get_placeholder_image_src();

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'unicamp' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'         => 'My Experience with General Education',
					'thumbnail'     => [
						'url' => $placeholder_image_src,
					],
					'student_name'  => 'Morgan McLaughlin',
					'student_level' => 'Graduate Student',
				],
				[
					'title'         => 'My top 5 UniCamp moments',
					'thumbnail'     => [
						'url' => $placeholder_image_src,
					],
					'student_name'  => 'Denise Henderson',
					'student_level' => 'Undergraduate Student',
				],
				[
					'title'         => 'Should you consider taking a gap year',
					'thumbnail'     => [
						'url' => $placeholder_image_src,
					],
					'student_name'  => 'Charlie Doyle',
					'student_level' => 'Graduate Student',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'    => 'thumbnail_size',
			'default' => 'full',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['items'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', [
			'unicamp-student-story-list',
		] );

		$stories = $settings['items'];
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php
			$loop_count         = 0;
			$left_col_template  = '';
			$right_col_template = '';
			?>
			<?php foreach ( $stories as $story ) : ?>
				<?php
				$loop_count++;

				$item_id  = $story['_id'];
				$item_key = 'item_' . $item_id;

				$item_tag = 'div';

				$this->add_render_attribute( $item_key, [
					'class'   => 'post-item link-secret',
					'data-id' => $item_id,
				] );

				if ( ! empty( $story['link']['url'] ) ) {
					$item_tag = 'a';
					$this->add_link_attributes( $item_key, $story['link'] );
				}
				?>

				<?php ob_start(); ?>
				<div class="student-story-image-item <?php echo esc_attr( 'post-' . $item_id ); ?>">
					<?php echo \Unicamp_Image::get_elementor_attachment( [
						'settings'       => $story,
						'image_key'      => 'thumbnail',
						'size_settings'  => $settings,
						'image_size_key' => 'thumbnail_size',
					] ); ?>
				</div>
				<?php $left_col_template .= ob_get_clean(); ?>

				<?php ob_start(); ?>
				<?php printf( '<%1$s %2$s>', $item_tag, $this->get_render_attribute_string( $item_key ) ); ?>
				<div class="post-count">
					<?php $number = str_pad( $loop_count, 2, '0', STR_PAD_LEFT ); ?>
					<?php echo esc_html( $number ); ?>
				</div>
				<div class="post-caption">
					<?php if ( ! empty( $story['title'] ) ) : ?>
						<h3 class="post-title"><?php echo esc_html( $story['title'] ) ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $story['student_name'] ) || ! empty( $story['student_level'] ) ) : ?>
						<div class="post-meta">
							<div class="meta-list">
								<?php if ( ! empty( $story['student_name'] ) ) : ?>
									<div
										class="meta-item student-name"><?php echo esc_html( $story['student_name'] ); ?></div>
								<?php endif; ?>
								<?php if ( ! empty( $story['student_level'] ) ) : ?>
									<div
										class="meta-item student-level"><?php echo esc_html( $story['student_level'] ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<?php printf( '</%1$s>', $item_tag ); ?>
				<?php $right_col_template .= ob_get_clean(); ?>
			<?php endforeach; ?>

			<div class="student-story-image-list-wrap">
				<div class="student-story-image-list">
					<?php \Unicamp_Helper::e( $left_col_template ); ?>
				</div>
			</div>
			<div class="student-story-list-wrap">
				<div class="student-story-list">
					<?php \Unicamp_Helper::e( $right_col_template ); ?>

					<?php if ( ! empty( $settings['view_all_link'] ) && ! empty( $settings['view_all_link']['url'] ) ) : ?>
						<?php
						$this->add_link_attributes( 'view_all', $settings['view_all_link'] );
						$this->add_render_attribute( 'view_all', 'class', 'btn-view-more' );
						?>
						<div class="box-footer">
							<a <?php $this->print_render_attribute_string( 'view_all' ); ?>>
								<span class="fal fa-angle-double-down"></span>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
