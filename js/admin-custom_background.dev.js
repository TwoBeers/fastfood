var farbtastic;

function pickColor(color) {
	farbtastic.setColor(color);
	jQuery('#background-color').val(color);
	jQuery('#custom-background-image').css('background-color', color);
	if ( color && color !== '#' )
		jQuery('#clearcolor').show();
	else
		jQuery('#clearcolor').hide();
}

jQuery(document).ready(function() {
	jQuery('#pickcolor').click(function() {
		jQuery('#colorPickerDiv').show();
		return false;
	});

	jQuery('#clearcolor a').click( function(e) {
		pickColor('');
		e.preventDefault();
	});

	jQuery('#background-color').keyup(function() {
		var _hex = jQuery('#background-color').val(), hex = _hex;
		if ( hex.charAt(0) != '#' )
			hex = '#' + hex;
		hex = hex.replace(/[^#a-fA-F0-9]+/, '');
		if ( hex != _hex )
			jQuery('#background-color').val(hex);
		if ( hex.length == 4 || hex.length == 7 )
			pickColor( hex );
	});

	jQuery('input[name="background-position-x"]').change(function() {
		jQuery('#custom-background-image').css('background-position', jQuery(this).val() + ' ' + jQuery('input[name="background-position-y"]:checked').val() );
	});

	jQuery('input[name="background-position-y"]').change(function() {
		jQuery('#custom-background-image').css('background-position', jQuery('input[name="background-position-x"]:checked').val() + ' ' + jQuery(this).val() );
	});

	jQuery('input[name="background-repeat"]').change(function() {
		jQuery('#custom-background-image').css('background-repeat', jQuery(this).val());
	});

	farbtastic = jQuery.farbtastic('#colorPickerDiv', function(color) {
		pickColor(color);
	});
	pickColor(jQuery('#background-color').val());

	jQuery(document).mousedown(function(){
		jQuery('#colorPickerDiv').each(function(){
			var display = jQuery(this).css('display');
			if ( display == 'block' )
				jQuery(this).fadeOut(2);
		});
	});
	
	
	jQuery('input[name="default-bg"]').change(function() {
		jQuery('.background-details').css('display', '' );
		var bg_url = jQuery(this).next('img').attr('src').replace("-thumbnail", "");
		var bg_pos_x = jQuery(this).parent().parent().find('.default-bg-info-posx').html();
		var bg_pos_y = jQuery(this).parent().parent().find('.default-bg-info-posy').html();
		var bg_repeat = jQuery(this).parent().parent().find('.default-bg-info-rep').html();
		var bg_attachment = jQuery(this).parent().parent().find('.default-bg-info-att').html();
		var bg_color = jQuery(this).parent().parent().find('.default-bg-info-col').html();
		jQuery('#custom-background-image').css('background-image', 'url(' + bg_url + ')' );
		jQuery('#custom-background-image').css('background-position', bg_pos_x + ' ' + bg_pos_y );
		jQuery('#custom-background-image').css('background-repeat', bg_repeat );
		jQuery('#custom-background-image').css('background-color', bg_color );
		jQuery('#background-color').val( bg_color );
		jQuery('input[name="background-position-y"]').val([ '' + bg_pos_y + '']);
		jQuery('input[name="background-position-x"]').val([ '' + bg_pos_x + '']);
		jQuery('input[name="background-repeat"]').val([ '' + bg_repeat + '']);
		jQuery('input[name="background-attachment"]').val([ '' + bg_attachment + '']);
	});
});

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	