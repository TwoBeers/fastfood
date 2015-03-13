<?php
/**
 * widgets.php
 *
 * Fastfood Widgets
 *
 * @package fastfood
 * @since fastfood 0.27
 */


add_action( 'widgets_init', 'fastfood_widgets_init' );


/**
 * Popular_Posts widget class
 */
class Fastfood_Widget_Popular_Posts extends WP_Widget {

	function Fastfood_Widget_Popular_Posts() {
		$widget_ops = array(
			'classname' => 'tb_popular_posts',
			'description' => __( 'The most commented posts on your site', 'fastfood' )
		);
		$this->WP_Widget('ff-popular-posts', __('Popular Posts','fastfood'), $widget_ops);
		$this->alt_option_name = 'tb_popular_posts';

		add_action( 'save_post'		, array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title'		=> __( 'Popular Posts','fastfood' ),
			'number'	=> 5,
			'thumb'		=> 0,
		);
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

		$instance = $this->validate_and_sanitize( $instance );

		extract($args);
		extract($instance);

		$r = new WP_Query( array(
			'showposts' => $number,
			'no_found_rows' => true,
			'nopaging' => 0,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby' => 'comment_count'
		) );

		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title; ?>
		<ul>
		<?php
			while ( $r->have_posts() ) :
				$r->the_post();
				$the_id		= get_the_ID();
				$the_title	= get_the_title() ? get_the_title() : $the_id;
				$the_class	= $thumb ? ' class="li-with-thumbs"' : '';
				$the_format	= get_post_format() ? get_post_format() : 'standard';
				$the_thumb	= $thumb? fastfood_get_the_thumb( array( 'id' => $the_id, 'size' => array( 32, 32 ), 'default' => '<span class="tbm-format f-' . $the_format . '"></span>' ) ) : '';

				echo sprintf( '<li%1$s><a href="%2$s" title="%3$s">%4$s%5$s</a></li>',
					$the_class,
					esc_url( get_permalink() ),
					esc_attr( $the_title ),
					$the_thumb,
					esc_html( $the_title )
				) . "\n";
			endwhile;
		?>
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
		$instance = $this->validate_and_sanitize( $new_instance, true );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_popular_posts'] ) )
			delete_option( 'tb_popular_posts' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {

		//we first need to trap unselected checkboxes value
		if ( $is_update ) {
			$instance['thumb']			= ( isset( $instance['thumb'] ) && ( $instance['thumb'] != 0 ) ) ? 1 : 0;
		}

		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']		= esc_html( empty( $instance['title'] ) ? $this->defaults['title'] : $instance['title'] );
		$instance['number']		= in_array( absint( $instance['number'] ), range( 1, 10 ) ) ? absint( $instance['number'] ) : $this->defaults['number'];

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('tb_popular_posts', 'widget');
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
		extract($instance);
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
		$widget_ops = array(
			'classname' => 'tb_latest_commented_posts',
			'description' => __( 'The latest commented posts/pages of your site', 'fastfood' )
		);
		$this->WP_Widget( 'ff-recent-comments', __( 'Latest activity','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_latest_commented_posts';

		add_action( 'comment_post'				, array( &$this, 'flush_widget_cache' ) );
		add_action( 'transition_comment_status'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title'		=> __( 'Latest activity','fastfood' ),
			'number'	=> 5,
			'thumb'		=> 0,
		);
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_latest_commented_posts', 'widget' );
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get( 'tb_latest_commented_posts', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$instance = $this->validate_and_sanitize( $instance );

 		extract( $args, EXTR_SKIP );
 		extract( $instance );

 		$output = '';

		$comments = get_comments( array(
			'status' => 'approve',
			'type' => 'comment',
			'number' => 200
		) );
		$post_array = array();
		$counter = 0;
		$output .= $before_widget;
		$output .= $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;

		$output .= '<ul>';
		if ( $comments ) {
			foreach ( ( array ) $comments as $comment) {
				if ( !in_array( $comment->comment_post_ID, $post_array ) ) {
					$post = get_post( $comment->comment_post_ID );
					setup_postdata( $post );

					$the_id		= $post->ID;
					$the_title	= get_the_title( $the_id ) ? get_the_title( $the_id ) : $the_id;
					$the_class	= $thumb ? ' class="li-with-thumbs"' : '';
					$the_format	= get_post_format( $the_id ) ? get_post_format( $the_id ) : 'standard';
					$the_thumb	= $thumb? fastfood_get_the_thumb( array( 'id' => $the_id, 'size' => array( 32, 32 ), 'default' => '<span class="tbm-format f-' . $the_format . '"></span>' ) ) : '';

					$output .= sprintf( '<li%1$s><a href="%2$s" title="%3$s">%4$s%5$s</a></li>',
						$the_class,
						esc_url( get_permalink( $the_id ) ),
						esc_attr( $the_title ),
						$the_thumb,
						esc_html( $the_title )
					) . "\n";

					$post_array[] = $comment->comment_post_ID;
					if ( ++$counter >= $number ) break;
				}
			}
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('tb_latest_commented_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $this->validate_and_sanitize( $new_instance, true );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_latest_commented_posts'] ) )
			delete_option( 'tb_latest_commented_posts' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {

		//we first need to trap unselected checkboxes value
		if ( $is_update ) {
			$instance['thumb']			= ( isset( $instance['thumb'] ) && ( $instance['thumb'] != 0 ) ) ? 1 : 0;
		}

		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']		= esc_html( empty( $instance['title'] ) ? $this->defaults['title'] : $instance['title'] );
		$instance['number']		= in_array( absint( $instance['number'] ), range( 1, 10 ) ) ? absint( $instance['number'] ) : $this->defaults['number'];

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );
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

		$this->defaults = array(
			'title'			=> __( 'Latest comment authors','fastfood'),
			'number'		=> 5,
			'icon_size'		=> 32,
		);
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_latest_commentators', 'widget' );
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		if ( get_option( 'require_name_email' ) != '1' ) return; //commentors must be identifiable

		$cache = wp_cache_get( 'tb_latest_commentators', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args, EXTR_SKIP );
		extract( $instance );

		$output = '';

		$comments = get_comments( array(
			'status' => 'approve',
			'type' => 'comment',
			'number' => 200
		) );
		$comment_author_array = array();
		$counter = 0;
		$output .= $before_widget;
		$output .= $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;

		if ( $comments ) {
			foreach ( ( array ) $comments as $comment ) {
				if( post_password_required( $comment->comment_post_ID ) ) continue;
				if ( !in_array( $comment->comment_author_email, $comment_author_array ) ) {
					if ( $comment->comment_author_url == '' )
						$format = '<span title="%1$s">%3$s</span>';
					else
						$format = '<span><a title="%1$s" target="_blank" href="%2$s">%3$s</a></span>';
					$output .= sprintf( $format,
						esc_attr( $comment->comment_author ),
						esc_url( $comment->comment_author_url ),
						get_avatar( $comment, $icon_size, $default = get_option( 'avatar_default' ) )
					) . "\n";
					$comment_author_array[] = $comment->comment_author_email;
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
		$instance = $this->validate_and_sanitize( $new_instance, true );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_latest_commentators'] ) )
			delete_option( 'tb_latest_commentators' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']		= esc_html( empty( $instance['title'] ) ? $this->defaults['title'] : $instance['title'] );
		$instance['number']		= in_array( absint( $instance['number'] ), range( 1, 10 ) ) ? absint( $instance['number'] ) : $this->defaults['number'];
		$instance['icon_size']	= in_array( absint( $instance['icon_size'] ), array ( 16, 24, 32, 40, 50, 60 ) ) ? absint( $instance['icon_size'] ) : $this->defaults['icon_size'];

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );

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
					foreach( array ( 16, 24, 32, 40, 50, 60 ) as $size ) {
						echo sprintf( '<option value="%1$s" %2$s>%1$spx</option>',
							$size,
							selected( $icon_size, $size, false )
						) . "\n";
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
		$widget_ops = array(
			'classname' => 'tb_categories',
			'description' => __( 'A list of popular categories', 'fastfood' )
		);
		$this->WP_Widget( 'ff-categories', __('Popular Categories', 'fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_categories';

		add_action( 'save_post'		, array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title'		=> __( 'Popular Categories', 'fastfood' ),
			'number'	=> 5,
		);
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_categories', 'widget' );
	}

	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'tb_categories', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args );
 		extract( $instance );

 		$output = '';

		$categories = get_categories( array(
			'orderby' => 'count',
			'hierarchical' => 0,
			'order' => 'DESC',
			'number' => $number
		) ); 
		$output .= $before_widget;
		$output .= $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;

		$output .= '<ul>';
		foreach ( $categories as $category ) {
			$output .= sprintf( '<li class="cat-item cat-item-%1$s"><a href="%2$s">%3$s <span class="details">(%4$s)</span></a></li>',
				esc_attr( $category->term_id ),
				esc_url( get_term_link( $category ) ),
				esc_attr( apply_filters( 'list_cats', $category->name, $category ) ),
				intval($category->count)
			);
		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('tb_categories', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $this->validate_and_sanitize( $new_instance, true );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_categories'] ) )
			delete_option( 'tb_categories' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']		= esc_html( empty( $instance['title'] ) ? $this->defaults['title'] : $instance['title'] );
		$instance['number']		= in_array( absint( $instance['number'] ), range( 1, 10 ) ) ? absint( $instance['number'] ) : $this->defaults['number'];

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );
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

		$icons_path = get_template_directory_uri() . '/images/follow/';
		$this->follow_urls = apply_filters( 'fastfood_follow_services', array(
			'blogger'		=> array(
				'name' => 'Blogger',
				'icon' => $icons_path . 'blogger.png' ),
			'blurb'			=> array(
				'name' => 'Blurb',
				'icon' => $icons_path . 'blurb.png' ),
			'Delicious'		=> array(
				'name' => 'Delicious',
				'icon' => $icons_path . 'delicious.png' ),
			'Deviantart'	=> array(
				'name' => 'deviantART',
				'icon' => $icons_path . 'deviantart.png' ),
			'Digg'			=> array(
				'name' => 'Digg',
				'icon' => $icons_path . 'digg.png' ),
			'Dropbox'		=> array(
				'name' => 'Dropbox',
				'icon' => $icons_path . 'dropbox.png' ),
			'Facebook'		=> array(
				'name' => 'Facebook',
				'icon' => $icons_path . 'facebook.png' ),
			'Flickr'		=> array(
				'name' => 'Flickr',
				'icon' => $icons_path . 'flickr.png' ),
			'Github'		=> array(
				'name' => 'GitHub',
				'icon' => $icons_path . 'github.png' ),
			'GooglePlus'	=> array(
				'name' => 'Google+',
				'icon' => $icons_path . 'googleplus.png' ),
			'Hi5'			=> array(
				'name' => 'Hi5',
				'icon' => $icons_path . 'hi5.png' ),
			'instagram'		=> array(
				'name' => 'Instagram',
				'icon' => $icons_path . 'instagram.png' ),
			'LinkedIn'		=> array(
				'name' => 'LinkedIn',
				'icon' => $icons_path . 'linkedin.png' ),
			'livejournal'	=> array(
				'name' => 'LiveJournal',
				'icon' => $icons_path . 'livejournal.png' ),
			'Myspace'		=> array(
				'name' => 'Myspace',
				'icon' => $icons_path . 'myspace.png' ),
			'Odnoklassniki'	=> array(
				'name' => 'Odnoklassniki',
				'icon' => $icons_path . 'odnoklassniki.png' ),
			'Orkut'			=> array(
				'name' => 'Orkut',
				'icon' => $icons_path . 'orkut.png' ),
			'pengyou'		=> array(
				'name' => 'Pengyou',
				'icon' => $icons_path . 'pengyou.png' ),
			'Picasa'		=> array(
				'name' => 'Picasa',
				'icon' => $icons_path . 'picasa.png' ),
			'pinterest'		=> array(
				'name' => 'Pinterest',
				'icon' => $icons_path . 'pinterest.png' ),
			'Qzone'			=> array(
				'name' => 'Qzone',
				'icon' => $icons_path . 'qzone.png' ),
			'Reddit'		=> array(
				'name' => 'Reddit',
				'icon' => $icons_path . 'reddit.png' ),
			'renren'		=> array(
				'name' => 'Renren',
				'icon' => $icons_path . 'renren.png' ),
			'scribd'		=> array(
				'name' => 'Scribd',
				'icon' => $icons_path . 'scribd.png' ),
			'slideshare'	=> array(
				'name' => 'SlideShare',
				'icon' => $icons_path . 'slideshare.png' ),
			'StumbleUpon'	=> array(
				'name' => 'StumbleUpon',
				'icon' => $icons_path . 'stumbleupon.png' ),
			'soundcloud'	=> array(
				'name' => 'SoundCloud',
				'icon' => $icons_path . 'soundcloud.png' ),
			'Technorati'	=> array(
				'name' => 'Technorati',
				'icon' => $icons_path . 'technorati.png' ),
			'Tencent'		=> array(
				'name' => 'Tencent',
				'icon' => $icons_path . 'tencent.png' ),
			'Twitter'		=> array(
				'name' => 'Twitter',
				'icon' => $icons_path . 'twitter.png' ),
			'tumblr'		=> array(
				'name' => 'Tumblr',
				'icon' => $icons_path . 'tumblr.png' ),
			'Vimeo'			=> array(
				'name' => 'Vimeo',
				'icon' => $icons_path . 'vimeo.png' ),
			'VKontakte'		=> array(
				'name' => 'VKontakte',
				'icon' => $icons_path . 'vkontakte.png' ),
			'Sina'			=> array(
				'name' => 'Weibo',
				'icon' => $icons_path . 'sina.png' ),
			'WindowsLive'	=> array(
				'name' => 'Windows Live',
				'icon' => $icons_path . 'windowslive.png' ),
			'Xing'			=> array(
				'name' => 'Xing',
				'icon' => $icons_path . 'xing.png' ),
			'yfrog'			=> array(
				'name' => 'YFrog',
				'icon' => $icons_path . 'yfrog.png' ),
			'Youtube'		=> array(
				'name' => 'Youtube',
				'icon' => $icons_path . 'youtube.png' ),
			'RSS'			=> array(
				'name' => 'RSS',
				'icon' => $icons_path . 'rss.png' ),
			) );

/**
 * Customize the services by adding something like this to your child theme/plugin:

	add_filter( 'fastdood_follow_services', 'my_custom_follow_services' );

	function my_custom_follow_services( $services ) {

		$services['my_cool_service_slug'] = array(
			'name' => 'My Cool Service',
			'icon' => PATH_TO_IMAGE,
		);

		return $services;

	}

 * PATH_TO_IMAGE may be:
 * get_stylesheet_directory_uri() . '/my_cool_service_icon.png' (for child theme)
 * plugins_url( 'my_cool_service_icon.png' , __FILE__ ) (for plugin)

 */
	}

	function form( $instance ) {
		$defaults = array( "title" => __( "Follow Me", "fastfood" ),
			"icon_size" => '48px',
		);
		foreach ( $this->follow_urls as $follow_service => $service_data ) {
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
		foreach($this->follow_urls as $follow_service => $service_data ) {
?>
		<div style="float: left; width: 40%; margin: 0pt 5%;<?php echo $alt; ?>">
			<h2>
				<input id="<?php echo $this->get_field_id( 'show_' . $follow_service ); ?>" name="<?php echo $this->get_field_name( 'show_' . $follow_service ); ?>" type="checkbox" <?php checked( $instance['show_'.$follow_service], 'on' ); ?>  class="checkbox" />
				<img style="vertical-align:middle; width:40px; height:40px;" src="<?php echo esc_url( $service_data['icon'] ) ?>" alt="<?php echo $follow_service; ?>" />
				<?php echo $service_data['name']; ?>
			</h2>
<?php
			if ( $follow_service != 'RSS' ) {
				$url_or_account = $follow_service;
?>
		<p>
			<label for="<?php echo $this->get_field_id( $follow_service . '_account' ); ?>">
<?php
				printf( __( 'Enter %1$s account link:', 'fastfood' ), $service_data['name'] );
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

		foreach ( $this->follow_urls as $follow_service => $service_data ) {
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
		foreach ( $this->follow_urls as $follow_service => $service_data ) {
			$show = ( isset( $instance['show_'.$follow_service] ) ) ? $instance['show_'.$follow_service] : false;
			$account = ( isset( $instance[$follow_service.'_account'] ) ) ? $instance[$follow_service.'_account'] : '';
			if ( $follow_service == 'RSS' ) {
				$account = get_bloginfo( 'rss2_url' );
			}
			if ( $show && !empty( $account ) ) {
				echo sprintf( '<a href="%1$s" target="_blank" class="ff-social-icon" title="%2$s"><img src="%3$s" alt="%4$s" style="width: %5$s; height: %6$s;" /></a>',
					esc_url( $account ),
					esc_attr( $service_data['name'] ),
					esc_url( $service_data['icon'] ),
					esc_attr( $follow_service ),
					esc_attr( $icon_size ),
					esc_attr( $icon_size )
				);
			}
		}
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

		add_action( 'save_post'		, array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title'		=> __( 'besides...', 'fastfood' ),
			'number'	=> 5,
			'type'		=> 'aside',
		);
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_besides', 'widget' );
	}

	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'tb_besides', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( !isset( $args['widget_id'] ) )
			$args['widget_id'] = null;

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args, EXTR_SKIP );
 		extract( $instance );

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
		if ( $title ) echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;

		?>
		<?php while ( $besides->have_posts() ) : $besides->the_post(); ?>

			<?php if ( $type == 'aside' ) { ?>
			<div class="wentry-aside">
				<?php the_content(); ?>
				<div class="fixfloat details"><?php the_author(); ?> - <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?></div>
			</div>
			<?php } elseif ( $type == 'status' ) { ?>
			<div class="wentry-status">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 24, $default = get_option( 'avatar_default' ), get_the_author() ); ?>
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
		$instance = $this->validate_and_sanitize( $new_instance, true );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_besides'] ) )
			delete_option( 'tb_besides' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']		= esc_html( $instance['title'] );
		$instance['number']		= in_array( absint( $instance['number'] ), range( 1, 10 ) ) ? absint( $instance['number'] ) : $this->defaults['number'];
		$instance['type']		= in_array( $instance['type'], array( 'aside', 'status' ) ) ? $instance['type'] : $this->defaults['type'];

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );
?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'fastfood' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Type of posts to show:', 'fastfood' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" >
				<?php
					foreach( array( 'aside', 'status' ) as $avaible_type ) {
						echo sprintf( '<option value="%1$s" %2$s>%1$s</option>',
							$avaible_type,
							selected( $type, $avaible_type, false )
						) . "\n";
					}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'fastfood' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" />
		</p>
<?php
	}
}


/**
 * Recent Posts in Category widget
 */
class Fastfood_Widget_Recent_Posts extends WP_Widget {

	function Fastfood_Widget_Recent_Posts() {
		$widget_ops = array(
			'classname' => 'tb_recent_entries',
			'description' => __( "The most recent posts in a single category", 'fastfood' )
		);
		$this->WP_Widget( 'ff-recent-posts', __( 'Recent Posts in Category', 'fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_recent_entries';

		add_action( 'save_post'		, array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Recent Posts in %s', 'fastfood' ),
			'number' => 5,
			'thumb' => 1,
			'category' => -1,
			'description' => 1,
		);
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

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args );
		extract( $instance );

		if ( $category === -1 ) {
			if ( !is_single() || is_attachment() ) return;
			global $post;
			$category = get_the_category( $post->ID );
			$category = ( $category ) ? $category[0]->cat_ID : '';
		}

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$title = sprintf( $title, '<a href="' . get_category_link( $category ) . '">' . get_cat_name( $category ) . '</a>' );

		$r = new WP_Query( array(
			'cat' => $category,
			'posts_per_page' => $number,
			'no_found_rows' => true,
			'nopaging' => 0,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true
		) );

		if ($r->have_posts()) :
?>

		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php if ( $description && category_description( $category ) ) echo '<div class="description">' . category_description( $category ) . '</div>'; ?>
		<ul>
		<?php
			while ( $r->have_posts() ) :
				$r->the_post();
				$the_id		= get_the_ID();
				$the_title	= get_the_title() ? get_the_title() : $the_id;
				$the_class	= $thumb ? ' class="li-with-thumbs"' : '';
				$the_format	= get_post_format() ? get_post_format() : 'standard';
				$the_thumb	= $thumb? fastfood_get_the_thumb( array( 'id' => $the_id, 'size' => array( 32, 32 ), 'default' => '<span class="tbm-format f-' . $the_format . '"></span>' ) ) : '';

				echo sprintf( '<li%1$s><a href="%2$s" title="%3$s">%4$s%5$s</a></li>',
					$the_class,
					esc_url( get_permalink() ),
					esc_attr( $the_title ),
					$the_thumb,
					esc_html( $the_title )
				) . "\n";
			endwhile;
		?>
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
		$instance = $this->validate_and_sanitize( $new_instance, true );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_recent_entries'] ) )
			delete_option( 'tb_recent_entries' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {

		//we first need to trap unselected checkboxes value
		if ( $is_update ) {
			$instance['thumb']			= ( isset( $instance['thumb'] ) && ( $instance['thumb'] != 0 ) ) ? 1 : 0;
			$instance['description']	= ( isset( $instance['description'] ) && ( $instance['description'] != 0 ) ) ? 1 : 0;
		}

		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']			= esc_html( empty( $instance['title'] ) ? $this->defaults['title'] : $instance['title'] );
		$instance['number']			= in_array( absint( $instance['number'] ), range( 1, 10 ) ) ? absint( $instance['number'] ) : $this->defaults['number'];
		$instance['category']		= (int) $instance['category'] ? (int) $instance['category'] : $this->defaults['category'];

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_recent_posts', 'widget' );
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );

		extract( $instance );
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
		$widget_ops = array(
			'classname' => 'tb_exif_details',
			'description' => __( "Display image details. It's visible ONLY in single attachments",'fastfood' )
		);
		$this->WP_Widget( 'ff-exif-details', __( 'Image details','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_exif_details';

		$this->defaults = array(
			'title'		=> __( 'Image details','fastfood' ),
		);
	}

	function widget($args, $instance) {
		if ( !is_attachment() || !wp_attachment_is_image() ) return;

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args );
 		extract( $instance );

?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title; ?>
		<?php
			foreach( fastfood_exif_details() as $image_meta ) {
				echo sprintf( '%1$s: %2$s',
					$image_meta['label'],
					$image_meta['readable']
				) . '<br />';
			}
		?>
		<?php echo $after_widget; ?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $this->validate_and_sanitize( $new_instance, true );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title'] = esc_html( $instance['title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

<?php
	}

}


/**
 * Clean Archives Widget
 */
class Fastfood_Widget_Clean_Archives extends WP_Widget {

	function Fastfood_Widget_Clean_Archives() {
		$widget_ops = array(
			'classname' => 'tb_clean_archives',
			'description' => __( "Show archives in a cleaner way",'fastfood' )
		);
		$this->WP_Widget( 'ff-clean-archives', __( 'Clean Archives','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_clean_archives';

		add_action( 'save_post'		, array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title'			=> __( 'Archives','fastfood' ),
			'month_style'	=> 'number',
		);

	}

	function flush_widget_cache() {
		wp_cache_delete( 'tb_clean_archives', 'widget' );
	}

	function widget($args, $instance) {
		global $wpdb; // Wordpress Database

		$cache = wp_cache_get( 'tb_clean_archives', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args, EXTR_SKIP );
		extract( $instance );

		$output = '';

		$years = $wpdb->get_results( "SELECT distinct year(post_date) AS year, count(ID) as posts FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' GROUP BY year(post_date) ORDER BY post_date DESC" );

		if ( empty( $years ) ) {
			return; // empty archive
		}

		$output .= $before_widget;
		if ( $title ) $output .= $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;

		if ( $month_style == 'acronym' )
			$months_short = array( '', __( 'jan','fastfood' ), __( 'feb','fastfood' ), __( 'mar','fastfood' ), __( 'apr','fastfood' ), __( 'may','fastfood' ), __( 'jun','fastfood' ), __( 'jul','fastfood' ), __( 'aug','fastfood' ), __( 'sep','fastfood' ), __( 'oct','fastfood' ), __( 'nov','fastfood' ), __( 'dec','fastfood' ) );
		else
			$months_short = array( '', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );

		$output .= '<ul class="ff-clean-archives">';

		foreach ( $years as $year ) {
			$output .= '<li><a class="year-link" href="' . esc_url( get_year_link( $year->year ) ) . '">' . esc_html( $year->year ) . '</a>';

			for ( $month = 1; $month <= 12; $month++ ) {
				if ( (int) $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND year(post_date) = '$year->year' AND month(post_date) = '$month'" ) > 0 ) {
					$output .= ' <a class="month-link" href="' . esc_url( get_month_link( $year->year, $month ) ) . '">' . esc_html( $months_short[$month] ) . '</a>';
				}
			}

			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_clean_archives', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $this->validate_and_sanitize( $new_instance, true );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['tb_clean_archives'] ) )
			delete_option( 'tb_clean_archives' );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']			= esc_html( $instance['title'] );
		$instance['month_style']	= in_array( $instance['month_style'], array( 'number', 'acronym' ) ) ? $instance['month_style'] : $this->defaults['month_style'];

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );
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
		$widget_ops = array(
			'classname' => 'tb_post_details',
			'description' => __( "Show some details and links related to the current post. It's visible ONLY in single posts",'fastfood' )
		);
		$this->WP_Widget( 'ff-post-details', __( 'Post details','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_post_details';

		$this->defaults = array(
			'title'			=> __( 'Post details','fastfood' ),
			'featured'		=> 0,
			'author'		=> 1,
			'avatar_size'	=> 48,
			'date'			=> 1,
			'tags'			=> 1,
			'categories'	=> 1,
		);

	}

	function widget($args, $instance) {
		if ( !is_single() || is_attachment() ) return;

		$instance = $this->validate_and_sanitize( $instance );

		extract( $args, EXTR_SKIP );
		extract( $instance );

		echo $before_widget;
		if ( $title ) echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		fastfood_post_details( array(
			'author'		=> $author,
			'date'			=> $date,
			'tags'			=> $tags,
			'categories'	=> $categories,
			'avatar_size'	=> $avatar_size,
			'featured'		=> $featured,
		) );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $this->validate_and_sanitize( $new_instance, true );

		return $instance;
	}

	function validate_and_sanitize( $instance, $is_update = false ) {

		//we first need to trap unselected checkboxes value
		if ( $is_update ) {
			$instance['featured']	= ( isset( $instance['featured'] ) && ( $instance['featured'] != 0 ) ) ? 1 : 0;
			$instance['author']		= ( isset( $instance['author'] ) && ( $instance['author'] != 0 ) ) ? 1 : 0;
			$instance['date']		= ( isset( $instance['date'] ) && ( $instance['date'] != 0 ) ) ? 1 : 0;
			$instance['tags']		= ( isset( $instance['tags'] ) && ( $instance['tags'] != 0 ) ) ? 1 : 0;
			$instance['categories']	= ( isset( $instance['categories'] ) && ( $instance['categories'] != 0 ) ) ? 1 : 0;
		}

		$instance = wp_parse_args( $instance, $this->defaults );

		$instance['title']			= esc_html( $instance['title'] );
		$instance['avatar_size']	= in_array( absint( $instance['avatar_size'] ), array ( 32, 48, 64, 96, 128 ) ) ? absint( $instance['avatar_size'] ) : $this->defaults['avatar_size'];

		return $instance;
	}

	function form( $instance ) {
		$instance = $this->validate_and_sanitize( $instance );
 		extract( $instance );
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
					foreach( array ( 32, 48, 64, 96, 128 ) as $size ) {
						echo sprintf( '<option value="%1$s" %2$s>%1$spx</option>',
							$size,
							selected( $avatar_size, $size, false )
						) . "\n";
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
class Fastfood_Widget_Font_Resize extends WP_Widget {

	function Fastfood_Widget_Font_Resize() {
		$widget_ops = array(
			'classname' => 'tb_font_resize',
			'description' => __( 'Simple javascript-based font resizer','fastfood' )
		);
		$this->WP_Widget( 'ff-font-resize', __( 'Font Resize','fastfood' ), $widget_ops );
		$this->alt_option_name = 'tb_font_resize';

	}

	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		echo '<a class="fontresizer-minus" href="javascript:void(0)" title="' . esc_attr( __('Decrease font size','fastfood') ) . '">-</a> ';
		echo '<a class="fontresizer-reset" href="javascript:void(0)" title="' . esc_attr( __('Reset font size','fastfood') ) . '">' . esc_html__( 'A', 'fastfood') . '</a> ';
		echo '<a class="fontresizer-plus" href="javascript:void(0)" title="' . esc_attr( __('Increase font size','fastfood') ) . '">+</a> ';
		echo $after_widget;

		wp_enqueue_script(
			'fastfood-font-resize',
			fastfood_get_minified( '%1$s/js/font-resize%2$s.js' ),
			array( 'jquery' ),
			'',
			true
		);
	}
}

/**
 * Register all of the default WordPress widgets on startup.
 */
function fastfood_widgets_init() {

	if ( !is_blog_installed() )
		return;

	if ( !FastfoodOptions::get_opt( 'fastfood_custom_widgets' ) )
		return;

	register_widget( 'Fastfood_Widget_Popular_Posts' );

	register_widget( 'Fastfood_Widget_Latest_Commented_Posts' );

	register_widget( 'Fastfood_Widget_Latest_Commentators' );

	register_widget( 'Fastfood_Widget_Pop_Categories' );

	register_widget( 'Fastfood_Widget_Social' );

	register_widget( 'Fastfood_Widget_Besides' );

	register_widget( 'Fastfood_Widget_Recent_Posts' );

	register_widget( 'Fastfood_Widget_Image_Exif' );

	register_widget( 'Fastfood_Widget_Clean_Archives' );

	register_widget( 'Fastfood_Widget_Post_Details' );

	register_widget( 'Fastfood_Widget_Font_Resize' );

}


class FastfoodWidgetClasses {

	public function __construct() {
		add_action( 'in_widget_form'			, array( $this, 'in_widget_form'), 10, 3 ); 
		add_filter( 'widget_update_callback'	, array( $this, 'widget_update_callback'), 10, 4 );
		add_filter( 'dynamic_sidebar_params'	, array( $this, 'dynamic_sidebar_params' ) );
	}


	function dynamic_sidebar_params( $params ) {
		global $wp_registered_widgets;
		static $widget_area_id = '';
		static $counter = 0;
		static $current_span = 0;

		if ( $widget_area_id !== $params[0]['id'] ) {
			$widget_area_id = $params[0]['id'];
			$counter = 0;
			$current_span = 0;
		}
		$counter++;

		$widget_id		= $params[0]['widget_id'];
		$widget_obj		= $wp_registered_widgets[$widget_id];
		$widget_opt		= get_option($widget_obj['callback'][0]->option_name);
		$widget_num		= $widget_obj['params'][0]['number'];

		$class_span		= ( isset( $widget_opt[$widget_num]['classes'] ) && !empty( $widget_opt[$widget_num]['classes'] ) ) ? $widget_opt[$widget_num]['classes'] : 'colspan-1';
		$class_span		.= ' widget-' . $counter;

		preg_match_all( '/colspan\-(.)/',$class_span, $span );

		$widget_area_columns = isset( $params[0]['columns'] ) ? $params[0]['columns'] : 1;

		if ( isset( $span[1][0] ) ){
			$span = absint( $span[1][0] );
			$current_span = $current_span + $span;
			if( $current_span > $widget_area_columns ) {
				$class_span .= ' clearleft';
				$current_span = $span;
			}
		}

		$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$class_span} ", $params[0]['before_widget'], 1 );

		return $params;
	}


	/**
	 * Hook used by WP_Widget and its children
	 */
	function in_widget_form($widget, $return, $instance) {
		$instance = wp_parse_args( (array) $instance, array( 'classes' => 'colspan-1' ) );
		$class = esc_attr( $instance['classes'] );

		$return		= null;
		$id			= $widget->get_field_id('classes');
		$name		= $widget->get_field_name('classes');
		$value		= $class;
?>

		<div class="widget-colspan widget-colspan-hide">
			<?php _e( 'Column Span', 'fastfood' ) ?>:<br />
			<?php foreach ( array( '1', '2', '3' ) as $option ) { ?>
				<label class="colspan-<?php echo $option;?>">
					<input class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="radio" value="colspan-<?php echo $option;?>" <?php checked( 'colspan-' . $option , esc_attr($value) ); ?>> <span><?php echo $option; ?></span>
				</label>
			<?php } ?>
			<a class="button colspan-options" href="javascript:void(0)"><?php _e( 'Span', 'fastfood' ) ?></a>
		</div>

<?php
	}


	/**
	 * Hook used by WP_Widget and its children
	 */
	function widget_update_callback($instance, $new_instance, $old_instance, $widget) {

		$instance['classes'] = esc_attr( $new_instance['classes'] );

		return $instance;

	}
}

new FastfoodWidgetClasses;
