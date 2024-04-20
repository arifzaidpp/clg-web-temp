<?php

if ( ! class_exists( 'InsightCore_BMW' ) ) {

	add_action( 'widgets_init', 'insight_core_load_bmw' );

	function insight_core_load_bmw() {
		register_widget( 'InsightCore_BMW' );
	}

	class InsightCore_BMW extends WP_Widget {

		function __construct() {

			$widget_details = array(
				'classname'   => 'insight-core-bmw',
				'description' => 'Add one of your custom menus as a widget.',
			);

			parent::__construct( 'insight-core-bmw', esc_html__( '[Insight] Navigation Menu', 'insight-core' ), $widget_details );

		}

		function widget( $args, $instance ) {
			$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] ); // Get menu

			if ( ! $nav_menu ) {
				return;
			}

			$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			}

			$nav_args = apply_filters( 'insightcore_bmw_nav_args', array(
				'fallback_cb' => '',
				'menu'        => $nav_menu,
			) );

			wp_nav_menu( $nav_args );
			echo $args['after_widget'];
		}

		// widget admin

		function update( $new_instance, $old_instance ) {
			$instance['title']    = sanitize_text_field( $new_instance['title'] );
			$instance['nav_menu'] = $new_instance['nav_menu'];

			return $instance;
		}

		function form( $instance ) {
			$title    = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

			// Get menus
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

			// If no menus exists, direct the user to create some.
			if ( ! $menus ) {
				echo '<p>' . sprintf( esc_html__( 'No menus have been created yet. <a href="%s">Create some</a>.', 'insight-core' ), admin_url( 'nav-menus.php' ) ) . '</p>';

				return;
			}
			?>
			<p><label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'insight-core' ) ?></label><input
					type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					value="<?php echo esc_html( $title ); ?>"/>
			</p>
			<p><label
					for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_html_e( 'Select Menu:', 'insight-core' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"
				        name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
					<?php
					foreach ( $menus as $menu ) {
						$selected = $nav_menu == $menu->slug ? ' selected="selected"' : '';
						echo '<option' . $selected . ' value="' . $menu->slug . '">' . $menu->name . '</option>';
					}
					?>
				</select></p>
			<?php
		}

	} // end class

} // end if
?>
