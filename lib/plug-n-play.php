<?php
/**
 * plug-n-play.php
 *
 * Plugins support
 *
 * @package fastfood
 * @since 0.37
 */


/**
 * Functions and hooks for Jetpack integration
 */
class Fastfood_For_Jetpack {

	function __construct () {

		add_action( 'init', array( $this, 'init' ) ); //Jetpack support

	}


	/* initialize Jetpack support */
	function init() {

		if ( ! class_exists( 'Jetpack' ) ) return;

		if ( fastfood_is_mobile() ) return;

		//Infinite Scroll
		add_theme_support( 'infinite-scroll', array(
			'type'			=> 'click',
			'container'		=> 'posts_content',
			'render'		=> array( $this, 'infinite_scroll_render' ),
			'wrapper'		=> false,
		) );
		if ( Jetpack::is_module_active( 'infinite-scroll' ) ) {
			//add_filter		( 'infinite_scroll_results'		, array( $this, 'infinite_scroll_encode' ), 11, 1 );
			remove_action( 'fastfood_hook_loop_after'						, 'fastfood_navigate_archives' );
		}

		//Sharedaddy
		if ( Jetpack::is_module_active( 'sharedaddy' ) ) {
			remove_filter	( 'the_content'									, 'sharing_display', 19 );
			remove_filter	( 'the_excerpt'									, 'sharing_display', 19 );
			add_action		( 'fastfood_hook_entry_bottom'					, array( $this, 'sharedaddy' ) );
		}

		//Carousel
		if ( Jetpack::is_module_active( 'carousel' ) ) {
			add_filter		( 'fastfood_option_fastfood_gallery_preview'	, '__return_false' );
		}

		//Likes
		if ( Jetpack::is_module_active( 'likes' ) ) {
			add_action		( 'fastfood_hook_entry_bottom'					, array( $this, 'likes' ) );
			remove_filter	( 'the_content'									, array( Jetpack_Likes::init(), 'post_likes' ), 30, 1);
			add_filter		( 'fastfood_filter_likes'						, array( Jetpack_Likes::init(), 'post_likes' ), 30, 1);
		}

	}


	//print the "likes" button after post content
	function likes() {

		echo '<br class="fixfloat" />' . apply_filters('fastfood_filter_likes','') . '<br class="fixfloat" />';

	}


	//Set the code to be rendered on for calling posts,
	function infinite_scroll_render() {

		get_template_part( 'loop' );

	}


	//re-encodes html result to UTF8 (jetpack bug?)
	//http://localhost/wordpress/?infinity=scrolling&action=infinite_scroll&page=5&order=DESC
	function infinite_scroll_encode( $results ) {

		$results['html'] = utf8_encode( utf8_decode( $results['html'] ) );
		return $results;

	}


	//print the sharedaddy buttons inside the "I-like-it" container instead of after post content
	function sharedaddy() {

		echo sharing_display();

	}

}

new Fastfood_For_Jetpack;



/**
 * Functions and hooks for bbPress integration
 */
class Fastfood_bbPress {

