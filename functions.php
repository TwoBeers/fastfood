<?php
/**** begin theme hooks ****/
// Tell WordPress to run fastfood_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'fastfood_setup' );
// Tell WordPress to run fastfood_default_options()
add_action( 'admin_init', 'fastfood_default_options' );
// Register sidebars by running fastfood_widget_area_init() on the widgets_init hook
add_action( 'widgets_init', 'fastfood_widget_area_init' );
// Add stylesheets
add_action( 'wp_print_styles', 'fastfood_stylesheet' );
// Add js animations
add_action( 'template_redirect', 'fastfood_scripts' );
// Add custom category page
add_action( 'template_redirect', 'fastfood_allcat' );
// mobile redirect
add_action( 'template_redirect', 'fastfood_mobile' );
// Add custom menus
add_action( 'admin_menu', 'fastfood_create_menu' );
// post expander ajax request
add_action('init', 'fastfood_post_expander_activate');
// gallery slide ajax request
add_action('init', 'fastfood_gallery_slide_activate');
// localize javascripts
add_action( 'wp_head', 'fastfood_localize_js' );
// Custom filters
add_filter( 'the_content', 'fastfood_content_replace' );
add_filter( 'excerpt_length', 'fastfood_new_excerpt_length' );
add_filter( 'get_comment_author_link', 'fastfood_add_quoted_on' );
add_filter('user_contactmethods','fastfood_new_contactmethods',10,1);
add_filter('widget_text', 'do_shortcode');
/**** end theme hooks ****/

// load theme options in $fastfood_opt variable, globally retrieved in php files
$fastfood_opt = get_option( 'fastfood_options' );

// check if is mobile browser
$ff_is_mobile_browser = fastfood_mobile_device_detect();

function fastfood_mobile_device_detect() {
	global $fastfood_opt;
	if ( !isset($_SERVER['HTTP_USER_AGENT']) ) return false;
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ( ( !isset( $fastfood_opt['fastfood_mobile_css'] ) || ( $fastfood_opt['fastfood_mobile_css'] == 1) ) && preg_match( '/(ipad|ipod|iphone|android|opera mini|blackberry|palm|symbian|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i' , $user_agent ) ) { // there were other words for mobile detecting but this is enought ;-)
		return true;
	} else {
		return false;
	}
}

// check if is ie6
$ff_is_ie6 = fastfood_ie6_detect();

function fastfood_ie6_detect() {
if ( isset($_SERVER['HTTP_USER_AGENT']) && ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false ) && !( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false ) ) {
		return true;
	} else {
		return false;
	}
}


// Set the content width based on the theme's design
if ( ! isset( $content_width ) ) {
	if ( ! $ff_is_mobile_browser ) {
		$content_width = 560;
	} else {
		$content_width = 300;
	}
}

