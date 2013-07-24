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

		if ( fastfood_is_mobile() ) return;

		//Infinite Scroll
		add_theme_support( 'infinite-scroll', array(
			'type'			=> 'click',
			'container'		=> 'posts_content',
			'render'		=> array( $this, 'infinite_scroll_render' ),
			'wrapper'		=> false,
		) );
		if ( class_exists( 'The_Neverending_Home_Page' ) ) {
			add_filter		( 'infinite_scroll_results'		, array( $this, 'infinite_scroll_encode' ), 11, 1 );
		}

		//Sharedaddy
		if ( function_exists( 'sharing_display' ) ) {
			remove_filter	( 'the_content'									, 'sharing_display', 19 );
			remove_filter	( 'the_excerpt'									, 'sharing_display', 19 );
			remove_action	( 'fastfood_hook_entry_before'					, 'fastfood_I_like_it' );
			add_action		( 'fastfood_hook_entry_bottom'					, array( $this, 'sharedaddy' ) );
		}

		//Carousel
		if ( class_exists( 'Jetpack_Carousel' ) ) {
			remove_filter	( 'post_gallery'								, 'fastfood_gallery_shortcode', 10, 2 );
			add_filter		( 'fastfood_option_fastfood_gallery_preview'	, '__return_false' );
		}

		//Likes
		if ( class_exists( 'Jetpack_Likes' ) ) {
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

		add_filter( 'fastfood_filter_breadcrumb'				, array( $this, 'breadcrumb' ) );
		add_filter( 'bbp_breadcrumb_separator'					, array( $this, 'breadcrumb_sep' ) );
		add_filter( 'fastfood_option_fastfood_xinfos_global'	, '__return_false' );
		add_filter( 'fastfood_option_fastfood_I_like_it'		, '__return_false' );
		add_filter( 'fastfood_filter_navbuttons'				, array( $this, 'navbuttons' ) );
		add_filter( 'fastfood_use_sidebar'						, array( $this, 'show_sidebar' ) );
		add_filter( 'fastfood_skip_post_widgets_area'			, '__return_true' );

	}

	/**
	 * Filter breadcrumb for bbPress Topics
	 */
	function breadcrumb() {

		$args = array(
			'before'		=> '<div id="crumbs" class="breadcrumb-navigation"><ul>',
			'after'			=> '<br class="fixfloat" /></ul></div>',
			'crumb_before'	=> '<li>',
			'crumb_after'	=> '</li>',
			'home_text'		=> '&nbsp;',
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

		if ( ! fastfood_get_opt( 'fastfood_rsideb_bbpress' ) )
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

		add_filter( 'fastfood_option_fastfood_xinfos_global'	, '__return_false' );
		add_filter( 'fastfood_option_fastfood_I_like_it'		, '__return_false' );
		add_filter( 'fastfood_skip_post_widgets_area'			, '__return_true' );
		add_filter( 'fastfood_use_sidebar'						, array( $this, 'show_sidebar' ) );
		add_filter( 'fastfood_filter_navbuttons'				, array( $this, 'navbuttons' ) );

	}

	function extra_options( $coa ) {

		$coa['fastfood_rsideb_buddypress'] = array(
			'group'				=> 'quickbar',
			'type'				=> 'chk',
			'default'			=> 0,
			'description'		=> __( 'in BuddyPress activity', 'fastfood' ),
			'info'				=> '',
			'req'				=> '',
			'sub'				=> false
		);

		$coa['fastfood_rsideb']['sub'][] = 'fastfood_rsideb_buddypress';

		return $coa;

	}

	function show_sidebar( $bool ) {

		if ( ! fastfood_get_opt( 'fastfood_rsideb_buddypress' ) )
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

new Fastfood_BuddyPress;
