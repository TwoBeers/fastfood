<?php
/**
 * functions.php
 *
 * functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Fastfood
 * @since 0.15
 */


/* Custom actions - WP hooks */

add_action( 'after_setup_theme'						, 'fastfood_setup' );
add_action( 'wp_enqueue_scripts'					, 'fastfood_stylesheet' );
add_action( 'wp_enqueue_scripts'					, 'fastfood_scripts' );
add_action( 'wp_footer'								, 'fastfood_scripts_l10n', 9 );
add_action( 'init'									, 'fastfood_post_expander_activate' );
add_action( 'init'									, 'fastfood_activate_get_comments_page' );
add_action( 'template_redirect'						, 'fastfood_allcat', 99 );
add_action( 'comment_form_comments_closed'			, 'fastfood_comments_closed' );
add_action( 'pre_get_posts'							, 'fastfood_exclude_format_from_blog' );
add_action( 'wp_head'								, 'fastfood_render_title' );


/* Custom actions - theme hooks */

add_action( 'fastfood_hook_body_top'				, 'fastfood_body_class_script' );
add_action( 'fastfood_hook_content_top'				, 'fastfood_search_reminder' );
add_action( 'fastfood_hook_post_content_after'		, 'fastfood_always_more' );
add_action( 'fastfood_hook_comments_top'			, 'fastfood_comments_header' );
add_action( 'fastfood_hook_footer_after'			, 'fastfood_print_preview_buttons', 99 );


/* Custom filters - WP hooks */

add_filter( 'use_default_gallery_style'				, '__return_false' );
add_filter( 'get_comment_author_link'				, 'fastfood_add_quoted_on' );
add_filter( 'the_title'								, 'fastfood_title_tags_filter', 10, 2 );
add_filter( 'excerpt_length'						, 'fastfood_excerpt_length' );
add_filter( 'excerpt_mblength'						, 'fastfood_excerpt_length' );
add_filter( 'excerpt_more'							, 'fastfood_excerpt_more' );
add_filter( 'the_content_more_link'					, 'fastfood_more_link', 10, 2 );
add_filter( 'body_class'							, 'fastfood_body_classes' );
add_filter( 'comment_form_defaults'					, 'fastfood_comment_form_defaults' );
add_filter( 'wp_list_categories'					, 'fastfood_wrap_categories_count' );
add_filter( 'comment_form_logged_in'				, 'fastfood_add_avatar_to_logged_in', 10, 3 );
add_filter( 'page_css_class'						, 'fastfood_add_parent_class', 10, 4 );
add_filter( 'wp_nav_menu_objects'					, 'fastfood_add_menu_parent_class' );
add_filter( 'get_search_form'						, 'fastfood_search_form' );
add_filter( 'comment_text'							, 'fastfood_wrap_comment_text',999 );
add_filter( 'comment_reply_link'					, 'fastfood_comment_reply_link' );


/* Custom filters - Misc hooks */

add_filter( 'tb_chat_load_style'					, '__return_false' );


/* theme variables globally retrieved in php files */
global $fastfood_opt;
global $fastfood_is_mobile;
$fastfood_is_mobile = false;


/**
 * theme infos
 */
function fastfood_get_info( $field ) {
	static $infos;

	if ( !isset( $infos ) ) {
		$infos['current_theme'] = wp_get_theme();
		$infos['version'] = $infos['current_theme']? $infos['current_theme']['Version'] : '';
		$infos['required_wp_version'] = '4.0';
	}

	return isset( $infos[$field] ) ? $infos[$field] : false;

}


/* load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes) */

require_once( 'lib/options.php' );
require_once( 'lib/back-compat.php' );
require_once( 'lib/builder.php' );
require_once( 'lib/formats-functions.php' );
require_once( 'lib/widgets.php' );
require_once( 'mobile/core-mobile.php' );
require_once( 'lib/breadcrumb.php' );
require_once( 'lib/hooks.php' );
require_once( 'lib/quickbar.php' );
require_once( 'lib/custom-background.php' );
require_once( 'lib/custom-header.php' );
require_once( 'lib/header-image-slider.php' );
require_once( 'lib/comment-reply.php' );
require_once( 'lib/admin.php' );
require_once( 'lib/plug-n-play.php' );
require_once( 'lib/customizer.php' );
require_once( 'lib/sanitize.php' );
require_once( 'lib/dynamic-css.php' );
require_once( 'lib/featured-content.php' );
require_once( 'lib/navigation.php' );
require_once( 'lib/sidebars.php' );


/**
 * Conditional tag
 * 
 * Check if in mobile view
 * 
 * @return bool
 */
function fastfood_is_mobile() { // mobile
	global $fastfood_is_mobile;

	return $fastfood_is_mobile;

}


/**
 * Conditional tag
 * 
 * Check if in print preview view
 * 
 * @return bool
 */
function fastfood_is_printpreview() { // print preview
	static $is_printpreview;

	if ( !isset( $is_printpreview ) ) {
		$is_printpreview = isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ? true : false;
	}

	return $is_printpreview;

}


/**
 * Conditional tag
 * 
 * Check if is "all category" page
 * 
 * @return bool
 */
function fastfood_is_allcat() {
	static $is_allcat;

	if ( !isset( $is_allcat ) ) {
		$is_allcat = isset( $_GET['allcat'] ) && md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ? true : false;
	}

	return $is_allcat;

}


/**
 * show all categories list (redirect to allcat.php if allcat=y)
 */
function fastfood_allcat () {

	if( fastfood_is_allcat() ) {
		get_template_part( 'allcat' );
		exit;
	}

}


/**
 * Add stylesheets
 */
