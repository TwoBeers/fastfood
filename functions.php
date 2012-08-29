<?php
/**** begin theme hooks ****/
// Tell WordPress to run fastfood_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'fastfood_setup' );
// Tell WordPress to run fastfood_default_options()
add_action( 'admin_init', 'fastfood_default_options' );
// Register sidebars by running fastfood_widget_area_init() on the widgets_init hook
add_action( 'widgets_init', 'fastfood_widget_area_init' );
// Add stylesheets
add_action( 'wp_enqueue_scripts', 'fastfood_stylesheet' );
// Add js animations
add_action( 'wp_head', 'fastfood_localize_js' );
add_action( 'template_redirect', 'fastfood_scripts' );
add_action( 'wp_footer', 'fastfood_init_scripts' );
add_action( 'wp_footer', 'fastfood_I_like_it_js' );
// Add custom category page
add_action( 'template_redirect', 'fastfood_allcat' );
// post expander ajax request
add_action( 'init', 'fastfood_post_expander_activate' );
// add links to admin bar
add_action( 'admin_bar_menu', 'fastfood_admin_bar_plus', 999 );  
// deregister styles
add_action( 'wp_print_styles', 'fastfood_deregister_styles', 100 );
// Custom filters
add_filter( 'the_content', 'fastfood_content_replace' );
add_filter( 'excerpt_length', 'fastfood_excerpt_length' );
add_filter( 'get_comment_author_link', 'fastfood_add_quoted_on' );
add_filter( 'user_contactmethods', 'fastfood_new_contactmethods', 10, 1 );
add_filter( 'img_caption_shortcode', 'fastfood_img_caption_shortcode', 10, 3 );
add_filter( 'the_title', 'fastfood_title_tags_filter', 10, 2 );
add_filter( 'excerpt_more', 'fastfood_excerpt_more' );
add_filter( 'the_content_more_link', 'fastfood_more_link', 10, 2 );
//add_filter( 'get_search_form', 'fastfood_search_form' );

/**** end theme hooks ****/

// load theme options in $fastfood_opt variable, globally retrieved in php files
$fastfood_opt = get_option( 'fastfood_options' );

// check if is mobile browser
$ff_is_mobile_browser = false;

// load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes)
require_once( 'mobile/core-mobile.php' ); // load mobile functions
require_once( 'lib/hooks.php' ); // load the custom hooks
require_once( 'lib/gallery-editor.php' ); // load the gallery editor
require_once( 'lib/my-custom-background.php' ); // load the custom background feature
require_once( 'lib/header-image-slider.php' ); // load the custom header stuff
require_once( 'lib/admin.php' ); // load the admin stuff
if ( $fastfood_opt['fastfood_audio_player'] == 1 ) require_once( 'lib/audio-player.php' ); // load the audio player module
if ( $fastfood_opt['fastfood_custom_widgets'] == 1 ) require_once( 'lib/widgets.php' ); // load the custom widgets module

// check if is ie6
$ff_is_ie6 = fastfood_ie6_detect();

function fastfood_ie6_detect() {
if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 6' ) !== false ) && !( strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera' ) !== false ) ) {
		return true;
	} else {
		return false;
	}
}

// check if in allcat view
$ff_is_allcat_page = fastfood_allcat_detect();

function fastfood_allcat_detect() {
	if( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
		return true;
	} else {
		return false;
	}
}

// check if in preview mode or not
$ff_is_printpreview = fastfood_print_preview_detect();

function fastfood_print_preview_detect() {
	if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) { //print preview
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

// get theme version
if ( function_exists( 'wp_get_theme' ) ) {
	$fastfood_theme = wp_get_theme( 'fastfood' );
	$fastfood_current_theme = wp_get_theme();
} else { // Compatibility with versions of WordPress prior to 3.4.
	$fastfood_theme = get_theme( 'Fastfood' );
	$fastfood_current_theme = get_current_theme();
}
$fastfood_version = $fastfood_theme? $fastfood_theme['Version'] : '';

// is sidebar visible?
if ( !function_exists( 'fastfood_use_sidebar' ) ) {
	function fastfood_use_sidebar() {
		global $fastfood_opt;
		if ( !is_singular() && $fastfood_opt['fastfood_rsidebindexes'] == 0 ) return false;
		if ( is_page() && $fastfood_opt['fastfood_rsidebpages'] == 0 ) return false;
		if ( is_single() && $fastfood_opt['fastfood_rsidebposts'] == 0 ) return false;
		return true;
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

		// Area 7, located after post/page content.
		register_sidebar( array(
			'name' => __( 'Post/Page Widget Area', 'fastfood' ),
			'id' => 'post-widgets-area',
			'description' => __( "The widget area after the post/page content. It's visible only in single posts/pages/attachments", 'fastfood' ),
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
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );

		// Area 4, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Second Footer Widget Area', 'fastfood' ),
			'id' => 'second-footer-widget-area',
			'description' => __( 'The second footer widget area', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );

		// Area 5, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Third Footer Widget Area', 'fastfood' ),
			'id' => 'third-footer-widget-area',
			'description' => __( 'The third footer widget area', 'fastfood' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );

		// Area 6, located in page 404.
		register_sidebar( array(
			'name' => __( 'Page 404', 'fastfood' ),
			'id' => '404-widgets-area',
			'description' => __( 'Enrich the page 404 with some useful widgets', 'fastfood' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="w_title">',
			'after_title' => '</div>',
		) );
	}
}

// skip every sidebar if in print preview
if ( !function_exists( 'fastfood_get_sidebar' ) ) {
	function fastfood_get_sidebar( $name = '' ) {
		global $ff_is_printpreview;
		
		if ( $ff_is_printpreview ) return;
		
		get_sidebar( $name );
		
	}
}

// deregister style for WP-Pagenavi plugin (if installed)
function fastfood_deregister_styles() {
	wp_deregister_style( 'wp-pagenavi' );
}

// Add stylesheets to page
if ( !function_exists( 'fastfood_stylesheet' ) ) {
	function fastfood_stylesheet(){
		global $fastfood_opt, $fastfood_version, $ff_is_printpreview, $ff_is_mobile_browser, $ff_is_ie6;
		if ( is_admin() || $ff_is_mobile_browser ) return;
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
			if ( ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) ) {
				wp_enqueue_style( 'thickbox' );
			}
			wp_enqueue_style( 'ff_general-style', get_stylesheet_uri(), false, $fastfood_version, 'screen' );
			if ( $fastfood_opt['fastfood_wpadminbar_css'] == 1 ) {
				wp_enqueue_style( 'ff_adminbar-style', get_template_directory_uri() . '/css/wpadminbar.css' );
			}
			//google font
			if ( $fastfood_opt['fastfood_google_font_family'] ) wp_enqueue_style( 'ff-google-fonts', 'http://fonts.googleapis.com/css?family=' . str_replace( ' ', '+' , $fastfood_opt['fastfood_google_font_family'] ) );
		}
		//print style
		wp_enqueue_style( 'ff_print-style', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'print' );
	}
}

// add scripts
if ( !function_exists( 'fastfood_scripts' ) ) {
	function fastfood_scripts(){
		global $fastfood_opt, $ff_is_printpreview, $fastfood_version, $ff_is_mobile_browser, $ff_is_ie6;

		if ( is_admin() ) return;
		
		if ( $ff_is_mobile_browser || $ff_is_printpreview ) return; //no scripts in print preview or mobile view

		if ( $ff_is_ie6 ) { // ie6 scripts
			if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); //standard comment-reply box
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
				wp_enqueue_script( 'comment-reply' ); //standard comment-reply box
			}
		}
		if ( ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) ) {
			wp_enqueue_script( 'thickbox' );
		}
	}
}

