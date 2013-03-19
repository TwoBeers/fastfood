<?php global $post; ?>

<!-- begin footer -->
				<?php fastfood_hook_footer_before(); ?>

				<div id="footer">

					<?php fastfood_hook_footer_top(); ?>

					<?php wp_nav_menu( array( 'container_class' => 'ff-menu', 'container_id' => 'secondary2', 'fallback_cb' => false, 'theme_location' => 'secondary2', 'depth' => 1 ) ); ?>

					<?php fastfood_get_sidebar( 'footer' ); ?>

					<?php fastfood_credits(); ?>

					<?php fastfood_hook_footer_bottom(); ?>

				</div><!-- close footer -->

				<?php fastfood_hook_footer_after(); ?>

			</div><!-- close content -->

			<?php fastfood_fixed_footer(); ?>

			<?php if ( fastfood_is_printpreview() ) { ?>
			<div id="close_preview">
				<a id="close_button" title="<?php _e( 'Close','fastfood' ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php _e( 'Close','fastfood' ); ?></a>
				<a href="javascript:window.print()" title="<?php _e( 'Print','fastfood' ); ?>" id="print_button" class="hide-if-no-js"><?php _e( 'Print','fastfood' ); ?></a>
			</div>
			<?php } ?>

		</div><!-- close main -->

		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

		<?php fastfood_hook_body_bottom(); ?>

		<?php wp_footer(); ?>

	</body>

</html>