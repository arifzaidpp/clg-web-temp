<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initial setup for this theme
 */
class Unicamp_Init {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Add theme supports.
		add_action( 'after_setup_theme', array( $this, 'setup' ) );

		// Core filters.
		add_filter( 'insight_core_info', array( $this, 'core_info' ) );

		// Add backwards compatibility for older versions for title tag feature.
		if ( ! function_exists( '_wp_render_title_tag' ) ) {
			add_action( 'wp_head', array( $this, 'unicamp_render_title' ) );
		}
	}

	function unicamp_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @access public
	 */
	public function setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'unicamp', UNICAMP_THEME_DIR . '/languages' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'    => esc_html__( 'Primary', 'unicamp' ),
			'secondary'  => esc_html__( 'Secondary', 'unicamp' ),
			'off_canvas' => esc_html__( 'Off Canvas', 'unicamp' ),
		) );

		// Adjust the content-width.
		$GLOBALS['content_width'] = apply_filters( 'content_width', 640 );

		/*
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */

		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */

		add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery', 'video', 'audio', 'quote', 'link' ) );

		/*
		 * Set up the WordPress core custom background feature.
		 */
		add_theme_support( 'custom-background', apply_filters( 'custom_background_args', array(
			'default-color' => '#ffffff',
			'default-image' => '',
		) ) );

		// Support editor style.
		add_editor_style( array( 'editor-style.css' ) );
		add_theme_support( 'custom-header' );

		/*
		 * Support selective refresh for widget
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'insight-core' );
		add_theme_support( 'insight-detect' );
		add_theme_support( 'insight-kungfu' );
		add_theme_support( 'insight-metabox' );
		add_theme_support( 'insight-megamenu' );
		add_theme_support( 'insight-sidebar' );

		// Gutenberg.
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * Core info
	 *
	 * @param $info
	 *
	 * @return mixed
	 */
	function core_info( $info ) {
		$info['icon']      = UNICAMP_THEME_URI . '/assets/admin/images/logo.png';
		$info['tf']        = 'https://themeforest.net/item/unicamp-university-and-college-wordpress-theme/32057449';
		$info['envato_id'] = '32057449';
		$info['docs']      = 'https://document.thememove.com/unicamp';
		$info['child']     = 'https://api.thememove.com/download/unicamp-child.zip';
		$info['update']    = 'https://api.thememove.com/update/unicamp';
		$info['api']       = 'https://api.thememove.com/update/unicamp';
		$info['support']   = 'https://thememove.ticksy.com/';
		$info['faqs']      = 'https://thememove.ticksy.com/articles/';
		$info['desc']      = esc_html__( 'Thank you for using our theme, please reward it a full five-star &#9733;&#9733;&#9733;&#9733;&#9733; rating.', 'unicamp' );

		return $info;
	}
}
