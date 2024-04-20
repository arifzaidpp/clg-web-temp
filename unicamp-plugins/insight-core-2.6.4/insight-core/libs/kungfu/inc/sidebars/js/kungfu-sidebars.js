jQuery( document ).ready( function( $ ) {
	'use strict';

	var $form_sidebars = $( '#kungfu-form-sidebars' );

	$form_sidebars.on( 'click', '.kungfu-remove-sidebar', function( evt ) {
		evt.preventDefault();

		var row = $( this ).parents( 'tr' ).first();
		var sidebar_name = row.find( 'td' ).eq( 0 ).text();
		var sidebar_class = row.find( 'td' ).eq( 1 ).text();
		var answer = confirm( "Are you sure you want to remove \"" + sidebar_name + "\" ?\nThis will remove any widgets you have assigned to this sidebar." );
		if ( answer ) {
			var data = {
				action: 'insight_core_remove_sidebar',
				sidebar_class: sidebar_class
			};

			var formValues = $form_sidebars.serializeArray();

			for ( var i = 0; i < formValues.length; i ++ ) {
				data[ formValues[ i ].name ] = formValues[ i ].value;
			}

			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: $.param( data ),
				dataType: 'json',
				cache: false,
				success: function( response ) {
					if ( response.success ) {
						row.remove();
					} else {
						alert( response.data.messages );
					}
				}
			} );
		} else {
			return false;
		}
	} );

	$form_sidebars.on( 'submit', function( evt ) {
		evt.preventDefault();

		var $sidebarNameInput = $form_sidebars.find( '#sidebar_name' );
		var sidebarNameVal = $sidebarNameInput.val();

		if ( '' === sidebarNameVal ) {
			alert( 'Please input a name for sidebar' );
			return;
		}

		var data = {
			action: 'insight_core_add_sidebar',
			sidebar_name: sidebarNameVal
		};
		var formValues = $form_sidebars.serializeArray();

		for ( var i = 0; i < formValues.length; i ++ ) {
			data[ formValues[ i ].name ] = formValues[ i ].value;
		}

		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: $.param( data ),
			dataType: 'json',
			cache: false,
			success: function( response ) {
				if ( response.success ) {
					$form_sidebars.find( '.wp-list-table tbody' ).append( '<tr><td>' + sidebarNameVal + '</td><td>' + response.data.messages + '</td><td><a href="javascript:void(0);" class="button kungfu-remove-sidebar"><i class="fa fa-remove"></i>Remove</a></td></tr>' );
					$sidebarNameInput.val( '' );
				} else {
					alert( response.data.messages );
				}
			}
		} );
	} );
} );
