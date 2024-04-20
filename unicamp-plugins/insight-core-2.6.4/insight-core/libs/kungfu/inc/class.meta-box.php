<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	add_action( 'load-post.php', 'kungfu_framework_register_meta_boxes' );
	add_action( 'load-post-new.php', 'kungfu_framework_register_meta_boxes' );
}

function kungfu_framework_register_meta_boxes() {

	$meta_boxes = apply_filters( 'insight_core_meta_boxes', array() );

	// Do nothing if has not any meta boxes
	if ( empty( $meta_boxes ) || ! is_array( $meta_boxes ) ) {
		return;
	}

	$screen = get_current_screen();

	foreach ( $meta_boxes as $meta_box ) {

		// Allow to set 'post_types' param by string
		if ( is_string( $meta_box['post_types'] ) ) {
			$meta_box['post_types'] = array( $meta_box['post_types'] );
		}

		// Only register meta box which in current screen.
		if ( in_array( $screen->id, $meta_box['post_types'] ) && $screen->base == 'post' ) {
			new Kungfu_Framework_Meta_Box( $meta_box );
		}
	}
}

if ( ! class_exists( 'Kungfu_Framework_Meta_Box' ) ) {
	class Kungfu_Framework_Meta_Box {

		public $meta_box = array();
		public $fields = array();
		public $id = '';
		public $group = true;

		static $meta_post_values = array();

		function __construct( $meta_box ) {
			// Run script only in admin area
			if ( ! is_admin() ) {
				return;
			}

			// Assign meta box values to local variables and add it's missed values
			$this->meta_box = $this->normalize( $meta_box );

			if ( isset( $this->meta_box['fields'] ) ) {
				$this->fields = $this->meta_box['fields'];
			}

			$this->id = &$this->meta_box['id'];

			$this->group = &$this->meta_box['group'];

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'add_meta_boxes', array( $this, 'kungfu_add_meta_boxes' ) );

			// Save post meta
			foreach ( $this->meta_box['post_types'] as $post_type ) {
				if ( 'attachment' === $post_type ) {
					// Attachment uses other hooks
					// @see wp_update_post(), wp_insert_attachment()
					add_action( 'edit_attachment', array( $this, 'kungfu_meta_box_save' ) );
					add_action( 'add_attachment', array( $this, 'kungfu_meta_box_save' ) );
				} else {
					add_action( "save_post_{$post_type}", array( $this, 'kungfu_meta_box_save' ) );
				}
			}
		}

		function enqueue_scripts() {
			$fields = Kungfu_Framework_Helper::get_fields( $this->fields );

			foreach ( $fields as $field ) {
				$class = 'KFF_' . $field . '_Field';
				if ( class_exists( $class ) && method_exists( $class, 'enqueue_scripts' ) ) {
					call_user_func( array( $class, 'enqueue_scripts' ) );
				}
			}
		}

		/**
		 * Normalize parameters for meta box
		 *
		 * @param array $meta_box Meta box definition
		 *
		 * @return array $meta_box Normalized meta box
		 */
		function normalize( $meta_box ) {
			// Set default values for meta box
			$meta_box = wp_parse_args( $meta_box, array(
				'id'             => sanitize_title( $meta_box['title'] ),
				'context'        => 'normal',
				'priority'       => 'high',
				'post_types'     => 'post',
				'autosave'       => false,
				'default_hidden' => false,
				'group'          => true,
			) );

			return $meta_box;
		}

		function kungfu_add_meta_boxes() {
			foreach ( $this->meta_box['post_types'] as $post_type ) {
				add_meta_box(
					$this->meta_box['id'],
					$this->meta_box['title'],
					array( $this, 'kungfu_meta_box_output' ),
					$post_type,
					$this->meta_box['context'],
					$this->meta_box['priority']
				);
			}
		}

		function kungfu_meta_box_output( $post ) {
			// Check whether form is submitted properly
			wp_nonce_field( "kungfu-save-" . $this->id, "nonce_" . $this->id );

			if ( $this->group == true ) {
				// Get post meta
				$post_metas = maybe_unserialize( get_post_meta( $post->ID, $this->id, true ) );
				//var_dump( $post_metas );
				if ( ! empty( $this->fields ) ) {
					Kungfu_Framework_Helper::render_form( $this->fields, $post_metas );

				}
			} else {
				$this->get_all_post_meta( $post, $this->fields );

				//var_dump( self::$meta_post_values );

				if ( ! empty( $this->fields ) ) {
					Kungfu_Framework_Helper::render_form( $this->fields, self::$meta_post_values );
				}
			}
		}

		function kungfu_meta_box_save( $post_id ) {
			/*
			 * We need to verify this came from our screen and with proper authorization,
			 * because the save_post action can be triggered at other times.
			 */
			$nonce = isset( $_POST[ "nonce_" . $this->id ] ) ? sanitize_key( $_POST[ "nonce_" . $this->id ] ) : '';
			if ( empty( $_POST[ "nonce_" . $this->id ] ) || ! wp_verify_nonce( $nonce, "kungfu-save-" . $this->id ) ) {
				return;
			}

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Make sure meta is added to the post, not a revision
			if ( $the_post = wp_is_post_revision( $post_id ) ) {
				$post_id = $the_post;
			}

			if ( $this->group == true ) {
				$metas = Kungfu_Framework_Helper::get_form_values( $this->fields );
				update_post_meta( $post_id, $this->id, maybe_serialize( $metas ) );
			} else {
				$this->loop_fields_save_meta( $post_id, $this->fields );
			}
		}

		function loop_fields_save_meta( $post_id, $fields ) {
			foreach ( $fields as $field ) {
				if ( in_array( $field['type'], array( 'tabpanel', 'accordion' ) ) ) {
					foreach ( $field['items'] as $item ) {
						if ( isset( $item['fields'] ) && ! empty( $item['fields'] ) ) {
							$this->loop_fields_save_meta( $post_id, $item['fields'] );
						}
					}
				} else {
					if ( isset( $field['id'] ) && isset( $_POST[ $field['id'] ] ) ) {
						$class = 'KFF_' . $field['type'] . '_Field';
						$value = stripslashes_deep( $_POST[ $field['id'] ] );
						if ( method_exists( $class, 'standardize' ) ) {
							$value = call_user_func( array( $class, 'standardize' ), array( $value ) );
						}
						update_post_meta( $post_id, $field['id'], $value );
					}
				}
			}
		}

		function get_all_post_meta( $post, $fields ) {
			foreach ( $fields as $field ) {
				if ( in_array( $field['type'], array( 'tabpanel', 'accordion' ) ) ) {
					foreach ( $field['items'] as $item ) {
						if ( isset( $item['fields'] ) && ! empty( $item['fields'] ) ) {
							$this->get_all_post_meta( $item['fields'] );
						}
					}
				} else {
					if ( isset( $field['id'] ) ) {
						$value = get_post_meta( $post->ID, $field['id'], true );
						if ( $value != '' ) {
							self::$meta_post_values[ $field['id'] ] = $value;
						}
					}
				}
			}
		}
	}
}
