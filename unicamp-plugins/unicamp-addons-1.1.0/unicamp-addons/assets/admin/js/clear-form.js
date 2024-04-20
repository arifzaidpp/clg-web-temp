jQuery( document ).ready( function( $ ) {
	'use strict';

	$( document ).ajaxSuccess( function( evt, xhr, opts ) {
		if (
			xhr.status >= 200
			&& xhr.status < 300
			&& opts.data
			&& /(^|&)action=add-tag($|&)/.test( opts.data )
			&& /(^|&)taxonomy=course-category($|&)/.test( opts.data )
			&& xhr.responseXML
			&& $( 'wp_error', xhr.responseXML ).length == 0
		) {
			var $form = $( 'form#addtag' );

			if ( $form.length > 0 ) {
				$form.find( 'input[type=checkbox]' ).removeAttr( 'checked' );

				var $mediaField = $form.find( '.unicamp-addons-media-wrap' );

				if ( $mediaField.length > 0 ) {
					var placeholder = $mediaField.find( 'img' ).attr( 'data-src-placeholder' );

					$mediaField.find( 'img' ).attr( 'src', placeholder );

					$mediaField.find( '.unicamp-addons-media-input' ).val( '' );
				}
			}
		}
	} );
} );
