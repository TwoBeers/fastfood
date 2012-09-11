var farbtastic;
var fastfoodOptions;

(function($) {

fastfoodOptions = {

	//initialize
	init : function() {
		
		fastfoodOptions.switchTab('quickbar');
		
		$('.ff_input').keyup(function() {
			var _hex = $(this).val();
			var hex = _hex;
			if ( hex.substr(0,1) != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			hex = hex.substring(0,7);
			if ( hex != _hex )
				$(this).val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				fastfoodOptions.updateColor( $(this).attr("id").replace('ff_input_', '') , hex );
		});

		$(document).mousedown(function(){
			$('.ff_cp').each( function() {
				var display = $(this).css('display');
				if (display == 'block')
					$(this).fadeOut(200);
			});
		});
		$('#to-defaults').click (function () {
			var answer = confirm(tb_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

	},

	//update inputs value
	updateColor : function (domid,color,txtcolor) {
		boxid = '#ff_box_' + domid;
		inputid = '#ff_input_' + domid;
		$(boxid).css('background-color', color );
		$(inputid).val(color);
	},

	// display the color picker
	showColorPicker : function (domid) {
		placeholder = '#ff_colorpicker_' + domid;
		$(placeholder).fadeIn();
		farbtastic = $.farbtastic(placeholder, function(color) { fastfoodOptions.updateColor(domid,color); });
		farbtastic.setColor($('#ff_input_' + domid).val());
	},

	//show only a set of rows
	switchTab : function (thisset) {
		thisclass = '.ff-tabgroup-' + thisset;
		thissel = '#ff-selgroup-' + thisset;
		$('.ff-tab-opt').css({ 'display' : 'none' });
		$(thisclass).css({ 'display' : '' });
		$('#ff-tabselector li').removeClass("sel-active");
		$(thissel).addClass("sel-active");
	}

};

$(document).ready(function($){ fastfoodOptions.init(); });

})(jQuery);