if ( !function_exists( 'fastfood_stylesheet' ) ) {
	function fastfood_stylesheet(){

		if (   is_admin()
			|| fastfood_is_mobile()
		)
			return;

		if ( fastfood_is_printpreview() ) { //print preview

			wp_enqueue_style(
				'fastfood-preview-print',
				sprintf( '%1$s/css/print.css' , get_template_directory_uri() ),
				false,
				fastfood_get_info( 'version' ),
				'screen'
			);

			wp_enqueue_style(
				'fastfood-preview-general',
				sprintf( '%1$s/css/print-preview.css' , get_template_directory_uri() ),
				false,
				fastfood_get_info( 'version' ),
				'screen'
			);

		} else { //normal view

			if ( FastfoodOptions::get_opt( 'fastfood_gallery_preview' ) )
				wp_enqueue_style(
					'thickbox'
				);

			wp_enqueue_style(
				'fastfood',
				get_stylesheet_uri(),
				false,
				fastfood_get_info( 'version' ),
				'screen'
			);

			wp_enqueue_style(
				'elusive-webfont',
				sprintf( '%1$s/elusive-iconfont/css/elusive-webfont.css' , get_template_directory_uri() )
			);

			// Load the Internet Explorer specific stylesheet.
			wp_enqueue_style(
				'fastfood-ie',
				sprintf( '%1$s/css/ie.css' , get_template_directory_uri() ),
				array( 'fastfood' ),
				fastfood_get_info( 'version' ),
				'screen'
			);
			wp_style_add_data(
				'fastfood-ie',
				'conditional',
				'lte IE 8'
			);

			if ( FastfoodOptions::get_opt( 'fastfood_responsive_layout' ) )
				wp_enqueue_style(
					'fastfood-responsive',
					sprintf( '%1$s/css/responsive.css' , get_template_directory_uri() ),
					false,
					fastfood_get_info( 'version' ),
					'screen and (max-width: ' . ( absint( FastfoodOptions::get_opt( 'fastfood_body_width' ) ) + 152 ) . 'px)'
				);

			//google font
			if ( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) ) {
				$gwf_family = 'family=' . urlencode( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) );
				$gwf_subset = FastfoodOptions::get_opt( 'fastfood_google_font_subset' )? '&subset=' . urlencode( str_replace( array( ' ', '"' ), '', FastfoodOptions::get_opt( 'fastfood_google_font_subset' ) ) ) : '';
				$gwf_url = '//fonts.googleapis.com/css?' . $gwf_family . $gwf_subset;
				wp_enqueue_style(
					'fastfood-google-fonts',
					$gwf_url
				);
			}

		}

		//print style
		wp_enqueue_style(
			'fastfood-print',
			sprintf( '%1$s/css/print.css' , get_template_directory_uri() ),
			false,
			fastfood_get_info( 'version' ),
			'print'
		);

	}
}


/**
 * get js modules
 * 
 * @return array Array of js modules.
 */
function fastfood_get_js_modules() {

	$modules = array();

	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_main_menu' ) )				$modules[] = 'main_menu';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_navigation_buttons' ) )	$modules[] = 'navigation_buttons';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_quickbar_panels' ) )		$modules[] = 'quickbar_panels';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_entry_meta' ) )			$modules[] = 'entry_meta';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_smooth_scroll' ) )			$modules[] = 'smooth_scroll';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_captions' ) )				$modules[] = 'captions';

	if ( FastfoodOptions::get_opt( 'fastfood_tinynav' ) )								$modules[] = 'tinynav';
	if ( FastfoodOptions::get_opt( 'fastfood_post_expand' ) )							$modules[] = 'post_expander';
	if ( FastfoodOptions::get_opt( 'fastfood_gallery_preview' ) )						$modules[] = 'thickbox';
	if ( FastfoodOptions::get_opt( 'fastfood_quotethis' ) )								$modules[] = 'quote_this';
	if ( FastfoodOptions::get_opt( 'fastfood_sticky_menu' ) )							$modules[] = 'sticky_menu';
	if ( FastfoodOptions::get_opt( 'fastfood_comments_navigation' ) )					$modules[] = 'get_comments';

	return apply_filters( 'fastfood_filter_js_modules', $modules );

}


/**
 * add scripts
 */
if ( !function_exists( 'fastfood_scripts' ) ) {
	function fastfood_scripts(){

		if (   is_admin()
			|| fastfood_is_mobile()
			|| fastfood_is_printpreview()
			|| !FastfoodOptions::get_opt( 'fastfood_jsani' )
		)
			return; //no scripts in admin, print preview, mobile view

		//tinynav script
		if ( FastfoodOptions::get_opt( 'fastfood_tinynav' ) )
			wp_enqueue_script(
				'tinynav',
				fastfood_get_minified( '%1$s/js/tinynav/tinynav%2$s.js' ),
				array( 'jquery' ),
				fastfood_get_info( 'version' ),
				true
			);

		$deps = array(
			'jquery',
			'jquery-effects-core',
			'hoverIntent',
		);
		if ( FastfoodOptions::get_opt( 'fastfood_gallery_preview' ) )
			$deps[] = 'thickbox';

		wp_enqueue_script(
			'fastfood',
			fastfood_get_minified( '%1$s/js/fastfood%2$s.js' ),
			$deps,
			fastfood_get_info( 'version' ),
			true
		);


	}
}


/**
 * localize script
 */
function fastfood_scripts_l10n() {
		$data = array(
			'script_modules'		=> fastfood_get_js_modules(),
			'post_expander_wait'	=> __( 'Post loading, please wait...', 'fastfood' ),
			'quote_link_info'		=> esc_attr( __( 'Add selected text as a quote', 'fastfood' ) ),
			'quote_link_alert'		=> __( 'Nothing to quote. First of all you should select some text...', 'fastfood' ),
			'responsive_threshold'	=> array( absint( FastfoodOptions::get_opt( 'fastfood_body_width' ) ) + 152, 782, 479 ),
			'close_minilogin'		=> __( 'Close', 'fastfood' ),
		);
		wp_localize_script(
			'fastfood',
			'_fastfoodL10n',
			apply_filters( 'fastfood_scripts_l10n', $data )
		);
	
}


/**
 * add a js-selecting class to <body>.
 * add listener to body resize, assigning to the <body> tag a layout class
 */
function fastfood_body_class_script(){

	?>

		<script type="text/javascript">
			/* <![CDATA[ */
			(function(){
				var b = document.body,
					c = b.className,
					window_onresize_TO = false;

				c = c.replace(/ff-no-js/, 'ff-js');
				b.className = c;

				_onresize();

				window.onresize = function() {
					if( window_onresize_TO !== false )
						clearTimeout( window_onresize_TO );

					window_onresize_TO = setTimeout( _onresize, 200 ); //200 is time in miliseconds
				}

				function _onresize() {
					var c = b.className;

					if ( window.innerWidth < 782 )
						c = c.replace(/layout-[0-9]/, 'layout-0');
					else if ( window.innerWidth < <?php echo absint( FastfoodOptions::get_opt( 'fastfood_body_width' ) ) + 152; ?> )
						c = c.replace(/layout-[0-9]/, 'layout-1');
					else
						c = c.replace(/layout-[0-9]/, 'layout-2');

					b.className = c;

				}
			})();
			/* ]]> */
		</script>

	<?php

}