// initialize scripts
if ( !function_exists( 'fastfood_init_scripts' ) ) {
	function fastfood_init_scripts(){
		global $fastfood_opt, $ff_is_printpreview, $ff_is_mobile_browser, $ff_is_ie6;
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

		<?php if ( $ff_is_mobile_browser || $ff_is_printpreview || $ff_is_ie6 ) return; ?>

<script type="text/javascript">
	/* <![CDATA[ */
	jQuery(document).ready(function($){
		$('#post-widgets-area .widget:nth-child(odd)').css('clear', 'left');
		$('#header-widget-area .widget:nth-child(3n+1)').css('clear', 'right');
		$('#error404-widgets-area .widget:nth-child(odd)').css('clear', 'left');
		<?php if ( ( $fastfood_opt['fastfood_post_expand'] == 1 ) ) { ?>
		fastfoodAnimations.postexpander();<?php } ?>
		<?php if ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) { ?>
		$('.storycontent a img').parent('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').addClass('thickbox');
		$('.storycontent .gallery').each(function() {
			$('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]',$(this)).attr('rel', $(this).attr('id'));
		});<?php } ?>
	});
	/* ]]> */
</script>
<!--[if lte IE 8]>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('img.size-full').each(function() {
			$(this).css('max-width', Math.min($(this).attr('width'), this.offsetWidth) + 'px');
		});
	});
</script>
<![endif]-->

		<?php
	}
}

// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'fastfood_allcat' ) ) {
	function fastfood_allcat () {
		global $ff_is_allcat_page;
		if( $ff_is_allcat_page ) {
			get_template_part( 'allcat' );
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

				//trim the post title if > 35 chars
				$post_title_short = mb_strimwidth( get_the_title( $post->ID ), 0, 35, '&hellip;' );		
				
				if ( post_password_required( $post ) ) {
					//hide comment author in protected posts
					$com_auth = __( 'someone', 'fastfood' );
				} else {
					//trim the comment author if > 20 chars
					$com_auth = mb_strimwidth( $comment->comment_author, 0, 20, '&hellip;' );
				}

			    echo '<li>'. sprintf( __( '%s about %s', 'fastfood' ), $com_auth, '<a href="' . get_permalink( $post->ID ) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a>' ) . '<div class="preview">';

				if ( post_password_required( $post ) ) {
					echo '[' . __( 'No preview: this is a comment of a protected post', 'fastfood' ) . ']';
				} else {
					comment_excerpt( $comment->comment_ID );
				}
				echo '</div></li>';
			}
		} else {
			echo '<li>' . __( 'No comments yet.', 'fastfood' ) . '</li>';
		}
		wp_reset_postdata();
	}
}


