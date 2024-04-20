(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			$( '.unicamp-wp-widget-course-name-filter' ).on( 'submit', '.search-form', function() {
				var baseLink = $( this ).attr( 'action' );
				var nameValue = $( this ).find( 'input[name="filter_name"]' ).val();

				if ( '' !== nameValue ) {
					baseLink = addUrlParam( baseLink, 'filter_name', nameValue );
					baseLink = addUrlParam( baseLink, 'filtering', '1' );
				}

				window.location.href = baseLink;

				return false;
			} );

			$( '.unicamp-wp-widget-course-form-filter' ).on( 'submit', 'form', function() {
				var baseLink = $( this ).attr( 'action' );
				var name = $( this ).find( 'input[name="filter_name"]' ).val();
				var level = $( this ).find( '[name="level"]' ).val();
				var category = parseInt( $( this ).find( '[name="filter_course-category"]' ).val() );
				var location = parseInt( $( this ).find( '[name="filter_course-location"]' ).val() );

				if ( '' !== name ) {
					baseLink = addUrlParam( baseLink, 'filter_name', name );
					baseLink = addUrlParam( baseLink, 'filtering', '1' );
				}

				if ( 0 !== category ) {
					baseLink = addUrlParam( baseLink, 'filter_course-category', category );
					baseLink = addUrlParam( baseLink, 'filtering', '1' );
				}

				if ( 0 !== location ) {
					baseLink = addUrlParam( baseLink, 'filter_course-location', location );
					baseLink = addUrlParam( baseLink, 'filtering', '1' );
				}

				if ( '' !== level ) {
					baseLink = addUrlParam( baseLink, 'level', level );
					baseLink = addUrlParam( baseLink, 'filtering', '1' );
				}

				window.location.href = baseLink;

				return false;
			} );
		} );

		/**
		 * Add a URL parameter (or changing it if it already exists)
		 * @param {string} search - This is typically document.location.search
		 * @param {string} key - The key to set
		 * @param {string} val - Value
		 */
		var addUrlParam = function( search, key, val ) {
			key = encodeURI( key );
			val = encodeURI( val );

			var re = new RegExp( "([?&])" + key + "=.*?(&|$)", "i" );
			var separator = search.indexOf( '?' ) !== - 1 ? "&" : "?";

			// Update value if key exist.
			if ( search.match( re ) ) {
				search = search.replace( re, '$1' + key + "=" + val + '$2' );
			} else {
				search += separator + key + '=' + val;
			}

			return search;
		};

	}( jQuery )
);
