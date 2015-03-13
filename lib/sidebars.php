<?php
/**
 * sidebars.php
 *
 * The widget areas stuff
 *
 * @package fastfood
 * @since fastfood 0.37
 */


add_action( 'fastfood_hook_site_header', 'fastfood_sidebar_header'   , 13    );
add_action( 'fastfood_hook_footer'     , 'fastfood_sidebar_footer'   , 11    );
add_action( 'widgets_init'             , 'fastfood_register_sidebars'        );


/**
 * Is the primary widget area visible?
 * 
 * @return bool
 */
function fastfood_use_sidebar() {
	static $bool;

	if ( !isset( $bool ) ) {

		$bool = true;

		if (
			( !is_singular()  && !FastfoodOptions::get_opt( 'fastfood_rsidebindexes'     ) ) ||
			( is_page()       && !FastfoodOptions::get_opt( 'fastfood_rsidebpages'       ) ) ||
			( is_attachment() && !FastfoodOptions::get_opt( 'fastfood_rsidebattachments' ) ) ||
			( is_single()     && !FastfoodOptions::get_opt( 'fastfood_rsidebposts'       ) )
		)
			$bool = false;

		$bool = apply_filters( 'fastfood_use_sidebar', $bool );

	}

	return $bool;

}


/**
 * Check conditions and call the desired sidebar
 * 
 * @param	string	$name				(optional) the sidebar slug
 * @param	boolean	$only_if_active		(optional) condition
 * @return	none
 */
function fastfood_get_sidebar( $name = 'primary', $only_if_active = false ) {

	if ( ( $name === 'primary' ) && !fastfood_use_sidebar() ) return;

	if ( !apply_filters( 'fastfood_get_sidebar_' . $name, true ) ) return;

	$sidebars = fastfood_register_sidebars();

	if ( $only_if_active && !is_active_sidebar( $sidebars[$name] ) ) return;

	get_sidebar( $name );

}


// default widgets to be printed in primary sidebar
function fastfood_default_widgets() {

	$default_widgets = array(
		'WP_Widget_Search',
		'WP_Widget_Meta',
		'WP_Widget_Pages',
		'WP_Widget_Categories',
		'WP_Widget_Archives'
	);

	foreach ( apply_filters( 'fastfood_default_widgets', $default_widgets ) as $widget ) {

		if ( class_exists( $widget ) ) the_widget( $widget, '', fastfood_get_default_widget_args() );

	}

}


/**
 * Define default Widget arguments
 */
function fastfood_get_default_widget_args( $widget_area = 'primary-widget-area' ) {

	return apply_filters( 'fastfood_get_default_widget_args', array(
		'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'		=> '</div>',
		'before_title'		=> '<h2 class="widgettitle">',
		'after_title'		=> '</h2>',
	), $widget_area );

}


/**
 * Register sidebars
 */
function fastfood_register_sidebars() {
	static $registered_sidebars = array();

	if ( empty( $registered_sidebars ) ) {

		// Area 1, located at the top of the sidebar.
		$registered_sidebars['primary'] = register_sidebar( array_merge( 
			array(
				'name'			=> __( 'Sidebar Widget Area', 'fastfood' ),
				'id'			=> 'primary-widget-area',
				'description'	=> __( 'The sidebar widget area', 'fastfood' ),
				'columns'		=> 1,
			),
			fastfood_get_default_widget_args( 'primary-widget-area' )
		) );

		// Area 2, located under the main menu.
		$registered_sidebars['header'] = register_sidebar( array_merge( 
			array(
				'name'			=> __( 'Header Widget Area', 'fastfood' ),
				'id'			=> 'header-widget-area',
				'description'	=> __( 'The widget area under the main menu', 'fastfood' ),
				'columns'		=> 3,
			),
			fastfood_get_default_widget_args( 'header-widget-area' )
		) );

		// Area 3, located after post/page content.
		$registered_sidebars['singular'] = register_sidebar( array_merge( 
			array(
				'name'			=> __( 'Post/Page Widget Area', 'fastfood' ),
				'id'			=> 'post-widgets-area',
				'description'	=> __( "The widget area after the post/page content. It's visible only in single posts/pages/attachments", 'fastfood' ),
				'columns'		=> 2,
			),
			fastfood_get_default_widget_args( 'post-widgets-area' )
		) );

		// Area 4, located after post/page content.
		$registered_sidebars['footer'] = register_sidebar( array_merge( 
			array(
				'name'			=> __( 'Footer Widget Area', 'fastfood' ),
				'id'			=> 'footer-widget-area',
				'description'	=> __( 'The footer widget area', 'fastfood' ),
				'columns'		=> 3,
			),
			fastfood_get_default_widget_args( 'footer-widget-area' )
		) );

		// Area 5, located in page 404.
		$registered_sidebars['error404'] = register_sidebar( array_merge( 
			array(
				'name'			=> __( 'Page 404 Widget Area', 'fastfood' ),
				'id'			=> 'error404-widgets-area',
				'description'	=> __( 'Enrich the page 404 with some useful widgets', 'fastfood' ),
				'columns'		=> 2,
			),
			fastfood_get_default_widget_args( 'error404-widgets-area' )
		) );

		// Area 6, located in footer.
		$registered_sidebars['hidden'] = register_sidebar( array_merge( 
			array(
				'name'			=> __( 'Hidden Widget Area', 'fastfood' ),
				'id'			=> 'hidden-widget-area',
				'description'	=> __( 'This widget area is not visible. Drop here your widget for eg. analytics scripts or whatever you want to keep hidden', 'fastfood' ),
				'columns'		=> 1,
			),
			fastfood_get_default_widget_args( 'hidden-widget-area' )
		) );

	}

	return $registered_sidebars;

}


function fastfood_sidebar_header() {

	fastfood_get_sidebar( 'header', true );

}


function fastfood_sidebar_footer() {

	fastfood_get_sidebar( 'footer', true );

}


















