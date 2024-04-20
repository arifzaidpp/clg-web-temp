jQuery( document ).ready( function( $ ) {
	'use strict';

	var $navs = $( '#tutor-single-course-tabs-nav' );
	var activeClass = 'active';

	$navs.on( 'click', 'a', function( e ) {
		e.preventDefault();
		e.stopPropagation();

		var parent = $( this ).parent();

		if ( parent.hasClass( activeClass ) ) {
			return;
		}

		parent.siblings().removeClass( activeClass );
		parent.addClass( activeClass );

		var id = $( this ).attr( 'href' );

		var tabContent = $( id );

		if ( tabContent.length ) {
			tabContent.siblings().removeClass( activeClass );
			tabContent.addClass( activeClass );
		}

		$( document ).trigger( 'UnicampCourseTabsChange' );
	} );
} );
