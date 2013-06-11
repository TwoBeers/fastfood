<?php
/**
 * breadcrumb.php
 *
 * The breadcrumb class.
 * Supports Breadcrumb NavXT and Yoast Breadcrumbs plugins.
 *
 * @package fastfood
 * @since 0.34
 */


class fastfood_Breadcrumb {

	function __construct() {

		add_action( 'fastfood_hook_breadcrumb_navigation', array( $this, 'display' ) );

	}


	function display(){

		if ( function_exists( 'bcn_display' ) ) { // Breadcrumb NavXT

			echo '<div id="navxt-crumbs" class="breadcrumb-navigation">'; bcn_display_list(); echo '</div>';

		} elseif ( function_exists( 'yoast_breadcrumb' ) ) { // Yoast Breadcrumbs

			echo '<div id="yoast-crumbs" class="breadcrumb-navigation">'; yoast_breadcrumb(); echo '</div>';

		} else {

			$output = apply_filters( 'fastfood_filter_breadcrumb', '' );

			if ( empty ( $output ) && fastfood_get_opt( 'fastfood_breadcrumb' ) )
				$output = '<div id="crumbs" class="breadcrumb-navigation">' . $this->the_breadcrumb() . '</div>';

			echo $output;

		}

	}


	// Copied and adapted from WP source
	function get_category_with_parents( $id, $separator = '%%FF_SEP%%' ){
		global $wp_query;

		$chain = '';

		$parent = &get_category( $id );
		if ( is_wp_error( $parent ) )
			return $parent;

		$name = $parent->cat_name . ' (' . $wp_query->found_posts . ')';

		if ( $parent->parent && ( $parent->parent != $parent->term_id ) )
			$chain .= get_category_parents( $parent->parent, true, $separator, FALSE );

		$chain .= '<span class="crumb-cat">' . $name . '</span>';

		return $chain;

	}


