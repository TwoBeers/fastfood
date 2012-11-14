var fastfoodAnimations;

(function($) {

fastfoodAnimations = {

	//initialize
	init : function( in_modules ) {

		var modules = in_modules.split(',');

		for (i in modules) {

			switch(modules[i]) {

				case 'main_menu':
					this.main_menu();
					break;

				case 'navigation_buttons':
					this.navigation_buttons();
					break;

				case 'quickbar_tools':
					this.quickbar_tools();
					break;

				case 'quickbar_panels':
					this.quickbar_panels();
					break;

				case 'entry_meta':
					this.entry_meta();
					break;

				case 'widgets_style':
					this.widgets_style();
					break;

				case 'post_expander':
					this.post_expander();
					break;

				case 'thickbox':
					this.thickbox();
					break;

				case 'quote_this':
					this.quote_this();
					break;

				default :
					//no default action
					break;

			}

		}

	},

	main_menu : function() {

		//main menu dropdown animation
		$('#mainmenu').children('li').each(function(){ //get every main list item
			var d = $(this).children('ul'); //for each main item, get the sub list
			var margintop_in = 50; //the starting distance between menu item and the popup submenu
			var margintop_out = 20; //the exiting distance between menu item and the popup submenu
			if(d.size() !== 0){ //if the sub list exists...
				$(this).children('a').append('<span class="hiraquo"> &#187;</span>'); //add a raquo to the main item
				
				d.css({'opacity' : 0 , 'margin-top' : margintop_in });
				
				$(this).mouseenter(function(){ //when mouse enters, slide down the sub list
					d.css({'display' : 'block' });
					d.animate(
						{ 'opacity' : 1 , 'margin-top' : 0 },
						200,
						'easeOutBack'
					);
				}).mouseleave(function(){ //when mouse leaves, hide the sub list
					d.stop();
					d.animate(
						{ 'opacity' : 0 , 'margin-top' : margintop_out },
						200,
						'swing',
						function(){ d.css({'display' : '' , 'margin-top' : margintop_in }); }
					);
				});
			}
		});

	},

	navigation_buttons : function() {

		//navbuttons tooltip animation
		$('#navbuttons').children('.minibutton').each( function(){ //get every minibutton
			var list = $(this).find('span.nb_tooltip');
			var marginright_in = 60; //the starting distance between menu item and the popup submenu
			list.css({ 'opacity' : 0 , 'right' : marginright_in });
			$(this).mouseenter( function(){ //when mouse enters, shift right the tooltip
				list.css({'display' : 'block' });
				list.animate(
					{ 'opacity' : 1 , 'right' : 23 },
					200,
					'easeOutBack'
				);
			}).mouseleave( function(){ //when mouse leaves, hide the tooltip
				list.stop();
				list.css({ 'opacity' : 0, 'display' : '', 'right' : marginright_in });
			});	
		});

		// fade in/out on scroll
		top_but = $('#navbuttons a[href="#"] span');
		bot_but = $('#navbuttons a[href="#footer"] span');
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

	quickbar_tools : function() {

		//quickbar tools animation
		$('#quickbar').children('.menutoolitem').each( function(){ //get every menutoolitem
			var list = $(this).find('.menutool');
			var marginleft_in = 120; //the starting distance between menu item and the popup submenu
			list.css({ 'opacity' : 0 , 'left' : marginleft_in });
			$(this).mouseenter( function(){ //when mouse enters, shift right the tooltip
				list.css({'display' : 'block' });
				list.animate(
					{ 'opacity' : 1 , 'left' : 50 },
					200,
					'easeOutBack'
				);
			}).mouseleave( function(){ //when mouse leaves, hide the tooltip
				list.stop();
				list.css({ 'opacity' : 0, 'display' : '', 'left' : marginleft_in });
			});	
		});

	},

	quickbar_panels : function() {

		//quickbar animation
		$('#quickbar').children('.menuitem').each( function(){ //get every quickbar item
			var list = $(this).children('.menuback'); // get the sub list for each quickbar item
			var trig = $(this).children('.itemimg');
				
			trig.removeClass('itemimg').addClass('itemimg_js');
			list.removeClass().addClass('menuback_js');

			list.css({ 'height' : 0 });
				
			$(this).mouseenter( function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
				if ( $('#user_menuback').hasClass('keepme') ) return;
				list.stop();
				trig.stop();
				list.animate(
					{ 'height': 250 },
					500,
					'easeOutBack'
				);
				trig.animate(
					{ 'width': 55 },
					200
				);
				
			}).mouseleave( function(){ //when mouse leaves, hide the submenu
				if ( $('#user_menuback').hasClass('keepme') ) return;
				list.stop();
				//trig.stop();
				list.css({ 'height' : 0 , 'display' : '' });
				trig.animate(
					{ 'width': 50 },
					300
				);
			});	
		});

		//add a "close" link after the submit button in minilogin form
		$('.login-submit').append( $('#closeminilogin') );
		$('#closeminilogin').css({ 'display' : 'inline' });
		$('#closeminilogin').click( function() {
			$('.itemimg_js').fadeIn();
			$('#user_menuback .menuback_js').css({ 'display' : '' , 'height' : 0  });
			$('#ff_minilogin_wrap').css({ 'display' : '' });
			$('#user_menuback').removeClass('keepme');
			return false;
		});

		//preserve the menu div from disappear when loginform name input is clicked
		$('#ff-user_login').mousedown( function() {
			$('#ff_minilogin_wrap').css({ 'display' : 'block' });
			$('#user_menuback').addClass('keepme');
			$('.itemimg_js').fadeOut();
		});

	},

	entry_meta : function() {

		//meta animation
		$('.top_meta').removeClass('top_meta').addClass('top_meta_js');
		$('.top_meta_js').children('.metafield').each( function(){  //get every metafield item
			var list = $(this).children('.metafield_content'); // get the sub list for each metafield item
			var parent = $(this).parent();
			list.css({ 'opacity' : 0, 'display' : 'block' });
			var mysize = list.height(); //retrieve the height of the sub list
			list.removeAttr("style");
			list.css({ 'opacity' : '', 'height' : 0 , 'padding-top' : 0, 'display' : '' });
			$(this).mouseenter( function(){ //when mouse enters, slide down the sub list
				list.animate(
					{'height': mysize , 'padding-top': 25 },
					200,
					'easeOutBack'
				);
				parent.addClass('meta_shadowed');
				parent.css({ 'border-color' : '#fff' });
			}).mouseleave( function(){ //when mouse leaves, hide the sub list
				list.stop();
				list.css({ 'display' : '', 'height' : 0 , 'padding-top' : 0 });
				parent.removeClass('meta_shadowed');
				parent.css({ 'border-color' : '' });
			});
		});

	},

	widgets_style : function() {

		// add style to some widgets areas
		$('#post-widgets-area .widget:nth-child(odd)').css('clear', 'left');
		$('#header-widget-area .widget:nth-child(3n+1)').css('clear', 'right');
		$('#error404-widgets-area .widget:nth-child(odd)').css('clear', 'left');

	},

	thickbox : function() {

		$('.storycontent a img').parent('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').addClass('thickbox');
		$('.storycontent .gallery').each(function() {
			$('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]',$(this)).attr('rel', $(this).attr('id'));
		});

	},

	quote_this : function () {
		htmltext = '<a id="tb-quotethis" href="#" onclick="fastfoodAnimations.add_quote(); return false" title="' + fastfood_l10n.quote_link_info + '" >&nbsp;</a>'
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

	//show only a set of rows
	post_expander : function () {
        $('a.more-link').click(function() {

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
	}

};

$(document).ready(function($){ fastfoodAnimations.init(fastfood_l10n.script_modules); });

})(jQuery);