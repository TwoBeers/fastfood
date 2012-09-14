<?php
/**
 * The mobile theme
 *
 * @package fastfood
 * @since fastfood 0.31
 */

// mobile support
add_action( 'template_redirect', 'fastfood_mobile' );
// Tell WordPress to run fastfood_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'fastfood_mobile_setup' );
// Register sidebars by running fastfood_mobile_widget_area_init() on the widgets_init hook
add_action( 'widgets_init', 'fastfood_mobile_widget_area_init' );

if ( !function_exists( 'fastfood_mobile_device_detect' ) ) {
	function fastfood_mobile_device_detect() {
		global $fastfood_opt;

		if ( is_admin() ) return false;

		// #1 check: mobile support is off (via options)
		if ( ( isset( $fastfood_opt['fastfood_mobile_css'] ) && ( $fastfood_opt['fastfood_mobile_css'] == 0) ) ) return false;
		
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
		if (isset($_COOKIE["mobile_override"])) {
			if ( md5( $_COOKIE["mobile_override"] ) == '532c28d5412dd75bf975fb951c740a30' ) { // 'mobile'
				return true;
			} else {
				return false;
			}
		}
		
		// #4 check: search for a mobile user agent
		if ( !isset($_SERVER['HTTP_USER_AGENT']) ) return false;
		$invalids = array( '+', '*', '?', '^', '$', '(', ')', '[', ']', '&', '*', '%', '/', "'", '"', '<', '>', '\\' );
        // get only 128 characters and delete characters not needed
        $user_agent = str_replace($invalids,' ',substr($_SERVER['HTTP_USER_AGENT'],0,128));
		if ( ( !isset( $fastfood_opt['fastfood_mobile_css'] ) || ( $fastfood_opt['fastfood_mobile_css'] == 1) ) && preg_match( '/(ipad|ipod|iphone|android|opera mini|blackberry|palm|symbian|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i' , $user_agent ) ) { // there were other words for mobile detecting but this is enought ;-)
			return true;
		} else {
			return false;
		}
	}
}
$fastfood_is_mobile = fastfood_mobile_device_detect(); // check if is mobile browser

// show mobile version
if ( !function_exists( 'fastfood_mobile' ) ) {
	function fastfood_mobile () {
		global $fastfood_is_mobile;
		if ( $fastfood_is_mobile ) {

			// Add stylesheets
			add_action( 'wp_enqueue_scripts', 'fastfood_mobile_stylesheet' );
			// Custom filters
			add_filter( 'user_contactmethods','fastfood_mobile_new_contactmethods',10,1 );
			add_filter( 'widget_tag_cloud_args', 'fastfood_mobile_tag_cloud_filter', 90 );
			add_filter( 'widget_categories_args', 'fastfood_mobile_widget_categories_filter', 90 );
			add_filter( 'widget_archives_args', 'fastfood_mobile_widget_archives_filter', 90 );
			add_filter( 'widget_pages_args', 'fastfood_mobile_widget_pages_filter', 90 );
			add_filter( 'fastfood_widget_pop_categories_args', 'fastfood_mobile_widget_pop_categories_filter', 90 );

			if ( is_page() )
				if ( is_front_page() )
					locate_template( array( 'mobile/loop-front-page-mobile.php' ), true, false );
				else
					locate_template( array( 'mobile/loop-page-mobile.php' ), true, false );
			elseif ( is_single() )
				locate_template( array( 'mobile/loop-single-mobile.php' ), true, false );
			else
				locate_template( array( 'mobile/loop-index-mobile.php' ), true, false );
			exit;
		}
	}
}


if ( !function_exists( 'fastfood_mobile_widget_area_init' ) ) {
	function fastfood_mobile_widget_area_init() {
		// Area 0, in the tbm sidebar.
		register_sidebar( array(
			'name' => __( 'Mobile Widget Area', 'fastfood' ),
			'id' => 'tbm-widget-area',
			'description' => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-body">',
			'after_widget' => '</div></div>',
			'before_title' => '</div>' . fastfood_mobile_seztitle( 'before' ),
			'after_title' => fastfood_mobile_seztitle( 'after' ) . '<div class="widget-body">',
		) );

	}
}