//complete options array, with type, defaults values, description, infos and required option
function fastfood_get_coa() {
	$fastfood_coa = array(
		'fastfood_qbar' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( 'sliding menu','fastfood' ),'info'=>__('[default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_qbar_user' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- user','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_qbar_minilogin' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '---- mini login','fastfood' ),'info'=>__( 'a small login form in the user panel [default = enabled]','fastfood' ),'req'=>'fastfood_qbar_user' ),
		'fastfood_qbar_reccom' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- recent comments','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_qbar_cat' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- categories','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_qbar_recpost' => array( 'group' =>'quickbar', 'type' =>'chk', 'default'=>1,'description'=>__( '-- recent posts','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_post_formats' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( 'post formats support','fastfood' ),'info'=>__('[default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_post_formats_gallery' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- "gallery" format','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_post_formats' ),
		'fastfood_post_formats_aside' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- "aside" format','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_post_formats' ),
		'fastfood_post_formats_status' => array( 'group' =>'postformats', 'type' =>'chk', 'default'=>1,'description'=>__( '-- "status" format','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_post_formats' ),
		'fastfood_postexcerpt' => array( 'group' =>'content', 'type' =>'chk', 'default'=>0,'description'=>__( 'content summary','fastfood' ),'info'=>__( 'use the summary instead of content in posts overview [default = disabled]','fastfood' ),'req'=>'' ),
		'fastfood_post_view_aside' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '"aside" posts','fastfood' ),'info'=>__( 'show aside posts on overview [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_post_view_status' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( '"status" posts','fastfood' ),'info'=>__( 'show status posts on overview [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_share_this' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( 'share this content','fastfood' ),'info'=>__( 'show share links after the post content [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_exif_info' => array( 'group' =>'content', 'type' =>'chk', 'default'=>1,'description'=>__( 'images informations', 'fastfood' ),'info'=>__( 'show EXIF informations on image attachments [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_on_list' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( 'info in overview', 'fastfood' ),'info'=>__( 'show details (author, date, tags, etc) in posts overview [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_on_page' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( 'info in pages', 'fastfood' ),'info'=>__( 'show details (author, date, tags, etc) in pages [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_on_post' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( 'info in posts', 'fastfood' ),'info'=>__( 'show details (author, date, tags, etc) in posts [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_static' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>0,'description'=>__( '-- static info', 'fastfood' ),'info'=>__( 'show details as a static list (not dropdown animated) [default = disabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_byauth' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post author', 'fastfood' ),'info'=>__( 'show author on posts details [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_date' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post date', 'fastfood' ),'info'=>__( 'show date on posts details [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_comm' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post comments', 'fastfood' ),'info'=>__( 'show comments on post/page details [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_tag' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post tags', 'fastfood' ),'info'=>__( 'show tags on posts details [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_xinfos_cat' => array( 'group' =>'postinfo', 'type' =>'chk', 'default'=>1,'description'=>__( '-- post categories', 'fastfood' ),'info'=>__( 'show categories on posts details [default = enabled]', 'fastfood' ),'req'=>'' ),
		'fastfood_post_expand' => array( 'group' =>'javascript', 'type' =>'chk', 'default'=>1,'description'=>__( 'post expander','fastfood' ),'info'=>__( 'expands a post to show the full contents when the reader clicks the "Read more..." link [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_gallery_preview' => array( 'group' =>'javascript', 'type' =>'chk', 'default'=>1,'description'=>__( 'gallery preview','fastfood' ),'info'=>__( 'load gallery images on fly [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_jsani' => array( 'group' =>'javascript', 'type' =>'chk', 'default'=>1,'description'=>__( 'javascript animations','fastfood' ),'info'=>__( 'try disable animations if you encountered problems with javascript [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_rsidebpages' => array( 'group' =>'sidebar', 'type' =>'chk', 'default'=>0,'description'=>__( 'sidebar on pages','fastfood' ),'info'=>__( 'show right sidebar on pages [default = disabled]','fastfood' ),'req'=>'' ),
		'fastfood_rsidebposts' => array( 'group' =>'sidebar', 'type' =>'chk', 'default'=>0,'description'=>__( 'sidebar on posts','fastfood' ),'info'=>__( 'show right sidebar on posts [default = disabled]','fastfood' ),'req'=>'' ),
		'fastfood_colors_link' => array( 'group' =>'colors', 'type' =>'col', 'default'=>'#D2691E','description'=>__( 'links','fastfood' ),'info'=>__('[default = #D2691E]','fastfood' ),'req'=>'' ),
		'fastfood_colors_link_hover' => array( 'group' =>'colors', 'type' =>'col', 'default'=>'#FF4500','description'=>__( 'highlighted links','fastfood' ),'info'=>__('[default = #FF4500]','fastfood' ),'req'=>'' ),
		'fastfood_colors_link_sel' => array( 'group' =>'colors', 'type' =>'col', 'default'=>'#CCCCCC','description'=>__( 'selected links','fastfood' ),'info'=>__('[default = #CCCCCC]','fastfood' ),'req'=>'' ),
		'fastfood_cust_comrep' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'custom comment reply form','fastfood' ),'info'=>__( 'custom floating form for post/reply comments [default = enabled]','fastfood' ),'req'=>'fastfood_jsani' ),
		'fastfood_editor_style' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'editor style', 'fastfood' ),'info'=>__( "add style to the editor in order to write the post exactly how it will appear on the site [default = enabled]", 'fastfood' ),'req'=>'' ),
		'fastfood_mobile_css' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'mobile support','fastfood' ),'info'=>__( 'use a dedicated style in mobile devices [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_wpadminbar_css' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'custom adminbar style','fastfood' ),'info'=>__( 'style integration with the theme for admin bar [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_head_h' => array( 'group' =>'other', 'type' =>'sel', 'default'=>'120px', 'options'=>array( '120px', '180px', '240px', '300px' ), 'description'=>__( 'Header height','fastfood' ),'info'=>__( '[default = 120px]','fastfood' ),'req'=>'' ),
		'fastfood_head_link' => array( 'group' =>'other', 'type' =>'chk', 'default'=>0,'description'=>__( 'linked header','fastfood' ),'info'=>sprintf( __( "use the header image as home link. The <a href=\"%s\">header image</a> must be set. If enabled, the site title and description are hidden [default = disabled]", 'fastfood' ), get_admin_url() . 'themes.php?page=custom-header' ), 'req'=>'' ),
		'fastfood_font_size' => array( 'group' =>'other', 'type' =>'sel', 'default'=>'11px', 'options'=>array('10px','11px','12px','13px','14px'), 'description'=>__( 'font size','fastfood' ),'info'=>__( '[default = 11px]','fastfood' ),'req'=>'' ),
		'fastfood_custom_bg' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'custom background','fastfood' ),'info'=>sprintf( __( "use the enhanced custom background page instead of the standard one. Disable it if the <a href=\"%s\">custom background page</a> works weird [default = enabled]", 'fastfood' ), get_admin_url() . 'themes.php?page=custom-background' ), 'req'=>'' ),
		'fastfood_navbuttons' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'navigation buttons', 'fastfood' ),'info'=>__( "the fixed navigation bar on the right. Note: Is strongly recommended to keep it enabled [default = enabled]", 'fastfood' ),'req'=>'' ),
		'fastfood_tbcred' => array( 'group' =>'other', 'type' =>'chk', 'default'=>1,'description'=>__( 'theme credits','fastfood' ),'info'=>__( "please, don't hide theme credits [default = enabled]",'fastfood' ),'req'=>'' )
	);
	return $fastfood_coa;
}

// get theme version
if ( get_theme( 'Fastfood' ) ) {
	$fastfood_current_theme = get_theme( 'Fastfood' );
	$fastfood_version = $fastfood_current_theme['Version'];
}

// check and set default options 
function fastfood_default_options() {
		global $fastfood_current_theme;
		$fastfood_coa = fastfood_get_coa();
		$fastfood_opt = get_option( 'fastfood_options' );

		// if options are empty, sets the default values
		if ( empty( $fastfood_opt ) || !isset( $fastfood_opt ) ) {
			foreach ( $fastfood_coa as $key => $val ) {
				$fastfood_opt[$key] = $fastfood_coa[$key]['default'];
			}
			$fastfood_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $fastfood_opt );
		} else if ( !isset( $fastfood_opt['version'] ) || $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
			// check for unset values and set them to default value -> when updated to new version
			foreach ( $fastfood_coa as $key => $val ) {
				if ( !isset( $fastfood_opt[$key] ) ) $fastfood_opt[$key] = $fastfood_coa[$key]['default'];
			}
			$fastfood_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $fastfood_opt );
		}
}

// print a reminder message for set the options after the theme is installed or updated
if ( !function_exists( 'fastfood_setopt_admin_notice' ) ) {
	function fastfood_setopt_admin_notice() {
		echo '<div class="updated"><p><strong>' . sprintf( __( "Fastfood theme says: \"Dont forget to set <a href=\"%s\">my options</a> and the header image!\"", 'fastfood' ), get_admin_url() . 'themes.php?page=tb_fastfood_functions' ) . '</strong></p></div>';
	}
}
if ( current_user_can( 'manage_options' ) && $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
	add_action( 'admin_notices', 'fastfood_setopt_admin_notice' );
}

// check if in preview mode or not
$ff_is_printpreview = false;
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
	$ff_is_printpreview = true;
}

// is sidebar visible?
if ( !function_exists( 'fastfood_use_sidebar' ) ) {
	function fastfood_use_sidebar() {
		global $fastfood_opt;
		if ( ( is_page() && ( $fastfood_opt['fastfood_rsidebpages'] == 0 ) ) || ( is_single() && ( $fastfood_opt['fastfood_rsidebposts'] == 0 ) ) || is_attachment() ) {
			return false;
		} else {
			return true;
		}
	}
}

if ( !function_exists( 'fastfood_widget_area_init' ) ) {
	function fastfood_widget_area_init() {
		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
			'name' => __( 'Sidebar Widget Area', 'fastfood' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The sidebar widget area', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );
		
		// Area 2, located under the main menu.
		register_sidebar( array(
			'name' => __( 'Menu Widget Area', 'fastfood' ),
			'id' => 'header-widget-area',
			'description' => __( 'The widget area under the main menu', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );
	
		// Area 3, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'First Footer Widget Area', 'fastfood' ),
			'id' => 'first-footer-widget-area',
			'description' => __( 'The first footer widget area', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="fwa_title">',
			'after_title' => '</div>',
		) );
	
		// Area 4, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Second Footer Widget Area', 'fastfood' ),
			'id' => 'second-footer-widget-area',
			'description' => __( 'The second footer widget area', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="fwa_title">',
			'after_title' => '</div>',
		) );
	
		// Area 5, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Third Footer Widget Area', 'fastfood' ),
			'id' => 'third-footer-widget-area',
			'description' => __( 'The third footer widget area', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="fwa_title">',
			'after_title' => '</div>',
		) );
	
		// Area 6, located in page 404.
		register_sidebar( array(
			'name' => __( 'Page 404', 'fastfood' ),
			'id' => '404-widgets-area',
			'description' => __( 'Enrich the page 404 with some useful widgets', 'fastfood' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="fwa_title">',
			'after_title' => '</div>',
		) );
	}
}

// Add stylesheets to page
if ( !function_exists( 'fastfood_stylesheet' ) ) {
	function fastfood_stylesheet(){
		global $fastfood_opt, $fastfood_version, $ff_is_printpreview, $ff_is_mobile_browser, $ff_is_ie6;
		// mobile style
		if ( $ff_is_mobile_browser ) {
			wp_enqueue_style( 'ff_mobile-style', get_template_directory_uri() . '/mobile/mobile-style.css', false, $fastfood_version, 'screen' );
			return;
		}
		// ie6 style
		if ( $ff_is_ie6 ) {
			wp_enqueue_style( 'ff_ie6-style', get_template_directory_uri() . '/css/ie6.css', false, $fastfood_version, 'screen' );
			return;
		}
		//shows print preview / normal view
		if ( $ff_is_printpreview ) { //print preview
			wp_enqueue_style( 'ff_print-style-preview', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'screen' );
			wp_enqueue_style( 'ff_general-style-preview', get_template_directory_uri() . '/css/print_preview.css', false, $fastfood_version, 'screen' );
		} else { //normal view
			wp_enqueue_style( 'ff_general-style', get_stylesheet_uri(), false, $fastfood_version, 'screen' );
			if ( $fastfood_opt['fastfood_wpadminbar_css'] == 1 ) {
				wp_enqueue_style( 'ff_adminbar-style', get_template_directory_uri() . '/css/wpadminbar.css' );
			}
		}
		//print style
		wp_enqueue_style( 'ff_print-style', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'print' );
	}
}

// add scripts
if ( !function_exists( 'fastfood_scripts' ) ) {
	function fastfood_scripts(){
		global $fastfood_opt, $ff_is_printpreview, $fastfood_version, $ff_is_mobile_browser, $ff_is_ie6;
		if ( $ff_is_mobile_browser || $ff_is_printpreview ) return; //no scripts in print preview or mobile view
		// ie6 scripts
		if ( $ff_is_ie6 ) {
			wp_enqueue_script( 'comment-reply' ); //standard comment-reply
			return;
		}
		if ( ( $fastfood_opt['fastfood_jsani'] == 1 ) ) {
			wp_enqueue_script( 'jquery-ui-effects', get_template_directory_uri() . '/js/jquery-ui-effects-1.8.6.min.js', array( 'jquery' ), '1.8.6', false  ); //fastfood js
			wp_enqueue_script( 'fastfoodscript', get_template_directory_uri() . '/js/fastfoodscript.min.js', array( 'jquery' ), $fastfood_version, true  ); //fastfood js
		}
		if ( is_singular() ) {
			if ( ( $fastfood_opt['fastfood_jsani'] == 1 ) && ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) ) {
				wp_enqueue_script( 'ff-comment-reply', get_template_directory_uri() . '/js/comment-reply.min.js', array( 'jquery-ui-draggable' ), $fastfood_version, false   ); //custom comment-reply pop-up box
			} else {
				wp_enqueue_script( 'comment-reply' ); //custom comment-reply pop-up box
			}
		}
		if ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) {
			wp_enqueue_script( "ff-gallery-preview", get_template_directory_uri() . '/js/gallery-slideshow.min.js', array( 'jquery' ), $fastfood_version, true );
		}
		if ( ( $fastfood_opt['fastfood_post_expand'] == 1 ) ) {
			wp_enqueue_script( "ff-post-expander", get_template_directory_uri() . '/js/post-expander.min.js', array( 'jquery' ), $fastfood_version, true );
		}

	}
}


// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'fastfood_allcat' ) ) {
	function fastfood_allcat () {
		if( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
			get_template_part( 'allcat' );
			exit;
		}
	}
}

// show mobile version
if ( !function_exists( 'fastfood_mobile' ) ) {
	function fastfood_mobile () {
		global $ff_is_mobile_browser;
		if ( $ff_is_mobile_browser ) {
			if ( is_singular() ) { 
				get_template_part( 'mobile/mobile-single' ); 
			} else {
				get_template_part( 'mobile/mobile-index' );
			}
			exit;
		}
	}
}

// Get Recent Comments
if ( !function_exists( 'fastfood_get_recentcomments' ) ) {
	function fastfood_get_recentcomments() {
		$comments = get_comments( 'status=approve&number=10&type=comment' ); // valid type values (not documented) : 'pingback','trackback','comment'
		if ( $comments ) {
			foreach ( $comments as $comment ) {
				//if( post_password_required( get_post( $comment->comment_post_ID ) ) ) { continue; } // uncomment to skip comments on protected posts. Hi Emma ;)
				$post = get_post( $comment->comment_post_ID );
				setup_postdata( $post );
				if ( $post->post_title == "" ) {
					$post_title_short = __( '(no title)', 'fastfood' );
				} else {
					//shrink the post title if > 35 chars
					$post_title_short = mb_strimwidth( esc_html( $post->post_title ), 0, 35, '&hellip;' );
				}
				if ( post_password_required( $post ) ) {
					//hide comment author in protected posts
					$com_auth = __( 'someone','fastfood' );
				} else {
					//shrink the comment author if > 20 chars
					$com_auth = mb_strimwidth( $comment->comment_author, 0, 20, '&hellip;' );
				}
			    echo '<li>'. $com_auth . ' ' . __( 'about','fastfood' ) . ' <a href="' . get_permalink( $post->ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a><div class="preview">';
				if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) {
					echo '[' . __( 'No preview: this is a comment of a protected post', 'fastfood' ) . ']';
				} else {
					comment_excerpt( $comment->comment_ID );
				}
				echo '</div></li>';
			}
		} else {
			echo '<li>' . __( 'No comments yet.','fastfood' ) . '</li>';
		}
		wp_reset_postdata();
	}
}


// Get Recent Entries
if ( !function_exists( 'fastfood_get_recententries' ) ) {
	function fastfood_get_recententries( $mode = '', $limit = 10 ) {
		$lastposts = get_posts( 'numberposts=10' );
		foreach( $lastposts as $post ) {
			setup_postdata( $post );
			$post_title = esc_html( $post->post_title );
			if ( $post_title == "" ) {
				$post_title_short = __( '(no title)', 'fastfood' );
			} else {
				//shrink the post title if > 35 chars
				$post_title_short = mb_strimwidth( esc_html( $post_title ), 0, 35, '&hellip;' );
			}
			//shrink the post author if > 20 chars
			$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );
			echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . __( 'by','fastfood' ) . ' ' . $post_auth . '<div class="preview">';
			if ( post_password_required( $post ) ) {
				echo '<img class="alignleft wp-post-image" src="' . get_template_directory_uri() . '/images/lock.png" alt="thumb" title="' . $post_title_short . '" />';
				echo '[' . __( 'No preview: this is a protected post','fastfood' ) . ']';
			} else {
				echo get_the_post_thumbnail( $post->ID, array( 50,50 ), array( 'class' => 'alignleft' ) );
				the_excerpt();
			}
			echo '</div></li>';
		}
		wp_reset_postdata();
	}
}

// Get Categories List (with posts related)
if ( !function_exists( 'fastfood_get_categories_wpr' ) ) {
	function fastfood_get_categories_wpr() {
		$args=array(
			'orderby' => 'count',
			'number' => 10,
			'order' => 'DESC'
		);
		$categories=get_categories( $args );
		foreach( $categories as $category ) {
			echo '<li class="ql_cat_li"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s",'fastfood' ), $category->name ) . '" ' . '>' . $category->name . '</a> (' . $category->count . ')<div class="cat_preview"><div class="mentit">' . __( 'Recent Posts','fastfood' ) . '</div><ul class="solid_ul">';
			$tmp_cat_ID = $category->cat_ID;
			$post_search_args = array(
				'numberposts' => 5,
				'category' => $tmp_cat_ID
				);
			$lastcatposts = get_posts( $post_search_args );
			foreach( $lastcatposts as $post ) {
				setup_postdata( $post );
				$post_title = esc_html( $post->post_title );
				if ( $post->post_title == "" ) {
					$post_title_short = __( '(no title)', 'fastfood' );
				} else {
					//shrink the post title if > 35 chars
					$post_title_short = mb_strimwidth( esc_html( $post->post_title ), 0, 35, '&hellip;' );
				}
				//shrink the post author if > 20 chars
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );
				echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . __( 'by','fastfood' ) . ' ' . $post_auth . '</li>';
			}
			echo '</ul></div></li>';
		}
		wp_reset_postdata();
	}
}

// Pages Menu
if ( !function_exists( 'fastfood_pages_menu' ) ) {
	function fastfood_pages_menu() {
		echo '<ul id="mainmenu">';
		wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted
		echo '</ul>';
	}
}

// Pages Menu (mobile)
if ( !function_exists( 'fastfood_pages_menu_mobile' ) ) {
	function fastfood_pages_menu_mobile() {
		echo '<div id="ff-pri-menu" class="ff-menu "><ul id="mainmenu" class="ff-group">';
		wp_list_pages( 'sort_column=menu_order&title_li=&depth=1' ); // menu-order sorted
		echo '</ul></div>';
	}
}

// page hierarchy
if ( !function_exists( 'fastfood_multipages' ) ) {
	function fastfood_multipages( $r_pos ){
		global $post;
		$args = array(
			'post_type' => 'page',
			'post_parent' => $post->ID,
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'numberposts' => 0
			);
		$childrens = get_posts( $args ); // retrieve the child pages
		$the_parent_page = $post->post_parent; // retrieve the parent page
		$has_herarchy = false;

		if ( ( $childrens ) || ( $the_parent_page ) ){ ?>
			<div class="metafield">
				<div class="metafield_trigger mft_hier" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
				<div class="metafield_content">
					<?php
					if ( $the_parent_page ) {
						$the_parent_link = '<a href="' . get_permalink( $the_parent_page ) . '" title="' . get_the_title( $the_parent_page ) . '">' . get_the_title( $the_parent_page ) . '</a>';
						echo __( 'Upper page: ', 'fastfood' ) . $the_parent_link ; // echoes the parent
					}
					if ( ( $childrens ) && ( $the_parent_page ) ) { echo ' - '; } // if parent & child, echoes the separator
					if ( $childrens ) {
						$the_child_list = '';
						foreach ($childrens as $children) {
							$the_child_list[] = '<a href="' . get_permalink( $children ) . '" title="' . get_the_title( $children ) . '">' . get_the_title( $children ) . '</a>';
						}
						$the_child_list = implode(', ' , $the_child_list);
						echo __( 'Lower pages: ', 'fastfood' ) . $the_child_list; // echoes the childs
					}
					?>
				</div>
			</div>
		<?php 
		$has_herarchy = true;
		}
		return $has_herarchy;
	}
}

// print extra info for posts/pages
if ( !function_exists( 'fastfood_extrainfo' ) ) {
	function fastfood_extrainfo( $auth, $date, $comms, $tags, $cats, $hiera = false, $listview = false ) {
		global $fastfood_opt;
		// extra info management
		if ( is_page() && $fastfood_opt['fastfood_xinfos_on_page'] == 0) return;
		if ( is_single() && $fastfood_opt['fastfood_xinfos_on_post'] == 0) return;
		if ( !is_singular() && $fastfood_opt['fastfood_xinfos_on_list'] == 0) return;
		if ( $fastfood_opt['fastfood_xinfos_static'] == 1) $listview = true;
		if ( $fastfood_opt['fastfood_xinfos_byauth'] == 0) $auth = false;
		if ( $fastfood_opt['fastfood_xinfos_date'] == 0) $date = false;
		if ( $fastfood_opt['fastfood_xinfos_comm'] == 0) $comms = false;
		if ( $fastfood_opt['fastfood_xinfos_tag'] == 0) $tags = false;
		if ( $fastfood_opt['fastfood_xinfos_cat'] == 0) $cats = false;

		$r_pos = 10;
		if ( !$listview ) {
		?>
		<div class="meta_container">
			<div class="meta top_meta">
				<?php
				if ( $auth ) { ?>
					<?php $post_auth = ( $auth === true ) ? '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : $auth; ?>
					<div class="metafield_trigger" style="left: 10px;"><?php printf( __( 'by %s', 'fastfood' ), $post_auth ); ?></div>
				<?php
				}
				if ( $cats ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_cat" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php echo __( 'Categories', 'fastfood' ) . ':'; ?>
							<?php the_category( ', ' ) ?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				if ( $tags ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_tag" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php _e( 'Tags:', 'fastfood' ); ?>
							<?php if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags('', ', ', ''); } ?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				$page_cd_nc = ( is_page() && !comments_open() && !have_comments() ); //true if page with comments disabled and no comments
				if ( $comms && !$page_cd_nc ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_comm" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php _e( 'Comments', 'fastfood' ); ?>:
							<?php comments_popup_link( __( 'No Comments', 'fastfood' ), __( '1 Comment', 'fastfood' ), __( '% Comments', 'fastfood' ) ); // number of comments?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				if ( $date ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_date" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php
							printf( __( 'Published on: <b>%1$s</b>', 'fastfood' ), '' );
							the_time( get_option( 'date_format' ) );
							?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				if ( $hiera ) {
				?>
					<?php if ( fastfood_multipages( $r_pos ) ) { $r_pos = $r_pos + 30; } ?>
				<?php
				}
				?>
				<div class="metafield_trigger edit_link" style="right: <?php echo $r_pos; ?>px;"><?php edit_post_link( __( 'Edit', 'fastfood' ),'' ); ?></div>
			</div>
		</div>
		<?php
		} else { ?>
			<div class="meta">
				<?php if ( $auth ) { ?>
					<?php $post_auth = ( $auth === true ) ? '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : $auth; ?>
					<?php printf( __( 'by %s', 'fastfood' ), $post_auth ); ?>
				<?php } ?>
				<?php if ( $date ) { printf( __( 'Published on: %1$s', 'fastfood' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br />'; }?>
				<?php if ( $comms ) { echo __( 'Comments', 'fastfood' ) . ': '; comments_popup_link( __( 'No Comments', 'fastfood' ), __( '1 Comment', 'fastfood' ), __( '% Comments', 'fastfood' ) ); echo '<br />'; } ?>
				<?php if ( $tags ) { echo __( 'Tags:', 'fastfood' ) . ' '; if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags('', ', ', ''); }; echo '<br />';  } ?>
				<?php if ( $cats ) { echo __( 'Categories', 'fastfood' ) . ':'; the_category( ', ' ); echo '<br />'; } ?>
				<?php edit_post_link( __( 'Edit', 'fastfood' ) ); ?>
			</div>
		<?php
		}
	}
}

//add share links to post/page
if ( !function_exists( 'fastfood_share_this' ) ) {
	function fastfood_share_this(){
		global $post, $fastfood_opt;
		if ( $fastfood_opt['fastfood_share_this'] == 1 ) { ?>
		   <div class="article-share fixfloat">
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://twitter.com/share?url=<?php echo get_permalink(); ?>&amp;text=<?php echo urlencode( get_the_title() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Twitter.png" width="24" height="24" alt="Twitter Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Twitter' ); ?>" /></a>
				</span>
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://digg.com/submit?url=<?php echo get_permalink(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Digg.png" width="24" height="24" alt="Digg Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Digg' ); ?>" /></a>
				</span>
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://www.stumbleupon.com/submit?url=<?php echo get_permalink(); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/StumbleUpon.png" width="24" height="24" alt="StumbleUpon Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'StumbleUpon' ); ?>" /></a>
				</span>
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo get_permalink(); ?>&amp;t=<?php echo urlencode( get_the_title() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Facebook.png" width="24" height="24" alt="Facebook Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Facebook' ); ?>" /></a>
				</span>
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://reddit.com/submit?url=<?php echo get_permalink(); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Reddit.png" width="24" height="24" alt="Reddit Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Reddit' ); ?>" /></a>
				</span>		
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://www.google.com/reader/link?title=<?php echo urlencode( get_the_title() ); ?>&amp;url=<?php echo get_permalink(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Buzz.png" width="24" height="24" alt="Buzz Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Buzz' ); ?>" /></a>
				</span>
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://v.t.sina.com.cn/share/share.php?url=<?php echo get_permalink(); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Sina.png" width="24" height="24" alt="Sina Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Sina Weibo' ); ?>" /></a>
				</span>
				<span class="share-item">
					<a rel="nofollow" target="_blank" href="http://v.t.qq.com/share/share.php?title=<?php echo urlencode( get_the_title() ); ?>&amp;url=<?php echo get_permalink(); ?>&amp;site=<?php echo home_url(); ?>&amp;pic=<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/Tencent.png" width="24" height="24" alt="Tencent Button" title="<?php printf( __( 'Share with %s','fastfood' ), 'Tencent' ); ?>" /></a>
				</span>
			</div>
		<?php }
	}
}

//add a fix for embed videos overlying quickbar
if ( !function_exists( 'fastfood_content_replace' ) ) {
	function fastfood_content_replace( $content ){
		$content = str_replace( '<param name="allowscriptaccess" value="always">', '<param name="allowscriptaccess" value="always"><param name="wmode" value="transparent">', $content );
		$content = str_replace( '<embed ', '<embed wmode="transparent" ', $content );
		return $content;
	}
}

// create theme option page
if ( !function_exists( 'fastfood_create_menu' ) ) {
	function fastfood_create_menu() {
		//create new top-level menu
		$pageopt = add_theme_page( __( 'Theme Options','fastfood' ), __( 'Theme Options','fastfood' ), 'edit_theme_options', 'tb_fastfood_functions', 'fastfood_edit_options' );
		//call register settings function
		add_action( 'admin_init', 'fastfood_register_tb_settings' );
		add_action( 'admin_print_styles-' . $pageopt, 'fastfood_theme_admin_styles' );
		add_action( 'admin_print_scripts-' . $pageopt, 'fastfood_theme_admin_scripts' );
		add_action( 'admin_print_styles-widgets.php', 'fastfood_widgets_style' );
	}
}

if ( !function_exists( 'fastfood_theme_admin_scripts' ) ) {
	function fastfood_theme_admin_scripts() {
		global $fastfood_version;
		wp_enqueue_script( 'fastfood-options-script', get_template_directory_uri().'/js/fastfood-options.dev.js',array('jquery','farbtastic'),$fastfood_version, true ); //fastfood js
	}
}

if ( !function_exists( 'fastfood_widgets_style' ) ) {
	function fastfood_widgets_style() {
		//add custom stylesheet
		wp_enqueue_style( 'ff-widgets-style', get_template_directory_uri() . '/css/widgets.css', false, '', 'screen' );
	}
}

if ( !function_exists( 'fastfood_register_tb_settings' ) ) {
	function fastfood_register_tb_settings() {
		//register fastfood settings
		register_setting( 'ff_settings_group', 'fastfood_options', 'fastfood_sanitaze_options' );
	}
}

// sanitize options value
if ( !function_exists( 'fastfood_sanitaze_options' ) ) {
	function fastfood_sanitaze_options($input) {
		global $fastfood_current_theme;
		$fastfood_coa = fastfood_get_coa();
		// check for updated values and return 0 for disabled ones <- index notice prevention
		foreach ( $fastfood_coa as $key => $val ) {
	
			if( $fastfood_coa[$key]['type'] == 'chk' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = 0;
				} else {
					$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
				}
			} elseif( $fastfood_coa[$key]['type'] == 'sel' ) {
				if ( !in_array( $input[$key], $fastfood_coa[$key]['options'] ) ) $input[$key] = $fastfood_coa[$key]['default'];
			} elseif( $fastfood_coa[$key]['type'] == 'col' ) {
				$color = str_replace( '#' , '' , $input[$key] );
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $color );
				$input[$key] = '#' . $color;
			}
		}
		// check for required options
		foreach ( $fastfood_coa as $key => $val ) {
			if ( $fastfood_coa[$key]['req'] != '' ) { if ( $input[$fastfood_coa[$key]['req']] == 0 ) $input[$key] = 0; }
		}
		//$input['hidden_opt'] = 'default'; //this hidden option avoids empty $fastfood_options when updated
		$input['version'] = $fastfood_current_theme['Version']; // keep version number
		return $input;
	}
}

// the custon header style - called only on your theme options page
if ( !function_exists( 'fastfood_theme_admin_styles' ) ) {
	function fastfood_theme_admin_styles() {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'ff-options-style', get_template_directory_uri() . '/css/ff-opt.css', false, '', 'screen' );
		?>
		<style type="text/css">
			#fastfood-infos-li div.wp-menu-image {
				background: url('<?php echo admin_url(); ?>/images/menu.png') no-repeat scroll -38px -39px transparent;
			}
			#fastfood-infos-li:hover div.wp-menu-image,
			#fastfood-infos-li.tab-selected div.wp-menu-image {
				background: url('<?php echo admin_url(); ?>/images/menu.png') no-repeat scroll -38px -7px transparent;
			}
		</style>
		<?php
	}
}

// the theme option page
if ( !function_exists( 'fastfood_edit_options' ) ) {
	function fastfood_edit_options() {
	  if ( !current_user_can( 'edit_theme_options' ) ) {
	    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	  }
		global $fastfood_opt, $fastfood_current_theme;
		$fastfood_coa = fastfood_get_coa();
		
		// update version value when admin visit options page
		if ( $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
			$fastfood_opt['version'] = $fastfood_current_theme['Version'];
			update_option( 'fastfood_options' , $fastfood_opt );
		}
		
		// options have been updated
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			//return options save message
			echo '<div style="position: absolute;left: 50%;" id="message" class="updated fade"><p><strong>' . __( 'Options saved.','fastfood' ) . '</strong></p></div>';
		}
		
	?>
		<div class="wrap" id="ff-main-wrap">
			<div class="icon32" id="icon-themes"><br></div>
			<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>
			<ul id="ff-tabselector" class="hide-if-no-js">
				<li id="ff-selgroup-quickbar"><a href="#" onClick="fastfoodSwitchTab.set('quickbar'); return false;"><?php _e( 'Quickbar' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-content"><a href="#" onClick="fastfoodSwitchTab.set('content'); return false;"><?php _e( 'Content' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-postinfo"><a href="#" onClick="fastfoodSwitchTab.set('postinfo'); return false;"><?php _e( 'Post/Page details' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-postformats"><a href="#" onClick="fastfoodSwitchTab.set('postformats'); return false;"><?php _e( 'Post formats' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-sidebar"><a href="#" onClick="fastfoodSwitchTab.set('sidebar'); return false;"><?php _e( 'Sidebar' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-javascript"><a href="#" onClick="fastfoodSwitchTab.set('javascript'); return false;"><?php _e( 'Javascript' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-other"><a href="#" onClick="fastfoodSwitchTab.set('other'); return false;"><?php _e( 'Other' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-colors"><a href="#" onClick="fastfoodSwitchTab.set('colors'); return false;"><?php _e( 'Colors' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-info"><a href="#" onClick="fastfoodSwitchTab.set('info'); return false;"><?php _e( 'Theme Info' , 'fastfood' ); ?></a></li>
			</ul>
			<ul id="selector" class="hide-if-js">
				<li id="fastfood-options-li"><a href="#fastfood-options"><?php _e( 'Options','fastfood' ); ?></a></li>
				<li id="fastfood-infos-li"><a href="#fastfood-infos"><?php _e( 'Theme Info','fastfood' ); ?></a></li>
			</ul>
			<div id="tabs-container">
				<div class="clear"></div>
				<div id="fastfood-options">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Options','fastfood' ); ?><h2>
					<form method="post" action="options.php">
						<?php settings_fields( 'ff_settings_group' ); ?>
						<div id="stylediv">
							<table style="border-collapse: collapse; width: 100%;border-bottom: 2px groove #fff;">
								<tr style="border-bottom: 2px groove #fff;">
									<th class="column-nam"><?php _e( 'name' , 'fastfood' ); ?></th>
									<th class="column-chk"><?php _e( 'status' , 'fastfood' ); ?></th>
									<th class="column-des"><?php _e( 'description' , 'fastfood' ); ?></th>
									<th class="column-req"><?php _e( 'require' , 'fastfood' ); ?></th>
								</tr>
							<?php foreach ($fastfood_coa as $key => $val) { ?>
								<?php if ( $fastfood_coa[$key]['type'] == 'chk' ) { ?>
									<tr class="ff-tab-opt ff-tabgroup-<?php echo $fastfood_coa[$key]['group']; ?>">
										<td class="column-nam"><?php echo $fastfood_coa[$key]['description']; ?></td>
										<td class="column-chk">
											<input name="fastfood_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $fastfood_opt[$key] ); ?> />
										</td>
										<td class="column-des"><?php echo $fastfood_coa[$key]['info']; ?></td>
										<td class="column-req"><?php if ( $fastfood_coa[$key]['req'] != '' ) echo $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></td>
									</tr>
								<?php } elseif ( $fastfood_coa[$key]['type'] == 'sel' ) { ?>
									<tr class="ff-tab-opt ff-tabgroup-<?php echo $fastfood_coa[$key]['group']; ?>">
										<td class="column-nam"><?php echo $fastfood_coa[$key]['description']; ?></td>
										<td class="column-chk">
											<select name="fastfood_options[<?php echo $key; ?>]">
											<?php foreach($fastfood_coa[$key]['options'] as $option) { ?>
												<option value="<?php echo $option; ?>" <?php selected( $fastfood_opt[$key], $option ); ?>><?php echo $option; ?></option>
											<?php } ?>
											</select>
										</td>
										<td class="column-des"><?php echo $fastfood_coa[$key]['info']; ?></td>
										<td class="column-req"><?php if ( $fastfood_coa[$key]['req'] != '' ) echo $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></td>
									</tr>
								<?php } elseif ( $fastfood_coa[$key]['type'] == 'col' ) { ?>
									<tr class="ff-tab-opt ff-tabgroup-<?php echo $fastfood_coa[$key]['group']; ?> hide-if-no-js">
										<td class="column-nam"><?php echo $fastfood_coa[$key]['description']; ?></td>
										<td class="column-chk">
											<div style="position:relative; display: block;">
												<input onclick="showMeColorPicker('<?php echo $key; ?>');" style="background-color:<?php echo $fastfood_opt[$key]; ?>;" class="color_preview_box" type="text" id="ff_box_<?php echo $key; ?>" value="" readonly="readonly" />
												<div class="ff_cp" id="ff_colorpicker_<?php echo $key; ?>"></div>
											</div>
										</td>
										<td class="column-des">
											<input class="ff_input" id="ff_input_<?php echo $key; ?>" type="text" name="fastfood_options[<?php echo $key; ?>]" value="<?php echo $fastfood_opt[$key]; ?>" />
											<a class="hide-if-no-js" href="#" onclick="showMeColorPicker('<?php echo $key; ?>'); return false;"><?php _e( 'Select a Color' , 'fastfood' ); ?></a>&nbsp;-&nbsp;
											<a class="hide-if-no-js" style="color:<?php echo $fastfood_coa[$key]['default']; ?>;" href="#" onclick="pickColor('<?php echo $key; ?>','<?php echo $fastfood_coa[$key]['default']; ?>'); return false;"><?php _e( 'Default' , 'fastfood' ); ?></a>
										</td>
										<td class="column-req"><?php if ( $fastfood_coa[$key]['req'] != '' ) echo $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></td>
									</tr>
								<?php }	?>
							<?php }	?>
							</table>
						</div>
						<p>
							<input type="hidden" name="fastfood_options[hidden_opt]" value="default" />
							<input class="button" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
							<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="themes.php?page=functions" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a>
						</p>
					</form>
					<p class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc;">
						<small>
							<?php _e( 'If you like/dislike this theme, or if you encounter any issues using it, please let us know it.', 'fastfood' ); ?><br />
							<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/tema-per-wordpress-fastfood' ); ?>" title="Fastfood theme" target="_blank"><?php _e( 'Leave a feedback', 'fastfood' ); ?></a>
						</small>
					</p>
					<p class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc; margin-top: 10px;">
						<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/temi-wp/wordpress-themes-translations' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
					</p>
				</div>
				<div id="fastfood-infos">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Theme Info','fastfood' ); ?><h2>
					<?php get_template_part( 'readme' ); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	<?php
	}
}

// set up custom colors and header image
if ( !function_exists( 'fastfood_setup' ) ) {
	function fastfood_setup() {
		global $fastfood_opt;
		
		// Register localization support
		load_theme_textdomain('fastfood', TEMPLATEPATH . '/languages' );
		// Theme uses wp_nav_menu() in three location
		register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'fastfood' )	) );
		register_nav_menus( array( 'secondary1' => __( 'Secondary Navigation Menu #1', 'fastfood' )	) );
		register_nav_menus( array( 'secondary2' => __( 'Secondary Navigation Menu #2', 'fastfood' )	) );
		// Register Features Support
		add_theme_support( 'automatic-feed-links' );
		// Thumbnails support
		add_theme_support( 'post-thumbnails' );
		// Add the editor style
		if ( isset( $fastfood_opt['fastfood_editor_style'] ) && ( $fastfood_opt['fastfood_editor_style'] == 1 ) ) add_editor_style( 'css/editor-style.css' );
	
		// This theme uses post formats
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'status' ) );

		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '404040' );
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define( 'HEADER_IMAGE', '%s/images/headers/tree.jpg' );
	
		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to fastfood_header_image_width and fastfood_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', 848 );
		
		$head_h = ( isset( $fastfood_opt['fastfood_head_h'] ) ? str_replace( 'px', '', $fastfood_opt['fastfood_head_h']) : 120 );
		define( 'HEADER_IMAGE_HEIGHT', $head_h );
	
		// Support text inside the header image.
		define( 'NO_HEADER_TEXT', false );
	
		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See fastfood_admin_header_style(), below.
		add_custom_image_header( 'fastfood_header_style', 'fastfood_admin_header_style' );
		
		// Add a way for the custom background to be styled in the admin panel that controls
		if ( isset( $fastfood_opt['fastfood_custom_bg'] ) && $fastfood_opt['fastfood_custom_bg'] == 1 ) {
			fastfood_add_custom_background( 'fastfood_custom_bg' , 'fastfood_admin_custom_bg_style' , '' );
		} else {
			add_custom_background( 'fastfood_custom_bg' , '' , '' );
		}
	
		// ... and thus ends the changeable header business.
	
		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'tree' => array(
				'url' => '%s/images/headers/tree.jpg',
				'thumbnail_url' => '%s/images/headers/tree-thumbnail.jpg',
				'description' => __( 'Ancient Tree', 'fastfood' )
			),
			'vector' => array(
				'url' => '%s/images/headers/vector.jpg',
				'thumbnail_url' => '%s/images/headers/vector-thumbnail.jpg',
				'description' => __( 'Vector Flowers', 'fastfood' )
			),
			'globe' => array(
				'url' => '%s/images/headers/globe.jpg',
				'thumbnail_url' => '%s/images/headers/globe-thumbnail.jpg',
				'description' => __( 'Globe', 'fastfood' )
			),
			'bamboo' => array(
				'url' => '%s/images/headers/bamboo.jpg',
				'thumbnail_url' => '%s/images/headers/bamboo-thumbnail.jpg',
				'description' => __( 'Bamboo Forest', 'fastfood' )
			),
			'stripes' => array(
				'url' => '%s/images/headers/stripes.jpg',
				'thumbnail_url' => '%s/images/headers/stripes-thumbnail.jpg',
				'description' => __( 'Orange stripes', 'fastfood' )
			),
			'paper' => array(
				'url' => '%s/images/headers/paper-and-coffee.png',
				'thumbnail_url' => '%s/images/headers/paper-and-coffee-thumbnail.png',
				'description' => __( 'Paper and coffee', 'fastfood' )
			),
			'abstract' => array(
				'url' => '%s/images/headers/abstract.jpg',
				'thumbnail_url' => '%s/images/headers/abstract-thumbnail.jpg',
				'description' => __( 'Abstract', 'fastfood' )
			),
			'carps' => array(
				'url' => '%s/images/headers/carps.jpg',
				'thumbnail_url' => '%s/images/headers/carps-thumbnail.jpg',
				'description' => __( 'Carps', 'fastfood' )
			),
			'bird' => array(
				'url' => '%s/images/headers/bird.jpg',
				'thumbnail_url' => '%s/images/headers/bird-thumbnail.jpg',
				'description' => __( 'Bird', 'fastfood' )
			),
			'orange' => array(
				'url' => '%s/images/headers/orange.jpg',
				'thumbnail_url' => '%s/images/headers/orange-thumbnail.jpg',
				'description' => __( 'Orange landscape', 'fastfood' )
			),
			'fog' => array(
				'url' => '%s/images/headers/fog.jpg',
				'thumbnail_url' => '%s/images/headers/fog-thumbnail.jpg',
				'description' => __( 'Fog', 'fastfood' )
			)
		) );
	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_header_style' ) ) {
	function fastfood_admin_header_style() {	
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-header.css" />' . "\n";
		fastfood_header_switch();
	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_custom_bg_style' ) ) {
	function fastfood_admin_custom_bg_style() {	
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-bg.css" />' . "\n";
	}
}


