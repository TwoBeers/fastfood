<?php
/**
 * quickbar.php
 *
 * The fixed footer
 *
 * This file contains the code for every element in
 * the fixed footer:
 * - quickbar
 * - statusbar
 * - navigation buttons
 *
 * @package fastfood
 * @since 0.33
 */


/**
 * the fixed footer containers
 *
 */
if ( !function_exists( 'fastfood_fixed_footer' ) ) {
	function fastfood_fixed_footer() {

		if ( fastfood_is_printpreview() ) return; // useless in print preview

?>
	<!-- begin fixed footer -->

	<div id="fixedfoot">

		<?php fastfood_quickbar(); ?>

		<?php fastfood_statusbar(); ?>

		<?php fastfood_navbuttons();?>

	</div>

	<!-- end fixed footer -->
<?php

	}
}


/**
 * the quickbar
 *
 */
if ( !function_exists( 'fastfood_quickbar' ) ) {
	function fastfood_quickbar( $args = '' ) {
		global $post, $fastfood_opt, $current_user;

		if ( ! fastfood_get_opt( 'fastfood_qbar' ) ) return;

		wp_reset_postdata();
		
		$defaults = array( 'r_posts' => 1, 'p_categories' => 1, 'r_comments' => 1, 'user' => 1 );
		$args = wp_parse_args( $args, $defaults );

?>

	<!-- begin quickbar -->
	<div id="quickbar"<?php if ( $fastfood_opt['fastfood_statusbar'] == 0 ) echo ' class="no-status"'; ?>>

	<?php

	/* custom elements can be easily added to quickbar using filters. eg:

		add_filter( 'fastfood_qbar_elements', 'fastfood_add_my_element' );

		function fastfood_add_my_element( $elements ) {
			$elements['my-first-element'] = array(
				'title' => 'my title',
				'image' => 'http://www.my-site.net/wp-content/uploads/2012/06/my-50x50-image.jpg',
				'content' => 'this is the content of my custom element. Hurray!'
			);
			return $elements;
		}

	*/
		$elements = array();
		
		$elements = apply_filters('fastfood_qbar_elements', $elements);
		
		foreach ( $elements as $key => $element ) {

	?>
		<div class="menuitem <?php echo $key; ?>">
			<div class="itemimg" style="background-image:url(<?php echo $element['image'] ?>)"></div>
			<div class="menuback custom-element">
				<div class="menulcont">
					<div class="mentit"><?php echo $element['title'] ?></div>
					<div>
						<?php echo $element['content'] ?>
					</div>
				</div>
			</div>
		</div>
	<?php

		}
	?>

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

	<?php if ( $args['r_comments'] && $fastfood_opt['fastfood_qbar_reccom'] == 1 ) { //							recent comments menu ?>
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

	<?php if ( $args['user'] && $fastfood_opt['fastfood_qbar_user'] == 1 ) { //									user links menu ?>
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
								$email = $current_user->user_email;
								echo get_avatar( $email, 50, $default=get_template_directory_uri() . '/images/user.png', 'user-avatar' );
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
									$awaiting_mod = wp_count_comments();
									$awaiting_mod = $awaiting_mod->moderated;
									$awaiting_mod = $awaiting_mod ? ' (' . number_format_i18n( $awaiting_mod ) . ')' : '';
								?>
									<li><a title="<?php _e( 'Comments', 'fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'fastfood' ); ?></a><?php echo $awaiting_mod; ?></li>
								<?php } ?>
							<?php } ?>
							<li><a title="<?php _e( 'Log out', 'fastfood' ); ?>" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e( 'Log out', 'fastfood' ); ?></a></li>
						<?php } ?>
						<?php if ( ! is_user_logged_in() ) {?>
							<li class="ql_cat_li">
								<a title="<?php _e( 'Log in', 'fastfood' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in', 'fastfood' ); ?></a>
								<?php fastfood_mini_login(); ?>
							</li>
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


/**
 * the statusbar
 *
 */
function fastfood_statusbar () {
	global $current_user;

	if ( ! fastfood_get_opt('fastfood_statusbar' ) ) return;

	$user_name = is_user_logged_in() ? $current_user->display_name : '';
	$welcome = sprintf( __( 'Welcome %s','fastfood' ), $user_name );
	$today_is = sprintf( __('today is %1$s, %2$s','fastfood'), date_i18n( __( 'l','fastfood' ) ), date_i18n( get_option( 'date_format' ) ) );

	$output = apply_filters( 'fastfood_filter_statusbar', $welcome . ', ' . $today_is );

	if ( $output )
		echo '<div id="statusbar">' . $output . '</div>';

}


/**
 * the navigation bar
 *
 */
if ( !function_exists( 'fastfood_navbuttons' ) ) {
	function fastfood_navbuttons( $args = '' ) {
		global $post, $fastfood_opt;
		global $paged, $wp_query;

		if ( ! fastfood_get_opt('fastfood_navbuttons' ) ) return;

		wp_reset_postdata();

		$is_post = is_single() && !is_attachment() && !fastfood_is_allcat();
		$is_image = is_attachment() && !fastfood_is_allcat();
		$is_page = is_singular() && !is_single() && !is_attachment() && !fastfood_is_allcat();
		$is_singular = is_singular() && !fastfood_is_allcat();

		$defaults = array(
			'print' => 1,
			'comment' => 1,
			'feed' => 1,
			'trackback' => 1,
			'home' => 1,
			'next_prev' => 1,
			'up_down' => 1,
			'fixed' => 1
		);
		$args = wp_parse_args( $args, $defaults );

		$buttons = array();

		// ------- Print -------
		if ( $fastfood_opt['fastfood_navbuttons_print'] && $args['print'] && $is_singular ) {

			$query_vars['style'] = 'printme';
			if ( get_query_var('page') ) {
				$query_vars['page'] = esc_html( get_query_var( 'page' ) );
			}
			if ( get_query_var( 'cpage' ) ) {
				$query_vars['cpage'] = esc_html( get_query_var( 'cpage' ) );
			}

			$buttons['print'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => add_query_arg( $query_vars, get_permalink( $post->ID ) ),
				'icon_class' => 'minib_print',
				'tooltip' => __( 'Print preview', 'fastfood' )
			);

		} 

		// ------- Leave a comment -------
		if ( $fastfood_opt['fastfood_navbuttons_comment'] && $args['comment'] && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { 

			$buttons['comment'] = array(
				'class' => '',
				'a_class' => 'show_comment_form',
				'a_rel' => '',
				'a_href' => '#respond',
				'icon_class' => 'minib_comment',
				'tooltip' => __( 'Leave a comment', 'fastfood' )
			);

		} 

		// ------- RSS feed -------
		if ( $fastfood_opt['fastfood_navbuttons_feed'] && $args['feed'] && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { 

			$buttons['feed'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => get_post_comments_feed_link( $post->ID, 'rss2' ),
				'icon_class' => 'minib_rss',
				'tooltip' => __( 'feed for comments on this post', 'fastfood' )
			);

		} 

		// ------- Trackback -------
		if ( $fastfood_opt['fastfood_navbuttons_trackback'] && $args['trackback'] && $is_singular && pings_open() ) { 

			$buttons['trackback'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => 'trackback',
				'a_href' => get_trackback_url(),
				'icon_class' => 'minib_track',
				'tooltip' => __( 'Trackback URL', 'fastfood' )
			);

		} 

		// ------- Home -------
		if ( $fastfood_opt['fastfood_navbuttons_home'] && $args['home'] ) { 

			$buttons['home'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => home_url(),
				'icon_class' => 'minib_home',
				'tooltip' => __( 'Home', 'fastfood' )
			);

		} 

		// ------- Back to parent post -------
		if ( $is_image && ! empty( $post->post_parent ) ) { 

			$buttons['image'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => 'gallery',
				'a_href' => get_permalink( $post->post_parent ),
				'icon_class' => 'minib_backtopost',
				'tooltip' => sprintf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) )
			);

		} 

		// ------- Next post -------
		if ( $fastfood_opt['fastfood_navbuttons_nextprev'] && $args['next_prev'] && $is_post && get_next_post() ) { 

			$buttons['nextpost'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => get_permalink( get_next_post() ),
				'icon_class' => 'minib_npage',
				'tooltip' => sprintf( __( 'Next Post', 'fastfood' ) . ': %s', get_the_title( get_next_post() ) )
			);

		} 

		// ------- Previous post -------
		if ( $fastfood_opt['fastfood_navbuttons_nextprev'] && $args['next_prev'] && $is_post && get_previous_post() ) { 

			$buttons['prevpost'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => get_permalink( get_previous_post() ),
				'icon_class' => 'minib_ppage',
				'tooltip' => sprintf( __( 'Previous Post', 'fastfood' ) . ': %s', get_the_title( get_previous_post() ) )
			);

		} 

		// ------- Newer Posts -------
		if ( $fastfood_opt['fastfood_navbuttons_newold'] && $args['next_prev'] && !$is_singular && !fastfood_is_allcat() && get_previous_posts_link() ) { 

			$buttons['newposts'] = array(
				'class' => 'nb-nextprev',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => previous_posts( false ),
				'icon_class' => 'minib_ppages',
				'tooltip' => __( 'Newer Posts', 'fastfood' )
			);

		} 

		// ------- Older Posts -------
		if ( $fastfood_opt['fastfood_navbuttons_newold'] && $args['next_prev'] && !$is_singular && !fastfood_is_allcat() && get_next_posts_link() ) { 

			$max_page = $wp_query->max_num_pages;

			$buttons['oldposts'] = array(
				'class' => 'nb-nextprev',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => next_posts( $max_page, false ),
				'icon_class' => 'minib_npages',
				'tooltip' => __( 'Older Posts', 'fastfood' )
			);

		} 

		// ------- Top -------
		if ( $fastfood_opt['fastfood_navbuttons_topbottom'] && $args['up_down'] ) { 

			$buttons['up'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => '#',
				'icon_class' => 'minib_top',
				'tooltip' => __( 'Top of page', 'fastfood' )
			);

		} 

		// ------- Bottom -------
		if ( $fastfood_opt['fastfood_navbuttons_topbottom'] && $args['up_down'] ) { 

			$buttons['down'] = array(
				'class' => '',
				'a_class' => '',
				'a_rel' => '',
				'a_href' => '#footer',
				'icon_class' => 'minib_bottom',
				'tooltip' => __( 'Bottom of page', 'fastfood' )
			);

		}

		$buttons = apply_filters( 'fastfood_filter_navbuttons', $buttons );

		if ( ! $buttons ) return;

?>

	<div id="navbuttons">

		<?php
			foreach ( $buttons as $button ) {

				$button['class'] = $button['class'] ? ' ' . esc_attr( trim( $button['class'] ) ) : '';
				$button['a_class'] = $button['a_class'] ? ' class="' . esc_attr( trim( $button['a_class'] ) ) . '"' : '';
				$button['a_rel'] = $button['a_rel'] ? ' rel="' . esc_attr( trim( $button['a_rel'] ) ) . '"' : '';
				$button['a_href'] = esc_url( $button['a_href'] );
				$button['icon_class'] = $button['icon_class'] ? ' ' . esc_attr( trim( $button['icon_class'] ) ) : '';
				$button['tooltip'] = esc_html( $button['tooltip'] );
		?>

		<div class="minibutton<?php echo $button['class']; ?>">
			<a href="<?php echo $button['a_href']; ?>"<?php echo $button['a_class']; ?><?php echo $button['a_rel']; ?>>
				<span class="minib_img<?php echo $button['icon_class']; ?>">&nbsp;</span>
			</a>
			<span class="nb_tooltip"><?php echo $button['tooltip']; ?></span>
		</div>

		<?php } ?>

	</div>

<?php

	}
}


// Get Recent Comments
if ( !function_exists( 'fastfood_get_recentcomments' ) ) {
	function fastfood_get_recentcomments( $number = 10 ) {

		$args=array(
			'status' => 'approve',
			'number' => apply_filters( 'fastfood_filter_get_recentcomments_number', $number ),
			'type' => 'comment'
		);
		$comments = get_comments( $args ); // valid type values (not documented) : 'pingback','trackback','comment'

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

		$args=array(
			'ignore_sticky_posts' => 'true',
			'post_status' => 'publish',
			'posts_per_page' => apply_filters( 'fastfood_filter_get_recententries_number', $number ),
			'no_found_rows' => 'true'
		);
		$r = new WP_Query( $args );

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
	function fastfood_get_categories_wpr( $number = 10 ) {

		$args=array(
			'orderby' => 'count',
			'number' => apply_filters( 'fastfood_filter_get_categories_wpr_number', $number ),
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

if ( isset( $fastfood_opt['fastfood_qbar_minilogin'] ) && ( $fastfood_opt['fastfood_qbar_minilogin'] == 1 ) && ( !class_exists("siCaptcha") ) ) {

?>
	<div id="ff_minilogin_wrap" class="cat_preview">

		<div class="mentit"><?php _e( 'Log in', 'fastfood' ); ?></div>

		<div id="ff_minilogin" class="solid_ul">
			<?php wp_login_form($args); ?>
			<a id="closeminilogin" href="#"><?php _e( 'Close', 'fastfood' ); ?></a>
		</div>

	</div>

<?php

		}

	}
}
