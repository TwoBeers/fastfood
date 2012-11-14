<?php

/* custom actions */
add_action( 'after_setup_theme', 'fastfood_setup' ); // Tell WordPress to run fastfood_setup() when the 'after_setup_theme' hook is run.
add_action( 'widgets_init', 'fastfood_widget_area_init' ); // Register sidebars by running fastfood_widget_area_init() on the widgets_init hook
add_action( 'wp_enqueue_scripts', 'fastfood_stylesheet' ); // Add stylesheets
add_action( 'wp_enqueue_scripts', 'fastfood_scripts' ); // Add js animations
add_action( 'wp_footer', 'fastfood_body_class_script' );
add_action( 'wp_footer', 'fastfood_I_like_it_js' );
add_action( 'init', 'fastfood_post_expander_activate' ); // post expander ajax request
add_action( 'admin_bar_menu', 'fastfood_admin_bar_plus', 999 ); // add links to admin bar
add_action( 'wp_print_styles', 'fastfood_deregister_styles', 100 ); // deregister styles

/* custom filters */
add_filter( 'embed_oembed_html', 'fastfood_wmode_transparent', 10, 3);
add_filter( 'img_caption_shortcode', 'fastfood_img_caption_shortcode', 10, 3 );
add_filter( 'previous_posts_link_attributes', 'fastfood_previous_posts_link_attributes', 10, 1 );
add_filter( 'next_posts_link_attributes', 'fastfood_next_posts_link_attributes', 10, 1 );
add_filter( 'the_content', 'fastfood_quote_content' );
add_filter( 'post_gallery', 'fastfood_gallery_shortcode', 10, 2 );
add_filter( 'tb_chat_load_style', '__return_false' );

// load theme options in $fastfood_opt variable, globally retrieved in php files
$fastfood_opt = get_option( 'fastfood_options' );

// check if is mobile browser
$fastfood_is_mobile = false;

// check if is ie6
$fastfood_is_ie6 = fastfood_ie6_detect();

function fastfood_ie6_detect() {
if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 6' ) !== false ) && !( strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera' ) !== false ) ) {
		return true;
	} else {
		return false;
	}
}

// load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes)
require_once( 'lib/the_bird.php' ); // load "the bird" core functions
require_once( 'mobile/core-mobile.php' ); // load mobile functions
require_once( 'lib/hooks.php' ); // load the custom hooks
require_once( 'lib/quickbar.php' ); // load the quickbar functions
require_once( 'lib/my-custom-background.php' ); // load the custom background feature
require_once( 'lib/header-image-slider.php' ); // load the custom header stuff
require_once( 'lib/comment-reply.php' ); // load comment reply script
require_once( 'lib/admin.php' ); // load the admin stuff
if ( $fastfood_opt['fastfood_audio_player'] == 1 ) require_once( 'lib/audio-player.php' ); // load the audio player module
if ( $fastfood_opt['fastfood_custom_widgets'] == 1 ) require_once( 'lib/widgets.php' ); // load the custom widgets module

// Set the content width based on the theme's design
if ( ! isset( $content_width ) ) {
	if ( ! $fastfood_is_mobile ) {
		$content_width = 560;
	} else {
		$content_width = 300;
	}
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

// skip every sidebar if in print preview
if ( !function_exists( 'fastfood_get_sidebar' ) ) {
	function fastfood_get_sidebar( $name = '' ) {
		global $fastfood_is_printpreview;
		
		if ( $fastfood_is_printpreview ) return;
		
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
		global $fastfood_opt, $fastfood_version, $fastfood_is_printpreview, $fastfood_is_mobile, $fastfood_is_ie6;

		if ( is_admin() || $fastfood_is_mobile ) return;

		// ie6 style
		if ( $fastfood_is_ie6 ) {
			wp_enqueue_style( 'fastfood-ie6', get_template_directory_uri() . '/css/ie6.css', false, $fastfood_version, 'screen' );
			return;
		}
		//shows print preview / normal view
		if ( $fastfood_is_printpreview ) { //print preview
			wp_enqueue_style( 'fastfood-print-preview', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'screen' );
			wp_enqueue_style( 'fastfood-general-preview', get_template_directory_uri() . '/css/print_preview.css', false, $fastfood_version, 'screen' );
		} else { //normal view
			if ( ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) ) {
				wp_enqueue_style( 'thickbox' );
			}
			wp_enqueue_style( 'fastfood-general-style', get_stylesheet_uri(), false, $fastfood_version, 'screen' );
			//google font
			if ( $fastfood_opt['fastfood_google_font_family'] ) {
				$gwf_family = 'family=' . urlencode( $fastfood_opt['fastfood_google_font_family'] );
				$gwf_subset = $fastfood_opt['fastfood_google_font_subset']? '&subset=' . urlencode( str_replace( array(' ','"'), '', $fastfood_opt['fastfood_google_font_subset'] ) ) : '';
				$gwf_url = 'http://fonts.googleapis.com/css?' . $gwf_family . $gwf_subset;
				wp_enqueue_style( 'fastfood-google-fonts', $gwf_url );
			}
		}
		//print style
		wp_enqueue_style( 'fastfood-print-style', get_template_directory_uri() . '/css/print.css', false, $fastfood_version, 'print' );
	}
}