// print extra info for posts/pages
if ( !function_exists( 'fastfood_post_details' ) ) {
	function fastfood_post_details( $args = '' ) {
		global $post;

		$defaults = array(
			'author'		=> 1,
			'date'			=> 1,
			'tags'			=> 1,
			'categories'	=> 1,
			'avatar_size'	=> 48,
			'featured'		=> 0,
			'echo'			=> 1,
		);

		$args = wp_parse_args( $args, $defaults );

		$tax_separator = apply_filters( 'fastfood_filter_taxomony_separator', ', ' );

		$output = '';

		if ( $args['featured'] &&  has_post_thumbnail( $post->ID ) )
			$output .= '<div class="tb-post-details post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail') . '</div>';

		if ( $args['author'] )
			$output .= fastfood_author_badge( $post->post_author, $args['avatar_size'] );

		if ( $args['categories'] )
			$output .= '<div class="tb-post-details post-details-cats"><span class="label">' . __( 'Categories', 'fastfood' ) . ': </span>' . get_the_category_list( $tax_separator ) . '</div>';

		if ( $args['tags'] )
			$tags = get_the_tags() ? get_the_tag_list( '</span>', $tax_separator, '' ) : __( 'No Tags', 'fastfood' ) . '</span>';
			$output .= '<div class="tb-post-details post-details-tags"><span class="label">' . __( 'Tags', 'fastfood' ) . ': ' . $tags . '</div>';

		if ( $args['date'] )
			$output .= '<div class="tb-post-details post-details-date"><span class="label">' . __( 'Published on', 'fastfood' ) . ': </span><a class="published" title="' . get_the_time( 'c' ) . '" href="' . get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')) . '">' . get_the_time( get_option( 'date_format' ) ) . '</a></div>';

		if ( !$args['echo'] )
			return $output;

		echo $output;

	}
}


// get the author badge
function fastfood_author_badge( $author = '', $size = 48 ) {
	global $authordata;

	$author = ( ( !$author ) && isset( $authordata->ID ) ) ? $authordata->ID : $author;

	$name = get_the_author_meta( 'nickname', $author ); // nickname

	$avatar = get_avatar( get_the_author_meta( 'email', $author ), $size, 'Gravatar Logo', get_the_author_meta( 'user_nicename', $author ) . '-photo' ); // gravatar

	$description = get_the_author_meta( 'description', $author ); // bio

	$author_link = get_author_posts_url($author); // link to author posts

	$author_net = ''; // author social networks
	foreach ( array( 'twitter' => 'Twitter', 'facebook' => 'Facebook', 'googleplus' => 'Google+', 'url' => 'web' ) as $s_key => $s_name ) {
		if ( get_the_author_meta( $s_key, $author ) )
			$author_net .= fastfood_build_link( array(
				'href'		=> get_the_author_meta( $s_key, $author ),
				'text'		=> '<img alt="' . $s_key . '" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/' . $s_key . '.png" />',
				'title'		=> esc_attr( sprintf( __('Follow %1$s on %2$s', 'fastfood'), $name, $s_name ) ),
				'class'		=> 'url',
				'target'	=> '_blank',
				'rel'		=> 'nofollow',
			) );
	}

	$output = '<li class="author-avatar">' . $avatar . '</li>';
	$output .= '<li class="author-name"><a class="fn nickname url" rel="author" href="' . $author_link . '" >' . $name . '</a></li>';
	$output .= $description ? '<li class="author-description note">' . $description . '</li>' : '';
	$output .= $author_net ? '<li class="author-social">' . $author_net . '</li>' : '';

	$output = '<div class="tb-post-details tb-author-bio vcard"><ul>' . $output . '</ul></div>';

	return apply_filters( 'fastfood_filter_author_badge', $output );

}


//get a thumb for a post/page
function fastfood_get_the_thumb_id( $args = '' ){
	global $post;

	$defaults = array(
		'post_id'		=> 0,
		'fb_attachment'	=> 1,
		'fb_header'		=> 1,
	);
	$args = wp_parse_args( $args, $defaults );

	if ( !$args['post_id'] ) $args['post_id'] = $post->ID;

	// has featured image
	if ( get_post_thumbnail_id( $args['post_id'] ) )
		return get_post_thumbnail_id( $args['post_id'] );

	$attachments = get_children( array(
		'post_parent'		=> $args['post_id'],
		'post_status'		=> 'inherit',
		'post_type'			=> 'attachment',
		'post_mime_type'	=> 'image',
		'orderby'			=> 'menu_order',
		'order'				=> 'ASC',
		'numberposts'		=> 1,
	) );

	//has attachments
	if ( $args['fb_attachment'] && $attachments )
		return key( $attachments );

	$header_image_data = (array) get_theme_mod( 'header_image_data' );
	if ( $args['fb_header'] && $header_image_data && isset( $header_image_data['attachment_id'] ) )
		return $header_image_data['attachment_id'];

	//nothing found
	return false;
}


//display the footer content
function fastfood_credits () {

?>
	<div id="credits">

		<?php echo fastfood_copyright(); ?> <strong><?php bloginfo( 'name' ); ?></strong> <?php _e( 'All rights reserved','fastfood' ); ?>

		<?php fastfood_hook_change_view(); ?>

		<?php
			if ( FastfoodOptions::get_opt('fastfood_tbcred' ) ) {
				$output = apply_filters( 'fastfood_filter_credits', sprintf( __( 'Powered by %1$s and %2$s', 'fastfood' ),
					'<a target="_blank" href="http://wordpress.org/" title="WordPress">WordPress</a>',
					'<a target="_blank" href="http://www.twobeers.net/" title="' . esc_attr( __( 'Visit theme authors homepage', 'fastfood' ) . ' @ twobeers.net' ) . '">Fastfood</a>'
				) );
				echo '<small>' . $output . '</small>';
			}
		?>

	</div>
<?php

}


/**
 * Display copyright notice customized according to date of first post
 */
