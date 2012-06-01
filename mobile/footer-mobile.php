

<?php if ( has_nav_menu( 'mobile' ) ) { ?>

		<?php echo fastfood_mobile_seztitle( 'before' ) . __('Menu','fastfood') . fastfood_mobile_seztitle( 'after' ); ?>
		<?php wp_nav_menu( array( 'menu_class' => 'widget-body', 'container_class' => 'widget_pages tbm-padded', 'fallback_cb' => false, 'theme_location' => 'mobile', 'depth' => 1, 'menu_id' => 'mobile-menu' ) ); ?>

<?php } ?>

			<?php locate_template( array( 'mobile/sidebar-mobile.php' ), true, false ); ?>
			<?php echo fastfood_mobile_seztitle( 'before' ) . '&copy; ' . date( 'Y' ) . ' - ' . get_bloginfo( 'name' ) . fastfood_mobile_seztitle( 'after' ); ?>
			<p id="themecredits">
				Powered by <a href="http://wordpress.org"><strong>WordPress</strong></a> and <a href="http://www.twobeers.net/"><strong>fastfood</strong></a>
				<br/>
				<br/>
				<?php wp_loginout(); wp_register(' | ', ''); ?><?php echo ' | <a href="' . home_url() . '?mobile_override=desktop">'. __('Desktop View','fastfood') .'</a>'; ?>
			</p>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>