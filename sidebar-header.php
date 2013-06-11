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

<?php fastfood_hook_sidebars_before( 'header' ); ?>

<div id="header-widget-area">

	<?php fastfood_hook_sidebar_top( 'header' ); ?>

	<br class="fixfloat" />

	<?php dynamic_sidebar( 'header-widget-area' ); ?>

	<br class="fixfloat" />

	<?php fastfood_hook_sidebar_bottom( 'header' ); ?>

</div><!-- #header-widget-area -->

<?php fastfood_hook_sidebars_after( 'header' ); ?>
