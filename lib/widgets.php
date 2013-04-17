<?php
/**
 * widgets.php
 *
 * Fastfood Widgets
 *
 * @package fastfood
 * @since fastfood 0.27
 */


add_action( 'widgets_init',		'fastfood_widget_area_init' );
add_action( 'widgets_init',		'fastfood_widgets_init' );


/**
 * Define default Widget arguments
 */
function fastfood_get_default_widget_args( $extra_wrap_class = '' ) {
	$widget_args = array(
		// Widget container opening tag, with classes
		'before_widget' => $extra_wrap_class ? '<div class="' . $extra_wrap_class . '"><div id="%1$s" class="widget %2$s">' : '<div id="%1$s" class="widget %2$s">',
		// Widget container closing tag
		'after_widget' => $extra_wrap_class ? '</div></div>' : '</div>',
		// Widget Title container opening tag, with classes
		'before_title' => '<div class="w_title">',
		// Widget Title container closing tag
		'after_title' => '</div>'
	);
	return $widget_args;
}


/**
 * Register sidebars
 */
function fastfood_widget_area_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Sidebar Widget Area', 'fastfood' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The sidebar widget area', 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

	// Area 2, located under the main menu.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Menu Widget Area', 'fastfood' ),
			'id' => 'header-widget-area',
			'description' => __( 'The widget area under the main menu', 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

	// Area 7, located after post/page content.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Post/Page Widget Area', 'fastfood' ),
			'id' => 'post-widgets-area',
			'description' => __( "The widget area after the post/page content. It's visible only in single posts/pages/attachments", 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'First Footer Widget Area', 'fastfood' ),
			'id' => 'first-footer-widget-area',
			'description' => __( 'The first footer widget area', 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Second Footer Widget Area', 'fastfood' ),
			'id' => 'second-footer-widget-area',
			'description' => __( 'The second footer widget area', 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Third Footer Widget Area', 'fastfood' ),
			'id' => 'third-footer-widget-area',
			'description' => __( 'The third footer widget area', 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

	// Area 6, located in page 404.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Page 404', 'fastfood' ),
			'id' => 'error404-widgets-area',
			'description' => __( 'Enrich the page 404 with some useful widgets', 'fastfood' ),
		),
		fastfood_get_default_widget_args()
	) );

}


/**
 * Popular_Posts widget class
 */
class Fastfood_Widget_Popular_Posts extends WP_Widget {

	function Fastfood_Widget_Popular_Posts() {
		$widget_ops = array('classname' => 'tb_popular_posts', 'description' => __( 'The most commented posts on your site','fastfood') );
		$this->WP_Widget('ff-popular-posts', __('Popular Posts','fastfood'), $widget_ops);
		$this->alt_option_name = 'tb_popular_posts';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'tb_popular_posts', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters( 'widget_title', empty($instance['title'] ) ? __( 'Popular Posts','fastfood' ) : $instance['title'], $instance, $this->id_base );
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
		$use_thumbs = ( !isset($instance['thumb']) || $thumb = (int) $instance['thumb'] ) ? 1 : 0;

		$r = new WP_Query( array( 'showposts' => $number, 'no_found_rows' => true, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'orderby' => 'comment_count' ) );
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php while ( $r->have_posts()) : $r->the_post(); ?>
		<li<?php if ( $use_thumbs ) echo ' class="li-with-thumbs"'; ?>>
			<?php $the_format = get_post_format( get_the_ID() ) ? get_post_format( get_the_ID() ) : 'standard'; ?>
			<?php $the_thumb = $use_thumbs? fastfood_get_the_thumb( array( 'id' => get_the_ID(), 'default' => '<span class="tbm-format f-' . $the_format . '"></span>' ) ) : ''; ?>
			<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php echo $the_thumb; if ( get_the_title() ) the_title(); else the_ID(); ?> <span class="details">(<?php echo get_comments_number(); ?>)</span></a>
		</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'tb_popular_posts', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_popular_posts'] ) )
			delete_option( 'tb_popular_posts' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('tb_popular_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		if ( !isset( $instance['number'] ) || !$number = (int) $instance['number'] )
			$number = 5;
		$thumb = 1;
		if ( isset( $instance['thumb'] ) && !$thumb = (int) $instance['thumb'] )
			$thumb = 0;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:','fastfood' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show post thumbnails','fastfood' ); ?></label>
		</p>
<?php
	}
}


/**
 * Latest commented posts widget
 */
class Fastfood_Widget_Latest_Commented_Posts extends WP_Widget {

