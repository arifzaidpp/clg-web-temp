<?php
defined( 'ABSPATH' ) || exit;

class Unicamp {

	const PRIMARY_FONT            = 'Gordita';
	const HEADING_FONT            = 'Butler';
	const PRIMARY_COLOR           = '#ae152d';
	const SECONDARY_COLOR         = '#9D2235';
	const THIRD_COLOR             = '#031f42';
	const HEADING_COLOR           = '#111';
	const HEADING_SECONDARY_COLOR = '#333';
	const TEXT_COLOR              = '#696969';
	const TEXT_LIGHTEN_COLOR      = '#ababab';
	const COMMENT_AVATAR_SIZE     = 70;
	const TITLE_BAR_MINIMAL_TYPE  = '03';

	public static function is_tablet() {
		if ( ! class_exists( 'Mobile_Detect' ) ) {
			return false;
		}

		return Mobile_Detect::instance()->isTablet();
	}

	public static function is_mobile() {
		if ( ! class_exists( 'Mobile_Detect' ) ) {
			return false;
		}

		if ( self::is_tablet() ) {
			return false;
		}

		return Mobile_Detect::instance()->isMobile();
	}

	public static function is_handheld() {
		return ( self::is_mobile() || self::is_tablet() );
	}

	public static function is_desktop() {
		return ! self::is_handheld();
	}

	/**
	 * Get settings for Kirki
	 *
	 * @param string $option_name
	 * @param string $default
	 *
	 * @return mixed
	 */
	public static function setting( $option_name = '', $default = '' ) {
		$value = Unicamp_Kirki::get_option( 'theme', $option_name );

		$value = $value === null ? $default : $value;

		return $value;
	}

	/**
	 * Primary Menu
	 */
	public static function menu_primary( $args = array() ) {
		$menu_class = 'menu__container sm sm-simple';

		if ( UNICAMP_IS_RTL ) {
			$menu_class .= ' sm-rtl';
		}

		$defaults = array(
			'theme_location' => 'primary',
			'container'      => 'ul',
			'menu_class'     => $menu_class,
			'menu_id'        => 'menu-primary', // Change this id also need to change in global variable below.
			'extra_class'    => '',
		);

		if ( $defaults['extra_class'] ) {
			$defaults['menu_class'] .= ' ' . $defaults['extra_class'];
		}

		$args = wp_parse_args( $args, $defaults );

		if ( has_nav_menu( 'primary' ) && class_exists( 'Unicamp_Walker_Nav_Menu' ) ) {
			$args['walker'] = new Unicamp_Walker_Nav_Menu;
		}

		$menu = Unicamp_Helper::get_post_meta( 'menu_display', '' );

		if ( $menu !== '' ) {
			$args['menu'] = $menu;
		}

		/**
		 * Nav menu render need many works.
		 * Cache it & used for mobile version to get the best performance.
		 *
		 * @see Unicamp::menu_mobile_primary()
		 */
		global $unicamp_primary_menu;

		ob_start();

		wp_nav_menu( $args );

		$unicamp_primary_menu = ob_get_clean();

		echo '' . $unicamp_primary_menu;
	}

	/**
	 * Primary Menu
	 */
	public static function menu_secondary( $args = array() ) {
		$menu_class = 'menu__container sm sm-simple';

		if ( UNICAMP_IS_RTL ) {
			$menu_class .= ' sm-rtl';
		}

		$defaults = array(
			'theme_location' => 'secondary',
			'container'      => 'ul',
			'menu_class'     => $menu_class,
			'menu_id'        => 'menu-secondary', // Change this id also need to change in global variable below.
			'extra_class'    => '',
		);

		if ( $defaults['extra_class'] ) {
			$defaults['menu_class'] .= ' ' . $defaults['extra_class'];
		}

		$args = wp_parse_args( $args, $defaults );

		if ( has_nav_menu( 'secondary' ) && class_exists( 'Unicamp_Walker_Nav_Menu' ) ) {
			$args['walker'] = new Unicamp_Walker_Nav_Menu;
		}

		/**
		 * Nav menu render need many works.
		 * Cache it & used for mobile version to get the best performance.
		 *
		 * @see Unicamp::menu_mobile_primary()
		 */
		global $unicamp_secondary_menu;

		ob_start();

		wp_nav_menu( $args );

		$unicamp_secondary_menu = ob_get_clean();

		echo '' . $unicamp_secondary_menu;
	}

