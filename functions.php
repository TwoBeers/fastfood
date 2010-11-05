<?php
load_theme_textdomain('fastfood', TEMPLATEPATH . '/languages' );

// Register Features Support
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' ); // Thumbnails support
/* add_theme_support('menus'); NB:No need, becuse automatically registered by register_nav_menus() on line 14 (http://codex.wordpress.org/Function_Reference/register_nav_menus/#Notes) */

// Set the content width based on the theme's design
if ( ! isset( $content_width ) ) {
	$content_width = 560;
}

//load options in $fastfood_opt variable, globally retrieved in php files
$fastfood_opt = fastfood_get_opt();

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
	//shows print preview / normal view
	if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
		wp_enqueue_style( 'print-style-preview', get_bloginfo( 'stylesheet_directory' ) . '/print_preview.css', false, $fastfood_version, 'screen' );
		$fastfood_printme = true;
	} else { //normal view 
		wp_enqueue_style( 'general-style', get_stylesheet_uri(), false, $fastfood_version, 'screen' );
		$fastfood_printme = false;
	}
	//print style
	wp_enqueue_style( 'print-style', get_bloginfo( 'stylesheet_directory' ) . '/print.css', false, $fastfood_version, 'print' );
}

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
	$comments = get_comments( 'status=approve&number=10' );
	//build up recent comments widget
	if ( $comments ) {
	    foreach ( $comments as $comment ) {
				$post_title = get_the_title( $comment->comment_post_ID );
				if ( strlen( $post_title ) > 35 ) {
					$post_title_short = substr( $post_title,0,35 ) . '&hellip;';
				} else {
					$post_title_short = $post_title;
				}
				if ( $post_title_short == "" ) {
					$post_title_short = __( '(no title)' );
				}
		    echo '<li>'. $comment->comment_author . ' ' . __( 'about','fastfood' ) . ' <a href="' . get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a><div class="preview">';
				//check for password protected posts
				if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) {
					echo '[' . __( 'No preview: this is a comment of a protected post','fastfood' ) . ']';
				} else {
					$comment_string = improved_trim_excerpt( $comment->comment_content );
					echo $comment_string;
				}
		    echo '</div></li>';
	    }
	} else {
		echo '<li>No Comments Yet</li>';
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
	wp_list_pages( 'title_li=' );
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
			<div class="metafield_trigger" style="background-image: url('<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>/hiera.png'); right: 40px; width:16px"> </div>
			<div class="metafield_content">
				<?php
				echo __('This page has relationships','fastfood') . ' - ';
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
add_action( 'admin_menu', 'fastfood_create_menu' );

function fastfood_create_menu() {
	//create new top-level menu
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'manage_options', 'tb-fastfood-functions', 'edit_fastfood_options' );
	//call register settings function
	add_action( 'admin_init', 'register_tb_fastfood_settings' );
}


function register_tb_fastfood_settings() {
	//register fastfood settings
	register_setting( 'ff_settings_group', 'fastfood_options' );
	//add custom stylesheet to admin
	wp_enqueue_style( 'ff-options-style', get_bloginfo( 'stylesheet_directory' ) . '/ff-opt.css', false, '', 'screen' );

}

