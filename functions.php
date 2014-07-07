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
add_action( 'wp_head'								, 'fastfood_custom_css' );
add_action( 'init'									, 'fastfood_post_expander_activate' );
add_action( 'init'									, 'fastfood_activate_get_comments_page' );
add_action( 'admin_bar_menu'						, 'fastfood_admin_bar_plus', 999 );
add_action( 'template_redirect'						, 'fastfood_allcat' );
add_action( 'comment_form_comments_closed'			, 'fastfood_comments_closed' );
add_action( 'pre_get_posts'							, 'fastfood_exclude_format_from_blog' );


/* Custom actions - theme hooks */

add_action( 'fastfood_hook_body_top'				, 'fastfood_body_class_script' );
add_action( 'fastfood_hook_header_after'			, 'fastfood_main_menu' );
add_action( 'fastfood_hook_attachment_before'		, 'fastfood_navigate_images' );
add_action( 'fastfood_hook_attachment_after'		, 'fastfood_video_player' );
add_action( 'fastfood_hook_comments_list_before'	, 'fastfood_navigate_comments' );
add_action( 'fastfood_hook_comments_list_after'		, 'fastfood_navigate_comments' );
add_action( 'fastfood_hook_content_top'				, 'fastfood_search_reminder' );
add_action( 'fastfood_hook_loop_after'				, 'fastfood_navigate_archives' );
add_action( 'fastfood_hook_post_content_after'		, 'fastfood_always_more' );
add_action( 'fastfood_hook_post_content_after'		, 'fastfood_link_pages' );


/* Custom filters - WP hooks */

add_filter( 'embed_oembed_html'						, 'fastfood_wmode_transparent', 10, 3);
add_filter( 'img_caption_shortcode_width'			, 'fastfood_img_caption_shortcode_width', 10, 2 );
add_filter( 'shortcode_atts_gallery'				, 'fastfood_shortcode_atts_gallery', 10, 3 );
add_filter( 'previous_posts_link_attributes'		, 'fastfood_previous_posts_link_attributes', 10, 1 );
add_filter( 'next_posts_link_attributes'			, 'fastfood_next_posts_link_attributes', 10, 1 );
add_filter( 'the_content'							, 'fastfood_quote_content' );
add_filter( 'wp_get_attachment_link'				, 'fastfood_get_attachment_link', 10, 6 );
add_filter( 'use_default_gallery_style'				, '__return_false' );
add_filter( 'avatar_defaults'						, 'fastfood_addgravatar' );
add_filter( 'get_comment_author_link'				, 'fastfood_add_quoted_on' );
add_filter( 'the_title'								, 'fastfood_title_tags_filter', 10, 2 );
add_filter( 'excerpt_length'						, 'fastfood_excerpt_length' );
add_filter( 'excerpt_mblength'						, 'fastfood_excerpt_length' );
add_filter( 'excerpt_more'							, 'fastfood_excerpt_more' );
add_filter( 'the_content_more_link'					, 'fastfood_more_link', 10, 2 );
add_filter( 'wp_title'								, 'fastfood_filter_wp_title' );
add_filter( 'body_class'							, 'fastfood_body_classes' );
add_filter( 'comment_form_defaults'					, 'fastfood_comment_form_defaults' );
add_filter( 'wp_list_categories'					, 'fastfood_wrap_categories_count' );
add_filter( 'wp_nav_menu_items'						, 'fastfood_add_home_link', 10, 2 );
add_filter( 'comment_form_logged_in'				, 'fastfood_add_avatar_to_logged_in', 10, 3 );
add_filter( 'page_css_class'						, 'fastfood_add_parent_class', 10, 4 );
add_filter( 'wp_nav_menu_objects'					, 'fastfood_add_menu_parent_class' );
add_filter( 'get_search_form'						, 'fastfood_search_form' );


/* Custom filters - Misc hooks */

add_filter( 'tb_chat_load_style'					, '__return_false' );


/* load theme options in $fastfood_opt variable, globally retrieved in php files */

$fastfood_opt = get_option( 'fastfood_options' );


/* theme infos */

function fastfood_get_info( $field ) {
	static $infos;

	if ( !isset( $infos ) ) {
		$infos['theme'] = wp_get_theme( 'fastfood' );
		$infos['current_theme'] = wp_get_theme();
		$infos['version'] = $infos['theme']? $infos['theme']['Version'] : '';
	}

	return $infos[$field];
}


/* load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes) */

require_once( 'lib/options.php' ); // load options

require_once( 'lib/widgets.php' ); // load the custom widgets module

$fastfood_is_mobile = false;
require_once( 'mobile/core-mobile.php' ); // load mobile functions

require_once( 'lib/breadcrumb.php' ); // load breadcrumb functions

require_once( 'lib/hooks.php' ); // load the custom hooks

require_once( 'lib/quickbar.php' ); // load the quickbar functions

require_once( 'lib/my-custom-background.php' ); // load the custom background feature

require_once( 'lib/custom-header.php' ); // load the custom header stuff

require_once( 'lib/header-image-slider.php' ); // load the header image slider code

require_once( 'lib/comment-reply.php' ); // load comment reply script

require_once( 'lib/admin.php' ); // load the admin stuff

require_once( 'lib/plug-n-play.php' ); // load the plugins support module


/* conditional tags */

function fastfood_is_mobile() { // mobile
	global $fastfood_is_mobile;

	return $fastfood_is_mobile;

}

function fastfood_is_printpreview() { // print preview
	static $is_printpreview;

	if ( !isset( $is_printpreview ) ) {
		$is_printpreview = isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ? true : false;
	}

	return $is_printpreview;

}

function fastfood_is_allcat() { // "all category" page
	static $is_allcat;

	if ( !isset( $is_allcat ) ) {
		$is_allcat = isset( $_GET['allcat'] ) && md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ? true : false;
	}

	return $is_allcat;

}


// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'fastfood_allcat' ) ) {
	function fastfood_allcat () {

		if( fastfood_is_allcat() ) {
			get_template_part( 'allcat' );
			exit;
		}

	}
}


