(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			$( '#form-delete-exist-posts' ).on( 'submit', function( e ) {
				var agree = confirm( 'This action will delete all exist pages/posts & meta data. Are you sure?' );

				if ( agree ) {
					var $form = $( this );
					var $button = $form.find( '#btn-delete-exist-posts' );

					$.ajax( {
						url: ajaxurl,
						type: 'POST',
						data: $form.serialize(),
						beforeSend: function() {
							$button.addClass( 'updating-icon' ).text( 'Deleting...' );
						},
						success: function( data ) {
							$button.addClass( 'btn-disabled' ).attr( 'disabled', true );
						},
						complete: function() {
							$button.removeClass( 'updating-icon' ).text( 'Delete Completed' );
						}
					} );
				}

				return false;
			} );
		} );

	}( jQuery )
);
