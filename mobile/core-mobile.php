<?php
/**
 * The mobile theme - Core functions
 *
 * @package fastfood
 * @subpackage mobile
 * @since 0.31
 */


class fastfood_Mobile {

	var $is_mobile = false;

	function __construct () {
		global $fastfood_is_mobile;

		$fastfood_is_mobile = $this->is_mobile = apply_filters( 'fastfood_filter_is_mobile', $this->device_detect() ); // check if is mobile browser

		add_action( 'template_redirect',			array( $this, 'init' ) ); // mobile support
		add_action( 'after_setup_theme',			array( $this, 'setup' ) ); // Tell WordPress to run setup() when the 'after_setup_theme' hook is run.
		add_action( 'widgets_init',					array( $this, 'widget_area_init' ) ); // Register sidebars by running widget_area_init() on the widgets_init hook
		add_action( 'fastfood_hook_change_view' ,	array( $this, 'change_view_link' ) );

	}


	function get_option ( $option ) {

		return fastfood_get_opt( $option );

	}


	// mobile detect
	function device_detect() {

		if ( is_admin() || is_feed() ) return false;

		// #1 check: mobile support is off (via options)
		if ( ! $this->get_option( 'fastfood_mobile_css' ) ) return false;

		// #2 check: mobile override, the user clicked the "switch to desktop/mobile" link. a cookie will be set
		if ( isset( $_GET['mobile_override'] ) ) {
			if ( md5( $_GET['mobile_override'] ) == '532c28d5412dd75bf975fb951c740a30' ) { // 'mobile'
				setcookie( "mobile_override", "mobile", time()+(60*60*24*30*12) );
				return true;
			} else {
				setcookie( "mobile_override", "desktop", time()+(60*60*24*30*12) );
				return false;
			}
		}

		// #3 check: the cookie is already set
		if (isset( $_COOKIE["mobile_override"]) ) {
			if ( md5( $_COOKIE["mobile_override"] ) == '532c28d5412dd75bf975fb951c740a30' ) { // 'mobile'
				return true;
			} else {
				return false;
			}
		}

		// #4 check: search for a mobile user agent
		if ( !isset( $_SERVER['HTTP_USER_AGENT']) ) return false;
		$invalids = array( '+', '*', '?', '^', '$', '(', ')', '[', ']', '&', '*', '%', '/', "'", '"', '<', '>', '\\' );
		// get only 128 characters and delete characters not needed
		$user_agent = str_replace( $invalids,' ',substr( $_SERVER['HTTP_USER_AGENT'],0,128) );
		if ( $this->get_option( 'fastfood_mobile_css' ) && preg_match( '/(ipad|ipod|iphone|android|opera mini|blackberry|palm|symbian|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i' , $user_agent ) ) { // there were other words for mobile detecting but this is enought ;-)
			return true;
		} else {
			return false;
		}

	}


	function init () {
		global $content_width;

		if ( ! $this->is_mobile ) return;

		add_action( 'wp_enqueue_scripts',					array( $this, 'stylesheet' ) );
		add_action( 'fastfood_mobile_hook_comments_before',	array( $this, 'comments_navigation' ) );
		add_action( 'fastfood_mobile_hook_comments_after',	array( $this, 'comments_navigation' ) );
		add_action( 'fastfood_mobile_hook_entry_before',	array( $this, 'posts_navigation' ) );
		add_action( 'fastfood_mobile_hook_entry_after',		array( $this, 'posts_navigation' ) );
		add_action( 'fastfood_mobile_hook_entry_after',		array( $this, 'page_hierarchy' ) );
		add_action( 'fastfood_mobile_hook_content_before',	array( $this, 'search_reminder' ) );
		add_action( 'fastfood_mobile_hook_content_after',	array( $this, 'indexes_navigation' ) );
		add_action( 'comment_form_before',					array( $this, 'enqueue_comments_reply' ) );
		add_filter( 'user_contactmethods',					array( $this, 'new_contactmethods' ),10,1 );
		add_filter( 'widget_tag_cloud_args',				array( $this, 'tag_cloud_filter' ), 90 );
		add_filter( 'widget_categories_args',				array( $this, 'widget_categories_filter' ), 90 );
		add_filter( 'wp_list_categories',					array( $this, 'list_categories_filter' ), 90 );
		add_filter( 'widget_archives_args',					array( $this, 'widget_archives_filter' ), 90 );
		add_filter( 'widget_pages_args',					array( $this, 'widget_pages_filter' ), 90 );
		add_filter( 'body_class' ,							array( $this, 'body_classes' ) );
		add_filter( 'post_class' ,							array( $this, 'post_classes' ) );
		add_filter( 'fastfood_mobile_filter_seztitle' ,		array( $this, 'get_seztitle' ) );
		add_filter( 'comment_form_default_fields' ,			array( $this, 'comments_form_fields' ), 90 );
		add_filter( 'comment_form_defaults' ,				array( $this, 'comment_form_defaults' ), 90 );
		add_filter( 'fastfood_filter_taxomony_separator' ,	array( $this, 'taxomony_separator' ) );

		// Set the content width
		$content_width = 300;

		if ( is_page() )
			if ( is_front_page() )
				locate_template( array( 'mobile/loop-front-page-mobile.php' ), true, false );
			else
				locate_template( array( 'mobile/loop-single-mobile.php' ), true, false );
		elseif ( is_single() )
			locate_template( array( 'mobile/loop-single-mobile.php' ), true, false );
		else
			locate_template( array( 'mobile/loop-index-mobile.php' ), true, false );
		exit;

	}


