var fastfoodOptions;

(function($) {

fastfoodOptions = {

	//initialize
	init : function() {
		
		fastfoodOptions.switchTab('quickbar');

		$('#theme-options .option_color_picker').each(function() {
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

		$('#to-defaults').click (function () {
			var answer = confirm(fastfood_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

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