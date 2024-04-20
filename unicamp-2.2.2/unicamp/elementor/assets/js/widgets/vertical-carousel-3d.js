(
	function( $, window, document, undefined ) {
		'use strict';

		var pluginName = 'UnicampCarousel3D';
		var defaults = {
			itemsSelector: '.carousel-item',
			scale: 0.85,
			transform: '40%'
		};

		function Plugin( element, options ) {

			this.element = element;

			this.options = $.extend( {}, defaults, options );

			this._defaults = defaults;
			this._name = pluginName;

			this.build();
		}

		Plugin.prototype = {

			build: function build() {
				this.init();
			},

			init: function init() {

				var self = this;
				var element = $( self.element );
				var items = self.options.itemsSelector;

				self.prepareItems();

				var activeItem = $( items, element ).first();
				var bottomItem = activeItem.next();
				var topItem = bottomItem.next();

				self.dragY = 0;
				self.startY = 0;
				self.currentY = 0;

				self.setActive( activeItem, element );
				self.initAnime( element, activeItem, topItem, bottomItem );
				self.initDrag();
				self.initClicks();

				element.addClass( 'carousel-initialized' );

				return self;
			},

			prepareItems: function prepareItems() {
				var self = this;
				var items = $( self.options.itemsSelector, self.element );

				if ( items.length <= 2 && items.length >= 1 ) {

					var firstItem = items[ 0 ];

					for ( var i = items.length; i <= 2; i ++ ) {

						$( firstItem ).clone( true ).appendTo( $( self.element ).find( '.carousel-items' ) );
					}
				}
			},

			setActive: function setActive( activeItem, element ) {
				var self = this;
				var items = self.options.itemsSelector;
				var currentTopItem = $( '.is-top', element );
				var currentActiveItem = $( '.is-active', element );
				var currentBottomItem = $( '.is-bottom', element );
				var firstItem = $( items, element ).first();
				var lastItem = $( items, element ).last();

				if ( currentTopItem.length ) {
					currentTopItem.addClass( 'was-top' );
				}
				if ( currentActiveItem.length ) {
					currentActiveItem.addClass( 'was-active' );
				}
				if ( currentBottomItem.length ) {
					currentBottomItem.addClass( 'was-bottom' );
				}

				activeItem.addClass( 'is-active' ).removeClass( 'is-top is-bottom' ).siblings().removeClass( 'is-active' );

				self.setBottom( activeItem );
				self.setTop( activeItem );
			},

			// Bottom Item will be based on the active item.
			setBottom: function setBottom( activeItem ) {
				var self = this;
				var element = $( self.element );
				var items = self.options.itemsSelector;
				var firstItem = $( items, element ).first();
				var lastItem = $( items, element ).last();

				var bottomItem = activeItem.next();

				if ( ! bottomItem.length && activeItem.is( ':last-child' ) ) {

					bottomItem = firstItem;
				}

				bottomItem.addClass( 'is-bottom' ).removeClass( 'is-active is-top was-active' ).siblings().removeClass( 'is-bottom' );
			},

			// Top Item will be based on the active item.
			setTop: function setTop( activeItem ) {
				var self = this;
				var element = $( self.element );
				var items = self.options.itemsSelector;
				var lastItem = $( items, element ).last();
				var firstItem = $( items, element ).first();

				var topItem = activeItem.prev();

				if ( ! topItem.length && activeItem.is( ':first-child' ) ) {

					topItem = lastItem;
				}

				topItem.addClass( 'is-top' ).removeClass( 'is-active is-bottom was-active' ).siblings().removeClass( 'is-top' );
			},

			initAnime: function initAnime( element, activeItem, topItem, bottomItem ) {
				var self = this;

				self.animInited = false;

				if ( ! self.animInited ) {

					var animeTimeline = anime.timeline( {
						duration: 0,
						easing: 'linear'
					} );

					animeTimeline.add( {
						targets: topItem.get( 0 ),
						translateY: '-40%',
						translateZ: - 50,
						scale: self.options.scale
					} ).add( {
						targets: activeItem.get( 0 ),
						translateZ: 0,
						scale: 1
					} ).add( {
						targets: bottomItem.get( 0 ),
						translateY: '40%',
						translateZ: - 50,
						scale: self.options.scale
					} );

					self.animInited = true;
				}
			},

			initClicks: function initClicks() {
				$( this.element ).on( 'click', '.is-top', this.moveItems.bind( this, 'prev' ) );
				$( this.element ).on( 'click', '.is-bottom', this.moveItems.bind( this, 'next' ) );
			},

			initDrag: function initDrag() {
				var self = this;
				var element = $( self.element );

				element.on( 'mousedown', self.pointerStart.bind( self ) );
				element.on( 'mousemove', self.pointerMove.bind( self ) );
				element.on( 'mouseup', self.pointerEnd.bind( self ) );
			},

			pointerStart: function pointerStart( event ) {

				var self = this;
				var element = $( self.element );
				var items = $( self.options.itemsSelector );

				self.startY = event.pageY || event.touches[ 0 ].pageY;
				self.currentY = self.startY;

				element.addClass( 'pointer-down' );
			},

			pointerMove: function pointerMove( event ) {

				var self = this;
				var element = $( self.element );

				self.currentY = event.pageY || event.touches[ 0 ].pageY;

				self.dragY = parseInt( self.startY - self.currentY, 10 );
			},

			pointerEnd: function pointerEnd( event ) {
				var self = this;
				var element = $( self.element );
				var items = $( self.options.itemsSelector );

				self.dragY = parseInt( self.startY - self.currentY, 10 );

				if ( self.dragY >= 20 ) {

					self.moveItems( 'next' );
				} else if ( self.dragY <= - 20 ) {

					self.moveItems( 'prev' );
				}

				element.removeClass( 'pointer-down' );
			},

			moveItems: function moveItems( dir ) {
				var _this = this;

				if ( $( this.element ).hasClass( 'items-moving' ) ) {
					return;
				}

				var self = this;
				var element = $( self.element );
				var items = $( self.options.itemsSelector );
				var bottomItem = $( '.is-bottom', element );
				var topItem = $( '.is-top', element );

				var animationTimeline = anime.timeline( {
					duration: 1000,
					easing: 'easeInOutQuad',
					run: function run() {
						$( items, element ).addClass( 'is-moving' );
					},
					complete: function complete() {
						$( items, element ).removeClass( 'is-moving was-top was-active was-bottom' );
						$( _this.element ).addClass( 'items-moved' ).removeClass( 'items-moving' );
					}
				} );

				if ( dir === 'next' ) {
					self.setActive( bottomItem, element );
				} else if ( dir === 'prev' ) {
					self.setActive( topItem, element );
				}

				var newActiveItem = $( '.is-active', element );
				var newBottomItem = $( '.is-bottom', element );
				var newTopItem = $( '.is-top', element );

				if ( dir === 'next' ) {
					self.moveNext( animationTimeline, newActiveItem, newBottomItem, newTopItem );
				} else if ( dir === 'prev' ) {
					self.movePrev( animationTimeline, newActiveItem, newBottomItem, newTopItem );
				}
			},

			moveNext: function moveNext( animationTimeline, newActiveItem, newBottomItem, newTopItem ) {
				this.move( animationTimeline, newActiveItem, newBottomItem, newTopItem );
			},

			movePrev: function movePrev( animationTimeline, newActiveItem, newBottomItem, newTopItem ) {
				this.move( animationTimeline, newActiveItem, newBottomItem, newTopItem );
			},

			move: function move( animationTimeline, newActiveItem, newBottomItem, newTopItem ) {
				$( this.element ).removeClass( 'items-moved' ).addClass( 'items-moving' );
				var self = this;

				animationTimeline.add( {
					targets: newTopItem.get( 0 ),
					translateY: [
						{ value: '-45%' },
						{
							value: '-40%',
							easing: 'linear'
						}
					],
					translateZ: [
						{ value: - 50 },
					],
					rotateX: [
						{ value: 12 },
						{ value: 0 }
					],
					scale: self.options.scale,
					perspective: 1,
				}, 0 ).add( {
					targets: newActiveItem.get( 0 ),
					translateY: '0%',
					translateZ: [
						{ value: 50 },
						{ value: 0 },
					],
					rotateX: [
						{ value: 12 },
						{ value: 0 }
					],
					scale: 1,
					perspective: 1,
				}, 0 ).add( {
					targets: newBottomItem.get( 0 ),
					translateY: [
						{ value: '45%' },
						{
							value: '40%',
							easing: 'linear'
						}
					],
					translateZ: [
						{ value: - 50 },
					],
					rotateX: [
						{ value: 12 },
						{ value: 0 }
					],
					scale: self.options.scale,
					perspective: 1,
				}, 0 );
			}
		};

		$.fn[ pluginName ] = function( options ) {

			return this.each( function() {

				var pluginOptions = $( this ).data( 'plugin-options' );
				var opts = null;

				if ( pluginOptions ) {
					opts = $.extend( true, {}, options, pluginOptions );
				}

				if ( ! $.data( this, "plugin_" + pluginName ) ) {

					$.data( this, "plugin_" + pluginName, new Plugin( this, opts ) );
				}
			} );
		};
	}
)( jQuery, window, document );
