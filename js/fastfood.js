var fastfoodAnimations;

( function( $ ) {

	fastfoodAnimations = {

		//initialize
		init : function( modules ) {

			for ( i in modules ) {

				switch( modules[i] ) {

					case 'main_menu':
						if ( $( 'body' ).is( '.layout-2,.layout-1' ) )
							this.main_menu();
						break;

					case 'navigation_buttons':
						if ( $( 'body' ).is( '.layout-2' ) )
							this.navigation_buttons();
						break;

					case 'smooth_scroll':
						this.smooth_scroll();
						break;

					case 'quickbar_panels':
						this.quickbar_panels();
						break;

					case 'entry_meta':
						this.entry_meta();
						break;

					case 'post_expander':
						this.post_expander();
						break;

					case 'thickbox':
						this.thickbox();
						$( 'body' ).on( 'post-load', function( event ){ //Jetpack Infinite Scroll trigger
							fastfoodAnimations.thickbox();
						} );
						break;

					case 'get_comments':
						this.get_comments();
						break;

					case 'quote_this':
						this.quote_this();
						break;

					case 'tinynav':
						this.tinynav();
						break;

					case 'captions':
						this.captions();
						break;

					case 'sticky_menu':
						if ( $( 'body' ).is( '.layout-2,.layout-1' ) )
							this.sticky_menu();
						break;

					case 'featured_slideshow':
						this.featured_slideshow();
						break;

					case 'boobs':
						alert('( . )( . ) boobs alert!!!');
						break;

					default :
						//no default action
						break;

				}

			}

		},


		main_menu : function() {

			//main menu dropdown animation
			$( '.nav-menu.all-levels' )
				.children( '.menu-item-parent' ).each( function(){ //get every main list item

					var $this         = $( this );
					var d             = $this.children( 'ul' ); //for each main item, get the sub list
					var margintop_in  = 20; //the starting distance between menu item and the popup submenu
					var margintop_out = 20; //the exiting distance between menu item and the popup submenu

					if( d.size() !== 0 ){ //if the sub list exists...

						d.css( { 'opacity' : 0 , 'margin-top' : margintop_in } );

						$this.hoverIntent(
							function(){ //when mouse enters, slide down the sub list
								offset = d.offset();
								if ( ( offset.left + d.outerWidth( true ) + 5 ) > $( document ).width() )
									d.addClass( 'right' );
								d.css( { 'display' : 'block' } ).animate(
									{ 'opacity' : 1 , 'margin-top' : 0 },
									400
								);
							},
							function(){ //when mouse leaves, hide the sub list
								d.stop().animate(
									{ 'opacity' : 0 , 'margin-top' : margintop_out },
									200,
									function(){ d.css( { 'display' : '' , 'margin-top' : margintop_in } ).removeClass( 'right' ); }
								);
							}
						);
					}
				} );

		},


		navigation_buttons : function() {

			//navbuttons tooltip animation
			$( '#navbuttons' )
				.children( '.minibutton' ).each( function(){ //get every minibutton

					var $this          = $( this );
					var list           = $this.find( 'span.nb_tooltip' );
					var marginright_in = 61; //the starting distance between menu item and the popup submenu

					list.css( { 'opacity' : 0 , 'right' : marginright_in } );

					$this.hoverIntent(
						function(){ //when mouse enters, shift right the tooltip
							list.css( { 'display' : 'block' } ).animate(
								{ 'opacity' : 1 , 'right' : 41 },
								200
							);
						},
						function(){ //when mouse leaves, hide the tooltip
							list.stop().css( { 'opacity' : 0, 'display' : '', 'right' : marginright_in } );
						}
					);
				} );

		},


		smooth_scroll : function() {

			var scroll_TO = false; //the TimeOut for functions fired on window scroll
			var top_but   = $( '#navbuttons a[href="#"]' );
			var bot_but   = $( '#navbuttons a[href="#footer"]' );

			// fade in/out on scroll
			top_but.hide();
			$(window).scroll( function () {

				if( scroll_TO !== false )
					clearTimeout( scroll_TO );

				scroll_TO = setTimeout( function() {

					// check for top action
					if ($( this ).scrollTop() > 100) {
						top_but.stop().fadeIn();
					} else {
						top_but.stop().fadeOut();
					}

					// check for bottom action
					if ( $( 'body' ).height()-$(window).scrollTop()-$(window).height() < 100) {
						bot_but.stop().fadeOut();
					} else {
						bot_but.stop().fadeIn();
					}

				}, 200 ); //200 is time in miliseconds

			} );

			// smooth scroll top
			top_but.click( function() {
				$("html, body").animate( {
					scrollTop: 0
				}, {
					duration: 1000
				} );
				return false;
			} );

			// smooth scroll bottom
			bot_but.click( function() {
				$("html, body").animate( {
					scrollTop: $( '#footer' ).offset().top - 80
				}, {
					duration: 1000
				} );
				return false;
			} );

		},


		quickbar_panels : function() {

			//quickbar animation
			var panels = $( '#quickbar' ).children( '.quickbar-panel' );
			var panels_count = Math.max( 6, panels.length );

			panels.each( function(){ //get every quickbar item

				var $this = $( this );
				var list = $this.children( '.quickbar-panel-container' ); // get the sub list for each quickbar item

				list.css( { 'height' : 0 } ).hide();

				$this.hoverIntent(
					function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
						if ( $( '#panel-user' ).hasClass( 'keepme' ) ) return;
						list.stop().show().animate(
							{ 'height': panels_count * 50 },
							400
						);
					},
					function(){ //when mouse leaves, hide the submenu
						if ( $( '#panel-user' ).hasClass( 'keepme' ) ) return;
						list.stop().css( { 'height' : 0 } ).hide();
					}
				);
			} );

			//add a "close" link after the submit button in minilogin form
			$( '<a id="closeminilogin" href="#">' + _fastfoodL10n.close_minilogin + '</a>' )
				.appendTo( '#minilogin .login-submit' )
				.click( function() {
					$( '.quickbar-panel-icon' ).fadeIn();
					$( '#panel-user .quickbar-panel-container' ).hide();
					$( '#panel-user' ).removeClass( 'keepme' );
					return false;
				} );

			//preserve the menu div from disappear when loginform name input is clicked
			$( '#panel-user:not(.keepme) input' ).mousedown( function() {
				$( '#panel-user' ).addClass( 'keepme' );
				$( '.quickbar-panel-icon' ).fadeOut();
			} );

		},


		entry_meta : function() {

			$( 'body' )
				.addClass( 'metadata-js' );

			var margintop_in  = 20; //the starting distance between menu item and the popup submenu

			$( '#posts-content' ).hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					$( '.metadata-panel-content', this )
						.slideDown();
				},
				function(){ //when mouse leaves, hide the sub list
					$( '.metadata-panel-content', this )
						.stop( true , true )
						.hide();
				},
				'.metadata-panel'
			);

		},


		thickbox : function() {

			$( '#posts-content' )
				.find( '.entry-content a img' )
					.parent( 'a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]' )
						.addClass( 'thickbox' );

			$( '#posts-content' )
				.find( '.entry-content .gallery' ).each( function() {

					var $this = $( this );

					$( 'a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]', $this )
						.attr( 'rel', $this.attr( 'id' ) );
				} );

		},


		quote_this : function () {

			htmltext = '<a id="tb-quotethis" href="#" onclick="fastfoodAnimations.add_quote(); return false" title="' + _fastfoodL10n.quote_link_info + '" ><i class="el-icon-quotes"></i></a>'
			$( htmltext ).insertBefore( '#comment' );

		},


		add_quote : function() {

			var posttext = '';

			if ( window.getSelection ){
				posttext = window.getSelection();
			}
			else if ( document.getSelection ){
				posttext = document.getSelection();
			}
			else if ( document.selection ){
				posttext = document.selection.createRange().text;
			}
			else {
				return true;
			}
			posttext = posttext.toString().replace( /&/g, "&amp;" ).replace( /"/g, "&quot;" ).replace( /</g, "&lt;" ).replace( />/g, "&gt;" );
			if ( posttext.length !== 0 ) {
				document.getElementById( "comment" ).value = document.getElementById( "comment" ).value + '<blockquote>' + posttext + '</blockquote>';
			} else {
				alert( _fastfoodL10n.quote_link_alert );
			}

		},


		post_expander : function () {

			$( '#posts-content' ).on( 'click.postexpander', 'a.more-link', function ( e ) {
				e.preventDefault();

				var link = $( this );

				$.ajax( {
					type: 'POST',
					url: link.attr( "href" ),
					beforeSend: function( XMLHttpRequest ) {
						link.html( _fastfoodL10n.post_expander_wait ).addClass( 'ajaxed' );
					},
					data: 'ff_post_expander=1',
					success: function( data ) {
						link.parents( ".entry-content" ).hide().html( $( data ) ).fadeIn( 600 );
					}
				} );

			} );

		},


		get_comments : function () {

			var navigation = $( '.navigate_comments' );

			navigation.find( 'a' ).click( function() {

				var link = $( this );

				$.ajax( {
					type: 'POST',
					url: link.attr( "href" ),
					beforeSend: function( XMLHttpRequest ) {
						link.addClass( 'ajaxed' );
					},
					data: 'ff_get_comments_page=1',
					success: function( data ) {
						$( '#commentlist-wrap' ).html( $( data ) );
						fastfoodAnimations.get_comments();
						$( 'body' ).trigger( 'comments-loaded' );
					}
				} );

				return false;

			} );

		},


		headerSlider : function ( options ) {

			// set default options
			var defaults = {
				speed : 2000, //duration of the animation
				pause : 3000 //pause between animations
			},

			// Take the options that the user selects, and merge them with defaults.
			options = $.extend( defaults, options );

			return $( '#head-image.slider' ).each( function() {

				// cache "this."
				var $this = $( this );
				var $images = $( 'img', $this );
				var $parent = $images.parent();

				if ( $images.size() > 1 ) {
					// call the slide function.
					slide();
					timId = loo_slide();
				}

				function loo_slide() {
					timId = setInterval( function() {
						slide();
					}, ( options.speed + options.pause ) );
					return timId;
				}

				function slide() {
					setTimeout( function() {
						$this
							.find( 'img:last' )
							.fadeIn( options.speed, function() { 
								$( this )
									.prependTo($parent)
									.css( "display", "" );
							} );
					}, options.pause );
				}
			} );

		},


		tinynav : function() {

			$( ".nav-menu" ).tinyNav( {
				active : 'current_page_item', // Set the "active" class for default menu
				label  : '', // String: Sets the <label> text for the <select> (if not set, no label will be added)
				header : '' // String: Specify text for "header" and show header instead of the active item
			} );

		},


		captions : function() {

			$( '#posts-content' ).hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					var $this = $( this ).closest( '.gallery-item' );
					$this.find( '.gallery-caption' ).stop().slideDown({
						start : function() {
							$this.addClass( 'expanded' );
						}
					});
				},
				function(){ //when mouse leaves, hide the sub list
					var $this = $( this ).closest( '.gallery-item' );
					$this.removeClass( 'expanded' ).find( '.gallery-caption' ).stop().slideUp();
				},
				'.gallery-item img'
			);

		},


		featured_slideshow : function () {

			$( '#featured-content' ).tinycarousel({
				axis          : 'y',
				interval      : true,
				buttons       : false,
				bullets       : true,
				animationTime : parseInt( _fastfoodL10n.featuredSlideshowSpeed ),
				intervalTime  : parseInt( _fastfoodL10n.featuredSlideshowPause )
			});

			var featured_content      = $( '#featured-content' );
			var featured_navigation   = $( '.featured-navigation', featured_content );
			var featured_content_data = featured_content.data( 'plugin_tinycarousel' );

			$( '.featured-content-inner', featured_content).hover(
				function() {
					featured_content_data.stop();
				}, function() {
					featured_content_data.start();
				}
			);

			$( featured_content ).hover(
				function() {
					featured_navigation.animate({
						right: 0,
					});
				}, function() {
					featured_navigation.animate({
						right: -20,
					});
				}
			);

		},


		sticky_menu : function() {

			var body           = $( 'body' ),
				_window        = $( window ),
				_menu          = $( '#menu-primary-container' ),
				mastheadHeight = _menu.height(),
				toolbarOffset,
				mastheadOffset;

			if ( _window.width() < 988 ) return;

			if ( _menu.length == 0 ) return;

			toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
			mastheadOffset = _menu.offset().top - toolbarOffset;

			_menu.css( 'top', toolbarOffset );

			$( '<div id="menu-primary-placeholder"></div>' ).css( 'height', mastheadHeight ).insertBefore( _menu );

			_window.on( 'scroll', function() {
				if ( ( window.scrollY > mastheadOffset ) ) {
					body.addClass( 'fixed-menu' );
				} else {
					body.removeClass( 'fixed-menu' );
				}
			} );

		}

	};

	$( document ).ready( function( $ ){ fastfoodAnimations.init( _fastfoodL10n.script_modules ); } );

} )( jQuery );