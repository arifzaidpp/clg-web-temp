<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Event_Meta_Box' ) ) {
	class Unicamp_Event_Meta_Box extends Unicamp_Event {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Add meta data for event.
			add_action( 'tp_event_admin_event_metabox_after_fields', [
				$this,
				'add_meta_short_location',
			], 10, 2 );

			add_action( 'tp_event_admin_event_metabox_after_fields', [
				$this,
				'add_meta_place',
			], 10, 2 );

			add_action( 'tp_event_admin_event_metabox_after_fields', [
				$this,
				'add_meta_organiser',
			], 10, 2 );

			add_action( 'tp_event_admin_event_metabox_after_fields', [
				$this,
				'add_meta_phone_number',
			], 10, 2 );

			add_action( 'tp_event_admin_event_metabox_after_fields', [
				$this,
				'add_meta_email',
			], 10, 2 );

			add_action( 'tp_event_admin_event_metabox_after_fields', [
				$this,
				'add_meta_website',
			], 10, 2 );
		}

		public function add_meta_short_location( $post, $prefix ) {
			$post_id    = $post->ID;
			$meta_key   = $prefix . 'short_location';
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			?>
			<div class="option_group">
				<p class="form-field">
					<label for="<?php echo esc_attr( $meta_key ); ?>">
						<?php esc_html_e( 'Short Location', 'unicamp' ); ?>
					</label>
					<input type="text" class="short" name="<?php echo esc_attr( $meta_key ); ?>"
					       id="<?php echo esc_attr( $meta_key ); ?>"
					       value="<?php echo esc_attr( $meta_value ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_meta_place( $post, $prefix ) {
			$post_id    = $post->ID;
			$meta_key   = $prefix . 'place';
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			?>
			<div class="option_group">
				<p class="form-field">
					<label for="<?php echo esc_attr( $meta_key ); ?>">
						<?php esc_html_e( 'Place', 'unicamp' ); ?>
					</label>
					<input type="text" class="short" name="<?php echo esc_attr( $meta_key ); ?>"
					       id="<?php echo esc_attr( $meta_key ); ?>"
					       value="<?php echo esc_attr( $meta_value ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_meta_organiser( $post, $prefix ) {
			$post_id    = $post->ID;
			$meta_key   = $prefix . 'organiser';
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			?>
			<div class="option_group">
				<p class="form-field">
					<label for="<?php echo esc_attr( $meta_key ); ?>">
						<?php esc_html_e( 'Organiser', 'unicamp' ); ?>
					</label>
					<input type="text" class="short" name="<?php echo esc_attr( $meta_key ); ?>"
					       id="<?php echo esc_attr( $meta_key ); ?>"
					       value="<?php echo esc_attr( $meta_value ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_meta_phone_number( $post, $prefix ) {
			$post_id    = $post->ID;
			$meta_key   = $prefix . 'phone_number';
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			?>
			<div class="option_group">
				<p class="form-field">
					<label for="<?php echo esc_attr( $meta_key ); ?>">
						<?php esc_html_e( 'Phone Number', 'unicamp' ); ?>
					</label>
					<input type="text" class="short" name="<?php echo esc_attr( $meta_key ); ?>"
					       id="<?php echo esc_attr( $meta_key ); ?>"
					       value="<?php echo esc_attr( $meta_value ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_meta_email( $post, $prefix ) {
			$post_id    = $post->ID;
			$meta_key   = $prefix . 'email';
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			?>
			<div class="option_group">
				<p class="form-field">
					<label for="<?php echo esc_attr( $meta_key ); ?>">
						<?php esc_html_e( 'Email', 'unicamp' ); ?>
					</label>
					<input type="email" class="short" name="<?php echo esc_attr( $meta_key ); ?>"
					       id="<?php echo esc_attr( $meta_key ); ?>"
					       value="<?php echo esc_attr( $meta_value ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_meta_website( $post, $prefix ) {
			$post_id    = $post->ID;
			$meta_key   = $prefix . 'website';
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			?>
			<div class="option_group">
				<p class="form-field">
					<label for="<?php echo esc_attr( $meta_key ); ?>">
						<?php esc_html_e( 'Website', 'unicamp' ); ?>
					</label>
					<input type="text" class="short" name="<?php echo esc_attr( $meta_key ); ?>"
					       id="<?php echo esc_attr( $meta_key ); ?>"
					       value="<?php echo esc_attr( $meta_value ); ?>"/>
				</p>
			</div>
			<?php
		}
	}

	Unicamp_Event_Meta_Box::instance()->initialize();
}
