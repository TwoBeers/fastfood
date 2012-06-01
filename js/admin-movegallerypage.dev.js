var fastfoodMovePageLink;

(function($) {

fastfoodMovePageLink = {

	//initialize
	init : function() {
		
		$('#menu-appearance a[href$="tb_fastfood_gallery_editor"]').parent('li').appendTo($('#menu-media ul'));

	}

};

$(document).ready(function($){ fastfoodMovePageLink.init(); });

})(jQuery);