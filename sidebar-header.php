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

<?php fastfood_hook_sidebars_before(); ?>

<?php fastfood_hook_this_sidebar_before( 'header' ); ?>

<div id="header-widget-area">

	<div class="fixfloat">

		<?php fastfood_hook_sidebar_top(); ?>

		<?php fastfood_hook_this_sidebar_top( 'header' ); ?>

	</div>

	<?php dynamic_sidebar( 'header-widget-area' ); ?>

	<div class="fixfloat">

		<?php fastfood_hook_this_sidebar_bottom( 'header' ); ?>

		<?php fastfood_hook_sidebar_bottom(); ?>

	</div> 

</div><!-- #header-widget-area -->

<?php fastfood_hook_this_sidebar_after( 'header' ); ?>

<?php fastfood_hook_sidebars_after(); ?>
