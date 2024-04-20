<?php

class InsightCore_WalkerNavMenu extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$children = get_posts( array(
			'post_type'   => 'nav_menu_item',
			'nopaging'    => true,
			'numberposts' => 1,
			'meta_key'    => '_menu_item_menu_item_parent',
			'meta_value'  => $item->ID
		) );
		foreach ( $children as $child ) {
			$obj = get_post_meta( $child->ID, '_menu_item_object' );
			if ( $obj[0] == 'ic_mega_menu' ) {
				$classes[] = apply_filters( 'insight_core_mega_menu_css_class', 'mega-menu', $item, $args, $depth );
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['href'] .= get_post_meta( $item->ID, '_url_parameters', true ) ? get_post_meta( $item->ID, '_url_parameters', true ) : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if ( $item->object == 'ic_mega_menu' ) {
			$menu_post               = get_post( $item->object_id );
			$mega_menu_content_class = apply_filters( 'insight_core_mega_menu_content_css_class', 'mega-menu-content', $item, $args, $depth );
			$output .= '<div class="' . esc_attr( $mega_menu_content_class ) . '">' . do_shortcode( $menu_post->post_content ) . '</div>';
		} else {
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
}