function fastfood_copyright() {
	// check for cached values for copyright dates
	$copyright_cache = wp_cache_get( 'copyright_dates', 'fastfood' );
	// query the database for first/last copyright dates, if no cache exists
	if ( false === $copyright_cache ) {
		global $wpdb;
		$copyright_dates = $wpdb->get_results("
			SELECT
			YEAR(min(post_date_gmt)) AS firstdate,
			YEAR(max(post_date_gmt)) AS lastdate
			FROM
			$wpdb->posts
			WHERE
			post_status = 'publish'
		");
		$copyright_cache = $copyright_dates;
		// add the first/last copyright dates to the cache
		wp_cache_set( 'copyright_dates', $copyright_cache, 'fastfood', '604800' );
	}
	// Build the copyright notice, based on cached date values
	$output = '&copy; ';
	if( $copyright_cache && $copyright_cache[0]->firstdate ) {
		$copyright = $copyright_cache[0]->firstdate;
		if( $copyright_cache[0]->firstdate != $copyright_cache[0]->lastdate ) {
			$copyright .= '-' . $copyright_cache[0]->lastdate;
		}
		$output .= $copyright;
	} else {
		$output .= date( 'Y' );
	}
	return $output;
}


//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
function fastfood_friendly_date() {

	$postTime = get_the_time( 'U' );
	$currentTime = time();
	$timeDifference = $currentTime - $postTime;

	$minInSecs = 60;
	$hourInSecs = 3600;
	$dayInSecs = 86400;
	$monthInSecs = $dayInSecs * 31;
	$yearInSecs = $dayInSecs * 366;

	//if over 2 years
	if ( $timeDifference > ( $yearInSecs * 2 ) ) {
		$dateWithNiceTone = __( 'quite a long while ago...', 'fastfood' );

	//if over a year
	} else if ( $timeDifference > $yearInSecs ) {
		$dateWithNiceTone = __( 'over a year ago', 'fastfood' );

	//if over 2 months
	} else if ( $timeDifference > ( $monthInSecs * 2 ) ) {
		$num = round( $timeDifference / $monthInSecs );
		$dateWithNiceTone = sprintf( __( '%s months ago', 'fastfood' ), $num );

	//if over a month
	} else if ( $timeDifference > $monthInSecs ) {
		$dateWithNiceTone = __( 'a month ago', 'fastfood' );

	//if more than 2 days ago
	} else {
		$htd = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
		$dateWithNiceTone = sprintf( __( '%s ago', 'fastfood' ), $htd );
	}

	return apply_filters( 'fastfood_filter_friendly_date', $dateWithNiceTone );

}


// page hierarchy
function fastfood_multipages(){
	global $post;

	$args = array(
		'post_type'		=> 'page',
		'post_parent'	=> $post->ID,
		'order'			=> 'ASC',
		'orderby'		=> 'menu_order',
		'numberposts'	=> 0,
		'no_found_rows'	=> true,
	);
	$parent = $post->post_parent; // retrieve the parent page
	$childrens = get_posts( $args ); // retrieve the child pages
	$output = '';
	$the_parent_link = '';
	$the_childrens_list = '';
	$the_separator = '';

	if ( $parent ) {
		$the_parent_link = __( 'Upper page', 'fastfood' ) . ': ' . fastfood_build_link( array(
			'href'		=> get_permalink( $parent ),
			'text'		=> get_the_title( $parent ),
			'title'		=> get_the_title( $parent ),
		) );
	}

	if ( ( $childrens ) && ( $parent ) ) { $the_separator = ' - '; }

	if ( $childrens ) {
		foreach ( $childrens as $children ) {
			$the_childrens_list[] = fastfood_build_link( array(
				'href'		=> get_permalink( $children ),
				'text'		=> get_the_title( $children ),
				'title'		=> get_the_title( $children ),
			) );
		}
		$the_childrens_list = __( 'Lower pages', 'fastfood' ) . ': ' . implode( ', ' , $the_childrens_list );
	}

	$output = $the_parent_link . $the_separator . $the_childrens_list;

	return $output;

}


// get the post thumbnail or (if not set) the format related icon
function fastfood_get_the_thumb( $args = '' ) {

	$defaults = array(
		'id'		=> '',
		'size'		=> array( 40, 40 ),
		'class'		=> '',
		'default'	=> '',
		'linked'	=> 0,
	);
	$args = wp_parse_args( $args, $defaults );

	if ( has_post_thumbnail( $args['id'] ) ) {
		$output = get_the_post_thumbnail( $args['id'], $args['size'], array( 'class' => $args['class'] ) );
	} else {
		$output = $args['default'];
	}

	if ( $args['linked'] )
		$thumb = '<a href="' . get_permalink( $args['id'] ) . '" rel="bookmark">' . $output . '</a>';
	else
		$thumb = $output;

	return apply_filters( 'fastfood_filter_get_the_thumb', $thumb );

}


/**
 * Display the post title ( optionally with the featured image )
 * 
 * @since 0.30
 *
 * @param array $args {
 *     @type string $alternative    Force to use this string as title.
 *     @type string $fallback       A fallback string to use if nor the title nor the alternative are set.
 *     @type bool   $featured       Display the thumbnail (if set) beside the title.
 *     @type string $href           The href attribute for the link.
 *     @type string $target         The target attribute for the link.
 *     @type string $title          The title attribute for the link.
 *     @type bool   $echo           Whether display or return the title.
 * }
 * @return string|null Null on display. String when echo is false.
 */
function fastfood_featured_title( $args = '' ) {
	global $post;

	$defaults = array(
		'alternative'	=> '',
		'fallback'		=> '',
		'thumbnail'		=> true,
		'href'			=> get_permalink(),
		'target'		=> '',
		'title'			=> the_title_attribute( array( 'echo' => 0 ) ),
		'echo'			=> 1,
	);
	$args = wp_parse_args( $args, $defaults );

	if ( FastfoodOptions::get_opt( 'fastfood_hide_frontpage_title' ) && is_page() && is_front_page() ) return;

	if ( FastfoodOptions::get_opt( 'fastfood_hide_pages_title' ) && is_page() ) return;

	if ( FastfoodOptions::get_opt( 'fastfood_hide_posts_title' ) && is_single() ) return;

	if ( $selected_ids = preg_replace( array( '/[^0-9 ,]/' ,'/[ ]/' ), array( '', ',' ), FastfoodOptions::get_opt( 'fastfood_hide_selected_entries_title' ) ) ) {
		$selected_ids = explode( ',', $selected_ids );
		if ( in_array( $post->ID, $selected_ids ) ) return;
	}

	if ( !FastfoodOptions::get_opt( 'fastfood_featured_title' ) ) $args['thumbnail'] = false;

	$post_title		= $args['alternative'] ? $args['alternative'] : get_the_title();
	$post_title		= $post_title ? $post_title : $args['fallback'];
	$post_title		= $post_title ? '<span class="entry-title-text">' . $post_title . '</span>' : '';
	$link_target	= $args['target'] ? ' target="'.$args['target'].'"' : '';
	$thumb			= ( $args['thumbnail'] && has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, array( FastfoodOptions::get_opt( 'fastfood_featured_title_size' ), FastfoodOptions::get_opt( 'fastfood_featured_title_size' ) ), array( 'class' => 'entry-title-thumbnail' ) ) : '';
	$title_class	= $thumb ? 'entry-title with-thumbnail thumbnail-' . esc_attr( FastfoodOptions::get_opt( 'fastfood_featured_title_size' ) ) : 'entry-title';
	$title_content	= $thumb . $post_title;
	$title_content	= is_singular() ? '<span class="entry-title-content">' . $title_content . '</span>' : '<a class="entry-title-content" title="' . $args['title'] . '" href="' . $args['href'] . '"' . $link_target . ' rel="bookmark">' . $title_content . '</a>';

	if ( $post_title || $thumb ) $post_title = '<h2 class="' . $title_class . '">' . $title_content . '</h2>';

	$post_title = apply_filters( 'fastfood_filter_featured_title', $post_title );

	if ( $args['echo'] )
		echo $post_title;
	else
		return $post_title;

}


// print extra info for posts/pages
function fastfood_extrainfo( $args = '' ) {

	$defaults = array(
		'auth'		=> FastfoodOptions::get_opt( 'fastfood_xinfos_byauth' ),
		'date'		=> FastfoodOptions::get_opt( 'fastfood_xinfos_date' ),
		'comms'		=> FastfoodOptions::get_opt( 'fastfood_xinfos_comm' ),
		'tags'		=> FastfoodOptions::get_opt( 'fastfood_xinfos_tag' ),
		'cats'		=> FastfoodOptions::get_opt( 'fastfood_xinfos_cat' ),
		'hiera'		=> FastfoodOptions::get_opt( 'fastfood_xinfos_hiera' ),
		'list_view'	=> FastfoodOptions::get_opt( 'fastfood_xinfos_static' ),
	);
	$args = wp_parse_args( $args, $defaults );


	//xinfos disabled when...
	if ( !FastfoodOptions::get_opt( 'fastfood_xinfos_global' ) ) return; //xinfos globally disabled
	if ( is_page() && is_front_page() && !FastfoodOptions::get_opt( 'fastfood_xinfos_on_front' ) ) return; // is front page
	if ( is_page() && !FastfoodOptions::get_opt( 'fastfood_xinfos_on_page' ) ) return;
	if ( is_single() && !FastfoodOptions::get_opt( 'fastfood_xinfos_on_post' ) ) return;
	if ( !is_singular() && !FastfoodOptions::get_opt( 'fastfood_xinfos_on_list' ) ) return;

	if ( is_page() ) {
		$page_cd_nc = ( !comments_open() && !have_comments() ); //true if page with comments disabled and no comments
		if ( $page_cd_nc ) $args['comms'] = false;
		$args['auth'] = false;
		$args['date'] = false;
		$args['tags'] = false;
		$args['cats'] = false;
	}

	$post_author	= ( ( $args['auth'] === true ) || ( $args['auth'] === 1 ) ) ? '<a class="fn author nickname url" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : '<span class="vcard fn author">' . $args['auth'] . '</span>';
	$post_author	= '<span class="hide_if_no_print">' . __( 'Author', 'fastfood' ) . ': </span><i class="el-icon-user"></i> ' . $post_author;
	$categories		= __( 'Categories', 'fastfood' ) . ': ' . get_the_category_list( ', ' );
	$tags			= __( 'Tags', 'fastfood' ) . ': ' . ( ( get_the_tags() ) ? get_the_tag_list( '', ', ' , '') : __( 'No Tags', 'fastfood' ) );
	$comments		= __( 'Comments', 'fastfood' ) . ': ' . fastfood_get_comments_link();
	$date			= __( 'Published on', 'fastfood' ) . ': <b class="published" title="' . get_the_time( 'c' ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</b>';
	$hierarchy		= fastfood_multipages();

	if ( is_page() && !$args['comms'] && !$hierarchy ) return;

	if ( !$args['list_view'] ) {

		?>

			<div class="metadata">

				<?php if ( $args['cats'] ) { ?>
					<div class="metadata-panel metadata-panel-categories">
						<div class="metadata-panel-content">
							<?php echo $categories; ?>
						</div>
						<span class="metadata-panel-trigger"><i class="el-icon-folder-open"></i></span>
					</div>
				<?php }?>

				<?php if ( $args['tags'] ) { ?>
					<div class="metadata-panel metadata-panel-tags">
						<div class="metadata-panel-content">
							<?php echo $tags; ?>
						</div>
						<span class="metadata-panel-trigger"><i class="el-icon-tags"></i></span>
					</div>
				<?php }?>

				<?php if ( $args['comms'] ) { ?>
					<div class="metadata-panel metadata-panel-comments">
						<div class="metadata-panel-content">
							<?php echo $comments; ?>
						</div>
						<span class="metadata-panel-trigger"><i class="el-icon-comment"></i></span>
					</div>
				<?php } ?>

				<?php if ( $args['date'] ) { ?>
					<div class="metadata-panel metadata-panel-date">
						<div class="metadata-panel-content">
							<?php echo $date; ?>
						</div>
						<span class="metadata-panel-trigger"><i class="el-icon-calendar"></i></span>
					</div>
				<?php } ?>

				<?php if ( $args['hiera'] && $hierarchy ) { ?>
					<div class="metadata-panel metadata-panel-hierarchy">
						<div class="metadata-panel-content">
							<?php echo $hierarchy; ?>
						</div>
						<span class="metadata-panel-trigger"><i class="el-icon-fork"></i></span>
					</div>
				<?php } ?>

				<?php if ( get_edit_post_link() ) { ?>
					<div class="metadata-panel metadata-panel-edit hide_if_print">
						<?php
							echo fastfood_build_link( array(
								'href'		=> get_edit_post_link(),
								'text'		=> '<i class="el-icon-pencil"></i><span class="screen-reader-text">' . __( 'Edit', 'fastfood' ) . '</span>',
								'title'		=> __( 'Edit', 'fastfood' ),
								'class'		=> 'metadata-panel-trigger',
								'rel'		=> 'nofollow',
							) );
						?>
					</div>
				<?php } ?>

				<?php if ( $args['auth'] ) { ?>
					<div class="metadata-panel metadata-panel-author vcard">
						<span class="metadata-panel-trigger"><?php echo $post_author; ?></span>
					</div>
				<?php } ?>

			</div>

		<?php

	} else {

		?>

			<div class="metadata static">

				<?php if ( $args['auth'] )	echo $post_author . '<br />'; ?>
				<?php if ( $args['date'] )	echo $date . '<br />'; ?>
				<?php if ( $args['comms'] )	echo $comments . '<br />'; ?>
				<?php if ( $args['tags'] )	echo $tags . '<br />'; ?>
				<?php if ( $args['cats'] )	echo $categories . '<br />'; ?>
				<?php edit_post_link( __( 'Edit', 'fastfood' ) ); ?>

			</div>

		<?php

	}
}


// comments-are-closed message when post type supports comments and we're not on a page
function fastfood_comments_closed() {
	if ( !is_page() && post_type_supports( get_post_type(), 'comments' ) ) {

?>
	<div class="solid-label"><?php _e( 'Comments are closed.', 'fastfood' ); ?></div>
<?php

	}
}


/**
 * skip posts with aside/status format (via options)
 *
 * taken from:
 * @link http://www.billerickson.net/customize-the-wordpress-query/
 *
 */
function fastfood_exclude_format_from_blog( $query ) {

	if( $query->is_main_query() && $query->is_home() ) {

		if ( !FastfoodOptions::get_opt( 'fastfood_post_view_aside') ) $terms[] = 'post-format-aside';
		if ( !FastfoodOptions::get_opt( 'fastfood_post_view_status' ) ) $terms[] = 'post-format-status';

		if ( isset( $terms ) ) {

			$tax_query = array(
				array(
					'taxonomy'	=> 'post_format',
					'terms'		=> $terms,
					'field'		=> 'slug',
					'operator'	=> 'NOT IN',
				),
			);

			$query->set( 'tax_query', $tax_query );

		}
	}

}


// Search reminder
function fastfood_search_reminder() {
	global $wp_query;

	$text = '';
	$term = get_queried_object();

	if ( !FastfoodOptions::get_opt( 'fastfood_breadcrumb' ) ) {
		if ( is_archive() ) {

			$title = '';
			$type = '';
			if ( is_category() || is_tag() || is_tax() ) {
				if ( is_category() )	$type = __( 'Category', 'fastfood' );
				elseif ( is_tag() )		$type = __( 'Tag', 'fastfood' );
				elseif ( is_tax() )		$type = __( 'Taxonomy', 'fastfood' );
				$title = $term->name;
			} elseif ( is_date() ) {
				$type = __( 'Date', 'fastfood' );
				if ( is_day() ) {
					$title = get_the_date();
				} else if ( is_month() ) {
					$title = single_month_title( ' ', false );
				} else if ( is_year() ) {
					$title = get_query_var( 'year' );
				}
			} elseif ( is_author() ) {
				$type = __( 'Author', 'fastfood' );
				$title = $term->display_name;
			}

			$text = sprintf( __( '%s archive', 'fastfood' ), get_bloginfo( 'name' ) ) . '<h2>' . $type . ' : <span class="ff-search-term">' . $title . '</span> <span class="ff-search-found">(' . $wp_query->found_posts . ')</span></h2>';

		} elseif ( is_search() ) {

			$text = sprintf( __( 'Search results for &#8220;%s&#8221;', 'fastfood' ), '<span class="ff-search-term">' . esc_html( get_search_query() ) . '</span> <span class="ff-search-found">(' . $wp_query->found_posts . ')</span>' );

		}

	}

	if ( $text ) {

?>
	<div class="ff-search-reminder">

		<?php echo $text; ?>

		<?php if ( is_category() && category_description() ) echo category_description(); ?>

	</div>
<?php

	}

	if ( is_author() )
		echo fastfood_author_badge();

}


//add some print links to print preview
function fastfood_print_preview_buttons() {
	global $post;

	if ( !fastfood_is_printpreview() ) return;
	?>

	<div id="close_preview">
		<a id="close_button" title="<?php _e( 'Close','fastfood' ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php _e( 'Close','fastfood' ); ?></a>
		<a href="javascript:window.print()" title="<?php _e( 'Print','fastfood' ); ?>" id="print_button" class="hide-if-no-js"><?php _e( 'Print','fastfood' ); ?></a>
	</div>

	<?php

}


/**
 * Get current page context
 *
 * Returns a string containing the context of the
 * current page. This string is useful for adding
 * a contextual $name to calls get_template_part_file(),
 * in order to facilitate Child Themes overriding
 * default Theme template part files.
 *
 * @param	none
 * @return	string	current page context
 */
function fastfood_get_context() {

	$context = apply_filters( 'fastfood_default_context', 'singular' );

	if ( is_front_page() ) {
		// Front Page
		$context = 'front-page';
	} else if ( is_attachment() ) {
		// Attachment Page
		$context = 'attachment';
	} else if ( is_singular( 'post' ) ) {
		// Single Blog Post
		$context = 'single';
	} else if ( is_page() ) {
		// Static Page
		$context = 'page';
	}

	return apply_filters( 'fastfood_get_context', $context );
}


/**
 * Displays the link to the comments popup window for the current post ID.
 *
 */
function fastfood_get_comments_link( $args = '' ) {

	$defaults = array(
		'zero'		=> false,
		'one'		=> false,
		'more'		=> false,
		'css_class'	=> '',
		'none'		=> false,
		'id'		=> false,
	);
	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);

	if ( false === $zero )	$zero	= __( 'No Comments', 'fastfood' );
	if ( false === $one )	$one	= __( '1 Comment', 'fastfood' );
	if ( false === $more )	$more	= __( '% Comments', 'fastfood' );
	if ( false === $none )	$none	= __( 'Comments Off', 'fastfood' );
	$id = ( $id ) ? (int)$id : get_the_ID();
	$css_class = ( !empty( $css_class ) ) ? ' class="' . esc_attr( $css_class ) . '"' : '';

	$number = get_comments_number( $id );

	if ( 0 == $number && !comments_open() && !pings_open() ) {

		$output = '<span' . $css_class . '>' . $none . '</span>';

	} elseif ( post_password_required() ) {

		$output = __( 'Enter your password to view comments', 'fastfood' );

	} else {

		$label = FastfoodOptions::get_opt( 'fastfood_cust_comrep' ) ? '#comments' : '#respond';
		$href = ( 0 == $number ) ? get_permalink() . $label : get_comments_link();
		$title = esc_attr( sprintf( __( 'Comment on %s', 'fastfood'), the_title_attribute( array( 'echo' => 0 ) ) ) );

		if ( $number > 1 )
			$text = str_replace( '%', number_format_i18n( $number ), $more );
		elseif ( $number == 0 )
			$text = $zero;
		else
			$text = $one;

		$output = '<a' . $css_class . ' href="' . esc_url( $href ) . '" title="' . $title . '">' . $text . '</a>';

	}

	return apply_filters( 'fastfood_get_comments_link' , $output );

}


// set up custom colors and header image
function fastfood_setup() {
	global $fastfood_opt;
	global $content_width;

	// Register localization support
	load_theme_textdomain( 'fastfood', get_template_directory() . '/languages' );

	FastfoodOptions::init();
	$fastfood_opt = wp_parse_args( get_option( 'fastfood_options' ), FastfoodOptions::get_defaults() );

	// Theme uses wp_nav_menu() in three location
	register_nav_menus( array( 'primary'	=> __( 'Primary menu', 'fastfood' ) ) );
	register_nav_menus( array( 'secondary1'	=> __( 'Secondary menu #1', 'fastfood' ) ) );
	register_nav_menus( array( 'secondary2'	=> __( 'Secondary menu #2', 'fastfood' ) ) );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

	// Add the editor style
	if ( FastfoodOptions::get_opt( 'fastfood_editor_style' ) )
		add_editor_style( 'css/editor-style.css' );

	// This theme uses post formats
	$pformats = array();
	if ( FastfoodOptions::get_opt( 'fastfood_post_formats_gallery' ) )	$pformats[] = 'gallery';
	if ( FastfoodOptions::get_opt( 'fastfood_post_formats_aside' ) )	$pformats[] = 'aside';
	if ( FastfoodOptions::get_opt( 'fastfood_post_formats_status' ) )	$pformats[] = 'status';
	if ( FastfoodOptions::get_opt( 'fastfood_post_formats_quote' ) )	$pformats[] = 'quote';
	$pformats = apply_filters( 'fastfood_filter_post_formats', $pformats );
	if ( !empty( $pformats ) )
		add_theme_support( 'post-formats', $pformats );

	$content_width = absint( FastfoodOptions::get_opt( 'fastfood_body_width' ) - FastfoodOptions::get_opt( 'fastfood_rsideb_width' ) - 42 );

}


// add 'quoted on' before trackback/pingback comments link
function fastfood_add_quoted_on( $return ) {

	$text = '';
	if ( get_comment_type() != 'comment' ) {
		$text = '<span style="font-weight: normal;">' . __( 'quoted on', 'fastfood' ) . ' </span>';
	}
	return $text . $return;

}


// strip tags and apply title format for blank titles
function fastfood_title_tags_filter( $title = '', $id = null ) {

	if ( is_admin() ) return $title;

	if ( FastfoodOptions::get_opt( 'fastfood_manage_blank_title' ) && $id && empty( $title ) ) {
		$postdata = array( get_post_format( $id )? get_post_format_string( get_post_format( $id ) ): __( 'Post', 'fastfood' ), get_the_time( get_option( 'date_format' ), $id ), $id );
		$codes = array( '%f', '%d', '%n' );
		$title = str_replace( $codes, $postdata, FastfoodOptions::get_opt( 'fastfood_blank_title' ) );
	}

	return strip_tags( $title, '<abbr><acronym><b><em><i><del><ins><bdo><strong><img><sub><sup>' );

}


//set the excerpt length
function fastfood_excerpt_length( $length ) {

	if ( is_admin() ) return $length;

	return (int) FastfoodOptions::get_opt( 'fastfood_excerpt_lenght' );

}


// use the "excerpt more" string as a link to the post
function fastfood_excerpt_more( $more ) {

	if ( is_admin() ) return $more;

	if ( FastfoodOptions::get_opt( 'fastfood_excerpt_more_link' ) ) {
		return '<a href="' . get_permalink() . '">' . esc_html( FastfoodOptions::get_opt( 'fastfood_excerpt_more_txt' ) ) . '</a>';
	} else {
		return esc_html( FastfoodOptions::get_opt( 'fastfood_excerpt_more_txt' ) );
	}

	return $more;

}


// custom "more" tag
function fastfood_more_link( $more_link, $more_link_text, $auto_hide = true ) {

	if ( FastfoodOptions::get_opt( 'fastfood_more_tag' ) && !is_admin() ) {
		$text = str_replace ( '%t', get_the_title(), esc_html( FastfoodOptions::get_opt( 'fastfood_more_tag' ) ) );
		$more_link = str_replace( $more_link_text, $text, $more_link );
	}

	if ( FastfoodOptions::get_opt( 'fastfood_more_tag_scroll' ) )
		$more_link = preg_replace( '|#more-[0-9]+|', '', $more_link );

	if ( FastfoodOptions::get_opt( 'fastfood_more_tag_always' ) && $auto_hide )
		$more_link = '';

	return $more_link;

}


// link to post at the end of content
function fastfood_always_more() {

	if ( FastfoodOptions::get_opt( 'fastfood_more_tag_always' ) )
		echo fastfood_more_link( '<p><a class="moretag" href="' . get_permalink() . '">Read...</a></p>', 'Read...', false );

}


/**
 * Add classes to <body>
 */
function fastfood_body_classes( $classes ) {

	$classes[] = 'ff-no-js';
	$classes[] = 'layout-2';

	if ( FastfoodOptions::get_opt( 'fastfood_tinynav' ) ) $classes[] = 'tinynav-support';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_captions' ) ) $classes[] = 'fading-captions';
	if ( FastfoodOptions::get_opt( 'fastfood_rsideb_position' ) == 'left' ) $classes[] = 'left-sidebar';

	return $classes;

}


/**
 * Add classes to <div id="posts-content">
 */
function fastfood_posts_content_class() {

	$classes[] = fastfood_use_sidebar() ? 'posts_narrow' : 'posts_wide';

	$classes = apply_filters( 'fastfood_posts_content_class', $classes );

	echo ' class="' . esc_attr( join( ' ', $classes ) ) . '"';

}


/**
 * Add parent class to wp_page_menu top parent list items
 */
function fastfood_add_parent_class( $css_class, $page, $depth, $args ) {

	if ( !empty( $args['has_children'] ) && $depth == 0 )
		$css_class[] = 'menu-item-parent';

	return $css_class;

}


/**
 * Add parent class to wp_nav_menu top parent list items
 */
function fastfood_add_menu_parent_class( $items ) {

	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}

	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			if ( !$item->menu_item_parent )
				$item->classes[] = 'menu-item-parent';
		}
	}

	return $items;
}


