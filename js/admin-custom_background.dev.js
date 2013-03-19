(function($) {
	$(document).ready(function() {
		$('input[name="default-bg"]').change(function() {
			var $this = $(this);
			var bg_url = $this.next('img').attr('src').replace("-thumbnail", "");
			var bg_pos_x = $this.parent().parent().find('.default-bg-info-posx').html();
			var bg_pos_y = $this.parent().parent().find('.default-bg-info-posy').html();
			var bg_repeat = $this.parent().parent().find('.default-bg-info-rep').html();
			var bg_attachment = $this.parent().parent().find('.default-bg-info-att').html();
			var bg_color = $this.parent().parent().find('.default-bg-info-col').html();
			$('#custom-background-image').css('background-image', 'url(' + bg_url + ')' );
			$('#custom-background-image').css('background-position', bg_pos_x + ' ' + bg_pos_y );
			$('#custom-background-image').css('background-repeat', bg_repeat );
			$('#background-color').wpColorPicker('color', bg_color)
			$('input[name="background-position-y"]').val([ '' + bg_pos_y + '']);
			$('input[name="background-position-x"]').val([ '' + bg_pos_x + '']);
			$('input[name="background-repeat"]').val([ '' + bg_repeat + '']);
			$('input[name="background-attachment"]').val([ '' + bg_attachment + '']);
		});
		$('input[name="background-position-x"]').change(function() {
			$('#custom-background-image').css('background-position', $(this).val() + ' ' + $('input[name="background-position-y"]:checked').val() );
		});

		$('input[name="background-position-y"]').change(function() {
			$('#custom-background-image').css('background-position', $('input[name="background-position-x"]:checked').val() + ' ' + $(this).val() );
		});

		$('input[name="background-repeat"]').change(function() {
			$('#custom-background-image').css('background-repeat', $(this).val());
		});
	});
})(jQuery);