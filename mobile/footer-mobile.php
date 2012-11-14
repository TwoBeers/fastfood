<?php
/**
 * mobile subtheme -> footer
 *
 * @package fastfood
 * @since fastfood 0.31
 */


?>


<?php if ( has_nav_menu( 'mobile' ) ) { ?>

		<?php echo fastfood_mobile_seztitle( 'before' ) . __('Menu','fastfood') . fastfood_mobile_seztitle( 'after' ); ?>
		<?php wp_nav_menu( array( 'menu_class' => 'widget-body', 'container_class' => 'widget_pages tbm-padded', 'fallback_cb' => false, 'theme_location' => 'mobile', 'depth' => 1, 'menu_id' => 'mobile-menu' ) ); ?>

<?php } ?>

			<?php locate_template( array( 'mobile/sidebar-mobile.php' ), true, false ); ?>
			<?php echo fastfood_mobile_seztitle( 'before' ) . '&copy; ' . date( 'Y' ) . ' - ' . get_bloginfo( 'name' ) . fastfood_mobile_seztitle( 'after' ); ?>
			<p id="themecredits">
				<?php printf( __( 'Fastfood theme by %s - Powered by %s', 'fastfood' ), '<a target="_blank" href="http://www.twobeers.net/" title="' . __( 'Visit author homepage', 'fastfood' ) . ' @ TwoBeers.net">TwoBeers Crew</a>', '<a target="_blank" href="http://wordpress.org/" title="WordPress">WordPress</a>' ); ?>
				<br/>
				<br/>
				<?php wp_loginout(); wp_register(' | ', ''); ?><?php echo ' | <a href="' . home_url() . '?mobile_override=desktop">'. __('Desktop View','fastfood') .'</a>'; ?>
			</p>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>