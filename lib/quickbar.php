<?php
/**
 * The quickbar stuff
 *
 * @package fastfood
 * @since fastfood 0.33
 */

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