// Add stylesheets to page
if ( !function_exists( 'fastfood_mobile_stylesheet' ) ) {
	function fastfood_mobile_stylesheet(){
		global $fastfood_version;
		if ( is_admin() ) return;
		wp_enqueue_style( 'tbm-mobile-style', get_template_directory_uri() . '/mobile/style-mobile.css', false, $fastfood_version, 'screen' );
	}
}

if ( !function_exists( 'fastfood_mobile_seztitle' ) ) {
	function fastfood_mobile_seztitle( $a ){
		if ( $a == 'before' ) 
			return '<h2 class="tbm-seztit"><a class="up" href="#head">&nbsp;</a><span>';
		else
			return '</span><a class="down" href="#themecredits">&nbsp;</a></h2>';
	}
}

// print extra info for posts/pages
if ( !function_exists( 'fastfood_mobile_post_details' ) ) {
	function fastfood_mobile_post_details( $auth, $date, $tags, $cats, $hiera = false, $av_size = 48, $featured = false ) {
		global $post;
		?>
			<?php if ( $featured &&  has_post_thumbnail( $post->ID ) ) { echo '<div class="tbm-post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail') . '</div>'; } ?>
			<?php if ( $auth ) {
				$author = $post->post_author;
				
				$name = get_the_author_meta('nickname', $author);
				$alt_name = get_the_author_meta('user_nicename', $author);
				$avatar = get_avatar($author, $av_size, 'Gravatar Logo', $alt_name.'-photo');
				$description = get_the_author_meta('description', $author);
				$author_link = get_author_posts_url($author);

				?>
				<div class="tbm-author-bio vcard">
					<ul>
						<li class="author-avatar"><?php echo $avatar; ?></li>
						<li class="author-name"><a class="fn" href="<?php echo $author_link; ?>" ><?php echo $name; ?></a></li>
						<li class="author-description note"><?php echo $description; ?> </li>
						<li class="author-social">
							<?php if ( get_the_author_meta('twitter', $author) ) echo '<a target="_blank" class="url" title="' . sprintf( __('follow %s on Twitter', 'fastfood'), $name ) . '" href="'.get_the_author_meta('twitter', $author).'"><img alt="twitter" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/twitter.png" /></a>'; ?>
							<?php if ( get_the_author_meta('facebook', $author) ) echo '<a target="_blank" class="url" title="' . sprintf( __('follow %s on Facebook', 'fastfood'), $name ) . '" href="'.get_the_author_meta('facebook', $author).'"><img alt="facebook" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/facebook.png" /></a>'; ?>
						</li>
					</ul>
				</div>
			<?php } ?>
			<div class="tbm-post-details">
				<?php if ( $cats ) { echo '<span class="tbm-post-details-cats">' . __( 'Categories', 'fastfood' ) . ': ' . '</span>'; the_category( ' ' ); echo '<br/>'; } ?>
				<?php if ( $tags ) { echo '<span class="tbm-post-details-tags">' . __( 'Tags', 'fastfood' ) . ': '; if ( !get_the_tags() ) { echo __( 'No Tags', 'fastfood' ) . '</span>'; } else { the_tags('</span>', '', ''); } echo '<br/>'; } ?>
				<?php if ( $date ) { echo '<span class="tbm-post-details-date">' . sprintf( __( 'Published on: %1$s', 'fastfood' ), '<b>' . get_the_time( get_option( 'date_format' ) ) . '</b>' ) . '</span>'; } ?>
				<div class="fixfloat"> </div>
			</div>
		<?php
	}
}

if ( !function_exists( 'fastfood_mobile_setup' ) ) {
	function fastfood_mobile_setup() {
		
		register_nav_menus( array( 'mobile' => __( 'Navigation Menu for mobiles<br><small>only supports the first level of hierarchy</small>', 'fastfood' ) ) );
	
	}
}

function fastfood_mobile_tag_cloud_filter($args = array()) {
   $args['smallest'] = 1;
   $args['largest'] = 1;
   $args['unit'] = 'em';
   return $args;
}

function fastfood_mobile_widget_categories_filter($args = array()) {
   $args['hierarchical'] = 0;
   $args['show_count'] = 0;
   return $args;
}

function fastfood_mobile_widget_archives_filter($args = array()) {
   $args['show_post_count'] = 0;
   return $args;
}

function fastfood_mobile_widget_pages_filter($args = array()) {
   $args['depth'] = 1;
   return $args;
}

function fastfood_mobile_widget_pop_categories_filter($args = array()) {
   $args['show_count'] = 0;
   return $args;
}

?>