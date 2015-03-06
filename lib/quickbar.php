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


class Fastfood_Fixed_Footer {

	/**
	 * Constructor
	 */
	function __construct () {

		add_action( 'template_redirect', array( $this, 'init' ) );

	}


	/**
	 * Initialize the class
	 *
	 * @since Fastfood 0.37
	 */
	function init() {

		if ( fastfood_is_mobile() ) return;

		add_action( 'fastfood_hook_body_bottom', array( $this, 'the_fixed_footer' ) );

		if ( FastfoodOptions::get_opt( 'fastfood_qbar' ) )
			add_action( 'fastfood_hook_fixed_footer', array( $this, 'the_quickbar' ) );

		if ( FastfoodOptions::get_opt('fastfood_statusbar' ) )
			add_action( 'fastfood_hook_fixed_footer', array( $this, 'the_statusbar' ) );

		if ( FastfoodOptions::get_opt('fastfood_navbuttons' ) )
			add_action( 'fastfood_hook_fixed_footer', array( $this, 'the_navbuttons' ) );

	}


	/**
	 * Print the fixed footer
	 *
	 * @since Fastfood 0.37
	 */
	function the_fixed_footer() {

		if ( false === has_action( 'fastfood_hook_fixed_footer' ) ) return;

		?>

			<div id="fixedfoot-wrapper">

				<div id="fixedfoot">

					<?php do_action( 'fastfood_hook_fixed_footer' ); ?>

				</div><!-- #fixedfoot -->

			</div><!-- #fixedfoot-wrapper -->

		<?php

	}


	/**
	 * The quickbar
	 *
	 * @since Fastfood 0.37
	 */
	function the_quickbar() {
		global $post, $current_user;

		$panels = array();

		/**
		 * Panel - Recent Posts
		 */
		if ( FastfoodOptions::get_opt( 'fastfood_qbar_recpost' ) )
			$panels['recent-posts'] = array(
				'title'		=> __( 'Recent Posts', 'fastfood' ),
				'image'		=> 'el-icon-file',
				'content'	=> '',
				'callback'	=> array( $this, 'qbar_recent_posts' ),
			);

		/**
		 * Panel - Popular Categories
		 */
		if ( FastfoodOptions::get_opt( 'fastfood_qbar_cat' ) )
			$panels['popular-categories'] = array(
				'title'		=> __( 'Categories', 'fastfood' ),
				'image'		=> 'el-icon-folder-open',
				'content'	=> '',
				'callback'	=> array( $this, 'qbar_popular_categories' ),
			);

		/**
		 * Panel - Recent Comments
		 */
		if ( FastfoodOptions::get_opt( 'fastfood_qbar_reccom' ) )
			$panels['recent-comments'] = array(
				'title'		=> __( 'Recent Comments', 'fastfood' ),
				'image'		=> 'el-icon-comment-alt',
				'content'	=> '',
				'callback'	=> array( $this, 'qbar_recent_comments' ),
			);

		/**
		 * Panel - User
		 */
		if ( FastfoodOptions::get_opt( 'fastfood_qbar_user' ) )
			$panels['user'] = array(
				'title'		=> __( 'User', 'fastfood' ),
				'image'		=> 'el-icon-user',
				'content'	=> '',
				'callback'	=> array( $this, 'qbar_user' ),
			);

		/* custom panels can be easily added to quickbar using filters. eg:

			add_filter( 'fastfood_qbar_elements', 'add_my_quickbar_panel' );

			function add_my_quickbar_panel( $panels ) {
				$panels['my-first-panel'] = array(
					'title' => 'my title',
					'image' => 'el-icon-elusive_iconset_class',
					'content' => 'this is the content of my custom panel. Hurray!'
				);
				return $panels;
			}

		*/
		$panels = apply_filters( 'fastfood_qbar_elements', $panels );

		if ( !$panels ) return;

		wp_reset_postdata();
		?>

			<div id="quickbar" class="no-grav has-<?php echo count( $panels ); ?>-panels<?php if ( !FastfoodOptions::get_opt( 'fastfood_statusbar' ) ) echo ' no-status'; ?>">

				<?php fastfood_hook_quickbar_top(); ?>

				<?php foreach ( $panels as $key => $panel ) { ?>

					<?php
						/**
						 * make sure every panel is well formed
						 */
						$panel = wp_parse_args( $panel, array(
							'title'		=> '',
							'image'		=> '',
							'content'	=> '',
							'callback'	=> '',
						) );
					?>
					<div class="quickbar-panel" id="panel-<?php echo $key; ?>">
						<i class="quickbar-panel-icon <?php echo $panel['image']; ?>"></i>
						<div class="quickbar-panel-container">
							<div class="quickbar-panel-content">
								<div class="quickbar-panel-title"><?php echo $panel['title']; ?></div>
								<?php if ( $panel['content'] ) echo $panel['content']; ?>
								<?php if ( $panel['callback'] ) call_user_func( $panel['callback'] ); ?>
							</div>
						</div>
					</div>

				<?php } ?>

				<?php fastfood_hook_quickbar_bottom(); ?>

			</div><!-- #quickbar -->

		<?php

	}