// the custon header style - add style customization to page - gets included in the site header
if ( !function_exists( 'fastfood_header_style' ) ) {
	function fastfood_header_style(){
	
		global $ff_is_printpreview, $ff_is_mobile_browser, $fastfood_opt;
		if ( $ff_is_printpreview || $ff_is_mobile_browser ) return;
	
		if ( 'blank' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) || '' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) || ( defined( 'NO_HEADER_TEXT' ) && NO_HEADER_TEXT ) )
			$style = 'display:none;';
		else
			$style = 'color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';';
	
		?>
		<style type="text/css">
			#head {
				background: transparent url( '<?php esc_url ( header_image() ); ?>' ) right bottom no-repeat;
				min-height: <?php echo HEADER_IMAGE_HEIGHT - 20; ?>px;
			}
			#head h1 a, #head .description {
				<?php echo $style; ?>
			}
			body {
				font-size: <?php echo $fastfood_opt['fastfood_font_size']; ?>;
			}
			a {
				color: <?php echo $fastfood_opt['fastfood_colors_link']; ?>;
			}
			a:hover,
			.current-menu-item a:hover,
			.current_page_item a:hover,
			.current-cat a:hover {
				color: <?php echo $fastfood_opt['fastfood_colors_link_hover']; ?>;
			}
			.current-menu-item > a,
			.current_page_item > a,
			.current-cat > a,
			li.current_page_ancestor .hiraquo {
				color: <?php echo $fastfood_opt['fastfood_colors_link_sel']; ?>;
			}	
		</style>
		<!--[if lte IE 8]>
		<style type="text/css">
			.js-res {
				border:1px solid #333333 !important;
			}
			.menuitem_1ul > ul > li {
				margin-right:-2px;
			}
			.gallery .attachment-thumbnail,
			.ffg-img img,
			.storycontent img.size-full {
				width:auto;
			}
		</style>
		<![endif]-->
		<?php
	}
}