	/**
	 * Off Canvas Menu
	 */
	public static function off_canvas_menu_primary( $args = array() ) {
		$defaults = [
			'theme_location' => 'off_canvas',
			'container'      => 'ul',
			'menu_class'     => 'menu__container',
			'menu_id'        => 'off-canvas-menu-primary',
			'extra_class'    => '',
		];

		$args = wp_parse_args( $args, $defaults );

		if ( has_nav_menu( 'off_canvas' ) ) {
			$args['walker'] = new Unicamp_Walker_Nav_Menu;
		}

		wp_nav_menu( $args );
	}

	/**
	 * Mobile Menu
	 */
	public static function menu_mobile_primary() {
		global $unicamp_primary_menu;

		if ( UNICAMP_IS_RTL ) {
			$mobile_menu = str_replace( '<ul id="menu-primary" class="menu__container sm sm-simple sm-rtl">', '<ul id="mobile-menu-primary" class="menu__container">', $unicamp_primary_menu );
		} else {
			$mobile_menu = str_replace( '<ul id="menu-primary" class="menu__container sm sm-simple">', '<ul id="mobile-menu-primary" class="menu__container">', $unicamp_primary_menu );
		}

		echo '' . $mobile_menu;
		unset( $GLOBALS['unicamp_primary_menu'] );
	}

	/**
	 * Logo
	 */
	public static function branding_logo( $reverse_scheme = null ) {
		$logo_dark_url  = Unicamp_Helper::get_post_meta( 'custom_dark_logo', '' );
		$logo_light_url = Unicamp_Helper::get_post_meta( 'custom_light_logo', '' );

		$logo_width   = intval( Unicamp::setting( 'logo_width' ) );
		$retina_width = $logo_width * 2;
		$sticky_logo_skin = Unicamp::setting( 'header_sticky_logo' );
		$header_logo_skin = Unicamp_Global::instance()->get_header_skin();

		if ( '' === $logo_dark_url ) {
			$logo_dark = Unicamp::setting( 'logo_dark' );

			if ( isset( $logo_dark['id'] ) ) {
				$logo_dark_url = Unicamp_Image::get_attachment_url_by_id( array(
					'id'   => $logo_dark['id'],
					'size' => "{$retina_width}x9999",
					'crop' => false,
				) );
			} else {
				$logo_dark_url = $logo_dark['url'];
			}
		}

		if ( '' === $logo_light_url ) {
			$logo_light = Unicamp::setting( 'logo_light' );

			if ( isset( $logo_light['id'] ) ) {
				$logo_light_url = Unicamp_Image::get_attachment_url_by_id( array(
					'id'   => $logo_light['id'],
					'size' => "{$retina_width}x9999",
					'crop' => false,
				) );
			} else {
				$logo_light_url = $logo_light['url'];
			}
		}

		$has_both_skin = false;

		if ( $sticky_logo_skin !== $header_logo_skin ||
		     is_page_template( 'templates/one-page-scroll.php' )
		) {
			$has_both_skin = true;
		}

		if ( null !== $reverse_scheme ) {
			$use_logo_scheme = 'dark';
		} else {
			$use_logo_scheme = $header_logo_skin;
		}

		$alt = get_bloginfo( 'name', 'display' );
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( $has_both_skin === false ) : ?>
				<?php if ( 'dark' === $use_logo_scheme ): ?>
					<img src="<?php echo esc_url( $logo_dark_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>"
					     class="branding-logo dark-logo">
				<?php else: ?>
					<img src="<?php echo esc_url( $logo_light_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>"
					     class="branding-logo light-logo">
				<?php endif; ?>
			<?php else: ?>
				<img src="<?php echo esc_url( $logo_light_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>"
				     class="branding-logo light-logo">
				<img src="<?php echo esc_url( $logo_dark_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>"
				     class="branding-logo dark-logo">
			<?php endif; ?>
		</a>
		<?php
	}

	/**
	 * Adds custom attributes to the body tag.
	 */
	public static function body_attributes() {
		$attrs = apply_filters( 'unicamp_body_attributes', array() );

		$attrs_string = '';
		if ( ! empty( $attrs ) ) {
			foreach ( $attrs as $attr => $value ) {
				$attrs_string .= " {$attr}=" . '"' . esc_attr( $value ) . '"';
			}
		}

		echo '' . $attrs_string;
	}

	/**
	 * Adds custom classes to the branding.
	 */
	public static function branding_class( $class = '' ) {
		$classes = array( 'branding' );

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'unicamp_branding_class', $classes, $class );

		echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
	}

	/**
	 * Adds custom classes to the navigation.
	 */
	public static function navigation_class( $class = '' ) {
		$classes = array( 'navigation page-navigation' );

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'unicamp_navigation_class', $classes, $class );

		echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
	}
}
