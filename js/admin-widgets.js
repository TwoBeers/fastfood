jQuery(document).ready(function($){
	$('#widget-list').append('<p class="clear sector-header">Fastfood widgets</p>');
	ff_widgets = $('#widget-list').find('.widget[id*=_ff-]');
	$('#widget-list').append(ff_widgets);

	$('#widget-list').append('<p class="clear sector-header">bbPress widgets</p>');
	bbp_widgets = $('#widget-list').find('.widget[id*=_bbp_]');
	$('#widget-list').append(bbp_widgets);

	$('#widget-list').append('<p class="clear sector-header">BuddyPress widgets</p>');
	bp_widgets = $('#widget-list').find('.widget[id*=_bp_]');
	$('#widget-list').append(bp_widgets);
});