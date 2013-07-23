<?php
/**
 * Functions and hooks for bbPress integration
 */
 
class Fastfood_bbPress {

	function __construct() {

		add_action( 'wp_head', array( $this, 'init' ), 999 );

	}

	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! ( function_exists( 'is_bbpress' ) && is_bbpress() ) ) return;

		add_filter( 'fastfood_filter_breadcrumb'				, array( $this, 'breadcrumb' ) );
		add_filter( 'bbp_breadcrumb_separator'					, array( $this, 'breadcrumb_sep' ) );
		add_filter( 'fastfood_option_fastfood_xinfos_global'	, '__return_false' );
		add_filter( 'fastfood_option_fastfood_I_like_it'		, '__return_false' );

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

}

new Fastfood_bbPress;
