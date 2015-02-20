/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	var $style = $( '#fastfood-dynamic-css' ),
		api = wp.customize;

	if ( !$style.length ) {
		$style = $( 'head' ).append( '<style type="text/css" id="fastfood-dynamic-css" />' )
		                    .find( '#fastfood-dynamic-css' );
	}

	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Site title color.
	api( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			$('#head-text, #head-text a').css( "color", to ).toggle( 'blank' !== to );
		} );
	} );

	// Site title background.
	api( 'header_text_background', function( value ) {
		value.bind( function( to ) {
			$('#head-text').css( "background-color", convertHex( to, 0.7 ) );
		} );
	} );

	// Site image height.
	api( 'fastfood_options[fastfood_head_h]', function( value ) {
		value.bind( function( to ) {
			$('#head-image').css( "max-height", parseInt( to ) );
		} );
	} );

	// Sidebar.
	api( 'fastfood_options[fastfood_rsideb_position]', function( value ) {
		value.bind( function( to ) {
			$('body').toggleClass( 'left-sidebar', 'right' !== to );
		} );
	} );

	// Font Family.
	api( 'fastfood_options[fastfood_font_family]', function( value ) {
		value.bind( function( to ) {
			$('body').css( "font-family", to );
		} );
	} );

	// Font Size.
	api( 'fastfood_options[fastfood_font_size]', function( value ) {
		value.bind( function( to ) {
			$('body').css( "font-size", parseInt( to ) );
		} );
	} );

	// Color Scheme CSS.
	api.bind( 'preview-ready', function() {
		api.preview.bind( 'fastfood-update-dynamic-css', function( css ) {
			$style.html( css );
		} );
	} );

	function convertHex( hex, opacity ){
		if ( !hex )
			return 'transparent';
		hex = hex.replace( '#', '' );
		r = parseInt( hex.substring( 0, 2 ), 16 );
		g = parseInt( hex.substring( 2, 4 ), 16 );
		b = parseInt( hex.substring( 4, 6 ), 16 );

		result = 'rgba('+r+','+g+','+b+','+opacity+')';
		return result;
	}

} )( jQuery );
