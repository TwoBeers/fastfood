/* global _fastfoodCustomizeControls */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api, $ ) {
	var timers = {};
	var cssTemplate = wp.template( 'fastfood-dynamic-css' );

	api.controlConstructor.radio = api.Control.extend( {
		ready: function() {
			if ( 'background_scheme' === this.id ) {
				this.setting.bind( 'change', function( value ) {

					// Update Background Color.
					api( 'background_color' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_color'] );
					api.control( 'background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_color'] )
						.wpColorPicker( 'defaultColor', _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_color'] )
						.wpColorPicker( 'color', _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_color'] );

					// Update Background Image.
					var api_control_background_image = api.control( 'background_image' );
					var api_control_background_image_container = api_control_background_image.container;
					api( 'background_image' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_image'] );
					if ( api_control_background_image_container.is( '.customize-control-background' ) ) {
						api_control_background_image_container.find( '.current' )
							.html('<div class="container"><div class="thumbnail-image"><img src="' + _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_image'] + '"/></div></div>');
						api_control_background_image_container.find( '.actions .remove-button,.actions .default-button' ).remove();
						api_control_background_image_container.find( '.actions' )
							.prepend('<button class="button remove-button" type="button">' + _fastfoodCustomizeControls.labels['remove'] + '</button>');
					}

					// Update Other Background Attributes.
					api( 'background_repeat' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_repeat'] );
					api( 'background_attachment' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_attachment'] );
					api( 'background_position_x' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_position_x'] );
					api( 'background_position_y' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_position_y'] );

					// Update Icons Color.
					var api_control_background_icons_color = api.control( 'background_icons_color' );
					api( 'background_icons_color' ).set( _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_icons_color'] );
					api_control_background_icons_color.container.find( '.color-picker-hex' )
						.data( 'data-default-color', _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_icons_color'] )
						.wpColorPicker( 'defaultColor', _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_icons_color'] )
						.wpColorPicker( 'color', _fastfoodCustomizeControls.backgroundScheme[value].attributes['background_icons_color'] );
				} );
			}
		}
	} );

	api.controlConstructor.tbslider = api.Control.extend( {
		ready: function() {
			var control     = this.container;
			var slider      = control.find( '.slider-bar' );
			var reset       = control.find( '.slider-reset' );
			var amount      = control.find( '.slider-amount' );
			var display     = control.find( '.slider-value' );
			var value       = parseInt( amount.val() );
			var min         = slider.data( 'min-range' ) ? slider.data( 'min-range' ) : 30;
			var max         = slider.data( 'max-range' ) ? slider.data( 'max-range' ) : 100;
			var defaultUnit = slider.data( 'unit' );

			// Set value to default if it hasn't been set
			if ( '' === amount.val() ) {
				value = amount.data( 'default-value' );
				amount.val( value );
			}
			
			display.text( value + defaultUnit );

			// Init jQuery UI slider
			slider.slider({
				min   : min,
				max   : max,
				value : value,
				slide : function( event, ui ) {
					display.text( ui.value + defaultUnit );
					clearTimeout(timers[event]);
					timers[event] = setTimeout(function() {
						amount.val( ui.value ).trigger('change');
					}, 300);
				}
			});

			/**
			 * Init Reset Event for the font
			 * size slider
			 */
			reset.on( 'click', function(e) {
				e.preventDefault();
				var defaultValue = slider.data( 'default-value' );
				slider.slider({ value : defaultValue });
				display.text( defaultValue + defaultUnit );
				amount.val( defaultValue ).trigger( 'change' );
				return false;
			});
		}
	} );

	// Generate the CSS for the current dynamic css.
	function updateCSS() {
		var css, attributes = _.object();

		// Collect the settings value.
		_.each( _fastfoodCustomizeControls.controls, function( control, setting ) {
			attributes[ setting ] = api( control )();
		});

		attributes.fastfood_rsideb_width = ( parseInt( attributes.fastfood_rsideb_width ) / parseInt( attributes.fastfood_body_width ) ) * 100;
		attributes.fastfood_content_width = 100 - attributes.fastfood_rsideb_width;
		css = cssTemplate( attributes );

		api.previewer.send( 'fastfood-update-dynamic-css', css );
	}

	// Update the CSS whenever a setting is changed.
	_.each( _fastfoodCustomizeControls.controls, function( control, setting ) {
		api( control, function( control ) {
			control.bind( updateCSS );
		} );
	} );

	api( 'fastfood_options[fastfood_body_width]', function( setting ) {
		setting.bind( function( value ) {
			_wpCustomizeHeader.data.width = parseInt( value );
		});
	} );
	api( 'fastfood_options[fastfood_head_h]', function( setting ) {
		setting.bind( function( value ) {
			_wpCustomizeHeader.data.height = parseInt( value );
		});
	} );

	// Control visibility for default controls
	_.each({
		'header_textcolor': {
			controls: [ 'header_text_background' ],
			callback: function( to ) { return 'blank' !== to; }
		},
		'background_image': {
			controls: [ 'background_position_y' ],
			callback: function( to ) { return !! to; }
		}
	}, function( o, settingId ) {
		api( settingId, function( setting ) {
			_.each( o.controls, function( controlId, i ) {
				api.control( controlId, function( control ) {
					var visibility = function( to ) {
						control.container.toggle( o.callback( to ) );
					};

					visibility( setting.get() );
					setting.bind( visibility );
				});
			});
		});
	});

	api.bind( 'ready', function() {
		render_theme_options();
	} );

	function render_theme_options() {

		// Grab the HTML out of our template tag and pre-compile it.
		var theme_nag = _.template( $( "script#tmpl-customize-theme-header" ).html() );
		$( '#customize-info' ).after( theme_nag() );

		toggle_theme_header();

		$( '#customize-theme-header' ).find( '.theme-controls-header-title' ).click( function() {
			if ( $( '#customize-theme-header' ).is( '.ready' ) ) {
				add_theme_controls_headers();
				$( '#customize-theme-header' ).removeClass('ready');
			}
			toggle_theme_header();
		});
	}//end of donate block

	function add_theme_controls_headers() {
		_.each( _fastfoodCustomizeControls.headers, function( header, header_key ) {
			label       = header.label       ? '<div class="theme-controls-header-title" data-sections="control-section-' + header_key + '">' + header.label + '</div>' : '';
			description = header.description ? '<div class="theme-controls-header-description control-subsection control-section-' + header_key + '"><p>' + header.description + '</p></div>' : '';
			$( '<li class="theme-controls-header can-expand">' + label + description + '</li>' )
				.insertBefore( '.control-section-' + header_key + ':first' )
				.on( 'click', '.theme-controls-header-title', function() {
					var sections = $(this).data( 'sections' ) ? '.' + $(this).data( 'sections' ) : false;
					toggle_theme_options( sections );
					$( '#customize-theme-controls .theme-controls-header-title' ).not( this ).parent().removeClass( 'expanded current' );
					$( this ).parent().toggleClass( 'expanded current' );
				});
		});
	}

	function toggle_theme_header() {
		var customize_theme_header = $( '#customize-theme-header' );
		var customize_theme_panels = $( '#customize-theme-controls' ).find( '.control-panel-fastfood_options' );
		if ( customize_theme_header.is( '.collapsed' ) ) {
			customize_theme_header.removeClass( 'collapsed' ).addClass( 'current' );
			$( '.theme-controls-header-description', customize_theme_header ).slideDown( 'fast' );
			customize_theme_panels.hide().css('height','auto').slideDown('fast');
		} else {
			customize_theme_header.addClass( 'collapsed' ).removeClass( 'current' );
			$( '.theme-controls-header-description', customize_theme_header ).slideUp( 'fast' );
			customize_theme_panels.slideUp( 'fast', function() {
				$(this).css('height',0);
			});
		}
	}

	function toggle_theme_options( current ) {
		var _sections = $('#customize-theme-controls').find( '.control-panel-fastfood_options .control-subsection' );
		if ( current ) {
			_sections.filter( current ).each( function() {
				$this = $( this );
				if ( $( this ).is( '.expanded' ) ) {
					$( this ).animate({
						height: 0
					}, 200, function() {
						$( this ).removeClass( 'expanded' ).css({ height: 'auto' });
					});
				} else {
					var cur_height = $( this ).outerHeight();
					$( this ).css({ height: 0 }).addClass( 'expanded' ).animate({
						height: cur_height
					}, 200, function() {
						$( this ).css({ height: 'auto' });
					});
				}
			});
			_sections.not( current ).filter( '.expanded' ).animate({
				height: 0
			}, 200, function() {
				$( this ).removeClass( 'expanded' ).css({ height: 'auto' });
			});
		}
	}

} )( wp.customize, jQuery );