// is sidebar visible?
function fastfood_use_sidebar() {
	static $bool;

	if ( ! isset( $bool ) ) {

		$bool = true;

		if (
			( ! is_singular() && ! FastfoodOptions::get_opt( 'fastfood_rsidebindexes' ) ) ||
			( is_page() && ! FastfoodOptions::get_opt( 'fastfood_rsidebpages' ) ) ||
			( is_attachment() && ! FastfoodOptions::get_opt( 'fastfood_rsidebattachments' ) ) ||
			( is_single() && ! FastfoodOptions::get_opt( 'fastfood_rsidebposts' ) )
		)
			$bool = false;

		$bool = apply_filters( 'fastfood_use_sidebar', $bool );

	}

	return $bool;

}


function fastfood_get_sidebar( $name = 'primary' ) {

	get_sidebar( $name );

}


// Add stylesheets to page
if ( !function_exists( 'fastfood_stylesheet' ) ) {
	function fastfood_stylesheet(){

		if ( is_admin() || fastfood_is_mobile() ) return;

		if ( fastfood_is_printpreview() ) { //print preview

			wp_enqueue_style( 'fastfood-print-preview', get_template_directory_uri() . '/css/print.css', false, fastfood_get_info( 'version' ), 'screen' );
			wp_enqueue_style( 'fastfood-general-preview', get_template_directory_uri() . '/css/print_preview.css', false, fastfood_get_info( 'version' ), 'screen' );

		} else { //normal view

			if ( FastfoodOptions::get_opt( 'fastfood_gallery_preview' ) )
				wp_enqueue_style( 'thickbox' );

			wp_enqueue_style( 'fastfood-general-style', get_stylesheet_uri(), false, fastfood_get_info( 'version' ), 'screen' );
			wp_enqueue_style( 'elusive-webfont', get_template_directory_uri() . '/elusive-iconfont/css/elusive-webfont.css' );

			if ( FastfoodOptions::get_opt( 'fastfood_responsive_layout' ) )
				wp_enqueue_style( 'fastfood-responsive-layout', get_template_directory_uri() . '/css/responsive.css', false, fastfood_get_info( 'version' ), 'screen and (max-width: 1004px)' );

			//google font
			if ( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) ) {
				$gwf_family = 'family=' . urlencode( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) );
				$gwf_subset = FastfoodOptions::get_opt( 'fastfood_google_font_subset' )? '&subset=' . urlencode( str_replace( array(' ','"'), '', FastfoodOptions::get_opt( 'fastfood_google_font_subset' ) ) ) : '';
				$gwf_url = '//fonts.googleapis.com/css?' . $gwf_family . $gwf_subset;
				wp_enqueue_style( 'fastfood-google-fonts', $gwf_url );
			}

		}

		//print style
		wp_enqueue_style( 'fastfood-print-style', get_template_directory_uri() . '/css/print.css', false, fastfood_get_info( 'version' ), 'print' );

	}
}


// get js modules
if ( !function_exists( 'fastfood_get_js_modules' ) ) {
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

		return  apply_filters( 'fastfood_filter_js_modules', $modules );

	}
}

// add scripts
if ( !function_exists( 'fastfood_scripts' ) ) {
	function fastfood_scripts(){

		if ( is_admin() || fastfood_is_mobile() || fastfood_is_printpreview() ) return; //no scripts in admin, print preview, mobile view

		if ( FastfoodOptions::get_opt( 'fastfood_jsani' ) ) {

			//tinynav script
			if ( FastfoodOptions::get_opt( 'fastfood_tinynav' ) ) wp_enqueue_script( 'fastfood-tinynav', get_template_directory_uri().'/js/tinynav/tinynav.min.js', array( 'jquery' ), fastfood_get_info( 'version' ), true );

			$deps = array(
				'jquery',
				'jquery-effects-core',
				'hoverIntent',
			);
			if ( FastfoodOptions::get_opt( 'fastfood_gallery_preview' ) )
				$deps[] = 'thickbox';

			wp_enqueue_script( 'fastfood-script', get_template_directory_uri() . '/js/fastfoodscript.min.js', $deps, fastfood_get_info( 'version' ), true ); //fastfood js

			$data = array(
				'script_modules' => fastfood_get_js_modules(),
				'post_expander_wait' => __( 'Post loading, please wait...', 'fastfood' ),
				'quote_link_info' => esc_attr( __( 'Add selected text as a quote', 'fastfood' ) ),
				'quote_link_alert' => __( 'Nothing to quote. First of all you should select some text...', 'fastfood' ),
			);
			wp_localize_script( 'fastfood-script', 'fastfood_l10n', $data );

		}

	}
}


// add a js-selecting class
if ( !function_exists( 'fastfood_body_class_script' ) ) {
	function fastfood_body_class_script(){

?>
	<script type="text/javascript">
		/* <![CDATA[ */
		(function(){
			var c = document.body.className;
			c = c.replace(/ff-no-js/, 'ff-js');
			document.body.className = c;
		})();
		/* ]]> */
	</script>
<?php

	}
}


