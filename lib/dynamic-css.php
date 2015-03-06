<?php
/**
 * dynamic-css.php
 *
 * This file contains the utilities for printing out the custom css.
 *
 * @package fastfood
 * @since Fastfood 0.37
 */


/**
 * Enqueues front-end custom CSS.
 *
 * @since Fastfood 0.37
 *
 * @see wp_add_inline_style()
 */
function fastfood_dynamic_css() {

	// gathers the theme mods
	$mods = array(
		'background_color',
		'background_repeat',
		'background_attachment',
		'background_position_x',
		'background_position_y',
		'background_image',
		'background_icons_color',
	);

	$attributes = fastfood_get_background_schemes( '0' );
	$attributes = $attributes['attributes'];

	foreach ( $mods as $attribute_key ) {
		$attributes[$attribute_key] = get_theme_mod( $attribute_key, $attributes[$attribute_key] );
	}

	$attributes['background_color'] = '#' . $attributes['background_color'];

	// gathers the theme options
	$opts = array(
		'fastfood_colors_link',
		'fastfood_colors_link_hover',
		'fastfood_colors_link_sel',
		'fastfood_body_width',
		'fastfood_rsideb_width',
		'fastfood_featured_title_size',
	);

	foreach ( $opts as $attribute_key ) {
		$attributes[$attribute_key] = FastfoodOptions::get_opt( $attribute_key );
	}

	$attributes['fastfood_rsideb_width'] = round( absint( $attributes['fastfood_rsideb_width'] ) / absint( $attributes['fastfood_body_width'] ), 5 ) * 100;
	$attributes['fastfood_content_width'] = 100 - $attributes['fastfood_rsideb_width'];
	$attributes['fastfood_featured_title_size'] = absint( $attributes['fastfood_featured_title_size'] );

	$dynamic_css = fastfood_get_dynamic_css( $attributes );

	wp_add_inline_style( 'fastfood', $dynamic_css );

}
add_action( 'wp_enqueue_scripts', 'fastfood_dynamic_css' );


/**
 * Returns custom CSS.
 *
 * @since Fastfood 0.37
 *
 * @param array $attributes custom attributes.
 * @return string custom CSS.
 */
function fastfood_get_dynamic_css( $attributes ) {

	$attributes = wp_parse_args( $attributes, array(
		'background_color'				=> '',
		'background_repeat'				=> '',
		'background_attachment'			=> '',
		'background_position_x'			=> '',
		'background_position_y'			=> '',
		'background_image'				=> '',
		'background_icons_color'		=> '',
		'fastfood_colors_link'			=> '',
		'fastfood_colors_link_hover'	=> '',
		'fastfood_colors_link_sel'		=> '',
		'fastfood_body_width'			=> '',
		'fastfood_rsideb_width'			=> '',
		'fastfood_content_width'		=> '',
		'fastfood_featured_title_size'	=> '',
	) );

	$css = <<<CSS
	/* dynamic CSS */

	body,
	html body.custom-background {
		background-color: {$attributes['background_color']};
		background-repeat: {$attributes['background_repeat']};
		background-attachment: {$attributes['background_attachment']};
		background-position: {$attributes['background_position_x']} {$attributes['background_position_y']};
		background-image: url({$attributes['background_image']});
	}
	.quickbar-panel .quickbar-panel-icon, .quickbar-panel .quickbar-panel-icon_js, .minibutton .minib_img {
		color: {$attributes['background_icons_color']};
	}
	a {
		color: {$attributes['fastfood_colors_link']};
	}
	textarea:hover,
	.bbpress .wp-editor-area:hover,
	.buddypress .wp-editor-area:hover,
	input[type=text]:hover,
	input[type=password]:hover,
	.ff-js .comment-reply-link:hover,
	textarea:focus,
	input[type=text]:focus,
	input[type=password]:focus {
		border-color: {$attributes['fastfood_colors_link_hover']};
	}
	button,
	.navigation_links a,
	.pagination-links a,
	.nav-single a,
	.nav-pages a span,
	.solid-label .show-comment-form,
	.tb_clean_archives .year-link,
	input[type=button],
	input[type=submit],
	input[type=reset],
	#hide-respond-link,
	div.mejs-container .mejs-controls .mejs-play,
	div.mejs-controls .mejs-time-rail .mejs-time-current,
	#posts_content #infinite-handle span {
		background: {$attributes['fastfood_colors_link']};
	}
	button:hover,
	.navigation_links a:hover,
	.pagination-links a:hover,
	.nav-single a:hover,
	.nav-pages a span:hover,
	.solid-label .show-comment-form:hover,
	.tb_clean_archives .year-link:hover,
	input[type=button]:hover,
	input[type=submit]:hover,
	input[type=reset]:hover,
	#hide-respond-link:hover,
	div.mejs-container .mejs-controls .mejs-pause,
	div.mejs-container .mejs-controls .mejs-play:hover,
	#posts_content #infinite-handle span:hover {
		background: {$attributes['fastfood_colors_link_hover']};
	}
	a:hover,
	.current-menu-item a:hover,
	.current_page_item a:hover,
	.current-cat a:hover,
	#mainmenu .menu-item-parent:hover > a:after {
		color: {$attributes['fastfood_colors_link_hover']};
	}
	.current-menu-item > a,
	.current_page_item > a,
	.current-cat > a,
	.crumbs .last,
	.crumbs li:last-of-type,
	.menu-item-parent:hover > a:after,
	.current-menu-ancestor > a:after,
	.current-menu-parent > a:after,
	.current_page_parent > a:after,
	.current_page_ancestor > a:after,
	#navxt-crumbs li.current_item,
	li.current-menu-ancestor > a:after {
		color: {$attributes['fastfood_colors_link_sel']};
	}
	#ff_background:before,
	#main,
	#fixedfoot,
	.quickbar-panel-container,
	.quickbar-panel-container_js {
		width: {$attributes['fastfood_body_width']}px;
	}
	#primary-widget-area {
		width: {$attributes['fastfood_rsideb_width']}%;
	}
	.posts_narrow {
		width: {$attributes['fastfood_content_width']}%;
	}
	.entry-title.with-thumbnail .entry-title-content {
		padding-left: {$attributes['fastfood_featured_title_size']}px;
		min-height: {$attributes['fastfood_featured_title_size']}px;
	}
	.entry-title-thumbnail{
		width: {$attributes['fastfood_featured_title_size']}px;
	}

