<?php
/**
 * navigation.php
 *
 * Every navigation element ( menu, next/prev link, etc )
 *
 * @package fastfood
 * @since fastfood 0.37
 */


add_action( 'fastfood_hook_comments_list_before', 'fastfood_navigate_comments'                     );
add_action( 'fastfood_hook_comments_list_after' , 'fastfood_navigate_comments'                     );
add_action( 'fastfood_hook_loop_after'          , 'fastfood_navigate_archives'                     );
add_action( 'fastfood_hook_post_content_after'  , 'fastfood_link_pages'                            );
add_action( 'fastfood_hook_entry_top'           , 'fastfood_single_nav'                            );

add_filter( 'previous_posts_link_attributes'    , 'fastfood_previous_posts_link_attributes', 10, 1 );
add_filter( 'next_posts_link_attributes'        , 'fastfood_next_posts_link_attributes'    , 10, 1 );


class Fastfood_Nav_Menus {

	/**
	 * Constructor.
	 */
	function __construct() {

		add_filter( 'wp_nav_menu_args'		, array( $this, 'add_levels_class'           )        );
		add_filter( 'wp_nav_menu'			, array( $this, 'add_depth_class'            ), 10, 2 );
		add_filter( 'nav_menu_css_class'	, array( $this, 'get_menu_depth'             ), 10, 4 );
		add_filter( 'wp_nav_menu_items'		, array( __CLASS__, 'menu_primary_home_link' ), 10, 2 );

		$this->depth = 0;

	}


	/**
	 * Add a level-related class to each menu
	 * 
	 * @since Fastfood 0.37
	 */
	function add_levels_class( $args ) {

		$levels_str = ( 1 === $args['depth'] ) ? ' single-level' : ' multiple-levels';
		$args['menu_class'] = $args['menu_class'] . $levels_str;

		return $args;

	}


	/**
	 * Save the max depth of each menu
	 * 
	 * @since Fastfood 0.37
	 */
	function get_menu_depth( $classes, $item, $args, $depth = 0 ) {

		$this->depth = max( $this->depth, $depth);

		return $classes;

	}


	/**
	 * Add a depth-related class to each menu
	 * 
	 * @since Fastfood 0.37
	 */
	function add_depth_class( $string, $args ) {

		++$this->depth;

		if ( preg_match( '/<ul[^>]+>/i', $args->items_wrap, $result ) ) {
			$pattern     = sprintf( $result[0], '(.*?)', '(.*?)' );
			$replacement = sprintf( $result[0], '$1', '$2 has-depth-' . $this->depth );
			$string      = preg_replace( '/' . $pattern . '/', $replacement, $string, 1 );
		}

		$this->depth = 0;

		return $string;

	}


	// display the main menu
	public static function menu_primary () {

		if ( FastfoodOptions::get_opt('fastfood_primary_menu' ) ) {
			?>

				<?php fastfood_hook_menu_primary_before(); ?>

				<div id="menu-primary-container" class="menu-primary-container menu-container">

					<?php fastfood_hook_menu_primary_top(); ?>

					<?php
						wp_nav_menu( array(
							'container'			=> false,
							'menu_id'			=> 'menu-primary',
							'menu_class'		=> 'nav-menu',
							'fallback_cb'		=> 'Fastfood_Nav_Menus::menu_primary_fallback',
							'theme_location'	=> 'primary',
						) );
					?>

					<?php fastfood_hook_menu_primary_bottom(); ?>

				</div>

				<?php fastfood_hook_menu_primary_after(); ?>

			<?php
		}

	}


	/**
	 * The first secondary menu
	 * 
	 * @since Fastfood 0.37
	 */
	public static function menu_secondary_first() {

		if ( has_nav_menu( 'secondary1' ) ) {
			?>

				<?php fastfood_hook_menu_secondary_first_before(); ?>

				<div id="menu-secondary-first-container" class="menu-secondary-container menu-container">

					<?php fastfood_hook_menu_secondary_first_top(); ?>

					<?php
						wp_nav_menu( array(
							'container'			=> false,
							'menu_id'			=> 'menu-secondary-first',
							'menu_class'		=> 'nav-menu secondary',
							'fallback_cb'		=> false,
							'theme_location'	=> 'secondary1',
							'depth'				=> 1,
						) );
					?>

					<?php fastfood_hook_menu_secondary_first_bottom(); ?>

				</div>

				<?php fastfood_hook_menu_secondary_first_after(); ?>

			<?php
		}

	}


	/**
	 * The second secondary menu
	 * 
	 * @since Fastfood 0.37
	 */
	public static function menu_secondary_second() {

		if ( has_nav_menu( 'secondary2' ) ) {
			?>

				<?php fastfood_hook_menu_secondary_second_before(); ?>

				<div id="menu-secondary-second-container" class="menu-secondary-container menu-container">

					<?php fastfood_hook_menu_secondary_second_top(); ?>

					<?php
						wp_nav_menu( array(
							'container'			=> false,
							'menu_id'			=> 'menu-secondary-second',
							'menu_class'		=> 'nav-menu secondary',
							'fallback_cb'		=> false,
							'theme_location'	=> 'secondary2',
							'depth'				=> 1,
						) );
					?>

					<?php fastfood_hook_menu_secondary_second_bottom(); ?>

				</div>

				<?php fastfood_hook_menu_secondary_second_after(); ?>

			<?php
		}

	}


