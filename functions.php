<?php
load_theme_textdomain('fastfood', TEMPLATEPATH . '/languages' );

// Register Features Support
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' ); // Thumbnails support

// Set the content width based on the theme's design
if ( ! isset( $content_width ) ) {
	$content_width = 560;
}

//complete options array, with defaults values, description, infos and required option
$fastfood_coa = array(
	'fastfood_qbar' => array( 'default'=>'true','description'=>__( 'sliding menu', 'fastfood' ),'info'=>__( '[default = enabled]', 'fastfood' ),'req'=>'' ),
	'fastfood_qbar_user' => array( 'default'=>'true','description'=>__( '-- user', 'fastfood' ),'info'=>__( '[default = enabled]', 'fastfood' ),'req'=>'fastfood_qbar' ),
	'fastfood_qbar_reccom' => array( 'default'=>'true','description'=>__( '-- recent comments', 'fastfood' ),'info'=>__( '[default = enabled]', 'fastfood' ),'req'=>'fastfood_qbar' ),
	'fastfood_qbar_cat' => array( 'default'=>'true','description'=>__( '-- categories','fastfood' ),'info'=>__( '[default = enabled]', 'fastfood' ),'req'=>'fastfood_qbar' ),
	'fastfood_qbar_recpost' => array( 'default'=>'true','description'=>__( '-- recent posts', 'fastfood' ),'info'=>__( '[default = enabled]', 'fastfood' ),'req'=>'fastfood_qbar' ),
	'fastfood_rsidebpages' => array( 'default'=>'false','description'=>__( 'sidebar on pages', 'fastfood' ),'info'=>__( 'show right sidebar on pages [default = disabled]', 'fastfood' ),'req'=>'' ),
	'fastfood_rsidebposts' => array( 'default'=>'false','description'=>__( 'sidebar on posts', 'fastfood' ),'info'=>__( 'show right sidebar on posts [default = disabled]', 'fastfood' ),'req'=>'' ),
	'fastfood_jsani' => array( 'default'=>'true','description'=>__( 'javascript animations', 'fastfood' ),'info'=>__( 'try disable animations if you encountered problems with javascript [default = enabled]', 'fastfood' ),'req'=>'' ),
	'fastfood_cust_comrep' => array( 'default'=>'true','description'=>__( 'custom comment reply form', 'fastfood' ),'info'=>__( 'custom floating form for post/reply comments [default = enabled]', 'fastfood' ),'req'=>'' ),
	'fastfood_tbcred' => array( 'default'=>'true','description'=>__( 'theme credits', 'fastfood' ),'info'=>__( "please, don't hide theme credits [default = enabled]", 'fastfood' ),'req'=>'' )
);

//load options in $fastfood_opt variable, globally retrieved in php files
$fastfood_opt = fastfood_get_opt();

// check if in preview mode or not
$is_ff_printpreview = false;
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
	$is_ff_printpreview = true;
}

//get theme version
if ( get_theme( 'Fastfood' ) ) {
	$current_theme = get_theme( 'Fastfood' );
	$fastfood_version = $current_theme['Version'];
} else {
	$fastfood_version = "";
}

// Theme uses wp_nav_menu() in one location
register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'fastfood' )	) );

