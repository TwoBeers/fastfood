var farbtastic;

// display the color picker
function showMeColorPicker(domid) {
	placeholder = '#ff_colorpicker_' + domid;
	jQuery(placeholder).fadeIn();
	farbtastic = jQuery.farbtastic(placeholder, function(color) { pickColor(domid,color); });
	farbtastic.setColor(jQuery('#ff_input_' + domid).val());
}

//update inputs value
function pickColor(domid,color) {
	boxid = '#ff_box_' + domid;
	inputid = '#ff_input_' + domid;
	jQuery(boxid).css('background-color', color );
	jQuery(inputid).val(color);
}

jQuery(document).ready(function($){
	fastfoodSwitchTab.set('quickbar');
	
	jQuery(document).mousedown(function(){
		jQuery('.ff_cp').each( function() {
			var display = jQuery(this).css('display');
			if (display == 'block')
				jQuery(this).fadeOut(200);
		});
	});
	jQuery('.fade').each( function() {
		jQuery(this).delay(2000).fadeOut(600);
	});


});

fastfoodSwitchTab = {
	set : function (thisset) { //show only a set of rows
		if ( thisset != 'info' ) {
			jQuery('#fastfood-infos').css({ 'display' : 'none' });
			jQuery('#fastfood-options').css({ 'display' : '' });
			thisclass = '.ff-tabgroup-' + thisset;
			thissel = '#ff-selgroup-' + thisset;
			jQuery('.ff-tab-opt').css({ 'display' : 'none' });
			jQuery(thisclass).css({ 'display' : '' });
			jQuery('#ff-tabselector li').removeClass("sel-active");
			jQuery(thissel).addClass("sel-active");
		} else {
			jQuery('#fastfood-infos').css({ 'display' : '' });
			jQuery('#fastfood-options').css({ 'display' : 'none' });
			jQuery('#ff-tabselector li').removeClass("sel-active");
			jQuery('#ff-selgroup-info').addClass("sel-active");
		}
	}
}
