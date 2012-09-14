var farbtastic;
var fastfoodOptions;

(function($) {

fastfoodOptions = {

	//initialize
	init : function() {
		
		fastfoodOptions.switchTab('quickbar');
		
		$('.col-tools .option_text').keyup(function() {
			var _hex = $(this).val();
			var hex = _hex;
			if ( hex.substr(0,1) != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			hex = hex.substring(0,7);
			if ( hex != _hex )
				$(this).val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				fastfoodOptions.updateColor( $(this).attr("id").replace('option_color_input_', '') , hex );
		});

		$(document).mousedown(function(){
			$('.option_cp').each( function() {
				var display = $(this).css('display');
				if (display == 'block')
					$(this).fadeOut(200);
			});
		});
		$('#to-defaults').click (function () {
			var answer = confirm(fastfood_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

	},

	//update inputs value
	updateColor : function (domid,color,txtcolor) {
		boxid = '#option_color_box_' + domid;
		inputid = '#option_color_input_' + domid;
		$(boxid).css('background-color', color );
		$(inputid).val(color);
	},

	// display the color picker
	showColorPicker : function (domid) {
		placeholder = '#option_colorpicker_' + domid;
		$(placeholder).fadeIn();
		farbtastic = $.farbtastic(placeholder, function(color) { fastfoodOptions.updateColor(domid,color); });
		farbtastic.setColor($('#option_color_input_' + domid).val());
	},

	//show only a set of rows
	switchTab : function (thisset) {
		if ( thisset != 'info' ) {
			$('#theme-infos').css({ 'display' : 'none' });
			$('#theme-options').css({ 'display' : '' });
			thisclass = '.tabgroup-' + thisset;
			thissel = '#selgroup-' + thisset;
			$('.tab-opt').css({ 'display' : 'none' });
			$(thisclass).css({ 'display' : '' });
			$('#tabselector li').removeClass("sel-active");
			$(thissel).addClass("sel-active");
		} else {
			$('#theme-infos').css({ 'display' : '' });
			$('#theme-options').css({ 'display' : 'none' });
			$('#tabselector li').removeClass("sel-active");
			$('#selgroup-info').addClass("sel-active");
		}
	}

};

$(document).ready(function($){ fastfoodOptions.init(); });

})(jQuery);