//Image EXIF details
function fastfood_exif_details(){

	$m = wp_get_attachment_metadata();

	// convert the shutter speed retrieve from database to fraction
	if ( $m['image_meta']['shutter_speed'] && (1 / $m['image_meta']['shutter_speed']) > 1) {
		if ((number_format((1 / $m['image_meta']['shutter_speed']), 1)) == 1.3
		or number_format((1 / $m['image_meta']['shutter_speed']), 1) == 1.5
		or number_format((1 / $m['image_meta']['shutter_speed']), 1) == 1.6
		or number_format((1 / $m['image_meta']['shutter_speed']), 1) == 2.5){
			$shutter_speed = "1/" . number_format((1 / $m['image_meta']['shutter_speed']), 1, '.', '');
		} else {
			$shutter_speed = "1/" . number_format((1 / $m['image_meta']['shutter_speed']), 0, '.', '');
		}
	}

	$uploaddir = wp_upload_dir();
	$imagesize = size_format( filesize( $uploaddir['basedir'] . '/' . $m['file'] ) );

	// array( LABEL, ORIGINAL_VALUE, READABLE_VALUE )

	if ( $imagesize )
		$image_meta['filesize'] = array( __( 'File Size', 'fastfood' ), $imagesize, $imagesize );

	if ( $m['width'] )
		$image_meta['width'] = array( __( 'Width', 'fastfood' ), $m['width'], $m['width'] . 'px' );

	if ( $m['height'] )
		$image_meta['height'] = array( __( 'Height', 'fastfood' ), $m['height'], $m['height'] . 'px' );

	if ( $m['image_meta']['created_timestamp'] )
		$image_meta['created_timestamp'] = array( __( 'Date Taken', 'fastfood' ), $m['image_meta']['created_timestamp'], date_i18n(get_option('date_format') . ' ' . get_option('time_format'),$m['image_meta']['created_timestamp']) );

	if ( $m['image_meta']['copyright'] )
		$image_meta['copyright'] = array( __( 'Copyright', 'fastfood' ), $m['image_meta']['copyright'], $m['image_meta']['copyright'] );

	if ( $m['image_meta']['credit'] )
		$image_meta['credit'] = array( __( 'Credit', 'fastfood' ), $m['image_meta']['credit'], $m['image_meta']['credit'] );

	if ( $m['image_meta']['title'] )
		$image_meta['title'] = array( __( 'Title', 'fastfood' ), $m['image_meta']['title'], $m['image_meta']['title'] );

	if ( $m['image_meta']['caption'] )
		$image_meta['caption'] = array( __( 'Caption', 'fastfood' ), $m['image_meta']['caption'], $m['image_meta']['caption'] );

	if ( $m['image_meta']['camera'] )
		$image_meta['camera'] = array( __( 'Camera', 'fastfood' ), $m['image_meta']['camera'], $m['image_meta']['camera'] );

	if ( $m['image_meta']['focal_length'] )
		$image_meta['focal_length'] = array( __( 'Focal Length', 'fastfood' ), $m['image_meta']['focal_length'], $m['image_meta']['focal_length'] . 'mm' );

	if ( $m['image_meta']['aperture'] )
		$image_meta['aperture'] = array( __( 'Aperture', 'fastfood' ), $m['image_meta']['aperture'], 'f/' . $m['image_meta']['aperture'] );

	if ( $m['image_meta']['iso'] )
		$image_meta['iso'] = array( __( 'ISO', 'fastfood' ), $m['image_meta']['iso'], $m['image_meta']['iso'] );

	if ( $m['image_meta']['shutter_speed'] )
		$image_meta['shutter_speed'] = array( __( 'Shutter Speed', 'fastfood' ), $m['image_meta']['shutter_speed'], sprintf( __( '%s seconds', 'fastfood' ), $shutter_speed) );

	return  apply_filters( 'fastfood_exif_details', $image_meta );

}


//Display navigation to next/previous post when applicable
if ( !function_exists( 'fastfood_single_nav' ) ) {
	function fastfood_single_nav() {
		global $post;

		if ( ! FastfoodOptions::get_opt( 'fastfood_browse_links' ) ) return;

		$next = get_previous_post();
		$prev = get_next_post();
		$next_title = get_the_title( $next ) ? get_the_title( $next ) : __( 'Previous Post', 'fastfood' );
		$prev_title = get_the_title( $prev ) ? get_the_title( $prev ) : __( 'Next Post', 'fastfood' );

?>
	<div class="nav-single fixfloat">
		<?php if ( $prev ) { ?>
			<span class="nav-previous"><a rel="prev" href="<?php echo get_permalink( $prev ); ?>" title="<?php echo esc_attr(strip_tags( __( 'Next Post', 'fastfood' ) . ': ' . $prev_title ) ); ?>"><?php echo $prev_title; ?><?php echo fastfood_get_the_thumb( $prev->ID, 32, 32, 'tb-thumb-format' ); ?></a></span>
		<?php } ?>
		<?php if ( $next ) { ?>
			<span class="nav-next"><a rel="next" href="<?php echo get_permalink( $next ); ?>" title="<?php echo esc_attr(strip_tags( __( 'Previous Post', 'fastfood' ) . ': ' . $next_title ) ); ?>"><?php echo fastfood_get_the_thumb( $next->ID, 32, 32, 'tb-thumb-format' ); ?><?php echo $next_title; ?></a></span>
		<?php } ?>
	</div><!-- #nav-single -->
<?php

	}
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
			$output .= '<div class="tb-post-details"><span class="post-details-cats">' . __( 'Categories', 'fastfood' ) . ': </span>' . get_the_category_list( $tax_separator ) . '</div>';

		if ( $args['tags'] )
			$tags = get_the_tags() ? get_the_tag_list( '</span>', $tax_separator, '' ) : __( 'No Tags', 'fastfood' ) . '</span>';
			$output .= '<div class="tb-post-details"><span class="post-details-tags">' . __( 'Tags', 'fastfood' ) . ': ' . $tags . '</div>';

		if ( $args['date'] )
			$output .= '<div class="tb-post-details"><span class="post-details-date">' . __( 'Published on', 'fastfood' ) . ': </span><a href="' . get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')) . '">' . get_the_time( get_option( 'date_format' ) ) . '</a></div>';

		if ( ! $args['echo'] )
			return $output;

		echo $output;

	}
}


// get the author badge
function fastfood_author_badge( $author = '', $size = 48 ) {
	global $authordata;

	$author = ( ( ! $author ) && isset( $authordata->ID ) ) ? $authordata->ID : $author;

	$name = get_the_author_meta( 'nickname', $author ); // nickname

	$avatar = get_avatar( get_the_author_meta( 'email', $author ), $size, 'Gravatar Logo', get_the_author_meta( 'user_nicename', $author ) . '-photo' ); // gravatar

	$description = get_the_author_meta( 'description', $author ); // bio

	$author_link = get_author_posts_url($author); // link to author posts

	$author_net = ''; // author social networks
	foreach ( array( 'twitter' => 'Twitter', 'facebook' => 'Facebook', 'googleplus' => 'Google+', 'url' => 'web' ) as $s_key => $s_name ) {
		if ( get_the_author_meta( $s_key, $author ) ) $author_net .= '<a target="_blank" class="url" title="' . esc_attr( sprintf( __('Follow %s on %s', 'fastfood'), $name, $s_name ) ) . '" href="'.get_the_author_meta( $s_key, $author ).'"><img alt="' . $s_key . '" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/' . $s_key . '.png" /></a>';
	}

	$output = '<li class="author-avatar">' . $avatar . '</li>';
	$output .= '<li class="author-name"><a class="fn" href="' . $author_link . '" >' . $name . '</a></li>';
	$output .= $description ? '<li class="author-description note">' . $description . '</li>' : '';
	$output .= $author_net ? '<li class="author-social">' . $author_net . '</li>' : '';

	$output = '<div class="tb-post-details tb-author-bio vcard"><ul>' . $output . '</ul></div>';

	return apply_filters( 'fastfood_filter_author_badge', $output );

}


