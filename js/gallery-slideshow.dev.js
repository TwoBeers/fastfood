jQuery(document).ready( function($) {
	$(".gallery").each(function(){
		$(this).after('<div class="ffg-slideshow" id="ffg-slideshow-' + $(this).attr('id') + '"><div class="ffg-info"><a class="ffg-preview-link" href="" onclick="ff_SwitchMe(\'' + $(this).attr('id') + '\'); return false;">' + ff_gallery_preview_text + '</a></div><div class="ffg-img"></div></div>');	
	});
});

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