	function __construct() {

		if ( ! function_exists( 'is_bbpress' ) ) return;

		add_action( 'wp_head'									, array( $this, 'init' ), 999 );
		add_filter( 'fastfood_options_array'					, array( $this, 'extra_options' ), 10, 1 );

	}

	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! is_bbpress() ) return;

		add_filter( 'fastfood_breadcrumb'				, array( $this, 'breadcrumb' ) );
		//add_filter( 'bbp_breadcrumb_separator'					, array( $this, 'breadcrumb_sep' ) );
		add_filter( 'fastfood_option_fastfood_xinfos_global'	, '__return_false' );
		add_filter( 'fastfood_filter_navbuttons'				, array( $this, 'navbuttons' ) );
		add_filter( 'fastfood_use_sidebar'						, array( $this, 'show_sidebar' ) );
		add_filter( 'fastfood_skip_post_widgets_area'			, '__return_true' );

	}

	/**
	 * Filter breadcrumb for bbPress Topics
	 */
	function breadcrumb() {

		$args = array(
			'before'	=> '<div class="crumbs">',
			'after'		=> '</div>',
			'home_text'	=> '<i class="el-icon-home"></i><span class="screen-reader-text">Home</span>',
		);

		if ( bbp_is_user_home() )
			$args['current_text'] = bbp_get_displayed_user_field( 'display_name' );

		return $breadcrumb = bbp_get_breadcrumb( $args );

	}

	function breadcrumb_sep( $sep ) {

		return '';

	}

	function extra_options( $coa ) {

		$coa['fastfood_rsideb_bbpress'] = array(
			'group'				=> 'quickbar',
			'type'				=> 'chk',
			'default'			=> 0,
			'description'		=> __( 'in bbPress forums', 'fastfood' ),
			'info'				=> '',
			'req'				=> '',
			'sub'				=> false
		);

		$coa['fastfood_rsideb']['sub'][] = 'fastfood_rsideb_bbpress';

		return $coa;

	}

	function show_sidebar( $bool ) {

		if ( ! FastfoodOptions::get_opt( 'fastfood_rsideb_bbpress' ) )
			$bool = false;

		return $bool;

	}

	function navbuttons( $buttons ) {

		foreach ( array( 'print', 'comment', 'feed', 'trackback', 'nextpost', 'prevpost', 'newposts', 'oldposts', 'nextpost' ) as $hidden ) {
			unset( $buttons[$hidden] );
		}

		return $buttons;

	}

}

new Fastfood_bbPress;



/**
 * Functions and hooks for BuddyPress integration
 */
class Fastfood_BuddyPress {

	function __construct() {

		if ( ! function_exists( 'is_buddypress' ) ) return;

		add_action( 'wp_head'									, array( $this, 'init' ), 999 );
		add_filter( 'fastfood_options_array'					, array( $this, 'extra_options' ), 10, 1 );

	}

	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! is_buddypress() ) return;

		add_filter( 'fastfood_option_fastfood_xinfos_global'		, '__return_false' );
		add_filter( 'fastfood_option_fastfood_hide_frontpage_title'	, '__return_false' );
		add_filter( 'fastfood_skip_post_widgets_area'				, '__return_true' );
		add_filter( 'fastfood_use_sidebar'							, array( $this, 'show_sidebar' ) );
		add_filter( 'fastfood_filter_featured_title'				, array( $this, 'show_title' ) );
		add_filter( 'fastfood_filter_navbuttons'					, array( $this, 'navbuttons' ) );

	}

	function extra_options( $coa ) {

		$coa['fastfood_rsideb_buddypress'] = array(
			'group'				=> 'quickbar',
			'type'				=> 'chk',
			'default'			=> 0,
			'description'		=> __( 'in BuddyPress', 'fastfood' ),
			'info'				=> '',
			'req'				=> '',
			'sub'				=> false
		);

		$coa['fastfood_hide_buddypress_title'] = array(
			'group'				=> 'content',
			'type'				=> 'chk',
			'default'			=> 0,
			'description'		=> __( 'in BuddyPress', 'fastfood' ),
			'info'				=> '',
			'req'				=> '',
			'sub'				=> false
		);

		$coa['fastfood_rsideb']['sub'][]		= 'fastfood_rsideb_buddypress';
		$coa['fastfood_hide_titles']['sub'][]	= 'fastfood_hide_buddypress_title';

		return $coa;

	}

	function show_sidebar( $bool ) {

		if ( ! FastfoodOptions::get_opt( 'fastfood_rsideb_buddypress' ) )
			$bool = false;

		return $bool;

	}

	function show_title( $title ) {

		if ( FastfoodOptions::get_opt( 'fastfood_hide_buddypress_title' ) )
			$title = '';

		return $title;

	}

	function navbuttons( $buttons ) {

		foreach ( array( 'print', 'comment', 'feed', 'trackback', 'nextpost', 'prevpost', 'newposts', 'oldposts', 'nextpost' ) as $hidden ) {
			unset( $buttons[$hidden] );
		}

		return $buttons;

	}

}

new Fastfood_BuddyPress;


/**
 * Functions and hooks for Breadcrumb NavXT integration
 */
