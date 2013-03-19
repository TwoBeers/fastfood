<?php
/**
 * The mobile theme - Footer template
 *
 * @package fastfood
 * @subpackage mobile
 * @since 0.31
 */
?>

			<?php locate_template( array( 'mobile/sidebar-mobile.php' ), true, false ); ?>

			<?php if ( has_nav_menu( 'mobile' ) ) { ?>

				<?php echo apply_filters( 'fastfood_mobile_filter_seztitle', __('Menu','fastfood') ); ?>

				<?php wp_nav_menu( array( 'menu_class' => 'widget-body', 'container_class' => 'widget_pages tbm-padded', 'fallback_cb' => false, 'theme_location' => 'mobile', 'depth' => 1, 'menu_id' => 'mobile-menu' ) ); ?>

			<?php } ?>

			<?php echo apply_filters( 'fastfood_mobile_filter_seztitle', '&copy; ' . date( 'Y' ) . ' - ' . get_bloginfo( 'name' ) ); ?>


		</div>
		<div id="footer">
			<p id="themecredits">
				<?php echo sprintf( __('Powered by %s and %s','fastfood'), '<a target="_blank" href="http://wordpress.org/" title="WordPress">WordPress</a>', '<a target="_blank" href="http://www.twobeers.net/" title="' . esc_attr( __( 'Visit theme authors homepage','fastfood' ) ) . ' @ twobeers.net">fastfood</a>') ; ?>
			</p>
			<?php wp_loginout(); wp_register( ' | ', '' ); ?><?php echo ' | <a href="' . home_url() . '?mobile_override=desktop">'. __( 'Desktop View', 'fastfood' ) .'</a>'; ?>
</div>
		<?php wp_footer(); ?>

	</body>

</html>