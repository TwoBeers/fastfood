<?php

/**
 * Fastfood Widgets
 *
 * based on WordPress default widgets (wp-includes/default-widgets.php)
 */

/**
 * Popular_Posts widget class
 */
class fastfood_ff_widget_popular_posts extends WP_Widget {

	function fastfood_ff_widget_popular_posts() {
		$widget_ops = array('classname' => 'ff_widget_popular_posts', 'description' => __( '[by Fastfood theme] The most commented posts on your site','fastfood') );
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
		
		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1, 'orderby' => 'comment_count'));
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
		$widget_ops = array('classname' => 'ff_widget_latest_commented_posts', 'description' => __( '[by Fastfood theme] The latest commented posts/pages of your site','fastfood' ) );
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

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment' ) );
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
		$widget_ops = array('classname' => 'ff_widget_latest_commentators', 'description' => __( '[by Fastfood theme] The latest comment authors','fastfood' ) );
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

		if ( ! $number = (int) $instance['number'] )
 			$number = 5;
 		else if ( $number < 1 )
 			$number = 1;

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment' ) );
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
						$output .=  '<li title="' .  $comment->comment_author . '">' . get_avatar( $comment, 32, $default=get_option('avatar_default') ) . '</li>';
					} else {
						$output .=  '<li><a href="' . $comment->comment_author_url . '" title="' .  $comment->comment_author . '">' . get_avatar( $comment, 32, $default=get_option('avatar_default')) . '</a></li>';
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
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['ff_widget_latest_commentators']) )
			delete_option('ff_widget_latest_commentators');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;

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
	
}

add_action('widgets_init', 'fastfood_widgets_init');