	function Fastfood_Widget_Latest_Commented_Posts() {
		$widget_ops = array( 'classname' => 'tb_latest_commented_posts', 'description' => __( 'The latest commented posts/pages of your site','fastfood' ) );
		$this->WP_Widget( 'ff-recent-comments', __( 'Latest activity','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_latest_commented_posts';

		add_action( 'comment_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'transition_comment_status', array( &$this, 'flush_widget_cache' ) );
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_latest_commented_posts', 'widget' );
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get( 'tb_latest_commented_posts', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract( $args, EXTR_SKIP );
 		$output = '';
 		$title = apply_filters( 'widget_title', empty($instance['title']) ? __( 'Latest activity','fastfood' ) : $instance['title'] );

		if ( ! $number = (int) $instance['number'] )
 			$number = 5;
 		else if ( $number < 1 )
 			$number = 1;
		$use_thumbs = ( !isset( $instance['thumb'] ) || $thumb = (int) $instance['thumb'] ) ? 1 : 0;

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment', 'number' => 200 ) );
		$post_array = array();
		$counter = 0;
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;
		$li_class = $use_thumbs ? ' class="li-with-thumbs"' : '';

		$output .= '<ul>';
		if ( $comments ) {
			foreach ( ( array ) $comments as $comment) {
				if ( ! in_array( $comment->comment_post_ID, $post_array ) ) {
					$post = get_post( $comment->comment_post_ID );
					setup_postdata( $post );
					
					$the_format = get_post_format( $post->ID ) ? get_post_format( $post->ID ) : 'standard';
					$the_thumb = $use_thumbs? fastfood_get_the_thumb( array( 'id' => $post->ID, 'default' => '<span class="tbm-format f-' . $the_format . '"></span>' ) ) : '';

					$output .=  '<li' . $li_class . '>' . ' <a href="' . get_permalink( $post->ID ) . '" title="' .  esc_html( $post->post_title ) . '">' . $the_thumb . $post->post_title . '</a></li>';
					$post_array[] = $comment->comment_post_ID;
					if ( ++$counter >= $number ) break;
				}
			}
 		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('tb_latest_commented_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_latest_commented_posts'] ) )
			delete_option( 'tb_latest_commented_posts' );

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$thumb = 1;
		if ( isset( $instance['thumb'] ) && !$thumb = (int) $instance['thumb'] )
			$thumb = 0;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:','fastfood' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show post thumbnails','fastfood' ); ?></label>
		</p>
<?php
	}
}


/**
 * Latest comment authors widget
 */
class Fastfood_Widget_Latest_Commentators extends WP_Widget {

	function Fastfood_Widget_Latest_Commentators() {
		$widget_ops = array( 'classname' => 'tb_latest_commentators', 'description' => __( 'The latest comment authors','fastfood' ) );
		$this->WP_Widget( 'ff-recent-commentators', __('Latest comment authors','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_latest_commentators';

		add_action( 'comment_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'transition_comment_status', array( &$this, 'flush_widget_cache' ) );
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_latest_commentators', 'widget' );
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		if ( get_option( 'require_name_email' ) != '1' ) return; //commentors must be identifiable

		$cache = wp_cache_get( 'tb_latest_commentators', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract( $args, EXTR_SKIP );
 		$output = '';
 		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Latest comment authors','fastfood') : $instance['title'] );
		$icon_size = isset( $instance['icon_size'] ) ? absint( $instance['icon_size'] ) : '32';

		if ( ! $number = (int) $instance['number'] )
 			$number = 5;
 		else if ( $number < 1 )
 			$number = 1;

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment', 'number' => 200 ) );
		$post_array = array();
		$counter = 0;
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;

		if ( $comments ) {
			foreach ( ( array ) $comments as $comment ) {
				if ( !in_array( $comment->comment_author_email, $post_array ) ) {
					if ( $comment->comment_author_url == '' ) {
						$output .=  '<span title="' .  $comment->comment_author . '">' . get_avatar( $comment, $icon_size, $default=get_option( 'avatar_default' ) ) . '</span>';
					} else {
						$output .=  '<span><a target="_blank" href="' . $comment->comment_author_url . '" title="' .  $comment->comment_author . '">' . get_avatar( $comment, $icon_size, $default=get_option( 'avatar_default' ) ) . '</a></span>';
					}
					$post_array[] = $comment->comment_author_email;
					if ( ++$counter >= $number ) break;
				}
			}
 		}
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_latest_commentators', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance["icon_size"] = in_array( $new_instance["icon_size"], array ('16', '24', '32', '40', '50', '60') ) ? $new_instance["icon_size"] : '32' ;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_latest_commentators'] ) )
			delete_option( 'tb_latest_commentators' );

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$icon_size = isset( $instance['icon_size'] ) ? absint( $instance['icon_size'] ) : '32';

		if ( get_option('require_name_email') != '1' ) {
			printf ( __( 'Comment authors <strong>must</strong> use a name and a valid e-mail in order to use this widget. Check the <a href="%1$s">Discussion settings</a>','fastfood' ), esc_url( admin_url( 'options-discussion.php' ) ) );
			return;
		}
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of users to show:','fastfood' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Select your icon size', 'fastfood' ); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'icon_size' ); ?>" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" >
<?php
			$size_array = array( '16', '24', '32', '40', '50', '60' );
			foreach( $size_array as $size ) {
?>
				<option value="<?php echo $size; ?>" <?php selected( $icon_size, $size ); ?>><?php echo $size; ?>px</option>
<?php
			}
?>
			</select>
		</p>
<?php
	}
}


/**
 * Popular Categories widget
 */
class Fastfood_Widget_Pop_Categories extends WP_Widget {