CSS;

	return $css;

}


/**
 * Enqueues front-end custom CSS.
 *
 * @since Fastfood 0.37
 *
 * @see wp_add_inline_style()
 */
function fastfood_custom_fonts() {

	$attributes['fastfood_font_size']					= absint( FastfoodOptions::get_opt( 'fastfood_font_size' ) );
	$attributes['fastfood_google_font_family']			= FastfoodOptions::get_opt( 'fastfood_google_font_family' );
	$attributes['fastfood_font_family']					= ( $attributes['fastfood_google_font_family'] && FastfoodOptions::get_opt( 'fastfood_google_font_body' ) ) ? $attributes['fastfood_google_font_family'] : FastfoodOptions::get_opt( 'fastfood_font_family' );
	$attributes['fastfood_google_font_post_title']		= ( $attributes['fastfood_google_font_family'] && FastfoodOptions::get_opt( 'fastfood_google_font_post_title' ) ) ? $attributes['fastfood_google_font_family'] : 'inherit';
	$attributes['fastfood_google_font_post_content']	= ( $attributes['fastfood_google_font_family'] && FastfoodOptions::get_opt( 'fastfood_google_font_post_content' ) ) ? $attributes['fastfood_google_font_family'] : 'inherit';

	$css = <<<CSS

	body {
		font-size: {$attributes['fastfood_font_size']}px;
		font-family: {$attributes['fastfood_font_family']};
	}
	h2.entry-title {
		font-family: {$attributes['fastfood_google_font_post_title']};
	}
	.entry-content {
		font-family: {$attributes['fastfood_google_font_post_content']};
	}

CSS;

	wp_add_inline_style( 'fastfood', $css );

}
add_action( 'wp_enqueue_scripts', 'fastfood_custom_fonts' );


/**
 * Enqueues front-end custom CSS.
 *
 * @since Fastfood 0.37
 *
 * @see wp_add_inline_style()
 */
function fastfood_custom_css() {

	$attributes['fastfood_custom_css']		= wp_strip_all_tags( FastfoodOptions::get_opt( 'fastfood_custom_css' ) );
	$attributes['fastfood_allowed_tags']	= FastfoodOptions::get_opt( 'fastfood_allowed_tags' ) ? 'block' : 'none';

	$css = <<<CSS
	/* custom CSS */

	.form-allowed-tags {
		display: {$attributes['fastfood_allowed_tags']};
	}

	{$attributes['fastfood_custom_css']}

CSS;

	wp_add_inline_style( 'fastfood', $css );

}
add_action( 'wp_enqueue_scripts', 'fastfood_custom_css' );