//get a thumb for a post/page
if ( !function_exists( 'fastfood_get_the_thumb_url' ) ) {
	function fastfood_get_the_thumb_url( $post_id = 0 ){
		global $post;

		if ( !$post_id ) $post_id = $post->ID;

		// has featured image
		if ( get_post_thumbnail_id( $post_id ) )
			return wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );

		$attachments = get_children( array(
			'post_parent'		=> $post_id,
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
			'post_mime_type'	=> 'image',
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'numberposts'		=> 1,
		) );

		//has attachments
		if ( $attachments )
			return wp_get_attachment_thumb_url( key($attachments) );

		//has an hardcoded <img>
		if ( $img = fastfood_get_first_image() )
			return $img['src'];

		//has a generated <img>
		if ( $img = fastfood_get_first_image( false, true) )
			return $img['src'];

		if ( $img = get_header_image() )
			return $img;

		//nothing found
		return '';
	}

}


// display the main menu
function fastfood_main_menu () {

	if ( FastfoodOptions::get_opt('fastfood_primary_menu' ) ) {

?>
	<div id="menu-primary-container" class="menu-container">

		<?php fastfood_hook_menu_top(); ?>

		<?php
			wp_nav_menu( array(
				'container'			=> false,
				'menu_id'			=> 'menu-primary',
				'menu_class'		=> 'nav-menu all-levels',
				'fallback_cb'		=> 'fastfood_pages_menu',
				'theme_location'	=> 'primary',
			) );
		?>

		<?php fastfood_hook_menu_bottom(); ?>

	</div>
<?php

	}

}


// Pages Menu
if ( !function_exists( 'fastfood_pages_menu' ) ) {
	function fastfood_pages_menu() {

?>
	<ul id="primary-menu" class="nav-menu all-levels">

		<?php echo fastfood_add_home_link( $items = '', $args = 'theme_location=primary' ); ?>

		<?php wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted ?>

	</ul>
<?php

	}
}


//add "Home" link
function fastfood_add_home_link( $items = '', $args = null ) {

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

		$homeMenuItem =
				'<li class="menu-item navhome' . $class . '">' .
				$args['before'] .
				'<a href="' . home_url( '/' ) . '" title="' . esc_attr__( 'Home', 'fastfood' ) . '">' .
				$args['link_before'] . __( 'Home', 'fastfood' ) . $args['link_after'] .
				'</a>' .
				$args['after'] .
				'</li>';

		$items = $homeMenuItem . $items;
	}

	return $items;

}


