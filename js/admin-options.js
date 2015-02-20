var fastfoodOptions;

(function($) {

fastfoodOptions = {

	//initialize
	init : function() {

		var frame;

		$( "#theme-options h3" ).each(function() {
			$(this).prependTo( $(this).next() );
		});
		$( "#theme-options .form-table" ).each(function() {
			$(this).appendTo( $(this).prev() );
		});

		fastfoodOptions.switchTab('style');

		$('#to-defaults').click (function () {
			var answer = confirm(fastfood_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

		$('#theme-options .fastfood_cp').each(function() {
			$this = $(this);
			$('<span class="visibility-preview"><span>lorem ipsum</span></span>').insertAfter( $this ).css({'color' : $this.val()});
			$preview = $this.next('.visibility-preview');
			$this.wpColorPicker({
				change: function( event, ui ) {
					$(this).closest('.wp-picker-container').next().css({'color': ui.color.toString()});
				},
				palettes: ['#d54e21','#b78f21','#a2aa2a','#2ea2cc','#bf6391','#000000','#aaaaaa','#ffffff']
			});
		});

	},

	//show only a set of rows
	switchTab : function (thisset) {
		if ( thisset != 'info' ) {
			$('#theme-infos').css({ 'display' : 'none' });
			$('#theme-options').css({ 'display' : '' });
			thisclass = '#theme-options .group-' + thisset;
			thissel = '#selgroup-' + thisset;
			$( "#theme-options [class^='group']" ).css({ 'display' : 'none' });
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