// the search form filter
function fastfood_search_form() {
	static $counter = 0;

	$counter++;

	$form = '<form role="search" method="get" id="searchform-'. $counter . '" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
		<div>
			<label class="screen-reader-text" for="s">' . __( 'Search', 'fastfood' ) . '</label>
			<input type="text" value="' . get_search_query() . '" name="s" id="s-'. $counter . '" placeholder="' . __( 'Search', 'fastfood' ) . '" />
			<button title="' . __( 'Search', 'fastfood' ) . '" type="submit"><i class="el-icon-search"></i></button>
		</div>
	</form>';

	return $form;

}


// wrap the comment content a div
function fastfood_wrap_comment_text( $output ) {

	return '<div class="comment-content">' . $output . '</div>';

}


//replace the comment_reply_link text
function fastfood_comment_reply_link( $link ) {

	preg_match_all( '/<a\b[^>]*>(.*?)<\/a>/',$link, $text );

	if ( isset( $text[1][0] ) )
		$link = str_replace( '>' . $text[1][0], ' title="' . esc_attr__( 'Reply to comment', 'fastfood' ) . '" ><i class="el-icon-return-key"></i><span class="screen-reader-text">' . esc_attr__( 'Reply to comment', 'fastfood' ) . '</span>', $link);

	return $link;

}


