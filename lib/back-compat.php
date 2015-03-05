<?php
/**
 * Fastfood back compat functionality
 *
 * HERE GOES SOMETHING
 *
 * @package Fastfood
 * @since Fastfood 0.37
 */


if ( version_compare( $GLOBALS['wp_version'], fastfood_get_info( 'required_wp_version' ), '<' ) ) {
	add_action( 'after_switch_theme'	, 'fastfood_switch_theme' );
	add_action( 'template_redirect'		, 'fastfood_preview' );
	add_action( 'customize_register'	, 'fastfood_load_customizer', 1 );
}

add_action( 'after_setup_theme', 'fastfood_upgrade', 1 );


/**
 * Prevent switching to Fastfood on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Fastfood 0.37
 */
function fastfood_switch_theme() {

	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'fastfood_upgrade_notice' );

}


/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Fastfood on old WordPress versions.
 *
 * @since Fastfood 0.37
 */
function fastfood_upgrade_notice() {

	$message = sprintf( __( 'Fastfood requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.', 'fastfood' ), fastfood_get_info( 'required_wp_version' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );

}


/**
 * Prevent the Customizer from being loaded on old WordPress versions.
 *
 * @since Fastfood 0.37
 */
function fastfood_load_customizer() {

		wp_die( sprintf( __( 'Fastfood requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.', 'fastfood' ), fastfood_get_info( 'required_wp_version' ), $GLOBALS['wp_version'] ), '', array(
			'back_link' => true,
		) );

}


/**
 * Prevent the Theme Preview from being loaded on old WordPress versions.
 *
 * @since Fastfood 0.37
 */
function fastfood_preview() {

	if ( isset( $_GET['preview'] ) )
		wp_die( sprintf( __( 'Fastfood requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.', 'fastfood' ), fastfood_get_info( 'required_wp_version' ), $GLOBALS['wp_version'] ) );

}


/**
 * Update some mods and options
 *
 * @since Fastfood 0.37
 */
function fastfood_upgrade() {

	if ( !is_admin() ) return;

	$options_version = get_theme_mod( 'options_version', '0.36' );

	if ( version_compare( $options_version, '0.37', '<' ) ) {

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

		$iconfont_color = get_theme_mod( 'background_icons_color' );

		if ( $background_color && !$iconfont_color ) {

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

			set_theme_mod( 'header_text_background', $header_text_background );

		}

	}

	set_theme_mod( 'options_version', fastfood_get_info( 'version' ) );

}
