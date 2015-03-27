<?php
/**
 * breadcrumb.php
 *
 * The breadcrumb class.
 *
 * @package fastfood
 * @since 0.34
 */


class Fastfood_Breadcrumb {

	function __construct() {

		add_action( 'after_setup_theme', array( $this, 'setup' ) );

	}

	function setup() {

		add_action( 'fastfood_hook_builder', array( $this, 'display' ), 10, array( 'id' => 'breadcrumb', 'section' => 'header', 'priority' => 99, 'label' => __( 'Breadcrumb', 'fastfood' ) ) );

	}


	function display() {

		if ( !FastfoodOptions::get_opt( 'fastfood_breadcrumb' ) ) return;

		echo $this->get_the_breadcrumb();

	}


	function get_the_breadcrumb() {

		$defaults = apply_filters( 'fastfood_breadcrumb_defaults', array(
			'container_before'			=> '',
			'container_after'			=> '',
			'container_crumb_open'		=> '<div class="crumbs">',
			'container_crumb_close'		=> '</div>',
			'delimiter'					=> '<span class="delimiter"> &raquo; </span>',
			'homename'					=> 'Home', //text for the 'Home' link
			'blogname'					=> 'Blog', //text for the 'Blog' link
			'current_before'			=> '<span class="current">',
			'current_after'				=> '</span>',
		) );

		extract( $defaults );

		$base_link = '';
		$hierarchy = '';
		$current_location = '';
		$current_location_link = '';
		$crumb_pagination = '';
		$home = home_url( '/' );

		global $wp_query, $post;

		// Base Link
		$base_link = '<a class="home" href="' . $home . '"><i class="el-icon-home"></i><span class="screen-reader-text">' . $homename . '</span></a>';

		// If static Page as Front Page, and on Blog Posts Index
		if ( is_home() && ( 'page' == get_option( 'show_on_front' ) ) ) {
			$hierarchy = $delimiter;
			$current_location = $blogname;
		}
		// If static Page as Front Page, and on Blog, output Blog link
		if ( !is_home() && !is_page() && !is_front_page() && ( 'page' == get_option( 'show_on_front' ) ) ) {
			$hierarchy = $delimiter;
			$current_location = '<a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">' . $blogname . '</a>';
		}
		// Define Category Hierarchy Crumbs for Category Archive
		if ( is_category() ) { 
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category( $thisCat );
			$parentCat = get_category( $thisCat->parent );
			if ( $thisCat->parent != 0 ) {
				$hierarchy = $delimiter . get_category_parents( $parentCat, TRUE, $delimiter );
			} else {
				$hierarchy = $delimiter;
			}
				// Set $current_location to the current category
				$current_location = single_cat_title( '' , FALSE ); 
		}
		// Define Crumbs for Day/Year/Month Date-based Archives
		elseif ( is_date() ) { 
			// Define Year/Month Hierarchy Crumbs for Day Archive
			if  ( is_day() ) {
				$date_string = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $delimiter . '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a>';
				$date_string .= $delimiter;
				$current_location = get_the_date(); 
			} 
			// Define Year Hierarchy Crumb for Month Archive
			elseif ( is_month() ) {
				$date_string = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>';
				$date_string .= $delimiter;
				$current_location = get_the_time( 'F' ); 
			} 
			// Set current_location for Year Archive
			elseif ( is_year() ) {
				$date_string = '';
				$current_location = get_the_time( 'Y' ); 
			}
			$hierarchy = $delimiter . $date_string; 
		}
		// Define Category Hierarchy Crumbs for Single Posts
		elseif ( is_singular( 'post' ) ) { 
			$cats = get_the_category(); 
			// Assume the first category is current
			$current_cat = ( $cats ? $cats[0] : '' );
			// Determine if category is hierarchical
			$cat_is_hierarchical = false;
			foreach ( $cats as $cat ) {
				if ( '0' != $cat->parent ) {
					$cat_is_hierarchical = true;
					break;
				}
			}
			// If category is hierarchical,
			// ensure we have the correct child category
			if ( $cat_is_hierarchical ) {
				foreach ( $cats as $cat ) {
					$children = get_categories( array( 'parent' => $cat->term_id ) );
					if ( 0 == count( $children ) ) {
						$current_cat = $cat;
						break;
					}
				}
			}
			// Get the hierarchical list of category links
			$hierarchy = $delimiter . get_category_parents( $current_cat, TRUE, $delimiter );
			// Note: get_the_title() is filtered to output a
			// default title if none is specified
			$current_location = get_the_title();
		}
		// Define Category and Parent Post Crumbs for Post Attachments
		elseif ( is_attachment() ) { 
			$hierarchy = $delimiter;
			$parent = $post->post_parent;
			if ( $parent ) {
				$parent = get_post( $parent );
				$cat_parents = '';
				if ( get_the_category( $parent->ID ) ) {
					$cat = get_the_category( $parent->ID ); 
					$cat = $cat[0];
					$cat_parents = get_category_parents( $cat, TRUE, $delimiter );
				}
				$hierarchy = $delimiter . $cat_parents . '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>' . $delimiter;
			}
			// Note: Titles are forced for attachments; the
			// filename will be used if none is specified
			$current_location = get_the_title();  
		}
		// Define Taxonomy Crumbs for Custom Post Types
		elseif ( is_singular( get_post_type() ) && !is_singular( 'post' ) && !is_page() && !is_attachment() ) {
			$post_type_object = get_post_type_object( get_post_type() );
			$post_type_name = $post_type_object->labels->name;
			$post_type_slug = $post_type_object->name;
			$taxonomies = get_object_taxonomies( get_post_type() );
			$taxonomy = ( !empty( $taxonomies ) ? $taxonomies[0] : false );
			$terms = ( $taxonomy ? get_the_term_list( $post->ID, $taxonomy ) : false );
			$hierarchy = $delimiter . '<a href="' . get_post_type_archive_link( $post_type_slug ) . '">' . $post_type_name . '</a>';
			$hierarchy .= ( $terms ? $delimiter . $terms . $delimiter : $delimiter );
			$current_location = get_the_title();
		}
		// Define Current Location for Parent Pages
		elseif ( !is_front_page() && is_page() && !$post->post_parent ) { 
			$hierarchy = $delimiter;
			// Note: get_the_title() is filtered to output a
			// default title if none is specified
			$current_location = get_the_title();
		}
		// Define Parent Page Hierarchy Crumbs for Child Pages
		elseif ( !is_front_page() && is_page() && $post->post_parent ) { 
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ( $parent_id ) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse( $breadcrumbs );
			foreach ( $breadcrumbs as $crumb ) {
				$hierarchy .= $delimiter . $crumb;
			}
			$hierarchy = $hierarchy . $delimiter;
			// Note: get_the_title() is filtered to output a
			// default title if none is specified
			$current_location = get_the_title(); 
		}
		// Define current location for Search Results page
		elseif ( is_search() ) {
			$hierarchy = $delimiter;
			$current_location = get_search_query();
		}
		// Define current location for Tag Archives
		elseif ( is_tag() ) {
			$hierarchy = $delimiter;
			$current_location = single_tag_title( '' , FALSE );  
		} 
		// Define current location for Custom Taxonomy Archives
		elseif ( is_tax() ) {
			$post_type_object = get_post_type_object( get_post_type() );
			$post_type_name = $post_type_object->labels->name;
			$post_type_slug = $post_type_object->name;
			$custom_tax = $wp_query->query_vars['taxonomy'];
			$custom_tax_object = get_taxonomy( $custom_tax );
			$hierarchy = $delimiter . '<a href="' . get_post_type_archive_link( $post_type_slug ) . '">' . $post_type_name . '</a>';
			$hierarchy .= $delimiter;
			$current_location = single_term_title( '', false ); 
		}
		// Define current location for Author Archives
		elseif ( is_author() ) { 
			$hierarchy = $delimiter;
			$current_location = get_the_author_meta( 'display_name', get_query_var( 'author' ) ); 
		}
		// Define current location for 404 Error page
		elseif ( is_404() ) { 
			$hierarchy = $delimiter;
			$current_location = __( 'Error 404','fastfood' ) . ' - ' . __( 'Page not found','fastfood' );
		}
		// Define current location for Post Format Archives
		elseif ( get_post_format() && !is_home() ) { 
			$hierarchy = $delimiter;
			$current_location = get_post_format_string( get_post_format() );
		}
		// Define current location for Custom Post Type Archives
		elseif ( is_post_type_archive( get_post_type() ) ) {
			$hierarchy = $delimiter;
			$post_type_object = get_post_type_object( get_post_type() );
			$post_type_name = $post_type_object->labels->name;
			$current_location = $post_type_name;
		}
		if ( is_front_page() && ( 'page' == get_option( 'show_on_front' ) ) ) { 
			$hierarchy = $delimiter;
			$current_location = get_the_title();
		}
		if ( is_front_page() && !( 'page' == get_option( 'show_on_front' ) ) ) { 
			$hierarchy = $delimiter;
			$current_location = $blogname;
		}
		if ( fastfood_is_allcat() ) {
			$hierarchy = $delimiter;
			$current_location = __( 'All Categories','fastfood' );
		}

		// Define pagination for paged Archive pages
		if ( get_query_var('paged') && !function_exists( 'wp_paginate' ) ) {
			$crumb_pagination = ' (Page ' . get_query_var( 'paged' ) . ')';
		}

		// Define pagination for Paged Posts and Pages
		if ( get_query_var('page') ) {
			$crumb_pagination = ' (Page ' . get_query_var( 'page' ) . ') ';
		}

		// Build the Current Location Link markup
		$current_location_link = $current_before . '<i class="el-icon-placeholder"></i>'.$current_location . $crumb_pagination . $current_after;
		// Define breadcrumb pagination

		// Build the resulting Breadcrumbs
		$breadcrumb = $container_before . $container_crumb_open . $base_link . $hierarchy . $current_location_link . $container_crumb_close . $container_after;

		// Output the result
		return apply_filters( 'fastfood_breadcrumb', $breadcrumb, $base_link, $container_before, $container_after, $container_crumb_open, $container_crumb_close, $delimiter, $homename, $blogname, $current_before, $current_after );

	}

}

new Fastfood_Breadcrumb;