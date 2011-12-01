<?php global $current_user, $fastfood_opt, $fastfood_version, $post, $ff_is_printpreview; ?>

<!-- begin footer -->
				<?php fastfood_hook_before_footer(); ?>
				<div id="footer">
					<?php fastfood_hook_footer(); ?>
					<?php wp_nav_menu( array( 'container_class' => 'ff-menu', 'container_id' => 'secondary2', 'fallback_cb' => false, 'theme_location' => 'secondary2', 'depth' => 1 ) ); ?>
					<?php get_sidebar( 'footer' ); ?>

					<small>&copy; <?php echo date( 'Y' ); ?>  <strong><?php bloginfo( 'name' ); ?></strong> <?php _e( 'All rights reserved','fastfood' ); ?><?php if ( $fastfood_opt['fastfood_tbcred'] == 1 ) { ?> - Fastfood theme by <a href="http://www.twobeers.net/" title="<?php _e( 'Visit author homepage','fastfood' ); ?> @ TwoBeers.net">TwoBeers Crew</a><?php } ?> - Powered by <a href="http://wordpress.org/" title="<?php _e( 'Powered by WordPress','fastfood' ); ?>">WordPress</a></small>
					<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

				</div><!-- close footer -->
				<?php fastfood_hook_after_footer(); ?>

			</div><!-- close content -->

			<div id="fixedfoot">
				<?php if ( $fastfood_opt['fastfood_qbar'] == 1 ) fastfood_quickbar(); ?>
				<?php if ( $fastfood_opt['fastfood_statusbar'] == 1 ) { ?><div id="statusbar"><?php _e( 'Welcome','fastfood' ); ?> <?php if ( is_user_logged_in() ) { echo $current_user->display_name; } ?>, <?php printf( __('today is %1$s, %2$s','fastfood'), date_i18n( __( 'l','fastfood' ) ), date_i18n( get_option( 'date_format' ) ) ); ?></div><?php } ?>
				<?php if ( $fastfood_opt['fastfood_navbuttons'] == 1 ) fastfood_navbuttons();	?>
			</div>
			
			<?php if ( $ff_is_printpreview ) { ?>
			<div id="close_preview">
				<a id="close_button" title="<?php _e( 'Close','fastfood' ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php _e( 'Close','fastfood' ); ?></a>
				<a href="javascript:window.print()" title="<?php _e( 'Print','fastfood' ); ?>" id="print_button" class="hide-if-no-js"><?php _e( 'Print','fastfood' ); ?></a>
			</div>
			<?php } ?>

		</div><!-- close main -->


		<!-- info: 
			<?php 
				global $fastfood_version; 
				echo ' | WP version - ' . get_bloginfo ( 'version' );
				echo ' | WP language - ' . get_bloginfo ( 'language' );
				if ( phpversion() ) echo ' | PHP v' . phpversion();
				foreach ( $fastfood_opt as $key => $val ) { echo ' | ' . $key . ' - ' . $val; };
			?>
			
		-->

		<?php wp_footer(); ?>
	</body>
</html>