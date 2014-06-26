var farbtastic;
var fastfoodOptions;

(function($) {

fastfoodOptions = {

	//initialize
	init : function() {

		var frame;

		fastfoodOptions.switchTab('style');

		$('#to-defaults').click (function () {
			var answer = confirm(fastfood_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

		$('#theme-options .fastfood_cp').each(function() {
			$this = $(this);
			$this.wpColorPicker({
				change: function( event, ui ) {
					$this.val( $this.wpColorPicker('color') );
				},
				clear: function() {
					$this.val( '' );
				},
				palettes: ['#21759b','#404040','#87ceeb','#000','#fff','#aaa','#ff7b0a','#f7009c']
			});
		});

	},

	//show only a set of rows
	switchTab : function (thisset) {
		if ( thisset != 'info' ) {
			$('#theme-infos').css({ 'display' : 'none' });
			$('#theme-options').css({ 'display' : '' });
			thisclass = '.tabgroup-' + thisset;
			thissel = '#selgroup-' + thisset;
			$('.tabgroup').css({ 'display' : 'none' });
			$(thisclass).css({ 'display' : '' });
			$('#tabselector a').removeClass("nav-tab-active");
			$(thissel).addClass("nav-tab-active");
		} else {
			$('#theme-infos').css({ 'display' : '' });
			$('#theme-options').css({ 'display' : 'none' });
			$('#tabselector a').removeClass("nav-tab-active");
			$('#selgroup-info').addClass("nav-tab-active");
		}
	}

};

$(document).ready(function($){ fastfoodOptions.init(); });

})(jQuery);