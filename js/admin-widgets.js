jQuery(document).ready(function($){

	if ( !$( 'body' ).hasClass( 'wp-customizer' ) ) {

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

	}

	var widgets_shell = $( 'div#widgets-right' );

	if ( !widgets_shell.length || !$( widgets_shell ).find( '.widget-control-actions' ).length ) {
		widgets_shell = $( 'form#customize-controls' );
	}


	$( 'a.colspan-options' ).each( function() {
		var $colspanOptionsButton = $( this ),
			$widget = $colspanOptionsButton.closest( 'div.widget' );

		$colspanOptionsButton.insertBefore( $widget.find( 'input.widget-control-save' ) );

		// Widgets with no configurable options don't show the Save button's container.
		$colspanOptionsButton
			.parent()
				.removeClass( 'widget-control-noform' )
				.find( '.spinner' )
					.remove()
					.css( 'float', 'left' )
					.prependTo( $colspanOptionsButton.parent() );

	} );

	widgets_shell.on( 'click.widgetcolspan', 'a.colspan-options', function ( e ) {
		e.preventDefault();

		var $colspanOptionsButton = $( this ),
			$widget = $colspanOptionsButton.closest( 'div.widget' );

		$( this ).toggleClass( 'active' );
		$('.widget-colspan',$widget).slideToggle( 400, function() {
			$widget.toggleClass( 'widget-colspan-visible' );
		});

	} );

});