	// Pages Menu
	public static function menu_primary_fallback() {

		?>

			<ul id="menu-primary" class="nav-menu multiple-levels">

				<?php echo self::menu_primary_home_link( $items = '', $args = 'theme_location=primary' ); ?>
				<?php wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted ?>

			</ul>

		<?php

	}


	//add "Home" link
	public static function menu_primary_home_link( $items = '', $args = null ) {

		$defaults = array(
			'theme_location'	=> 'undefined',
			'before'			=> '',
			'after'				=> '',
			'link_before'		=> '',
			'link_after'		=> '',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ( $args['theme_location'] === 'primary' ) && ( 'posts' == get_option( 'show_on_front' ) ) ) {
			if ( is_front_page() || is_single() )
				$class = ' current_page_item';
			else
				$class = '';

			$home =
					'<li class="menu-item navhome' . $class . '">' .
					$args['before'] .
					'<a href="' . home_url( '/' ) . '" title="' . esc_attr__( 'Home', 'fastfood' ) . '">' .
					$args['link_before'] . __( 'Home', 'fastfood' ) . $args['link_after'] .
					'</a>' .
					$args['after'] .
					'</li>';

			$items = $home . $items;
		}

		return $items;

	}


}

new Fastfood_Nav_Menus;


// comments navigation
function fastfood_navigate_comments(){
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		?>

		<div class="navigation_links navigate_comments">
			<?php
				if ( ! apply_filters( 'fastfood_filter_navigation_comments', false ) )
					echo str_replace( "\n", "", paginate_comments_links( array( 'prev_text' => '&laquo;', 'next_text' => '&raquo;', 'echo' => 0 ) ) );
			?>
		</div>

		<?php
	}

}


// archives pages navigation
function fastfood_navigate_archives() {
	global $paged, $wp_query;

	if ( !$paged ) $paged = 1;

?>
	<div class="navigation_links navigate_archives">
	<?php
	if ( !apply_filters( 'fastfood_filter_navigation_archives', false ) ) {
				next_posts_link( '<i class="el-icon-chevron-left"></i>' );
				printf( '<span class="pages">' . __( 'page %1$s of %2$s','fastfood' ) . '</span>', $paged, $wp_query->max_num_pages );
				previous_posts_link( '<i class="el-icon-chevron-right"></i>' );
	}
	?>
	</div>
<?php

}


// displays page-links for paginated posts
function fastfood_link_pages() {

	$args = array(
		'before'           => '<div class="nav-pages">' . __( 'Pages', 'fastfood' ) . '&nbsp;',
		'after'            => '</div>',
		'link_before'      => '<span>',
		'link_after'       => '</span>',
		'separator'        => '',
	);

	if ( is_single() || !FastfoodOptions::get_opt( 'fastfood_postexcerpt' ) ) 
		wp_link_pages( $args );

}


//Display navigation to next/previous post when applicable
function fastfood_single_nav() {
	global $post;

	if ( !is_single() ) return;

	$next = get_next_post();
	$prev = get_previous_post();
	$next_title = get_the_title( $next ) ? get_the_title( $next ) : __( 'Next Post', 'fastfood' );
	$prev_title = get_the_title( $prev ) ? get_the_title( $prev ) : __( 'Previous Post', 'fastfood' );

	$output = '';

	if ( $prev ) {
		$output .= fastfood_build_link( array(
			'href'		=> get_permalink( $prev ),
			'text'		=> '<span>' . $prev_title . '</span>' . fastfood_get_the_thumb( array(
				'id'		=> $prev->ID,
				'size'		=> array( 32, 32 ),
				'class'		=> 'tb-thumb-format',
			) ),
			'title'		=> esc_attr( strip_tags( __( 'Previous Post', 'fastfood' ) . ': ' . $prev_title ) ),
			'class'		=> 'nav-previous el-icon-chevron-left',
			'rel'		=> 'prev',
		) );
	}

	if ( $next ) {
		$output .= fastfood_build_link( array(
			'href'		=> get_permalink( $next ),
			'text'		=> '<span>' . $next_title . '</span>' . fastfood_get_the_thumb( array(
				'id'		=> $next->ID,
				'size'		=> array( 32, 32 ),
				'class'		=> 'tb-thumb-format',
			) ),
			'title'		=> esc_attr( strip_tags( __( 'Next Post', 'fastfood' ) . ': ' . $next_title ) ),
			'class'		=> 'nav-next el-icon-chevron-right',
			'rel'		=> 'next',
		) );
	}

	if ( !$output ) return;
	?>

		<div class="nav-single">
			<?php echo $output; ?>
		</div><!-- #nav-single -->

	<?php

}


// add a title to previous posts link
function fastfood_previous_posts_link_attributes( $attr ) {

	$attr = $attr . ' title="' . esc_attr( __( 'Newer Posts', 'fastfood' ) ) . '" ';
	return $attr;

}


// add a title to next posts link
function fastfood_next_posts_link_attributes( $attr ) {

	$attr = $attr . ' title="' . esc_attr( __( 'Older Posts', 'fastfood' ) ) . '" ';
	return $attr;

}
