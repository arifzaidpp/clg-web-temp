<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_Media_Press' ) ) {
	class Unicamp_Media_Press extends Unicamp_BP {

		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			remove_action( 'bp_after_activity_post_form', 'mpp_activity_upload_buttons' );
			add_action( 'bp_after_activity_post_form', [ $this, 'mpp_activity_upload_buttons' ] );

			//add_action( 'bp_activity_post_form_options', [ $this, 'mpp_activity_upload_buttons' ], 11 );
			/*remove_action( 'bp_after_activity_post_form', 'mpp_activity_dropzone' );
			add_action( 'bp_after_activity_post_form', 'mpp_activity_dropzone', 12 );*/

			remove_action( 'mpp_group_nav', 'mp_group_nav', 0 );
			add_action( 'mpp_group_nav', [ $this, 'group_nav' ], 0 );
		}

		/**
		 * Add current class to sub nav in group
		 */
		public function group_nav() {
			if ( ! bp_is_group() ) {
				return;
			}

			$component    = 'groups';
			$component_id = groups_get_current_group()->id;

			if ( mpp_user_can_create_gallery( $component, $component_id ) ) {
				global $bp;
				$current_item = isset( $bp->canonical_stack['action_variables'] ) ? $bp->canonical_stack['action_variables'][0] : '';

				echo sprintf( "<li class='%s'><a href='%s'>%s</a></li>", '' === $current_item ? 'current selected' : '', mpp_get_gallery_base_url( $component, $component_id ), esc_html__( 'All Galleries', 'unicamp' ) );

				if ( mpp_group_is_my_galleries_enabled() ) {
					echo sprintf( "<li class='%s'><a href='%s'>%s</a></li>", 'my-gallery' === $current_item ? 'current selected' : '', mpp_group_get_user_galleries_url(), esc_html__( 'My Galleries', 'unicamp' ) );
				}

				echo sprintf( "<li class='%s'><a href='%s'>%s</a></li>", 'create' === $current_item ? 'current selected' : '', mpp_get_gallery_create_url( $component, $component_id ), esc_html__( 'Create Gallery', 'unicamp' ) );
			}
		}

		/**
		 * Custom Icon
		 *
		 * @see mpp_activity_upload_buttons()
		 *
		 * Add various upload icons/buttons to activity post form
		 */
		public function mpp_activity_upload_buttons() {
			$component    = mpp_get_current_component();
			$component_id = mpp_get_current_component_id();

			// If activity upload is disabled or the user is not allowed to upload to current component, don't show.
			if ( ! mpp_is_activity_upload_enabled( $component ) || ! mpp_user_can_upload( $component, $component_id ) ) {
				return;
			}

			// if we are here, the gallery activity stream upload is enabled,
			// let us see if we are on user profile and gallery is enabled.
			if ( ! mpp_is_enabled( $component, $component_id ) ) {
				return;
			}
			// if we are on group page and either the group component is not enabled or gallery is not enabled for current group, do not show the icons.
			if ( function_exists( 'bp_is_group' ) && bp_is_group() && ( ! mpp_is_active_component( 'groups' ) || ! ( function_exists( 'mpp_group_is_gallery_enabled' ) && mpp_group_is_gallery_enabled() ) ) ) {
				return;
			}
			// for now, avoid showing it on single gallery/media activity stream.
			if ( mpp_is_single_gallery() || mpp_is_single_media() ) {
				return;
			}

			?>
			<div id="mpp-activity-upload-buttons" class="mpp-upload-buttons">
				<?php do_action( 'mpp_before_activity_upload_buttons' ); // allow to add more type.  ?>

				<?php if ( mpp_is_active_type( 'photo' ) && mpp_component_supports_type( $component, 'photo' ) ) : ?>
					<a href="#" id="mpp-photo-upload" data-media-type="photo"
					   title="<?php esc_attr_e( 'Upload photo', 'unicamp' ); ?>">
						<?php echo Unicamp_Helper::get_file_contents( UNICAMP_BP_DIR . '/assets/images/media-button-photo.svg' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( mpp_is_active_type( 'audio' ) && mpp_component_supports_type( $component, 'audio' ) ) : ?>
					<a href="#" id="mpp-audio-upload" data-media-type="audio"
					   title="<?php esc_attr_e( 'Upload audio', 'unicamp' ); ?>">
						<?php echo Unicamp_Helper::get_file_contents( UNICAMP_BP_DIR . '/assets/images/media-button-audio.svg' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( mpp_is_active_type( 'video' ) && mpp_component_supports_type( $component, 'video' ) ) : ?>
					<a href="#" id="mpp-video-upload" data-media-type="video"
					   title="<?php esc_attr_e( 'Upload video', 'unicamp' ); ?>">
						<?php echo Unicamp_Helper::get_file_contents( UNICAMP_BP_DIR . '/assets/images/media-button-video.svg' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( mpp_is_active_type( 'doc' ) && mpp_component_supports_type( $component, 'doc' ) ) : ?>
					<a href="#" id="mpp-doc-upload" data-media-type="doc"
					   title="<?php esc_attr_e( 'Upload document', 'unicamp' ); ?>">
						<?php echo Unicamp_Helper::get_file_contents( UNICAMP_BP_DIR . '/assets/images/media-button-doc.svg' ); ?>
					</a>
				<?php endif; ?>

				<?php do_action( 'mpp_after_activity_upload_buttons' ); // allow to add more type.  ?>

			</div>
			<?php
		}
	}

	Unicamp_Media_Press::instance()->initialize();
}