// custom background style - gets included in the site header
if ( !function_exists( 'fastfood_custom_bg' ) ) {
	function fastfood_custom_bg() {
		global $ff_is_printpreview, $ff_is_mobile_browser;
		if ( $ff_is_printpreview || $ff_is_mobile_browser ) return;

		$background = get_background_image();
		$color = get_background_color();
		if ( ! $background && ! $color ) return;
	
		$style = $color ? "background-color: #$color;" : '';
	
		if ( $background ) {
			$image = " background-image: url('$background');";
	
			$repeat = get_theme_mod( 'background_repeat', 'repeat' );
			if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) $repeat = 'repeat';
			$repeat = " background-repeat: $repeat;";
	
			$position_x = get_theme_mod( 'background_position_x', 'left' );
			$position_y = get_theme_mod( 'background_position_y', 'top' );
			if ( ! in_array( $position_x, array( 'center', 'right', 'left' ) ) ) $position = 'left';
			if ( ! in_array( $position_y, array( 'center', 'top', 'bottom' ) ) ) $position = 'top';
			$position = " background-position: $position_x $position_y;";
	
			$attachment = get_theme_mod( 'background_attachment', 'scroll' );
			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) ) $attachment = 'scroll';
			$attachment = " background-attachment: $attachment;";
	
			$style .= $image . $repeat . $position . $attachment;
		} else {
			$style .= ' background-image: url("");';
		}
		?>
		<style type="text/css"> 
			body { <?php echo trim( $style ); ?> }
		</style>
		<?php
	}
}

