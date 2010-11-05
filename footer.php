
<!-- begin footer -->
			</div><!-- close sidebar or posts_wide -->

			<?php
			if ( !is_singular() ) {
				get_sidebar();
			}
			?>

			<div id="footer">

				<?php	get_sidebar( 'footer' ); ?>

				<small>&copy; <?php echo date( 'Y' ); ?>  <strong><?php bloginfo( 'name' ); ?></strong> <?php _e( 'All rights reserved','fastfood' ); ?> - Fastfood theme <?php global $fastfood_version; if( !empty( $fastfood_version ) ) { echo 'v' . $fastfood_version; } ?> by <a href="http://www.twobeers.net/" title="<?php _e( 'Visit author homepage' ); ?> @ TwoBeers.net">TwoBeers Crew</a> - Powered by <a href="http://wordpress.org/" title="<?php _e( 'Powered by WordPress' ); ?>">WordPress</a></small>
				<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

			</div><!-- close footer -->

		</div><!-- close content -->

		<!-- begin quickbar -->
		<div id="quickbar">

			<?php
			global $current_user, $fastfood_opt, $fastfood_allcat;
			if ( $fastfood_opt['fastfood_qbar'] == 'show' ) {
			?>

			<div class="menuitem">
				<div  class="itemimg" style="bottom: 185px; background-image: url('<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>post.png');"></div>
				<div class="menuback">
					<div class="menulcont">
						<div class="menuitem_1ul">
							<div class="mentit"><?php _e( 'Recent Posts' ); ?></div>
							<ul class="solid_ul">
								<?php get_fastfood_recententries() ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="menuitem">
				<div  class="itemimg" style="bottom: 135px; background-image: url('<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>cat.png');"></div>
				<div class="menuback">
					<div class="menulcont">
						<div class="menuitem_1ul">
						<div class="mentit"><?php _e( 'Categories' ); ?></div>
							<ul class="solid_ul">
								<?php get_fastfood_categories_wpr(); ?>
								<li style="text-align: right; margin:16px 0 10px;"><a title="<?php _e( 'View all categories' ); ?>" href="<?php echo home_url(); ?>/?allcat=y"><?php _e( 'More...' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="menuitem">
				<div  class="itemimg" style="bottom: 85px; background-image: url('<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>comm.png');"></div>
				<div class="menuback">
					<div class="menulcont">
						<div class="menuitem_1ul">
						<div class="mentit"><?php _e( 'Recent Comments' ); ?></div>
							<ul class="solid_ul">
								<?php get_fastfood_recentcomments(); ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="menuitem">
				<div  class="itemimg" style="bottom: 35px; background-image: url('<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>user2.png');"></div>
				<div class="menuback">
					<div class="menulcont">
						<div class="menuitem_1ul">
						<div class="mentit"><?php _e( 'User','fastfood' ); ?></div>
							<ul class="solid_ul">
								<li id="logged">
									<?php
									if (is_user_logged_in()) { //fix for notice when user not log-in
										get_currentuserinfo();
										$email = $current_user->user_email;
										echo get_avatar( $email, 50, $default=get_bloginfo( 'stylesheet_directory' ) . '/images/user.png','user-avatar' );
										printf( __( 'Logged in as %s','fastfood' ), '<strong>' . $current_user->display_name . '</strong>' );
									} else {
										echo get_avatar( 'dummyemail', 50, $default=get_bloginfo( 'stylesheet_directory' ) . '/images/user.png','user-avatar' );
										echo __( 'Not logged in','fastfood' );
									}
									?>
								</li>
								<?php wp_register(); ?>
								<?php if ( is_user_logged_in() ) {?>
								<li><a href="<?php echo home_url(); ?>/wp-admin/profile.php"><?php _e( 'Your Profile' ); ?></a></li>
								<li><a title="<?php _e( 'Add New Post' ); ?>" href="<?php echo home_url(); ?>/wp-admin/post-new.php"><?php _e( 'New Post' ); ?></a></li>
								<?php } ?>
								<li><?php wp_loginout(); ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

		<?php } ?>

			<div id="statusbar">
				<?php
				_e( 'Welcome' );
				echo ' ';
				if ( is_user_logged_in() ) {
					echo $current_user->display_name;
				}
				echo ', ';
				_e('Today is ','fastfood');
				echo date_i18n( 'l' ) . ' ' . date_i18n( __( 'F j, Y' ) );
				?>
			</div>

			<div id="navbuttons">
				<?php if ( is_singular() ) { ?>

					<div class="minibutton">
						<a href="<?php
							the_permalink();
							if ( strchr( get_permalink(),'?' ) ) {
								echo '&style=printme';
							} else {
								echo '?style=printme';
							}
							if ( get_query_var('page') ) {
								echo '&page=' . esc_html( get_query_var('page') );
							}
							if ( get_query_var('cpage') ) {
								echo '&cpage=' . esc_html( get_query_var('cpage') );
							}
							?>">
							<img alt="Print" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>printer.png" />
							<span class="nb_tooltip"><?php _e( 'Print' ); ?></span>
						</a>
					</div>

					<?php if ( ( comments_open( $post->ID ) ) && ( ! post_password_required() ) ) { ?>

						<div class="minibutton">
							<a href="#respond" title="<?php _e( 'Leave a comment' ); ?>" style="border-top:1px solid #999999;" onclick="return addComment.viewForm()" >
								<img alt="Leave a comment" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>comment_bubble.png" />
								<span class="nb_tooltip"><?php _e( 'Leave a comment' ); ?></span>
							</a>
						</div>

						<div class="minibutton">
							<a href="<?php echo get_post_comments_feed_link( $post->ID, 'rss2' ); ?> " title="<?php _e( 'feed for comments on this post','fastfood' ); ?>" style="border-top:1px solid #999999;">
								<img alt="feed" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>rss_feed.png" />
								<span class="nb_tooltip"><?php _e( 'feed for comments on this post','fastfood' ); ?></span>
							</a>
						</div>

						<?php if ( pings_open() ) { ?>

							<div class="minibutton">
								<a href="<?php global $tmptrackback; echo $tmptrackback; ?>" rel="trackback" title="Trackback URL">
									<img alt="Trackback" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>trackback.png" />
									<span class="nb_tooltip"><?php _e( 'Trackback URL' ); ?></span>
								</a>
							</div>

						<?php
						}
					}
					?>

					<div class="minibutton">
						<a href="<?php echo home_url(); ?>" title="home" style="border-top:1px solid #999999;">
							<img alt="home" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>home.png" />
							<span class="nb_tooltip"><?php _e( 'Home' ); ?></span>
						</a>
					</div>

					<?php if ( ! is_attachment() ) { ?>
						<div class="minibutton">
							<?php next_post_link( '%link', '<img title="' . __( 'Next Post' ) . ': %title" alt="Next Post" src="' . get_bloginfo( "stylesheet_directory" ) . '/images/' . 'left_arrow.png" /><span class="nb_tooltip">' . __( 'Next Post' ) . ': %title</span>' ); ?>
						</div>

						<div class="minibutton">
							<?php previous_post_link( '%link', '<img title="' . __( 'Previous Post' ) . ': %title" alt="Previous Post" src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/' . 'right_arrow.png" /><span class="nb_tooltip">' . __( 'Previous Post' ) . ': %title</span>' ); ?>
						</div>
					<?php } else { ?>
						<?php if ( ! empty( $post->post_parent ) ) { ?>
							<div class="minibutton">
								<a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php esc_attr( printf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) ) ); ?>" rel="gallery">
									<img alt="<?php esc_attr( printf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) ) ); ?>" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/'; ?>right_arrow.png" />
									<span class="nb_tooltip"><?php esc_attr( printf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) ) ); ?></span>
								</a>
							</div>
						<?php } ?>
					<?php } ?>

				<?php } else {?>

					<div class="minibutton">
						<a href="<?php echo home_url(); ?>" title="home">
							<img alt="home" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>home.png" />
							<span class="nb_tooltip"><?php _e( 'Home' ); ?></span>
						</a>
					</div>

					<?php
					if( !isset( $fastfood_allcat ) || ( $fastfood_allcat != 'y' ) ) {
					?>
						<div class="minibutton">
							<?php next_posts_link('<img src="' . get_bloginfo('stylesheet_directory') . '/images/right_arrow.png" alt="Previous Posts" title="'. __('Older Posts','fastfood').'" /><span class="nb_tooltip">' . __('Older Posts','fastfood') . '</span>'); ?>
						</div>
						<div class="minibutton">
							<?php previous_posts_link('<img src="' . get_bloginfo('stylesheet_directory') . '/images/left_arrow.png" alt="Next Posts" title="'. __('Newer Posts','fastfood').'" /><span class="nb_tooltip">' . __('Newer Posts','fastfood') . '</span>'); ?>
						</div>
					<?php
					}
				} ?>

				<div class="minibutton">
					<a href="#" title="<?php _e( 'Top of page','fastfood' ); ?>" style="border-top:1px solid #999999;">
						<img alt="Top" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>up_arrow.png" />
						<span class="nb_tooltip"><?php _e( 'Top of page','fastfood' ); ?></span>
					</a>
				</div>

				<div class="minibutton">
					<a href="#footer" title="<?php _e( 'Bottom of page','fastfood' ); ?>">
						<img alt="Bottom" src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/images/' ?>down_arrow.png" />
						<span class="nb_tooltip"><?php _e( 'Bottom of page','fastfood' ); ?></span>
					</a>
				</div>

			</div>
		</div>

		</div><!-- close main -->

		<?php wp_footer(); ?>
	</body>
</html>