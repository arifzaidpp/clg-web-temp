jQuery( document ).ready( function() {
	var data = {
		action: 'ic_set_views', post_id: ic_vars.post_id, ic_nonce: ic_vars.ic_nonce
	};

	jQuery.post( ic_vars.ajax_url, data, function( response ) {
		//console.log( response );
	} );
} );