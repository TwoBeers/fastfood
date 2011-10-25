(function($) {
	$.ff_animation = function() {

		//main menu dropdown animation
		$('#mainmenu').children('li').each(function(){ //get every main list item
			var d = $(this).children('ul'); //for each main item, get the sub list
			var margintop_in = 50; //the starting distance between menu item and the popup submenu
			var margintop_out = 20; //the exiting distance between menu item and the popup submenu
			if(d.size() !== 0){ //if the sub list exists...
				$(this).children('a').append('<span class="hiraquo"> »</span>'); //add a raquo to the main item
				
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

		//quickbar animation
		$('#quickbar').children('.menuitem').each( function(){ //get every quickbar item
			var list = $(this).children('.menuback'); // get the sub list for each quickbar item
			var trig = $(this).children('.itemimg');
				
			trig.removeClass('itemimg').addClass('itemimg_js');
			list.removeClass().addClass('menuback_js');

			list.css({ 'height' : 0 });
				
			$(this).mouseenter( function(){ //when mouse enters, slide left the sub list, restore its shadow and animate the button
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
				list.stop();
				trig.stop();
				list.css({ 'height' : 0 , 'display' : '' });
				trig.animate(
					{ 'width': 50 },
					300
				);
			});	
		});
		

		//meta animation
		$('.top_meta').children('.metafield').each( function(){  //get every metafield item
			var list = $(this).children('.metafield_content'); // get the sub list for each metafield item
			var parent = $(this).parent();
			parent.removeClass('top_meta');
			parent.addClass('top_meta_js');
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
		
		//add a "close" link after the submit button in minilogin form
		$('.login-submit').append( $('#closeminilogin') );
		$('#closeminilogin').css({ 'display' : 'inline' });
		$('#closeminilogin').click( function() {
			$('#ff-user_login').parents('.menuback_js').css({ 'display' : '' , 'height' : 0  });
			$('#user_menuback').mouseleave( function(){ //when mouse leaves, hide the submenu
				$('#ff-user_login').parents('.menuback_js').css({ 'display' : '' , 'height' : 0  });
				$('#ff-user_login').parents('.cat_preview').css({ 'display' : '' });
			});
			return false;
		});
		
		//preserve the menu div from disappear when loginform name input is clicked
		$('#ff-user_login').mousedown( function() {
			$('#user_menuback').unbind("mouseleave");
			$('#ff-user_login').parents('.cat_preview').css({ 'display' : 'block' });
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
		
	};

    $.fn.ff_postexpander = function() {

        return this.each(function() {
            
            $(this).click(function() {

				var link = $(this);

				$.ajax({
					type: 'POST',
					url: link.attr("href"),
					beforeSend: function(XMLHttpRequest) { link.html(ff_post_expander_text).addClass('ajaxed'); },
					data: 'ff_post_expander=1',
					success: function(data) { link.parents(".storycontent").hide().html($(data)).fadeIn(600); }
				});	

				return false;

			});

        });

    };
	
    $.fn.ff_gallery_slider = function() {

        return this.each(function() {
            
			$(this).after('<div class="ffg-slideshow" id="ffg-slideshow-' + $(this).attr('id') + '"><div class="ffg-info"><a class="ffg-preview-link" href="" onclick="ff_SwitchMe(\'' + $(this).attr('id') + '\'); return false;">' + ff_gallery_preview_text + '</a></div><div class="ffg-img"></div></div>');	

        });

    };

})(jQuery);

function ff_SwitchMe(domid) {
	var the_items = '#' + domid + ' .gallery-item a';
	var the_slider = jQuery('#ffg-slideshow-' + domid );
	var the_slider_info = the_slider.children('.ffg-info');
	var the_slider_img = the_slider.children('.ffg-img');
	jQuery('#' + domid).addClass('ajaxed');
	the_slider.addClass('ffg-slider');
	the_slider_info.html('<small>' + ff_gallery_click_text + '</small>');
	jQuery(the_items).click(function(){
		jQuery(the_items).children('img').removeClass('thumbsel');
		var link = jQuery(this);
		link.children('img').addClass('thumbsel');
		var img_ext = '.' + link.children('img').attr("src").match( /([^\.]+)$/g )
		var img_link = link.children('img').attr("src").replace( /\-([^\-]+)$/g , img_ext );
		the_slider_info.html('<span class="loading"></span>').slideDown();
		the_slider_img.stop().fadeOut(600, function(){the_slider_img.html('<a href="' + link.attr("href") + '"><img src="' + img_link + '" alt="image preview" /></a>');}).delay(1000).fadeIn(600, function(){ the_slider_info.slideUp();});
		return false;
	});
	var the_br = '#' + domid + ' br';
	var the_item = '#' + domid + ' .gallery-item';
	var the_caption = '#' + domid + ' .gallery-caption';
	
	jQuery(the_br).css({ 'display' : 'none' });
	jQuery(the_caption).css({ 'display' : 'none' });
	jQuery(the_br + ':last').css({ 'display' : '' });
	var d = jQuery(the_item);
	d.animate(
		{ 'width' : '10%', 'margin-right' : '10px' },
		1000
	);
}