//display the footer content
function fastfood_credits () {

?>
	<div id="credits">

		<?php echo fastfood_copyright(); ?> <strong><?php bloginfo( 'name' ); ?></strong> <?php _e( 'All rights reserved','fastfood' ); ?>

		<?php fastfood_hook_change_view(); ?>

		<?php
			if ( FastfoodOptions::get_opt('fastfood_tbcred' ) ) {
				$output = apply_filters( 'fastfood_filter_credits', sprintf( __( 'Fastfood theme by %s - Powered by %s', 'fastfood' ), '<a target="_blank" href="http://www.twobeers.net/" title="' . esc_attr( __( 'Visit author homepage', 'fastfood' ) . ' @ TwoBeers.net' ) . '">TwoBeers Crew</a>', '<a target="_blank" href="http://wordpress.org/" title="WordPress">WordPress</a>' ) );
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
	if( $copyright_cache ) {
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
		the_widget( $widget, '', fastfood_get_default_widget_args() );
	}

}


// Get first image of a post
if ( !function_exists( 'fastfood_get_first_image' ) ) {
	function fastfood_get_first_image( $post_id = null, $filtered_content = false ) {

		$post = get_post( $post_id );

		$first_image = array( 'img' => '', 'title' => '', 'src' => '' );

		//search the images in post content
		preg_match_all( '/<img[^>]+>/i',$filtered_content ? apply_filters( 'the_content', $post->post_content ): $post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_image['img'] = $result[0][0];
			//get the title (if any)
			preg_match_all( '/(title)=(["|\'][^"|\']*["|\'])/i',$first_image['img'], $title );
			if ( isset( $title[2][0] ) ){
				$first_image['title'] = str_replace( '"','',$title[2][0] );
			}
			//get the path
			preg_match_all( '/(src)=(["|\'][^"|\']*["|\'])/i',$first_image['img'], $src );
			if ( isset( $src[2][0] ) ){
				$first_image['src'] = str_replace( array( '"', '\''),'',$src[2][0] );
			}
			return $first_image;
		} else {
			return false;
		}

	}
}


// Get first link of a post
if ( !function_exists( 'fastfood_get_first_link' ) ) {
	function fastfood_get_first_link() {
		global $post;

		$first_link = array( 'anchor' => '', 'title' => '', 'href' => '', 'text' => '' );

		//search the link in post content
		preg_match_all( "/<a\b[^>]*>(.*?)<\/a>/i",$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_link['anchor'] = $result[0][0];
			$first_link['text'] = isset( $result[1][0] ) ? $result[1][0] : '';
			//get the title (if any)
			preg_match_all( '/(title)=(["\'][^"]*["\'])/i',$first_link['anchor'], $title );
			$first_link['title'] = isset( $title[2][0] ) ? str_replace( array('"','\''),'',$title[2][0] ) : '';
			//get the path
			preg_match_all( '/(href)=(["\'][^"]*["\'])/i',$first_link['anchor'], $href );
			$first_link['href'] = isset( $href[2][0] ) ? str_replace( array('"','\''),'',$href[2][0] ) : '';
			return $first_link;
		} else {
			return false;
		}

	}
}


// Get first blockquote words
if ( !function_exists( 'fastfood_get_blockquote' ) ) {
	function fastfood_get_blockquote() {
		global $post;

		$first_quote = array( 'quote' => '', 'cite' => '' );

		//search the blockquote in post content
		preg_match_all( '/<blockquote\b[^>]*>([\w\W]*?)<\/blockquote>/',$post->post_content, $blockquote );
		//grab the first one
		if ( isset( $blockquote[0][0] ) ){
			$first_quote['quote'] = strip_tags( $blockquote[0][0] );
			$words = explode( " ", $first_quote['quote'], 6 );
			if ( count( $words ) == 6 ) $words[5] = '...';
			$first_quote['quote'] = implode( ' ', $words );
			preg_match_all( '/<cite>([\w\W]*?)<\/cite>/',$blockquote[0][0], $cite );
			$first_quote['cite'] = ( isset( $cite[1][0] ) ) ? $cite[1][0] : '';
			return $first_quote;
		} else {
			return false;
		}

	}
}


// Get first gallery
if ( !function_exists( 'fastfood_get_shortcode' ) ) {
	function fastfood_get_shortcode( $content = '', $tag = 'no_shortcode' ) {

		$pattern = get_shortcode_regex();

		if (   preg_match_all( '/'. $pattern .'/s', $content, $matches )
			&& array_key_exists( 2, $matches )
			&& in_array( $tag, $matches[2] ) ) // gallery shortcode is being used
		{
			$key = array_search( $tag, $matches[2] );
			$attrs = shortcode_parse_atts( $matches['3'][$key] );
			return $attrs;
		}

		return false;

	}
}


// run the gallery preview
function fastfood_gallery_preview( $post = null ) {

	if ( ! $post = get_post( $post ) )
		return false;

	if( false === $attr = fastfood_get_shortcode( $post->post_content, 'gallery' ) )
		return false;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract( shortcode_atts( array(
		'order'			=> 'ASC',
		'orderby'		=> 'menu_order ID',
		'id'			=> $post->ID,
		'itemtag'		=> 'dl',
		'icontag'		=> 'dt',
		'captiontag'	=> 'dd',
		'columns'		=> 3,
		'size'			=> 'thumbnail',
		'include'		=> '',
		'exclude'		=> ''
	), $attr) );

	$id = intval( $id );

	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( ! empty( $include ) ) {
		$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $exclude ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
	}

	if ( empty( $attachments ) )
		return false;

	$images_count = count( $attachments );
	$first_image = array_shift( $attachments );
	$other_imgs = array_slice( $attachments, 0, 4 );

	$output = '<span class="gallery-item size-medium">' . wp_get_attachment_image( $first_image->ID, 'large' ) . '</span><!-- .gallery-item -->';

	$output .= '<div class="thumbnail-wrap">';
	foreach ( $other_imgs as $image )
		$output .= '<div class="gallery-item size-thumbnail">' . wp_get_attachment_image( $image->ID, 'thumbnail' ) . '</div>';
	$output .= '</div>';

	$output .= '<p class="info">';
	$output .= sprintf( _n( 'This gallery contains %s image</a>', 'This gallery contains %s images</a>', $images_count, 'fastfood' ),
		fastfood_build_link( array(
			'href'		=> get_permalink( $id ),
			'text'		=> '<strong>' . number_format_i18n( $images_count ) . '</strong>',
			'title'		=> __( 'View gallery', 'fastfood' ),
			'rel'		=> 'gallery',
		) )
	);
	$output .= '</p>';

	$output = apply_filters( 'fastfood_gallery_preview', $output );

	$output = '<div class="gallery gallery-preview">' . $output . '</div>';

	echo $output;

	return true;

}


//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
function fastfood_friendly_date() {

	$postTime = get_the_time('U');
	$currentTime = time();
	$timeDifference = $currentTime - $postTime;

	$minInSecs = 60;
	$hourInSecs = 3600;
	$dayInSecs = 86400;
	$monthInSecs = $dayInSecs * 31;
	$yearInSecs = $dayInSecs * 366;

	//if over 2 years
	if ($timeDifference > ($yearInSecs * 2)) {
		$dateWithNiceTone = __( 'quite a long while ago...', 'fastfood' );

	//if over a year
	} else if ($timeDifference > $yearInSecs) {
		$dateWithNiceTone = __( 'over a year ago', 'fastfood' );

	//if over 2 months
	} else if ($timeDifference > ($monthInSecs * 2)) {
		$num = round($timeDifference / $monthInSecs);
		$dateWithNiceTone = sprintf(__('%s months ago', 'fastfood' ),$num);

	//if over a month
	} else if ($timeDifference > $monthInSecs) {
		$dateWithNiceTone = __( 'a month ago', 'fastfood' );

	//if more than 2 days ago
	} else {
		$htd = human_time_diff( get_the_time('U'), current_time('timestamp') );
		$dateWithNiceTone = sprintf(__('%s ago', 'fastfood' ), $htd );
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
		$the_parent_link = __( 'Upper page: ', 'fastfood' ) . fastfood_build_link( array(
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
		$the_childrens_list = __( 'Lower pages: ', 'fastfood' ) . implode( ', ' , $the_childrens_list );
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


// display the post title with the featured image
	function fastfood_featured_title( $args = '' ) {
		global $post;

		$defaults = array(
			'alternative'	=> '',
			'fallback'		=> '',
			'featured'		=> true,
			'href'			=> get_permalink(),
			'target'		=> '',
			'title'			=> the_title_attribute( array( 'echo' => 0 ) ),
			'echo'			=> 1,
		);
		$args = wp_parse_args( $args, $defaults );

		if ( FastfoodOptions::get_opt( 'fastfood_hide_frontpage_title' ) && is_page() && is_front_page() ) return;

		if ( FastfoodOptions::get_opt( 'fastfood_hide_pages_title' ) && is_page() ) return;

		if ( FastfoodOptions::get_opt( 'fastfood_hide_posts_title' ) && is_single() ) return;

		if ( $selected_ids = preg_replace( array( '/[^0-9 ,]/' ,'/[ ]/' ), array( '', ',' ), FastfoodOptions::get_opt( 'fastfood_hide_selected_entries_title', '' ) ) ) {
			$selected_ids = explode( ',', $selected_ids );
			if ( in_array( $post->ID, $selected_ids ) ) return;
		}

		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();
		$post_title = $post_title ? $post_title : $args['fallback'];
		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';
		if ( !FastfoodOptions::get_opt( 'fastfood_featured_title' ) ) $args['featured'] = false;
		$thumb = ( $args['featured'] && has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, array( FastfoodOptions::get_opt( 'fastfood_featured_title_size', 10 ), FastfoodOptions::get_opt( 'fastfood_featured_title_size', 10 ) ) ) : '';
		$title_class = $thumb ? 'entry-title storytitle featured-' . esc_attr( FastfoodOptions::get_opt( 'fastfood_featured_title_size', 10 ) ) : 'storytitle';
		$title_content = is_singular() ? $thumb . $post_title : '<a title="' . $args['title'] . '" href="' . $args['href'] . '"' . $link_target . ' rel="bookmark">' . $thumb . $post_title . '</a>';
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
	if ( ! FastfoodOptions::get_opt( 'fastfood_xinfos_global' ) ) return; //xinfos globally disabled
	if ( is_page() && is_front_page() && ! FastfoodOptions::get_opt( 'fastfood_xinfos_on_front' ) ) return; // is front page
	if ( is_page() && ! FastfoodOptions::get_opt( 'fastfood_xinfos_on_page' ) ) return;
	if ( is_single() && ! FastfoodOptions::get_opt( 'fastfood_xinfos_on_post' ) ) return;
	if ( !is_singular() && ! FastfoodOptions::get_opt( 'fastfood_xinfos_on_list' ) ) return;

	if ( is_page() ) {
		$page_cd_nc = ( !comments_open() && !have_comments() ); //true if page with comments disabled and no comments
		if ( $page_cd_nc ) $args['comms'] = false;
		$args['auth'] = false;
		$args['date'] = false;
		$args['tags'] = false;
		$args['cats'] = false;
	}

	$post_author = ( ( $args['auth'] === true ) || ( $args['auth'] === 1 ) ) ? '<a class="vcard fn author" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : '<span class="vcard fn author">' . $args['auth'] . '</span>';
	$post_author =  '<i class="el-icon-user"></i> ' . $post_author;

	$categories = __( 'Categories', 'fastfood' ) . ': ' . get_the_category_list(', ');

	$tags = __( 'Tags', 'fastfood' ) . ': ' . ( ( get_the_tags() ) ? get_the_tag_list( '', ', ' , '') : __( 'No Tags', 'fastfood' ) );

	$comments = __( 'Comments', 'fastfood' ) . ': ' . fastfood_get_comments_link();

	$date = sprintf( __( 'Published on: %s', 'fastfood' ), '<b class="published" title="' . get_the_time( 'c' ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</b>' );

	$hierarchy = fastfood_multipages();

	if ( is_page() && ! $args['comms'] && ! $hierarchy ) return;

	if ( !$args['list_view'] ) {

?>
	<div class="meta_container">

		<div class="meta top_meta">

			<?php if ( $args['cats'] ) { ?>
				<div class="metafield metafield-categories">
					<div class="metafield_content">
						<?php echo $categories; ?>
					</div>
					<i class="metafield_trigger el-icon-folder-open"></i>
				</div>
			<?php }?>

			<?php if ( $args['tags'] ) { ?>
				<div class="metafield metafield-tags">
					<div class="metafield_content">
						<?php echo $tags; ?>
					</div>
					<i class="metafield_trigger el-icon-tags"></i>
				</div>
			<?php }?>

			<?php if ( $args['comms'] ) { ?>
				<div class="metafield metafield-comments">
					<div class="metafield_content">
						<?php echo $comments; ?>
					</div>
					<i class="metafield_trigger el-icon-comment"></i>
				</div>
			<?php } ?>

			<?php if ( $args['date'] ) { ?>
				<div class="metafield metafield-date">
					<div class="metafield_content">
						<?php echo $date; ?>
					</div>
					<i class="metafield_trigger el-icon-calendar"></i>
				</div>
			<?php } ?>

			<?php if ( $args['hiera'] && $hierarchy ) { ?>
				<div class="metafield metafield-hierarchy">
					<div class="metafield_content">
						<?php echo $hierarchy; ?>
					</div>
					<i class="metafield_trigger el-icon-fork"></i>
				</div>
			<?php } ?>

			<?php if ( get_edit_post_link() ) { ?>
				<div class="metafield metafield-edit">
					<?php
						echo fastfood_build_link( array(
							'href'		=> get_edit_post_link(),
							'text'		=> '<span class="screen-reader-text">' . __( 'Edit', 'fastfood' ) . '</span>',
							'title'		=> __( 'Edit', 'fastfood' ),
							'class'		=> 'metafield_trigger el-icon-pencil',
							'rel'		=> 'nofollow',
						) );
					?>
				</div>
			<?php } ?>

			<?php if ( $args['auth'] ) { ?>
				<div class="metafield metafield-author">
					<span class="metafield_trigger"><?php echo $post_author; ?></span>
				</div>
			<?php } ?>

		</div>

	</div>
<?php

	} else {

?>
	<div class="meta">
		<?php if ( $args['auth'] ) echo $post_author . '<br />'; ?>
		<?php if ( $args['date'] ) echo $date . '<br />'; ?>
		<?php if ( $args['comms'] ) echo $comments . '<br />'; ?>
		<?php if ( $args['tags'] ) echo $tags . '<br />'; ?>
		<?php if ( $args['cats'] ) echo $categories . '<br />'; ?>
		<?php edit_post_link( __( 'Edit', 'fastfood' ) ); ?>
	</div>
<?php

	}
}


	/**
	 * return the previous and next image IDs
	 *
	 * @param	mixed	$post		(optional) the post (ID or object)
	 * @return	array				the previous and next image IDs array('prev','next')
	*/
function fastfood_get_prevnext_images( $post = null ) {

	$out = array( 'prev' => '', 'next' => '' );

	if ( ! $post = get_post( $post ) )
		return $out;

	if ( ! wp_attachment_is_image( $post->ID ) )
		return $out;

	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );

	foreach ( $attachments as $key => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}

	$prev_k = $key - 1;
	$next_k = $key + 1;

	if ( isset( $attachments[ $prev_k ] ) )
		$out['prev'] = $attachments[ $prev_k ]->ID;

	if ( isset( $attachments[ $next_k ] ) )
		$out['next'] = $attachments[ $next_k ]->ID;

	return $out;
}


// images navigation links
function fastfood_navigate_images( $post = null ) {

	if ( ! $post = get_post( $post ) )
		return;

	if ( ! wp_attachment_is_image( $post->ID ) )
		return;

	$images = fastfood_get_prevnext_images( $post->ID );

	if ( $images['prev'] )
		$images['prev'] = fastfood_build_link( array(
			'href'		=> get_attachment_link( $images['prev'] ),
			'text'		=> '<i class="el-icon-chevron-left"></i> ' . wp_get_attachment_image( $images['prev'], array( 70, 70 ) ),
			'class'		=> 'size-thumbnail',
			'rel'		=> 'prev',
		) );

	if ( $images['next'] )
		$images['next'] = fastfood_build_link( array(
			'href'		=> get_attachment_link( $images['next'] ),
			'text'		=> wp_get_attachment_image( $images['next'], array( 70, 70 ) ) . '<i class="el-icon-chevron-right"></i>',
			'class'		=> 'size-thumbnail',
			'rel'		=> 'next',
		) );

?>
	<div class="img-navi">

		<?php echo $images['prev']; ?>
		<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 70, 70 ) ); ?></span>
		<?php echo $images['next']; ?>

	</div>
<?php

}


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


// comments-are-closed message when post type supports comments and we're not on a page
function fastfood_comments_closed() {
	if ( ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) {

?>
	<div class="comment_tools"><?php _e( 'Comments are closed.', 'fastfood' ); ?></div>
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

		if ( ! FastfoodOptions::get_opt( 'fastfood_post_view_aside') ) $terms[] = 'post-format-aside';
		if ( ! FastfoodOptions::get_opt( 'fastfood_post_view_status' ) ) $terms[] = 'post-format-status';

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

	if ( ! FastfoodOptions::get_opt( 'fastfood_breadcrumb' ) ) {
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


// get the post format string
function fastfood_get_post_format( $id ) {

	if ( post_password_required() )
		$format = 'protected';
	else
		$format = ( FastfoodOptions::get_opt( 'fastfood_post_formats_' . get_post_format( $id ) ) ) ? get_post_format( $id ) : '' ;

	return $format;

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


// archives pages navigation
function fastfood_navigate_archives() {
	global $paged, $wp_query;

	if ( !$paged ) $paged = 1;

?>
	<div class="navigation_links navigate_archives">
	<?php
	if ( ! apply_filters( 'fastfood_filter_navigation_archives', false ) ) {
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

	if ( is_single() || ! FastfoodOptions::get_opt( 'fastfood_postexcerpt' ) ) {

?>
	<div class="fixfloat">
		<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages', 'fastfood' ) . ':&after=</div><br class="fixfloat" />' ); ?>
	</div>
<?php

	} else
		echo '<br class="fixfloat" />';

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
	$css_class = ( ! empty( $css_class ) ) ? ' class="' . esc_attr( $css_class ) . '"' : '';

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
	global $content_width;

	// Register localization support
	load_theme_textdomain( 'fastfood', get_template_directory() . '/languages' );

	FastfoodOptions::init();

	// Theme uses wp_nav_menu() in three location
	register_nav_menus( array( 'primary'	=> __( 'Main Navigation Menu', 'fastfood' ) ) );
	register_nav_menus( array( 'secondary1'	=> __( 'Secondary Navigation Menu #1', 'fastfood' ) ) );
	register_nav_menus( array( 'secondary2'	=> __( 'Secondary Navigation Menu #2', 'fastfood' ) ) );

	// Register Features Support
	add_theme_support( 'automatic-feed-links' );

	// Thumbnails support
	add_theme_support( 'post-thumbnails' );

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
	if ( ! empty( $pformats ) )
		add_theme_support( 'post-formats', $pformats );

	$content_width = 540;

}


// the custom header style - add style customization to page - gets included in the site header
function fastfood_custom_css(){

?>
	<style type="text/css">
		body {
			font-size: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_font_size' ) ); ?>;
	<?php if ( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) && FastfoodOptions::get_opt( 'fastfood_google_font_body' ) ) { ?>
			font-family: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) ); ?>;
	<?php } else { ?>
			font-family: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_font_family' ) ); ?>;
	<?php } ?>
		}
	<?php if ( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) && FastfoodOptions::get_opt( 'fastfood_google_font_post_title' ) ) { ?>
		h2.storytitle {
			font-family: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) ); ?>;
		}
	<?php } ?>
	<?php if ( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) && FastfoodOptions::get_opt( 'fastfood_google_font_post_content' ) ) { ?>
		.storycontent {
			font-family: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_google_font_family' ) ); ?>;
		}
	<?php } ?>
		a {
			color: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_colors_link' ) ); ?>;
		}
		textarea:hover,
		.bbpress .wp-editor-area:hover,
		input[type=text]:hover,
		input[type=password]:hover,
		textarea:focus,
		input[type=text]:focus,
		input[type=password]:focus {
			border: 1px solid <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_colors_link_hover' ) ); ?>;
		}
		button,
		input[type=button],
		input[type=submit],
		input[type=reset],
		#posts_content #infinite-handle span {
			background: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_colors_link' ) ); ?>;
		}
		button:hover,
		input[type=button]:hover,
		input[type=submit]:hover,
		input[type=reset]:hover,
		#posts_content #infinite-handle span:hover {
			background: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_colors_link_hover' ) ); ?>;
		}
		a:hover,
		.current-menu-item a:hover,
		.current_page_item a:hover,
		.current-cat a:hover,
		#mainmenu .menu-item-parent:hover > a:after {
			color: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_colors_link_hover' ) ); ?>;
		}
		.current-menu-item > a,
		.current_page_item > a,
		.current-cat > a,
		.crumbs .last,
		.crumbs li:last-of-type,
		.menu-item-parent:hover > a:after,
		.current-menu-ancestor > a:after,
		.current-menu-parent > a:after,
		.current_page_parent > a:after,
		.current_page_ancestor > a:after,
		#navxt-crumbs li.current_item,
		li.current-menu-ancestor > a:after {
			color: <?php echo esc_attr( FastfoodOptions::get_opt( 'fastfood_colors_link_sel' ) ); ?>;
		}
		<?php
			if ( FastfoodOptions::get_opt( 'fastfood_custom_css' ) )
				echo wp_strip_all_tags( FastfoodOptions::get_opt( 'fastfood_custom_css' ) );
		?>
	</style>
	<!-- InternetExplorer really sucks! -->
	<!--[if lte IE 8]>
	<style type="text/css">
		.js-res {
			border:1px solid #333333 !important;
		}
		.menuitem_1ul > ul > li {
			margin-right:-2px;
		}
		.attachment-thumbnail,
		.wp-caption img,
		.gallery-item img,
		.storycontent img.size-full,
		.storycontent img.attachment-full,
		.widget img.size-full,
		.widget img.attachment-full,
		.widget img.size-medium,
		.widget img.attachment-medium {
			width:auto;
		}
		.gallery-thumb img,
		#main .avatar {
			max-width:700px;
		}
	}

	</style>
	<![endif]-->