// wrap the categories count with a span
function fastfood_wrap_categories_count( $output ) {
	$pattern = '/<\/a>\s(\(\d+\))/i';
	$replacement = ' <span class="details">$1</span></a>';
	return preg_replace( $pattern, $replacement, $output );
}


// backwards compatibility for the <title> tag
function fastfood_render_title() {

	if ( !function_exists( '_wp_render_title_tag' ) ) return;
?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php

}


// filters comments_form() default arguments
function fastfood_comment_form_defaults( $defaults ) {

	$defaults['comment_field']			= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
	$defaults['label_submit']			= __( 'Say It!','fastfood' );
	$defaults['title_reply']			= '<span class="label">'. __( 'Leave a comment','fastfood' ) . '</span>';

	return $defaults;

}


function fastfood_comments_header() {

	$output = '';
	$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
	if ( $num_comments == 0 ) {
		$comments = __('No Comments', 'fastfood');
	} elseif ( $num_comments > 1 ) {
		$comments = sprintf( __('%s Comments', 'fastfood'), $num_comments );
	} else {
		$comments = __('1 Comment', 'fastfood');
	}

	if ( post_password_required() ) {

		$output = __( 'Enter your password to view comments.','fastfood' );

	} elseif ( comments_open() ) {

		$output = $comments . sprintf( ' <a class="show-comment-form hide_if_print" href="#respond" title="%1$s">%2$s</a>',
			esc_attr__( 'Leave a comment', 'fastfood' ),
			esc_html__( 'Leave a comment', 'fastfood' )
		);

	} elseif ( have_comments() ) {

		$output = $comments;

	}

	$class = $num_comments ? 'has-comments' : 'no-comments';
	if ( $output )
		echo '<div class="solid-label comments-header ' . $class . '">' . $output . '</div>';

}

