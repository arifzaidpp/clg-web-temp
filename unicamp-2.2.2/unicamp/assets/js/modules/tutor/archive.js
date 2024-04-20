jQuery( document ).ready( function( $ ) {
	'use strict';

	$( '#course-sorting-select' ).on( 'change', function( e ) {
		var url = $( this ).find( ':selected' ).data( 'url' );

		window.location = url;
	} );
} );