<?php

}


//add a default gravatar
function fastfood_addgravatar( $avatar_defaults ) {
	$myavatar = get_template_directory_uri() . '/images/user.png';
	$avatar_defaults[$myavatar] = __( 'Fastfood Default Gravatar', 'fastfood' );

	return $avatar_defaults;
}


//add a fix for embed videos overlying quickbar
function fastfood_wmode_transparent( $html, $url = null, $attr = null ) {

	if ( strpos( $html, '<embed ' ) !== false ) {

		$html = str_replace( '</param><embed', '</param><param name="wmode" value="transparent"></param><embed', $html);
		$html = str_replace( '<embed ', '<embed wmode="transparent" ', $html);

	} elseif ( strpos ( $html, 'feature=oembed' ) !== false ) {

		$html = str_replace( 'feature=oembed', 'feature=oembed&wmode=transparent', $html );

	}

	return $html;

}


//wrap the post content with a <blockquote> if no <blockquote>s found
function fastfood_quote_content( $content ) {

	/* Check if we're displaying a 'quote' post. */
	if ( has_post_format( 'quote' ) && FastfoodOptions::get_opt( 'fastfood_post_formats_quote' ) ) {

		/* Match any <blockquote> elements. */
		preg_match( '/<blockquote.*?>/', $content, $matches );

		/* If no <blockquote> elements were found, wrap the entire content in one. */
		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;
}


function fastfood_img_caption_shortcode_width( $caption_width, $atts ) {
	return $atts['width'];
}


function fastfood_shortcode_atts_gallery( $out, $pairs, $atts ) {
	if ( FastfoodOptions::get_opt( 'fastfood_force_link_to_image' ) && $out['link'] !=='none' )
		$out['link'] = 'file';

	return $out;
}

//add attachment description to thickbox
function fastfood_get_attachment_link( $markup = '', $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false ) {

	$id = intval( $id );
	$_post = get_post( $id );

	if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || ! $url = wp_get_attachment_url( $_post->ID ) )
		return __( 'Missing Attachment','fastfood' );

	if ( $permalink )
		$url = get_attachment_link( $_post->ID );

	$post_title = esc_attr( $_post->post_excerpt ? $_post->post_excerpt : $_post->post_title );

	if ( $text )
		$link_text = $text;
	elseif ( $size && 'none' != $size )
		$link_text = wp_get_attachment_image( $id, $size, $icon );
	else
		$link_text = '';

	if ( trim( $link_text ) == '' )
		$link_text = $_post->post_title;

	return "<a href='$url' title='$post_title'>$link_text</a>";

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

// Add specific CSS class by filter
function fastfood_body_classes( $classes ) {

	$classes[] = 'ff-no-js';

	if ( FastfoodOptions::get_opt( 'fastfood_tinynav' ) ) $classes[] = 'tinynav-support';
	if ( FastfoodOptions::get_opt( 'fastfood_basic_animation_captions' ) ) $classes[] = 'fading-captions';

	return $classes;

}


/**
 * Add parent class to wp_page_menu top parent list items
 */
function fastfood_add_parent_class( $css_class, $page, $depth, $args ) {

	if ( ! empty( $args['has_children'] ) && $depth == 0 )
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
			if ( ! $item->menu_item_parent )
				$item->classes[] = 'menu-item-parent';
		}
	}

	return $items;
}