// get js modules
if ( !function_exists( 'fastfood_get_js_modules' ) ) {
	function fastfood_get_js_modules() {
		global $fastfood_opt;

		$modules = array(
			'main_menu',
			'navigation_buttons',
			'quickbar_tools',
			'quickbar_panels',
			'entry_meta',
			'widgets_style',
		);
		
		if ( $fastfood_opt['fastfood_post_expand'] )		$modules[] = 'post_expander';
		if ( $fastfood_opt['fastfood_gallery_preview'] )	$modules[] = 'thickbox';
		if ( $fastfood_opt['fastfood_quotethis'] )			$modules[] = 'quote_this';

		$modules = implode(',', $modules);

		return $modules;

	}
}

// add scripts
if ( !function_exists( 'fastfood_scripts' ) ) {
	function fastfood_scripts(){
		global $fastfood_opt, $fastfood_is_printpreview, $fastfood_version, $fastfood_is_mobile, $fastfood_is_ie6;

		if ( is_admin() ) return;

		if ( $fastfood_is_mobile || $fastfood_is_printpreview ) return; //no scripts in print preview or mobile view

		if ( ( $fastfood_opt['fastfood_jsani'] == 1 ) ) {
			wp_enqueue_script( 'fastfood-script', get_template_directory_uri() . '/js/fastfoodscript.dev.js', array( 'jquery', 'jquery-effects-core' ), $fastfood_version, true ); //fastfood js
			$data = array(
				'script_modules' => fastfood_get_js_modules(),
				'post_expander_wait' => __( 'Post loading, please wait...', 'fastfood' ),
				'quote_link_info' => esc_attr( __( 'Add selected text as a quote', 'fastfood' ) ),
				'quote_link_alert' => __( 'Nothing to quote. First of all you should select some text...', 'fastfood' )
			);
			wp_localize_script( 'fastfood-script', 'fastfood_l10n', $data );
		}

		if ( ( $fastfood_opt['fastfood_gallery_preview'] == 1 ) ) {
			wp_enqueue_script( 'thickbox' );
		}
	}
}

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