function fastfood_widgets_init() {
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

// Register sidebars by running fastfood_widgets_init() on the widgets_init hook
add_action( 'widgets_init', 'fastfood_widgets_init' );

// Add stylesheets to page
function fastfood_stylesheet(){
	global $fastfood_version;
	global $is_ff_printpreview;
	//shows print preview / normal view
	if ( $is_ff_printpreview ) { //print preview
		wp_enqueue_style( 'print-style-preview', get_bloginfo( 'stylesheet_directory' ) . '/css/print.css', false, $fastfood_version, 'screen' );
		wp_enqueue_style( 'general-style-preview', get_bloginfo( 'stylesheet_directory' ) . '/css/print_preview.css', false, $fastfood_version, 'screen' );
	} else { //normal view 
		wp_enqueue_style( 'general-style', get_stylesheet_uri(), false, $fastfood_version, 'screen' );
	}
	//print style
	wp_enqueue_style( 'print-style', get_bloginfo( 'stylesheet_directory' ) . '/css/print.css', false, $fastfood_version, 'print' );
}
add_action( 'wp_print_styles', 'fastfood_stylesheet' );

// add scripts
function fastfood_scripts(){
	global $fastfood_opt;
	global $is_ff_printpreview;
	global $fastfood_version;
	if ( $fastfood_opt['fastfood_jsani'] == 'true' ) {
		if ( !$is_ff_printpreview ) { //script not to be loaded in print preview
			wp_enqueue_script( 'fastfoodscript', get_bloginfo( 'stylesheet_directory' ) . '/js/fastfoodscript.min.js',array('jquery'),$fastfood_version, true  ); //fastfood js
			wp_enqueue_script( 'jquery-ui-effects', get_bloginfo( 'stylesheet_directory' ) . '/js/jquery-ui-effects-1.8.6.min.js',array('jquery'),'1.8.6', false  ); //fastfood js
		}
	}
	if ( is_singular() && !$is_ff_printpreview ) {
		if ( $fastfood_opt['fastfood_cust_comrep'] == 'true' ) {
			wp_enqueue_script( 'ff-comment-reply', get_bloginfo( 'stylesheet_directory' ) . '/js/comment-reply.min.js' ); //custom comment-reply pop-up box
		} else {
			wp_enqueue_script( 'comment-reply' ); //custom comment-reply pop-up box
		}
	}
}
add_action( 'template_redirect', 'fastfood_scripts' );


// show all categories list (redirect to allcat.php if allcat=y)
function ff_allcat () {
	if( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
		get_template_part( 'allcat' );
		exit;
	}
}
add_action( 'template_redirect', 'ff_allcat' );

// Get Recent Comments
function get_fastfood_recentcomments() {
	$comments = get_comments( 'status=approve&number=10&type=comment' ); // valid type values (not documented) : 'pingback','trackback','comment'
	if ( $comments ) {
		foreach ( $comments as $comment ) {
			$post_title = get_the_title( $comment->comment_post_ID );
			if ( strlen( $post_title ) > 35 ) { //shrink the post title if > 35 chars
				$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
			} else {
				$post_title_short = $post_title;
			}
			if ( $post_title_short == "" ) {
				$post_title_short = __( '(no title)' );
			}
			$com_auth = $comment->comment_author;
			if ( strlen( $com_auth ) > 35 ) {  //shrink the comment author if > 35 chars
				$com_auth = substr( $com_auth,0,35 ) . '&hellip;';
			}
		    echo '<li>'. $com_auth . ' ' . __( 'about','fastfood' ) . ' <a href="' . get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a><div class="preview">';
		if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) {
			echo '[' . __( 'No preview: this is a comment of a protected post', 'fastfood' ) . ']';
		} else {
			$comment_string = improved_trim_excerpt( $comment->comment_content );
			echo $comment_string;
		}
			echo '</div></li>';
		}
	} else {
		echo '<li>' . __( 'No comments yet.' ) . '</li>';
	}
}


// Get Recent Entries
function get_fastfood_recententries( $mode = '', $limit = 10 ) {
	$lastposts = get_posts( 'numberposts=10' );
	foreach( $lastposts as $post ) {
		setup_postdata( $post );
		$post_title = esc_html( $post->post_title );
		if ( strlen( $post_title ) > 35 ) {
			$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
		} else {
			$post_title_short = $post_title;
		}
		if ( $post_title_short == "" ) {
			$post_title_short = __( '(no title)' );
		}
		echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . __( 'by','fastfood' ) . ' ' . get_the_author() . '<div class="preview">';
		if ( post_password_required( $post ) ) {
			echo '<img class="alignleft wp-post-image" src="' . get_bloginfo( 'stylesheet_directory' ) . '"/images/thumb.png" alt="thumb" title="' . $post_title_short . '" />';
			echo '[' . __( 'No preview: this is a protected post','fastfood' ) . ']';
		} else {
			the_post_thumbnail( array( 50,50 ), array( 'class' => 'alignleft' ) );
			echo improved_trim_excerpt( $post->post_content );
		}
		echo '</div></li>';
	}
}

