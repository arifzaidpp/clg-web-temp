(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			$( '.form-input-date' ).datepicker( {
				changeMonth: true,
				changeYear: true,
			} );

			/**
			 * Skip empty query string.
			 */
			$( '.event-filtering-form' ).on( 'submit', function() {
				$( this ).find( 'select' ).filter( function() {
					return ! $.trim( this.value ).length;
				} ).prop( 'disabled', true );

				$( this ).find( 'input' ).filter( function() {
					return ! $.trim( this.value ).length;
				} ).prop( 'disabled', true );
			} );
		} );

	}( jQuery )
);
