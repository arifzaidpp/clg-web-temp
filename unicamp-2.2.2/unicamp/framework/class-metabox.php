<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Metabox' ) ) {
	class Unicamp_Metabox {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'insight_core_meta_boxes', array( $this, 'register_meta_boxes' ) );
		}

		/**
		 * Register Metabox
		 *
		 * @param $meta_boxes
		 *
		 * @return array
		 */
		public function register_meta_boxes( $meta_boxes ) {
			$page_registered_sidebars = Unicamp_Helper::get_registered_sidebars( true );

			$general_options = array(
				array(
					'title'  => esc_attr__( 'Layout', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'site_layout',
							'type'    => 'select',
							'title'   => esc_html__( 'Layout', 'unicamp' ),
							'desc'    => esc_html__( 'Controls the layout of this page.', 'unicamp' ),
							'options' => array(
								''      => esc_attr__( 'Default', 'unicamp' ),
								'boxed' => esc_attr__( 'Boxed', 'unicamp' ),
								'wide'  => esc_attr__( 'Wide', 'unicamp' ),
							),
							'default' => '',
						),
						array(
							'id'    => 'site_width',
							'type'  => 'text',
							'title' => esc_html__( 'Site Width', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the site width for this page. Enter value including any valid CSS unit. For e.g: 1200px. Leave blank to use global setting.', 'unicamp' ),
						),
						array(
							'id'    => 'site_top_spacing',
							'type'  => 'text',
							'title' => esc_html__( 'Site Top Spacing', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the top spacing of this page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'unicamp' ),
						),
						array(
							'id'    => 'site_bottom_spacing',
							'type'  => 'text',
							'title' => esc_html__( 'Site Bottom Spacing', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the bottom spacing of this page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'unicamp' ),
						),
						array(
							'id'    => 'site_class',
							'type'  => 'text',
							'title' => esc_html__( 'Body Class', 'unicamp' ),
							'desc'  => esc_html__( 'Add a class name to body then refer to it in custom CSS.', 'unicamp' ),
						),
					),
				),
				array(
					'title'  => esc_attr__( 'Background', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'site_background_message',
							'type'    => 'message',
							'title'   => esc_html__( 'Info', 'unicamp' ),
							'message' => esc_html__( 'These options controls the background on boxed mode.', 'unicamp' ),
						),
						array(
							'id'    => 'site_background_color',
							'type'  => 'color',
							'title' => esc_html__( 'Background Color', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background color of the outer background area in boxed mode of this page.', 'unicamp' ),
						),
						array(
							'id'    => 'site_background_image',
							'type'  => 'media',
							'title' => esc_html__( 'Background Image', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background image of the outer background area in boxed mode of this page.', 'unicamp' ),
						),
						array(
							'id'      => 'site_background_repeat',
							'type'    => 'select',
							'title'   => esc_html__( 'Background Repeat', 'unicamp' ),
							'desc'    => esc_html__( 'Controls the background repeat of the outer background area in boxed mode of this page.', 'unicamp' ),
							'options' => array(
								'no-repeat' => esc_attr__( 'No repeat', 'unicamp' ),
								'repeat'    => esc_attr__( 'Repeat', 'unicamp' ),
								'repeat-x'  => esc_attr__( 'Repeat X', 'unicamp' ),
								'repeat-y'  => esc_attr__( 'Repeat Y', 'unicamp' ),
							),
						),
						array(
							'id'      => 'site_background_attachment',
							'type'    => 'select',
							'title'   => esc_html__( 'Background Attachment', 'unicamp' ),
							'desc'    => esc_html__( 'Controls the background attachment of the outer background area in boxed mode of this page.', 'unicamp' ),
							'options' => array(
								''       => esc_attr__( 'Default', 'unicamp' ),
								'fixed'  => esc_attr__( 'Fixed', 'unicamp' ),
								'scroll' => esc_attr__( 'Scroll', 'unicamp' ),
							),
						),
						array(
							'id'    => 'site_background_position',
							'type'  => 'text',
							'title' => esc_html__( 'Background Position', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background position of the outer background area in boxed mode of this page.', 'unicamp' ),
						),
						array(
							'id'    => 'site_background_size',
							'type'  => 'text',
							'title' => esc_html__( 'Background Size', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background size of the outer background area in boxed mode of this page.', 'unicamp' ),
						),
						array(
							'id'      => 'content_background_message',
							'type'    => 'message',
							'title'   => esc_html__( 'Info', 'unicamp' ),
							'message' => esc_html__( 'These options controls the background of main content on this page.', 'unicamp' ),
						),
						array(
							'id'    => 'content_background_color',
							'type'  => 'color',
							'title' => esc_html__( 'Background Color', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background color of main content on this page.', 'unicamp' ),
						),
						array(
							'id'    => 'content_background_image',
							'type'  => 'media',
							'title' => esc_html__( 'Background Image', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background image of main content on this page.', 'unicamp' ),
						),
						array(
							'id'      => 'content_background_repeat',
							'type'    => 'select',
							'title'   => esc_html__( 'Background Repeat', 'unicamp' ),
							'desc'    => esc_html__( 'Controls the background repeat of main content on this page.', 'unicamp' ),
							'options' => array(
								'no-repeat' => esc_attr__( 'No repeat', 'unicamp' ),
								'repeat'    => esc_attr__( 'Repeat', 'unicamp' ),
								'repeat-x'  => esc_attr__( 'Repeat X', 'unicamp' ),
								'repeat-y'  => esc_attr__( 'Repeat Y', 'unicamp' ),
							),
						),
						array(
							'id'    => 'content_background_position',
							'type'  => 'text',
							'title' => esc_html__( 'Background Position', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the background position of main content on this page.', 'unicamp' ),
						),
					),
				),
				array(
					'title'  => esc_html__( 'Header', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'top_bar_type',
							'type'    => 'select',
							'title'   => esc_html__( 'Top Bar Type', 'unicamp' ),
							'desc'    => esc_html__( 'Select top bar type that displays on this page.', 'unicamp' ),
							'default' => '',
							'options' => Unicamp_Top_Bar::instance()->get_list( true ),
						),
						array(
							'id'      => 'header_type',
							'type'    => 'select',
							'title'   => esc_attr__( 'Header Type', 'unicamp' ),
							'desc'    => wp_kses(
								sprintf(
									__( 'Select header type that displays on this page. When you choose Default, the value in %s will be used.', 'unicamp' ),
									'<a href="' . admin_url( '/customize.php?autofocus[section]=header' ) . '">Customize</a>'
								), 'unicamp-a' ),
							'default' => '',
							'options' => Unicamp_Header::instance()->get_list( true ),
						),
						array(
							'id'      => 'header_overlay',
							'type'    => 'select',
							'title'   => esc_attr__( 'Header Overlay', 'unicamp' ),
							'default' => '',
							'options' => array(
								''  => esc_html__( 'Default', 'unicamp' ),
								'0' => esc_html__( 'No', 'unicamp' ),
								'1' => esc_html__( 'Yes', 'unicamp' ),
							),
						),
						array(
							'id'      => 'header_skin',
							'type'    => 'select',
							'title'   => esc_attr__( 'Header Skin', 'unicamp' ),
							'default' => '',
							'options' => array(
								''      => esc_html__( 'Default', 'unicamp' ),
								'dark'  => esc_html__( 'Dark', 'unicamp' ),
								'light' => esc_html__( 'Light', 'unicamp' ),
							),
						),
						array(
							'id'      => 'menu_display',
							'type'    => 'select',
							'title'   => esc_html__( 'Primary menu', 'unicamp' ),
							'desc'    => esc_html__( 'Select which menu displays on this page.', 'unicamp' ),
							'default' => '',
							'options' => Unicamp_Nav_Menu::get_all_menus(),
						),
						array(
							'id'      => 'menu_one_page',
							'type'    => 'switch',
							'title'   => esc_attr__( 'One Page Menu', 'unicamp' ),
							'default' => '0',
							'options' => array(
								'0' => esc_attr__( 'Disable', 'unicamp' ),
								'1' => esc_attr__( 'Enable', 'unicamp' ),
							),
						),
						array(
							'id'      => 'custom_dark_logo',
							'type'    => 'media',
							'title'   => esc_html__( 'Custom Dark Logo', 'unicamp' ),
							'desc'    => esc_html__( 'Select custom dark logo for this page.', 'unicamp' ),
							'default' => '',
						),
						array(
							'id'      => 'custom_light_logo',
							'type'    => 'media',
							'title'   => esc_html__( 'Custom Light Logo', 'unicamp' ),
							'desc'    => esc_html__( 'Select custom light logo for this page.', 'unicamp' ),
							'default' => '',
						),
						array(
							'id'      => 'custom_logo_width',
							'type'    => 'text',
							'title'   => esc_html__( 'Custom Logo Width', 'unicamp' ),
							'desc'    => esc_html__( 'Controls the width of logo. For e.g: 150px', 'unicamp' ),
							'default' => '',
						),
						array(
							'id'      => 'custom_sticky_logo_width',
							'type'    => 'text',
							'title'   => esc_html__( 'Custom Sticky Logo Width', 'unicamp' ),
							'desc'    => esc_html__( 'Controls the width of sticky logo. For e.g: 150px', 'unicamp' ),
							'default' => '',
						),
					),
				),
				array(
					'title'  => esc_html__( 'Page Title Bar', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'page_title_bar_layout',
							'type'    => 'select',
							'title'   => esc_html__( 'Layout', 'unicamp' ),
							'default' => '',
							'options' => Unicamp_Title_Bar::instance()->get_list( true ),
						),
						array(
							'id'    => 'page_title_bar_bottom_spacing',
							'type'  => 'text',
							'title' => esc_html__( 'Spacing', 'unicamp' ),
							'desc'  => esc_html__( 'Controls the bottom spacing of title bar of this page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'unicamp' ),
						),
						array(
							'id'      => 'page_title_bar_background_color',
							'type'    => 'color',
							'title'   => esc_html__( 'Background Color', 'unicamp' ),
							'default' => '',
						),
						array(
							'id'      => 'page_title_bar_background',
							'type'    => 'media',
							'title'   => esc_html__( 'Background Image', 'unicamp' ),
							'default' => '',
						),
						array(
							'id'      => 'page_title_bar_background_overlay',
							'type'    => 'color',
							'title'   => esc_html__( 'Background Overlay', 'unicamp' ),
							'default' => '',
						),
						array(
							'id'    => 'page_title_bar_custom_heading',
							'type'  => 'text',
							'title' => esc_html__( 'Custom Heading Text', 'unicamp' ),
							'desc'  => esc_html__( 'Insert custom heading for the page title bar. Leave blank to use default.', 'unicamp' ),
						),
					),
				),
				array(
					'title'  => esc_html__( 'Sidebars', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'page_sidebar_1',
							'type'    => 'select',
							'title'   => esc_html__( 'Sidebar 1', 'unicamp' ),
							'desc'    => esc_html__( 'Select sidebar 1 that will display on this page.', 'unicamp' ),
							'default' => 'default',
							'options' => $page_registered_sidebars,
						),
						array(
							'id'      => 'page_sidebar_2',
							'type'    => 'select',
							'title'   => esc_html__( 'Sidebar 2', 'unicamp' ),
							'desc'    => esc_html__( 'Select sidebar 2 that will display on this page.', 'unicamp' ),
							'default' => 'default',
							'options' => $page_registered_sidebars,
						),
						array(
							'id'      => 'page_sidebar_position',
							'type'    => 'switch',
							'title'   => esc_html__( 'Sidebar Position', 'unicamp' ),
							'desc'    => wp_kses(
								sprintf(
									__( 'Select position of Sidebar 1 for this page. If sidebar 2 is selected, it will display on the opposite side. If you set as "Default" then the value in %s will be used.', 'unicamp' ),
									'<a href="' . admin_url( '/customize.php?autofocus[section]=sidebars' ) . '">Customize -> Sidebar</a>'
								), 'unicamp-a' ),
							'default' => 'default',
							'options' => Unicamp_Helper::get_list_sidebar_positions( true ),
						),
					),
				),
				array(
					'title'  => esc_html__( 'Sliders', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'revolution_slider',
							'type'    => 'select',
							'title'   => esc_html__( 'Revolution Slider', 'unicamp' ),
							'desc'    => esc_html__( 'Select the unique name of the slider.', 'unicamp' ),
							'options' => Unicamp_Helper::get_list_revslider(),
						),
						array(
							'id'      => 'slider_position',
							'type'    => 'select',
							'title'   => esc_html__( 'Slider Position', 'unicamp' ),
							'default' => 'below',
							'options' => array(
								'above' => esc_attr__( 'Above Header', 'unicamp' ),
								'below' => esc_attr__( 'Below Header', 'unicamp' ),
							),
						),
					),
				),
				array(
					'title'  => esc_html__( 'Footer', 'unicamp' ),
					'fields' => array(
						array(
							'id'      => 'footer_enable',
							'type'    => 'select',
							'title'   => esc_html__( 'Footer Enable', 'unicamp' ),
							'default' => '',
							'options' => array(
								''     => esc_html__( 'Yes', 'unicamp' ),
								'none' => esc_html__( 'No', 'unicamp' ),
							),
						),
					),
				),
			);

			// Page
			$meta_boxes[] = array(
				'id'         => 'insight_page_options',
				'title'      => esc_html__( 'Page Options', 'unicamp' ),
				'post_types' => array( 'page' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'fields'     => array(
					array(
						'type'  => 'tabpanel',
						'items' => $general_options,
					),
				),
			);

			// Post
			$meta_boxes[] = array(
				'id'         => 'insight_post_options',
				'title'      => esc_html__( 'Page Options', 'unicamp' ),
				'post_types' => array( 'post' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'fields'     => array(
					array(
						'type'  => 'tabpanel',
						'items' => array_merge( array(
							array(
								'title'  => esc_html__( 'Post', 'unicamp' ),
								'fields' => array(
									array(
										'id'    => 'post_gallery',
										'type'  => 'gallery',
										'title' => esc_html__( 'Gallery Format', 'unicamp' ),
									),
									array(
										'id'    => 'post_video',
										'type'  => 'text',
										'title' => esc_html__( 'Video URL', 'unicamp' ),
										'desc'  => esc_html__( 'Input the url of video vimeo or youtube. For e.g: https://www.youtube.com/watch?v=9No-FiEInLA', 'unicamp' ),
									),
									array(
										'id'    => 'post_audio',
										'type'  => 'textarea',
										'title' => esc_html__( 'Audio Format', 'unicamp' ),
									),
									array(
										'id'    => 'post_quote_text',
										'type'  => 'text',
										'title' => esc_html__( 'Quote Format - Source Text', 'unicamp' ),
									),
									array(
										'id'    => 'post_quote_name',
										'type'  => 'text',
										'title' => esc_html__( 'Quote Format - Source Name', 'unicamp' ),
									),
									array(
										'id'    => 'post_quote_url',
										'type'  => 'text',
										'title' => esc_html__( 'Quote Format - Source Url', 'unicamp' ),
									),
									array(
										'id'    => 'post_link',
										'type'  => 'text',
										'title' => esc_html__( 'Link Format', 'unicamp' ),
									),
								),
							),
						), $general_options ),
					),
				),
			);

			// Product
			$meta_boxes[] = array(
				'id'         => 'insight_product_options',
				'title'      => esc_html__( 'Page Options', 'unicamp' ),
				'post_types' => array( 'product' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'fields'     => array(
					array(
						'type'  => 'tabpanel',
						'items' => array_merge( array(
							array(
								'title'  => esc_html__( 'Product', 'unicamp' ),
								'fields' => array(
									array(
										'id'      => 'single_product_layout_style',
										'type'    => 'select',
										'title'   => esc_html__( 'Single Product Style', 'unicamp' ),
										'desc'    => esc_html__( 'Select style of this single product page.', 'unicamp' ),
										'default' => '',
										'options' => array(
											''       => esc_html__( 'Default', 'unicamp' ),
											'list'   => esc_html__( 'List', 'unicamp' ),
											'slider' => esc_html__( 'Slider', 'unicamp' ),
										),
									),
								),
							),
						), $general_options ),
					),
				),
			);

			return $meta_boxes;
		}

	}

	Unicamp_Metabox::instance()->initialize();
}
