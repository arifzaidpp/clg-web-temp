(
	function( $ ) {
		"use strict";
		$( document ).ready( function() {
			$( '.sw_color' ).wpColorPicker();
			// Only show the "remove image" button when needed
			if ( '' === jQuery( '#sw_image' ).val() ) {
				jQuery( '#sw_remove_image' ).hide();
			}
			// Uploading files
			var file_frame_tm;
			jQuery( document ).on( 'click', '#sw_upload_image', function( event ) {
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame_tm ) {
					file_frame_tm.open();
					return;
				}
				// Create the media frame.
				file_frame_tm = wp.media.frames.downloadable_file = wp.media( {
					title: 'Choose an image',
					button: {
						text: 'Use image'
					},
					multiple: false
				} );
				// When an image is selected, run a callback.
				file_frame_tm.on( 'select', function() {
					var attachment = file_frame_tm.state().get( 'selection' ).first().toJSON();
					jQuery( '#sw_image' ).val( attachment.id );
					jQuery( '#sw_image_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
					jQuery( '#sw_remove_image' ).show();
				} );
				// Finally, open the modal.
				file_frame_tm.open();
			} );
			jQuery( document ).on( 'click', '#sw_remove_image', function() {
				jQuery( '#sw_image_thumbnail' ).find( 'img' ).attr( 'src', isw_vars.placeholder_img );
				jQuery( '#sw_image' ).val( '' );
				jQuery( '#sw_remove_image' ).hide();
				return false;
			} );
		} );
	}
)( jQuery );