// the search form filter
function fastfood_search_form() {
	static $counter = 0;

	$counter++;

	$output = '
		<form method="get" id="searchform-'. $counter . '" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
			<input title="' . __( 'Search', 'fastfood' ) . '" type="text" class="field" name="s" id="s-'. $counter . '" value="" />
		</form>
	';

	return $output;

}


// wrap the categories count with a span
function fastfood_wrap_categories_count( $output ) {
	$pattern = '/<\/a>\s(\(\d+\))/i';
	$replacement = ' <span class="details">$1</span></a>';
	return preg_replace( $pattern, $replacement, $output );
}


//filters wp_title()
function fastfood_filter_wp_title( $title ) {

	if ( is_single() && empty( $title ) ) {
		$_post = get_queried_object();
		$title = fastfood_title_tags_filter( '', $_post->ID ) . ' &laquo; ';
	}
	// Get the Site Name
	$site_name = get_bloginfo( 'name' );
	// Append name
	$filtered_title = $title . $site_name;
	// If site front page, append description
	if ( is_front_page() ) {
		// Get the Site Description
		$site_description = get_bloginfo( 'description' );
		// Append Site Description to title
		$filtered_title .= ' - ' . $site_description;
	}
	// Return the modified title
	return $filtered_title;

}


// add links to admin bar
function fastfood_admin_bar_plus() {
	global $wp_admin_bar;
	if ( !current_user_can( 'edit_theme_options' ) || !is_admin_bar_showing() )
		return;
	$add_menu_meta = array(
		'target'	=> '_blank'
	);
	$wp_admin_bar->add_menu( array(
		'id'		=> 'ff_theme_options',
		'parent'	=> 'appearance',
		'title'		=> __( 'Theme Options', 'fastfood' ),
		'href'		=> get_admin_url() . 'themes.php?page=fastfood_theme_options',
		'meta'		=> $add_menu_meta
	) );
}


// filters comments_form() default arguments
function fastfood_comment_form_defaults( $defaults ) {

	$defaults['comment_field']			= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
	$defaults['label_submit']			= __( 'Say It!','fastfood' );
	$defaults['title_reply']			= __( 'Leave a comment','fastfood' );

	return $defaults;

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


//add a video player using HTML5
if ( !function_exists( 'fastfood_video_player' ) ) {
	function fastfood_video_player() {

		$embed_defaults = wp_embed_defaults();
		$file = wp_get_attachment_url();
		$mime = get_post_mime_type();
		$mime_type = explode( '/', $mime );

		if ( isset( $mime_type[0] ) && $mime_type[0] == 'video' ) {

?>
	<div class="ff-media-player">
		<video controls="">
			<source src="<?php echo $file;?>" />
			<span class="ff-player-notice"><?php _e( 'this video type is not supported by your browser', 'fastfood' ); ?></span>
		</video>
	</div>
<?php

		}

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

		return sprintf( '<a href="%s"%s>%s</a>',
			$args['href'],
			$attributes,
			$args['text']
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
