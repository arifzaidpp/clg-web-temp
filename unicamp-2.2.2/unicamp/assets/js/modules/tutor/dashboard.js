(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			/**
			 * Edition version of edit review form on dashboard.
			 */
			$( document.body ).on( 'submit', '.tutor_update_review_form', function( e ) {
				e.preventDefault();

				var $form = $( this );
				var formData = $form.serializeArray();


				var nonce_key = _tutorobject.nonce_key;

				var data = {
					action: 'unicamp_update_review_modal',
					nonce_key: _tutorobject[ nonce_key ],
				};

				formData.forEach( function( item, index, array ) {
					if ( 'tutor_rating_gen_input' === item.name ) {
						data.rating = item.value;
					} else {
						data[ item.name ] = item.value;
					}
				} );

				$.ajax( {
					url: _tutorobject.ajaxurl,
					type: 'POST',
					data: data,
					dataType: 'json',
					beforeSend: function() {
						$form.find( 'button[type="submit"]' ).addClass( 'tutor-updating-message' );
					},
					success: function( data ) {
						if ( data.success ) {
							// Close the modal.
							$( '.tutor-edit-review-modal-wrap' ).removeClass( 'show' );
							location.reload( true );
						}
					},
					complete: function() {
						$form.find( 'button[type="submit"]' ).removeClass( 'tutor-updating-message' );
					}
				} );
			} );

			var withdrawMethodInput = $( '.withdraw-method-select-input' );

			withdrawMethodInput.on( 'change', function( e ) {
				$( '.withdraw-account-save-btn-wrap' ).show();
			} );

			withdrawMethodInput.each( function() {
				var $that = $( this );
				if ( $that.is( ':checked' ) ) {
					$( '.withdraw-account-save-btn-wrap' ).show();
				}
			} );
		} );
	}( jQuery )
);
