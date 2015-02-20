			</div><!-- close content -->

			<?php fastfood_hook_content_after(); ?>

			<?php fastfood_hook_footer_before(); ?>

			<div id="footer"><!-- begin footer -->

				<?php fastfood_hook_footer_top(); ?>

				<?php
					wp_nav_menu( array(
						'container_class'	=> 'menu-container',
						'menu_id'			=> 'menu-secondary2',
						'menu_class'		=> 'nav-menu one-level secondary',
						'fallback_cb'		=> false,
						'theme_location'	=> 'secondary2',
						'depth'				=> 1,
					) );
				?>

				<?php fastfood_get_sidebar( 'footer', true ); ?>

				<?php fastfood_credits(); ?>

				<?php fastfood_hook_footer_bottom(); ?>

			</div><!-- close footer -->

			<?php fastfood_hook_footer_after(); ?>

		</div><!-- close main -->

		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

		<?php fastfood_hook_body_bottom(); ?>

		<?php fastfood_get_sidebar( 'hidden', true ); ?>

		<?php wp_footer(); ?>

	</body>

</html>