// add the avatar before the "logged in as..." message
function fastfood_add_avatar_to_logged_in( $text = '', $commenter = false, $user_identity = false ) {

	$avatar = is_user_logged_in() ? get_avatar( get_current_user_id(), 50, $default = get_option( 'avatar_default' ) ) . ' ' : '';

	$text = str_replace( '<p class="logged-in-as">', '<p class="logged-in-as no-grav">' . $avatar, $text );

	return $text;

}


// retrieve the post content, then die (for "post_expander" ajax request)
function fastfood_post_expander_show_post () {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	die();
}


//is a "post_expander" ajax request?
function fastfood_post_expander_activate ( ) {
	if ( isset( $_POST["ff_post_expander"] ) ) {
		add_action( 'wp', 'fastfood_post_expander_show_post' );
	}
}


// retrieve the post content, then die (for "post_expander" ajax request)
function fastfood_get_comments_page () {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			comments_template('/comments-list.php');
		}
	}
	die();
}


//is a "post_expander" ajax request?
function fastfood_activate_get_comments_page ( ) {
	if ( isset( $_POST["ff_get_comments_page"] ) ) {
		add_action( 'wp', 'fastfood_get_comments_page' );
	}
}


function fastfood_build_link( $args = '' ) {

		$defaults = array(
			'href'		=> '',
			'text'		=> '',
			'title'		=> '',
			'class'		=> '',
			'target'	=> '',
			'rel'		=> '',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( !$args['href'] || !$args['text'] ) return;

		$args['href'] = esc_url( $args['href'] );
		$args['title'] = esc_html( $args['title'] );

		$args['title'] = $args['title'] ? ' title="' . esc_attr( $args['title'] ) . '"' : '';
		$args['class'] = $args['class'] ? ' class="' . esc_attr( $args['class'] ) . '"' : '';
		$args['target'] = in_array( $args['target'], array( '_blank', '_parent', '_top' ) ) ? ' target="' . esc_attr( $args['target'] ) . '"' : '';
		$args['rel'] = $args['rel'] ? ' rel="' . esc_attr( $args['rel'] ) . '"' : '';

		$attributes = $args['title'] . $args['class'] . $args['target'] . $args['rel'];

		return sprintf( '<a href="%1$s"%2$s>%3$s</a>',
			$args['href'],
			$attributes,
			$args['text']
		);

}


/**
 * Convert HEX to RGB.
 *
 * @since Fastfood 0.37
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function fastfood_hex2rgb( $color ) {
	$color = trim( $color, '#' );
	if ( strlen( $color ) == 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) == 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}
	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}


/**
 * Get the minified path of the file.
 * 
 * @param string $path The path, eg '%1$s/folder_name/file_base_name%2$s.file_ext'.
 * @return string
 */
function fastfood_get_minified( $path ) {

	return sprintf( $path,
		get_template_directory_uri(),
		( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'
	);

}


//non multibyte fix
if ( !function_exists( 'mb_strimwidth' ) ) {
	function mb_strimwidth( $string, $start, $length, $wrap = '&hellip;' ) {
		if ( strlen( $string ) > $length ) {
			$ret_string = substr( $string, $start, $length ) . $wrap;
		} else {
			$ret_string = $string;
		}
		return $ret_string;
	}
}