	/**
	 * The Status Bar
	 *
	 * @since Fastfood 0.37
	 */
	function the_statusbar () {
		global $current_user;

		$user_name	= is_user_logged_in() ? $current_user->display_name : '';
		$welcome	= sprintf( __( 'Welcome %s','fastfood' ), $user_name );
		$avatar		= is_user_logged_in() ? get_avatar( $current_user->user_email, 32, $default = get_template_directory_uri() . '/images/user.png', 'user-avatar' ) : '';
		$today_is	= sprintf( __('today is %1$s, %2$s','fastfood'), date_i18n( __( 'l','fastfood' ) ), date_i18n( get_option( 'date_format' ) ) );

		$output = apply_filters( 'fastfood_filter_statusbar', $welcome . ', ' . $today_is . $avatar );

		if ( $output ) {
			?>

				<div id="statusbar" class="no-grav">

					<?php echo $output; ?>

				</div><!-- #statusbar -->

			<?php
		}

	}


	/**
	 * The Navigation Buttons
	 *
	 * @since Fastfood 0.37
	 */
	function the_navbuttons( $args = '' ) {
		global $post, $paged, $wp_query;

		wp_reset_postdata();

		$is_post		= is_single() && !is_attachment() && !fastfood_is_allcat();
		$is_image		= is_attachment() && !fastfood_is_allcat();
		$is_page		= is_singular() && !is_single() && !is_attachment() && !fastfood_is_allcat();
		$is_singular	= is_singular() && !fastfood_is_allcat();

		$defaults = array(
			'print'		=> 1,
			'comment'	=> 1,
			'feed'		=> 1,
			'trackback'	=> 1,
			'home'		=> 1,
			'next_prev'	=> 1,
			'up_down'	=> 1,
			'fixed'		=> 1
		);
		$args = wp_parse_args( $args, $defaults );

		$buttons = array();

		// ------- Print -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_print' ) && $args['print'] && $is_singular ) {

			$query_vars['style'] = 'printme';
			if ( get_query_var('page') ) {
				$query_vars['page'] = esc_html( get_query_var( 'page' ) );
			}
			if ( get_query_var( 'cpage' ) ) {
				$query_vars['cpage'] = esc_html( get_query_var( 'cpage' ) );
			}

			$buttons['print'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> add_query_arg( $query_vars, get_permalink( $post->ID ) ),
				'icon_class'	=> 'el-icon-print',
				'tooltip'		=> __( 'Print preview', 'fastfood' )
			);

		}

		// ------- Leave a comment -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_comment' ) && $args['comment'] && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { 