// Get Recent Entries
if ( !function_exists( 'fastfood_get_recententries' ) ) {
	function fastfood_get_recententries( $number = 10 ) {
		$r = new WP_Query( array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
		if ( $r->have_posts() ) {
			while ( $r->have_posts() ) {
				$r->the_post();

				//trim the post title if > 35 chars
				$post_title_short = mb_strimwidth( get_the_title(), 0, 35, '&hellip;' );				
				
				//trim the post author if > 20 chars
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );

				echo '<li><a href="' . get_permalink() . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . $post_title_short . '</a> ' . sprintf( __( 'by %s', 'fastfood' ), $post_auth ) . '<div class="preview">';
				if ( post_password_required() ) {
					echo '<img class="alignleft wp-post-image" src="' . get_template_directory_uri() . '/images/lock.png" alt="thumb" />';
					echo '[' . __( 'No preview: this is a protected post', 'fastfood' ) . ']';
				} else {
					echo get_the_post_thumbnail( get_the_ID(), array( 50,50 ), array( 'class' => 'alignleft' ) );
					the_excerpt();
				}
				echo '</div></li>';
			}
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
			echo '<li class="ql_cat_li"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'fastfood' ), $category->name ) . '" ' . '>' . $category->name . '</a> ( ' . $category->count . ')<div class="cat_preview"><div class="mentit">' . __( 'Recent Posts', 'fastfood' ) . '</div><ul class="solid_ul">';
			$tmp_cat_ID = $category->cat_ID;
			$post_search_args = array(
				'numberposts' => 5,
				'category' => $tmp_cat_ID,
				'no_found_rows' => true
				);
			$lastcatposts = get_posts( $post_search_args );
			foreach( $lastcatposts as $post ) {
				setup_postdata( $post );

				$post_title = get_the_title( $post->ID );		
				//trim the post title if > 35 chars
				$post_title_short = mb_strimwidth( $post_title, 0, 35, '&hellip;' );		

				//trim the post author if > 20 chars
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );

				echo '<li><a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( strip_tags( $post_title ) ) . '">' . $post_title_short . '</a> ' . __( 'by', 'fastfood' ) . ' ' . $post_auth . '</li>';
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
			'numberposts' => 0,
			'no_found_rows' => true
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
						foreach ( $childrens as $children ) {
							$the_child_list[] = '<a href="' . get_permalink( $children ) . '" title="' . get_the_title( $children ) . '">' . get_the_title( $children ) . '</a>';
						}
						$the_child_list = implode( ', ' , $the_child_list );
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

// print posts/pages details
if ( !function_exists( 'fastfood_post_details' ) ) {
	function fastfood_post_details( $args = '' ) {
		global $post;
		
		$defaults = array( 'author' => 1, 'date' => 1, 'tags' => 1, 'categories' => 1, 'avatar_size' => 48, 'featured' => 0 );
		$args = wp_parse_args( $args, $defaults );
		
		?>
			<?php if ( $args['featured'] &&  has_post_thumbnail( $post->ID ) ) { echo '<div class="ff-post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '</div>'; } ?>
			<?php if ( $args['author'] ) {
				$author = $post->post_author;

				$name = get_the_author_meta( 'nickname', $author );
				$alt_name = get_the_author_meta( 'user_nicename', $author );
				$avatar = get_avatar( $author, $args['avatar_size'], 'Gravatar Logo', $alt_name.'-photo' );
				$description = get_the_author_meta( 'description', $author );
				$author_link = get_author_posts_url( $author );

				?>
				<div class="ff-author-bio vcard">
					<ul>
						<li class="author-avatar"><?php echo $avatar; ?></li>
						<li class="author-name"><a class="fn" href="<?php echo $author_link; ?>" ><?php echo $name; ?></a></li>
						<li class="author-description note"><?php echo $description; ?> </li>
						<li class="fixfloat"></li>
					<?php if ( get_the_author_meta( 'twitter', $author ) || get_the_author_meta( 'facebook', $author ) ) { ?>
						<li class="author-social">
							<?php if ( get_the_author_meta( 'twitter', $author ) ) echo '<a target="_blank" class="url" title="' . sprintf( __( 'follow %s on Twitter', 'fastfood' ), $name ) . '" href="' . get_the_author_meta( 'twitter', $author ) . '"><img alt="twitter" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/twitter.png" /></a>'; ?>
							<?php if ( get_the_author_meta( 'facebook', $author ) ) echo '<a target="_blank" class="url" title="' . sprintf( __( 'follow %s on Facebook', 'fastfood' ), $name ) . '" href="' . get_the_author_meta( 'facebook', $author ) . '"><img alt="facebook" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/facebook.png" /></a>'; ?>
						</li>
					<?php } ?>
					</ul>
				</div>
			<?php } ?>
			<?php if ( $args['categories'] ) { echo '<span class="ff-post-details-cats">' . __( 'Categories', 'fastfood' ) . ': ' . '</span>'; the_category( ', ' ); echo '<br />'; } ?>
			<?php if ( $args['tags'] ) { echo '<span class="ff-post-details-tags">' . __( 'Tags', 'fastfood' ) . ': ' . '</span>'; if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags( '', ', ', '' ); } echo '<br />'; } ?>
			<?php if ( $args['date'] ) { echo '<span class="ff-post-details-date">' . __( 'Published on', 'fastfood' ) . ': ' . '</span>'; echo '<b>' . get_the_time( get_option( 'date_format' ) ) . '</b>'; } ?>
		<?php
	}
}

// get the post thumbnail or (if not set) the format related icon
if ( !function_exists( 'fastfood_get_the_thumb' ) ) {
	function fastfood_get_the_thumb( $args = '' ) {
	
		$defaults = array( 'id' => '', 'size' => array( 40, 40 ), 'class' => '', 'default' => '', 'linked' => 0 );
		$args = wp_parse_args( $args, $defaults );

		if ( has_post_thumbnail( $args['id'] ) ) {
			$output = get_the_post_thumbnail( $args['id'], $args['size'], array( 'class' => $args['class'] ) );
		} else {
			$output = $args['default'];
		}
		if ( $args['linked'] )
			return '<a href="' . get_permalink( $args['id'] ) . '" rel="bookmark">' . $output . '</a>';
		else
			return $output;
	}
}

// display the post title with the featured image
if ( !function_exists( 'fastfood_featured_title' ) ) {
	function fastfood_featured_title( $args = '' ) {
		global $post, $fastfood_opt;

		$defaults = array( 'alternative' => '', 'fallback' => '', 'featured' => true, 'href' => get_permalink(), 'target' => '', 'title' => the_title_attribute( array( 'echo' => 0 ) ) );
		$args = wp_parse_args( $args, $defaults );

		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();
		$post_title = $post_title ? $post_title : $args['fallback'];
		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';
		if ( $fastfood_opt['fastfood_featured_title'] == 0 ) $args['featured'] = false;
		$thumb = ( $args['featured'] && has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, array( $fastfood_opt['fastfood_featured_title_size'], $fastfood_opt['fastfood_featured_title_size'] ) ) : '';
		$title_class = $thumb ? 'entry-title storytitle featured-' . $fastfood_opt['fastfood_featured_title_size'] : 'storytitle';
		if ( $post_title || $thumb ) $post_title = '<h2 class="' . $title_class . '"><a title="' . $args['title'] . '" href="' . $args['href'] . '"' . $link_target . ' rel="bookmark">' . $thumb . $post_title . '</a></h2>';

		echo $post_title;
	}
}

// print extra info for posts/pages
if ( !function_exists( 'fastfood_extrainfo' ) ) {
	function fastfood_extrainfo( $args = '' ) {
		global $fastfood_opt;
		
		$defaults = array( 'auth' => 1, 'date' => 1, 'comms' => 1, 'tags' => 1, 'cats' => 1, 'hiera' => 0, 'list_view' => 0 );
		$args = wp_parse_args( $args, $defaults );


		//xinfos disabled when...
		if ( ! $fastfood_opt['fastfood_xinfos_global'] ) return; //xinfos globally disabled
		if ( is_front_page() && $fastfood_opt['fastfood_xinfos_on_front'] == 0 ) return; // is front page
		if ( is_page() && $fastfood_opt['fastfood_xinfos_on_page'] == 0 ) return;
		if ( is_single() && $fastfood_opt['fastfood_xinfos_on_post'] == 0 ) return;
		if ( !is_singular() && $fastfood_opt['fastfood_xinfos_on_list'] == 0 ) return;
		if ( $fastfood_opt['fastfood_xinfos_static'] == 1 ) $args['list_view'] = true;
		if ( $fastfood_opt['fastfood_xinfos_byauth'] == 0 ) $args['auth'] = false;
		if ( $fastfood_opt['fastfood_xinfos_date'] == 0 ) $args['date'] = false;
		if ( $fastfood_opt['fastfood_xinfos_comm'] == 0 ) $args['comms'] = false;
		if ( $fastfood_opt['fastfood_xinfos_tag'] == 0 ) $args['tags'] = false;
		if ( $fastfood_opt['fastfood_xinfos_cat'] == 0 ) $args['cats'] = false;
		if ( $fastfood_opt['fastfood_xinfos_hiera'] == 0 ) $args['hiera'] = false;

		$r_pos = 10;
		if ( !$args['list_view'] ) {
		?>
		<div class="meta_container">
			<div class="meta top_meta">
				<?php
				if ( $args['auth'] ) { ?>
					<?php $post_auth = ( $args['auth'] === true ) || ( $args['auth'] === 1 ) ? '<a class="vcard fn author" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : '<span class="vcard fn author">' . $args['auth'] . '</span>'; ?>
					<div class="metafield_trigger" style="left: 10px;"><?php printf( __( 'by %s', 'fastfood' ), $post_auth ); ?></div>
				<?php
				}
				if ( $args['cats'] ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_cat" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php echo __( 'Categories', 'fastfood' ); ?>:
							<?php the_category( ', ' ) ?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				if ( $args['tags'] ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_tag" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php _e( 'Tags', 'fastfood' ); ?>:
							<?php if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags( '', ', ', '' ); } ?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				$page_cd_nc = ( is_page() && !comments_open() && !have_comments() ); //true if page with comments disabled and no comments
				if ( $args['comms'] && !$page_cd_nc ) {
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
				if ( $args['date'] ) {
				?>
					<div class="metafield">
						<div class="metafield_trigger mft_date" style="right: <?php echo $r_pos; ?>px; width:16px"> </div>
						<div class="metafield_content">
							<?php
							printf( __( 'Published on: %s', 'fastfood' ), '<b class="published" title="' . get_the_time( 'c' ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</b>' );
							?>
						</div>
					</div>
				<?php
					$r_pos = $r_pos + 30;
				}
				if ( $args['hiera'] ) {
				?>
					<?php if ( fastfood_multipages( $r_pos ) ) { $r_pos = $r_pos + 30; } ?>
				<?php
				}
				?>
				<div class="metafield_trigger edit_link" style="right: <?php echo $r_pos; ?>px;"><?php edit_post_link( __( 'Edit', 'fastfood' ), '' ); ?></div>
			</div>
		</div>
		<?php
		} else { ?>
			<div class="meta">
				<?php if ( $args['auth'] ) { ?>
					<?php $post_auth = ( $args['auth'] === true ) || ( $args['auth'] === 1 ) ? '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' : $args['auth']; ?>
					<?php printf( __( 'by %s', 'fastfood' ), $post_auth ); ?>
				<?php } ?>
				<?php if ( $args['date'] ) { printf( __( 'Published on: %s', 'fastfood' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br />'; }?>
				<?php if ( $args['comms'] ) { echo __( 'Comments', 'fastfood' ) . ': '; comments_popup_link( __( 'No Comments', 'fastfood' ), __( '1 Comment', 'fastfood' ), __( '% Comments', 'fastfood' ) ); echo '<br />'; } ?>
				<?php if ( $args['tags'] ) { echo __( 'Tags', 'fastfood' ) . ': '; if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags( '', ', ', '' ); }; echo '<br />';  } ?>
				<?php if ( $args['cats'] ) { echo __( 'Categories', 'fastfood' ) . ': '; the_category( ', ' ); echo '<br />'; } ?>
				<?php edit_post_link( __( 'Edit', 'fastfood' ) ); ?>
			</div>
		<?php
		}
	}
}

if ( !function_exists( 'fastfood_breadcrumb' ) ) {
	function fastfood_breadcrumb() {
		global $wp_query, $post, $ff_is_allcat_page;

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


		$sep = '||';
		if ( !function_exists( 'fastfood_get_category_parents' ) ) {
			// Copied and adapted from WP source
			function fastfood_get_category_parents( $id, $link = FALSE, $separator = '||', $nicename = FALSE ){
				global $wp_query;
				$chain = '';
				$parent = &get_category( $id );
				if ( is_wp_error( $parent ) )
				   return $parent;

				if ( $nicename )
				   $name = $parent->slug;
				else
				   $name = $parent->cat_name;

				if ( $parent->parent && ( $parent->parent != $parent->term_id ) )
				   $chain .= get_category_parents( $parent->parent, true, $separator, $nicename );

				$chain .= '<span class="crumb-cat">'.$name.' (' . $wp_query->found_posts . ')</span>';
				return $chain;
			}
		}

		$on_front = get_option( 'show_on_front' );
		if ( $on_front == "page" ) {
			$homelink = '<a class="crumb-home"' . $opt['nofollow'] . 'href="' . get_permalink( get_option( 'page_on_front' ) ) . '">&nbsp;</a>';
			$bloglink = $homelink . $sep . '<a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
		} else {
			$homelink = '<a class="crumb-home"' . $opt['nofollow'] . 'href="' . home_url() . '">&nbsp;</a>';
			$bloglink = $homelink;
		}

		if ( $ff_is_allcat_page ) {
			$output = $homelink . $sep . '<span>' . __( 'All Categories', 'fastfood' ) . '</span>';
		} elseif ( ( $on_front == "page" && is_front_page() ) || ( $on_front == "posts" && is_home() ) ) {
			$output = $homelink . $sep . '<span>' . $opt['home'] . '</span>';
		} elseif ( $on_front == "page" && is_home() ) {
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
						$output .= '<a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '</a>' . $sep;
					}
				}
			}
			if ( is_category() ) {
				$cat = intval( get_query_var( 'cat' ) );
				$output .= fastfood_get_category_parents( $cat, false, $sep );
			} elseif ( is_tag() ) {
				$output .= '<span class="crumb-tag">' . sprintf( $opt['archive_prefix'], wp_title( '', false, 'right' ) ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_date() ) {
				$output .= '<span class="crumb-date">' . sprintf( $opt['archive_prefix'], wp_title( '', false, 'right' ) ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_author() ) {
				$output .= '<span class="crumb-auth">' . sprintf( $opt['archive_prefix'], wp_title( '', false, 'right' ) ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_404() ) {
				$output .= '<span class="crumb-error">' . __( 'Page not found', 'fastfood' ) . '</span>';
			} elseif ( is_search() ) {
				$output .= '<span class="crumb-search">' . sprintf( $opt['search_prefix'], stripslashes( strip_tags(get_search_query() ) ) ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_attachment() ) {
				if ( $post->post_parent ) {
					$output .= '<a href="' . get_permalink( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a>' . $sep;
				}
				$output .= '<span>' . get_the_title() . '</span>';
			} else if ( is_tax() ) {
				$taxonomy 	= get_taxonomy ( get_query_var( 'taxonomy' ) );
				$term 		= get_query_var( 'term' );
				$output .= '<span>' . $taxonomy->label . ': ' . $term . ' (' . $wp_query->found_posts . ')</span>';
			} else {
				if ( get_query_var( 'page' ) ) {
					$output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . $sep . '<span>' . __( 'Page', 'fastfood' ) . get_query_var( 'page' ) . '</span>';
				} else {
					$output .= get_the_title() ? '<span>' . get_the_title() . '</span>' : '<span>' . sprintf ( __( 'post #%s', 'fastfood' ), get_the_ID() ) . '</span>';
				}
			}
		} else {
			$post = $wp_query->get_queried_object();

			// If this is a top level Page, it's simple to output the breadcrumb
			if ( 0 == $post->post_parent ) {
				if ( get_query_var( 'page' ) ) {
					$output = $homelink . $sep . '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . $sep . '<span>' . __( 'Page', 'fastfood' ) . get_query_var( 'page' ) . '</span>';
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
					$tmp['url'] 	= get_permalink( $ancestor );
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
							$output .= '<a href="' . $link['url'] . '">' . $link['title'] . '</a>' . $sep . '<span>' . __( 'Page', 'fastfood' ) . get_query_var( 'page' ) . '</span>';
						} else {
							$output .= '<span>' . $link['title'] . '</span>';
						}
					}
				}
			}
		}
		if ( get_query_var( 'paged' ) ) {
			$output .= $sep . '<span>' . __( 'Page', 'fastfood' ) . get_query_var( 'paged' ) . '</span>';
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
		?>
		<div id="ff-breadcrumb-wrap">
			<div id="crumbs">
			<?php echo $output; ?>
			</div>
		</div>
		<?php
	}
}

//quickbar
if ( !function_exists( 'fastfood_quickbar' ) ) {
	function fastfood_quickbar( $args = '' ) {
		global $post, $fastfood_opt, $current_user;

		wp_reset_postdata();
		
		$defaults = array( 'r_posts' => 1, 'p_categories' => 1, 'r_comments' => 1, 'user' => 1 );
		$args = wp_parse_args( $args, $defaults );

		?>

<!-- begin quickbar -->
<div id="quickbar"<?php if ( $fastfood_opt['fastfood_statusbar'] == 0 ) echo ' class="no-status"'; ?>>
	<!-- quickbar tool - uncomment to use
		<div class="menutoolitem">
			<div class="itemimg menutool_trig" style="background-image: url( '<?php echo get_template_directory_uri(); ?>/images/qbartool.png' );"></div>
			<div class="menutool">[put here your code]</div>
		</div>
	quickbar tool -->
	<br />
	<?php if ( $args['r_posts'] && $fastfood_opt['fastfood_qbar_recpost'] == 1 ) { //							recent posts menu ?>
		<div class="menuitem">
			<div id="mii_rpost" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'Recent Posts', 'fastfood' ); ?></div>
					<ul class="solid_ul">
						<?php fastfood_get_recententries() ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $args['p_categories'] && $fastfood_opt['fastfood_qbar_cat'] == 1 ) { // 							popular categories menu ?>
		<div class="menuitem">
			<div id="mii_pcats" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'Categories', 'fastfood' ); ?></div>
					<ul class="solid_ul">
						<?php fastfood_get_categories_wpr(); ?>
						<li class="all_cat"><a title="<?php _e( 'View all categories', 'fastfood' ); ?>" href="<?php echo home_url(); ?>/?allcat=y"><?php _e( 'More...', 'fastfood' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $args['r_comments'] && $fastfood_opt['fastfood_qbar_reccom'] == 1 ) { //recent comments menu ?>
		<div class="menuitem">
			<div id="mii_rcomm" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'Recent Comments', 'fastfood' ); ?></div>
					<ul class="solid_ul">
						<?php fastfood_get_recentcomments(); ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $args['user'] && $fastfood_opt['fastfood_qbar_user'] == 1 ) { //user links menu ?>
		<div class="menuitem" id="user_menuback">
			<div id="mii_cuser" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'User', 'fastfood' ); ?></div>
					<ul class="solid_ul">
						<li id="logged">
							<?php
							if ( is_user_logged_in() ) { //fix for notice when user not log-in
								get_currentuserinfo();
								$ff_email = $current_user->user_email;
								echo get_avatar( $ff_email, 50, $default=get_template_directory_uri() . '/images/user.png', 'user-avatar' );
								printf( __( 'Logged in as <a href="%1$s">%2$s</a>.', 'fastfood' ), admin_url( 'profile.php' ), '<strong>' . $current_user->display_name . '</strong>' );
							} else {
								echo get_avatar( 'dummyemail', 50, $default=get_template_directory_uri() . '/images/user.png', 'user-avatar' );
								echo __( 'Not logged in', 'fastfood' );
							}
							?>
						</li>
						<?php if ( ! is_user_logged_in() || current_user_can( 'read' ) ) { wp_register(); }?>
						<?php if ( is_user_logged_in() ) { ?>
							<?php if ( current_user_can( 'read' ) ) { ?>
								<li><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile', 'fastfood' ); ?></a></li>
								<?php if ( current_user_can( 'publish_posts' ) ) { ?>
									<li><a title="<?php _e( 'Add New Post', 'fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post', 'fastfood' ); ?></a></li>
								<?php } ?>
								<?php if ( current_user_can( 'moderate_comments' ) ) {
									$ff_awaiting_mod = wp_count_comments();
									$ff_awaiting_mod = $ff_awaiting_mod->moderated;
									$ff_awaiting_mod = $ff_awaiting_mod ? ' (' . number_format_i18n( $ff_awaiting_mod ) . ')' : '';
								?>
									<li><a title="<?php _e( 'Comments', 'fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'fastfood' ); ?></a><?php echo $ff_awaiting_mod; ?></li>
								<?php } ?>
							<?php } ?>
							<li><a title="<?php _e( 'Log out', 'fastfood' ); ?>" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e( 'Log out', 'fastfood' ); ?></a></li>
						<?php } ?>
						<?php if ( ! is_user_logged_in() ) {?>
							<?php fastfood_mini_login(); ?>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

		<?php
	}
}

//navigation bar
if ( !function_exists( 'fastfood_navbuttons' ) ) {
	function fastfood_navbuttons( $args = '' ) {
		global $post, $fastfood_opt, $ff_is_allcat_page;

		$is_post = is_single() && !is_attachment() && !$ff_is_allcat_page;
		$is_image = is_attachment() && !$ff_is_allcat_page;
		$is_page = is_singular() && !is_single() && !is_attachment() && !$ff_is_allcat_page;
		$is_singular = is_singular() && !$ff_is_allcat_page;

		$defaults = array( 'print' => 1, 'comment' => 1, 'feed' => 1, 'trackback' => 1, 'home' => 1, 'next_prev' => 1, 'up_down' => 1, 'fixed' => 1 );
		$args = wp_parse_args( $args, $defaults );

	?>

<div id="navbuttons_cont">
	<div id="navbuttons">

		<?php // ------- Print -------
			if ( $fastfood_opt['fastfood_navbuttons_print'] && $args['print'] && $is_singular ) { ?>
			<div class="minibutton">
				<a href="<?php
					$ff_arr_params['style'] = 'printme';
					if ( get_query_var( 'page' ) ) {
						$ff_arr_params['page'] = esc_html( get_query_var( 'page' ) );
					}
					if ( get_query_var( 'cpage' ) ) {
						$ff_arr_params['cpage'] = esc_html( get_query_var( 'cpage' ) );
					}
					echo add_query_arg( $ff_arr_params, get_permalink( $post->ID ) );
					?>">
					<span class="minib_img minib_print">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Print preview', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Leave a comment -------
			if ( $fastfood_opt['fastfood_navbuttons_comment'] && $args['comment'] && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { ?>
			<div class="minibutton">
				<a href="#respond"<?php if ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) { echo ' onclick="return addComment.viewForm()"'; } ?>>
					<span class="minib_img minib_comment">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Leave a comment', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- RSS feed -------
			if ( $fastfood_opt['fastfood_navbuttons_feed'] && $args['feed'] && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_post_comments_feed_link( $post->ID, 'rss2' ); ?> ">
					<span class="minib_img minib_rss">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'feed for comments on this post', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Trackback -------
			if ( $fastfood_opt['fastfood_navbuttons_trackback'] && $args['trackback'] && $is_singular && pings_open() ) { ?>
			<div class="minibutton">
				<a href="<?php global $ff_tmptrackback; echo $ff_tmptrackback; ?>" rel="trackback">
					<span class="minib_img minib_track">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Trackback URL', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Home -------
			if ( $fastfood_opt['fastfood_navbuttons_home'] && $args['home'] ) { ?>
			<div class="minibutton">
				<a href="<?php echo home_url(); ?>">
					<span class="minib_img minib_home">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Home', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Back to parent post -------
			if ( $is_image ) { ?>
			<?php if ( !empty( $post->post_parent ) ) { ?>
				<div class="minibutton">
					<a href="<?php echo get_permalink( $post->post_parent ); ?>" rel="gallery">
						<span class="minib_img minib_backtopost">&nbsp;</span>
					</a>
					<span class="nb_tooltip"><?php esc_attr( printf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) ) ); ?></span>
				</div>
			<?php } ?>
		<?php } ?>

		<?php // ------- Next post -------
			if ( $fastfood_opt['fastfood_navbuttons_nextprev'] && $args['next_prev'] && $is_post && get_next_post() ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_permalink( get_next_post() ); ?>">
					<span class="minib_img minib_npage">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php esc_attr( printf( __( 'Next Post', 'fastfood' ) . ': %s', get_the_title( get_next_post() ) ) ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Previous post -------
			if ( $fastfood_opt['fastfood_navbuttons_nextprev'] && $args['next_prev'] && $is_post && get_previous_post() ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_permalink( get_previous_post() ); ?>">
					<span class="minib_img minib_ppage">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php esc_attr( printf( __( 'Previous Post', 'fastfood' ) . ': %s', get_the_title( get_previous_post() ) ) ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Newer Posts -------
			if ( $fastfood_opt['fastfood_navbuttons_newold'] && $args['next_prev'] && !$is_singular && !$ff_is_allcat_page && get_previous_posts_link() ) { ?>
			<div class="minibutton nb-nextprev">
				<?php previous_posts_link( '<span class="minib_img minib_ppages">&nbsp;</span>' ); ?>
				<span class="nb_tooltip"><?php echo __( 'Newer Posts', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Older Posts -------
			if ( $fastfood_opt['fastfood_navbuttons_newold'] && $args['next_prev'] && !$is_singular && !$ff_is_allcat_page && get_next_posts_link() ) { ?>
			<div class="minibutton nb-nextprev">
				<?php next_posts_link( '<span class="minib_img minib_npages">&nbsp;</span>' ); ?>
				<span class="nb_tooltip"><?php echo __( 'Older Posts', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Top -------
			if ( $fastfood_opt['fastfood_navbuttons_topbottom'] && $args['up_down'] ) { ?>
			<div class="minibutton">
				<a href="#">
					<span class="minib_img minib_top">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Top of page', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Bottom -------
			if ( $fastfood_opt['fastfood_navbuttons_topbottom'] && $args['up_down'] ) { ?>
			<div class="minibutton">
				<a href="#footer">
					<span class="minib_img minib_bottom">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Bottom of page', 'fastfood' ); ?></span>
			</div>
		<?php } ?>
		<div class="fixfloat"> </div>
	</div>
</div>

	<?php
	}
}

if ( !function_exists( 'fastfood_exif_info' ) ) {
	function fastfood_exif_info(){
		global $post, $fastfood_opt;
		?>
		<!-- Using WordPress functions to retrieve the extracted EXIF information from database -->
		<div class="exif-attachment-info">
			<?php
			$ff_imgmeta = wp_get_attachment_metadata( $post->ID );

			// Convert the shutter speed retrieve from database to fraction
			if ( $ff_imgmeta['image_meta']['shutter_speed'] && ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ) > 1 ) {
				if ( ( number_format( ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ), 1 ) ) == 1.3
				or number_format( ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ), 1 ) == 1.5
				or number_format( ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ), 1 ) == 1.6
				or number_format( ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ), 1 ) == 2.5 ){
					$ff_pshutter = '1/' . number_format( ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ), 1, '.', '' );
				} else {
					$ff_pshutter = '1/' . number_format( ( 1 / $ff_imgmeta['image_meta']['shutter_speed'] ), 0, '.', '' );
				}
			} else {
				$ff_pshutter = $ff_imgmeta['image_meta']['shutter_speed'];
			}

			// Start to display EXIF and IPTC data of digital photograph
			echo __( 'Width', 'fastfood' ) . ': ' . $ff_imgmeta['width'].'px<br />';
			echo __( 'Height', 'fastfood' ) . ': ' . $ff_imgmeta['height'].'px<br />';
			if ( $ff_imgmeta['image_meta']['created_timestamp'] ) echo __( 'Date Taken', 'fastfood' ) . ': ' . date( 'd-M-Y H:i:s', $ff_imgmeta['image_meta']['created_timestamp'] ).'<br />';
			if ( $ff_imgmeta['image_meta']['copyright'] ) echo __( 'Copyright', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['copyright'].'<br />';
			if ( $ff_imgmeta['image_meta']['credit'] ) echo __( 'Credit', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['credit'].'<br />';
			if ( $ff_imgmeta['image_meta']['title'] ) echo __( 'Title', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['title'].'<br />';
			if ( $ff_imgmeta['image_meta']['caption'] ) echo __( 'Caption', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['caption'].'<br />';
			if ( $ff_imgmeta['image_meta']['camera'] ) echo __( 'Camera', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['camera'].'<br />';
			if ( $ff_imgmeta['image_meta']['focal_length'] ) echo __( 'Focal Length', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['focal_length'].'mm<br />';
			if ( $ff_imgmeta['image_meta']['aperture'] ) echo __( 'Aperture', 'fastfood' ) . ': f/' . $ff_imgmeta['image_meta']['aperture'].'<br />';
			if ( $ff_imgmeta['image_meta']['iso'] ) echo __( 'ISO', 'fastfood' ) . ': ' . $ff_imgmeta['image_meta']['iso'].'<br />';
			if ( $ff_pshutter ) echo __( 'Shutter Speed', 'fastfood' ) . ': ' . sprintf( __( '%s seconds', 'fastfood' ), $ff_pshutter ) . '<br />'
			?>
		</div>
	<?php
	}
}

//add "like" badges to post/page
if ( !function_exists( 'fastfood_I_like_it' ) ) {
	function fastfood_I_like_it(){
		global $fastfood_opt, $post;
		if ( $fastfood_opt['fastfood_I_like_it'] == 0 ) return;
		if ( ( $fastfood_opt['fastfood_I_like_it_plus1'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_twitter'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_facebook'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_linkedin'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_stumbleupon'] == 0 ) && ( ( $fastfood_opt['fastfood_I_like_it_pinterest']	== 0 ) || ( ( $fastfood_opt['fastfood_I_like_it_pinterest']	== 1 ) && !is_attachment() ) ) ) return;

		$pName = rawurlencode( get_the_title( $post->ID ) );
		$pHref = rawurlencode( home_url() . '/?p=' . $post->ID );

		?>
		<div class="ff-I-like-it">
			<?php if ( $fastfood_opt['fastfood_I_like_it_plus1']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="g-plusone" data-size="tall" data-href="<?php echo $pHref; ?>"></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_twitter']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="t-twits"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $pHref; ?>" data-text="<?php echo $pName . ': ' . $pHref; ?>" data-count="vertical"></a></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_facebook']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="fb-like" data-href="<?php echo $pHref; ?>" data-send="false" data-layout="box_count" data-width="42" data-show-faces="false"></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_linkedin']		== 1 ) { ?><div class="ff-I-like-it-button"><script type="IN/Share" data-url="<?php echo $pHref; ?>" data-counter="top"></script></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_stumbleupon']	== 1 ) { ?><div class="ff-I-like-it-button"><script src="http://www.stumbleupon.com/hostedbadge.php?s=5&r=<?php echo $pHref; ?>"></script></div><?php } ?>
			<?php if ( ( $fastfood_opt['fastfood_I_like_it_pinterest']	== 1 ) && is_attachment() && ( wp_attachment_is_image() ) ) { ?><div class="ff-I-like-it-button"><a href="http://pinterest.com/pin/create/button/?url=<?php echo $pHref; ?>&media=<?php echo urlencode( wp_get_attachment_url() ); ?>&description=<?php echo urlencode( $post->post_excerpt ); ?>" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div><?php } ?>
		</div>
		<?php
	}
}

if ( !function_exists( 'fastfood_I_like_it_js' ) ) {
	function fastfood_I_like_it_js(){
		global $fastfood_opt;
?>
<?php if ( $fastfood_opt['fastfood_I_like_it_plus1'] == 1 ) { ?>
	<script type="text/javascript">
		(function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = '//apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
	</script>
<?php } ?>

<?php if ( $fastfood_opt['fastfood_I_like_it_twitter'] == 1 ) { ?>
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
<?php } ?>

<?php if ( $fastfood_opt['fastfood_I_like_it_facebook'] == 1 ) { ?>
	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
<?php } ?>

<?php if ( $fastfood_opt['fastfood_I_like_it_linkedin'] == 1 ) { ?>
	<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
<?php } ?>
<?php if ( ( $fastfood_opt['fastfood_I_like_it_pinterest']	== 1 ) && is_attachment() && ( wp_attachment_is_image() ) ) { ?>
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<?php } ?>

<?php
	}
}

//add share links to post/page
if ( !function_exists( 'fastfood_share_this' ) ) {
	function fastfood_share_this( $args = '' ){
		global $post, $fastfood_opt;

		$defaults = array(
			'size' => 24, 
			'echo' => true,
			'compact' => false,
			'twitter' => 1,
			'facebook' => 1,
			'sina' => 1,
			'tencent' => 1,
			'qzone' => 1,
			'reddit' => 1,
			'stumbleupon' => 1,
			'digg' => 1,
			'bookmarks' => 1,
			'blogger' => 1,
			'delicious' => 1
		);
		$args = wp_parse_args( $args, $defaults );

		$share = array();
		$pName = rawurlencode( $post->post_title );
		$pHref = rawurlencode( home_url() . '/?p=' . $post->ID );
		$pPict = rawurlencode( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );

		$share['twitter'] = array( 'Twitter', 'http://twitter.com/home?status=' . $pName . ' - ' . $pHref );
		$share['facebook'] = array( 'Facebook', 'http://www.facebook.com/sharer.php?u=' . $pHref.'&t=' . $pName );
		$share['sina'] = array( 'Weibo', 'http://v.t.sina.com.cn/share/share.php?url=' . $pHref );
		$share['tencent'] = array( 'Tencent', 'http://v.t.qq.com/share/share.php?url=' . $pHref . '&title=' . $pName . '&pic=' . $pPict );
		$share['qzone'] = array( 'Qzone', 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $pHref );
		$share['reddit'] = array( 'Reddit', 'http://reddit.com/submit?url=' . $pHref . '&title=' . $pName );
		$share['stumbleupon'] = array( 'StumbleUpon', 'http://www.stumbleupon.com/submit?url=' . $pHref . '&title=' . $pName );
		$share['digg'] = array( 'Digg', 'http://digg.com/submit?url=' . $pHref . '&title=' . $pName );
		$share['bookmarks'] = array( 'Bookmarks', 'https://www.google.com/bookmarks/mark?op=edit&bkmk=' . $pHref . '&title=' . $pName );
		$share['blogger'] = array( 'Blogger', 'http://www.blogger.com/blog_this.pyra?t&u=' . $pHref . '&n=' . $pName . '&pli=1' );
		$share['delicious'] = array( 'Delicious', 'http://delicious.com/save?v=5&noui&jump=close&url=' . $pHref . '&title=' . $pName );

		$outer = '<div class="article-share fixfloat">';
		foreach( $share as $key => $btn ){
			if ( $args[$key] )
				$outer .= '<a class="share-item" rel="nofollow" target="_blank" id="' . $key . '" href="' . $btn[1] . '"><img src="' . get_template_directory_uri() . '/images/follow/' . strtolower( $key ) . '.png" width="' . $args['size'] . '" height="' . $args['size'] . '" alt="' . $btn[0] . ' Button"  title="' . sprintf( __( 'Share with %s', 'fastfood' ), $btn[0] ) . '" /></a>';
		}
		$outer .= '</div>';
		if ( $args['echo'] ) echo $outer; else return $outer;
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

// set up custom colors and header image
if ( !function_exists( 'fastfood_setup' ) ) {
	function fastfood_setup() {
		global $fastfood_opt;

		// Register localization support
		load_theme_textdomain( 'fastfood', get_template_directory() . '/languages' );
		// Theme uses wp_nav_menu() in three location
		register_nav_menus( array( 'primary' => __( 'Main Navigation Menu', 'fastfood' ) ) );
		register_nav_menus( array( 'secondary1' => __( 'Secondary Navigation Menu #1', 'fastfood' )	) );
		register_nav_menus( array( 'secondary2' => __( 'Secondary Navigation Menu #2', 'fastfood' )	) );
		// Register Features Support
		add_theme_support( 'automatic-feed-links' );
		// Thumbnails support
		add_theme_support( 'post-thumbnails' );
		// Add the editor style
		if ( isset( $fastfood_opt['fastfood_editor_style'] ) && ( $fastfood_opt['fastfood_editor_style'] == 1 ) ) add_editor_style( 'css/editor-style.css' );

		// This theme uses post formats
		if ( $fastfood_opt['fastfood_post_formats'] == 1 ) {
			$pformats = array();
			if ( $fastfood_opt['fastfood_post_formats_gallery'] == 1 ) $pformats[] = 'gallery';
			if ( $fastfood_opt['fastfood_post_formats_aside'] == 1 ) $pformats[] = 'aside';
			if ( $fastfood_opt['fastfood_post_formats_status'] == 1 ) $pformats[] = 'status';
			add_theme_support( 'post-formats', $pformats );
		}

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

		$head_h = ( isset( $fastfood_opt['fastfood_head_h'] ) ? str_replace( 'px', '', $fastfood_opt['fastfood_head_h'] ) : 120 );
		fastfood_setup_custom_header( $head_h );

	}
}

//the custom header support
if ( !function_exists( 'fastfood_setup_custom_header' ) ) {
	function fastfood_setup_custom_header( $head_h ) {
		$args = array(
			'width'					=> 848, // Header image width (in pixels)
			'height'				=> $head_h, // Header image height (in pixels)
			'default-image'			=> get_template_directory_uri() . '/images/headers/tree.jpg', // Header image default
			'header-text'			=> true, // Header text display default
			'default-text-color'	=> '404040', // Header text color default
			'wp-head-callback'		=> 'fastfood_header_style',
			'admin-head-callback'	=> ''
		);
	 
		$args = apply_filters( 'fastfood_custom_header_args', $args );
	 
		if ( function_exists( 'get_custom_header' ) ) {
			add_theme_support( 'custom-header', $args );
		} else {
			// Compatibility with versions of WordPress prior to 3.4.
			define( 'HEADER_TEXTCOLOR',		$args['default-text-color'] );
			define( 'NO_HEADER_TEXT',		$args['header-text'] );
			define( 'HEADER_IMAGE',			$args['default-image'] );
			define( 'HEADER_IMAGE_WIDTH',	$args['width'] );
			define( 'HEADER_IMAGE_HEIGHT',	$args['height'] );
			add_custom_image_header( $args['wp-head-callback'], $args['admin-head-callback'] );
		}
	}
}

// the custom header (filterable)
if ( !function_exists( 'fastfood_header' ) ) {
	function fastfood_header(){
		global $ff_is_printpreview, $fastfood_opt;
		
		if ( $ff_is_printpreview ) 
			return $output = '
					<div id="head">
						<h1><a href="' . home_url() . '/">' . get_bloginfo( 'name' ) . '</a></h1>
					</div>';

		// Allow plugins/themes to override the default header.
		$output = apply_filters('fastfood_header', '');
		if ( $output != '' )
			return $output;

		if ( ( $fastfood_opt['fastfood_head_link'] == 1 ) && (  get_header_image() != '' ) ) {
			$output = '<div id="head-wrap"><a href="' . home_url() . '/"><img src="' . get_header_image() . '" /></a></div>';
		} else {
			$output = '<div id="head-wrap">
						<div id="head">
							' . fastfood_hook_before_site_title() . '
							<h1><a href="' . home_url() . '/">' . get_bloginfo( 'name' ) . '</a></h1>
							' . fastfood_hook_after_site_title() . '
							<div class="description">' . get_bloginfo( 'description' ) . '</div>
						</div>
					</div>';
		}

		return $output;

	}
}

// the custom header style - add style customization to page - gets included in the site header
if ( !function_exists( 'fastfood_header_style' ) ) {
	function fastfood_header_style(){

		global $ff_is_printpreview, $ff_is_mobile_browser, $fastfood_opt;
		if ( $ff_is_printpreview || $ff_is_mobile_browser ) return;

		if ( ( 'blank' == get_header_textcolor() ) || ( $fastfood_opt['fastfood_head_link'] == 1 ) )
			$style = 'display:none;';
		else
			$style = 'color:#' . get_header_textcolor() . ';';


		if ( function_exists( 'get_custom_header' ) )
			$min_height = get_custom_header()->height;
		else // Compatibility with versions of WordPress prior to 3.4.
			$min_height = HEADER_IMAGE_HEIGHT;
			

		?>
<style type="text/css">
	#head-wrap {
		background: transparent url( '<?php header_image(); ?>' ) right bottom no-repeat;
		min-height: <?php echo $min_height; ?>px;
	}
	#head {
		<?php echo $style; ?>
	}
	body {
		font-size: <?php echo $fastfood_opt['fastfood_font_size']; ?>;
<?php if ( $fastfood_opt['fastfood_google_font_family'] && $fastfood_opt['fastfood_google_font_body'] ) { ?>
		font-family: <?php echo $fastfood_opt['fastfood_google_font_family']; ?>;
<?php } else { ?>
		font-family: <?php echo $fastfood_opt['fastfood_font_family']; ?>;
<?php } ?>
	}
<?php if ( $fastfood_opt['fastfood_google_font_family'] && $fastfood_opt['fastfood_google_font_post_title'] ) { ?>
	h2.storytitle {
		font-family: <?php echo $fastfood_opt['fastfood_google_font_family']; ?>;
	}
<?php } ?>
<?php if ( $fastfood_opt['fastfood_google_font_family'] && $fastfood_opt['fastfood_google_font_post_content'] ) { ?>
	.storycontent {
		font-family: <?php echo $fastfood_opt['fastfood_google_font_family']; ?>;
	}
<?php } ?>
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
	#crumbs .last,
	li.current_page_ancestor .hiraquo {
		color: <?php echo $fastfood_opt['fastfood_colors_link_sel']; ?>;
	}
	<?php 
		if ( $fastfood_opt['fastfood_custom_css'] )
			echo $fastfood_opt['fastfood_custom_css']; 
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
	.storycontent img.size-full {
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
		?>
		<li class="ql_cat_li">
			<a title="<?php _e( 'Log in', 'fastfood' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in', 'fastfood' ); ?></a>
			<?php if ( isset( $fastfood_opt['fastfood_qbar_minilogin'] ) && ( $fastfood_opt['fastfood_qbar_minilogin'] == 1 ) && ( !class_exists("siCaptcha") ) ) { ?>
				<div id="ff_minilogin_wrap" class="cat_preview">
					<div class="mentit"><?php _e( 'Log in', 'fastfood' ); ?></div>
					<div id="ff_minilogin" class="solid_ul">
						<?php wp_login_form($args); ?>
						<a id="closeminilogin" href="#"><?php _e( 'Close', 'fastfood' ); ?></a>
					</div>
				</div>
			<?php } ?>
		</li>

		<?php
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
				ff_post_expander_text = "<?php _e( 'Post loading, please wait...', 'fastfood' ); ?>";
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

// custom image caption
if ( !function_exists( 'fastfood_img_caption_shortcode' ) ) {
	function fastfood_img_caption_shortcode( $deprecated, $attr, $content = null ) {

		extract( shortcode_atts( array(
			'id'	=> '',
			'align'	=> 'alignnone',
			'width'	=> '',
			'caption' => ''
		), $attr ) );

		if ( 1 > (int) $width || empty( $caption ) )
			return $content;

		if ( $id ) $id = 'id="' . esc_attr( $id ) . '" ';

		return '<div ' . $id . 'class="wp-caption ' . esc_attr( $align ) . '" style="width: ' . $width . 'px"><div class="wp-caption-inside">'
		. do_shortcode( $content ) . '<div class="wp-caption-text">' . $caption . '</div></div></div>';
	}
}

// strip tags from titles and apply title format for blank ones
function fastfood_title_tags_filter( $title, $id ) {
	global $fastfood_opt;
	
	if ( is_admin() ) return $title;
	
	$title = strip_tags( $title, '<abbr><acronym><b><em><i><del><ins><bdo><strong>' );
	
	if ( empty( $title ) ) {
		if ( !isset( $fastfood_opt['fastfood_blank_title'] ) || empty( $fastfood_opt['fastfood_blank_title'] ) ) return __( '(no title)', 'fastfood' );
		$postdata = array( get_post_format( $id )? __( get_post_format( $id ), 'fastfood' ): __( 'post', 'fastfood' ), get_the_time( get_option( 'date_format' ), $id ) );
		$codes = array( '%f', '%d' );
		return str_replace( $codes, $postdata, $fastfood_opt['fastfood_blank_title'] );
	} else
		return $title;
}

//set the excerpt lenght
if ( !function_exists( 'fastfood_excerpt_length' ) ) {
	function fastfood_excerpt_length( $length ) {
		global $fastfood_opt;
		return (int) $fastfood_opt['fastfood_excerpt_lenght'];
	}
}

// use the "excerpt more" string as a link to the post
function fastfood_excerpt_more( $more ) {
	global $fastfood_opt, $post;
	if ( is_admin() ) return $more;
	if ( isset( $fastfood_opt['fastfood_excerpt_more_txt'] ) && isset( $fastfood_opt['fastfood_excerpt_more_link'] ) ) {
		if ( $fastfood_opt['fastfood_excerpt_more_link'] ) {
			return '<a href="' . get_permalink() . '">' . $fastfood_opt['fastfood_excerpt_more_txt'] . '</a>';
		} else {
			return $fastfood_opt['fastfood_excerpt_more_txt'];
		}
	}
	return $more;
}

// custom text for the "more" tag
function fastfood_more_link( $more_link, $more_link_text ) {
	global $fastfood_opt;
	
	if ( isset( $fastfood_opt['fastfood_more_tag'] ) && !is_admin() ) {
		$text = str_replace ( '%t', get_the_title(), $fastfood_opt['fastfood_more_tag'] );
		return str_replace( $more_link_text, $text, $more_link );
	}
	return $more_link;
}

//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
if ( !function_exists( 'fastfood_friendly_date' ) ) {
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

		echo $dateWithNiceTone;

	}
}

// add links to admin bar
if ( !function_exists( 'fastfood_admin_bar_plus' ) ) {
	function fastfood_admin_bar_plus() {
		global $wp_admin_bar, $fastfood_opt;
		if ( !current_user_can( 'edit_theme_options' ) || !is_admin_bar_showing() )
			return;
		$add_menu_meta = array(
			'target'    => '_blank'
		);
		$wp_admin_bar->add_menu( array(
			'id'        => 'ff_theme_options',
			'parent'    => 'appearance',
			'title'     => __( 'Theme Options', 'fastfood' ),
			'href'      => get_admin_url() . 'themes.php?page=tb_fastfood_functions',
			'meta'      => $add_menu_meta
		) );
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

?>