// set the custom excerpt length
if ( !function_exists( 'fastfood_new_excerpt_length' ) ) {
	function fastfood_new_excerpt_length( $length ) {
		return 50;
	}
}

//add a default gravatar
if ( !function_exists( 'fastfood_addgravatar' ) ) {
	function fastfood_addgravatar( $avatar_defaults ) {
	  $myavatar = get_template_directory_uri() . '/images/user.png';
	  $avatar_defaults[$myavatar] = __( 'Fastfood Default Gravatar', 'fastfood' );
	
	  return $avatar_defaults;
	}
	add_filter( 'avatar_defaults', 'fastfood_addgravatar' );
}

// pages navigation links
if ( !function_exists( 'fastfood_page_navi' ) ) {
	function fastfood_page_navi($this_page_id) {
		$pages = get_pages( array('sort_column' => 'menu_order') ); // get the menu-ordered list of the pages
		$page_links = array();
		foreach ($pages as $k => $pagg) {
			if ( $pagg->ID == $this_page_id ) { // we are in this $pagg
				if ( $k == 0 ) { // is first page
					$page_links['next']['link'] = get_page_link($pages[1]->ID);
					$page_links['next']['title'] = $pages[1]->post_title;
					if ( $page_links['next']['title'] == '' ) $page_links['next']['title'] = __( '(no title)','fastfood' );
				} elseif ( $k == ( count( $pages ) -1 ) ) { // is last page
					$page_links['prev']['link'] = get_page_link($pages[$k - 1]->ID);
					$page_links['prev']['title'] = $pages[$k - 1]->post_title;
					if ( $page_links['prev']['title'] == '' ) $page_links['prev']['title'] = __( '(no title)','fastfood' );
				} else {
					$page_links['next']['link'] = get_page_link($pages[$k + 1]->ID);
					$page_links['next']['title'] = $pages[$k + 1]->post_title;
					if ( $page_links['next']['title'] == '' ) $page_links['next']['title'] = __( '(no title)','fastfood' );
					$page_links['prev']['link'] = get_page_link($pages[$k - 1]->ID);
					$page_links['prev']['title'] = $pages[$k - 1]->post_title;
					if ( $page_links['prev']['title'] == '' ) $page_links['prev']['title'] = __( '(no title)','fastfood' );
				}
			}
		}
		return $page_links;
	}
}

