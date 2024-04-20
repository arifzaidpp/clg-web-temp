<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Sidebar' ) ) {
	class Unicamp_Sidebar {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			// Register widget areas.
			add_action( 'widgets_init', [ $this, 'register_sidebars' ] );

			add_filter( 'insight_core_dynamic_sidebar_args', [ $this, 'change_sidebar_args' ] );
		}

		/**
		 * Change sidebar args of dynamic sidebar.
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function change_sidebar_args( $args ) {
			$args['before_title'] = '<p class="widget-title heading"><span>';
			$args['after_title']  = '</span></p>';

			return $args;
		}

		public function get_default_sidebar_args() {
			return [
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<p class="widget-title heading"><span>',
				'after_title'   => '</span></p>',
			];
		}

		/**
		 * Register widget area.
		 *
		 * @access public
		 * @link   https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 */
		public function register_sidebars() {
			$default_args = $this->get_default_sidebar_args();

			register_sidebar( array_merge( $default_args, [
				'id'          => 'blog_sidebar',
				'name'        => esc_html__( 'Blog Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets here.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'page_sidebar',
				'name'        => esc_html__( 'Page Sidebar', 'unicamp' ),
				'description' => esc_html__( 'Add widgets here.', 'unicamp' ),
			] ) );

			register_sidebar( array_merge( $default_args, [
				'id'          => 'top_bar_widgets',
				'name'        => esc_html__( 'Top Bar Widgets', 'unicamp' ),
				'description' => esc_html__( 'Add widgets here.', 'unicamp' ),
			] ) );
		}

		/**
		 * @param string $name name of sidebar to render.
		 *
		 * Check sidebar is active then render it.
		 */
		public function generated_sidebar( $name ) {
			if ( is_active_sidebar( $name ) ) {
				dynamic_sidebar( $name );
			}
		}

		public function render( $template_position = 'left' ) {
			$sidebar1         = Unicamp_Global::instance()->get_sidebar_1();
			$sidebar2         = Unicamp_Global::instance()->get_sidebar_2();
			$sidebar_position = Unicamp_Global::instance()->get_sidebar_position();

			if ( 'none' !== $sidebar1 ) {
				$classes = [ 'page-sidebar', 'page-sidebar-' . $template_position ];
				$classes = apply_filters( 'unicamp_page_sidebar_class', $classes );
				$classes = implode( ' ', $classes );

				if ( $template_position === $sidebar_position ) {
					$this->get_sidebar_html( $classes, $sidebar1, true );
				}

				/**
				 * Only render sidebar 2 if sidebar 1 defined.
				 */
				if ( 'none' !== $sidebar2 && $template_position !== $sidebar_position ) {
					$this->get_sidebar_html( $classes, $sidebar2 );
				}
			}
		}

		public function get_sidebar_html( $classes, $name, $first_sidebar = false ) {
			?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="page-sidebar-inner tm-sticky-column" itemscope="itemscope">
					<div class="page-sidebar-content">
						<?php do_action( 'unicamp_page_sidebar_before_content', $name, $first_sidebar ); ?>

						<?php dynamic_sidebar( $name ); ?>

						<?php do_action( 'unicamp_page_sidebar_after_content', $name, $first_sidebar ); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	Unicamp_Sidebar::instance()->initialize();
}