	function Fastfood_Widget_Pop_Categories() {
		$widget_ops = array( 'classname' => 'tb_categories', 'description' => __( 'A list of popular categories', 'fastfood' ) );
		$this->WP_Widget( 'ff-categories', __('Popular Categories', 'fastfood' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Popular Categories', 'fastfood' ) : $instance['title'], $instance, $this->id_base );
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
?>
		<ul>
<?php
		$cat_args = array( 'orderby' => 'count', 'hierarchical' => 0, 'order' => 'DESC', 'number' => $number );
		$categories =  get_categories( $cat_args ); 
		foreach ($categories as $category) {
			$item = '<li class="cat-item cat-item-' . $category->term_id . '">';
			$cat_name = esc_attr( $category->name );
			$cat_name = apply_filters( 'list_cats', $cat_name, $category );
			$link = '<a href="' . esc_attr( get_term_link($category) ) . '">' .  $cat_name . ' <span class="details">(' . intval($category->count) . ')</span></a>';
			$item .= $link . '</li>';
			echo $item;
		}
?>
			<li class="all_cat"><a title="<?php _e( 'View all categories', 'fastfood' ); ?>" href="<?php  echo home_url(); ?>/?allcat=y">[...]</a></li>
		</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		if ( !isset( $instance['number'] ) || !$number = (int) $instance['number'] )
			$number = 5;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of categories to show:','fastfood' ); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
<?php
	}

}


/**
 * Social network widget
 * Social media services supported: Facebook, Twitter, Myspace, Youtube, LinkedIn, Del.icio.us, Digg, Flickr, Reddit, StumbleUpon, Technorati and Github and many more.
 * Optional: RSS icon.
 */
class Fastfood_Widget_Social extends WP_Widget {
	function Fastfood_Widget_Social() {
		$widget_ops = array(
			'classname' => 'tb_social',
			'description' => __( "This widget lets visitors of your blog subscribe to it and follow you on popular social networks like Twitter, FaceBook etc.", "fastfood" ) );
		$control_ops = array('width' => 650);

		$this->WP_Widget( "ff-social", __( "Follow Me", "fastfood" ), $widget_ops, $control_ops );
		$this->follow_urls = array(
			'blogger'		=> 'Blogger',
			'blurb'			=> 'Blurb',
			'Delicious'		=> 'Delicious',
			'Deviantart'	=> 'deviantART',
			'Digg'			=> 'Digg',
			'Dropbox'		=> 'Dropbox',
			'Facebook'		=> 'Facebook',
			'Flickr'		=> 'Flickr',
			'Github'		=> 'GitHub',
			'GooglePlus'	=> 'Google+',
			'Hi5'			=> 'Hi5',
			'LinkedIn'		=> 'LinkedIn',
			'livejournal'	=> 'LiveJournal',
			'Myspace'		=> 'Myspace',
			'Odnoklassniki'	=> 'Odnoklassniki',
			'Orkut'			=> 'Orkut',
			'pengyou'		=> 'Pengyou',
			'Picasa'		=> 'Picasa',
			'pinterest'		=> 'Pinterest',
			'Qzone'			=> 'Qzone',
			'Reddit'		=> 'Reddit',
			'renren'		=> 'Renren',
			'scribd'		=> 'Scribd',
			'slideshare'	=> 'SlideShare',
			'StumbleUpon'	=> 'StumbleUpon',
			'soundcloud'	=> 'SoundCloud',
			'Technorati'	=> 'Technorati',
			'Tencent'		=> 'Tencent',
			'Twitter'		=> 'Twitter',
			'tumblr'		=> 'Tumblr',
			'ubuntuone'		=> 'Ubuntu One',
			'Vimeo'			=> 'Vimeo',
			'VKontakte'		=> 'VKontakte',
			'Sina'			=> 'Weibo',
			'WindowsLive'	=> 'Windows Live',
			'Xing'			=> 'Xing',
			'yfrog'			=> 'YFrog',
			'Youtube'		=> 'Youtube',
			'RSS'			=> 'RSS' );
	}

	function form( $instance ) {
		$defaults = array( "title" => __( "Follow Me", "fastfood" ),
			"icon_size" => '48px',
		);
		foreach ( $this->follow_urls as $follow_service => $service_name ) {
			$defaults[$follow_service."_icon"] = $follow_service;
			$defaults["show_".$follow_service] = false;
		}
		$instance = wp_parse_args( ( array ) $instance, $defaults );
?>
	<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'fastfood' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
	<div style="padding: 10px 0; border-top: 1px solid #DFDFDF;">
		<p><?php echo __( 'NOTE: Enter the <strong>full</strong> addresses ( with <em>http://</em> )', 'fastfood' ); ?></p>
<?php
		$alt = ' clear: left;';
		foreach($this->follow_urls as $follow_service => $service_name ) {
?>
		<div style="float: left; width: 40%; margin: 0pt 5%;<?php echo $alt; ?>">
			<h2>
				<input id="<?php echo $this->get_field_id( 'show_' . $follow_service ); ?>" name="<?php echo $this->get_field_name( 'show_' . $follow_service ); ?>" type="checkbox" <?php checked( $instance['show_'.$follow_service], 'on' ); ?>  class="checkbox" />
				<img style="vertical-align:middle; width:40px; height:40px;" src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo strtolower( $follow_service ); ?>.png" alt="<?php echo $follow_service; ?>" />
				<?php echo $service_name; ?>
			</h2>
<?php
			if ( $follow_service != 'RSS' ) {
				$url_or_account = $follow_service;
?>
		<p>
			<label for="<?php echo $this->get_field_id( $follow_service . '_account' ); ?>">
<?php
				printf( __( 'Enter %1$s account link:', 'fastfood' ), $service_name );
?>
			</label>
			<input id="<?php echo $this->get_field_id( $follow_service . '_account' ); ?>" name="<?php echo $this->get_field_name( $follow_service . '_account' ); ?>" value="<?php if ( isset( $instance[$follow_service . '_account'] ) ) echo $instance[$follow_service . '_account']; ?>" class="widefat" />
		</p>

<?php
			}
?>
		</div>
<?php
		$alt = ( $alt ) ? '' : ' clear: left;';
		}
?>
		<div class="clear" style="padding: 10px 0; border-top: 1px solid #DFDFDF; text-align: right;">
			<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Select your icon size', 'fastfood' ); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'icon_size' ); ?>" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" >
<?php
			$size_array = array ( '16px', '24px', '32px', '40px', '50px', '60px' );
			foreach($size_array as $size) {
?>
				<option value="<?php echo $size; ?>" <?php selected( $instance['icon_size'], $size ); ?>><?php echo $size; ?></option>
<?php
			}
?>
			</select>
		</div>
	</div>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance["title"] = strip_tags($new_instance["title"]);
		$instance["icon_size"] = $new_instance["icon_size"];

		foreach ( $this->follow_urls as $follow_service => $service_name ) {
			$instance['show_'.$follow_service] = $new_instance['show_'.$follow_service];
			$instance[$follow_service.'_account'] = $new_instance[$follow_service.'_account'];
		}

		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );
		$icon_size = ( isset( $instance['icon_size'] ) ) ? $instance['icon_size'] : '48px';
		echo $before_widget;
		if ( !empty( $title ) ) {
			echo $before_title;
			echo $title;
			echo $after_title;
		}
?>
	<div class="fix" style="text-align: center;">
<?php
		foreach ( $this->follow_urls as $follow_service => $service_name ) {
			$show = ( isset( $instance['show_'.$follow_service] ) ) ? $instance['show_'.$follow_service] : false;
			$account = ( isset( $instance[$follow_service.'_account'] ) ) ? $instance[$follow_service.'_account'] : '';
			if ( $follow_service == 'RSS' ) {
				$account = get_bloginfo( 'rss2_url' );
			}
			if ( $show && !empty( $account ) ) {
?><a href="<?php echo $account; ?>" target="_blank" class="ff-social-icon" title="<?php echo $service_name;?>"><img src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo strtolower( $follow_service );?>.png" alt="<?php echo $follow_service;?>" style='width: <?php echo $icon_size;?>; height: <?php echo $icon_size;?>;' /></a><?php
			}
		}
?>
	</div>
<?php
		echo $after_widget;
	}
}


/**
 * Makes a custom Widget for displaying Aside and Status Posts
 * Based on Twenty_Eleven_Ephemera_Widget
 */
class Fastfood_Widget_Besides extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function Fastfood_Widget_Besides() {
		$widget_ops = array( 'classname' => 'tb_besides', 'description' => __( 'Use this widget to list your recent Aside and Status posts', 'fastfood' ) );
		$this->WP_Widget( 'ff-widget-besides', __( 'besides...', 'fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_besides';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}
	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'tb_besides', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = null;

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'besides...', 'fastfood' ) : $instance['title'], $instance, $this->id_base);

