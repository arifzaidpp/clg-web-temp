(
	function( $ ) {
		'use strict';

		var UnicampGridPlugin = function( $el, options ) {
			this._isotopeOptions = {
				itemSelector: '.grid-item',
				percentPosition: true,
				transitionDuration: 0,
				packery: {
					columnWidth: '.grid-sizer',
				},
				fitRows: {
					gutter: 10
				}
			};

			this.init = function() {
				var plugin = this;
				var resizeTimer;
				var $grid = $el.find( '.unicamp-grid' );

				var settings = $el.data( 'grid' );

				var gridData;

				if ( $grid.length > 0 && settings && typeof settings.type !== 'undefined' ) {
					if ( $unicamp.isRTL ) {
						plugin._isotopeOptions.originLeft = false;
					}

					if ( 'masonry' === settings.type || 'metro' === settings.type ) {
						plugin._isotopeOptions.layoutMode = 'packery';
					} else {
						plugin._isotopeOptions.layoutMode = 'fitRows';
					}

					gridData = $grid.imagesLoaded( function() {
						plugin.calculateMasonrySize();
						if ( 'grid' === settings.type ) {
							$grid.children( '.grid-item' ).matchHeight();
							$grid.isotope( 'layout' );
						}
						$grid.addClass( 'loaded' );
					} );

					gridData.one( 'arrangeComplete', function() {
						plugin.handlerEntranceAnimation();
						plugin.handlerTooltips();
					} );

					$( window ).on( 'resize', function() {
						plugin.calculateMasonrySize();

						// Sometimes layout can be overlap. then re-cal layout one time.
						clearTimeout( resizeTimer );
						resizeTimer = setTimeout( function() {
							// Run code here, resizing has "stopped"
							plugin.calculateMasonrySize();
						}, 500 ); // DO NOT decrease the time. Sometime, It'll make layout overlay on resize.
					} );
				} else {
					$grid.addClass( 'loaded' );
					plugin.handlerEntranceAnimation();
					plugin.handlerTooltips();
				}

				$el.on( 'UnicampQueryEnd', function( event, el, $items ) {
					plugin.update( $items );
				} );
			};

			this.update = function( $items ) {
				var plugin = this;
				var settings = $el.data( 'grid' );
				var $grid = $el.children( '.unicamp-grid' );

				if ( $grid.length > 0 && settings && typeof settings.type !== 'undefined' ) {
					$grid.isotope()
					     .append( $items )
					     .isotope( 'reloadItems', $items )
					     .imagesLoaded()
					     .always( function() {
						     $items.addClass( 'animate' );
						     plugin.calculateMasonrySize( plugin._isotopeOptions );
						     if ( 'grid' === settings.type ) {
							     $grid.children( '.grid-item' ).matchHeight();
							     $grid.isotope( 'layout' );
						     }
					     } );
				} else {
					$grid.append( $items ).imagesLoaded().always( function() {
						$items.addClass( 'animate' );
					} );
				}
			};

			this.calculateMasonrySize = function() {
				var plugin = this;
				var $grid = $el.children( '.unicamp-grid' );

				var windowWidth    = window.innerWidth,
				    $gridWidth     = $grid[ 0 ].getBoundingClientRect().width,
				    $column        = 1,
				    $gutter        = 0,
				    $zigzagHeight  = 0,
				    settings       = $el.data( 'grid' ),
				    lgGutter       = settings.gutter !== undefined ? settings.gutter : 0,
				    mdGutter       = settings.gutterTablet !== undefined ? settings.gutterTablet : lgGutter,
				    smGutter       = settings.gutterMobile !== undefined ? settings.gutterMobile : mdGutter,
				    lgColumns      = settings.columns !== undefined ? settings.columns : 1,
				    mdColumns      = settings.columnsTablet !== undefined ? settings.columnsTablet : lgColumns,
				    smColumns      = settings.columnsMobile !== undefined ? settings.columnsMobile : mdColumns,
				    lgZigzagHeight = settings.zigzagHeight !== undefined ? settings.zigzagHeight : 0,
				    mdZigzagHeight = settings.zigzagHeightTablet !== undefined ? settings.zigzagHeightTablet : lgZigzagHeight,
				    smZigzagHeight = settings.zigzagHeightMobile !== undefined ? settings.zigzagHeightMobile : mdZigzagHeight,
				    zigzagReversed = settings.zigzagReversed !== undefined && settings.zigzagReversed === 1 ? true : false;

				var tabletBreakPoint = 1025;
				var mobileBreakPoint = 768;

				if ( typeof elementorFrontendConfig !== 'undefined' ) {
					tabletBreakPoint = elementorFrontendConfig.breakpoints.lg;
					mobileBreakPoint = elementorFrontendConfig.breakpoints.md;
				}

				if ( windowWidth >= tabletBreakPoint ) {
					$column = lgColumns;
					$gutter = lgGutter;
					$zigzagHeight = lgZigzagHeight;
				} else if ( windowWidth >= mobileBreakPoint ) {
					$column = mdColumns;
					$gutter = mdGutter;
					$zigzagHeight = mdZigzagHeight;
				} else {
					$column = smColumns;
					$gutter = smGutter;
					$zigzagHeight = smZigzagHeight;
				}

				$el.attr( 'data-active-columns', $column );

				var totalGutterPerRow = (
					                        $column - 1
				                        ) * $gutter;

				var columnWidth = (
					                  $gridWidth - totalGutterPerRow
				                  ) / $column;

				columnWidth = Math.floor( columnWidth );

				var columnWidth2 = columnWidth;
				if ( $column > 1 ) {
					columnWidth2 = columnWidth * 2 + $gutter;
				}

				$grid.children( '.grid-sizer' ).css( {
					'width': columnWidth + 'px'
				} );

				var columnHeight   = 0,
				    columnHeight2  = 0, // 200%.
				    columnHeight7  = 0, // 70%.
				    columnHeight13 = 0, // 130%.
				    isMetro        = false,
				    ratioW         = 1,
				    ratioH         = 1;

				if ( settings.ratio ) {
					ratioH = settings.ratio;
					isMetro = true;
				}

				// Calculate item height for only metro type.
				if ( isMetro ) {
					columnHeight = columnWidth * ratioH / ratioW;
					columnHeight = Math.floor( columnHeight );

					if ( $column > 1 ) {
						columnHeight2 = columnHeight * 2 + $gutter;
						columnHeight13 = parseInt( columnHeight * 1.3 );
						columnHeight7 = columnHeight2 - $gutter - columnHeight13;
					} else {
						columnHeight2 = columnHeight7 = columnHeight13 = columnHeight;
					}
				}

				$grid.children( '.grid-item' ).each( function( index ) {
					var gridItem = $( this );

					// Zigzag.
					if (
						$zigzagHeight > 0 // Has zigzag.
						&& $column > 1 // More than 1 column.
						&& index + 1 <= $column // On top items.
					) {
						if ( zigzagReversed === false ) { // Is odd item.
							if ( index % 2 === 0 ) {
								gridItem.css( {
									'marginTop': $zigzagHeight + 'px'
								} );
							} else {
								gridItem.css( {
									'marginTop': '0px'
								} );
							}
						} else {
							if ( index % 2 !== 0 ) {
								gridItem.css( {
									'marginTop': $zigzagHeight + 'px'
								} );
							} else {
								gridItem.css( {
									'marginTop': '0px'
								} );
							}
						}

					} else {
						gridItem.css( {
							'marginTop': '0px'
						} );
					}

					if ( gridItem.data( 'width' ) === 2 ) {
						gridItem.css( {
							'width': columnWidth2 + 'px'
						} );
					} else {
						gridItem.css( {
							'width': columnWidth + 'px'
						} );
					}

					if ( 'grid' === settings.type ) {
						gridItem.css( {
							'marginBottom': $gutter + 'px'
						} );
					}

					if ( isMetro ) {
						var $itemHeight;

						if ( gridItem.hasClass( 'grid-item-height' ) ) {
							$itemHeight = gridItem;
						} else {
							$itemHeight = gridItem.find( '.grid-item-height' );
						}

						if ( gridItem.data( 'height' ) === 2 ) {
							$itemHeight.css( {
								'height': columnHeight2 + 'px'
							} );
						} else if ( gridItem.data( 'height' ) === 1.3 ) {
							$itemHeight.css( {
								'height': columnHeight13 + 'px'
							} );
						} else if ( gridItem.data( 'height' ) === 0.7 ) {
							$itemHeight.css( {
								'height': columnHeight7 + 'px'
							} );
						} else {
							$itemHeight.css( {
								'height': columnHeight + 'px'
							} );
						}
					}
				} );

				if ( plugin._isotopeOptions ) {
					plugin._isotopeOptions.packery.gutter = $gutter;
					plugin._isotopeOptions.fitRows.gutter = $gutter;
					$grid.isotope( plugin._isotopeOptions );
				}

				$grid.isotope( 'layout' );
			};

			this.handlerEntranceAnimation = function() {
				var $grid = $el.children( '.unicamp-grid' );

				// Used find() for flex layout.
				var items = $grid.find( '.grid-item' );

				items.elementorWaypoint( function() {
					// Fix for different ver of waypoints plugin.
					var _self = this.element ? this.element : this;
					var $self = $( _self );
					$self.addClass( 'animate' );
				}, {
					offset: '90%',
					triggerOnce: true
				} );
			};

			this.handlerTooltips = function() {
				var tooltipSettings = $el.data( 'power-tip' );

				if ( tooltipSettings && $.powerTip ) {
					$el.find( '.unicamp-tooltip' ).each( function( i, o ) {
						var target = $( this ).attr( 'data-tooltip' );

						$( this ).powerTip( {
							placement: tooltipSettings.placement,
							popupClass: tooltipSettings.popupClass,
							smartPlacement: true,
							mouseOnToPopup: true,
						} );

						$( this ).data( 'powertiptarget', target );
					} );
				}
			}
		};

		$.fn.UnicampGridLayout = function( methodOrOptions ) {
			var method = (
				typeof methodOrOptions === 'string'
			) ? methodOrOptions : undefined;

			if ( method ) {
				var unicampGridLayouts = [];

				this.each( function() {
					var $el = $( this );
					var UnicampGridLayout = $el.data( 'UnicampGridLayout' );

					unicampGridLayouts.push( UnicampGridLayout );
				} );

				var args = (
					arguments.length > 1
				) ? Array.prototype.slice.call( arguments, 1 ) : undefined;

				var results = [];

				this.each( function( index ) {
					var UnicampGridLayout = unicampGridLayouts[ index ];

					if ( ! UnicampGridLayout ) {
						console.warn( '$.UnicampGridLayout not instantiated yet' );
						console.info( this );
						results.push( undefined );
						return;
					}

					if ( typeof UnicampGridLayout[ method ] === 'function' ) {
						var result = UnicampGridLayout[ method ].apply( UnicampGridLayout, args );
						results.push( result );
					} else {
						console.warn( 'Method \'' + method + '\' not defined in $.UnicampGridLayout' );
					}
				} );

				return (
					results.length > 1
				) ? results : results[ 0 ];
			} else {
				var options = (
					typeof methodOrOptions === 'object'
				) ? methodOrOptions : undefined;

				return this.each( function() {
					var $el = $( this );
					var UnicampGridLayout = new UnicampGridPlugin( $el, options );

					$el.data( 'UnicampGridLayout', UnicampGridLayout );

					UnicampGridLayout.init();
				} );
			}

		};
	}( jQuery )
);