function edit_fastfood_options() {
	global $fastfood_opt;
	if( empty( $fastfood_opt ) ) {
		$fastfood_opt['fastfood_jsani'] = 'active';
		$fastfood_opt['fastfood_qbar'] = 'show';
		add_option( 'fastfood_options', $fastfood_opt, '', 'yes' );
	}
	if ( isset( $_REQUEST['updated'] ) ) echo '<div id="message" class="updated"><p><strong>' . __( 'Options saved.' ) . '</strong></p></div>';
?>
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
		/* ]]> */
	</script>
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
					<div>
						<p><?php _e( 'Sliding Menu','fastfood' ); ?></p>
						<small><?php _e( 'Hide/Show sliding menu','fastfood' ); ?></small>
						<div class="ff_opt_input">
							<?php
							$fastfood_qbar = array( 'show' => __( 'show','fastfood' ) , 'hide' => __( 'hide','fastfood' ) );
							foreach ( $fastfood_qbar as $fastfood_qbar_value => $fastfood_qbar_option ) {
								$fastfood_qbar_selected = ( $fastfood_qbar_value == $fastfood_opt['fastfood_qbar'] ) ? ' checked="checked"' : '';
								echo '<input type="radio" name="fastfood_options[fastfood_qbar]" title="' . $fastfood_qbar_option . '" value="' . $fastfood_qbar_value . '" ' . $fastfood_qbar_selected . ' />' . $fastfood_qbar_option . '&nbsp;&nbsp;';
							}
							?>
						</div>
					</div>
					<div>
						<p><?php _e( 'Javascript Animations','fastfood' ); ?></p>
						<small><?php _e( 'Try disable animations if you encountered problems with javascript','fastfood' ); ?></small>
						<div class="ff_opt_input">
							<?php
							$fastfood_jsani = array( 'active' => __( 'active','fastfood' ) , 'inactive' => __( 'inactive','fastfood' ) );
							foreach ( $fastfood_jsani as $fastfood_jsani_value => $fastfood_jsani_option ) {
								$fastfood_jsani_selected = ( $fastfood_jsani_value == $fastfood_opt['fastfood_jsani'] ) ? ' checked="checked"' : '';
								echo '<input type="radio" name="fastfood_options[fastfood_jsani]" title="' . $fastfood_jsani_option . '" value="' . $fastfood_jsani_value . '" ' . $fastfood_jsani_selected . ' />' . $fastfood_jsani_option . '&nbsp;&nbsp;';
							}
							?>
						</div>
					</div>
					<div>
						<input class="button" type="submit" name="Submit" value="<?php _e( 'Update Options','fastfood' ); ?>" />
						<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="themes.php?page=functions" target="_self"><?php _e( 'Undo Changes','fastfood' ); ?></a>
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
			document.getElementById('fastfood-infos').className = 'tab-hidden';
			document.getElementById('fastfood-options-li').className = 'tab-selected';
			/* ]]> */
		</script>
	</div>
<?php
}

//output preview
function fastfood_preview(){
	global $fastfood_opt;
	$base_url = get_bloginfo( 'stylesheet_directory' ) . '/images/';

	echo '<div id="theme_preview">
					<div id="ff_head" style="background: transparent url(\'' . $base_url . $fastfood_opt['fastfood_himg'] . '.png\') right bottom no-repeat; height:130px; width:100%; position:absolute; right:0px; bottom:0px;"></div>
				</div>';
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
	
		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', false );
	
		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See fastfood_admin_header_style(), below.
		add_custom_image_header( '', 'fastfood_admin_header_style' );
	
		// ... and thus ends the changeable header business.
	
		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'tree' => array(
				'url' => '%s/images/headers/tree.jpg',
				'thumbnail_url' => '%s/images/headers/tree-thumbnail.jpg',
				'description' => 'Ancient Tree'
			),
			'vector' => array(
				'url' => '%s/images/headers/vector.jpg',
				'thumbnail_url' => '%s/images/headers/vector-thumbnail.jpg',
				'description' => 'Vector Flowers'
			),
			'globe' => array(
				'url' => '%s/images/headers/globe.jpg',
				'thumbnail_url' => '%s/images/headers/globe-thumbnail.jpg',
				'description' => 'Globe'
			),
			'bamboo' => array(
				'url' => '%s/images/headers/bamboo.jpg',
				'thumbnail_url' => '%s/images/headers/bamboo-thumbnail.jpg',
				'description' => 'Bamboo Forest'
			)
		) );
	}
}

if ( ! function_exists( 'fastfood_admin_header_style' ) ) {
	// Styles the header image displayed on the Appearance > Header admin panel.
	function fastfood_admin_header_style() {
	?>
	<style type="text/css">
	#headimg {
		border: 1px solid #777777 !important;
		background-position:right bottom;
		-moz-box-shadow: 0 0 7px #000000;
		box-shadow: 0 0 7px #000000;
		-webkit-box-shadow: 0 0 7px #000000;
		-khtml-box-shadow: 0 0 7px #000000;
	}
	#headimg h1 {
		margin:20px 20px 5px;
	}
	#headimg #name {
		font-size:1.5em;
		font-style:italic;
		text-decoration:none;
		text-shadow:1px 1px 0 #FFFFFF;
	}
	#headimg #desc {
		font-size:11px;
		text-shadow:1px 1px 0 #FFFFFF;
		font-style:italic;
		padding-left:20px;
	}
	</style>
	<?php
	}
}

function fastfood_get_opt() {
	$fastfood_options = get_option( 'fastfood_options' );
	if( empty( $fastfood_options ) ) {
		$fastfood_options['fastfood_jsani'] = 'active';
		$fastfood_options['fastfood_qbar'] = 'show';
	}
	return $fastfood_options;
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

?>