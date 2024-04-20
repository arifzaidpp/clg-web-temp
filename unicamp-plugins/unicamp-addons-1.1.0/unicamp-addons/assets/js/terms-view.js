jQuery( document ).ready( function() {
	var data = {
		action: 'unicamp_course_term_set_views',
		term_id: unicampAddons.term_id,
		unicamp_nonce: unicampAddons.nonce
	};

	jQuery.post( unicampAddons.ajax_url, data, function( response ) {

	} );
} );