// display a simple login form in quickbar
if ( !function_exists( 'fastfood_mini_login' ) ) {
	function fastfood_mini_login() {
		global $fastfood_opt;
		$args = array(
			'redirect' => home_url(),
			'form_id' => 'ff-loginform',
			'id_username' => 'ff-user_login',
			'id_password' => 'ff-user_pass',
			'id_remember' => 'ff-rememberme',
			'id_submit' => 'ff-submit' );
		if ( (!class_exists("siCaptcha") ) && ( $fastfood_opt['fastfood_qbar_minilogin'] == 1 ) ) { //mini login form is skipped if siCaptcha plugin is active or disabled via options
			?>
			<li class="ql_cat_li">
				<a title="<?php _e( 'Log in','fastfood' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in','fastfood' ); ?></a>
				<div class="cat_preview" style="padding-left: 20px;">
					<div class="mentit"><?php _e( 'Log in','fastfood' ); ?></div>
					<div id="ff_minilogin" class="solid_ul">
						<?php wp_login_form($args); ?>
						<a id="closeminilogin" href="#" style="display: none; margin-left:10px;"><?php _e('Close','fastfood'); ?></a>
					</div>
				</div>
			</li>
	
			<?php
		} else {
			?>
			<li>
				<a title="<?php _e( 'Log in','fastfood' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in','fastfood' ); ?></a>
			</li>
			<?php
		}
	}
}

