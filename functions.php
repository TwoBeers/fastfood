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
add_action( 'wp_head', 'fastfood_localize_js' );
add_action( 'template_redirect', 'fastfood_scripts' );
add_action( 'wp_footer', 'fastfood_init_scripts' );
add_action( 'wp_footer', 'fastfood_I_like_it_js' );
// Add custom category page
add_action( 'template_redirect', 'fastfood_allcat' );
// mobile redirect
add_action( 'template_redirect', 'fastfood_mobile' );
// post expander ajax request
add_action('init', 'fastfood_post_expander_activate');
// Custom filters
add_filter( 'the_content', 'fastfood_content_replace' );
add_filter( 'excerpt_length', 'fastfood_new_excerpt_length' );
add_filter( 'get_comment_author_link', 'fastfood_add_quoted_on' );
add_filter( 'user_contactmethods','fastfood_new_contactmethods', 10, 1 );
add_filter( 'post_gallery', 'fastfood_gallery', 10, 2 );
add_filter( 'img_caption_shortcode', 'fastfood_img_caption_shortcode', 10, 3 );
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
if ( get_theme( 'Fastfood' ) ) {
	$fastfood_current_theme = get_theme( 'Fastfood' );
	$fastfood_version = $fastfood_current_theme['Version'];
}

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
			if ( ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) ) {
				wp_enqueue_style( 'thickbox' );
			}
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
		$("#post-widgets-area .widget:nth-child(odd)").css("clear", "left");
		$("#header-widget-area .widget:nth-child(3n+1)").css("clear", "right");
		$("#error404-widgets-area .widget:nth-child(odd)").css("clear", "left");
		<?php if ( ( $fastfood_opt['fastfood_jsani'] == 1 ) ) { ?>
		$.ff_animation();<?php } ?>
		<?php if ( ( $fastfood_opt['fastfood_post_expand'] == 1 ) ) { ?>
		$('a.more-link').ff_postexpander();<?php } ?>
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
				if ( post_password_required( $post ) ) {
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
	function fastfood_get_recententries( $number = 10 ) {
		$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) {
			while ($r->have_posts()) {
				$r->the_post();

				$post_title = get_the_title();
				$post_title_short = get_the_title() ? mb_strimwidth( esc_html( $post_title ), 0, 35, '&hellip;' ) : __( '(no title)', 'fastfood' );
				$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );

				echo '<li><a href="' . get_permalink() . '" title="' . $post_title . '">' . $post_title_short . '</a> ' . __( 'by','fastfood' ) . ' ' . $post_auth . '<div class="preview">';
				if ( post_password_required() ) {
					echo '<img class="alignleft wp-post-image" src="' . get_template_directory_uri() . '/images/lock.png" alt="thumb" />';
					echo '[' . __( 'No preview: this is a protected post','fastfood' ) . ']';
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
			echo '<li class="ql_cat_li"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s",'fastfood' ), $category->name ) . '" ' . '>' . $category->name . '</a> (' . $category->count . ')<div class="cat_preview"><div class="mentit">' . __( 'Recent Posts','fastfood' ) . '</div><ul class="solid_ul">';
			$tmp_cat_ID = $category->cat_ID;
			$post_search_args = array(
				'numberposts' => 5,
				'category' => $tmp_cat_ID,
				'no_found_rows' => true
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

// print posts/pages details
if ( !function_exists( 'fastfood_post_details' ) ) {
	function fastfood_post_details( $auth, $date, $tags, $cats, $hiera = false, $av_size = 48, $featured = false ) {
		global $post;
		?>
			<?php if ( $featured &&  has_post_thumbnail( $post->ID ) ) { echo '<div class="ff-post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail') . '</div>'; } ?>
			<?php if ( $auth ) {
				$author = $post->post_author;
				
				$name = get_the_author_meta('nickname', $author);
				$alt_name = get_the_author_meta('user_nicename', $author);
				$avatar = get_avatar($author, $av_size, 'Gravatar Logo', $alt_name.'-photo');
				$description = get_the_author_meta('description', $author);
				$author_link = get_author_posts_url($author);

				?>
				<div class="ff-author-bio vcard">
					<ul>
						<li class="author-avatar"><?php echo $avatar; ?></li>
						<li class="author-name"><a class="fn" href="<?php echo $author_link; ?>" ><?php echo $name; ?></a></li>
						<li class="author-description note"><?php echo $description; ?> </li>
						<li class="fixfloat"></li>
					<?php if ( get_the_author_meta('twitter', $author) || get_the_author_meta('facebook', $author) ) { ?>
						<li class="author-social">
							<?php if ( get_the_author_meta('twitter', $author) ) echo '<a target="_blank" class="url" title="' . sprintf( __('follow %s on Twitter', 'fastfood'), $name ) . '" href="'.get_the_author_meta('twitter', $author).'"><img alt="twitter" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>'; ?>
							<?php if ( get_the_author_meta('facebook', $author) ) echo '<a target="_blank" class="url" title="' . sprintf( __('follow %s on Facebook', 'fastfood'), $name ) . '" href="'.get_the_author_meta('facebook', $author).'"><img alt="facebook" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>'; ?>
						</li>
					<?php } ?>
					</ul>
				</div>
			<?php } ?>
			<?php if ( $cats ) { echo '<span class="ff-post-details-cats">' . __( 'Categories', 'fastfood' ) . ': ' . '</span>'; the_category( ', ' ); echo '<br/>'; } ?>
			<?php if ( $tags ) { echo '<span class="ff-post-details-tags">' . __( 'Tags', 'fastfood' ) . ': ' . '</span>'; if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags('', ', ', ''); } echo '<br/>'; } ?>
			<?php if ( $date ) { echo '<span class="ff-post-details-date">' . __( 'Published on', 'fastfood' ) . ': ' . '</span>'; echo '<b>' . get_the_time( get_option( 'date_format' ) ) . '</b>'; } ?>
		<?php
	}
}

// display the post title with the featured image
if ( !function_exists( 'fastfood_featured_title' ) ) {
	function fastfood_featured_title( $args = array() ) {
		global $post, $fastfood_opt;
		
		$defaults = array( 'alternative' => '', 'fallback' => '', 'featured' => true, 'href' => get_permalink(), 'target' => '', 'title' => the_title_attribute( array('echo' => 0 ) ) );
		$args = wp_parse_args( $args, $defaults );
		
		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();
		$post_title = $post_title ? $post_title : $args['fallback'];
		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';
		if ( $fastfood_opt['fastfood_featured_title'] == 0 ) $args['featured'] = false;
		$thumb = ( $args['featured'] && has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, array($fastfood_opt['fastfood_featured_title_size'],$fastfood_opt['fastfood_featured_title_size']) ) : '';
		$title_class = $thumb ? 'storytitle featured-' . $fastfood_opt['fastfood_featured_title_size'] : 'storytitle';
		if ( $post_title || $thumb ) $post_title = '<h2 class="' . $title_class . '"><a title="' . $args['title'] . '" href="' . $args['href'] . '"' . $link_target . ' rel="bookmark">' . $thumb . $post_title . '</a></h2>';

		echo $post_title;
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
		if ( $fastfood_opt['fastfood_xinfos_hiera'] == 0) $hiera = false;

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


if ( !function_exists( 'fastfood_breadcrumb' ) ) {
	function fastfood_breadcrumb() {
		global $wp_query, $post, $ff_is_allcat_page;
		
		$opt 						= array();
		$opt['home'] 				= 'Home';
		$opt['sep'] 				= '';
		$opt['archive_prefix'] 		=  __('Archives for %s', 'fastfood' );
		$opt['search_prefix'] 		=  __('Search for "%s"', 'fastfood' );
		$opt['item_tag']			= 'li';
		$opt['item_class']			= '';
		$opt['wrap_tag']			= 'ul';
		$opt['wrap_class']			= '';
		$opt['class_first']			= 'first';
		$opt['class_last']			= 'last';
		$opt['nofollow']			= ' rel="nofollow" ';


		$sep = '||';
		if (!function_exists('fastfood_get_category_parents')) {
			// Copied and adapted from WP source
			function fastfood_get_category_parents($id, $link = FALSE, $separator = '||', $nicename = FALSE){
				global $wp_query;
				$chain = '';
				$parent = &get_category($id);
				if ( is_wp_error( $parent ) )
				   return $parent;

				if ( $nicename )
				   $name = $parent->slug;
				else
				   $name = $parent->cat_name;

				if ( $parent->parent && ($parent->parent != $parent->term_id) )
				   $chain .= get_category_parents($parent->parent, true, $separator, $nicename);

				$chain .= '<span class="crumb-cat">'.$name.' ('.$wp_query->found_posts.')</span>';
				return $chain;
			}
		}
		
		$on_front = get_option('show_on_front');
		if ($on_front == "page") {
			$homelink = '<a class="crumb-home"'.$opt['nofollow'].'href="'.get_permalink(get_option('page_on_front')).'">&nbsp;</a>';
			$bloglink = $homelink.$sep.'<a href="'.get_permalink(get_option('page_for_posts')).'">'.get_the_title(get_option('page_for_posts')).'</a>';
		} else {
			$homelink = '<a class="crumb-home"'.$opt['nofollow'].'href="'.home_url().'">&nbsp;</a>';
			$bloglink = $homelink;
		}
			
		if ( $ff_is_allcat_page ) {
			$output = $homelink.$sep.'<span>'.__( 'All Categories','fastfood' ).'</span>';
		} elseif ( ($on_front == "page" && is_front_page()) || ($on_front == "posts" && is_home()) ) {
			$output = $homelink.$sep.'<span>'.$opt['home'].'</span>';
		} elseif ( $on_front == "page" && is_home() ) {
			$output = $homelink.$sep.'<span>'.get_the_title(get_option('page_for_posts')).'</span>';
		} elseif ( !is_page() ) {
			$output = $bloglink.$sep;
			if ( is_single() && has_category() ) {
				$cats = get_the_category();
				$cat = $cats[0];
				if ( is_object($cat) ) {
					if ($cat->parent != 0) {
						$output .= get_category_parents($cat->term_id, true, $sep);
					} else {
						$output .= '<a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a>'.$sep; 
					}
				}
			}
			if ( is_category() ) {
				$cat = intval( get_query_var('cat') );
				$output .= fastfood_get_category_parents($cat, false, $sep);
			} elseif ( is_tag() ) {
				$output .= '<span class="crumb-tag">'.sprintf( $opt['archive_prefix'], wp_title( '', false, 'right' ) ).' ('.$wp_query->found_posts.')</span>';
			} elseif ( is_date() ) {
				$output .= '<span class="crumb-date">'.sprintf( $opt['archive_prefix'], wp_title( '', false, 'right' ) ).' ('.$wp_query->found_posts.')</span>';
			} elseif ( is_author() ) {
				$output .= '<span class="crumb-auth">'.sprintf( $opt['archive_prefix'], wp_title( '', false, 'right' ) ).' ('.$wp_query->found_posts.')</span>';
			} elseif ( is_404() ) {
				$output .= '<span class="crumb-error">'.__( 'Page not found','fastfood' ).'</span>';
			} elseif ( is_search() ) {
				$output .= '<span class="crumb-search">'.sprintf( $opt['search_prefix'], stripslashes(strip_tags(get_search_query())) ).' ('.$wp_query->found_posts.')</span>';
			} elseif ( is_attachment() ) {
				if ( $post->post_parent ) {
					$output .= '<a href="'.get_permalink( $post->post_parent ).'">'.get_the_title( $post->post_parent ).'</a>'.$sep;
				}
				$output .= '<span>'.get_the_title().'</span>';
			} else if ( is_tax() ) {
				$taxonomy 	= get_taxonomy ( get_query_var('taxonomy') );
				$term 		= get_query_var('term');
				$output .= '<span>'.$taxonomy->label .': '. $term.' ('.$wp_query->found_posts.')</span>';
			} else {
				if ( get_query_var('page') ) {
					$output .= '<a href="'.get_permalink().'">'.get_the_title().'</a>'.$sep.'<span>'.__('Page','fastfood').get_query_var('page').'</span>';
				} else {
					$output .= get_the_title() ? '<span>'.get_the_title().'</span>' : '<span>'.sprintf ( __('post #%s','fastfood'), get_the_ID() ).'</span>';
				}
			}
		} else {
			$post = $wp_query->get_queried_object();

			// If this is a top level Page, it's simple to output the breadcrumb
			if ( 0 == $post->post_parent ) {
				if ( get_query_var('page') ) {
					$output = $homelink.$sep.'<a href="'.get_permalink().'">'.get_the_title().'</a>'.$sep.'<span>'.__('Page','fastfood').get_query_var('page').'</span>';
				} else {
					$output = $homelink.$sep.'<span>'.get_the_title().'</span>';
				}
			} else {
				if (isset($post->ancestors)) {
					if (is_array($post->ancestors))
						$ancestors = array_values($post->ancestors);
					else 
						$ancestors = array($post->ancestors);				
				} else {
					$ancestors = array($post->post_parent);
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse($ancestors);

				// Add the current Page to the ancestors list (as we need it's title too)
				$ancestors[] = $post->ID;

				$links = array();			
				foreach ( $ancestors as $ancestor ) {
					$tmp  = array();
					$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
					$tmp['url'] 	= get_permalink($ancestor);
					$tmp['cur'] = false;
					if ($ancestor == $post->ID) {
						$tmp['cur'] = true;
					}
					$links[] = $tmp;
				}

				$output = $homelink;
				foreach ( $links as $link ) {
					$output .= ' '.$sep;
					if (!$link['cur']) {
						$output .= '<a href="'.$link['url'].'">'.$link['title'].'</a>';
					} else {
						if ( get_query_var('page') ) {
							$output .= '<a href="'.$link['url'].'">'.$link['title'].'</a>'.$sep.'<span>'.__('Page','fastfood').get_query_var('page').'</span>';
						} else {
							$output .= '<span>'.$link['title'].'</span>';
						}
					}
				}
			}
		}
		if ( get_query_var('paged') ) {
			$output .= $sep.'<span>'.__('Page','fastfood').get_query_var('paged').'</span>';
		}

		$output_items = explode( $sep, $output ) ;

		$class					= array();
		$class['wrap']			= ( $opt['wrap_class'] ) ? ' class="'.$opt['wrap_class'].'"' : '';
		$class['item']			= ( $opt['item_class'] ) ? ' class="'.$opt['item_class'].'"' : '';
		$opt['item_class']		= ( $opt['item_class'] ) ? ' '.$opt['item_class'] : '';
		$class['item_first']	= ( $opt['item_class'] || $opt['class_first'] ) ? ' class="'.$opt['class_first'].$opt['item_class'].'"' : '';
		$class['item_last']		= ( $opt['item_class'] || $opt['class_last'] ) ? ' class="'.$opt['class_last'].$opt['item_class'].'"' : '';

		if ( count($output_items) == 0 ) return;
		if ( count($output_items) == 1 ) $output_items[0] = '<'.$opt['item_tag'].' class="'.$opt['last'].'">'.$output_items[0].'</'.$opt['item_tag'].'>';
		if ( count($output_items) > 1 ) {
			foreach ($output_items as $key => $val) {
				if ( $key == (count($output_items)-1) ) {
					$output_items[$key] = '<'.$opt['item_tag'].$class['item_last']	.'>'.$val.'</'.$opt['item_tag'].'>';
				} elseif ( $key == 0 ) {
					$output_items[$key] = '<'.$opt['item_tag'].$class['item_first']	.'>'.$val.'</'.$opt['item_tag'].'>';
				} else {
					$output_items[$key] = '<'.$opt['item_tag'].$class['item']		.'>'.$val.'</'.$opt['item_tag'].'>';
				}
			}
		}

		$output = '<'.$opt['wrap_tag'].$class['wrap'].'>'.implode($opt['sep'], $output_items).'</'.$opt['wrap_tag'].'>';
		?>
		<div id="ff-breadcrumb-wrap">
			<div id="crumbs">
			<?php echo $output;?>
			</div>
		</div>
		<?php
	}
}


//quickbar
if (!function_exists('fastfood_quickbar')) {
	function fastfood_quickbar( $r_posts = true, $p_categories = true, $r_comments = true, $user = true ) {
		global $post, $fastfood_opt, $current_user;
		
		wp_reset_postdata();
		?>

<!-- begin quickbar -->
<div id="quickbar">
	<!-- quickbar tool - uncomment to use
		<div class="menutoolitem">
			<div class="itemimg menutool_trig" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/qbartool.png');"></div>
			<div class="menutool">[put here your code]</div>
		</div>
	quickbar tool -->
	<br />
	<?php if ( $r_posts && $fastfood_opt['fastfood_qbar_recpost'] == 1 ) { //							recent posts menu ?>
		<div class="menuitem">
			<div id="mii_rpost" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'Recent Posts','fastfood' ); ?></div>
					<ul class="solid_ul">
						<?php fastfood_get_recententries() ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $p_categories && $fastfood_opt['fastfood_qbar_cat'] == 1 ) { // 							popular categories menu ?>
		<div class="menuitem">
			<div id="mii_pcats" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'Categories','fastfood' ); ?></div>
					<ul class="solid_ul">
						<?php fastfood_get_categories_wpr(); ?>
						<li style="text-align: right; margin:16px 0 10px;"><a title="<?php _e( 'View all categories','fastfood' ); ?>" href="<?php echo home_url(); ?>/?allcat=y"><?php _e( 'More...','fastfood' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $r_comments && $fastfood_opt['fastfood_qbar_reccom'] == 1 ) { // 						recent comments menu ?>
		<div class="menuitem">
			<div id="mii_rcomm" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'Recent Comments','fastfood' ); ?></div>
					<ul class="solid_ul">
						<?php fastfood_get_recentcomments(); ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $user && $fastfood_opt['fastfood_qbar_user'] == 1 ) { // 								user links menu ?>
		<div class="menuitem" id="user_menuback">
			<div id="mii_cuser" class="itemimg"></div>
			<div class="menuback">
				<div class="menulcont">
					<div class="mentit"><?php _e( 'User','fastfood' ); ?></div>
					<ul class="solid_ul">
						<li id="logged">
							<?php
							if (is_user_logged_in()) { //fix for notice when user not log-in
								get_currentuserinfo();
								$ff_email = $current_user->user_email;
								echo get_avatar( $ff_email, 50, $default=get_template_directory_uri() . '/images/user.png','user-avatar' );
								printf( __( 'Logged in as <a href="%1$s">%2$s</a>.','fastfood' ), admin_url( 'profile.php' ), '<strong>' . $current_user->display_name . '</strong>' );
							} else {
								echo get_avatar( 'dummyemail', 50, $default=get_template_directory_uri() . '/images/user.png','user-avatar' );
								echo __( 'Not logged in','fastfood' );
							}
							?>
						</li>
						<?php if ( ! is_user_logged_in() || current_user_can( 'read' ) ) { wp_register(); }?>
						<?php if ( is_user_logged_in() ) { ?>
							<?php if ( current_user_can( 'read' ) ) { ?>
								<li><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile','fastfood' ); ?></a></li>
								<?php if ( current_user_can( 'publish_posts' ) ) { ?>
									<li><a title="<?php _e( 'Add New Post','fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post','fastfood' ); ?></a></li>
								<?php } ?>
								<?php if ( current_user_can( 'moderate_comments' ) ) {
									$ff_awaiting_mod = wp_count_comments();
									$ff_awaiting_mod = $ff_awaiting_mod->moderated;
									$ff_awaiting_mod = $ff_awaiting_mod ? ' (' . number_format_i18n( $ff_awaiting_mod ) . ')' : '';
								?>
									<li><a title="<?php _e( 'Comments', 'fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'fastfood' ); ?></a><?php echo $ff_awaiting_mod; ?></li>
								<?php } ?>
							<?php } ?>
							<li><a title="<?php _e( 'Log out','fastfood' ); ?>" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e( 'Log out','fastfood' ); ?></a></li>
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
if (!function_exists('fastfood_navbuttons')) {
	function fastfood_navbuttons( $print = 1, $comment = 1, $feed = 1, $trackback = 1, $home = 1, $next_prev = 1, $up_down = 1, $fixed = 1 ) {
		global $post, $fastfood_opt, $ff_is_allcat_page;
		
		$is_post = is_single() && !is_attachment() && !$ff_is_allcat_page;
		$is_image = is_attachment() && !$ff_is_allcat_page;
		$is_page = is_singular() && !is_single() && !is_attachment() && !$ff_is_allcat_page;
		$is_singular = is_singular() && !$ff_is_allcat_page;
	?>

<div id="navbuttons_cont">
	<div id="navbuttons">

		<?php // ------- Print ------- 
			if ( $fastfood_opt['fastfood_navbuttons_print'] && $print && $is_singular ) { ?>
			<div class="minibutton">
				<a href="<?php
					$ff_arr_params['style'] = 'printme';
					if ( get_query_var('page') ) {
						$ff_arr_params['page'] = esc_html( get_query_var( 'page' ) );
					}
					if ( get_query_var('cpage') ) {
						$ff_arr_params['cpage'] = esc_html( get_query_var( 'cpage' ) );
					}
					echo add_query_arg( $ff_arr_params, get_permalink( $post->ID ) );
					?>">
					<span class="minib_img minib_print">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Print preview','fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Leave a comment -------
			if ( $fastfood_opt['fastfood_navbuttons_comment'] && $comment && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { ?>
			<div class="minibutton">
				<a href="#respond"<?php if ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) { echo ' onclick="return addComment.viewForm()"'; } ?>>
					<span class="minib_img minib_comment">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Leave a comment','fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- RSS feed -------
			if ( $fastfood_opt['fastfood_navbuttons_feed'] && $feed && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_post_comments_feed_link( $post->ID, 'rss2' ); ?> ">
					<span class="minib_img minib_rss">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'feed for comments on this post', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Trackback -------
			if ( $fastfood_opt['fastfood_navbuttons_trackback'] && $trackback && $is_singular && pings_open() ) { ?>
			<div class="minibutton">
				<a href="<?php global $ff_tmptrackback; echo $ff_tmptrackback; ?>" rel="trackback">
					<span class="minib_img minib_track">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Trackback URL','fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Home -------
			if ( $fastfood_opt['fastfood_navbuttons_home'] && $home ) { ?>
			<div class="minibutton">
				<a href="<?php echo home_url(); ?>">
					<span class="minib_img minib_home">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Home','fastfood' ); ?></span>
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
			if ( $fastfood_opt['fastfood_navbuttons_nextprev'] && $next_prev && $is_post && get_next_post() ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_permalink( get_next_post() ); ?>">
					<span class="minib_img minib_npage">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php esc_attr( printf( __( 'Next Post', 'fastfood' ) . ': %s', get_the_title( get_next_post() ) ) ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Previous post ------- 
			if ( $fastfood_opt['fastfood_navbuttons_nextprev'] && $next_prev && $is_post && get_previous_post() ) { ?>
			<div class="minibutton">
				<a href="<?php echo get_permalink( get_previous_post() ); ?>">
					<span class="minib_img minib_ppage">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php esc_attr( printf( __( 'Previous Post', 'fastfood' ) . ': %s', get_the_title( get_previous_post() ) ) ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Newer Posts ------- 
			if ( $fastfood_opt['fastfood_navbuttons_newold'] && $next_prev && !$is_singular && !$ff_is_allcat_page && get_previous_posts_link() ) { ?>
			<div class="minibutton nb-nextprev">
				<?php previous_posts_link( '<span class="minib_img minib_ppages">&nbsp;</span>' ); ?>
				<span class="nb_tooltip"><?php echo __( 'Newer Posts', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Older Posts -------
			if ( $fastfood_opt['fastfood_navbuttons_newold'] && $next_prev && !$is_singular && !$ff_is_allcat_page && get_next_posts_link() ) { ?>
			<div class="minibutton nb-nextprev">
				<?php next_posts_link( '<span class="minib_img minib_npages">&nbsp;</span>' ); ?>
				<span class="nb_tooltip"><?php echo __( 'Older Posts', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Top -------
			if ( $fastfood_opt['fastfood_navbuttons_topbottom'] && $up_down ) { ?>
			<div class="minibutton">
				<a href="#">
					<span class="minib_img minib_top">&nbsp;</span>
				</a>
				<span class="nb_tooltip"><?php _e( 'Top of page', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Bottom -------
			if ( $fastfood_opt['fastfood_navbuttons_topbottom'] && $up_down ) { ?>
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
			if ( $ff_imgmeta['image_meta']['shutter_speed'] && (1 / $ff_imgmeta['image_meta']['shutter_speed']) > 1) {
				if ((number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
				or number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1) == 1.5
				or number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1) == 1.6
				or number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1) == 2.5){
					$ff_pshutter = "1/" . number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1, '.', '');
				} else {
					$ff_pshutter = "1/" . number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 0, '.', '');
				}
			} else {
				$ff_pshutter = $ff_imgmeta['image_meta']['shutter_speed'];
			}

			// Start to display EXIF and IPTC data of digital photograph
			echo __("Width", "fastfood" ) . ": " . $ff_imgmeta['width']."px<br />";
			echo __("Height", "fastfood" ) . ": " . $ff_imgmeta['height']."px<br />";
			if ( $ff_imgmeta['image_meta']['created_timestamp'] ) echo __("Date Taken", "fastfood" ) . ": " . date("d-M-Y H:i:s", $ff_imgmeta['image_meta']['created_timestamp'])."<br />";
			if ( $ff_imgmeta['image_meta']['copyright'] ) echo __("Copyright", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['copyright']."<br />";
			if ( $ff_imgmeta['image_meta']['credit'] ) echo __("Credit", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['credit']."<br />";
			if ( $ff_imgmeta['image_meta']['title'] ) echo __("Title", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['title']."<br />";
			if ( $ff_imgmeta['image_meta']['caption'] ) echo __("Caption", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['caption']."<br />";
			if ( $ff_imgmeta['image_meta']['camera'] ) echo __("Camera", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['camera']."<br />";
			if ( $ff_imgmeta['image_meta']['focal_length'] ) echo __("Focal Length", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['focal_length']."mm<br />";
			if ( $ff_imgmeta['image_meta']['aperture'] ) echo __("Aperture", "fastfood" ) . ": f/" . $ff_imgmeta['image_meta']['aperture']."<br />";
			if ( $ff_imgmeta['image_meta']['iso'] ) echo __("ISO", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['iso']."<br />";
			if ( $ff_pshutter ) echo __("Shutter Speed", "fastfood" ) . ": " . sprintf( __("%s seconds", "fastfood" ), $ff_pshutter) . "<br />"
			?>
		</div>
	<?php
	}
}

//add a media (audio and video) player using HTML5 
function fastfood_multimedia_attachment() {
	$embed_defaults = wp_embed_defaults();
	$file = wp_get_attachment_url();
	$mime = get_post_mime_type();
	$mime_type = explode( '/', $mime );

	if ( isset( $mime_type[0] ) && $mime_type[0] == 'audio') {
		?>
		<div class="ff-media-player">
			<audio controls="">
				<source src="<?php echo $file;?>" />
				<span class="ff-player-notice"><?php _e( 'this audio type is not supported by your browser','fastfood' ); ?></span>
			</audio>
		</div>
		<?php
	} elseif ( isset( $mime_type[0] ) && $mime_type[0] == 'video') {
		?>
		<div class="ff-media-player">
			<video controls="">
				<source src="<?php echo $file;?>" />
				<span class="ff-player-notice"><?php _e( 'this video type is not supported by your browser','fastfood' ); ?></span>
			</video>
		</div>
		<?php
	}
}

//add "like" badges to post/page
if ( !function_exists( 'fastfood_I_like_it' ) ) {
	function fastfood_I_like_it(){
		global $fastfood_opt;
		if ( $fastfood_opt['fastfood_I_like_it'] == 0 ) return;
		?>
		<div class="ff-I-like-it">
			<?php if ( $fastfood_opt['fastfood_I_like_it_plus1']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_twitter']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="t-twits"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-count="vertical"></a></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_facebook']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="box_count" data-width="42" data-show-faces="false"></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_linkedin']		== 1 ) { ?><div class="ff-I-like-it-button"><script type="IN/Share" data-url="<?php the_permalink(); ?>" data-counter="top"></script></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_stumbleupon']	== 1 ) { ?><div class="ff-I-like-it-button"><script src="http://www.stumbleupon.com/hostedbadge.php?s=5&r=<?php the_permalink(); ?>"></script></div><?php } ?>
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


<?php
	}
}

//add share links to post/page
if ( !function_exists( 'fastfood_share_this' ) ) {
	function fastfood_share_this( $args = array() ){
		global $post, $fastfood_opt;
		
		if ( $fastfood_opt['fastfood_share_this'] == 0 ) return;
		
		$defaults = array( 'size' => 24, 'echo' => true );
		$args = wp_parse_args( $args, $defaults );
		
		$share = array();
		$pName = rawurlencode($post->post_title);
		$pHref = rawurlencode(get_permalink($post->ID));
		$pPict = rawurlencode(wp_get_attachment_url(get_post_thumbnail_id($post->ID)));

		$share['Twitter'] = array('Twitter', 'http://twitter.com/home?status='.$pName.' - '.$pHref);
		$share['Facebook'] = array('Facebook', 'http://www.facebook.com/sharer.php?u='.$pHref.'&t='.$pName);
		$share['Sina'] = array('Weibo', 'http://v.t.sina.com.cn/share/share.php?url='.$pHref);
		$share['Tencent'] = array('Tencent', 'http://v.t.qq.com/share/share.php?url='.$pHref.'&title='.$pName.'&pic='.$pPict);
		$share['Qzone'] = array('Qzone', 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='.$pHref);
		$share['Buzz'] = array('Google Buzz', 'http://www.google.com/reader/link?url='.$pHref.'&title='.$pName);
		$share['Reddit'] = array('Reddit', 'http://reddit.com/submit?url='.$pHref.'&title='.$pName);
		$share['StumbleUpon'] = array('StumbleUpon', 'http://www.stumbleupon.com/submit?url='.$pHref.'&title='.$pName);
		$share['Digg'] = array('Digg', 'http://digg.com/submit?url='.$pHref);
		$share['Orkut'] = array('Orkut', 'http://promote.orkut.com/preview?nt=orkut.com&tt='.$pName.'&du='.$pHref.'&tn='.$pPict);

		$outer = '<div class="article-share fixfloat">';
		foreach($share as $key => $btn){
			$outer .= '<a class="share-item" rel="nofollow" target="_blank" id="'.$key.'" href="'.$btn[1].'"><img src="'.get_template_directory_uri().'/images/follow/'.$key.'.png" width="'.$args['size'].'" height="'.$args['size'].'" alt="'.$btn[0].' Button"  title="'.sprintf( __( 'Share with %s','fastfood' ), $btn[0] ).'" /></a>';
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
		if ( $fastfood_opt['fastfood_post_formats'] == 1 ) {
			$pformats = array();
			if ( $fastfood_opt['fastfood_post_formats_gallery'] == 1 ) $pformats[] = 'gallery';
			if ( $fastfood_opt['fastfood_post_formats_aside'] == 1 ) $pformats[] = 'aside';
			if ( $fastfood_opt['fastfood_post_formats_status'] == 1 ) $pformats[] = 'status';
			add_theme_support( 'post-formats', $pformats );
		}

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
		font-family: <?php echo $fastfood_opt['fastfood_font_family']; ?>;
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
	#crumbs .last,
	li.current_page_ancestor .hiraquo {
		color: <?php echo $fastfood_opt['fastfood_colors_link_sel']; ?>;
	}	
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
				<div class="cat_preview">
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

//custom gallery function
if ( !function_exists( 'fastfood_gallery' ) ) {
	function fastfood_gallery( $output, $attr ) {
		global $post, $fastfood_opt;

		static $ff_gallery_instance = 0;
		$ff_gallery_instance++;
		
		// orderby
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}

		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $attr));

		if ( $fastfood_opt['fastfood_force_link_to_image'] == 1 ) $attr['link'] = 'file';
		
		$id = intval($id);
		if ( 'RAND' == $order )
			$orderby = 'none';

		if ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$columns = intval($columns);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$ff_gallery_instance}";

		$gallery_style = $gallery_div = '';
		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
			if ( $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}

		$output .= "
				<br style='clear: both;' />
			</div>\n";

		return $output;

	}
}

// custom image caption
if ( !function_exists( 'fastfood_img_caption_shortcode' ) ) {
	function fastfood_img_caption_shortcode( $deprecated, $attr, $content = null ) {

		extract(shortcode_atts(array(
			'id'	=> '',
			'align'	=> 'alignnone',
			'width'	=> '',
			'caption' => ''
		), $attr));

		if ( 1 > (int) $width || empty($caption) )
			return $content;

		if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

		return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px"><div class="wp-caption-inside">'
		. do_shortcode( $content ) . '<div class="wp-caption-text">' . $caption . '</div></div></div>';
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

// load the admin part
get_template_part('lib/admin');

// load the custom widgets module
get_template_part('lib/widgets');

// load the custom hooks
get_template_part('lib/hooks');

?>