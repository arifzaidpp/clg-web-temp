<?php

namespace Unicamp_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

abstract class Terms_Carousel_Base extends Carousel_Base {

	private $terms         = null;
	private $current_slide = null;
	private $current_key   = null;

	abstract protected function get_taxonomy_name();

	abstract protected function print_slide();

	protected function query_terms() {
		$settings = $this->get_settings_for_display();

		$taxonomy_name = $this->get_taxonomy_name();

		$attributes = [
			'taxonomy'   => $taxonomy_name,
			'number'     => $settings['number'],
			'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? true : false,
			'orderby'    => $settings['orderby'],
			'order'      => $settings['order'],
		];

		if ( 'by_id' === $settings['source'] ) {
			$attributes['ids'] = implode( ',', $settings['categories'] );
		} elseif ( 'by_parent' === $settings['source'] ) {
			$attributes['parent'] = $settings['parent'];
		} elseif ( 'current_subcategories' === $settings['source'] ) {
			$attributes['parent'] = get_queried_object_id();
		}

		$terms = get_terms( $attributes );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			$terms = [];
		}

		$this->terms = $terms;
	}

	protected function get_terms() {
		if ( $this->terms === null ) {
			$this->query_terms();
		}

		return $this->terms;
	}

	protected function get_current_slide() {
		return $this->current_slide;
	}

	protected function get_current_key() {
		return $this->current_key;
	}

	protected function register_controls() {
		parent::register_controls();

		$this->register_query_section();
	}

	protected function register_query_section() {
		$this->start_controls_section( 'query_section', [
			'label' => esc_html__( 'Query', 'unicamp' ),
		] );

		$this->add_control( 'source', [
			'label'       => esc_html__( 'Source', 'unicamp' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				''                      => esc_html__( 'Show All', 'unicamp' ),
				'by_id'                 => esc_html__( 'Manual Selection', 'unicamp' ),
				'by_parent'             => esc_html__( 'By Parent', 'unicamp' ),
				'current_subcategories' => esc_html__( 'Current Subcategories', 'unicamp' ),
			],
			'label_block' => true,
		] );

		$taxonomy_name = $this->get_taxonomy_name();

		$categories = get_terms( [
			'taxonomy' => $taxonomy_name,
		] );

		$options = [];
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control( 'categories', [
			'label'       => esc_html__( 'Categories', 'unicamp' ),
			'type'        => Controls_Manager::SELECT2,
			'options'     => $options,
			'default'     => [],
			'label_block' => true,
			'multiple'    => true,
			'condition'   => [
				'source' => 'by_id',
			],
		] );

		$parent_options = [ '0' => esc_html__( 'Only Top Level', 'unicamp' ) ] + $options;
		$this->add_control(
			'parent', [
			'label'     => esc_html__( 'Parent', 'unicamp' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '0',
			'options'   => $parent_options,
			'condition' => [
				'source' => 'by_parent',
			],
		] );

		$this->add_control( 'hide_empty', [
			'label'     => esc_html__( 'Hide Empty', 'unicamp' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_on'  => esc_html__( 'Hide', 'unicamp' ),
			'label_off' => esc_html__( 'Show', 'unicamp' ),
		] );

		$this->add_control( 'number', [
			'label'   => esc_html__( 'Categories Count', 'unicamp' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => '10',
		] );

		$this->add_control( 'orderby', [
			'label'   => esc_html__( 'Order By', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'name',
			'options' => [
				'name'        => esc_html__( 'Name', 'unicamp' ),
				'slug'        => esc_html__( 'Slug', 'unicamp' ),
				'description' => esc_html__( 'Description', 'unicamp' ),
				'count'       => esc_html__( 'Count', 'unicamp' ),
			],
		] );

		$this->add_control( 'order', [
			'label'   => esc_html__( 'Order', 'unicamp' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'desc',
			'options' => [
				'asc'  => esc_html__( 'ASC', 'unicamp' ),
				'desc' => esc_html__( 'DESC', 'unicamp' ),
			],
		] );

		$this->end_controls_section();
	}

	protected function print_slides( array $settings ) {
		$terms = $this->get_terms();

		if ( empty( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) :
			$term_id = $term->term_id;

			$this->current_slide = $term;
			$this->current_key   = "term_{$term_id}_";

			$this->print_slide();

		endforeach;
	}
}