// Get Categories List (with posts related)
function get_fastfood_categories_wpr() {
	$args=array(
		'orderby' => 'count',
		'number' => 10,
		'order' => 'DESC'
	);
	$categories=get_categories( $args );
	foreach( $categories as $category ) {
		echo '<li class="ql_cat_li"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name . '</a> (' . $category->count . ')<div class="cat_preview"><div class="mentit">' . __( 'Recent Posts' ) . '</div><ul class="solid_ul">';
		$tmp_cat_ID = $category->cat_ID;
		$post_search_args = array(
			'numberposts' => 5,
			'category' => $tmp_cat_ID
			);
		$lastcatposts = get_posts( $post_search_args );
		foreach( $lastcatposts as $post ) {
			setup_postdata( $post );
			$post_title = esc_html( $post->post_title );
			if ( strlen( $post_title ) > 35 ) {
				$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
			} else {
				$post_title_short = $post_title;
			}
			if ( $post_title_short == "" ) {
				$post_title_short = __( '(no title)' );
			}
			echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . __( 'by','fastfood' ) . ' ' . get_the_author() . '</li>';
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
			<div class="metafield_trigger mft_hier" style="right: 40px; width:16px"> </div>
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


//add a fix for embed videos overlaing quickbar
function fastfood_content_replace(){
	$content = get_the_content();
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$content = str_replace( '<param name="allowscriptaccess" value="always">', '<param name="allowscriptaccess" value="always"><param name="wmode" value="transparent">', $content );
	$content = str_replace( '<embed ', '<embed wmode="transparent" ', $content );
	echo $content;
}

// create custom plugin settings menu
function fastfood_create_menu() {
	//create new top-level menu
	$pageopt = add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'manage_options', 'tb-fastfood-functions', 'edit_fastfood_options' );
	//call register settings function
	add_action( 'admin_init', 'register_tb_fastfood_settings' );
	add_action( 'admin_print_styles-' . $pageopt, 'my_theme_admin_styles' );

}
add_action( 'admin_menu', 'fastfood_create_menu' );

function register_tb_fastfood_settings() {
	//register fastfood settings
	register_setting( 'ff_settings_group', 'fastfood_options' );
	//add custom stylesheet to admin
	wp_enqueue_style( 'ff-admin-style', get_bloginfo( 'stylesheet_directory' ) . '/css/ff-admin.css', false, '', 'screen' );
}

/* called only on your theme options page, enqueue our stylesheet here */
function my_theme_admin_styles() {
	wp_enqueue_style( 'ff-options-style', get_bloginfo( 'stylesheet_directory' ) . '/css/ff-opt.css', false, '', 'screen' );
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

function edit_fastfood_options() {
	//the option page
	global $fastfood_coa;
	$fastfood_options = get_option( 'fastfood_options' );
	//if options are empty, sets the default values
	
	if ( isset( $_REQUEST['updated'] ) ) { // options has been updated
	
		// check for updated values and return false for disabled ones
		foreach ( $fastfood_coa as $key => $val ) {
			if( !isset( $fastfood_options[$key] ) ) $fastfood_options[$key] = 'false';
		}
		// check for required options
		foreach ( $fastfood_coa as $key => $val ) {
			if ( $fastfood_coa[$key]['req'] != '' ) { if ( $fastfood_options[$fastfood_coa[$key]['req']] == 'false' ) $fastfood_options[$key] = 'false'; }
		}
		$fastfood_options['hidden_opt'] ='default'; //this hidden option avoids empty $fastfood_options when updated
		update_option( 'fastfood_options' , $fastfood_options );

		//return options save message
		echo '<div id="message" class="updated"><p><strong>' . __( 'Options saved.' ) . '</strong></p></div>';
		
	} else { // no update

		if ( empty( $fastfood_options ) ) {
			foreach ( $fastfood_coa as $key => $val ) {
				$fastfood_options[$key] = $fastfood_coa[$key]['default'];
			}
			$fastfood_options['hidden_opt'] ='default'; //this hidden option avoids empty $fastfood_options when updated
		} else {
			// check for unset values and set them to default value
			foreach ( $fastfood_coa as $key => $val ) {
				if ( !isset( $fastfood_options[$key] ) ) $fastfood_options[$key] = $fastfood_coa[$key]['default'];
			}
		}
		add_option( 'fastfood_options' , $fastfood_options, '' , 'yes' );
	}
?>
	<div class="wrap">
		<div class="icon32" id="icon-themes"><br></div>
		<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options' ); ?></h2>
		<div id="tabs-container">				
			<ul id="selector">
				<li id="fastfood-options-li">
					<div class="wp-menu-image"><br></div>
					<a href="#fastfood-options" onClick="fastfoodSwitchClass('fastfood-options'); return false;"><?php _e( 'Options' ); ?></a>
				</li>
				<li id="fastfood-infos-li">
					<div class="wp-menu-image"><br></div>
					<a href="#fastfood-infos" onClick="fastfoodSwitchClass('fastfood-infos'); return false;"><?php _e( 'About' ); ?></a>
				</li>
			</ul>
			<div class="clear"></div>
			<div id="fastfood-options">
				<br />
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
							<tr>
								<td style="width: 220px;font-weight:bold;border-right:1px solid #CCCCCC;"><?php echo $fastfood_coa[$key]['description']; ?></td>
								<td style="width: 20px;border-right:1px solid #CCCCCC;text-align:center;">
									<input name="fastfood_options[<?php echo $key; ?>]" value="true" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 'true' , $fastfood_options[$key] ); ?> />
								</td>
								<td style="font-style:italic;border-right:1px solid #CCCCCC;"><?php echo $fastfood_coa[$key]['info']; ?></td>
								<td><?php if ( $fastfood_coa[$key]['req'] != '' ) echo $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></td>
							</tr>
						<?php }	?>
						</table>
					</div>
					<div>
						<input type="hidden" name="fastfood_options[hidden_opt]" value="default" />
						<input class="button" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
						<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="themes.php?page=functions" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a>
					</div>
				</form>
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

// Tell WordPress to run fastfood_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'fastfood_setup' );

if ( !function_exists( 'fastfood_setup' ) ) {
	function fastfood_setup() {
	
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
			)
		) );
	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_header_style' ) ) {
	function fastfood_admin_header_style() {	
		echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo( 'stylesheet_directory' ) . '/css/custom-header.css" />' . "\n";
	}
}

