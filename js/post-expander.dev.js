jQuery(document).ready( function($) {
	$(".more-link").click( function() {
		var link = $(this);
		$.ajax({
			type: 'POST',
			url: link.attr("href"),
			beforeSend: function(XMLHttpRequest) { link.html(ff_post_expander_text).addClass('ajaxed'); },
			data: 'ff_post_expander=1',
			success: function(data) { link.parents(".storycontent").hide().html($(data)).fadeIn(600); }
		});	
		return false;
	});
});