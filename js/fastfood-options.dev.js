var farbtastic;

// display the color picker
function fastfoodShowMeColorPicker(domid) {
	placeholder = '#ff_colorpicker_' + domid;
	jQuery(placeholder).fadeIn();
	farbtastic = jQuery.farbtastic(placeholder, function(color) { fastfoodPickColor(domid,color); });
	farbtastic.setColor(jQuery('#ff_input_' + domid).val());
}

//update inputs value
function fastfoodPickColor(domid,color) {
	boxid = '#ff_box_' + domid;
	inputid = '#ff_input_' + domid;
	jQuery(boxid).css('background-color', color );
	jQuery(inputid).val(color);
}

jQuery(document).ready(function(){
	fastfoodSwitchTab.set('quickbar');
	
	jQuery('.ff_input').keyup(function() {
		var _hex = jQuery(this).val();
		var hex = _hex;
		if ( hex.substr(0,1) != '#' )
			hex = '#' + hex;
		hex = hex.replace(/[^#a-fA-F0-9]+/, '');
		hex = hex.substring(0,7);
		if ( hex != _hex )
			jQuery(this).val(hex);
		if ( hex.length == 4 || hex.length == 7 )
			fastfoodPickColor( jQuery(this).attr("id").replace('ff_input_', '') , hex );
	});

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
		thisclass = '.ff-tabgroup-' + thisset;
		thissel = '#ff-selgroup-' + thisset;
		jQuery('.ff-tab-opt').css({ 'display' : 'none' });
		jQuery(thisclass).css({ 'display' : '' });
		jQuery('#ff-tabselector li').removeClass("sel-active");
		jQuery(thissel).addClass("sel-active");
	}
}