		$type = ( isset( $instance['type'] ) ) ? $instance['type'] : 'aside';

		if ( ! isset( $instance['number'] ) )
			$instance['number'] = '10';

		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$besides_args = array(
			'order' => 'DESC',
			'posts_per_page' => $number,
			'no_found_rows' => true,
			'nopaging' => 0,
			'post_status' => 'publish',
			'post__not_in' => get_option( 'sticky_posts' ),
			'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'terms' => array( 'post-format-' . $type ),
					'field' => 'slug',
					'operator' => 'IN',
				),
			),
		);
		$besides = new WP_Query();
		$besides->query( $besides_args );

		if ( $besides->have_posts() ) :

		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;

		?>
		<?php while ( $besides->have_posts() ) : $besides->the_post(); ?>

			<?php if ( $type == 'aside' ) { ?>
			<div class="wentry-aside">
				<?php the_content(); ?>
				<div class="fixfloat details"><?php the_author(); ?> - <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?></div>
			</div>
			<?php } elseif ( $type == 'status' ) { ?>
			<div class="wentry-status">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 24, $default=get_option( 'avatar_default' ), get_the_author() ); ?>
				<a style="font-weight: bold;" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php printf( 'View all posts by %s', esc_attr( get_the_author() ) ); ?>"><?php echo get_the_author(); ?></a>
				<?php the_content(); ?>
				<div class="fixfloat details"><?php echo fastfood_friendly_date(); ?></div>
			</div>
			<?php } ?>

		<?php endwhile; ?>
		<?php

		echo $after_widget;

		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'tb_besides', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['type'] = in_array( $new_instance['type'], array( 'aside', 'status' ) ) ? $new_instance['type'] : 'aside';
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_besides'] ) )
			delete_option( 'tb_besides' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_besides', 'widget' );
	}

	function form( $instance ) {
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 10;
		$type = isset( $instance['type'] ) ? $instance['type'] : 'aside';
?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'fastfood' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Type of posts to show:', 'fastfood' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" >
<?php
			$type_array = array( 'aside', 'status' );
			foreach( $type_array as $avaible_type ) {
?>
				<option value="<?php echo $avaible_type; ?>" <?php selected( $type, $avaible_type ); ?>><?php echo $avaible_type; ?></option>
<?php
			}
?>
			</select></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'fastfood' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
		<?php
	}
}