// Pages Menu
if ( !function_exists( 'fastfood_pages_menu' ) ) {
	function fastfood_pages_menu() {
		echo '<ul id="mainmenu">';
		wp_list_pages( 'sort_column=menu_order&title_li=' ); // menu-order sorted
		echo '</ul>';
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

		$defaults = array( 'alternative' => '', 'fallback' => '', 'featured' => true, 'href' => get_permalink(), 'target' => '', 'title' => the_title_attribute( array( 'echo' => 0 ) ), 'echo' => 1 );
		$args = wp_parse_args( $args, $defaults );

		if ( $fastfood_opt['fastfood_hide_pages_title'] == 1 && is_page() ) return;

		if ( $fastfood_opt['fastfood_hide_posts_title'] == 1 && is_single() ) return;

		$selected_ids = explode( ',', $fastfood_opt['fastfood_hide_selected_entries_title'] );
		if ( in_array( $post->ID, $selected_ids ) ) return;

		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();
		$post_title = $post_title ? $post_title : $args['fallback'];
		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';
		if ( $fastfood_opt['fastfood_featured_title'] == 0 ) $args['featured'] = false;
		$thumb = ( $args['featured'] && has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, array( $fastfood_opt['fastfood_featured_title_size'], $fastfood_opt['fastfood_featured_title_size'] ) ) : '';
		$title_class = $thumb ? 'entry-title storytitle featured-' . $fastfood_opt['fastfood_featured_title_size'] : 'storytitle';
		$title_content = is_singular() ? $thumb . $post_title : '<a title="' . $args['title'] . '" href="' . $args['href'] . '"' . $link_target . ' rel="bookmark">' . $thumb . $post_title . '</a>';
		if ( $post_title || $thumb ) $post_title = '<h2 class="' . $title_class . '">' . $title_content . '</h2>';

		if ( $args['echo'] )
			echo $post_title;
		else 
			return $post_title;

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
				<?php if ( $args['date'] ) { printf( __( 'Published on: %s', 'fastfood' ), get_the_time( get_option( 'date_format' ) ) ) ; echo '<br>'; }?>
				<?php if ( $args['comms'] ) { echo __( 'Comments', 'fastfood' ) . ': '; comments_popup_link( __( 'No Comments', 'fastfood' ), __( '1 Comment', 'fastfood' ), __( '% Comments', 'fastfood' ) ); echo '<br>'; } ?>
				<?php if ( $args['tags'] ) { echo __( 'Tags', 'fastfood' ) . ': '; if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags( '', ', ', '' ); }; echo '<br>';  } ?>
				<?php if ( $args['cats'] ) { echo __( 'Categories', 'fastfood' ) . ': '; the_category( ', ' ); echo '<br>'; } ?>
				<?php edit_post_link( __( 'Edit', 'fastfood' ) ); ?>
			</div>
		<?php
		}
	}
}

