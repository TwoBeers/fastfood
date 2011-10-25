<?php

/**
 * Fastfood Widgets
 */

/**
 * Popular_Posts widget class
 */
class fastfood_ff_widget_popular_posts extends WP_Widget {

	function fastfood_ff_widget_popular_posts() {
		$widget_ops = array('classname' => 'ff_widget_popular_posts', 'description' => __( 'The most commented posts on your site','fastfood') );
		$this->WP_Widget('ff-popular-posts', __('Popular Posts','fastfood'), $widget_ops);
		$this->alt_option_name = 'ff_widget_popular_posts';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('ff_widget_popular_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Popular Posts','fastfood') : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
		$use_thumbs = ( !isset($instance['thumb']) || $thumb = (int) $instance['thumb'] ) ? 1 : 0;
		
		$r = new WP_Query(array('showposts' => $number, 'no_found_rows' => true, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'orderby' => 'comment_count'));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<li<?php if ( $use_thumbs ) echo ' class="li-with-thumbs"'; ?>>
			<?php
				if ( $use_thumbs ) {
					if( has_post_thumbnail() ) {
						the_post_thumbnail( array( 40,40 ) );
					} else {
						echo '<div class="post-thumb"></div>';
					}
				}
			 ?>
			<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a> <span>(<?php echo get_comments_number(); ?>)</span>
		</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('ff_widget_popular_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['ff_widget_popular_posts']) )
			delete_option('ff_widget_popular_posts');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('ff_widget_popular_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$thumb = 1;
		if ( isset($instance['thumb']) && !$thumb = (int) $instance['thumb'] )
			$thumb = 0;
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','fastfood'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:','fastfood'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
			<label for="<?php echo $this->get_field_id('thumb'); ?>"><?php _e('Show post thumbnails','fastfood'); ?></label>
		</p>	
<?php
	}
}

/**
 * latest_Commented_Posts widget class
 *
 */
class fastfood_widget_latest_commented_posts extends WP_Widget {

	function fastfood_widget_latest_commented_posts() {
		$widget_ops = array('classname' => 'ff_widget_latest_commented_posts', 'description' => __( 'The latest commented posts/pages of your site','fastfood' ) );
		$this->WP_Widget('ff-recent-comments', __('Latest activity','fastfood'), $widget_ops);
		$this->alt_option_name = 'ff_widget_latest_commented_posts';

		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function flush_widget_cache() {
		wp_cache_delete('ff_widget_latest_commented_posts', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get('ff_widget_latest_commented_posts', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract($args, EXTR_SKIP);
 		$output = '';
 		$title = apply_filters('widget_title', empty($instance['title']) ? __('Latest activity','fastfood') : $instance['title']);

		if ( ! $number = (int) $instance['number'] )
 			$number = 5;
 		else if ( $number < 1 )
 			$number = 1;
		$use_thumbs = ( !isset($instance['thumb']) || $thumb = (int) $instance['thumb'] ) ? 1 : 0;

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment', 'number' => 200 ) );
		$post_array = array();
		$counter = 0;
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;
		$li_class = $use_thumbs ? ' class="li-with-thumbs"' : '';

		$output .= '<ul>';
		if ( $comments ) {
			foreach ( (array) $comments as $comment) {
				if ( ! in_array( $comment->comment_post_ID, $post_array ) ) {
					$post = get_post( $comment->comment_post_ID );
					setup_postdata( $post );
					if ( $use_thumbs ) {
						if( has_post_thumbnail($post->ID) ) {
							$the_thumb = get_the_post_thumbnail( $post->ID, array( 40,40 ) );
						} else {
							$the_thumb = '<div class="post-thumb"></div>';
						}
					} else {
						$the_thumb = '';
					}
					
					$output .=  '<li' . $li_class . '>' . $the_thumb . ' <a href="' . get_permalink( $post->ID ) . '" title="' .  esc_html( $post->post_title ) . '">' . $post->post_title . '</a></li>';
					$post_array[] = $comment->comment_post_ID;
					if ( ++$counter >= $number ) break;
				}
			}
 		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('ff_widget_latest_commented_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['ff_widget_latest_commented_posts']) )
			delete_option('ff_widget_latest_commented_posts');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$thumb = 1;
		if ( isset($instance['thumb']) && !$thumb = (int) $instance['thumb'] )
			$thumb = 0;
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','fastfood'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:','fastfood'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
			<label for="<?php echo $this->get_field_id('thumb'); ?>"><?php _e('Show post thumbnails','fastfood'); ?></label>
		</p>
<?php
	}
}


/**
 * latest_Comment_Authors widget class
 *
 */
class fastfood_widget_latest_commentators extends WP_Widget {

	function fastfood_widget_latest_commentators() {
		$widget_ops = array('classname' => 'ff_widget_latest_commentators', 'description' => __( 'The latest comment authors','fastfood' ) );
		$this->WP_Widget('ff-recent-commentators', __('Latest comment authors','fastfood'), $widget_ops);
		$this->alt_option_name = 'ff_widget_latest_commentators';

		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function flush_widget_cache() {
		wp_cache_delete('ff_widget_latest_commentators', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		if ( get_option('require_name_email') != '1' ) return; //commentors must be identifiable
		
		$cache = wp_cache_get('ff_widget_latest_commentators', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract($args, EXTR_SKIP);
 		$output = '';
 		$title = apply_filters('widget_title', empty($instance['title']) ? __('Latest comment authors','fastfood') : $instance['title']);
		$icon_size = isset($instance['icon_size']) ? absint($instance['icon_size']) : '32';

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

		$output .= '<ul>';
		if ( $comments ) {
			foreach ( (array) $comments as $comment) {
				if ( !in_array( $comment->comment_author_email, $post_array ) ) {
					if ( $comment->comment_author_url == '' ) {
						$output .=  '<li title="' .  $comment->comment_author . '">' . get_avatar( $comment, $icon_size, $default=get_option('avatar_default') ) . '</li>';
					} else {
						$output .=  '<li><a target="_blank" href="' . $comment->comment_author_url . '" title="' .  $comment->comment_author . '">' . get_avatar( $comment, $icon_size, $default=get_option('avatar_default')) . '</a></li>';
					}
					$post_array[] = $comment->comment_author_email;
					if ( ++$counter >= $number ) break;
				}
			}
 		}
		$output .= '</ul><div class="fixfloat"></div>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('ff_widget_latest_commentators', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
        $instance["icon_size"] = in_array( $new_instance["icon_size"], array ('16', '24', '32', '40', '50', '60') ) ? $new_instance["icon_size"] : '32' ;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['ff_widget_latest_commentators']) )
			delete_option('ff_widget_latest_commentators');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$icon_size = isset($instance['icon_size']) ? absint($instance['icon_size']) : '32';

		if ( get_option('require_name_email') != '1' ) {
			printf ( __( 'Comment authors <strong>must</strong> use a name and a valid e-mail in order to use this widget. Check the <a href="%1$s">Discussion settings</a>','fastfood' ), esc_url( admin_url( 'options-discussion.php' ) ) );
			return;
		}
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','fastfood'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of users to show:','fastfood'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('icon_size'); ?>"><?php _e('Select your icon size', 'fastfood'); ?></label><br />
            <select name="<?php echo $this->get_field_name('icon_size'); ?>" id="<?php echo $this->get_field_id('icon_size'); ?>" >
<?php
            $size_array = array ('16', '24', '32', '40', '50', '60');
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
 * Popular Categories widget class
 *
 */
class fastfood_Widget_pop_categories extends WP_Widget {

	function fastfood_Widget_pop_categories() {
		$widget_ops = array( 'classname' => 'ff_widget_categories', 'description' => __( 'A list of popular categories', 'fastfood' ) );
		$this->WP_Widget('ff-categories', __('Popular Categories', 'fastfood'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Popular Categories', 'fastfood' ) : $instance['title'], $instance, $this->id_base);
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
		$cat_args = 'number=' . $number . '&title_li=&orderby=count&order=DESC&hierarchical=0&show_count=1';
		wp_list_categories($cat_args);
?>
			<li style="text-align: right;margin-top:12px;"><a title="<?php _e('View all categories', 'fastfood'); ?>" href="<?php  echo home_url(); ?>/?allcat=y"><?php _e('View all', 'fastfood'); ?></a></li>
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
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','fastfood'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of categories to show:','fastfood'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
<?php
	}

}

/**
 * Social network widget class.
 * Social media services supported: Facebook, Twitter, Myspace, Youtube, LinkedIn, Del.icio.us, Digg, Flickr, Reddit, StumbleUpon, Technorati and Github.
 * Optional: RSS icon. 
 *
 */

class fastfood_Widget_social extends WP_Widget {
	function fastfood_Widget_social() {
		$widget_ops = array(
            'classname' => 'ff-widget-social',
            'description' => __("This widget lets visitors of your blog subscribe to it and follow you on popular social networks like Twitter, FaceBook etc.", "fastfood"));
		$control_ops = array('width' => 650);

		$this->WP_Widget("ff-social", __("Follow Me", "fastfood"), $widget_ops, $control_ops);
        $this->follow_urls = array(
			'Buzz',
			'Delicious',
			'Deviantart',
			'Digg',
			'Dropbox',
			'Facebook',
			'Flickr',
			'Github',
			'GooglePlus',
			'Hi5',
			'LinkedIn',
			'Myspace',
			'Odnoklassniki',
			'Orkut',
			'Qzone',
			'Reddit',
			'Sina',
			'StumbleUpon',
			'Technorati',
			'Tencent',
			'Twitter',
			'Vimeo',
			'VKontakte',
			'WindowsLive',
			'Youtube',
			'RSS');
	}

    function form($instance) {
        $defaults = array("title" => __("Follow Me", "fastfood"),
            "icon_size" => '48px',
        );
        foreach ($this->follow_urls as $follow_service ) {
            $defaults[$follow_service."_icon"] = $follow_service;
            $defaults["show_".$follow_service] = false;
        }
        $instance = wp_parse_args((array)$instance, $defaults);
?>
	<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'fastfood' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title']); ?>" /></p>
    <div style="padding: 10px 0; border-top: 1px solid #DFDFDF;">

<?php
        foreach($this->follow_urls as $follow_service ) {
?>
        <div style="float: left; width: 40%; margin: 0pt 5%;">
			<h2>
				<input id="<?php echo $this->get_field_id('show_'.$follow_service); ?>" name="<?php echo $this->get_field_name('show_'.$follow_service); ?>" type="checkbox" <?php checked( $instance['show_'.$follow_service], 'on'); ?>  class="checkbox" />
				<img style="vertical-align:middle; width:40px; height:40px;" src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo $follow_service; ?>.png" alt="<?php echo $follow_service; ?>" />
				<?php echo $follow_service; ?>
			</h2>
<?php
            if ($follow_service != 'RSS') {
                $url_or_account = $follow_service;
?>
        <p>
            <label for="<?php echo $this->get_field_id($follow_service.'_account'); ?>">
<?php
				printf(__('Enter %1$s account link:', 'fastfood'), $follow_service);
?>
            </label>
            <input id="<?php echo $this->get_field_id($follow_service.'_account'); ?>" name="<?php echo $this->get_field_name($follow_service.'_account'); ?>" value="<?php if (isset($instance[$follow_service.'_account'])) echo $instance[$follow_service.'_account']; ?>" class="widefat" />
        </p>

<?php
            }
?>
        </div>
<?php
        }
?>
        <div class="clear" style="padding: 10px 0; border-top: 1px solid #DFDFDF; text-align: right;">
            <label for="<?php echo $this->get_field_id('icon_size'); ?>"><?php _e('Select your icon size', 'fastfood'); ?></label><br />
            <select name="<?php echo $this->get_field_name('icon_size'); ?>" id="<?php echo $this->get_field_id('icon_size'); ?>" >
<?php
            $size_array = array ('16px', '24px', '32px', '40px', '50px', '60px');
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

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["icon_size"] = $new_instance["icon_size"];

        foreach ($this->follow_urls as $follow_service ) {
            $instance['show_'.$follow_service] = $new_instance['show_'.$follow_service];
            $instance[$follow_service.'_account'] = $new_instance[$follow_service.'_account'];
        }

        return $instance;
    }

	function widget( $args, $instance ) {
		extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$icon_size = ( isset($instance['icon_size']) ) ? $instance['icon_size'] : '48px';
        echo $before_widget;
        if (!empty($title)) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }
?>
    <div class="fix" style="text-align: center;">
<?php
        foreach ($this->follow_urls as $follow_service ) {
			$show = ( isset($instance['show_'.$follow_service]) ) ? $instance['show_'.$follow_service] : false;
			$account = ( isset($instance[$follow_service.'_account']) ) ? $instance[$follow_service.'_account'] : '';
			if ($follow_service == 'RSS') {
				$account = get_bloginfo( 'rss2_url' );
			}
			if ($show && !empty($account)) {
?>
        <a href="<?php echo $account; ?>" target="_blank" class="ff-social-icon" title="<?php echo $follow_service;?>">
            <img src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo $follow_service;?>.png" alt="<?php echo $follow_service;?>" style='width: <?php echo $icon_size;?>; height: <?php echo $icon_size;?>;' />
        </a>
<?php
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
 *
 * Based on Twenty_Eleven_Ephemera_Widget
 *
 */

class fastfood_Widget_besides extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function fastfood_Widget_besides() {
		$widget_ops = array( 'classname' => 'ff_widget_besides', 'description' => __( 'Use this widget to list your recent Aside and Status posts', 'fastfood' ) );
		$this->WP_Widget( 'ff-widget-besides', __( 'besides...', 'fastfood' ), $widget_ops );
		$this->alt_option_name = 'ff_widget_besides';

		add_action( 'save_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache' ) );
	}
	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'ff_widget_besides', 'widget' );

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
				<span style="font-style: italic; color: #999;"><?php the_author(); ?> - <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?></span>
			</div>
			<?php } elseif ( $type == 'status' ) { ?>
			<div class="wentry-status">
				<?php echo get_avatar( get_the_author_meta('user_email'), 24, $default=get_option('avatar_default'), get_the_author() ); ?>
				<a style="font-weight: bold;" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php printf( 'View all posts by %s', esc_attr( get_the_author() ) ); ?>"><?php echo get_the_author(); ?></a>
				<?php the_content(); ?>
				<span style="color: #999;"><?php echo fastfood_friendly_date(); ?></span>
			</div>
			<?php } ?>

		<?php endwhile; ?>
		<?php

		echo $after_widget;

		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'ff_widget_besides', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['type'] = in_array( $new_instance['type'], array( 'aside', 'status' ) ) ? $new_instance['type'] : 'aside';
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['ff_widget_besides'] ) )
			delete_option( 'ff_widget_besides' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'ff_widget_besides', 'widget' );
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
            foreach($type_array as $avaible_type) {
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
 * Recent Posts in Category widget class
 *
 */
class fastfood_Widget_recent_posts extends WP_Widget {

	function fastfood_Widget_recent_posts() {
		$widget_ops = array('classname' => 'ff_widget_recent_entries', 'description' => __( "The most recent posts in a single category", 'fastfood' ) );
		$this->WP_Widget('ff-recent-posts', __('Recent Posts in Category', 'fastfood' ), $widget_ops);
		$this->alt_option_name = 'ff_widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('ff_widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$use_thumbs = ( !isset($instance['thumb']) || $thumb = (int) $instance['thumb'] ) ? 1 : 0;
		$category = isset( $instance['category']) ? absint($instance['category'] ) : '';
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts in %s', 'fastfood' ) : $instance['title'], $instance, $this->id_base);
		$title = sprintf( $title, '<a href="' . get_category_link( $category ) . '">' . get_cat_name( $category ) . '</a>' );
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query( array( 'cat' => $category, 'posts_per_page' => $number, 'no_found_rows' => true, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<li<?php if ( $use_thumbs ) echo ' class="li-with-thumbs"'; ?>>
			<?php
				if ( $use_thumbs ) {
					if( has_post_thumbnail() ) {
						the_post_thumbnail( array( 40,40 ) );
					} else {
						echo '<div class="post-thumb"></div>';
					}
				}
			 ?>
			<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
		</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('ff_widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['category'] = (int) $new_instance['category'];
		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['ff_widget_recent_entries']) )
			delete_option('ff_widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('ff_widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$category = isset($instance['category']) ? absint($instance['category']) : '';
		$thumb = isset($instance['thumb']) ? absint($instance['thumb']) : 1;
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'fastfood' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Title:', 'fastfood' ); ?></label>
			<?php wp_dropdown_categories( Array(
						'orderby'            => 'ID', 
						'order'              => 'ASC',
						'show_count'         => 1,
						'hide_empty'         => 0,
						'hide_if_empty'      => false,
						'echo'               => 1,
						'selected'           => $category,
						'hierarchical'       => 1, 
						'name'               => $this->get_field_name('category'),
						'id'                 => $this->get_field_id('category'),
						'class'              => 'widefat',
						'taxonomy'           => 'category',
					) ); ?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'fastfood' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p>
			<input id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
			<label for="<?php echo $this->get_field_id('thumb'); ?>"><?php _e('Show post thumbnails','fastfood'); ?></label>
		</p>	

<?php
	}
}

/**
 * Register all of the default WordPress widgets on startup.
 */
function fastfood_widgets_init() {
	if ( !is_blog_installed() )
		return;

	register_widget('fastfood_ff_widget_popular_posts');

	register_widget('fastfood_Widget_latest_Commented_Posts');
	
	register_widget('fastfood_widget_latest_commentators');
	
	register_widget('fastfood_Widget_pop_categories');
	
	register_widget('fastfood_Widget_social');
	
	register_widget('fastfood_Widget_besides');
	
	register_widget('fastfood_Widget_recent_posts');
}

add_action('widgets_init', 'fastfood_widgets_init');
