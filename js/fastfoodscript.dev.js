jQuery(document).ready(function($){

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
		list.css({ 'opacity' : 1, 'display' : '', 'height' : 0 , 'padding-top' : 0 });
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

});
