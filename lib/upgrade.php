<?php
/**
 * upgrade.php
 *
 * Fastfood Upgrade
 *
 * @package fastfood
 * @since fastfood 0.37
 */


function fastfood_upgrade() {

	if ( !is_admin() ) return;

	$previous_version = get_theme_mod( 'current_version', '0.36' );

	if ( version_compare( $previous_version, '0.37', '<' ) ) {

		// footer widget area
		$sidebars_widgets = get_option( 'sidebars_widgets', array() );

		if (
			isset( $sidebars_widgets['first-footer-widget-area'] ) &&
			isset( $sidebars_widgets['second-footer-widget-area'] ) &&
			isset( $sidebars_widgets['third-footer-widget-area'] )
		) {
			$_sidebars_widgets = array_merge( $sidebars_widgets['first-footer-widget-area'], $sidebars_widgets['second-footer-widget-area'], $sidebars_widgets['third-footer-widget-area'] );
			$sidebars_widgets['footer-widget-area'] = $_sidebars_widgets;
			unset( $sidebars_widgets['first-footer-widget-area'] );
			unset( $sidebars_widgets['second-footer-widget-area'] );
			unset( $sidebars_widgets['third-footer-widget-area'] );
			wp_set_sidebars_widgets( $sidebars_widgets );
		}

		// header slider
		$header_image = get_theme_mod( 'header_image' );

		if ( $header_image === 'fastfood-slider-uploaded' ) {
			set_theme_mod( 'header_image', '' );
			set_theme_mod( 'display_header_slider', 1 );
		}

		// quickbar-navbar icons
		$background_color = get_theme_mod( 'background_color' );

		$iconfont_color = '#404040';

		if ( $background_color ) {

			$r = hexdec( substr( $background_color, 0, 2 ) );
			$g = hexdec( substr( $background_color, 2, 2) );
			$b = hexdec( substr( $background_color, 4, 2) );
			$yiq = ( ( $r*299 )+( $g*587 )+( $b*114 ) )/1000;
			$iconfont_color = ( $yiq >= 120 ) ? '#404040' : '#FFFFFF';

			set_theme_mod( 'background_icons_color', $iconfont_color );

		}

		// header text background
		$header_text_background = get_theme_mod( 'header_text_background' );

		if ( $header_text_background ) {

			$match = array( 'transparent' => 'transparent', 'black' => '#000000', 'white' => '#FFFFFF' );

			$header_text_background = isset( $match[$header_text_background] ) ? $match[$header_text_background] : 'transparent';

			set_theme_mod( 'background_icons_color', $header_text_background );

		}

	}

	set_theme_mod( 'current_version', fastfood_get_info( 'version' ) );

}
add_action( 'after_setup_theme', 'fastfood_upgrade', 1 );