			$buttons['comment'] = array(
				'class'			=> '',
				'a_class'		=> 'show-comment-form',
				'a_rel'			=> '',
				'a_href'		=> '#respond',
				'icon_class'	=> 'el-icon-comment',
				'tooltip'		=> __( 'Leave a comment', 'fastfood' )
			);

		}

		// ------- RSS feed -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_feed' ) && $args['feed'] && $is_singular && comments_open( $post->ID ) && !post_password_required() ) { 

			$buttons['feed'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> get_post_comments_feed_link( $post->ID, 'rss2' ),
				'icon_class'	=> 'el-icon-rss',
				'tooltip'		=> __( 'feed for comments on this post', 'fastfood' )
			);

		}

		// ------- Trackback -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_trackback' ) && $args['trackback'] && $is_singular && pings_open() ) { 

			$buttons['trackback'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> 'trackback',
				'a_href'		=> get_trackback_url(),
				'icon_class'	=> 'el-icon-refresh',
				'tooltip'		=> __( 'Trackback URL', 'fastfood' )
			);

		}

		// ------- Home -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_home' ) && $args['home'] ) { 

			$buttons['home'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> home_url(),
				'icon_class'	=> 'el-icon-home',
				'tooltip'		=> __( 'Home', 'fastfood' )
			);

		}

		// ------- Back to parent post -------
		if ( $is_image && !empty( $post->post_parent ) ) { 

			$buttons['image'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> 'gallery',
				'a_href'		=> get_permalink( $post->post_parent ),
				'icon_class'	=> 'el-icon-hand-left',
				'tooltip'		=> sprintf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) )
			);

		}

		// ------- Previous/Next image -------
		if ( $is_image ) { 

			$images = fastfood_get_prevnext_images( $post->ID );

			if ( $images['prev'] )
				$buttons['prevpost'] = array(
					'class'			=> '',
					'a_class'		=> '',
					'a_rel'			=> 'prev',
					'a_href'		=> get_attachment_link( $images['prev'] ),
					'icon_class'	=> 'el-icon-chevron-left',
					'tooltip'		=> __( 'Previous Image', 'fastfood' )
				);

			if ( $images['next'] )
				$buttons['nextpost'] = array(
					'class'			=> '',
					'a_class'		=> '',
					'a_rel'			=> 'next',
					'a_href'		=> get_attachment_link( $images['next'] ),
					'icon_class'	=> 'el-icon-chevron-right',
					'tooltip'		=> __( 'Next Image', 'fastfood' )
				);

		}

		// ------- Previous post -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_nextprev' ) && $args['next_prev'] && $is_post && get_previous_post() ) { 

			$buttons['prevpost'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> get_permalink( get_previous_post() ),
				'icon_class'	=> 'el-icon-chevron-left',
				'tooltip'		=> sprintf( __( 'Previous Post', 'fastfood' ) . ': %s', get_the_title( get_previous_post() ) )
			);

		}

		// ------- Next post -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_nextprev' ) && $args['next_prev'] && $is_post && get_next_post() ) { 

			$buttons['nextpost'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> get_permalink( get_next_post() ),
				'icon_class'	=> 'el-icon-chevron-right',
				'tooltip'		=> sprintf( __( 'Next Post', 'fastfood' ) . ': %s', get_the_title( get_next_post() ) )
			);

		}

		// ------- Older Posts -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_newold' ) && $args['next_prev'] && !$is_singular && !fastfood_is_allcat() && get_next_posts_link() ) { 

			$max_page = $wp_query->max_num_pages;

			$buttons['oldposts'] = array(
				'class'			=> 'nb-nextprev',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> next_posts( $max_page, false ),
				'icon_class'	=> 'el-icon-chevron-left',
				'tooltip'		=> __( 'Older Posts', 'fastfood' )
			);

		}

		// ------- Newer Posts -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_newold' ) && $args['next_prev'] && !$is_singular && !fastfood_is_allcat() && get_previous_posts_link() ) { 

			$buttons['newposts'] = array(
				'class'			=> 'nb-nextprev',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> previous_posts( false ),
				'icon_class'	=> 'el-icon-chevron-right',
				'tooltip'		=> __( 'Newer Posts', 'fastfood' )
			);

		}

		// ------- Top -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_topbottom' ) && $args['up_down'] ) { 

			$buttons['up'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> '#',
				'icon_class'	=> 'el-icon-chevron-up',
				'tooltip'		=> __( 'Top of page', 'fastfood' )
			);

		}

		// ------- Bottom -------
		if ( FastfoodOptions::get_opt( 'fastfood_navbuttons_topbottom' ) && $args['up_down'] ) { 

			$buttons['down'] = array(
				'class'			=> '',
				'a_class'		=> '',
				'a_rel'			=> '',
				'a_href'		=> '#footer',
				'icon_class'	=> 'el-icon-chevron-down',
				'tooltip'		=> __( 'Bottom of page', 'fastfood' )
			);

		}

		/* custom buttons can be easily added to navbar using filters. eg:

			add_filter( 'fastfood_filter_navbuttons', 'fastfood_add_my_button' );

			function fastfood_add_my_button( $buttons ) {
				$buttons['my-button'] = array(
					'class'			=> 'my_button_class',
					'a_class'		=> 'my_button_anchor_class',
					'a_rel'			=> 'my_button_anchor_rel',
					'a_href'		=> 'my_button_anchor_href',
					'icon_class'	=> 'elusive_icon_class',
					'tooltip'		=> 'my button tooltip text'
				);
				return $buttons;
			}

		*/

		$buttons = apply_filters( 'fastfood_filter_navbuttons', $buttons );

		if ( !$buttons ) return;
		?>

			<div id="navbuttons">
				<?php
					foreach ( $buttons as $button ) {

						$button['class'] = $button['class'] ? ' ' . esc_attr( trim( $button['class'] ) ) : '';
						$button['icon_class'] = $button['icon_class'] ? ' ' . esc_attr( trim( $button['icon_class'] ) ) : '';
						$button['tooltip'] = esc_html( $button['tooltip'] );
				?>

				<div class="minibutton<?php echo $button['class']; ?>">
					<?php
						echo fastfood_build_link( array( 
							'href'	=> $button['a_href'],
							'class'	=> $button['a_class'],
							'rel'	=> $button['a_rel'],
							'text'	=> '<i class="minib_img' . $button['icon_class'] . '"></i>',
						) );
					?>
					<span class="nb_tooltip"><?php echo $button['tooltip']; ?></span>
				</div>

				<?php
					}
				?>
			</div><!-- #navbuttons -->

		<?php

	}


	/**
	 * Display Recent Comments
	 *
	 * @since Fastfood 0.37
	 */
	function qbar_recent_comments() {

		$args = array(
			'status'	=> 'approve',
			'number'	=> apply_filters( 'fastfood_filter_get_recentcomments_number', 10 ),
			'type'		=> 'comment'
		);
		$comments = get_comments( $args ); // valid type values (not documented) : 'pingback','trackback','comment'

		if ( $comments ) {

			?>

				<ul class="solid_ul">

					<?php
						foreach ( $comments as $comment ) {
							//if( post_password_required( get_post( $comment->comment_post_ID ) ) ) { continue; } // uncomment to skip comments on protected posts. Hi Emma ;)
							$post = get_post( $comment->comment_post_ID );
							setup_postdata( $post );

							$post_title_short = mb_strimwidth( get_the_title( $post->ID ), 0, 35, '&hellip;' );

							if ( post_password_required( $post ) ) {
								//hide comment author in protected posts
								$com_auth = __( 'someone', 'fastfood' );
							} else {
								//trim the comment author if > 20 chars
								$com_auth = mb_strimwidth( $comment->comment_author, 0, 20, '&hellip;' );
							}
							?>

								<li>
									<?php
										echo sprintf( '<span class="label">' . apply_filters( 'fastfood_filter_comment_by', __( '%1$s about %2$s', 'fastfood' ) ) . '</span>',
											$com_auth,
											fastfood_build_link( array( 'href' => get_comment_link( $comment ), 'text' => $post_title_short ) )
										);
									?>
									<div class="quickbar-panel-subcontent">
										<div class="quickbar-panel-title"><i class="el-icon-eye-open"></i></div>
											<div class="preview">
												<?php
													if ( post_password_required( $post ) ) {
														echo '<i class="el-icon-lock"></i> [' . __( 'No preview: this is a comment of a protected post', 'fastfood' ) . ']';
													} else {echo get_avatar( $comment, 32 );
														comment_excerpt( $comment->comment_ID );
													}
												?>
											</div>
									</div>
								</li>

							<?php
						}
					?>

				</ul>

			<?php

		} else {

			?>

				<ul>
					<li><?php _e( 'No comments yet.', 'fastfood' ); ?></li>
				</ul>

			<?php

		}

		wp_reset_postdata();

	}


	/**
	 * Display Recent Posts
	 *
	 * @since Fastfood 0.37
	 */
	function qbar_recent_posts() {

		$args = array(
			'ignore_sticky_posts' => 'true',
			'post_status' => 'publish',
			'posts_per_page' => apply_filters( 'fastfood_filter_get_recententries_number', 10 ),
			'no_found_rows' => 'true'
		);
		$r = new WP_Query( $args );

		if ( $r->have_posts() ) {

			?>

				<ul class="solid_ul">

					<?php
						while ( $r->have_posts() ) {
							$r->the_post();

							//trim the post title if > 35 chars
							$post_title_short = mb_strimwidth( get_the_title(), 0, 35, '&hellip;' );

							//trim the post author if > 20 chars
							$post_auth = mb_strimwidth( get_the_author(), 0, 20, '&hellip;' );
							?>

								<li>
									<?php
										echo sprintf( '<span class="label">' . apply_filters( 'fastfood_filter_post_by_author',  __( '%1$s by %2$s', 'fastfood' ) ) . '</span>',
											fastfood_build_link( array( 'href' => get_permalink(), 'text' => $post_title_short, 'title' => the_title_attribute( array( 'echo' => 0 ) ) ) ),
											$post_auth
										);
									?>
									<div class="quickbar-panel-subcontent">
										<div class="quickbar-panel-title"><i class="el-icon-eye-open"></i></div>
										<div class="preview">
											<?php
												if ( post_password_required() ) {
													echo '<i class="el-icon-lock"></i> [' . __( 'No preview: this is a protected post', 'fastfood' ) . ']';
												} else {
													echo get_the_post_thumbnail( get_the_ID(), array( 32,32 ), array( 'class' => 'alignleft' ) );
													the_excerpt();
												}
											?>
										</div>
									</div>
								</li>

							<?php

						}
					?>

				</ul>

			<?php

		}

		wp_reset_postdata();

	}


	/**
	 * Display Popular Categories (with related posts)
	 *
	 * @since Fastfood 0.37
	 */
	function qbar_popular_categories() {

		$args = array(
			'orderby'	=> 'count',
			'number'	=> apply_filters( 'fastfood_filter_get_categories_wpr_number', 9 ),
			'order'		=> 'DESC'
		);
		$categories = get_categories( $args );

		if ( !$categories ) return;

		?>

			<ul class="solid_ul">

				<?php foreach( $categories as $category ) { ?>

					<li>
						<?php
							echo '<span class="label">' . fastfood_build_link( array( 
								'href' => get_category_link( $category->term_id ),
								'text' => $category->name,
								'title' => sprintf( __( "View all posts in %s", 'fastfood' ), $category->name )
							) ) . ' (' . $category->count . ')' . '</span>';
						?>
						<div class="quickbar-panel-subcontent">
							<div class="quickbar-panel-title"><?php _e( 'Recent Posts', 'fastfood' ); ?></div>
							<ul class="solid_ul">
								<?php
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
								?>

								<li>
									<?php
										echo '<span class="label">' . sprintf( apply_filters( 'fastfood_filter_post_by_author',  __( '%1$s by %2$s', 'fastfood' ) ),
											fastfood_build_link( array( 'href' => get_permalink( $post->ID ), 'text' => $post_title_short, 'title' => the_title_attribute( array( 'echo' => 0, 'post' => $post->ID ) ) ) ),
											$post_auth
										) . '</span>';
									?>
								</li>

								<?php
									}
								?>

							</ul>
						</div>
					</li>

				<?php } ?>

			</ul>

		<?php

		wp_reset_postdata();

	}


	/**
	 * Display User Links
	 *
	 * @since Fastfood 0.37
	 */
	function qbar_user() {
		global $post, $current_user;

		?>

			<div id="logged">
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
			</div>
			<ul class="solid_ul">
				<?php if ( !is_user_logged_in() || current_user_can( 'read' ) ) { wp_register( '<li><span class="label">', '</span></li>' ); }?>
				<?php if ( is_user_logged_in() ) { ?>
					<?php if ( current_user_can( 'read' ) ) { ?>
						<li><span class="label"><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile', 'fastfood' ); ?></a></span></li>
						<?php if ( current_user_can( 'publish_posts' ) ) { ?>
							<li><span class="label"><a title="<?php _e( 'Add New Post', 'fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post', 'fastfood' ); ?></a></span></li>
						<?php } ?>
						<?php if ( current_user_can( 'moderate_comments' ) ) {
							$awaiting_mod = wp_count_comments();
							$awaiting_mod = $awaiting_mod->moderated;
							$awaiting_mod = $awaiting_mod ? ' (' . number_format_i18n( $awaiting_mod ) . ')' : '';
						?>
							<li><span class="label"><a title="<?php _e( 'Comments', 'fastfood' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'fastfood' ); ?></a><?php echo $awaiting_mod; ?></span></li>
						<?php } ?>
					<?php } ?>
					<li><span class="label"><a title="<?php _e( 'Log out', 'fastfood' ); ?>" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e( 'Log out', 'fastfood' ); ?></a></span></li>
				<?php } ?>
				<?php if ( !is_user_logged_in() ) {?>
					<li class="qb-login-link">
						<span class="label"><a title="<?php _e( 'Log in', 'fastfood' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in', 'fastfood' ); ?></a></span>
						<?php $this->mini_login(); ?>
					</li>
				<?php } ?>
			</ul>

		<?php

	}


	/**
	 * Display a small login module
	 *
	 * @since Fastfood 0.37
	 */
	function mini_login() {

		if ( !FastfoodOptions::get_opt( 'fastfood_qbar_minilogin' ) || class_exists("siCaptcha") ) return;

		$args = array(
			'redirect'		=> home_url(),
		);

		?>

			<div id="minilogin" class="quickbar-panel-subcontent">

				<div class="quickbar-panel-title"><?php _e( 'Log in', 'fastfood' ); ?></div>

				<div class="preview">
					<?php wp_login_form($args); ?>
				</div>

			</div>

		<?php

	}

}

new Fastfood_Fixed_Footer;

