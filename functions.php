<?php
/**** begin theme hooks ****/
// Tell WordPress to run fastfood_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'fastfood_setup' );
// Register sidebars by running fastfood_widget_area_init() on the widgets_init hook
add_action( 'widgets_init', 'fastfood_widget_area_init' );
// Add stylesheets
add_action( 'wp_print_styles', 'fastfood_stylesheet' );
// Add js animations
add_action( 'template_redirect', 'fastfood_scripts' );
// Add custom category page
add_action( 'template_redirect', 'fastfood_allcat' );
// Add custom menus
add_action( 'admin_menu', 'fastfood_create_menu' );
// Custom filters
add_filter( 'the_content', 'fastfood_content_replace' );
add_filter( 'excerpt_length', 'new_excerpt_length' );
add_filter( 'get_comment_author_link', 'fastfood_add_quoted_on' );
add_filter('user_contactmethods','fastfood_new_contactmethods',10,1);
/**** end theme hooks ****/

// load theme options in $fastfood_opt variable, globally retrieved in php files
$fastfood_opt = get_option( 'fastfood_options' );

// check if is mobile browser
$ff_is_mobile_browser = fastfood_mobile_device_detect();

function fastfood_mobile_device_detect() {
	global $fastfood_opt;
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ( ( !isset( $fastfood_opt['fastfood_mobile_css'] ) || ( $fastfood_opt['fastfood_mobile_css'] = 1) ) && preg_match( '/(ipad|ipod|iphone|android|opera mini|blackberry|palm|symbian|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i' , $user_agent ) ) { // there were other words for mobile detecting but this is enought ;-)
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

//complete options array, with defaults values, description, infos and required option
function fastfood_get_coa() {
	$fastfood_coa = array(
		'fastfood_qbar' => array( 'type' =>'chk', 'default'=>1,'description'=>__( 'sliding menu','fastfood' ),'info'=>__('[default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_qbar_user' => array( 'type' =>'chk', 'default'=>1,'description'=>__( '-- user','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_qbar_minilogin' => array( 'type' =>'chk', 'default'=>1,'description'=>__( '---- mini login','fastfood' ),'info'=>__( 'a small login form in the user panel [default = enabled]','fastfood' ),'req'=>'fastfood_qbar_user' ),
		'fastfood_qbar_reccom' => array( 'type' =>'chk', 'default'=>1,'description'=>__( '-- recent comments','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_qbar_cat' => array( 'type' =>'chk', 'default'=>1,'description'=>__( '-- categories','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_qbar_recpost' => array( 'type' =>'chk', 'default'=>1,'description'=>__( '-- recent posts','fastfood' ),'info'=>__( '[default = enabled]','fastfood' ),'req'=>'fastfood_qbar' ),
		'fastfood_rsidebpages' => array( 'type' =>'chk', 'default'=>0,'description'=>__( 'sidebar on pages','fastfood' ),'info'=>__( 'show right sidebar on pages [default = disabled]','fastfood' ),'req'=>'' ),
		'fastfood_rsidebposts' => array( 'type' =>'chk', 'default'=>0,'description'=>__( 'sidebar on posts','fastfood' ),'info'=>__( 'show right sidebar on posts [default = disabled]','fastfood' ),'req'=>'' ),
		'fastfood_jsani' => array( 'type' =>'chk', 'default'=>1,'description'=>__( 'javascript animations','fastfood' ),'info'=>__( 'try disable animations if you encountered problems with javascript [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_cust_comrep' => array( 'type' =>'chk', 'default'=>1,'description'=>__( 'custom comment reply form','fastfood' ),'info'=>__( 'custom floating form for post/reply comments [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_mobile_css' => array( 'type' =>'chk', 'default'=>1,'description'=>__( 'mobile support','fastfood' ),'info'=>__( 'use a dedicated style in mobile devices [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_wpadminbar_css' => array( 'type' =>'chk', 'default'=>1,'description'=>__( 'custom adminbar style','fastfood' ),'info'=>__( 'style integration with the theme for admin bar [default = enabled]','fastfood' ),'req'=>'' ),
		'fastfood_font_size' => array( 'type' =>'sel', 'default'=>'11px', 'options'=>array('10px','11px','12px','13px','14px'), 'description'=>__( 'font size','fastfood' ),'info'=>__( '[default = 11px]','fastfood' ),'req'=>'' ),
		'fastfood_tbcred' => array( 'type' =>'chk', 'default'=>1,'description'=>__( 'theme credits','fastfood' ),'info'=>__( "please, don't hide theme credits [default = enabled]",'fastfood' ),'req'=>'' )
	);
	return $fastfood_coa;
}

// get theme version
if ( get_theme( 'Fastfood' ) ) {
	$fastfood_current_theme = get_theme( 'Fastfood' );
}

// theme version's check
if ( isset( $fastfood_current_theme ) ) {
	if ( !isset( $fastfood_opt['version'] ) && isset( $_GET['activated'] ) ) {
		$fastfood_version = $fastfood_current_theme['Version'];
	} elseif ( !isset( $fastfood_opt['version'] ) && !isset( $_GET['activated'] ) ) {
		if ( current_user_can( 'manage_options' ) ) {
			$fastfood_version_notice = 1;
		}
		$fastfood_version = $fastfood_current_theme['Version'];
	} else {
		if ( current_user_can( 'manage_options' ) && $fastfood_opt['version'] < $fastfood_current_theme['Version'] && !isset( $_GET['activated'] ) ) {
			$fastfood_version_notice = 1;
		}
		$fastfood_version = $fastfood_opt['version'];
	}
} else {
	$fastfood_version = '';
}

// check if in preview mode or not
$ff_is_printpreview = false;
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
	$ff_is_printpreview = true;
}

// print a reminder message when theme is updated
function fastfood_update_admin_notice() {
	echo '<div class="updated"><p><strong>' . sprintf( __( "Fastfood theme says: \"Dont forget to set <a href=\"%s\">my options</a> and the header image!\"", 'fastfood' ), get_admin_url() . 'themes.php?page=tb_fastfood_functions' ) . '</strong></p></div>';
}
if ( current_user_can( 'manage_options' ) && isset( $fastfood_version_notice ) && $fastfood_version_notice == 1 ) {
	add_action( 'admin_notices', 'fastfood_update_admin_notice' );
}

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

}

// Add stylesheets to page
function fastfood_stylesheet(){
	global $fastfood_opt, $fastfood_version, $ff_is_printpreview, $ff_is_mobile_browser;
	//shows print preview / normal view
	if ( $ff_is_printpreview ) { //print preview
		wp_enqueue_style( 'ff_print-style-preview', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'screen' );
		wp_enqueue_style( 'ff_general-style-preview', get_template_directory_uri() . '/css/print_preview.css', false, $fastfood_version, 'screen' );
	} else { //normal view
		if ( $ff_is_mobile_browser ) {
			wp_enqueue_style( 'ff_general-style-mobile', get_template_directory_uri() . '/css/mobile-style.css', false, $fastfood_version, 'screen' );
		} else {
			wp_enqueue_style( 'ff_general-style', get_stylesheet_uri(), false, $fastfood_version, 'screen' );
			if ( $fastfood_opt['fastfood_wpadminbar_css'] == 1 ) {
				wp_enqueue_style( 'ff_adminbar-style', get_template_directory_uri() . '/css/wpadminbar.css' );
			}
		}
	}
	//print style
	wp_enqueue_style( 'ff_print-style', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'print' );
}

// add scripts
function fastfood_scripts(){
	global $fastfood_opt, $ff_is_printpreview, $fastfood_version, $ff_is_mobile_browser;
	if ( ( $fastfood_opt['fastfood_jsani'] == 1 ) && !$ff_is_mobile_browser ) {
		if ( !$ff_is_printpreview ) { //script not to be loaded in print preview
			wp_enqueue_script( 'fastfoodscript', get_template_directory_uri() . '/js/fastfoodscript.min.js', array( 'jquery' ), $fastfood_version, true  ); //fastfood js
			wp_enqueue_script( 'jquery-ui-effects', get_template_directory_uri() . '/js/jquery-ui-effects-1.8.6.min.js', array( 'jquery' ), '1.8.6', false  ); //fastfood js
		}
	}
	if ( is_singular() && !$ff_is_printpreview ) {
		if ( ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) && !$ff_is_mobile_browser ) {
			wp_enqueue_script( 'ff-comment-reply', get_template_directory_uri() . '/js/comment-reply.min.js' ); //custom comment-reply pop-up box
		} else {
			wp_enqueue_script( 'comment-reply' ); //custom comment-reply pop-up box
		}
	}
}


// show all categories list (redirect to allcat.php if allcat=y)
function fastfood_allcat () {
	if( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
		get_template_part( 'allcat' );
		exit;
	}
}

// Get Recent Comments
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
}


// Get Recent Entries
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
}

// Get Categories List (with posts related)
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
				$post_title_short = __( '(no title)', 'shiword' );
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
}

// Pages Menu
function fastfood_pages_menu() {
	echo '<ul id="mainmenu">';
	wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted
	echo '</ul>';
}

// page hierarchy
function fastfood_multipages(){
	global $post;
	$args = array(
		'post_type' => 'page',
		'post_parent' => $post->ID
		); 
	$childrens = get_posts($args); // retrieve the child pages
	$the_parent_page = $post->post_parent; // retrieve the parent page

	if ( ( $childrens ) || ( $the_parent_page ) ){ ?>
		<div class="metafield">
			<div class="metafield_trigger mft_hier no-mobile" style="right: 40px; width:16px"> </div>
			<div class="metafield_content">
				<?php
				echo __('This page has hierarchy','fastfood') . ' - ';
				if ( $the_parent_page ) {
					$the_parent_link = '<a href="' . get_permalink( $the_parent_page ) . '" title="' . get_the_title( $the_parent_page ) . '">' . get_the_title( $the_parent_page ) . '</a>';
					echo __('Parent page: ','fastfood') . $the_parent_link ; // echoes the parent
				}
				if ( ( $childrens ) && ( $the_parent_page ) ) { echo ' - '; } // if parent & child, echoes the separator
				if ( $childrens ) {
					$the_child_list = '';
					foreach ($childrens as $children) {
						$the_child_list[] = '<a href="' . get_permalink( $children ) . '" title="' . get_the_title( $children ) . '">' . get_the_title( $children ) . '</a>';
					}
					$the_child_list = implode(', ' , $the_child_list);
					echo __('Child pages: ','fastfood') . $the_child_list; // echoes the childs
				}
				?>
			</div>
		</div>
	<?php }
}

//add a fix for embed videos overlying quickbar
function fastfood_content_replace( $content ){
	$content = str_replace( '<param name="allowscriptaccess" value="always">', '<param name="allowscriptaccess" value="always"><param name="wmode" value="transparent">', $content );
	$content = str_replace( '<embed ', '<embed wmode="transparent" ', $content );
	return $content;
}

// create theme option page
function fastfood_create_menu() {
	//create new top-level menu
	$pageopt = add_theme_page( __( 'Theme Options','fastfood' ), __( 'Theme Options','fastfood' ), 'manage_options', 'tb_fastfood_functions', 'fastfood_edit_options' );
	//call register settings function
	add_action( 'admin_init', 'fastfood_register_tb_settings' );
	add_action( 'admin_print_styles-' . $pageopt, 'fastfood_theme_admin_styles' );
	add_action( 'admin_print_styles-widgets.php', 'fastfood_widgets_style' );
}

function fastfood_widgets_style() {
	//add custom stylesheet
	echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/widgets.css" />' . "\n";
}

function fastfood_register_tb_settings() {
	//register fastfood settings
	register_setting( 'ff_settings_group', 'fastfood_options', 'fastfood_sanitaze_options' );
	//add custom stylesheet to admin
	wp_enqueue_style( 'ff-admin-style', get_template_directory_uri() . '/css/ff-admin.css', false, '', 'screen' );
}

// sanitize options value
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

// the custon header style - called only on your theme options page
function fastfood_theme_admin_styles() {
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

// the theme option page
function fastfood_edit_options() {
  if ( !current_user_can( 'manage_options' ) ) {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
	global $fastfood_opt, $fastfood_current_theme;
	$fastfood_coa = fastfood_get_coa();
	
	// options have been updated
	if ( isset( $_REQUEST['updated'] ) ) {
		//return options save message
		echo '<div id="message" class="updated"><p><strong>' . __( 'Options saved.','fastfood' ) . '</strong></p></div>';
	}

	//if options are empty, sets the default values
	if ( empty( $fastfood_opt ) || !isset( $fastfood_opt ) ) {
		foreach ( $fastfood_coa as $key => $val ) {
			$fastfood_opt[$key] = $fastfood_coa[$key]['default'];
		}
		update_option( 'fastfood_options' , $fastfood_opt );
	} else if ( !isset( $fastfood_opt['version'] ) || $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
		// check for unset values and set them to default value -> when updated to new version
		foreach ( $fastfood_coa as $key => $val ) {
			if ( !isset( $fastfood_opt[$key] ) ) $fastfood_opt[$key] = $fastfood_coa[$key]['default'];
		}
		update_option( 'fastfood_options' , $fastfood_opt );
	}
	
?>
	<div class="wrap">
		<div class="icon32" id="icon-themes"><br></div>
		<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>
		<div id="tabs-container">				
			<ul id="selector">
				<li id="fastfood-options-li">
					<div class="wp-menu-image"><br></div>
					<a href="#fastfood-options" onClick="fastfoodSwitchClass('fastfood-options'); return false;"><?php _e( 'Options','fastfood' ); ?></a>
				</li>
				<li id="fastfood-infos-li">
					<div class="wp-menu-image"><br></div>
					<a href="#fastfood-infos" onClick="fastfoodSwitchClass('fastfood-infos'); return false;"><?php _e( 'Theme Info','fastfood' ); ?></a>
				</li>
			</ul>
			<div class="clear"></div>
			<div id="fastfood-options">
				<form method="post" action="options.php">
					<?php settings_fields( 'ff_settings_group' ); ?>
					<div id="stylediv">
						<table style="border-collapse: collapse; width: 100%;border-bottom: 2px groove #fff;">
							<tr style="border-bottom: 2px groove #fff;">
								<th><?php _e( 'name' , 'fastfood' ); ?></th>
								<th><?php _e( 'status' , 'fastfood' ); ?></th>
								<th><?php _e( 'description' , 'fastfood' ); ?></th>
								<th><?php _e( 'require' , 'fastfood' ); ?></th>
							</tr>
						<?php foreach ($fastfood_coa as $key => $val) { ?>
							<?php if ( $fastfood_coa[$key]['type'] == 'chk' ) { ?>
								<tr>
									<td style="width: 220px;font-weight:bold;border-right:1px solid #ccc;"><?php echo $fastfood_coa[$key]['description']; ?></td>
									<td style="width: 20px;border-right:1px solid #ccc;text-align:center;">
										<input name="fastfood_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $fastfood_opt[$key] ); ?> />
									</td>
									<td style="font-style:italic;border-right:1px solid #ccc;"><?php echo $fastfood_coa[$key]['info']; ?></td>
									<td><?php if ( $fastfood_coa[$key]['req'] != '' ) echo $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></td>
								</tr>
							<?php } elseif ( $fastfood_coa[$key]['type'] == 'sel' ) { ?>
								<tr>
									<td style="width: 220px;font-weight:bold;border-right:1px solid #ccc;"><?php echo $fastfood_coa[$key]['description']; ?></td>
									<td style="width: 20px;border-right:1px solid #ccc;text-align:center;">
										<select name="fastfood_options[<?php echo $key; ?>]">
										<?php foreach($fastfood_coa[$key]['options'] as $option) { ?>
											<option value="<?php echo $option; ?>" <?php if ( $fastfood_opt[$key] == $option ) echo " selected "; ?>><?php echo $option; ?></option>
										<?php } ?>
										</select>
									</td>
									<td style="font-style:italic;border-right:1px solid #ccc;"><?php echo $fastfood_coa[$key]['info']; ?></td>
									<td><?php if ( $fastfood_coa[$key]['req'] != '' ) echo $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></td>
								</tr>
							<?php }	?>
						<?php }	?>
						</table>
					</div>
					<div>
						<input type="hidden" name="fastfood_options[hidden_opt]" value="default" />
						<input class="button" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
						<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="themes.php?page=functions" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a>
					</div>
				</form>
				<div class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc;">
					<small>
						<?php _e( 'If you like/dislike this theme, or if you encounter any issues using it, please let us know it.', 'fastfood' ); ?><br />
						<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/tema-per-wordpress-fastfood' ); ?>" title="Fastfood theme" target="_blank"><?php _e( 'Leave a feedback', 'fastfood' ); ?></a>
					</small>
				</div>
				<div class="stylediv" style="clear: both; text-align: center; border: 1px solid #ccc; margin-top: 10px;">
					<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/temi-wp/wordpress-themes-translations' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
				</div>
			</div>
			<div id="fastfood-infos">
				<?php esc_attr( get_template_part( 'readme' ) ); ?>
			</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript">
			/* <![CDATA[ */
			function fastfoodSwitchClass(a) { // simple animation for option tabs
				switch(a) {
					case 'fastfood-options':
						document.getElementById('fastfood-infos').className = 'tab-hidden';
						document.getElementById('fastfood-options').className = '';
						document.getElementById('fastfood-options-li').className = 'tab-selected';
						document.getElementById('fastfood-infos-li').className = '';
					break;
					case 'fastfood-infos':
						document.getElementById('fastfood-infos').className = '';
						document.getElementById('fastfood-options').className = 'tab-hidden';
						document.getElementById('fastfood-options-li').className = '';
						document.getElementById('fastfood-infos-li').className = 'tab-selected';
					break;
				}
			}
			document.getElementById('fastfood-infos').className = 'tab-hidden';
			document.getElementById('fastfood-options-li').className = 'tab-selected';
			/* ]]> */
		</script>
	</div>
<?php
}

// set up custom colors and header image
if ( !function_exists( 'fastfood_setup' ) ) {
	function fastfood_setup() {
		
		// Register localization support
		load_theme_textdomain('fastfood', TEMPLATEPATH . '/languages' );
		// Theme uses wp_nav_menu() in one location
		register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'fastfood' )	) );
		// Register Features Support
		add_theme_support( 'automatic-feed-links' );
		// Thumbnails support
		add_theme_support( 'post-thumbnails' );
		// the editor stylesheet support
		add_editor_style( 'css/editor-style.css' );
	
		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '404040' );
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define( 'HEADER_IMAGE', '%s/images/headers/tree.jpg' );
	
		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to fastfood_header_image_width and fastfood_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', 848 );
		define( 'HEADER_IMAGE_HEIGHT', 120 );
	
		// Support text inside the header image.
		define( 'NO_HEADER_TEXT', false );
	
		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See fastfood_admin_header_style(), below.
		add_custom_image_header( 'fastfood_header_style', 'fastfood_admin_header_style' );
		
		// Add a way for the custom background to be styled in the admin panel that controls
		add_custom_background( 'fastfood_custom_bg' , '' , '' );
	
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
			'abstract' => array(
				'url' => '%s/images/headers/abstract.jpg',
				'thumbnail_url' => '%s/images/headers/abstract-thumbnail.jpg',
				'description' => __( 'Abstract', 'fastfood' )
			)
		) );
	}
}

// print a reminder message for set the options after the theme is installed
function fastfood_setopt_admin_notice() {
	echo '<div class="updated"><p><strong>' . sprintf( __( "Fastfood theme says: \"Dont forget to set <a href=\"%s\">my options</a> and the header image!\"", 'fastfood' ), get_admin_url() . 'themes.php?page=tb_fastfood_functions' ) . '</strong></p></div>';
}
if ( current_user_can( 'manage_options' ) && isset( $_GET['activated'] ) && $pagenow == "themes.php" ) {
	add_action( 'admin_notices', 'fastfood_setopt_admin_notice' );
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_header_style' ) ) {
	function fastfood_admin_header_style() {	
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-header.css" />' . "\n";
	}
}

//  the custon header style - add style customization to page - gets included in the site header
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
		}
		#head h1 a, #head .description {
			<?php echo $style; ?>
		}
		body {
			font-size: <?php echo $fastfood_opt['fastfood_font_size']; ?>;
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
	</style>
	<![endif]-->
	<?php
}

// custom background style - gets included in the site header
function fastfood_custom_bg() {
	$background = get_background_image();
	$color = get_background_color();
	if ( ! $background && ! $color ) return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) $repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) ) $position = 'left';
		$position = " background-position: top $position;";

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

// set the custom excerpt length
function new_excerpt_length( $length ) {
	return 50;
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

// display a simple login form in quickbar
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

// add 'quoted on' before trackback/pingback comments link
function fastfood_add_quoted_on( $return ) {
	global $comment;
	$text = '';
	if ( get_comment_type() != 'comment' ) {
		$text = '<span style="font-weight: normal;">' . __( 'this post is quoted by', 'fastfood' ) . ' </span>';
	}
	return $text . $return;
}

//Add new contact methods to author panel
function fastfood_new_contactmethods( $contactmethods ) {
	//add Twitter
	$contactmethods['twitter'] = 'Twitter';
	//add Facebook
	$contactmethods['facebook'] = 'Facebook';

	return $contactmethods;
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

// load the custom widgets module
get_template_part('widgets');

?>