// add 'quoted on' before trackback/pingback comments link
if ( !function_exists( 'fastfood_add_quoted_on' ) ) {
	function fastfood_add_quoted_on( $return ) {
		global $comment;
		$text = '';
		if ( get_comment_type() != 'comment' ) {
			$text = '<span style="font-weight: normal;">' . __( 'this post is quoted by', 'fastfood' ) . ' </span>';
		}
		return $text . $return;
	}
}

// localize js
if ( !function_exists( 'fastfood_localize_js' ) ) {
	function fastfood_localize_js() {
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
				ff_post_expander_text = "<?php _e( 'Post loading, please wait...','fastfood' ); ?>";
				ff_gallery_preview_text = "<?php _e( 'Preview','fastfood' ); ?>";
				ff_gallery_click_text = "<?php _e( 'Click on thumbnails','fastfood' ); ?>";
			/* ]]> */
		</script>
		<?php
	}
}

//script for the custom header image
if ( !function_exists( 'fastfood_header_switch' ) ) {
	function fastfood_header_switch() {
		global $_wp_default_headers;
		$default_headers = $_wp_default_headers;
		?>
		
<script type="text/javascript">
	/* <![CDATA[ */
	jQuery(document).ready( function($) {
	  $("#available-headers .default-header input").click( function() {
		var def_header = $(this);
		switch( def_header.attr("value") )
		{
		<?php foreach ( $default_headers as $header_key => $header ) { ?>
			case "<?php echo esc_attr($header_key); ?>":
				$("#headimg").css({ 'background-image' : 'url(<?php printf( $header['url'], get_template_directory_uri(), get_stylesheet_directory_uri()); ?>)' });
				break;
		<?php } ?>
		}
	  });
	});
	/* ]]> */
</script>

		<?php
	}
}