	function the_breadcrumb() {
		global $wp_query, $post;

		$opt 						= array();
		$opt['home'] 				= 'Home';
		$opt['sep'] 				= '';
		$opt['archive_prefix'] 		=  __( 'Archives for %s', 'fastfood' );
		$opt['search_prefix'] 		=  __( 'Search for "%s"', 'fastfood' );
		$opt['item_tag']			= 'li';
		$opt['item_class']			= '';
		$opt['wrap_tag']			= 'ul';
		$opt['wrap_class']			= '';
		$opt['class_first']			= 'first';
		$opt['class_last']			= 'last';
		$opt['nofollow']			= ' rel="nofollow" ';


		$sep = '%%FF_SEP%%';

		if ( get_option( 'show_on_front' ) == "page" ) {

			$homelink = '<a class="crumb-home"' . $opt['nofollow'] . 'href="' . esc_url( get_permalink( get_option( 'page_on_front' ) ) ) . '">&nbsp;</a>';
			$bloglink = $homelink . $sep . '<a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';

		} else {

			$homelink = '<a class="crumb-home"' . $opt['nofollow'] . 'href="' . esc_url( home_url() ) . '">&nbsp;</a>';
			$bloglink = $homelink;

		}

		if ( fastfood_is_allcat() ) {

			$output = $homelink . $sep . '<span>' . __( 'All Categories', 'fastfood' ) . '</span>';

		} elseif ( ( get_option( 'show_on_front' ) == "page" && is_front_page() ) || ( get_option( 'show_on_front' ) == "posts" && is_home() ) ) {

			$output = $homelink . $sep . '<span>' . $opt['home'] . '</span>';

		} elseif ( get_option( 'show_on_front' ) == "page" && is_home() ) {

			$output = $homelink . $sep . '<span>' . get_the_title( get_option( 'page_for_posts' ) ) . '</span>';

		} elseif ( !is_page() ) {

			$output = $bloglink . $sep;

			if ( is_single() && has_category() ) {
				$cats = get_the_category();
				$cat = $cats[0];
				if ( is_object( $cat ) ) {
					if ( $cat->parent != 0 ) {
						$output .= get_category_parents( $cat->term_id, true, $sep );
					} else {
						$output .= '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . $cat->name . '</a>' . $sep;
					}
				}
			}

			if ( is_category() ) {

				$cat = intval( get_query_var( 'cat' ) );
				$output .= $this->get_category_with_parents( $cat, $sep );

			} elseif ( is_tag() ) {

				$title = single_term_title( '', false );
				$output .= '<span class="crumb-tag">' . sprintf( $opt['archive_prefix'], $title ) . ' (' . $wp_query->found_posts . ')</span>';

			} elseif ( is_date() ) {

				if ( is_day() ) {
					$title = get_the_date();
				} else if ( is_month() ) {
					$title = single_month_title( ' ', false );
				} else if ( is_year() ) {
					$title = get_query_var( 'year' );
				}
				$output .= '<span class="crumb-date">' . sprintf( $opt['archive_prefix'], $title ) . ' (' . $wp_query->found_posts . ')</span>';

			} elseif ( is_author() ) {

				$author = get_queried_object();
				$title = $author->display_name;
				$output .= '<span class="crumb-auth">' . sprintf( $opt['archive_prefix'], $title ) . ' (' . $wp_query->found_posts . ')</span>';

			} elseif ( is_404() ) {

				$output .= '<span class="crumb-error">' . __( 'Page not found', 'fastfood' ) . '</span>';

			} elseif ( is_search() ) {

				$output .= '<span class="crumb-search">' . sprintf( $opt['search_prefix'], stripslashes( strip_tags(get_search_query() ) ) ) . ' (' . $wp_query->found_posts . ')</span>';

			} elseif ( is_attachment() ) {

				if ( $post->post_parent ) {
					$output .= '<a href="' . esc_url( get_permalink( $post->post_parent ) ) . '">' . get_the_title( $post->post_parent ) . '</a>' . $sep;
				}
				$output .= '<span>' . get_the_title() . '</span>';

			} else if ( is_tax() ) {

				$taxonomy 	= get_taxonomy ( get_query_var( 'taxonomy' ) );
				$term 		= get_query_var( 'term' );
				$output .= '<span>' . $taxonomy->label . ': ' . $term . ' (' . $wp_query->found_posts . ')</span>';

			} else {

				if ( get_query_var( 'page' ) ) {
					$output .= '<a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a>' . $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'page' ) ) . '</span>';
				} else {
					$output .= get_the_title() ? '<span>' . get_the_title() . '</span>' : '<span>' . sprintf ( __( 'post #%s', 'fastfood' ), get_the_ID() ) . '</span>';
				}

			}

		} else {

			$post = $wp_query->get_queried_object();

			if ( 0 == $post->post_parent ) {

				if ( get_query_var( 'page' ) ) {
					$output = $homelink . $sep . '<a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a>' . $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'page' ) ) . '</span>';
				} else {
					$output = $homelink . $sep . '<span>' . get_the_title() . '</span>';
				}

			} else {

				if ( isset( $post->ancestors ) ) {
					if ( is_array( $post->ancestors ) )
						$ancestors = array_values( $post->ancestors );
					else
						$ancestors = array( $post->ancestors );
				} else {
					$ancestors = array( $post->post_parent );
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse( $ancestors );

				// Add the current Page to the ancestors list (as we need it's title too)
				$ancestors[] = $post->ID;

				$links = array();
				foreach ( $ancestors as $ancestor ) {
					$tmp  = array();
					$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
					$tmp['url'] 	= esc_url( get_permalink( $ancestor ) );
					$tmp['cur'] = false;
					if ( $ancestor == $post->ID ) {
						$tmp['cur'] = true;
					}
					$links[] = $tmp;
				}

				$output = $homelink;
				foreach ( $links as $link ) {
					$output .= ' ' . $sep;
					if ( !$link['cur'] ) {
						$output .= '<a href="' . $link['url'] . '">' . $link['title'] . '</a>';
					} else {
						if ( get_query_var( 'page' ) ) {
							$output .= '<a href="' . $link['url'] . '">' . $link['title'] . '</a>' . $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'page' ) ) . '</span>';
						} else {
							$output .= '<span>' . $link['title'] . '</span>';
						}
					}
				}

			}

		}

		if ( get_query_var( 'paged' ) ) {
			$output .= $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'paged' ) ) . '</span>';
		}

		$output_items = explode( $sep, $output ) ;

		$class					= array();
		$class['wrap']			= ( $opt['wrap_class'] ) ? ' class="' . $opt['wrap_class'] . '"' : '';
		$class['item']			= ( $opt['item_class'] ) ? ' class="' . $opt['item_class'] . '"' : '';
		$opt['item_class']		= ( $opt['item_class'] ) ? ' ' . $opt['item_class'] : '';
		$class['item_first']	= ( $opt['item_class'] || $opt['class_first'] ) ? ' class="' . $opt['class_first'] . $opt['item_class'] . '"' : '';
		$class['item_last']		= ( $opt['item_class'] || $opt['class_last'] ) ? ' class="' . $opt['class_last'] . $opt['item_class'] . '"' : '';

		if ( count( $output_items ) == 0 ) return;
		if ( count( $output_items ) == 1 ) $output_items[0] = '<' . $opt['item_tag'] . ' class="' . $opt['last'] . '">' . $output_items[0] . '</' . $opt['item_tag'] . '>';
		if ( count( $output_items ) > 1 ) {
			foreach ( $output_items as $key => $val ) {
				if ( $key == ( count( $output_items )-1 ) ) {
					$output_items[$key] = '<' . $opt['item_tag'] . $class['item_last'] . '>' . $val . '</' . $opt['item_tag'] . '>';
				} elseif ( $key == 0 ) {
					$output_items[$key] = '<' . $opt['item_tag'] . $class['item_first'] . '>' . $val . '</' . $opt['item_tag'] . '>';
				} else {
					$output_items[$key] = '<' . $opt['item_tag'] . $class['item'] . '>' . $val . '</' . $opt['item_tag'] . '>';
				}
			}
		}

		$output = '<' . $opt['wrap_tag'] . $class['wrap'] . '>' . implode( $opt['sep'], $output_items ) . '</' . $opt['wrap_tag'] . '>';

		return $output;

	}

}

new fastfood_Breadcrumb;