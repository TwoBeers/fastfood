<?php
/**
 * Fastfood Featured Content
 *
 * This module allows you to define a subset of posts to be displayed
 * in the theme's Featured Content area.
 */
class Fastfood_Featured_Content {

	/**
	 * The maximum number of posts a Featured Content area can contain.
	 *
	 * @since Fastfood 0.37
	 *
	 * @static
	 * @access public
	 * @var int
	 */
	public static $max_posts = 5;

	/**
	 * Instantiate.
	 *
	 * All custom functionality will be hooked into the "init" action.
	 *
	 * @static
	 * @access public
	 * @since Fastfood 0.37
	 */
	public static function setup() {
		add_action( 'init'				, array( __CLASS__, 'init'              ), 30 );
		add_action( 'after_setup_theme'	, array( __CLASS__, 'add_theme_support' )     );
	}

	/**
	 * Add theme support
	 */
	public static function add_theme_support() {
		// Add support for featured content.
		add_theme_support( 'featured-content', array(
			'featured_content_filter'	=> 'fastfood_get_featured_posts',
			'max_posts'					=> 5,
		) );
	}

	/**
	 * Conditionally hook into WordPress.
	 *
	 * @static
	 * @access public
	 * @since Fastfood 0.37
	 */
	public static function init() {
		$theme_support		= get_theme_support( 'featured-content' );
		self::$max_posts	= absint( $theme_support[0]['max_posts'] );
		$filter				= $theme_support[0]['featured_content_filter'];

		add_action( $filter									, array( __CLASS__, 'get_featured_posts' )    );
		add_action( 'pre_get_posts'							, array( __CLASS__, 'pre_get_posts'      )    );
		add_action( 'fastfood_hook_breadcrumb_navigation'	, array( __CLASS__, 'featured_content'   ), 9 );
	}

	/**
	 * Get featured posts.
	 *
	 * @static
	 * @access public
	 * @since Fastfood 0.37
	 *
	 * @return array Array of featured posts.
	 */
	public static function get_featured_posts( $featured_posts ) {

		if ( $featured_posts ) return $featured_posts;

		$post_ids = array_slice( get_option( 'sticky_posts', array() ), 0, self::$max_posts );

		// No need to query if there is are no featured posts.
		if ( empty( $post_ids ) ) {
			return array();
		}

		$featured_posts = get_posts( array(
			'include'			=> $post_ids,
			'posts_per_page'	=> count( $post_ids ),
		) );

		return $featured_posts;
	}

	/**
	 * Exclude featured posts from the home page blog query.
	 *
	 * Filter the home page posts, and remove any featured post ID's from it.
	 * Hooked onto the 'pre_get_posts' action, this changes the parameters of
	 * the query before it gets any posts.
	 *
	 * @static
	 * @access public
	 * @since Fastfood 0.37
	 *
	 * @param WP_Query $query WP_Query object.
	 * @return WP_Query Possibly-modified WP_Query.
	 */
	public static function pre_get_posts( $query ) {

		// Bail if not home or not main query.
		if ( ! $query->is_home() || ! $query->is_main_query() ) {
			return;
		}

		$query->set( 'ignore_sticky_posts', 1 );
	}

	/**
	 * Display the featured content
	 */
	public static function featured_content() {
		global $post;

		$featured_posts = apply_filters( 'fastfood_get_featured_posts', array() );

		if ( ! (bool) $featured_posts ) return;

		?>

			<div id="featured-content" class="featured-content">
				<div class="featured-content-inner">
				<?php
					/**
					 * Fires before the Twenty Fourteen featured content.
					 *
					 * @since Twenty Fourteen 1.0
					 */
					do_action( 'fastfood_featured_posts_before' );

					foreach ( (array) $featured_posts as $order => $post ) :
						setup_postdata( $post );

						 // Include the featured content template.
						get_template_part( 'post', 'featured' );
					endforeach;

					/**
					 * Fires after the Twenty Fourteen featured content.
					 *
					 * @since Twenty Fourteen 1.0
					 */
					do_action( 'fastfood_featured_posts_after' );

					wp_reset_postdata();
				?>
				</div><!-- .featured-content-inner -->
			</div><!-- #featured-content .featured-content -->

		<?php

	}
} // Fastfood_Featured_Content

Fastfood_Featured_Content::setup();