	function setup() {

		register_nav_menus( array( 'mobile' => __( 'Navigation Menu for mobiles<br><small>only supports the first level of hierarchy</small>', 'fastfood' ) ) );

	}


	function widget_area_init() {

		// Area 0, in the tbm sidebar.
		register_sidebar( array(
			'name'				=> __( 'Mobile Widget Area', 'fastfood' ),
			'id'				=> 'tbm-widget-area',
			'description'		=> '',
			'before_widget'		=> '<div id="%1$s" class="widget %2$s"><div class="widget-body">',
			'after_widget'		=> '</div></div>',
			'before_title'		=> '</div>' . $this->get_seztitle_elements( 'before' ),
			'after_title'		=> $this->get_seztitle_elements( 'after' ) . '<div class="widget-body">',
		) );

	}


	function stylesheet(){

		if ( is_admin() ) return;

		wp_enqueue_style( 'tbm-mobile-style', get_template_directory_uri() . '/mobile/style-mobile.css', false, fastfood_get_info( 'version' ), 'screen' );

	}


	function enqueue_comments_reply() {

		if( get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

	}


	function get_seztitle( $title ){

		return $this->get_seztitle_elements( 'before' ) . $title . $this->get_seztitle_elements( 'after' );

	}


	function get_seztitle_elements( $pos ){

		if ( $pos == 'before' )
			return '<h2 class="tbm-seztit"><a class="up" href="#head">&nbsp;</a><span>';
		elseif ( $pos == 'after' )
			return '</span><a class="down" href="#themecredits">&nbsp;</a></h2>';

	}


	function comments_navigation(){

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {

			?>
				<div class="tbm-pc-navi">
					<?php paginate_comments_links(); ?>
				</div>
			<?php

		}

	}


	function posts_navigation(){

		if ( ! is_single() ) return;
		if ( ! get_next_post() && ! get_previous_post() ) return;

		?>
			<div class="tbm-navi">
					<?php if ( get_next_post() ) { ?><span class="tbm-halfspan tbm-prev"><?php next_post_link( '%link', '&#60;&#60;' ); ?></span><?php } ?>
					<?php if ( get_previous_post() ) { ?><span class="tbm-halfspan tbm-next"><?php previous_post_link( '%link', '&#62;&#62;' ); ?></span><?php } ?>
					<br class="fixfloat">
			</div>
		<?php

	}


	function indexes_navigation(){
		global $paged, $wp_query;

		if ( !$paged )
			$paged = 1;

		echo apply_filters( 'fastfood_mobile_filter_seztitle', sprintf( __( 'page %1$s of %2$s', 'fastfood' ) , $paged, $wp_query->max_num_pages ) );

		if ( $wp_query->max_num_pages > 1 ) {

			?>
				<div class="tbm-index-navi">
					<?php if ( function_exists( 'wp_pagenavi' ) ) { ?>
						<?php wp_pagenavi(); ?>
					<?php } else { ?>
								<?php previous_posts_link( __( 'Previous page', 'fastfood' ) ); ?>
								<?php next_posts_link( __( 'Next page', 'fastfood' ) ); ?>
					<?php } ?>
				</div>
			<?php

		} 

	}


	function page_hierarchy(){
		global $post;

		if ( ! is_page() ) return;

		$args = array(
			'post_type' => 'page',
			'post_parent' => $post->ID,
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'numberposts' => 0
			);

		$sub_pages = get_posts( $args ); // retrieve the child pages

		if ( !empty($sub_pages) ) {

			?>
				<?php echo apply_filters( 'fastfood_mobile_filter_seztitle', __( 'Child pages', 'fastfood' ) ); ?>
				<ul class="tbm-group">
					<?php 
					foreach ( $sub_pages as $children ) {
						echo '<li class="outset"><a href="' . get_permalink( $children ) . '" title="' . esc_attr( strip_tags( get_the_title( $children ) ) ) . '">' . get_the_title( $children ) . '</a></li>';
					}
					?>
				</ul>
			<?php

		}

		$parent_page = $post->post_parent; // retrieve the parent page

		if ( $parent_page ) {
			?>
				<?php echo apply_filters( 'fastfood_mobile_filter_seztitle', __( 'Parent page', 'fastfood' ) ); ?>
				<ul class="tbm-group">
						<li class="outset"><a href="<?php echo get_permalink( $parent_page ); ?>" title="<?php echo esc_attr( strip_tags( get_the_title( $parent_page ) ) ); ?>"><?php echo get_the_title( $parent_page ); ?></a></li>
				</ul>
			<?php
		}

	}


	function search_reminder() {

		$text = __( 'Posts', 'fastfood' );

		if ( is_archive() ) {

			$term = get_queried_object();
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

			$text = $type . ' : <span class="search-term">' . $title . '</span>';

		} elseif ( is_search() ) {

			$text = sprintf( __( 'Search results for &#8220;%s&#8221;', 'fastfood' ), '<span class="search-term">' . esc_html( get_search_query() ) . '</span>' );

		} elseif ( is_404() ) {

			$text = __( 'Error 404', 'fastfood' );

		}

		echo apply_filters( 'fastfood_mobile_filter_seztitle', $text );

	}

	// Custom form fields for the comment form
	function comments_form_fields( $fields ) {

		$commenter	=	wp_get_current_commenter();
		$req		=	get_option( 'require_name_email' );
		$aria_req	=	( $req ? " aria-required='true'" : '' );

		$custom_fields =  array(
			'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
						'<label for="author">' . __( 'Name', 'fastfood' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
			'email'  => '<p class="comment-form-email">' . '<input id="email" name="email" type="text" value="' . sanitize_email(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
						'<label for="email">' . __( 'Email', 'fastfood' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
			'url'    => '<p class="comment-form-url">' . '<input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" />' .
						'<label for="url">' . __( 'Website', 'fastfood' ) . '</label>' .'</p>',
		);

		return $custom_fields;

	}


	// filters comments_form() default arguments
	function comment_form_defaults( $defaults ) {

		$defaults['label_submit']		= __( 'Say It!','fastfood' );
		$defaults['comment_field']		= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
		$defaults['title_reply']		= apply_filters( 'fastfood_mobile_filter_seztitle', __( 'Leave a comment', 'fastfood' ) );
		$defaults['title_reply_to']		= apply_filters( 'fastfood_mobile_filter_seztitle', __( 'Leave a Reply to %s', 'fastfood' ) );
		$defaults['comment_notes_after']		= '';

		return $defaults;

	}


function change_view_link () {

	echo '<span class="hide_if_print"> - <a href="' . add_query_arg( 'mobile_override', 'mobile' ) . '">'. __('Mobile View','fastfood') .'</a></span>';

}


	function taxomony_separator( $sep ) {
		return ' ';
	}


	function tag_cloud_filter( $args = array() ) {
		$args['smallest'] = 1;
		$args['largest'] = 1;
		$args['unit'] = 'em';
		return $args;
	}


	function widget_categories_filter( $args = array() ) {
		$args['hierarchical'] = 0;
		return $args;
	}


	function list_categories_filter( $output ) {
		$pattern = '/<\/a>\s(\(\d+\))/i';
		$replacement = ' <span class="details">$1</span></a>';
		return preg_replace( $pattern, $replacement, $output );
	}


	function widget_archives_filter( $args = array() ) {
		$args['show_post_count'] = 0;
		return $args;
	}


	function widget_pages_filter( $args = array() ) {
		$args['depth'] = 1;
		return $args;
	}


	// Add specific CSS class to body by filter
	function body_classes( $classes ) {

		$classes[] = $this->get_option( 'fastfood_mobile_css_color' );

		return $classes;

	}


	// Add specific CSS class to posts by filter
	function post_classes( $classes ) {

		$classes[] = 'tbm-padded';
		$classes[] = 'tbm-post';

		return $classes;

	}

}

new fastfood_Mobile;