//Add new contact methods to author panel
if ( !function_exists( 'fastfood_new_contactmethods' ) ) {
	function fastfood_new_contactmethods( $contactmethods ) {
		//add Twitter
		$contactmethods['twitter'] = 'Twitter';
		//add Facebook
		$contactmethods['facebook'] = 'Facebook';
	
		return $contactmethods;
	}
}

//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
if ( !function_exists( 'fastfood_friendly_date' ) ) {
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
		
		echo $dateWithNiceTone;
			
	}
}

// retrieve the post content, then die (for "post_expander" ajax request)
if ( !function_exists( 'fastfood_post_expander_show_post' ) ) {
	function fastfood_post_expander_show_post (  ) {
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		die();
	}
}

//is a "post_expander" ajax request?
function fastfood_post_expander_activate ( ) {
	if ( isset( $_POST["ff_post_expander"] ) ) {
		add_action( 'wp', 'fastfood_post_expander_show_post' );
	}
}

// retrieve the post content, then die (for "ff_gallery_slide" ajax request)
if ( !function_exists( 'fastfood_gallery_slide_show_post' ) ) {
	function fastfood_gallery_slide_show_post (  ) {
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>
					<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size', 'fastfood' ) ;  // link to Full size image ?>" rel="attachment"><?php
						$ff_attachment_width  = apply_filters( 'fastfood_attachment_size', 1000 );
						$ff_attachment_height = apply_filters( 'fastfood_attachment_height', 1000 );
						echo wp_get_attachment_image( $post->ID, array( $ff_attachment_width, $ff_attachment_height ) ); // filterable image width with, essentially, no limit for image height.
					?></a>
				<?php
			}
		}
		die();
	}
}

//is a "ff_gallery_slide" ajax request?
function fastfood_gallery_slide_activate ( ) {
	if ( isset( $_POST["ff_gallery_slide"] ) ) {
		add_action( 'wp', 'fastfood_gallery_slide_show_post' );
	}
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

//Add callbacks for background image display. based on WP theme.php -> add_custom_background()
if ( !function_exists( 'fastfood_add_custom_background' ) ) {
	function fastfood_add_custom_background( $header_callback = '', $admin_header_callback = '', $admin_image_div_callback = '' ) {
		if ( isset( $GLOBALS['custom_background'] ) )
			return;

		if ( empty( $header_callback ) )
			$header_callback = '_custom_background_cb';

		add_action( 'wp_head', $header_callback );

		add_theme_support( 'custom-background', array( 'callback' => $header_callback ) );

		if ( ! is_admin() )
			return;
		require_once( 'lib/my-custom-background.php' );
		$GLOBALS['custom_background'] =& new Custom_Background( $admin_header_callback, $admin_image_div_callback );
		add_action( 'admin_menu', array( &$GLOBALS['custom_background'], 'init' ) );
	}
}

// load the custom widgets module
get_template_part('lib/widgets');

// load the custom hooks
get_template_part('lib/hooks');

?>