class Fastfood_For_NavXT {

	function __construct() {

		add_filter( 'fastfood_breadcrumb'	, array( $this, 'display_breadcrumb' ), 10, 2 );

	}

	function display_breadcrumb( $output, $base_link ) {

		if ( function_exists( 'bcn_display_list' ) ) {

			$base_link = '<li class="home">' . $base_link . '</li>';

			$output = '<ul class="crumbs navxt">' . $base_link . bcn_display_list( $return = true ) . '</ul>';

		}

		return $output;

	}

}

new Fastfood_For_NavXT;


/**
 * Functions and hooks for Yoast Breadcrumbs integration
 */
class Fastfood_For_Yoast_Breadcrumbs {

	function __construct() {

		add_filter( 'wpseo_breadcrumb_output_wrapper'	, array( $this, 'breadcrumb_output_wrapper' ) );
		add_filter( 'wpseo_breadcrumb_output_class'		, array( $this, 'breadcrumb_output_class' ) );
		add_filter( 'wpseo_breadcrumb_separator'		, array( $this, 'breadcrumb_separator' ) );
		add_filter( 'wpseo_breadcrumb_links'			, array( $this, 'breadcrumb_links' ) );
		add_filter( 'fastfood_breadcrumb'				, array( $this, 'display_breadcrumb' ), 10, 2 );

	}

	function breadcrumb_output_wrapper( $data ) {

		return 'div';

	}

	function breadcrumb_output_class( $data ) {

		return 'crumbs wpseo';

	}

	function breadcrumb_separator( $data ) {

		return '<span class="delimiter">'. $data . '</span>';

	}

	function breadcrumb_links( $data ) {

		$home = array(
			'text'			=> '<i class="el-icon-home"></i><span class="screen-reader-text">Home</span>',
			'url'			=> home_url( '/' ),
			'allow_html'	=> true,
		);
		$i = array_unshift( $data, $home );
		if ( isset( $data[ $i - 1 ]['text'] ) )
			$data[ $i - 1 ]['text'] = '<i class="el-icon-placeholder"></i>' . $data[ $i - 1 ]['text'];
		return $data;

	}

	function display_breadcrumb( $output, $base_link ) {

		if ( function_exists( 'yoast_breadcrumb' ) ) {

			$_output = yoast_breadcrumb( '', '', false );

			if ( $_output )
				$output = $_output ;

		}

		return $output;

	}

}

new Fastfood_For_Yoast_Breadcrumbs;


/**
 * Functions and hooks for WP Paginate integration
 */
class Fastfood_For_WP_Paginate {

	function __construct() {

		add_action( 'wp_print_styles', array( $this, 'dequeue_style' ), 99 );

		add_filter( 'fastfood_filter_navigation_comments'	, array( $this, 'navigate_comments' ) );
		add_filter( 'fastfood_filter_navigation_archives'	, array( $this, 'navigate_archives' ) );

	}

	function dequeue_style() {

		wp_dequeue_style( 'wp-paginate' );

	}

	function navigate_comments( $bool ) {

		if ( function_exists( 'wp_paginate_comments' ) ) {

			wp_paginate_comments();

			$bool = true;

		}

		return $bool;

	}

	function navigate_archives( $bool ) {

		if ( function_exists( 'wp_paginate' ) ) {

			wp_paginate();

			$bool = true;

		}

		return $bool;

	}

}

new Fastfood_For_WP_Paginate;


/**
 * Functions and hooks for WP-Pagenavi integration
 */
class Fastfood_For_WP_Pagenavi {

	function __construct() {

		add_action( 'wp_print_styles', array( $this, 'dequeue_style' ), 99 );

		add_filter( 'fastfood_filter_navigation_archives', array( $this, 'navigate_archives' ) );

	}

	function dequeue_style() {

		wp_dequeue_style( 'wp-pagenavi' );

	}

	function navigate_archives( $bool ) {

		if ( function_exists( 'wp_pagenavi' ) ) {

			wp_pagenavi();

			$bool = true;

		}

		return $bool;

	}

}

new Fastfood_For_WP_Pagenavi;