// Add style customization to page - gets included in the site header
function fastfood_header_style(){

	global $is_ff_printpreview;
	if ( $is_ff_printpreview ) return;

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

//get the theme options values. uses default values if options are empty or unset
function fastfood_get_opt() {

	global $fastfood_coa;
	
	$fastfood_options = get_option( 'fastfood_options' );
	foreach ( $fastfood_coa as $key => $val ) {
		if( ( !isset( $fastfood_options[$key] ) ) || empty( $fastfood_options[$key] ) ) { 
			$fastfood_options[$key] = $fastfood_coa[$key]['default']; 
		}
	}
	return ( $fastfood_options );
}

//custom excerpt maker
function improved_trim_excerpt( $text ) {
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );
	$text = preg_replace( '@<script[^>]*?>.*?</script>@si', '', $text );
	$text = strip_tags( $text, '<p>' );
	$text = preg_replace( '@<p[^>]*?>@si', '', $text );
	$text = preg_replace( '@</p>@si', '<br/>', $text );
	$excerpt_length = 50;
	$words = explode(' ', $text, $excerpt_length + 1);
	if ( count( $words ) > $excerpt_length ) {
		array_pop( $words );
		array_push( $words, '[...]' );
		$text = implode( ' ', $words );
	}
	return $text;
}

// set the custom excerpt length
function new_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'new_excerpt_length' );

//add a default gravatar
if ( !function_exists( 'fastfood_addgravatar' ) ) {
	function fastfood_addgravatar( $avatar_defaults ) {
	  $myavatar = get_bloginfo( 'stylesheet_directory' ) . '/images/user.png';
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
				if ( $page_links['next']['title'] == '' ) $page_links['next']['title'] = __( '(no title)' );
			} elseif ( $k == ( count( $pages ) -1 ) ) { // is last page
				$page_links['prev']['link'] = get_page_link($pages[$k - 1]->ID);
				$page_links['prev']['title'] = $pages[$k - 1]->post_title;
				if ( $page_links['prev']['title'] == '' ) $page_links['prev']['title'] = __( '(no title)' );
			} else {
				$page_links['next']['link'] = get_page_link($pages[$k + 1]->ID);
				$page_links['next']['title'] = $pages[$k + 1]->post_title;
				if ( $page_links['next']['title'] == '' ) $page_links['next']['title'] = __( '(no title)' );
				$page_links['prev']['link'] = get_page_link($pages[$k - 1]->ID);
				$page_links['prev']['title'] = $pages[$k - 1]->post_title;
				if ( $page_links['prev']['title'] == '' ) $page_links['prev']['title'] = __( '(no title)' );
			}
		}
	}
	return $page_links;
}

// display a simple login form in quickbar
function fastfood_mini_login() {
	$args = array(
		'redirect' => home_url(),
		'form_id' => 'ff-loginform',
		'id_username' => 'ff-user_login',
		'id_password' => 'ff-user_pass',
		'id_remember' => 'ff-rememberme',
		'id_submit' => 'ff-submit' );
	?>
	<li class="ql_cat_li">
		<a title="<?php _e( 'Log in' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ); ?></a>
		<div class="cat_preview">
			<div class="mentit"><?php _e( 'Log in' ); ?></div>
			<div id="ff_minilogin">
				<?php wp_login_form($args); ?>
			</div>
		</div>
	</li>

	<?php
}

?>