//navigation bar
if ( !function_exists( 'fastfood_navbuttons' ) ) {
	function fastfood_navbuttons( $args = '' ) {
		global $post, $fastfood_opt, $fastfood_is_allcat_page;

		wp_reset_postdata();

		$is_post = is_single() && !is_attachment() && !$fastfood_is_allcat_page;
		$is_image = is_attachment() && !$fastfood_is_allcat_page;
		$is_page = is_singular() && !is_single() && !is_attachment() && !$fastfood_is_allcat_page;
		$is_singular = is_singular() && !$fastfood_is_allcat_page;

		$defaults = array( 'print' => 1, 'comment' => 1, 'feed' => 1, 'trackback' => 1, 'home' => 1, 'next_prev' => 1, 'up_down' => 1, 'fixed' => 1 );
		$args = wp_parse_args( $args, $defaults );

	?>

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
				<a class="show_comment_form" href="#respond">
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
				<a href="<?php echo get_trackback_url(); ?>" rel="trackback">
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
			if ( $fastfood_opt['fastfood_navbuttons_newold'] && $args['next_prev'] && !$is_singular && !$fastfood_is_allcat_page && get_previous_posts_link() ) { ?>
			<div class="minibutton nb-nextprev">
				<?php previous_posts_link( '<span class="minib_img minib_ppages">&nbsp;</span>' ); ?>
				<span class="nb_tooltip"><?php echo __( 'Newer Posts', 'fastfood' ); ?></span>
			</div>
		<?php } ?>

		<?php // ------- Older Posts -------
			if ( $fastfood_opt['fastfood_navbuttons_newold'] && $args['next_prev'] && !$is_singular && !$fastfood_is_allcat_page && get_next_posts_link() ) { ?>
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

	<?php
	}
}

//add "like" badges to post/page
if ( !function_exists( 'fastfood_I_like_it' ) ) {
	function fastfood_I_like_it(){
		global $fastfood_opt, $fastfood_is_printpreview, $post;

		if ( ( $fastfood_opt['fastfood_I_like_it'] == 0 ) || $fastfood_is_printpreview ) return;
		if ( ( $fastfood_opt['fastfood_I_like_it_plus1'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_twitter'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_facebook'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_linkedin'] == 0 ) && ( $fastfood_opt['fastfood_I_like_it_stumbleupon'] == 0 ) && ( ( $fastfood_opt['fastfood_I_like_it_pinterest']	== 0 ) || ( ( $fastfood_opt['fastfood_I_like_it_pinterest']	== 1 ) && !is_attachment() ) ) ) return;

		$pName = rawurlencode( get_the_title( $post->ID ) );
		$pHref = rawurlencode( get_permalink( $post->ID ) );
		$psHref = rawurlencode( home_url() . '/?p=' . $post->ID );

		?>
		<div class="ff-I-like-it">
			<?php if ( $fastfood_opt['fastfood_I_like_it_plus1']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="g-plusone" data-size="tall" data-href="<?php echo $pHref; ?>"></div></div><?php } ?>
			<?php if ( $fastfood_opt['fastfood_I_like_it_twitter']		== 1 ) { ?><div class="ff-I-like-it-button"><div class="t-twits"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $pHref; ?>" data-text="<?php echo $pName . ': ' . $psHref; ?>" data-count="vertical"></a></div></div><?php } ?>
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
		global $fastfood_opt, $fastfood_is_printpreview;

		if ( ( $fastfood_opt['fastfood_I_like_it'] == 0 ) || $fastfood_is_printpreview ) return;
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
			if ( $fastfood_opt['fastfood_post_formats_quote'] == 1 ) $pformats[] = 'quote';
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

		add_theme_support( 'custom-header', $args );
	}
}

// the custom header (filterable)
if ( !function_exists( 'fastfood_header' ) ) {
	function fastfood_header(){
		global $fastfood_is_printpreview, $fastfood_opt;
		
		if ( $fastfood_is_printpreview )
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
							<h1><a href="' . home_url() . '/">' . get_bloginfo( 'name' ) . '</a></h1>
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

		global $fastfood_is_printpreview, $fastfood_is_mobile, $fastfood_opt;
		if ( $fastfood_is_printpreview || $fastfood_is_mobile ) return;

		if ( ( 'blank' == get_header_textcolor() ) || ( $fastfood_opt['fastfood_head_link'] == 1 ) )
			$style = 'display:none;';
		else
			$style = 'color:#' . get_header_textcolor() . ';';


		$min_height = get_custom_header()->height;

		?>
<style type="text/css">
	#head-wrap {
		background: transparent url( '<?php header_image(); ?>' ) right bottom no-repeat;
		min-height: <?php echo $min_height; ?>px;
	}
	#head h1 a,
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
	#navxt-crumbs li.current_item,
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

//add a fix for embed videos overlying quickbar
if ( !function_exists( 'fastfood_wmode_transparent' ) ) {
	function fastfood_wmode_transparent( $html, $url = null, $attr = null ) {
		if ( strpos( $html, '<embed ' ) !== false ) {
			$html = str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed', $html);
			$html = str_replace('<embed ', '<embed wmode="transparent" ', $html);
			return $html;
		} elseif ( strpos ( $html, 'feature=oembed' ) !== false )
			return str_replace( 'feature=oembed', 'feature=oembed&wmode=transparent', $html );
		else
			return $html;
	}
}

function fastfood_quote_content( $content ) {
	global $fastfood_opt;

	/* Check if we're displaying a 'quote' post. */
	if ( has_post_format( 'quote' ) && $fastfood_opt['fastfood_post_formats_quote'] == 1 ) {

		/* Match any <blockquote> elements. */
		preg_match( '/<blockquote.*?>/', $content, $matches );

		/* If no <blockquote> elements were found, wrap the entire content in one. */
		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;
}

// the gallery shortcode filter. supports 'ids' attribute (WP3.5)
function fastfood_gallery_shortcode( $output, $attr ) {
	global $post, $wp_locale, $fastfood_opt;

	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
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
		'ids'        => '',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	if ( $fastfood_opt['fastfood_force_link_to_image'] == 1 ) $attr['link'] = 'file';

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty( $ids ) ) {
		// what's the difference between 'ids' and 'include'? 'ids' attribute is almost useless
		$include = $ids;
	}

	if ( !empty( $include ) ) {
		// 'include' is explicitly ordered
		$orderby = 'post__in';
	}

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
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
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = $gallery_div;

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
			'href'      => get_admin_url() . 'themes.php?page=fastfood_theme_options',
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