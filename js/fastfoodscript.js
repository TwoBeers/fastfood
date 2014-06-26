var fastfoodAnimations;

(function($) {

fastfoodAnimations = {

	//initialize
	init : function( modules ) {

		for (i in modules) {

			switch(modules[i]) {

				case 'main_menu':
					this.main_menu();
					break;

				case 'navigation_buttons':
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
					$('body').on('post-load', function(event){ //Jetpack Infinite Scroll trigger
						fastfoodAnimations.entry_meta();
					});
					break;

				case 'post_expander':
					this.post_expander();
					$('body').on('post-load', function(event){ //Jetpack Infinite Scroll trigger
						fastfoodAnimations.post_expander();
					});
					break;

				case 'thickbox':
					this.thickbox();
					$('body').on('post-load', function(event){ //Jetpack Infinite Scroll trigger
						fastfoodAnimations.thickbox();
					});
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
					this.sticky_menu();
					break;

				default :
					//no default action
					break;

			}

		}

	},

	main_menu : function() {

		//main menu dropdown animation
		$('#mainmenu').children('.menu-item-parent').each(function(){ //get every main list item
			var $this = $(this);
			var d = $this.children('ul'); //for each main item, get the sub list
			var margintop_in = 50; //the starting distance between menu item and the popup submenu
			var margintop_out = 20; //the exiting distance between menu item and the popup submenu
			if(d.size() !== 0){ //if the sub list exists...

				d.css({'opacity' : 0 , 'margin-top' : margintop_in });

				$this.hoverIntent(
					function(){ //when mouse enters, slide down the sub list
						d.css({'display' : 'block' }).animate(
							{ 'opacity' : 1 , 'margin-top' : 0 },
							200,
							'easeOutBack'
						);
					},
					function(){ //when mouse leaves, hide the sub list
						d.stop().animate(
							{ 'opacity' : 0 , 'margin-top' : margintop_out },
							200,
							'swing',
							function(){ d.css({'display' : '' , 'margin-top' : margintop_in }); }
						);
					}
				);
			}
		});

	},

	navigation_buttons : function() {

		//navbuttons tooltip animation
		$('#navbuttons').children('.minibutton').each( function(){ //get every minibutton
			var $this = $(this);
			var list = $this.find('span.nb_tooltip');
			var marginright_in = 60; //the starting distance between menu item and the popup submenu
			list.css({ 'opacity' : 0 , 'right' : marginright_in });
			$this.hoverIntent(
				function(){ //when mouse enters, shift right the tooltip
					list.css({'display' : 'block' }).animate(
						{ 'opacity' : 0.9 , 'right' : 40 },
						200,
						'easeOutBack'
					);
				},
				function(){ //when mouse leaves, hide the tooltip
					list.stop().css({ 'opacity' : 0, 'display' : '', 'right' : marginright_in });
				}
			);
		});

	},

	smooth_scroll : function() {

		// fade in/out on scroll
		top_but = $('#navbuttons a[href="#"]');
		bot_but = $('#navbuttons a[href="#footer"]');
		top_but.hide();
		$(function () {
			$(window).scroll(function () {
				// check for top action
				if ($(this).scrollTop() > 100) {
					top_but.fadeIn();
				} else {
					top_but.fadeOut();
				}
				// check for bottom action
				if ( $('body').height()-$(window).scrollTop()-$(window).height() < 100) {
					bot_but.fadeOut();
				} else {
					bot_but.fadeIn();
				}

			});
		});

		// smooth scroll top
		top_but.click(function() {
			$("html, body").animate({
				scrollTop: 0
			}, {
				duration: 1000
			});
			return false;
		});

		// smooth scroll bottom
		bot_but.click(function() {
			$("html, body").animate({
				scrollTop: $('#footer').offset().top - 80
			}, {
				duration: 1000
			});
			return false;
		});

	},

	quickbar_panels : function() {

		//quickbar animation
		$('#quickbar').children('.menuitem').each( function(){ //get every quickbar item
			var $this = $(this);
			var list = $this.children('.menuback'); // get the sub list for each quickbar item
			var trig = $this.children('.itemimg');

			trig.removeClass('itemimg').addClass('itemimg_js');
			list.removeClass().addClass('menuback_js').css({ 'height' : 0 }).hide();

			$this.hoverIntent(
				function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
					if ( $('#panel_user').hasClass('keepme') ) return;
					list.stop().show().animate(
						{ 'height': 250 },
						500,
						'easeOutBack'
					);
				},
				function(){ //when mouse leaves, hide the submenu
					if ( $('#panel_user').hasClass('keepme') ) return;
					list.stop().css({ 'height' : 0 , 'display' : '' }).hide();
				}
			);
		});

		//add a "close" link after the submit button in minilogin form
		$('.login-submit').append( $('#closeminilogin') );
		$('#closeminilogin').css({ 'display' : 'inline' });
		$('#closeminilogin').click( function() {
			$('.itemimg_js').fadeIn();
			$('#panel_user .menuback_js').css({ 'display' : '' , 'height' : 0  });
			$('#ff_minilogin_wrap').css({ 'display' : '' });
			$('#panel_user').removeClass('keepme');
			return false;
		});

		//preserve the menu div from disappear when loginform name input is clicked
		$('#ff-user_login').mousedown( function() {
			$('#ff_minilogin_wrap').css({ 'display' : 'block' });
			$('#panel_user').addClass('keepme');
			$('.itemimg_js').fadeOut();
		});

	},

	entry_meta : function() {

		//meta animation
		$('#posts_content').find('.top_meta').removeClass('top_meta').addClass('top_meta_js').children('.metafield').each( function(){  //get every metafield item
			var $this = $(this);
			var list = $this.children('.metafield_content'); // get the sub list for each metafield item
			if ( list.length == 0 ) return;
			var parent = $this.parent();
			list.css({ 'display' : 'block' }).hide();
			$this.hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					list.slideDown(200,'easeOutBack');
					parent.addClass('meta_shadowed').css({ 'border-color' : '#fff' });
				},
				function(){ //when mouse leaves, hide the sub list
					list.hide();
					parent.removeClass('meta_shadowed').css({ 'border-color' : '' });
				}
			);
		});

	},

	thickbox : function() {

		$('#posts_content').find('.storycontent a img').parent('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').addClass('thickbox');
		$('#posts_content').find('.storycontent .gallery').each(function() {
			var $this = $(this);
			$('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]',$this).attr('rel', $this.attr('id'));
		});

	},

	quote_this : function () {
		htmltext = '<a id="tb-quotethis" href="#" onclick="fastfoodAnimations.add_quote(); return false" title="' + fastfood_l10n.quote_link_info + '" ><i class="el-icon-quotes"></i></a>'
		$(htmltext).insertBefore('#comment');
	},

	add_quote : function() {

		var posttext = '';
		if (window.getSelection){
			posttext = window.getSelection();
		}
		else if (document.getSelection){
			posttext = document.getSelection();
		}
		else if (document.selection){
			posttext = document.selection.createRange().text;
		}
		else {
			return true;
		}
		posttext = posttext.toString().replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
		if ( posttext.length !== 0 ) {
			document.getElementById("comment").value = document.getElementById("comment").value + '<blockquote>' + posttext + '</blockquote>';
		} else {
			alert(fastfood_l10n.quote_link_alert);
		}

	},

	post_expander : function () {
		$('#posts_content').find('a.more-link').unbind().click(function() {

			var link = $(this);

			$.ajax({
				type: 'POST',
				url: link.attr("href"),
				beforeSend: function(XMLHttpRequest) { link.html(fastfood_l10n.post_expander_wait).addClass('ajaxed'); },
				data: 'ff_post_expander=1',
				success: function(data) { link.parents(".storycontent").hide().html($(data)).fadeIn(600); }
			});	

			return false;

		});

	},

	get_comments : function () {
		var navigation = $('.navigate_comments');
		navigation.find('a').click(function() {

			var link = $(this);

			$.ajax({
				type: 'POST',
				url: link.attr("href"),
				beforeSend: function(XMLHttpRequest) { link.addClass('ajaxed'); },
				data: 'ff_get_comments_page=1',
				success: function(data) {
					$('#commentlist-wrap').html($(data));
					fastfoodAnimations.get_comments();
				}
			});	

			return false;

		});

	},

	headerSlider : function (options) {

		// set default options
		var defaults = {
			speed : 2000, //duration of the animation
			pause : 3000 //pause between animations
		},

		// Take the options that the user selects, and merge them with defaults.
		options = $.extend(defaults, options);

		return $('#slide-head').each(function() {

			// cache "this."
			var $this = $(this);

			$('img',$this).css("display","");

			if ($this.children().size() > 1) {
				// call the slide function.
				timId = slide();
			}

			function slide() {
				timId = setInterval(function() {
					$this
						.children(':last')
						.fadeOut(options.speed, function() { 
							$(this)
								.prependTo($this)
								.show();
						});
				}, (options.speed + options.pause));
				return timId;
			}
		});
	},

	tinynav : function() {
		//if ( $(window).width() > 800 ) return false;
		$(".nav-menu").tinyNav({
			active: 'current_page_item', // Set the "active" class for default menu
			label: '', // String: Sets the <label> text for the <select> (if not set, no label will be added)
			header: '' // String: Specify text for "header" and show header instead of the active item
		});
	},

	captions : function() {
		$('#content').find('.gallery-item').unbind().hoverIntent(
			function(){ //when mouse enters, slide down the sub list
				$('.gallery-caption',this).stop().slideDown();
			},
			function(){ //when mouse leaves, hide the sub list
				$('.gallery-caption',this).stop().slideUp();
			}
		);
		$('#content').find('.wp-caption').each( function() {
			var $this = $(this);
			var list = $('.wp-caption-text',this);
			list.css('display', 'none');
			$this.unbind().hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					list.stop().slideDown(200);
				},
				function(){ //when mouse leaves, hide the sub list
					list.stop().css('display', 'block').slideUp(200);
				}
			);

		});

	},

	sticky_menu : function() { //return;
		var body    = $( 'body' ),
			_window = $( window ),
			_menu   = $( '#pages' ),
			mastheadHeight = _menu.height(),
			toolbarOffset,
			mastheadOffset;

		toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
		mastheadOffset = _menu.offset().top - toolbarOffset;
		_menu.css( 'top', toolbarOffset );

		_window.on( 'scroll', function() {
			if ( ( window.scrollY > mastheadOffset ) ) {
				body.addClass( 'fixed-menu' );
				$('#header').css('marginBottom',mastheadHeight + 40);
			} else {
				body.removeClass( 'fixed-menu' );
				$('#header').css('marginBottom',0);
			}
		} );

	}

};

$(document).ready(function($){ fastfoodAnimations.init(fastfood_l10n.script_modules); });

})(jQuery);