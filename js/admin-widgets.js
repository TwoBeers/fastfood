jQuery(document).ready(function($){
	$('#widget-list').append('<p class="clear sector-header">Fastfood widgets</p>');
	ff_widgets = $('#widget-list').find('.widget[id*=_ff-]');
	$('#widget-list').append(ff_widgets);

	bbp_widgets = $('#widget-list').find('.widget[id*=_bbp_]');
	if (bbp_widgets.length > 0) {
		$('#widget-list').append('<p class="clear sector-header">bbPress widgets</p>');
		$('#widget-list').append(bbp_widgets);
	}

	bp_widgets = $('#widget-list').find('.widget[id*=_bp_]');
	if (bp_widgets.length > 0) {
		$('#widget-list').append('<p class="clear sector-header">BuddyPress widgets</p>');
		$('#widget-list').append(bp_widgets);
	}

	jp_widgets = $('#widget-list').find('.widget[id*=_image-],.widget[id*=_facebook-likebox-],.widget[id*=_grofile-],.widget[id*=_rss_links-],.widget[id*=_jetpack_readmill_widget-],.widget[id*=_twitter_timeline-]');
	if (jp_widgets.length > 0) {
		$('#widget-list').append('<p class="clear sector-header">Jetpack widgets</p>');
		$('#widget-list').append(jp_widgets);
	}
});