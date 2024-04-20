(
	function( window, $ ) {
		'use strict';
		window.unicamp = window.unicamp || {};

		const isEmptyObject = ( obj ) => {
			for ( const name in obj ) {
				return false;
			}

			return true;
		};

		/**
		 * Add a URL parameter (or changing it if it already exists)
		 * @param {string} url - This is typically document.location.search
		 * @param {string} key - The key to set
		 * @param {string} val - Value
		 */
		var addUrlParam = function( url, key, val ) {
			key = encodeURI( key );
			val = encodeURI( val );

			var re = new RegExp( "([?&])" + key + "=.*?(&|$)", "i" );
			var separator = url.indexOf( '?' ) !== - 1 ? "&" : "?";

			// Update value if key exist.
			if ( url.match( re ) ) {
				url = url.replace( re, '$1' + key + "=" + val + '$2' );
			} else {
				url += separator + key + '=' + val;
			}

			return url;
		};

		const getUrlParamsAsObject = function( query ) {
			var params = {};

			if ( - 1 === query.indexOf( '?' ) ) {
				return params;
			}

			query = query.substring( query.indexOf( '?' ) + 1 );

			var re = /([^&=]+)=?([^&]*)/g;
			var decodeRE = /\+/g;

			var decode = function( str ) {
				return decodeURIComponent( str.replace( decodeRE, " " ) );
			};

			var e;
			while ( e = re.exec( query ) ) {
				var k = decode( e[ 1 ] ), v = decode( e[ 2 ] );
				if ( k.substring( k.length - 2 ) === '[]' ) {
					k = k.substring( 0, k.length - 2 );
					(
						params[ k ] || (
							params[ k ] = []
						)
					).push( v );
				}
				else {
					params[ k ] = v;
				}
			}

			var assign = function( obj, keyPath, value ) {
				var lastKeyIndex = keyPath.length - 1;
				for ( var i = 0; i < lastKeyIndex; ++ i ) {
					var key = keyPath[ i ];
					if ( ! (
						key in obj
					) ) {
						obj[ key ] = {}
					}
					obj = obj[ key ];
				}
				obj[ keyPath[ lastKeyIndex ] ] = value;
			}

			for ( var prop in params ) {
				var structure = prop.split( '[' );
				if ( structure.length > 1 ) {
					var levels = [];
					structure.forEach( function( item, i ) {
						var key = item.replace( /[?[\]\\ ]/g, '' );
						levels.push( key );
					} );
					assign( params, levels, params[ prop ] );
					delete(
						params[ prop ]
					);
				}
			}
			return params;
		};

		const getScrollbarWidth = function() {
			// Creating invisible container.
			const outer = document.createElement( 'div' );
			outer.style.visibility = 'hidden';
			outer.style.overflow = 'scroll'; // forcing scrollbar to appear.
			outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps.
			document.body.appendChild( outer );

			// Creating inner element and placing it in the container.
			const inner = document.createElement( 'div' );
			outer.appendChild( inner );

			// Calculating difference between container's full width and the child width.
			const scrollbarWidth = (
				outer.offsetWidth - inner.offsetWidth
			);

			// Removing temporary elements from the DOM.
			outer.parentNode.removeChild( outer );

			return scrollbarWidth;

		};

		const setBodyOverflow = function() {
			$( 'body' ).css( {
				'overflow': 'hidden',
				'paddingRight': this.getScrollbarWidth() + 'px'
			} );
		};

		const unsetBodyOverflow = function() {
			$( 'body' ).css( {
				'overflow': 'visible',
				'paddingRight': 0
			} );
		};

		const setBodyHandling = function() {
			$( 'body' ).removeClass( 'completed' ).addClass( 'handling' );
		};

		const setBodyCompleted = function() {
			$( 'body' ).removeClass( 'handling' ).addClass( 'completed' );
		};

		const setElementHandling = function( $element ) {
			$element.addClass( 'updating-icon' );
		};

		const unsetElementHandling = function( $element ) {
			$element.removeClass( 'updating-icon' );
		};

		const getStyle = ( el, style ) => {
			if ( window.getComputedStyle ) {
				return style ? document.defaultView.getComputedStyle( el, null ).getPropertyValue( style ) : document.defaultView.getComputedStyle( el, null );
			}
			else if ( el.currentStyle ) {
				return style ? el.currentStyle[ style.replace( /-\w/g, ( s ) => {
					return s.toUpperCase().replace( '-', '' );
				} ) ] : el.currentStyle;
			}
		};

		const setCookie = function( cname, cvalue, exdays ) {
			var d = new Date();
			d.setTime( d.getTime() + (
				exdays * 24 * 60 * 60 * 1000
			) );
			var expires = 'expires=' + d.toUTCString();
			document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
		};

		const getCookie = function( cname ) {
			var name = cname + '=';
			var ca = document.cookie.split( ';' );
			for ( var i = 0; i < ca.length; i ++ ) {
				var c = ca[ i ];
				while ( c.charAt( 0 ) == ' ' ) {
					c = c.substring( 1 );
				}
				if ( c.indexOf( name ) == 0 ) {
					return c.substring( name.length, c.length );
				}
			}
			return '';
		};

		const isHandheld = function() {
			let check = false;
			(
				function( a ) {
					if ( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test( a ) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test( a.substr( 0, 4 ) ) ) {
						check = true;
					}
				}
			)( navigator.userAgent || navigator.vendor || window.opera );
			return check;
		};

		const isValidSelector = function( selector ) {
			if ( selector.match( /^([.#])(.+)/ ) ) {
				return true;
			}

			return false;
		};

		const copyToClipboard = function( text ) {
			if ( window.clipboardData && window.clipboardData.setData ) {
				// Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
				return window.clipboardData.setData( "Text", text );

			}
			else if ( document.queryCommandSupported && document.queryCommandSupported( "copy" ) ) {
				var textarea = document.createElement( "textarea" );
				textarea.textContent = text;
				textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in Microsoft Edge.
				document.body.appendChild( textarea );
				textarea.select();
				try {
					return document.execCommand( "copy" );  // Security exception may be thrown by some browsers.
				}
				catch ( ex ) {
					console.warn( "Copy to clipboard failed.", ex );
					return prompt( "Copy to clipboard: Ctrl+C, Enter", text );
				}
				finally {
					document.body.removeChild( textarea );
				}
			}
		};

		const wooSetContentHTML = ( $element, content ) => {
			if ( undefined === $element.attr( 'data-o_content' ) ) {
				$element.attr( 'data-o_content', $element.html() );
			}
			$element.html( content );
		};

		const wooResetContentHTML = ( $element ) => {
			if ( undefined !== $element.attr( 'data-o_content' ) ) {
				$element.html( $element.attr( 'data-o_content' ) );
			}
		};

		unicamp.Helpers = {
			isEmptyObject,
			isValidSelector,
			isHandheld,
			addUrlParam,
			getUrlParamsAsObject,
			getScrollbarWidth,
			setBodyOverflow,
			unsetBodyOverflow,
			setBodyHandling,
			setBodyCompleted,
			setElementHandling,
			unsetElementHandling,
			getStyle,
			setCookie,
			getCookie,
			copyToClipboard,
			wooSetContentHTML,
			wooResetContentHTML
		}

	}( window, jQuery )
);

(
	function( $ ) {
		'use strict';

		var $window            = $( window ),
		    $html              = $( 'html' ),
		    $body              = $( 'body' ),
		    $pageHeader        = $( '#page-header' ),
		    $headerInner       = $( '#page-header-inner' ),
		    $pageContent       = $( '#page-content' ),
		    headerStickyHeight = parseInt( $unicamp.header_sticky_height ),
		    smoothScrollOffset,
		    queueResetDelay,
		    animateQueueDelay  = 200,
		    wWidth             = window.innerWidth;

		$( document ).ready( function() {
			calMobileMenuBreakpoint();

			scrollToTop();

			initSliders();
			initGridMainQuery();
			initSmoothScrollLinks();
			initLightGalleryPopups();
			initVideoPopups();
			initSearchPopup();
			initHeaderRightMoreTools();
			initFooterAlwaysBottom();

			initSmartmenu();
			initCategoryMenu();
			initOffCanvasMenu();
			initMobileMenu();
			initCookieNotice();
			handlerVerticalHeader();
			handlerArchiveFiltering();
			initModal();

			$( '.unicamp-nice-select' ).UnicampNiceSelect();
		} );

		$( window ).on( 'resize', function() {
			wWidth = window.innerWidth;

			calMobileMenuBreakpoint();
			initStickyHeader();
			handlerVerticalHeader();
			initFooterAlwaysBottom();
		} );

		$( window ).on( 'load', function() {
			initPreLoader();
			initStickyHeader();
			navOnePage();
			handlerEntranceAnimation();
			handlerEntranceQueueAnimation();
		} );

		function handlerEntranceAnimation() {
			var items = $( '.modern-grid' ).children( '.grid-item' );

			items.elementorWaypoint( function() {
				// Fix for different ver of waypoints plugin.
				var _self = this.element ? this.element : this;
				var $self = $( _self );
				$self.addClass( 'animate' );
			}, {
				offset: '100%',
				triggerOnce: true
			} );
		}

		function handlerEntranceQueueAnimation() {
			$( '.unicamp-entrance-animation-queue' ).each( function() {
				var itemQueue  = [],
				    queueTimer,
				    queueDelay = $( this ).data( 'animation-delay' ) ? $( this ).data( 'animation-delay' ) : animateQueueDelay;

				$( this ).children( '.item' ).elementorWaypoint( function() {
					// Fix for different ver of waypoints plugin.
					var _self = this.element ? this.element : $( this );

					queueResetDelay = setTimeout( function() {
						queueDelay = animateQueueDelay;
					}, animateQueueDelay );

					itemQueue.push( _self );
					processItemQueue( itemQueue, queueDelay, queueTimer );
					queueDelay += animateQueueDelay;
				}, {
					offset: '100%',
					triggerOnce: true
				} );
			} );
		}

		function processItemQueue( itemQueue, queueDelay, queueTimer, queueResetDelay ) {
			clearTimeout( queueResetDelay );
			queueTimer = window.setInterval( function() {
				if ( itemQueue !== undefined && itemQueue.length ) {
					$( itemQueue.shift() ).addClass( 'animate' );
					processItemQueue();
				} else {
					window.clearInterval( queueTimer );
				}
			}, queueDelay );
		}

		function initPreLoader() {
			setTimeout( function() {
				$body.addClass( 'loaded' );
			}, 200 );

			var $loader = $( '#page-preloader' );

			setTimeout( function() {
				$loader.remove();
			}, 2000 );
		}

		function initModal() {
			if ( $.fn.UnicampModal ) {
				$body.on( 'click', '[data-unicamp-toggle="modal"]', function( evt ) {
					evt.preventDefault();
					var $target = $( $( this ).data( 'unicamp-target' ) );

					if ( $target.length > 0 ) {
						if ( $( this ).attr( 'data-unicamp-dismiss' ) === '1' ) {
							$target.UnicampModal( 'close' );
						} else {
							$target.UnicampModal( 'open' );
						}
					}
				} );
			}
		}

		function initSliders() {
			$( '.tm-slider' ).each( function() {
				if ( $( this ).hasClass( 'unicamp-swiper-linked-yes' ) ) {
					var mainSlider = $( this ).children( '.unicamp-main-swiper' ).UnicampSwiper();
					var thumbsSlider = $( this ).children( '.unicamp-thumbs-swiper' ).UnicampSwiper();

					mainSlider.controller.control = thumbsSlider;
					thumbsSlider.controller.control = mainSlider;
				} else {
					$( this ).UnicampSwiper();
				}
			} );
		}

		function initLightGalleryPopups() {
			if ( $().lightGallery ) {
				$( '.unicamp-light-gallery' ).each( function() {
					initLightGallery( $( this ) );
				} );
			}
		}

		function initVideoPopups() {
			if ( $().lightGallery ) {
				$( '.tm-popup-video' ).each( function() {
					handlerPopupVideo( $( this ) );
				} );
			}
		}

		function initGridMainQuery() {
			if ( $().UnicampGridLayout ) {
				$( '.unicamp-main-post' ).UnicampGridLayout();
			}
		}

		function handlerPopupVideo( $popup ) {
			var options = {
				selector: 'a',
				fullScreen: false,
				zoom: false,
				getCaptionFromTitleOrAlt: false,
				counter: false
			};
			$popup.lightGallery( options );
		}

		function navOnePage() {
			if ( ! $body.hasClass( 'one-page' ) ) {
				return;
			}

			var $mainNav = $( '#page-navigation' ).find( '.menu__container' ).first();
			var $li = $mainNav.children( '.menu-item' );
			var $links = $li.children( 'a[href*="#"]:not([href="#"])' );

			$li.each( function() {
				if ( $( this ).hasClass( 'current-menu-item' ) ) {
					var _link = $( this ).children( 'a' );

					if ( _link[ 0 ].hash !== '' ) {
						$( this ).removeClass( 'current-menu-item' );
					}
				}
			} );

			// Handler links class when scroll to target section.
			if ( $.fn.elementorWaypoint ) {
				$links.each( function() {
					var $this = $( this );
					var target = this.hash;
					var parent = $this.parent();

					if ( isValidSmoothScrollTarget( target ) ) {
						var $target = $( target );

						if ( $target.length > 0 ) {
							$target.elementorWaypoint( function( direction ) {
								if ( direction === 'down' ) {
									parent.siblings( 'li' ).removeClass( 'current-menu-item' );
									parent.addClass( 'current-menu-item' );
								}
							}, {
								offset: '25%'
							} );

							$target.elementorWaypoint( function( direction ) {
								if ( direction === 'up' ) {
									parent.siblings( 'li' ).removeClass( 'current-menu-item' );
									parent.addClass( 'current-menu-item' );
								}
							}, {
								offset: '-25%'
							} );
						}
					}
				} );
			}

			// Allows for easy implementation of smooth scrolling for navigation links.
			$links.on( 'click', function() {
				var $this = $( this );
				var target = this.hash;
				var parent = $this.parent( 'li' );

				parent.siblings( 'li' ).removeClass( 'current-menu-item' );
				parent.addClass( 'current-menu-item' );

				if ( isValidSmoothScrollTarget( target ) ) {
					handlerSmoothScroll( target );
				}

				return false;
			} );

			// Smooth scroll to section if url has hash tag when page loaded.
			var hashTag = window.location.hash;

			if ( isValidSmoothScrollTarget( hashTag ) ) {
				handlerSmoothScroll( hashTag );
			}
		}

		function initSmoothScrollLinks() {
			// Allows for easy implementation of smooth scrolling for buttons.
			$( '.smooth-scroll-link' ).on( 'click', function( e ) {
				var target = getSmoothScrollTarget( $( this ) );

				if ( isValidSmoothScrollTarget( target ) ) {
					e.preventDefault();
					e.stopPropagation();

					handlerSmoothScroll( target );
				}
			} );
		}

		function getSmoothScrollTarget( $link ) {
			var target = $link.attr( 'href' );

			if ( ! target ) {
				target = $link.data( 'href' );
			}

			return target;
		}

		function getSmoothScrollOffset() {
			if ( smoothScrollOffset ) {
				return smoothScrollOffset
			}

			var windowWidth = window.innerWidth;
			smoothScrollOffset = 0;

			// Add offset of header sticky.
			if ( $unicamp.header_sticky_enable == 1 && $pageHeader.length > 0 && $headerInner.data( 'sticky' ) == '1' ) {

				var headerHeight = $headerInner.height();

				if ( $headerInner.data( 'header-vertical' ) == '1' ) {
					if ( windowWidth < $unicamp.mobile_menu_breakpoint ) {
						smoothScrollOffset += headerHeight;
					}
				} else {
					smoothScrollOffset += headerHeight;
				}
			}

			// Add offset of admin bar when viewport min-width 600.
			if ( windowWidth > 600 ) {
				var $adminBar = $( '#wpadminbar' );

				if ( $adminBar.length > 0 ) {
					var adminBarHeight = $adminBar.height();
					smoothScrollOffset += adminBarHeight;
				}
			}

			if ( smoothScrollOffset > 0 ) {
				smoothScrollOffset = - smoothScrollOffset;
			}

			return smoothScrollOffset;
		}

		function isValidSmoothScrollTarget( selector ) {
			if ( selector.match( /^([.#])(.+)/ ) ) {
				return true;
			}

			return false;
		}

		function handlerSmoothScroll( target ) {
			var offset = getSmoothScrollOffset();

			$.smoothScroll( {
				offset: offset,
				scrollTarget: $( target ),
				speed: 600,
				easing: 'linear'
			} );
		}

		function initCategoryMenu() {
			var $catMenu = $pageHeader.find( '.header-category-dropdown' ).first();

			var RENDERED_CLASS = 'rendered';

			var placeholderItemTemplate = '<li><div class="course-item-placeholder"><div class="course-thumbnail-placeholder"></div><div class="course-caption-placeholder"><div class="course-title-placeholder"></div><div class="course-price-placeholder"></div></div></div></li>';
			var placeholderTemplate = '';

			for ( var i = 6; i > 0; i -- ) {
				placeholderTemplate += placeholderItemTemplate;
			}

			placeholderTemplate = '<ul class="children course-list course-placeholder">' + placeholderTemplate + '</ul>';

			$catMenu.on( 'mouseenter', '.cat-item.has-children', function() {
				var li = $( this );
				var id = li.data( 'id' );

				if ( ! li.hasClass( RENDERED_CLASS ) && id ) {
					li.addClass( RENDERED_CLASS );
					li.append( placeholderTemplate );

					var data = {
						action: 'unicamp_actions',
						type: 'get_course_by_cat_id',
						'cat_id': id
					};

					$.ajax( {
						url: $unicamp.ajaxurl,
						type: 'POST',
						data: data,
						dataType: 'json',
						cache: true,
						success: function( results ) {
							li.children( '.children' ).remove();

							if ( results.template ) {
								li.append( results.template );
							}
						}
					} );
				}
			} );
		}

		function initSmartmenu() {
			var primaryMenu = function() {
				var $primaryMenu = $pageHeader.find( '#page-navigation' ).find( 'ul' ).first();

				if ( ! $primaryMenu.hasClass( 'sm' ) ) {
					return;
				}

				$primaryMenu.smartmenus( {
					showTimeout: 150,
					hideTimeout: 150,
					subMenusSubOffsetX: 0,
					subMenusSubOffsetY: 0
				} );

				// Add animation for sub menu.
				$primaryMenu.on( {
					'show.smapi': function( e, menu ) {
						$( menu ).removeClass( 'hide-animation' ).addClass( 'show-animation' );
					},
					'hide.smapi': function( e, menu ) {
						$( menu ).removeClass( 'show-animation' ).addClass( 'hide-animation' );
					}
				} ).on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'ul', function( e ) {
					$( this ).removeClass( 'show-animation hide-animation' );
					e.stopPropagation();
				} );
			}

			var secondaryMenu = function() {
				var $secondaryMenu = $pageHeader.find( '#page-navigation-secondary' ).find( 'ul' ).first();

				if ( ! $secondaryMenu.hasClass( 'sm' ) ) {
					return;
				}

				$secondaryMenu.smartmenus( {
					showTimeout: 150,
					hideTimeout: 150,
					subMenusSubOffsetX: 0,
					subMenusSubOffsetY: 0
				} );

				// Add animation for sub menu.
				$secondaryMenu.on( {
					'show.smapi': function( e, menu ) {
						$( menu ).removeClass( 'hide-animation' ).addClass( 'show-animation' );
					},
					'hide.smapi': function( e, menu ) {
						$( menu ).removeClass( 'show-animation' ).addClass( 'hide-animation' );
					}
				} ).on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'ul', function( e ) {
					$( this ).removeClass( 'show-animation hide-animation' );
					e.stopPropagation();
				} );
			}

			primaryMenu();
			secondaryMenu();
		}

		function initLightGallery( $gallery ) {
			var _download   = (
				    $unicamp.light_gallery_download === '1'
			    ),
			    _autoPlay   = (
				    $unicamp.light_gallery_auto_play === '1'
			    ),
			    _zoom       = (
				    $unicamp.light_gallery_zoom === '1'
			    ),
			    _fullScreen = (
				    $unicamp.light_gallery_full_screen === '1'
			    ),
			    _share      = (
				    $unicamp.light_gallery_share === '1'
			    ),
			    _thumbnail  = (
				    $unicamp.light_gallery_thumbnail === '1'
			    );

			var options = {
				selector: '.zoom',
				mode: 'lg-fade',
				thumbnail: _thumbnail,
				download: _download,
				autoplay: _autoPlay,
				zoom: _zoom,
				share: _share,
				fullScreen: _fullScreen,
				hash: false,
				animateThumb: false,
				showThumbByDefault: false,
				getCaptionFromTitleOrAlt: false
			};

			$gallery.lightGallery( options );
		}

		function scrollToTop() {
			if ( $unicamp.scroll_top_enable != 1 ) {
				return;
			}
			var $scrollUp = $( '#page-scroll-up' );
			var lastScrollTop = 0;

			$window.on( 'scroll', function() {
				var st = $( this ).scrollTop();
				if ( st > lastScrollTop ) {
					$scrollUp.removeClass( 'show' );
				} else {
					if ( $window.scrollTop() > 200 ) {
						$scrollUp.addClass( 'show' );
					} else {
						$scrollUp.removeClass( 'show' );
					}
				}
				lastScrollTop = st;
			} );

			$scrollUp.on( 'click', function( evt ) {
				$( 'html, body' ).animate( { scrollTop: 0 }, 600 );
				evt.preventDefault();
			} );
		}

		function openMobileMenu() {
			$body.addClass( 'page-mobile-menu-opened' );

			$html.css( {
				'overflow': 'hidden'
			} );

			$( document ).trigger( 'mobileMenuOpen' );
		}

		function closeMobileMenu() {
			$body.removeClass( 'page-mobile-menu-opened' );

			$html.css( {
				'overflow': ''
			} );

			$( document ).trigger( 'mobileMenuClose' );
		}

		function calMobileMenuBreakpoint() {
			var _breakpoint = $unicamp.mobile_menu_breakpoint;
			if ( wWidth <= _breakpoint ) {
				$body.removeClass( 'desktop-menu' ).addClass( 'mobile-menu' );
			} else {
				$body.addClass( 'desktop-menu' ).removeClass( 'mobile-menu' );
			}
		}

		function initMobileMenu() {
			$( '#page-open-mobile-menu' ).on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				/**
				 * Lazy load background image.
				 */
				var $popupMobileMenu = $( '#page-mobile-main-menu' );

				if ( $popupMobileMenu.length > 0 && ! $popupMobileMenu.hasClass( 'rendered' ) ) {
					$popupMobileMenu.addClass( 'rendered' );

					var background = $popupMobileMenu.data( 'background' );
					if ( '' !== background ) {
						$popupMobileMenu.children( '.inner' ).css( 'background-image', 'url(' + background + ')' );
					}
				}

				openMobileMenu();
			} );

			$( '#page-close-mobile-menu' ).on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				closeMobileMenu();
			} );

			$( '#page-mobile-main-menu' ).on( 'click', function( e ) {
				if ( e.target !== this ) {
					return;
				}

				closeMobileMenu();
			} );

			var menu = $( '#mobile-menu-primary' );

			menu.on( 'click', 'a', function( e ) {
				var $this = $( this );
				var _li = $( this ).parent( 'li' );
				var target = getSmoothScrollTarget( $this );

				if ( $body.hasClass( 'one-page' ) && isValidSmoothScrollTarget( target ) ) {
					closeMobileMenu();

					_li.siblings( 'li' ).removeClass( 'current-menu-item' );
					_li.addClass( 'current-menu-item' );

					setTimeout( function() {
						handlerSmoothScroll( target );
					}, 300 );

					return false;
				}
			} );

			menu.on( 'click', '.toggle-sub-menu', function( e ) {
				var _li = $( this ).parents( 'li' ).first();

				e.preventDefault();
				e.stopPropagation();

				var _friends = _li.siblings( '.opened' );
				_friends.removeClass( 'opened' );
				_friends.find( '.opened' ).removeClass( 'opened' );
				_friends.find( '.sub-menu' ).stop().slideUp();

				if ( _li.hasClass( 'opened' ) ) {
					_li.removeClass( 'opened' );
					_li.find( '.opened' ).removeClass( 'opened' );
					_li.find( '.sub-menu' ).stop().slideUp();
				} else {
					_li.addClass( 'opened' );
					_li.children( '.sub-menu' ).stop().slideDown();
				}
			} );
		}

		function initOffCanvasMenu() {
			var menu = $( '#off-canvas-menu-primary' );
			var $popup = $( '#popup-canvas-menu' );

			$( '#page-open-main-menu' ).on( 'click', function( e ) {
				e.preventDefault();
				$popup.addClass( 'open' );
				$body.addClass( 'page-popup-open' );
			} );

			$( '#page-close-main-menu' ).on( 'click', function( e ) {
				e.preventDefault();
				$body.removeClass( 'page-popup-open' );
				$popup.removeClass( 'open' );
				menu.find( '.sub-menu' ).slideUp();
			} );

			menu.on( 'click', '.menu-item-has-children > a, .page_item_has_children > a', function( e ) {
				var _li = $( this ).parent( 'li' );

				console.log( 'run' );

				if ( _li.hasClass( 'has-mega-menu' ) ) {
					return;
				}

				e.preventDefault();
				e.stopPropagation();

				var _friends = _li.siblings( '.opened' );
				_friends.removeClass( 'opened' );
				_friends.find( '.opened' ).removeClass( 'opened' );
				_friends.find( '.sub-menu, .children' ).stop().slideUp();

				if ( _li.hasClass( 'opened' ) ) {
					_li.removeClass( 'opened' );
					_li.find( '.opened' ).removeClass( 'opened' );
					_li.find( '.sub-menu, .children' ).stop().slideUp();
				} else {
					_li.addClass( 'opened' );
					_li.children( '.sub-menu, .children' ).stop().slideDown();
				}
			} );
		}

		function initStickyHeader() {
			var $headerHolder = $pageHeader.children( '.page-header-place-holder' );
			if ( $unicamp.header_sticky_enable == 1 && $pageHeader.length > 0 && $headerInner.data( 'sticky' ) == '1' ) {
				if ( $headerInner.data( 'header-vertical' ) != '1' ) {
					var _hOffset = $headerInner.offset().top;

					// Fix offset top return negative value on some devices.
					if ( _hOffset < 0 ) {
						_hOffset = 0;
					}

					var _hHeight = $headerInner.outerHeight();
					var offset = _hOffset + _hHeight + 100;

					if ( ! $pageHeader.hasClass( 'header-layout-fixed' ) ) {
						var _hHeight = $headerInner.outerHeight();

						$headerHolder.height( _hHeight );
						$headerInner.addClass( 'held' );
					}

					$pageHeader.headroom( {
						offset: offset,
						onTop: function() {
							if ( ! $pageHeader.hasClass( 'header-layout-fixed' ) ) {

								setTimeout( function() {
									var _hHeight = $headerInner.outerHeight();

									$headerHolder.height( _hHeight );
								}, 300 );
							}
						},
					} );
				} else {
					if ( wWidth <= $unicamp.mobile_menu_breakpoint ) {
						if ( ! $pageHeader.data( 'headroom' ) ) {
							var _hOffset = $headerInner.offset().top;

							// Fix offset top return negative value on some devices.
							if ( _hOffset < 0 ) {
								_hOffset = 0;
							}

							var _hHeight = $headerInner.outerHeight();
							var offset = _hOffset + _hHeight + 100;

							$pageHeader.headroom( {
								offset: offset
							} );
						}
					} else {
						if ( $pageHeader.data( 'headroom' ) ) {
							$pageHeader.data( 'headroom' ).destroy();
							$pageHeader.removeData( 'headroom' );
						}
					}
				}
			}
		}

		function initSearchPopup() {
			var popupSearch = $( '#popup-search' );

			$( '#page-open-popup-search' ).on( 'click', function( e ) {
				e.preventDefault();

				$body.addClass( 'page-search-popup-opened' );
				popupSearch.addClass( 'open' );

				setTimeout( function() {
					popupSearch.find( '.search-field' ).focus();
				}, 500 );
			} );

			$( '#search-popup-close' ).on( 'click', function( e ) {
				e.preventDefault();

				$body.removeClass( 'page-search-popup-opened' );
				popupSearch.removeClass( 'open' );
			} );
		}

		function initHeaderRightMoreTools() {
			var toggleBtn = $( '#page-open-components' );
			toggleBtn.on( 'click', function() {
				$body.toggleClass( 'header-more-tools-opened' );
			} );


			$( document ).on( 'click', function( evt ) {
				if ( evt.target.id === 'header-right-inner' ) {
					return;
				}

				if ( $( evt.target ).closest( '#header-right-inner' ).length ) {
					return;
				}

				if ( $( evt.target ).closest( toggleBtn ).length ) {
					return;
				}

				$body.removeClass( 'header-more-tools-opened' );
			} );
		}

		function initCookieNotice() {
			if ( $unicamp.noticeCookieEnable == 1 && $unicamp.noticeCookieConfirm != 'yes' && $unicamp.noticeCookieMessages != '' ) {

				$.growl( {
					location: 'br',
					fixed: true,
					duration: 3600000,
					size: 'large',
					title: '',
					message: $unicamp.noticeCookieMessages
				} );

				$( '#tm-button-cookie-notice-ok' ).on( 'click', function() {
					$( this ).parents( '.growl-message' ).first().siblings( '.growl-close' ).trigger( 'click' );

					var _data = {
						action: 'notice_cookie_confirm'
					};

					_data = $.param( _data );

					$.ajax( {
						url: $unicamp.ajaxurl,
						type: 'POST',
						data: _data,
						dataType: 'json',
						success: function( results ) {

						},
						error: function( errorThrown ) {
							console.log( errorThrown );
						}
					} );
				} );
			}
		}

		function initFooterAlwaysBottom() {
			var height = window.innerHeight;

			$( '#page' ).css( 'min-height', height + 'px' );
		}

		function handlerVerticalHeader() {
			if ( $headerInner.data( 'header-vertical' ) != '1' ) {
				return;
			}

			var _wWidth = window.innerWidth;

			if ( _wWidth <= $unicamp.mobile_menu_breakpoint ) {
				$html.css( {
					marginLeft: 0
				} );
			} else {
				var headerWidth = $headerInner.outerWidth();
				$html.css( {
					marginLeft: headerWidth + 'px'
				} );
			}
		}

		function handlerArchiveFiltering() {
			var $form = $( '#archive-form-filtering' );

			if ( $form.length > 0 ) {
				var $select = $form.find( 'select' );

				$select.on( 'change', function() {
					$( this ).closest( 'form' ).submit();
				} );
			}
		}
	}( jQuery )
);
