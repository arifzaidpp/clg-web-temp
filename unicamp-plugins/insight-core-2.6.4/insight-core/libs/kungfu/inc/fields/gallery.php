<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'KFF_Gallery_Field' ) ) {
	class KFF_Gallery_Field {
		static function template( $field, $post_metas ) {

			$field['subtitle'] = isset( $field['subtitle'] ) ? '<p class="kungfu-form-sub-title">' . $field['subtitle'] . '</p>' : '';
			$field['desc']     = isset( $field['desc'] ) ? '<p class="kungfu-form-description">' . $field['desc'] . '</p>' : '';

			$value = isset( $post_metas[ $field['id'] ] ) && $post_metas[ $field['id'] ] != null ? $post_metas[ $field['id'] ] : '';

			$valEncoded = '';

			ob_start();
			if ( ! empty( $value ) ) {
				$valEncoded = htmlspecialchars( json_encode( $value ) );
				foreach ( $value as $attachment ) {
					printf(
						'<li class="image" data-attachment-id="%s" >
							<img src="%s" />
							<ul class="actions">
								<li>
									<a href="#" class="kungfu-gallery-remove" title="Delete"><i class="fa fa-times-circle-o"></i></a>
								</li>
							</ul>
						</li>',
						$attachment['id'],
						$attachment['thumbnail']
					);
				}
			}

			$list = ob_get_clean();

			return sprintf( '
				<div class="kungfu-form-wrapper">
      		<div class="kungfu-form-title">
            <label class="kungfu-form-label">%s</label>%s
          </div>
      		<div class="kungfu-form-control">
						<div class="kungfu-gallery-upload-wrap">
							<ul class="kungfu-gallery-images">%s</ul>
							<a class="kungfu-gallery-open kungfu-button success"><i class="fa fa-upload"></i>%s</a>
							<a class="kungfu-gallery-clear kungfu-button danger" style="display:%s"><i class="fa fa-trash-o"></i>%s</a>
							<input type="hidden" class="kungfu-gallery" name="%s" value="%s" />
						</div>
					</div>
				</div>',
				$field['title'],
				$field['subtitle'],
				$list,
				__( 'Upload', 'insight-core' ),
				! empty( $value ) ? 'inline-block' : 'none',
				__( 'Clear', 'insight-core' ),
				$field['id'],
				$valEncoded
			);
		}

		static function enqueue_scripts() {
			// This function loads in the required media files for the media manager
			wp_enqueue_media();

			wp_enqueue_script( 'kungfu-gallery', KFF_JS_URL . 'gallery.js', array(
				'jquery-core'
			), false, true );
		}

		static function standardize( $value ) {
			return json_decode( $value[0], true );
		}
	}
}