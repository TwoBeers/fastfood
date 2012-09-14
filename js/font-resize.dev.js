var fastfoodFontResize;

(function($) {

fastfoodFontResize = {

	//initialize
	init : function () {
		var resizeStep = 1;
		var baseElement = $('body');
		var startFontSize = parseFloat(baseElement.css("font-size"));
		var savedSize = fastfoodFontResize.cookie('fontSize');
		if(savedSize != null && savedSize != "" && savedSize > 4) {
			$(baseElement).css("font-size", savedSize + "px");
		}
		$('.fontResizer_plus').click(function() {
			var newFontSize = parseFloat(baseElement.css("font-size"));
			newFontSize=newFontSize+parseFloat(resizeStep);
			baseElement.css("font-size",newFontSize+"px");
			fastfoodFontResize.cookie('fontSize', newFontSize);
		});
		$('.fontResizer_reset').click(function() {
			baseElement.css("font-size",startFontSize);
			fastfoodFontResize.cookie('fontSize', startFontSize);
		});
		$('.fontResizer_minus').click(function() {
			var newFontSize = parseFloat(baseElement.css("font-size"))
			newFontSize=newFontSize-resizeStep;
			baseElement.css("font-size",newFontSize+"px");
			fastfoodFontResize.cookie('fontSize', newFontSize);
		});
	},

	cookie : function(name, value) {
		if (typeof value != 'undefined') { // name and value given, set cookie
			var exdate = new Date();
			var expiredays = 31; //coockie expires in # days
			exdate.setDate(exdate.getDate() + expiredays);
			document.cookie = name + "=" + encodeURIComponent(value) + ";expires="+exdate.toGMTString();
		} else { // only name given, get cookie
			var cookieValue = null;
			if (document.cookie && document.cookie != '') {
				var cookies = document.cookie.split(';');
				for (var i = 0; i < cookies.length; i++) {
					var cookie = jQuery.trim(cookies[i]);
					if (cookie.substring(0, name.length + 1) == (name + '=')) {
						cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
						break;
					}
				}
			}
			return cookieValue;
		}
	}

};

$(document).ready(function($){ fastfoodFontResize.init(); });

})(jQuery);