/**
 * Recent Posts in Category widget
 */
class Fastfood_Widget_Recent_Posts extends WP_Widget {

	function Fastfood_Widget_Recent_Posts() {
		$widget_ops = array( 'classname' => 'tb_recent_entries', 'description' => __( "The most recent posts in a single category", 'fastfood' ) );
		$this->WP_Widget( 'ff-recent-posts', __( 'Recent Posts in Category', 'fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_recent_entries';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'tb_recent_posts', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract( $args );

		$category = isset( $instance['category'] ) ? intval( $instance['category'] ) : '';
		if ( $category === -1 ) {
			if ( !is_single() || is_attachment() ) return;
			global $post;
			$category = get_the_category( $post->ID );
			$category = ( $category ) ? $category[0]->cat_ID : '';
		}

		$use_thumbs = ( !isset($instance['thumb']) || $thumb = (int) $instance['thumb'] ) ? 1 : 0;
		$description = ( !isset($instance['description']) || $description = (int) $instance['description'] ) ? 1 : 0;
		$title = apply_filters('widget_title', empty($instance['title']) ? __( 'Recent Posts in %s', 'fastfood' ) : $instance['title'], $instance, $this->id_base);
		$title = sprintf( $title, '<a href="' . get_category_link( $category ) . '">' . get_cat_name( $category ) . '</a>' );
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query( array( 'cat' => $category, 'posts_per_page' => $number, 'no_found_rows' => true, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php if ( $description && category_description( $category ) ) echo '<div class="bz-cat-descr">' . category_description( $category ) . '</div>'; ?>
		<ul>
		<?php  while ( $r->have_posts() ) : $r->the_post(); ?>
		<li<?php if ( $use_thumbs ) echo ' class="li-with-thumbs"'; ?>>
			<?php $the_format = get_post_format( get_the_ID() ) ? get_post_format( get_the_ID() ) : 'standard'; ?>
			<?php $the_thumb = $use_thumbs? fastfood_get_the_thumb( array( 'id' => get_the_ID(), 'default' => '<span class="tbm-format f-' . $the_format . '"></span>' ) ) : ''; ?>
			<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php echo $the_thumb; if ( get_the_title() ) the_title(); else the_ID(); ?></a>
		</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'tb_recent_posts', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['category'] = (int) $new_instance['category'];
		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;
		$instance['description'] = (int) $new_instance['description'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_recent_entries'] ) )
			delete_option( 'tb_recent_entries' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_recent_posts', 'widget' );
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Recent Posts in %s', 'fastfood' );
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$category = isset( $instance['category'] ) ? intval( $instance['category'] ) : '';
		$thumb = isset( $instance['thumb'] ) ? absint( $instance['thumb'] ) : 1;
		$description = isset( $instance['description'] ) ? absint( $instance['description'] ) : 1;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			<small><?php echo __( 'use <code>%s</code> as a placeholder for the category name', 'fastfood' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category', 'fastfood' ); ?></label>
			<?php $dropdown_categories = wp_dropdown_categories( Array(
						'orderby'			=> 'ID',
						'order'			  => 'ASC',
						'show_count'		 => 1,
						'hide_empty'		 => 0,
						'hide_if_empty'	  => true,
						'echo'			   => 0,
						'selected'		   => $category,
						'hierarchical'	   => 1,
						'name'			   => $this->get_field_name('category'),
						'id'				 => $this->get_field_id('category'),
						'class'			  => 'widefat',
						'taxonomy'		   => 'category',
					) ); ?>

			<?php
			echo str_replace( '</select>', '<option ' . selected( $category , -1 , 0 ) . 'value="-1" class="level-0">' . __( '(current post category)', 'fastfood' ) . '</option></select>', $dropdown_categories );
			?>
			<small><?php echo __( 'by selecting "(current post category)", the widget will be visible ONLY in single posts', 'fastfood' ); ?></small>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" value="1" type="checkbox" <?php checked( 1 , $description ); ?> />
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Show category description','fastfood' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'fastfood' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show post thumbnails','fastfood' ); ?></label>
		</p>

<?php
	}
}


/**
 * Image EXIF widget
 */
class Fastfood_Widget_Image_Exif extends WP_Widget {

	function Fastfood_Widget_Image_Exif() {
		$widget_ops = array( 'classname' => 'tb_exif_details', 'description' => __( "Display image details. It's visible ONLY in single attachments",'fastfood' ) );
		$this->WP_Widget( 'ff-exif-details', __( 'Image details','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_exif_details';

	}

	function widget($args, $instance) {
		if ( !is_attachment() || !wp_attachment_is_image() ) return;
		extract($args);

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php fastfood_exif_details(); ?>
		<?php echo $after_widget; ?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : __( 'Image details','fastfood' );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

<?php
	}
}


/**
 * Post share links
 */
class Fastfood_Widget_Share_This extends WP_Widget {

	function Fastfood_Widget_Share_This() {
		$widget_ops = array( 'classname' => 'tb_share_this', 'description' => __( "Show some popular sharing services links. It's visible ONLY in single posts, pages and attachments",'fastfood' ) );
		$this->WP_Widget( 'ff-share-this', __( 'Share this','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_share_this';

	}

	function widget( $args, $instance ) {
		if ( !is_singular() ) return;
		extract( $args );

		$icon_size = isset( $instance['icon_size'] ) ? absint( $instance['icon_size'] ) : '16';

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php fastfood_share_this( array( 'size' => $icon_size ) ); ?>
		<?php echo $after_widget; ?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance["icon_size"] = in_array( $new_instance["icon_size"], array ( '16', '24', '32', '48', '64' ) ) ? $new_instance["icon_size"] : '16' ;

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Share this','fastfood' );
		$icon_size = isset( $instance['icon_size'] ) ? absint( $instance['icon_size'] ) : '16';
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Select icon size', 'fastfood' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'icon_size' ); ?>" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" >
<?php
			$size_array = array ( '16', '24', '32', '48', '64' );
			foreach($size_array as $size) {
?>
				<option value="<?php echo $size; ?>" <?php selected( $icon_size, $size ); ?>><?php echo $size; ?>px</option>
<?php
			}
?>
			</select>
		</p>

<?php
	}
}


/**
 * Clean Archives Widget
 */
class Fastfood_Widget_Clean_Archives extends WP_Widget {

	function Fastfood_Widget_Clean_Archives() {
		$widget_ops = array( 'classname' => 'tb_clean_archives', 'description' => __( "Show archives in a cleaner way",'fastfood' ) );
		$this->WP_Widget( 'ff-clean-archives', __( 'Clean Archives','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_clean_archives';

	}

	function widget($args, $instance) {
		extract($args);

		global $wpdb; // Wordpress Database

		$years = $wpdb->get_results( "SELECT distinct year(post_date) AS year, count(ID) as posts FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' GROUP BY year(post_date) ORDER BY post_date DESC" );

		if ( empty( $years ) ) {
			return; // empty archive
		}

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$month_style = ( isset( $instance['month_style'] ) && in_array( $instance['month_style'], array ('number', 'acronym') ) ) ? $instance['month_style'] : 'number';
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php
			if ( $month_style == 'acronym' )
				$months_short = array( '', __( 'jan','fastfood' ), __( 'feb','fastfood' ), __( 'mar','fastfood' ), __( 'apr','fastfood' ), __( 'may','fastfood' ), __( 'jun','fastfood' ), __( 'jul','fastfood' ), __( 'aug','fastfood' ), __( 'sep','fastfood' ), __( 'oct','fastfood' ), __( 'nov','fastfood' ), __( 'dec','fastfood' ) );
			else
				$months_short = array( '', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );

		?>
		<ul class="ff-clean-archives">
		<?php foreach ( $years as $year ) {
			echo '<li><a class="year-link" href="' . get_year_link( $year->year ) . '">' . $year->year . '</a> ';

			for ( $month = 1; $month <= 12; $month++ ) {
				if ( (int) $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND year(post_date) = '$year->year' AND month(post_date) = '$month'" ) > 0 ) {
					echo '<a class="month-link" href="' . get_month_link( $year->year, $month ) . '">' . $months_short[$month] . '</a>';
				}

				if ( $month != 12 ) {
					echo ' ';
				}
			}

			echo '</li>';
		} ?>

		</ul>
		<?php echo $after_widget; ?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance["month_style"] = in_array( $new_instance["month_style"], array ( 'number', 'acronym' ) ) ? $new_instance["month_style"] : 'number' ;

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Archives','fastfood' );
		$month_style = isset( $instance['month_style'] ) ? esc_attr( $instance['month_style'] ) : 'number';
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'month_style' ); ?>"><?php _e( 'Select month style', 'fastfood' ); ?></label>
			<select name="<?php echo $this->get_field_name('month_style'); ?>" id="<?php echo $this->get_field_id( 'month_style' ); ?>" >
				<option value="number" <?php selected( $month_style, 'number' ); ?>>number</option>
				<option value="acronym" <?php selected( $month_style, 'acronym' ); ?>>acronym</option>
			</select>
		</p>
<?php
	}
}


/**
 * Post details widget class
 */
class Fastfood_Widget_Post_Details extends WP_Widget {

	function Fastfood_Widget_Post_Details() {
		$widget_ops = array( 'classname' => 'tb_post_details', 'description' => __( "Show some details and links related to the current post. It's visible ONLY in single posts",'fastfood' ) );
		$this->WP_Widget( 'ff-post-details', __( 'Post details','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_post_details';

	}

	function widget($args, $instance) {
		if ( !is_single() || is_attachment() ) return;
		extract($args);

		$avatar_size = isset($instance['avatar_size']) ? absint( $instance['avatar_size'] ) : '48';

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		fastfood_post_details( array( 'author' => $instance['author'], 'date' => $instance['date'], 'tags' => $instance['tags'], 'categories' => $instance['categories'], 'avatar_size' => $avatar_size, 'featured' => $instance['featured'] ) );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['featured'] = (int) $new_instance['featured'] ? 1 : 0;
		$instance['author'] = (int) $new_instance['author'] ? 1 : 0;
		$instance['avatar_size'] = in_array( $new_instance['avatar_size'], array ('32', '48', '64', '96', '128') ) ? $new_instance['avatar_size'] : '48' ;
		$instance['date'] = (int) $new_instance['date'] ? 1 : 0;
		$instance['tags'] = (int) $new_instance['tags'] ? 1 : 0;
		$instance['categories'] = (int) $new_instance['categories'] ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Post details','fastfood' );
		$featured = isset( $instance['featured'] ) ? absint( $instance['featured'] ) : 0;
		$author = isset( $instance['author'] ) ? absint( $instance['author'] ) : 1;
		$avatar_size = isset( $instance['avatar_size'] ) ? absint( $instance['avatar_size'] ) : '48';
		$date = isset( $instance['date'] ) ? absint( $instance['date'] ) : 1;
		$tags = isset( $instance['tags'] ) ? absint( $instance['tags'] ) : 1;
		$categories = isset( $instance['categories'] ) ? absint( $instance['categories'] ) : 1;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'featured' ); ?>" name="<?php echo $this->get_field_name( 'featured' ); ?>" value="1" type="checkbox" <?php checked( 1 , $featured ); ?> />
			<label for="<?php echo $this->get_field_id( 'featured' ); ?>"><?php _e( 'thumbnail','fastfood' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name('author'); ?>" value="1" type="checkbox" <?php checked( 1 , $author ); ?> />
			<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Author','fastfood' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Select avatar size', 'fastfood' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" >
<?php
			$size_array = array ( '32', '48', '64', '96', '128' );
			foreach( $size_array as $size ) {
?>
				<option value="<?php echo $size; ?>" <?php selected( $avatar_size, $size ); ?>><?php echo $size; ?>px</option>
<?php
			}
?>
			</select>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" value="1" type="checkbox" <?php checked( 1 , $date ); ?> />
			<label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Date','fastfood' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" value="1" type="checkbox" <?php checked( 1 , $tags ); ?> />
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Tags','fastfood' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" value="1" type="checkbox" <?php checked( 1 , $categories ); ?> />
			<label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories','fastfood' ); ?></label>
		</p>
<?php
	}
}

/**
 * simple font resize widget
 */
function fastfood_widget_font_resize($args) {
	extract($args);
	echo $before_widget;
	echo '<a class="fontresizer-minus" href="javascript:void(0)" title="' . esc_attr( __('Decrease font size','fastfood') ) . '">-</a> ';
	echo '<a class="fontresizer-reset" href="javascript:void(0)" title="' . esc_attr( __('Reset font size','fastfood') ) . '">A</a> ';
	echo '<a class="fontresizer-plus" href="javascript:void(0)" title="' . esc_attr( __('Increase font size','fastfood') ) . '">+</a> ';
	echo $after_widget;
	wp_enqueue_script( 'fastfood-fontresize', get_template_directory_uri() . '/js/font-resize.min.js', array( 'jquery' ), '', true  );

}

/**
 * Register all of the default WordPress widgets on startup.
 */
function fastfood_widgets_init() {

	if ( !is_blog_installed() )
		return;

	if ( ! fastfood_get_opt( 'fastfood_custom_widgets' ) )
		return;

	register_widget( 'Fastfood_Widget_Popular_Posts' );

	register_widget( 'Fastfood_Widget_Latest_Commented_Posts' );

	register_widget( 'Fastfood_Widget_Latest_Commentators' );

	register_widget( 'Fastfood_Widget_Pop_Categories' );

	register_widget( 'Fastfood_Widget_Social' );

	register_widget( 'Fastfood_Widget_Besides' );

	register_widget( 'Fastfood_Widget_Recent_Posts' );

	register_widget( 'Fastfood_Widget_Image_Exif' );

	register_widget( 'Fastfood_Widget_Share_This' );

	register_widget( 'Fastfood_Widget_Clean_Archives' );

	register_widget( 'Fastfood_Widget_Post_Details' );

	if ( fastfood_is_mobile() )
		return;

	wp_register_sidebar_widget('ff-font-resize', 'Font Resize', 'fastfood_widget_font_resize', array( 'classname' => 'tb_font_resize', 'description' => 'Simple javascript-based font resizer' ) );
}

