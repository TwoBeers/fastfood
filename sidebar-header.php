<?php
/**
 * sidebar-header.php
 *
 * Template part file that contains the header widget area
 *
 * @package fastfood
 * @since 0.15
 */
?>

<!-- here should be the Header widget area -->
<?php
	/* The Header widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'header-widget-area'  ) )
		return;
?>

<div id="header-widget-area">

	<?php fastfood_hook_sidebar_top(); ?>

	<?php dynamic_sidebar( 'header-widget-area' ); ?>

	<?php fastfood_hook_sidebar_bottom(); ?>

	<br class="fixfloat">

</div><!-- #header-widget-area -->
