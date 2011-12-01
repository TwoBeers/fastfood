<!-- here should be the footer widget area -->
<?php
	/* The footer widget area is triggered if any of the areas have widgets. */
	if ( ! is_active_sidebar( 'first-footer-widget-area' ) && ! is_active_sidebar( 'second-footer-widget-area' ) && ! is_active_sidebar( 'third-footer-widget-area'  ) && ! is_active_sidebar( 'fourth-footer-widget-area' ) ) {
		return;
	}
?>

<div id="footer-widget-area">
	<div class="fixfloat"><?php fastfood_hook_before_footer_sidebar_content(); ?></div>

	<div id="first_fwa" class="widget-area">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) dynamic_sidebar( 'first-footer-widget-area' ); ?>
	</div><!-- #first .widget-area -->

	<div id="second_fwa" class="widget-area">
			<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) dynamic_sidebar( 'second-footer-widget-area' ); ?>
	</div><!-- #second .widget-area -->

	<div id="third_fwa" class="widget-area">
			<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) dynamic_sidebar( 'third-footer-widget-area' ); ?>
	</div><!-- #third .widget-area -->

	<div class="fixfloat"><?php fastfood_hook_after_footer_sidebar_content(); ?></div>

</div><!-- #footer-widget-area -->
