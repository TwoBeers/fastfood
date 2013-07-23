<?php
/**
 * Functions and hooks for BuddyPress integration
 */
 
class Fastfood_BuddyPress {

	function __construct() {

		add_action( 'wp_head', array( $this, 'init' ), 999 );

	}

	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! ( function_exists( 'is_buddypress' ) && is_buddypress() ) ) return;

		add_filter( 'fastfood_option_fastfood_xinfos_global'	, '__return_false' );
		add_filter( 'fastfood_option_fastfood_I_like_it'		, '__return_false' );

	}

}

new Fastfood_BuddyPress;
