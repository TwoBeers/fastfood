
<!-- begin footer -->
			</div><!-- close sidebar or posts_wide -->

			<?php
				global $current_user, $fastfood_opt, $fastfood_is_allcat_page, $fastfood_version;
				if ( 
					( !is_page() && !is_single() && !is_attachment() ) ||
					( is_page() && ( $fastfood_opt['fastfood_rsidebpages'] == 1 ) ) ||
					( is_single() && ( $fastfood_opt['fastfood_rsidebposts'] == 1  && !is_attachment() ) )
				) get_sidebar(); // show sidebar
			?>

			<div id="footer">

				<?php	get_sidebar( 'footer' ); ?>

				<small>&copy; <?php echo date( 'Y' ); ?>  <strong><?php bloginfo( 'name' ); ?></strong> <?php _e( 'All rights reserved','fastfood' ); ?><?php if ( $fastfood_opt['fastfood_tbcred'] == 1 ) { ?> - Fastfood theme <?php if( !empty( $fastfood_version ) ) { echo 'v' . $fastfood_version; } ?> by <a href="http://www.twobeers.net/" title="<?php _e( 'Visit author homepage' ); ?> @ TwoBeers.net">TwoBeers Crew</a><?php } ?> - Powered by <a href="http://wordpress.org/" title="<?php _e( 'Powered by WordPress' ); ?>">WordPress</a></small>
				<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

			</div><!-- close footer -->

		</div><!-- close content -->

		<div id="fixedfoot">
			<?php
				if ( $fastfood_opt['fastfood_qbar'] == 1 ) {
			?>
				<!-- begin quickbar -->
				<div id="quickbar">
					<!-- quickbar tool - uncomment to use
						<div class="menutoolitem">
							<div class="itemimg menutool_trig" style="background-image: url('<?php echo get_bloginfo( 'stylesheet_directory' ); ?>/images/qbartool.png');"></div>
							<div class="menutool">[put here your code]</div>
						</div>
					quickbar tool -->
					<br />
					<?php if ( $fastfood_opt['fastfood_qbar_recpost'] == 1 ) { // recent posts menu ?>
						<div class="menuitem">
							<div id="mii_rpost" class="itemimg"></div>
							<div class="menuback">
								<div class="menulcont">
									<div class="mentit"><?php _e( 'Recent Posts' ); ?></div>
									<ul class="solid_ul">
										<?php get_fastfood_recententries() ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ( $fastfood_opt['fastfood_qbar_cat'] == 1 ) { // popular categories menu ?>
						<div class="menuitem">
							<div id="mii_pcats" class="itemimg"></div>
							<div class="menuback">
								<div class="menulcont">
									<div class="mentit"><?php _e( 'Categories' ); ?></div>
									<ul class="solid_ul">
										<?php get_fastfood_categories_wpr(); ?>
										<li style="text-align: right; margin:16px 0 10px;"><a title="<?php _e( 'View all categories' ); ?>" href="<?php echo home_url(); ?>/?allcat=y"><?php _e( 'More...' ); ?></a></li>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ( $fastfood_opt['fastfood_qbar_reccom'] == 1 ) { // recent comments menu ?>
						<div class="menuitem">
							<div id="mii_rcomm" class="itemimg"></div>
							<div class="menuback">
								<div class="menulcont">
									<div class="mentit"><?php _e( 'Recent Comments' ); ?></div>
									<ul class="solid_ul">
										<?php get_fastfood_recentcomments(); ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ( $fastfood_opt['fastfood_qbar_user'] == 1 ) { // user links menu ?>
						<div class="menuitem">
							<div id="mii_cuser" class="itemimg"></div>
							<div class="menuback">
								<div class="menulcont">
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
										<?php if ( ! is_user_logged_in() || current_user_can( 'read' ) ) { wp_register(); }?>
										<?php if ( is_user_logged_in() ) { ?>
											<?php if ( current_user_can( 'read' ) ) { ?>
												<li><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile' ); ?></a></li>
												<?php if ( current_user_can( 'publish_posts' ) ) { ?>
													<li><a title="<?php _e( 'Add New Post' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post' ); ?></a></li>
												<?php } ?>
												<?php if ( current_user_can( 'moderate_comments' ) ) { ?>
													<li><a title="<?php _e( 'Comments' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments' ); ?></a></li>
												<?php } ?>
											<?php } ?>
											<li><a title="<?php _e( 'Log out' ); ?>" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e( 'Log out' ); ?></a></li>
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
			<?php } ?>

			<div id="statusbar">
				<?php _e( 'Welcome' ); ?> <?php if ( is_user_logged_in() ) { echo $current_user->display_name; } ?>, <?php _e( 'today is ', 'fastfood' ); echo date_i18n( 'l' ); ?> <?php echo date_i18n( __( 'F j, Y' ) ); ?>
			</div>

			<div id="navbuttons">
				<?php if ( is_singular() ) { ?>

					<div class="minibutton">
						<a href="<?php
							$arr_params['style'] = 'printme';
							if ( get_query_var('page') ) {
								$arr_params['page'] = esc_html( get_query_var( 'page' ) );
							}
							if ( get_query_var('cpage') ) {
								$arr_params['cpage'] = esc_html( get_query_var( 'cpage' ) );
							}
							echo add_query_arg( $arr_params, get_permalink() );
							?>">
							<span class="minib_img" style="background-position: center 0px;">&nbsp;</span>
							<span class="nb_tooltip"><?php _e( 'Print' ); ?></span>
						</a>
					</div>

					<?php if ( comments_open( $post->ID ) && !post_password_required() ) { ?>

						<div class="minibutton">
							<a href="#respond" title="<?php _e( 'Leave a comment' ); ?>"<?php if ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) { echo ' onclick="return addComment.viewForm()"'; } ?> >
								<span class="minib_img" style="background-position: center -24px; border-top:1px solid #999999;">&nbsp;</span>
								<span class="nb_tooltip"><?php _e( 'Leave a comment' ); ?></span>
							</a>
						</div>

						<div class="minibutton">
							<a href="<?php echo get_post_comments_feed_link( $post->ID, 'rss2' ); ?> " title="<?php _e( 'feed for comments on this post', 'fastfood' ); ?>">
								<span class="minib_img" style="background-position: center -48px; border-top:1px solid #999999;">&nbsp;</span>
								<span class="nb_tooltip"><?php _e( 'feed for comments on this post', 'fastfood' ); ?></span>
							</a>
						</div>

						<?php if ( pings_open() ) { ?>

							<div class="minibutton">
								<a href="<?php global $tmptrackback; echo $tmptrackback; ?>" rel="trackback" title="Trackback URL">
									<span class="minib_img" style="background-position: center -72px;">&nbsp;</span>
									<span class="nb_tooltip"><?php _e( 'Trackback URL' ); ?></span>
								</a>
							</div>

						<?php
						}
					}
					?>

					<div class="minibutton">
						<a href="<?php echo home_url(); ?>" title="home">
							<span class="minib_img" style="background-position: center -96px; border-top:1px solid #999999;">&nbsp;</span>
							<span class="nb_tooltip"><?php _e( 'Home' ); ?></span>
						</a>
					</div>

					<?php if ( is_page() ) { 
						$page_nav_links = fastfood_page_navi($post->ID); // get the menu-ordered prev/next pages links
						if ( isset ( $page_nav_links['prev'] ) ) { // prev page link ?>
							<div class="minibutton">
								<a href="<?php echo $page_nav_links['prev']['link']; ?>" title="<?php echo $page_nav_links['prev']['title']; ?>">
									<span class="minib_img" style="background-position: center -120px;">&nbsp;</span>
									<span class="nb_tooltip"><?php echo __( 'Previous page' ) . ': ' . $page_nav_links['prev']['title']; ?></span>
								</a>
							</div>
						<?php }
						if ( isset ( $page_nav_links['next'] ) ) { // next page link ?>
							<div class="minibutton">
								<a href="<?php echo $page_nav_links['next']['link']; ?>" title="<?php echo $page_nav_links['next']['title']; ?>">
									<span class="minib_img" style="background-position: center -144px;">&nbsp;</span>
									<span class="nb_tooltip"><?php echo __( 'Next page' ) . ': ' . $page_nav_links['next']['title']; ?></span>
								</a>
							</div>
						<?php } ?>
					<?php } elseif ( is_attachment() ) { ?>
						<?php if ( !empty( $post->post_parent ) ) { ?>
							<div class="minibutton">
								<a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php esc_attr( printf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) ) ); ?>" rel="gallery">
									<span class="minib_img" style="background-position: center -144px;">&nbsp;</span>
									<span class="nb_tooltip"><?php esc_attr( printf( __( 'Return to %s', 'fastfood' ), get_the_title( $post->post_parent ) ) ); ?></span>
								</a>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div class="minibutton">
							<?php next_post_link( '%link', '<span class="minib_img" style="background-position: center -120px;">&nbsp;</span><span class="nb_tooltip">' . __( 'Next Post' ) . ': %title</span>' ); ?>
						</div>

						<div class="minibutton">
							<?php previous_post_link( '%link', '<span class="minib_img" style="background-position: center -144px;">&nbsp;</span><span class="nb_tooltip">' . __( 'Previous Post' ) . ': %title</span>' ); ?>
						</div>
					<?php } ?>

				<?php } else {?>

					<div class="minibutton">
						<a href="<?php echo home_url(); ?>" title="home">
							<span class="minib_img" style="background-position: center -96px;">&nbsp;</span>
							<span class="nb_tooltip"><?php _e( 'Home' ); ?></span>
						</a>
					</div>

					<?php
					if( !isset( $fastfood_is_allcat_page ) || !$fastfood_is_allcat_page ) {
					?>
						<div class="minibutton">
							<?php next_posts_link( '<span class="minib_img" style="background-position: center -144px;">&nbsp;</span><span class="nb_tooltip">' . __('Older Posts','fastfood') . '</span>' ); ?>
						</div>
						<div class="minibutton">
							<?php previous_posts_link( '<span class="minib_img" style="background-position: center -120px;">&nbsp;</span><span class="nb_tooltip">' . __('Newer Posts','fastfood') . '</span>' ); ?>
						</div>
					<?php
					}
				} ?>

				<div class="minibutton">
					<a href="#" title="<?php _e( 'Top of page', 'fastfood' ); ?>">
						<span class="minib_img" style="background-position: center -168px; border-top:1px solid #999999;">&nbsp;</span>
						<span class="nb_tooltip"><?php _e( 'Top of page', 'fastfood' ); ?></span>
					</a>
				</div>

				<div class="minibutton">
					<a href="#footer" title="<?php _e( 'Bottom of page', 'fastfood' ); ?>">
						<span class="minib_img" style="background-position: center -192px;">&nbsp;</span>
						<span class="nb_tooltip"><?php _e( 'Bottom of page', 'fastfood' ); ?></span>
					</a>
				</div>
			</div>
		</div>

		</div><!-- close main -->

		<?php wp_footer(); ?>
	</body>
</html>